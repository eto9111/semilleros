<?php

namespace App;

/**
 * Implementación de análisis manual para el semillero FOCUS.
 * No genera diagnósticos automáticos: el entrenador es quien realiza las correcciones pedagógicas.
 */
class AnalizadorPosturaManual implements AnalizadorPosturaInterface
{
    public function analizar(array $datos): array
    {
        return [
            'motor' => 'manual',
            'estado' => 'registrado',
            'mensaje' => 'análisis automático pendiente',
            'nota' => 'El sistema actúa como herramienta de apoyo. Las observaciones y recomendaciones son formuladas por el entrenador.',
            'datos_procesados' => $datos,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    public function getTipo(): string
    {
        return 'manual';
    }
}
