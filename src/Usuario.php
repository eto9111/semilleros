<?php

namespace App;

use PDO;

class Usuario
{
    /**
     * Busca usuario por email
     */
    public static function buscarPorEmail(string $email): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM usuarios WHERE LOWER(email) = LOWER(:email) LIMIT 1');
        $stmt->execute([':email' => trim($email)]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Busca usuario por ID
     */
    public static function buscarPorId(int $id): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT id, rol, nombres, apellidos, email, escuela_club, terminos_aceptados, fecha_registro, activo FROM usuarios WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }
}
