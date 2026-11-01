<?php

namespace App;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * Carga variables de entorno desde el archivo .env si existen
     */
    public static function loadEnv(?string $path = null): void
    {
        $envFile = $path ?? dirname(__DIR__) . '/.env';
        if (!file_exists($envFile)) {
            return;
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                // Quitar comillas externas si existen
                if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                    (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                    $value = substr($value, 1, -1);
                }
                if (!array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                }
            }
        }
    }

    /**
     * Retorna la conexión PDO única (Singleton)
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            self::loadEnv();

            $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'localhost';
            $port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '5432';
            $dbname = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'baloncesto_yolo';
            $user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'postgres';
            $pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '1';

            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};options='--client_encoding=UTF8'";

            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);

                // Auto-migración automática si la base de datos está vacía
                self::autoMigrar(self::$instance);

            } catch (PDOException $e) {
                // Mensaje claro para depuración
                die("Error de conexión a la base de datos PostgreSQL: " . htmlspecialchars($e->getMessage()));
            }
        }

        return self::$instance;
    }

    /**
     * Si no existen las tablas (por ejemplo, en el primer despliegue en Render),
     * ejecuta automáticamente schema.sql y los datos DEMO.
     */
    private static function autoMigrar(PDO $pdo): void
    {
        try {
            $check = $pdo->query("SELECT to_regclass('public.usuario')");
            if ($check->fetchColumn() === null) {
                $schemaFile = dirname(__DIR__) . '/database/schema.sql';
                if (file_exists($schemaFile)) {
                    $pdo->exec(file_get_contents($schemaFile));
                }
            }
        } catch (\Throwable $e) {
            error_log("Error en auto-migración: " . $e->getMessage());
        }
    }
}
