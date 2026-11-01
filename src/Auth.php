<?php

namespace App;

use PDO;

class Auth
{
    /**
     * Inicia la sesión PHP con parámetros de seguridad
     */
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_samesite', 'Lax');
            session_start();
        }
    }

    /**
     * Genera un token CSRF seguro y lo guarda en la sesión
     */
    public static function csrfToken(): string
    {
        self::startSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Valida el token CSRF recibido por POST
     */
    public static function validateCsrf(?string $token): bool
    {
        self::startSession();
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Inicia sesión verificando credenciales y rol específico
     */
    public static function login(string $email, string $password, string $rolEsperado): array
    {
        self::startSession();
        $db = Database::getConnection();

        $stmt = $db->prepare('SELECT id, rol, nombres, apellidos, email, password_hash, escuela_club, activo FROM usuarios WHERE LOWER(email) = LOWER(:email) AND rol = :rol LIMIT 1');
        $stmt->execute([
            ':email' => trim($email),
            ':rol' => $rolEsperado
        ]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            return ['exito' => false, 'mensaje' => 'Correo o contraseña incorrectos para el rol de ' . htmlspecialchars($rolEsperado) . '.'];
        }

        if (!$usuario['activo']) {
            return ['exito' => false, 'mensaje' => 'Esta cuenta se encuentra inactiva o en proceso de retiro.'];
        }

        if (!password_verify($password, $usuario['password_hash'])) {
            return ['exito' => false, 'mensaje' => 'Correo o contraseña incorrectos.'];
        }

        // Regenerar ID de sesión para prevenir Session Fixation
        session_regenerate_id(true);

        $_SESSION['usuario_id'] = (int)$usuario['id'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
        $_SESSION['usuario_nombre'] = $usuario['nombres'] . ' ' . $usuario['apellidos'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_escuela'] = $usuario['escuela_club'];

        return ['exito' => true, 'usuario' => $usuario];
    }

    /**
     * Cierra la sesión activa
     */
    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    /**
     * Verifica si hay un usuario autenticado
     */
    public static function estaAutenticado(): bool
    {
        self::startSession();
        return !empty($_SESSION['usuario_id']) && !empty($_SESSION['usuario_rol']);
    }

    /**
     * Retorna el rol del usuario autenticado o null
     */
    public static function getRol(): ?string
    {
        self::startSession();
        return $_SESSION['usuario_rol'] ?? null;
    }

    /**
     * Retorna el ID del usuario actual
     */
    public static function getId(): ?int
    {
        self::startSession();
        return isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : null;
    }

    /**
     * Retorna la información en sesión del usuario
     */
    public static function getUsuarioActual(): ?array
    {
        self::startSession();
        if (!self::estaAutenticado()) {
            return null;
        }

        return [
            'id' => $_SESSION['usuario_id'],
            'rol' => $_SESSION['usuario_rol'],
            'nombre' => $_SESSION['usuario_nombre'] ?? '',
            'email' => $_SESSION['usuario_email'] ?? '',
            'escuela_club' => $_SESSION['usuario_escuela'] ?? '',
        ];
    }

    /**
     * Exige que el usuario esté autenticado con un rol específico
     */
    public static function requerirRol(string $rol): void
    {
        self::startSession();
        if (!self::estaAutenticado()) {
            $redir = $rol === 'deportista' ? '/index.php?action=login_deportista' : '/index.php?action=login_entrenador';
            header("Location: {$redir}&error=Debe+iniciar+sesion");
            exit;
        }

        if (self::getRol() !== $rol) {
            http_response_code(403);
            die("Acceso denegado: Se requiere rol de " . htmlspecialchars($rol) . ". Tu rol actual es " . htmlspecialchars(self::getRol()));
        }
    }
}
