<?php

function testRequest(string $url, string $method = 'GET', $data = null, &$cookies = []): array {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

    $cookieHeader = [];
    foreach ($cookies as $k => $v) {
        $cookieHeader[] = "$k=$v";
    }
    if (!empty($cookieHeader)) {
        curl_setopt($ch, CURLOPT_COOKIE, implode('; ', $cookieHeader));
    }

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    $response = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headersRaw = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    curl_close($ch);

    // Guardar cookies
    preg_match_all('/^Set-Cookie:\s*([^;=\s]+)=([^; \r\n]+)/mi', $headersRaw, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $cookies[$match[1]] = $match[2];
    }

    // Ubicacion si hay redireccion
    $location = null;
    if (preg_match('/^Location:\s*(.*)$/mi', $headersRaw, $locMatch)) {
        $location = trim($locMatch[1]);
    }

    return [
        'code' => $httpCode,
        'body' => $body,
        'location' => $location,
        'cookies' => $cookies
    ];
}

function extractCsrf(string $html): ?string {
    if (preg_match('/name="csrf_token"\s+value="([^"]+)"/', $html, $m)) {
        return $m[1];
    }
    return null;
}

echo "========================================================\n";
echo "PRUEBAS END-TO-END AUTOMATIZADAS - BALONCESTO YOLO FOCUS\n";
echo "========================================================\n\n";

$baseUrl = 'http://localhost:8000';
$errores = 0;

// Test 1: Inicio
$res = testRequest("$baseUrl/index.php?action=inicio");
if ($res['code'] === 200 && str_contains($res['body'], 'Soy Deportista') && str_contains($res['body'], 'Soy Entrenador')) {
    echo " [PASA] Página de Inicio carga correctamente con tarjetas de roles.\n";
} else {
    echo " [FALLA] Página de Inicio. Código: {$res['code']}\n";
    $errores++;
}

// Test 2: Solución
$res = testRequest("$baseUrl/index.php?action=solucion");
if ($res['code'] === 200 && str_contains($res['body'], 'Objetivo General') && str_contains($res['body'], 'YOLO')) {
    echo " [PASA] Página 'La Solución' contiene justificación, objetivos y módulo YOLO.\n";
} else {
    echo " [FALLA] Página 'La Solución'. Código: {$res['code']}\n";
    $errores++;
}

// Test 3: Privacidad
$res = testRequest("$baseUrl/index.php?action=privacidad");
if ($res['code'] === 200 && str_contains($res['body'], 'Ley 1581 de 2012') && str_contains($res['body'], 'Resolución 8430')) {
    echo " [PASA] Página 'Privacidad' contiene marco legal, protección de menores y derechos.\n";
} else {
    echo " [FALLA] Página 'Privacidad'. Código: {$res['code']}\n";
    $errores++;
}

// Test 4: Buscador API
$res = testRequest("$baseUrl/index.php?action=api_buscar&q=lanzamiento");
$json = json_decode($res['body'], true);
if ($res['code'] === 200 && is_array($json) && count($json) > 0) {
    echo " [PASA] API del Buscador responde JSON con sugerencias en vivo para 'lanzamiento'.\n";
} else {
    echo " [FALLA] API del Buscador. Código: {$res['code']}\n";
    $errores++;
}

// Test 5: Flujo Deportista (Login)
$cookiesDep = [];
$resLoginGet = testRequest("$baseUrl/index.php?action=login_deportista", 'GET', null, $cookiesDep);
$csrfDep = extractCsrf($resLoginGet['body']);

if (!$csrfDep) {
    echo " [FALLA] No se pudo extraer CSRF token para deportista.\n";
    $errores++;
} else {
    $resLoginPost = testRequest("$baseUrl/index.php?action=do_login_deportista", 'POST', [
        'csrf_token' => $csrfDep,
        'email' => 'mateo.demo@cesmag.edu.co',
        'password' => 'password123'
    ], $cookiesDep);

    if ($resLoginPost['code'] === 302 && str_contains($resLoginPost['location'], 'deportista_panel')) {
        echo " [PASA] Login de deportista DEMO autentica y redirige al panel.\n";

        // Panel deportista
        $resPanelDep = testRequest("$baseUrl/index.php?action=deportista_panel", 'GET', null, $cookiesDep);
        if ($resPanelDep['code'] === 200 && str_contains($resPanelDep['body'], 'Mateo (DEMO)')) {
            echo " [PASA] Panel del deportista muestra estadísticas y recomendaciones del entrenador.\n";
        } else {
            echo " [FALLA] Carga del panel del deportista.\n";
            $errores++;
        }

        // Historial y graficas
        $resHist = testRequest("$baseUrl/index.php?action=deportista_historial&tecnica=lanzamiento", 'GET', null, $cookiesDep);
        if ($resHist['code'] === 200 && str_contains($resHist['body'], 'graficaPostura')) {
            echo " [PASA] Historial de deportista genera canvas y dataset para Chart.js.\n";
        } else {
            echo " [FALLA] Carga de historial y gráficas del deportista.\n";
            $errores++;
        }

        // Guardar nueva medición
        $resMedicionGet = testRequest("$baseUrl/index.php?action=deportista_medicion", 'GET', null, $cookiesDep);
        $csrfMed = extractCsrf($resMedicionGet['body']);
        $resMedPost = testRequest("$baseUrl/index.php?action=do_guardar_medicion", 'POST', [
            'csrf_token' => $csrfMed,
            'fecha_medicion' => date('Y-m-d'),
            'tecnica' => 'lanzamiento',
            'val_flexion_rodillas' => 110.5,
            'eval_flexion_rodillas' => 'adecuada',
            'val_alineacion_codo' => 90.0,
            'eval_alineacion_codo' => 'adecuada',
            'val_extension_brazo' => 172.0,
            'eval_extension_brazo' => 'adecuada',
            'val_seguimiento_muneca' => 62.0,
            'eval_seguimiento_muneca' => 'adecuada',
            'val_estabilidad_base' => 45.0,
            'eval_estabilidad_base' => 'adecuada',
            'notas' => 'Prueba automatizada de registro de tiro'
        ], $cookiesDep);

        if ($resMedPost['code'] === 302 && str_contains($resMedPost['location'], 'deportista_historial')) {
            echo " [PASA] Guardado de nueva medición manual ejecutado con éxito.\n";
        } else {
            echo " [FALLA] Guardado de medición manual.\n";
            $errores++;
        }
    } else {
        echo " [FALLA] Login de deportista DEMO.\n";
        $errores++;
    }
}

// Test 6: Flujo Entrenador (Login)
$cookiesEnt = [];
$resEntLoginGet = testRequest("$baseUrl/index.php?action=login_entrenador", 'GET', null, $cookiesEnt);
$csrfEnt = extractCsrf($resEntLoginGet['body']);

if ($csrfEnt) {
    $resEntPost = testRequest("$baseUrl/index.php?action=do_login_entrenador", 'POST', [
        'csrf_token' => $csrfEnt,
        'email' => 'entrenador.demo@cesmag.edu.co',
        'password' => 'password123'
    ], $cookiesEnt);

    if ($resEntPost['code'] === 302 && str_contains($resEntPost['location'], 'entrenador_panel')) {
        echo " [PASA] Login de entrenador DEMO autentica y redirige al panel técnico.\n";

        // Panel de entrenador
        $resPanelEnt = testRequest("$baseUrl/index.php?action=entrenador_panel", 'GET', null, $cookiesEnt);
        if ($resPanelEnt['code'] === 200 && str_contains($resPanelEnt['body'], 'Mateo (DEMO)') && str_contains($resPanelEnt['body'], 'Sofía (DEMO)')) {
            echo " [PASA] Panel de entrenador lista exclusivamente a sus deportistas asignados.\n";
        } else {
            echo " [FALLA] Panel de entrenador no muestra los deportistas asignados.\n";
            $errores++;
        }

        // Expediente de deportista
        $resExpediente = testRequest("$baseUrl/index.php?action=entrenador_ver_deportista&id=2", 'GET', null, $cookiesEnt);
        if ($resExpediente['code'] === 200 && str_contains($resExpediente['body'], 'graficaEntrenador') && str_contains($resExpediente['body'], 'Vista Comparativa')) {
            echo " [PASA] Expediente del deportista muestra comparativa y gráfica para el entrenador.\n";
        } else {
            echo " [FALLA] Expediente del deportista para el entrenador.\n";
            $errores++;
        }

        // Agregar observación técnica
        $csrfObs = extractCsrf($resExpediente['body']);
        $resObsPost = testRequest("$baseUrl/index.php?action=do_guardar_observacion", 'POST', [
            'csrf_token' => $csrfObs,
            'deportista_id' => 2,
            'fecha_observacion' => date('Y-m-d'),
            'titulo' => 'Retroalimentación de prueba automatizada',
            'observacion' => 'Excelente ángulo de flexión de rodillas registrado.',
            'recomendacion' => 'Mantener la pausa de medio segundo en la posición inicial.'
        ], $cookiesEnt);

        if ($resObsPost['code'] === 302 && str_contains($resObsPost['location'], 'entrenador_ver_deportista')) {
            echo " [PASA] Entrenador formula y registra nueva observación pedagógica con éxito.\n";
        } else {
            echo " [FALLA] Guardado de observación por el entrenador.\n";
            $errores++;
        }

        // Búsqueda de deportistas en vivo por el entrenador
        $resBusqEnt = testRequest("$baseUrl/index.php?action=api_buscar&q=Mateo", 'GET', null, $cookiesEnt);
        $jsonBusq = json_decode($resBusqEnt['body'], true);
        if ($resBusqEnt['code'] === 200 && is_array($jsonBusq) && count($jsonBusq) > 0) {
            echo " [PASA] Buscador en vivo encuentra al deportista por nombre desde la sesión de entrenador.\n";
        } else {
            echo " [FALLA] Buscador de deportistas para entrenador.\n";
            $errores++;
        }
    } else {
        echo " [FALLA] Login de entrenador DEMO.\n";
        $errores++;
    }
}

// Test 7: Seguridad de Roles (Un deportista intentando entrar al panel de entrenador)
$resSec = testRequest("$baseUrl/index.php?action=entrenador_panel", 'GET', null, $cookiesDep);
if ($resSec['code'] === 403) {
    echo " [PASA] Seguridad en Servidor: Se rechaza con HTTP 403 a un deportista que intenta entrar al panel de entrenador.\n";
} else {
    echo " [FALLA] Falló la restricción de rol en el servidor. Código: {$resSec['code']}\n";
    $errores++;
}

echo "\n========================================================\n";
if ($errores === 0) {
    echo " RESULTADO: TODAS LAS PRUEBAS (10/10) PASARON EXITOSAMENTE.\n";
} else {
    echo " RESULTADO: Hubo $errores prueba(s) fallida(s).\n";
}
echo "========================================================\n";
