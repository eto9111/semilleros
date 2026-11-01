<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Usuario;
use App\Entrenador;
use App\Deportista;
use App\Medicion;
use App\Observacion;

echo "== Iniciando carga de datos DEMO para Baloncesto YOLO (FOCUS CESMAG) ==\n";

$db = Database::getConnection();

// Verificar si ya existen los usuarios DEMO
$entrenadorExistente = Usuario::buscarPorEmail('entrenador.demo@cesmag.edu.co');
if ($entrenadorExistente) {
    echo "Los datos DEMO ya existen en la base de datos.\n";
    exit(0);
}

// 1. Crear Entrenador DEMO
$resEnt = Entrenador::registrar([
    'nombres' => 'Carlos Alberto (DEMO)',
    'apellidos' => 'Gómez Nariño',
    'email' => 'entrenador.demo@cesmag.edu.co',
    'password' => 'password123',
    'escuela_club' => 'Club Deportivo Leones de Pasto (DEMO)',
    'especialidad' => 'Entrenador Certificado Baloncesto Formativo',
    'telefono' => '3157894561',
    'terminos_aceptados' => true
]);

if (!$resEnt['exito']) {
    die("Error al crear entrenador DEMO: " . $resEnt['mensaje'] . "\n");
}
$entrenadorId = $resEnt['usuario_id'];
echo " Entrenador DEMO creado (ID: $entrenadorId - entrenador.demo@cesmag.edu.co / password123)\n";

// 2. Crear Deportista 1 DEMO (Mateo)
$resDep1 = Deportista::registrar([
    'nombres' => 'Mateo (DEMO)',
    'apellidos' => 'Rosero Portilla',
    'email' => 'mateo.demo@cesmag.edu.co',
    'password' => 'password123',
    'escuela_club' => 'Club Deportivo Leones de Pasto (DEMO)',
    'fecha_nacimiento' => '2013-05-14',
    'entrenador_id' => $entrenadorId,
    'nombre_acudiente' => 'Sandra Liliana Portilla (Madre DEMO)',
    'autorizacion_acudiente' => true,
    'terminos_aceptados' => true
]);

if (!$resDep1['exito']) {
    die("Error al crear deportista 1: " . $resDep1['mensaje'] . "\n");
}
$dep1Id = $resDep1['usuario_id'];
echo " Deportista 1 DEMO creado (ID: $dep1Id - mateo.demo@cesmag.edu.co / password123)\n";

// 3. Crear Deportista 2 DEMO (Sofía)
$resDep2 = Deportista::registrar([
    'nombres' => 'Sofía (DEMO)',
    'apellidos' => 'Benavides Insuasty',
    'email' => 'sofia.demo@cesmag.edu.co',
    'password' => 'password123',
    'escuela_club' => 'Club Deportivo Leones de Pasto (DEMO)',
    'fecha_nacimiento' => '2012-09-22',
    'entrenador_id' => $entrenadorId,
    'nombre_acudiente' => 'Javier Benavides (Padre DEMO)',
    'autorizacion_acudiente' => true,
    'terminos_aceptados' => true
]);

if (!$resDep2['exito']) {
    die("Error al crear deportista 2: " . $resDep2['mensaje'] . "\n");
}
$dep2Id = $resDep2['usuario_id'];
echo " Deportista 2 DEMO creado (ID: $dep2Id - sofia.demo@cesmag.edu.co / password123)\n";

// 4. Crear mediciones históricas para Mateo (Lanzamiento)
$medicionesMateo = [
    [
        'fecha' => '2026-09-12',
        'tecnica' => 'lanzamiento',
        'val_flexion_rodillas' => 135,
        'eval_flexion_rodillas' => 'por corregir',
        'val_alineacion_codo' => 75,
        'eval_alineacion_codo' => 'por corregir',
        'val_extension_brazo' => 150,
        'eval_extension_brazo' => 'por corregir',
        'val_seguimiento_muneca' => 45,
        'eval_seguimiento_muneca' => 'por corregir',
        'val_estabilidad_base' => 30,
        'eval_estabilidad_base' => 'por corregir',
        'notas' => 'Primera sesión de evaluación en cancha de entrenamiento CESMAG. Se observa tiro con poca flexión y base muy cerrada.'
    ],
    [
        'fecha' => '2026-09-26',
        'tecnica' => 'lanzamiento',
        'val_flexion_rodillas' => 125,
        'eval_flexion_rodillas' => 'por corregir',
        'val_alineacion_codo' => 82,
        'eval_alineacion_codo' => 'adecuada',
        'val_extension_brazo' => 160,
        'eval_extension_brazo' => 'adecuada',
        'val_seguimiento_muneca' => 52,
        'eval_seguimiento_muneca' => 'adecuada',
        'val_estabilidad_base' => 38,
        'eval_estabilidad_base' => 'adecuada',
        'notas' => 'Segunda sesión. Mejoró la apertura de pies y la alineación inicial del codo.'
    ],
    [
        'fecha' => '2026-10-15',
        'tecnica' => 'lanzamiento',
        'val_flexion_rodillas' => 112,
        'eval_flexion_rodillas' => 'adecuada',
        'val_alineacion_codo' => 88,
        'eval_alineacion_codo' => 'adecuada',
        'val_extension_brazo' => 168,
        'eval_extension_brazo' => 'adecuada',
        'val_seguimiento_muneca' => 60,
        'eval_seguimiento_muneca' => 'adecuada',
        'val_estabilidad_base' => 44,
        'eval_estabilidad_base' => 'adecuada',
        'notas' => 'Tercera sesión. Gran progreso en la cadena de impulso desde las piernas hasta el quiebre de muñeca.'
    ],
    // Drible para Mateo
    [
        'fecha' => '2026-10-05',
        'tecnica' => 'drible_inicial',
        'val_flexion_rodillas' => 130,
        'eval_flexion_rodillas' => 'por corregir',
        'val_inclinacion_tronco' => 35,
        'eval_inclinacion_tronco' => 'por corregir',
        'eval_posicion_balon_costado' => 'por corregir',
        'val_altura_bote' => 95,
        'eval_altura_bote' => 'por corregir',
        'eval_cabeza_levantada' => 'por corregir',
        'notas' => 'Bote muy alto por encima de la cadera y la vista fija en el balón.'
    ],
    [
        'fecha' => '2026-10-22',
        'tecnica' => 'drible_inicial',
        'val_flexion_rodillas' => 118,
        'eval_flexion_rodillas' => 'adecuada',
        'val_inclinacion_tronco' => 22,
        'eval_inclinacion_tronco' => 'adecuada',
        'eval_posicion_balon_costado' => 'adecuada',
        'val_altura_bote' => 72,
        'eval_altura_bote' => 'adecuada',
        'eval_cabeza_levantada' => 'adecuada',
        'notas' => 'Bote a la altura de la cintura, mejor control con las yemas de los dedos y cabeza al frente.'
    ]
];

foreach ($medicionesMateo as $m) {
    Medicion::crear($dep1Id, [
        'tecnica' => $m['tecnica'],
        'fecha_medicion' => $m['fecha'],
        'val_flexion_rodillas' => $m['val_flexion_rodillas'] ?? null,
        'eval_flexion_rodillas' => $m['eval_flexion_rodillas'] ?? 'adecuada',
        'val_alineacion_codo' => $m['val_alineacion_codo'] ?? null,
        'eval_alineacion_codo' => $m['eval_alineacion_codo'] ?? 'adecuada',
        'val_extension_brazo' => $m['val_extension_brazo'] ?? null,
        'eval_extension_brazo' => $m['eval_extension_brazo'] ?? 'adecuada',
        'val_seguimiento_muneca' => $m['val_seguimiento_muneca'] ?? null,
        'eval_seguimiento_muneca' => $m['eval_seguimiento_muneca'] ?? 'adecuada',
        'val_estabilidad_base' => $m['val_estabilidad_base'] ?? null,
        'eval_estabilidad_base' => $m['eval_estabilidad_base'] ?? 'adecuada',
        'val_separacion_pies' => $m['val_separacion_pies'] ?? null,
        'eval_separacion_pies' => $m['eval_separacion_pies'] ?? 'adecuada',
        'val_flexion_rodillas_caderas' => $m['val_flexion_rodillas_caderas'] ?? null,
        'eval_flexion_rodillas_caderas' => $m['eval_flexion_rodillas_caderas'] ?? 'adecuada',
        'val_inclinacion_tronco' => $m['val_inclinacion_tronco'] ?? null,
        'eval_inclinacion_tronco' => $m['eval_inclinacion_tronco'] ?? 'adecuada',
        'eval_posicion_manos' => $m['eval_posicion_manos'] ?? 'adecuada',
        'eval_pies_sin_cruzarse' => $m['eval_pies_sin_cruzarse'] ?? 'adecuada',
        'eval_posicion_balon_costado' => $m['eval_posicion_balon_costado'] ?? 'adecuada',
        'val_altura_bote' => $m['val_altura_bote'] ?? null,
        'eval_altura_bote' => $m['eval_altura_bote'] ?? 'adecuada',
        'eval_cabeza_levantada' => $m['eval_cabeza_levantada'] ?? 'adecuada',
        'notas' => $m['notas']
    ]);
}
echo " Mediciones DEMO cargadas para Mateo\n";

// 5. Mediciones para Sofía (Defensa)
$medicionesSofia = [
    [
        'fecha' => '2026-09-18',
        'tecnica' => 'defensa',
        'val_separacion_pies' => 45,
        'eval_separacion_pies' => 'por corregir',
        'val_flexion_rodillas_caderas' => 130,
        'eval_flexion_rodillas_caderas' => 'por corregir',
        'val_inclinacion_tronco' => 38,
        'eval_inclinacion_tronco' => 'por corregir',
        'eval_posicion_manos' => 'por corregir',
        'eval_pies_sin_cruzarse' => 'por corregir',
        'notas' => 'En los desplazamientos laterales tiende a cruzar los pies y las manos caen por debajo de la cintura.'
    ],
    [
        'fecha' => '2026-10-12',
        'tecnica' => 'defensa',
        'val_separacion_pies' => 62,
        'eval_separacion_pies' => 'adecuada',
        'val_flexion_rodillas_caderas' => 108,
        'eval_flexion_rodillas_caderas' => 'adecuada',
        'val_inclinacion_tronco' => 24,
        'eval_inclinacion_tronco' => 'adecuada',
        'eval_posicion_manos' => 'adecuada',
        'eval_pies_sin_cruzarse' => 'adecuada',
        'notas' => 'Centro de gravedad bajo, excelente separación constante de pies y palmas abiertas activas.'
    ]
];

foreach ($medicionesSofia as $m) {
    Medicion::crear($dep2Id, [
        'tecnica' => $m['tecnica'],
        'fecha_medicion' => $m['fecha'],
        'val_separacion_pies' => $m['val_separacion_pies'] ?? null,
        'eval_separacion_pies' => $m['eval_separacion_pies'] ?? 'adecuada',
        'val_flexion_rodillas_caderas' => $m['val_flexion_rodillas_caderas'] ?? null,
        'eval_flexion_rodillas_caderas' => $m['eval_flexion_rodillas_caderas'] ?? 'adecuada',
        'val_inclinacion_tronco' => $m['val_inclinacion_tronco'] ?? null,
        'eval_inclinacion_tronco' => $m['eval_inclinacion_tronco'] ?? 'adecuada',
        'eval_posicion_manos' => $m['eval_posicion_manos'] ?? 'adecuada',
        'eval_pies_sin_cruzarse' => $m['eval_pies_sin_cruzarse'] ?? 'adecuada',
        'notas' => $m['notas']
    ]);
}
echo " Mediciones DEMO cargadas para Sofía\n";

// 6. Observaciones del entrenador
Observacion::crear($entrenadorId, $dep1Id, [
    'fecha_observacion' => '2026-10-16',
    'titulo' => 'Evolución en la mecánica de lanzamiento',
    'observacion' => 'Mateo ha mostrado una mejora notable en la alineación del codo respecto al aro y la extensión final del brazo.',
    'recomendacion' => 'Continuar con series de 20 repeticiones frente al espejo manteniendo el quiebre de muñeca (ganso) 2 segundos después del tiro.'
]);

Observacion::crear($entrenadorId, $dep2Id, [
    'fecha_observacion' => '2026-10-14',
    'titulo' => 'Consolidación de la postura defensiva',
    'observacion' => 'Se corrigió el cruce de pies durante los cambios de dirección en zigzag en media cancha.',
    'recomendacion' => 'Enfatizar el paso de caída inicial y mantener el peso corporal distribuido sobre los metatarsos.'
]);
echo " Observaciones y recomendaciones DEMO cargadas con éxito\n";

echo "\n== Carga completada satisfactoriamente ==\n";
