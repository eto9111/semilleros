<?php

namespace App;

/**
 * Interfaz para el análisis de posturas en baloncesto.
 * Permite desacoplar el origen de los datos (actualmente manual, futuro modelo YOLO Pose).
 */
interface AnalizadorPosturaInterface
{
    /**
     * Analiza un conjunto de datos o captura postural
     *
     * @param array $datos Datos de la técnica, puntos clave o mediciones manuales
     * @return array Resultado estructurado del análisis con estado, ángulos y observaciones
     */
    public function analizar(array $datos): array;

    /**
     * Retorna el tipo de motor de análisis ('manual' o 'yolo')
     */
    public function getTipo(): string;
}
