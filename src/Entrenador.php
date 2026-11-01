<?php

namespace App;

use PDO;
use Exception;

class Entrenador
{
    /**
     * Registra un nuevo entrenador en la base de datos
     */
    public static function registrar(array $datos): array
    {
        $db = Database::getConnection();

        // Validaciones básicas
        $nombres = trim($datos['nombres'] ?? '');
        $apellidos = trim($datos['apellidos'] ?? '');
        $email = trim($datos['email'] ?? '');
        $password = $datos['password'] ?? '';
        $escuela = trim($datos['escuela_club'] ?? '');
        $aceptaTerminos = !empty($datos['terminos_aceptados']);

        if ($nombres === '' || $apellidos === '' || $email === '' || $password === '' || $escuela === '') {
            return ['exito' => false, 'mensaje' => 'Todos los campos marcados son obligatorios.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['exito' => false, 'mensaje' => 'El correo electrónico no tiene un formato válido.'];
        }

        if (strlen($password) < 6) {
            return ['exito' => false, 'mensaje' => 'La contraseña debe tener al menos 6 caracteres.'];
        }

        if (!$aceptaTerminos) {
            return ['exito' => false, 'mensaje' => 'Debe leer y aceptar el aviso de privacidad y consentimiento informado.'];
        }

        // Verificar correo único
        if (Usuario::buscarPorEmail($email)) {
            return ['exito' => false, 'mensaje' => 'El correo electrónico ya se encuentra registrado.'];
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $db->beginTransaction();

            $stmtUser = $db->prepare('INSERT INTO usuarios (rol, nombres, apellidos, email, password_hash, escuela_club, terminos_aceptados) 
                                      VALUES (:rol, :nombres, :apellidos, :email, :pass, :escuela, :terminos) RETURNING id');
            $stmtUser->execute([
                ':rol' => 'entrenador',
                ':nombres' => $nombres,
                ':apellidos' => $apellidos,
                ':email' => $email,
                ':pass' => $passwordHash,
                ':escuela' => $escuela,
                ':terminos' => true
            ]);
            $usuarioId = (int)$stmtUser->fetchColumn();

            $stmtEnt = $db->prepare('INSERT INTO entrenadores (usuario_id, especialidad, telefono) VALUES (:id, :esp, :tel)');
            $stmtEnt->execute([
                ':id' => $usuarioId,
                ':esp' => trim($datos['especialidad'] ?? 'Entrenador de Baloncesto Formativo'),
                ':tel' => trim($datos['telefono'] ?? '')
            ]);

            $db->commit();
            return ['exito' => true, 'usuario_id' => $usuarioId];
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return ['exito' => false, 'mensaje' => 'Error al registrar entrenador: ' . $e->getMessage()];
        }
    }

    /**
     * Retorna la lista de todos los entrenadores activos (para selector en registro de deportistas)
     */
    public static function obtenerTodos(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT u.id, u.nombres, u.apellidos, u.escuela_club, e.especialidad
            FROM entrenadores e
            INNER JOIN usuarios u ON u.id = e.usuario_id
            WHERE u.activo = TRUE
            ORDER BY u.apellidos ASC, u.nombres ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Retorna los deportistas asignados a un entrenador, con filtro opcional de búsqueda
     */
    public static function obtenerDeportistasAsignados(int $entrenadorId, ?string $busqueda = null): array
    {
        $db = Database::getConnection();
        $sql = "
            SELECT 
                u.id,
                u.nombres,
                u.apellidos,
                u.email,
                u.escuela_club,
                d.fecha_nacimiento,
                d.nombre_acudiente,
                EXTRACT(YEAR FROM AGE(CURRENT_DATE, d.fecha_nacimiento)) AS edad,
                (SELECT COUNT(*) FROM mediciones m WHERE m.deportista_id = u.id) AS total_mediciones,
                (SELECT MAX(fecha_medicion) FROM mediciones m WHERE m.deportista_id = u.id) AS ultima_medicion,
                (SELECT COUNT(*) FROM observaciones o WHERE o.deportista_id = u.id AND o.entrenador_id = :entrenador_id) AS total_observaciones
            FROM deportistas d
            INNER JOIN usuarios u ON u.id = d.usuario_id
            WHERE d.entrenador_id = :entrenador_id AND u.activo = TRUE
        ";

        $params = [':entrenador_id' => $entrenadorId];

        if ($busqueda !== null && trim($busqueda) !== '') {
            $sql .= " AND (LOWER(u.nombres) LIKE :busq OR LOWER(u.apellidos) LIKE :busq OR LOWER(u.escuela_club) LIKE :busq)";
            $params[':busq'] = '%' . strtolower(trim($busqueda)) . '%';
        }

        $sql .= " ORDER BY u.apellidos ASC, u.nombres ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Valida si un deportista pertenece a un entrenador determinado (Seguridad estricta en servidor)
     */
    public static function esEntrenadorDe(int $entrenadorId, int $deportistaId): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT 1 FROM deportistas WHERE usuario_id = :dep_id AND entrenador_id = :ent_id");
        $stmt->execute([':dep_id' => $deportistaId, ':ent_id' => $entrenadorId]);
        return (bool)$stmt->fetchColumn();
    }
}
