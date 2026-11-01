<?php

namespace App;

use PDO;
use Exception;
use DateTime;

class Deportista
{
    /**
     * Calcula la edad a partir de la fecha de nacimiento
     */
    public static function calcularEdad(string $fechaNacimiento): int
    {
        $nacimiento = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        return $hoy->diff($nacimiento)->y;
    }

    /**
     * Registra un nuevo deportista en la base de datos
     */
    public static function registrar(array $datos): array
    {
        $db = Database::getConnection();

        $nombres = trim($datos['nombres'] ?? '');
        $apellidos = trim($datos['apellidos'] ?? '');
        $email = trim($datos['email'] ?? '');
        $password = $datos['password'] ?? '';
        $escuela = trim($datos['escuela_club'] ?? '');
        $fechaNacimiento = trim($datos['fecha_nacimiento'] ?? '');
        $entrenadorId = (int)($datos['entrenador_id'] ?? 0);
        $aceptaTerminos = !empty($datos['terminos_aceptados']);

        if ($nombres === '' || $apellidos === '' || $email === '' || $password === '' || $escuela === '' || $fechaNacimiento === '') {
            return ['exito' => false, 'mensaje' => 'Todos los campos básicos son obligatorios.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['exito' => false, 'mensaje' => 'El correo electrónico no es válido.'];
        }

        if (strlen($password) < 6) {
            return ['exito' => false, 'mensaje' => 'La contraseña debe tener al menos 6 caracteres.'];
        }

        if ($entrenadorId <= 0) {
            return ['exito' => false, 'mensaje' => 'Debe seleccionar a su entrenador responsable de la lista.'];
        }

        if (!$aceptaTerminos) {
            return ['exito' => false, 'mensaje' => 'Debe aceptar los términos de privacidad y el asentimiento informado.'];
        }

        // Validar si es menor de edad (< 18 años)
        $edad = self::calcularEdad($fechaNacimiento);
        $nombreAcudiente = trim($datos['nombre_acudiente'] ?? '');
        $autorizacionAcudiente = !empty($datos['autorizacion_acudiente']);

        if ($edad < 18) {
            if ($nombreAcudiente === '') {
                return ['exito' => false, 'mensaje' => 'Por ser menor de edad (' . $edad . ' años), se requiere el nombre completo del acudiente o representante legal.'];
            }
            if (!$autorizacionAcudiente) {
                return ['exito' => false, 'mensaje' => 'Por ser menor de edad, el acudiente debe autorizar explícitamente la participación en el semillero.'];
            }
        }

        // Verificar si el correo ya existe
        if (Usuario::buscarPorEmail($email)) {
            return ['exito' => false, 'mensaje' => 'El correo electrónico ya está registrado en el sistema.'];
        }

        // Verificar existencia del entrenador
        $stmtEnt = $db->prepare('SELECT usuario_id FROM entrenadores WHERE usuario_id = :id');
        $stmtEnt->execute([':id' => $entrenadorId]);
        if (!$stmtEnt->fetch()) {
            return ['exito' => false, 'mensaje' => 'El entrenador seleccionado no es válido.'];
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $db->beginTransaction();

            $stmtUser = $db->prepare('INSERT INTO usuarios (rol, nombres, apellidos, email, password_hash, escuela_club, terminos_aceptados) 
                                      VALUES (:rol, :nombres, :apellidos, :email, :pass, :escuela, :terminos) RETURNING id');
            $stmtUser->execute([
                ':rol' => 'deportista',
                ':nombres' => $nombres,
                ':apellidos' => $apellidos,
                ':email' => $email,
                ':pass' => $passwordHash,
                ':escuela' => $escuela,
                ':terminos' => true
            ]);
            $usuarioId = (int)$stmtUser->fetchColumn();

            $stmtDep = $db->prepare('INSERT INTO deportistas (usuario_id, fecha_nacimiento, entrenador_id, nombre_acudiente, autorizacion_acudiente) 
                                     VALUES (:id, :fecha, :entrenador, :acudiente, :autorizacion)');
            $stmtDep->execute([
                ':id' => $usuarioId,
                ':fecha' => $fechaNacimiento,
                ':entrenador' => $entrenadorId,
                ':acudiente' => $edad < 18 ? $nombreAcudiente : null,
                ':autorizacion' => $edad < 18 ? $autorizacionAcudiente : true
            ]);

            $db->commit();
            return ['exito' => true, 'usuario_id' => $usuarioId];
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return ['exito' => false, 'mensaje' => 'Error al registrar deportista: ' . $e->getMessage()];
        }
    }

    /**
     * Obtiene el perfil completo de un deportista con datos de su entrenador
     */
    public static function obtenerPerfil(int $usuarioId): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT 
                u.id,
                u.nombres,
                u.apellidos,
                u.email,
                u.escuela_club,
                u.fecha_registro,
                d.fecha_nacimiento,
                d.nombre_acudiente,
                d.autorizacion_acudiente,
                d.entrenador_id,
                EXTRACT(YEAR FROM AGE(CURRENT_DATE, d.fecha_nacimiento)) AS edad,
                ue.nombres AS entrenador_nombres,
                ue.apellidos AS entrenador_apellidos,
                ue.email AS entrenador_email,
                ue.escuela_club AS entrenador_escuela
            FROM deportistas d
            INNER JOIN usuarios u ON u.id = d.usuario_id
            INNER JOIN usuarios ue ON ue.id = d.entrenador_id
            WHERE u.id = :id AND u.activo = TRUE
            LIMIT 1
        ");
        $stmt->execute([':id' => $usuarioId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Registra solicitud de eliminación / retiro de datos (Habeas Data)
     */
    public static function solicitarEliminacionDatos(int $usuarioId, string $motivo): array
    {
        $db = Database::getConnection();
        try {
            $stmt = $db->prepare("INSERT INTO solicitudes_eliminacion (deportista_id, motivo) VALUES (:id, :motivo)");
            $stmt->execute([
                ':id' => $usuarioId,
                ':motivo' => trim($motivo)
            ]);
            return ['exito' => true, 'mensaje' => 'Solicitud registrada correctamente. De acuerdo con la Ley 1581 de 2012, sus datos serán desvinculados del sistema.'];
        } catch (Exception $e) {
            return ['exito' => false, 'mensaje' => 'Error al registrar solicitud: ' . $e->getMessage()];
        }
    }
}
