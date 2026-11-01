<?php

namespace App;

class Buscador
{
    /**
     * Realiza búsqueda en vivo sobre rutas y (si es entrenador) sobre sus deportistas
     */
    public static function buscar(string $termino, ?string $rolUsuario = null, ?int $entrenadorId = null): array
    {
        $termino = mb_strtolower(trim($termino), 'UTF-8');
        if ($termino === '') {
            return [];
        }

        $resultados = [];

        // 1. Cargar catálogo de rutas desde config/rutas.json
        $archivoRutas = dirname(__DIR__) . '/config/rutas.json';
        if (file_exists($archivoRutas)) {
            $rutas = json_decode(file_get_contents($archivoRutas), true) ?? [];

            foreach ($rutas as $item) {
                $rolPermitido = $item['rol_permitido'] ?? 'todos';

                // Filtrar según permisos de sesión
                $puedeVer = false;
                if ($rolPermitido === 'todos') {
                    $puedeVer = true;
                } elseif ($rolPermitido === 'anonimo' && empty($rolUsuario)) {
                    $puedeVer = true;
                } elseif (!empty($rolUsuario) && $rolPermitido === $rolUsuario) {
                    $puedeVer = true;
                }

                if (!$puedeVer) {
                    continue;
                }

                // Coincidencia en título, descripción o palabras clave
                $coincide = false;
                if (str_contains(mb_strtolower($item['titulo'], 'UTF-8'), $termino)) {
                    $coincide = true;
                } elseif (str_contains(mb_strtolower($item['descripcion'], 'UTF-8'), $termino)) {
                    $coincide = true;
                } else {
                    foreach ($item['palabras_clave'] as $kw) {
                        if (str_contains(mb_strtolower($kw, 'UTF-8'), $termino)) {
                            $coincide = true;
                            break;
                        }
                    }
                }

                if ($coincide) {
                    $resultados[] = [
                        'tipo' => 'navegacion',
                        'icono' => '',
                        'titulo' => $item['titulo'],
                        'descripcion' => $item['descripcion'],
                        'ruta' => $item['ruta']
                    ];
                }
            }
        }

        // 2. Si el usuario es entrenador, buscar también entre sus deportistas asignados
        if ($rolUsuario === 'entrenador' && $entrenadorId !== null) {
            $deportistas = Entrenador::obtenerDeportistasAsignados($entrenadorId, $termino);
            foreach ($deportistas as $dep) {
                $nombreCompleto = $dep['nombres'] . ' ' . $dep['apellidos'];
                $resultados[] = [
                    'tipo' => 'deportista',
                    'icono' => '',
                    'titulo' => 'Deportista: ' . $nombreCompleto,
                    'descripcion' => 'Club: ' . $dep['escuela_club'] . ' · Edad: ' . $dep['edad'] . ' años · Mediciones: ' . $dep['total_mediciones'],
                    'ruta' => '/index.php?action=entrenador_ver_deportista&id=' . $dep['id']
                ];
            }
        }

        return $resultados;
    }
}
