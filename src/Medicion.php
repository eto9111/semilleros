<?php

namespace App;

use PDO;
use Exception;

class Medicion
{
    /**
     * Retorna la estructura y metadatos de las variables posturales de cada técnica
     */
    public static function getVariablesPorTecnica(): array
    {
        return [
            'lanzamiento' => [
                'nombre' => 'Lanzamiento (Mecánica de Tiro)',
                'descripcion' => 'Postura y cadena cinética en el tiro a canasta',
                'variables' => [
                    'flexion_rodillas' => [
                        'etiqueta' => 'Flexión de rodillas',
                        'unidad' => 'grados (°)',
                        'tipo' => 'numero_y_valoracion',
                        'ejemplo' => 'Ej: 110'
                    ],
                    'alineacion_codo' => [
                        'etiqueta' => 'Alineación del codo',
                        'unidad' => 'grados (°)',
                        'tipo' => 'numero_y_valoracion',
                        'ejemplo' => 'Ej: 90'
                    ],
                    'extension_brazo' => [
                        'etiqueta' => 'Extensión del brazo',
                        'unidad' => 'grados (°)',
                        'tipo' => 'numero_y_valoracion',
                        'ejemplo' => 'Ej: 170'
                    ],
                    'seguimiento_muneca' => [
                        'etiqueta' => 'Seguimiento de la muñeca (Quiebre)',
                        'unidad' => 'grados (°)',
                        'tipo' => 'numero_y_valoracion',
                        'ejemplo' => 'Ej: 60'
                    ],
                    'estabilidad_base' => [
                        'etiqueta' => 'Estabilidad de la base (Separación de pies)',
                        'unidad' => 'centímetros (cm)',
                        'tipo' => 'numero_y_valoracion',
                        'ejemplo' => 'Ej: 45'
                    ],
                ]
            ],
            'defensa' => [
                'nombre' => 'Postura Defensiva',
                'descripcion' => 'Posición básica y balance para desplazamientos defensivos',
                'variables' => [
                    'separacion_pies' => [
                        'etiqueta' => 'Separación de pies',
                        'unidad' => 'centímetros (cm)',
                        'tipo' => 'numero_y_valoracion',
                        'ejemplo' => 'Ej: 60'
                    ],
                    'flexion_rodillas_caderas' => [
                        'etiqueta' => 'Flexión de rodillas y caderas',
                        'unidad' => 'grados (°)',
                        'tipo' => 'numero_y_valoracion',
                        'ejemplo' => 'Ej: 105'
                    ],
                    'inclinacion_tronco' => [
                        'etiqueta' => 'Inclinación del tronco',
                        'unidad' => 'grados (°)',
                        'tipo' => 'numero_y_valoracion',
                        'ejemplo' => 'Ej: 25'
                    ],
                    'posicion_manos' => [
                        'etiqueta' => 'Posición de las manos',
                        'unidad' => 'valoración',
                        'tipo' => 'valoracion_pura',
                        'ejemplo' => ''
                    ],
                    'pies_sin_cruzarse' => [
                        'etiqueta' => 'Pies sin cruzarse en el desplazamiento',
                        'unidad' => 'valoración',
                        'tipo' => 'valoracion_pura',
                        'ejemplo' => ''
                    ],
                ]
            ],
            'drible_inicial' => [
                'nombre' => 'Drible Inicial (Bote)',
                'descripcion' => 'Postura de control, bote y visión periférica',
                'variables' => [
                    'flexion_rodillas' => [
                        'etiqueta' => 'Flexión de rodillas',
                        'unidad' => 'grados (°)',
                        'tipo' => 'numero_y_valoracion',
                        'ejemplo' => 'Ej: 115'
                    ],
                    'inclinacion_tronco' => [
                        'etiqueta' => 'Inclinación del tronco',
                        'unidad' => 'grados (°)',
                        'tipo' => 'numero_y_valoracion',
                        'ejemplo' => 'Ej: 20'
                    ],
                    'posicion_balon_costado' => [
                        'etiqueta' => 'Posición del balón al costado',
                        'unidad' => 'valoración',
                        'tipo' => 'valoracion_pura',
                        'ejemplo' => ''
                    ],
                    'altura_bote' => [
                        'etiqueta' => 'Altura del bote',
                        'unidad' => 'centímetros (cm)',
                        'tipo' => 'numero_y_valoracion',
                        'ejemplo' => 'Ej: 70'
                    ],
                    'cabeza_levantada' => [
                        'etiqueta' => 'Cabeza levantada (visión periférica)',
                        'unidad' => 'valoración',
                        'tipo' => 'valoracion_pura',
                        'ejemplo' => ''
                    ],
                ]
            ]
        ];
    }

    /**
     * Guarda una nueva medición postural
     */
    public static function crear(int $deportistaId, array $datos): array
    {
        $db = Database::getConnection();

        $tecnica = $datos['tecnica'] ?? '';
        $fecha = trim($datos['fecha_medicion'] ?? date('Y-m-d'));
        $notas = trim($datos['notas'] ?? '');
        $origen = $datos['origen'] ?? 'manual';

        $todas = self::getVariablesPorTecnica();
        if (!isset($todas[$tecnica])) {
            return ['exito' => false, 'mensaje' => 'Técnica de baloncesto inválida.'];
        }

        if (empty($fecha)) {
            return ['exito' => false, 'mensaje' => 'La fecha de la medición es obligatoria.'];
        }

        $defVariables = $todas[$tecnica]['variables'];
        $variablesGuardadas = [];

        foreach ($defVariables as $clave => $def) {
            $valorNum = isset($datos['val_' . $clave]) && $datos['val_' . $clave] !== '' ? (float)$datos['val_' . $clave] : null;
            $valoracion = $datos['eval_' . $clave] ?? 'adecuada';

            if (!in_array($valoracion, ['adecuada', 'por corregir'])) {
                $valoracion = 'adecuada';
            }

            $variablesGuardadas[$clave] = [
                'etiqueta' => $def['etiqueta'],
                'unidad' => $def['unidad'],
                'tipo' => $def['tipo'],
                'valor_numerico' => $valorNum,
                'valoracion' => $valoracion
            ];
        }

        try {
            $stmt = $db->prepare('
                INSERT INTO mediciones (deportista_id, fecha_medicion, tecnica, variables, notas, origen) 
                VALUES (:deportista_id, :fecha, :tecnica, :variables, :notas, :origen) 
                RETURNING id
            ');
            $stmt->execute([
                ':deportista_id' => $deportistaId,
                ':fecha' => $fecha,
                ':tecnica' => $tecnica,
                ':variables' => json_encode($variablesGuardadas, JSON_UNESCAPED_UNICODE),
                ':notas' => $notas,
                ':origen' => $origen
            ]);
            $id = (int)$stmt->fetchColumn();

            return ['exito' => true, 'id' => $id];
        } catch (Exception $e) {
            return ['exito' => false, 'mensaje' => 'Error al guardar medición: ' . $e->getMessage()];
        }
    }

    /**
     * Retorna el historial de mediciones de un deportista
     */
    public static function obtenerHistorial(int $deportistaId, ?string $tecnica = null): array
    {
        $db = Database::getConnection();
        $sql = "SELECT id, deportista_id, fecha_medicion, tecnica, variables, notas, origen, fecha_registro 
                FROM mediciones 
                WHERE deportista_id = :dep_id";
        $params = [':dep_id' => $deportistaId];

        if ($tecnica !== null && $tecnica !== '' && $tecnica !== 'todas') {
            $sql .= " AND tecnica = :tecnica";
            $params[':tecnica'] = $tecnica;
        }

        $sql .= " ORDER BY fecha_medicion DESC, id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $filas = $stmt->fetchAll();

        foreach ($filas as &$f) {
            $f['variables'] = json_decode($f['variables'], true) ?? [];
        }

        return $filas;
    }

    /**
     * Retorna una medición específica por ID
     */
    public static function obtenerPorId(int $medicionId): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM mediciones WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $medicionId]);
        $row = $stmt->fetch();
        if ($row) {
            $row['variables'] = json_decode($row['variables'], true) ?? [];
            return $row;
        }
        return null;
    }

    /**
     * Prepara datos estructurados para las gráficas de Chart.js por técnica a lo largo del tiempo
     */
    public static function prepararDatosGraficas(int $deportistaId, string $tecnica): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT id, fecha_medicion, variables 
            FROM mediciones 
            WHERE deportista_id = :dep_id AND tecnica = :tecnica 
            ORDER BY fecha_medicion ASC, id ASC
        ");
        $stmt->execute([':dep_id' => $deportistaId, ':tecnica' => $tecnica]);
        $mediciones = $stmt->fetchAll();

        $labels = [];
        $series = [];

        $todas = self::getVariablesPorTecnica();
        $variablesDef = $todas[$tecnica]['variables'] ?? [];

        foreach ($variablesDef as $clave => $def) {
            $series[$clave] = [
                'etiqueta' => $def['etiqueta'],
                'unidad' => $def['unidad'],
                'tipo' => $def['tipo'],
                'valores' => []
            ];
        }

        foreach ($mediciones as $m) {
            $labels[] = date('d/m/Y', strtotime($m['fecha_medicion']));
            $vars = json_decode($m['variables'], true) ?? [];

            foreach ($variablesDef as $clave => $def) {
                if ($def['tipo'] === 'numero_y_valoracion') {
                    $valNum = $vars[$clave]['valor_numerico'] ?? null;
                    $series[$clave]['valores'][] = $valNum;
                } else {
                    // Para valoración pura: 1 si adecuada, 0 si por corregir
                    $valScore = isset($vars[$clave]['valoracion']) && $vars[$clave]['valoracion'] === 'adecuada' ? 1 : 0;
                    $series[$clave]['valores'][] = $valScore;
                }
            }
        }

        return [
            'labels' => $labels,
            'series' => $series,
            'total_mediciones' => count($mediciones)
        ];
    }
}
