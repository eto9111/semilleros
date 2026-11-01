<?php

namespace App;

use PDO;
use Exception;

class Observacion
{
    /**
     * Registra una observación y recomendación técnica formulada por el entrenador
     */
    public static function crear(int $entrenadorId, int $deportistaId, array $datos): array
    {
        // Validar que el entrenador realmente tiene asignado este deportista
        if (!Entrenador::esEntrenadorDe($entrenadorId, $deportistaId)) {
            return ['exito' => false, 'mensaje' => 'No tiene permisos para retroalimentar a este deportista.'];
        }

        $titulo = trim($datos['titulo'] ?? '');
        $observacion = trim($datos['observacion'] ?? '');
        $recomendacion = trim($datos['recomendacion'] ?? '');
        $fecha = trim($datos['fecha_observacion'] ?? date('Y-m-d'));
        $medicionId = !empty($datos['medicion_id']) ? (int)$datos['medicion_id'] : null;

        if ($titulo === '' || $observacion === '' || $recomendacion === '') {
            return ['exito' => false, 'mensaje' => 'El título, la observación postural y la recomendación técnica son obligatorios.'];
        }

        $db = Database::getConnection();

        try {
            $stmt = $db->prepare('
                INSERT INTO observaciones (deportista_id, entrenador_id, medicion_id, fecha_observacion, titulo, observacion, recomendacion)
                VALUES (:dep_id, :ent_id, :med_id, :fecha, :titulo, :obs, :rec)
                RETURNING id
            ');
            $stmt->execute([
                ':dep_id' => $deportistaId,
                ':ent_id' => $entrenadorId,
                ':med_id' => $medicionId,
                ':fecha' => $fecha,
                ':titulo' => $titulo,
                ':obs' => $observacion,
                ':rec' => $recomendacion
            ]);
            $id = (int)$stmt->fetchColumn();

            return ['exito' => true, 'id' => $id];
        } catch (Exception $e) {
            return ['exito' => false, 'mensaje' => 'Error al guardar la retroalimentación: ' . $e->getMessage()];
        }
    }

    /**
     * Obtiene todas las observaciones realizadas a un deportista
     */
    public static function obtenerPorDeportista(int $deportistaId): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT 
                o.id,
                o.fecha_observacion,
                o.titulo,
                o.observacion,
                o.recomendacion,
                o.medicion_id,
                o.fecha_registro,
                u.nombres AS entrenador_nombres,
                u.apellidos AS entrenador_apellidos,
                u.escuela_club AS entrenador_escuela
            FROM observaciones o
            INNER JOIN usuarios u ON u.id = o.entrenador_id
            WHERE o.deportista_id = :dep_id
            ORDER BY o.fecha_observacion DESC, o.id DESC
        ");
        $stmt->execute([':dep_id' => $deportistaId]);
        return $stmt->fetchAll();
    }
}
