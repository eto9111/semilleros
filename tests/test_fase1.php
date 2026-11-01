<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;

echo "========================================================\n";
echo "PRUEBAS DE VERIFICACIÓN - FASE 1: INSTALACIÓN Y BASE DE DATOS\n";
echo "Semillero FOCUS · Universidad CESMAG\n";
echo "========================================================\n\n";

$errores = 0;

// 1. Sistema Operativo y PHP
echo "[1] Entorno y PHP:\n";
echo "    - SO: " . PHP_OS_FAMILY . " (" . php_uname('s') . ")\n";
echo "    - Versión PHP: " . PHP_VERSION . "\n";
if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
    echo "    ✓ PHP 8.x verificado correctamente.\n";
} else {
    echo "    ✗ ERROR: Se requiere PHP 8.x o superior.\n";
    $errores++;
}

// 2. Extensión pdo_pgsql
echo "\n[2] Extensión pdo_pgsql:\n";
$drivers = PDO::getAvailableDrivers();
if (in_array('pgsql', $drivers)) {
    echo "    ✓ pdo_pgsql está activo y disponible en PHP.\n";
} else {
    echo "    ✗ ERROR: pdo_pgsql no está disponible.\n";
    $errores++;
}

// 3. Conexión a la base de datos vía .env
echo "\n[3] Conexión PDO a PostgreSQL ('baloncesto_yolo'):\n";
try {
    $pdo = Database::getConnection();
    echo "    ✓ Conexión PDO establecida con éxito leyendo desde .env.\n";
} catch (\Throwable $e) {
    echo "    ✗ ERROR de conexión: " . $e->getMessage() . "\n";
    $errores++;
    exit(1);
}

// 4. Verificación de las 11 tablas canónicas
echo "\n[4] Verificación de las 11 tablas en PostgreSQL:\n";
$tablasEsperadas = [
    'escuela',
    'usuario',
    'entrenador',
    'deportista',
    'tecnica',
    'variable_postural',
    'medicion',
    'detalle_medicion',
    'observacion',
    'solicitud_eliminacion',
    'analisis_yolo'
];

$stmt = $pdo->query("
    SELECT table_name 
    FROM information_schema.tables 
    WHERE table_schema = 'public' AND table_type = 'BASE TABLE';
");
$tablasExistentes = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($tablasEsperadas as $tabla) {
    if (in_array($tabla, $tablasExistentes)) {
        echo "    ✓ Tabla '$tabla' existe.\n";
    } else {
        echo "    ✗ FALTA tabla '$tabla'.\n";
        $errores++;
    }
}

// 5. Verificación de la Vista
echo "\n[5] Verificación de la Vista 'v_historial_mediciones':\n";
$stmtV = $pdo->query("
    SELECT table_name 
    FROM information_schema.views 
    WHERE table_schema = 'public' AND table_name = 'v_historial_mediciones';
");
if ($stmtV->fetchColumn()) {
    echo "    ✓ Vista 'v_historial_mediciones' existe y está lista.\n";
} else {
    echo "    ✗ FALTA la vista 'v_historial_mediciones'.\n";
    $errores++;
}

// 6. Verificación de las 15 variables posturales
echo "\n[6] Verificación de las 15 variables posturales (3 técnicas):\n";
$stmtVars = $pdo->query("
    SELECT t.codigo as tecnica, vp.nombre, vp.unidad
    FROM variable_postural vp
    JOIN tecnica t ON vp.id_tecnica = t.id
    ORDER BY t.id, vp.id;
");
$vars = $stmtVars->fetchAll(PDO::FETCH_ASSOC);

$totalVars = count($vars);
echo "    Total variables detectadas: $totalVars / 15\n";
if ($totalVars === 15) {
    echo "    ✓ Las 15 variables posturales oficiales están insertadas:\n";
    foreach ($vars as $v) {
        echo sprintf("      * [%-14s] %-30s (%s)\n", $v['tecnica'], $v['nombre'], $v['unidad']);
    }
} else {
    echo "    ✗ Se esperaban 15 variables y hay $totalVars.\n";
    $errores++;
}

// 7. Verificación del Trigger de Protección al Menor
echo "\n[7] Verificación del Trigger en 'deportista' (protección a menores):\n";
$stmtTrg = $pdo->query("
    SELECT trigger_name 
    FROM information_schema.triggers 
    WHERE event_object_table = 'deportista' AND trigger_name = 'trg_validar_menor_deportista';
");
if ($stmtTrg->fetchColumn()) {
    echo "    ✓ Trigger 'trg_validar_menor_deportista' activo y protegiendo registros.\n";
} else {
    echo "    ✗ FALTA el trigger 'trg_validar_menor_deportista'.\n";
    $errores++;
}

// 8. Verificación de la Escuela DEMO
echo "\n[8] Verificación de Catálogo de Escuela DEMO:\n";
$escuela = $pdo->query("SELECT nombre, ciudad FROM escuela WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
if ($escuela) {
    echo "    ✓ Escuela DEMO ID 1: " . $escuela['nombre'] . " (" . $escuela['ciudad'] . ")\n";
} else {
    echo "    ✗ FALTA la escuela DEMO ID 1.\n";
    $errores++;
}

// Resumen Final
echo "\n========================================================\n";
if ($errores === 0) {
    echo "RESULTADO: ¡TODAS LAS PRUEBAS DE LA FASE 1 PASARON CON ÉXITO!\n";
} else {
    echo "RESULTADO: SE ENCONTRARON $errores ERRORES.\n";
}
echo "========================================================\n";
exit($errores === 0 ? 0 : 1);
