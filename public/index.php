<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Auth;
use App\Deportista;
use App\Entrenador;
use App\Medicion;
use App\Observacion;
use App\Buscador;

Auth::startSession();

$action = $_GET['action'] ?? 'inicio';

/**
 * Función auxiliar para renderizar plantillas con layout
 */
function render(string $viewPath, array $data = []): void
{
    extract($data);
    $usuario = Auth::getUsuarioActual();
    $rol = Auth::getRol();
    require __DIR__ . '/../templates/layout/header.php';
    require __DIR__ . '/../templates/' . $viewPath . '.php';
    require __DIR__ . '/../templates/layout/footer.php';
    exit;
}

/**
 * Redirección con mensaje
 */
function redirect(string $url, ?string $exito = null, ?string $error = null): void
{
    if ($exito) {
        $sep = str_contains($url, '?') ? '&' : '?';
        $url .= $sep . 'exito=' . urlencode($exito);
    }
    if ($error) {
        $sep = str_contains($url, '?') ? '&' : '?';
        $url .= $sep . 'error=' . urlencode($error);
    }
    header("Location: {$url}");
    exit;
}

// -------------------------------------------------------------
// ENRUTADOR PRINCIPAL
// -------------------------------------------------------------
switch ($action) {

    // --- PÁGINAS INFORMATIVAS ---
    case 'inicio':
        render('inicio', ['tituloPagina' => 'Inicio - Baloncesto YOLO (FOCUS CESMAG)']);
        break;

    case 'solucion':
        render('solucion', ['tituloPagina' => 'La Solución - Baloncesto YOLO']);
        break;

    case 'privacidad':
        render('privacidad', ['tituloPagina' => 'Privacidad y Datos - Baloncesto YOLO']);
        break;

    // --- API DE BÚSQUEDA GLOBAL EN VIVO ---
    case 'api_buscar':
        header('Content-Type: application/json; charset=utf-8');
        $q = $_GET['q'] ?? '';
        $resultados = Buscador::buscar($q, Auth::getRol(), Auth::getId());
        echo json_encode($resultados, JSON_UNESCAPED_UNICODE);
        exit;

    // --- AUTENTICACIÓN: DEPORTISTA ---
    case 'login_deportista':
        if (Auth::estaAutenticado() && Auth::getRol() === 'deportista') {
            redirect('/index.php?action=deportista_panel');
        }
        render('auth/login_deportista', ['tituloPagina' => 'Ingreso Deportista']);
        break;

    case 'do_login_deportista':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/index.php?action=login_deportista');
        if (!Auth::validateCsrf($_POST['csrf_token'] ?? '')) {
            redirect('/index.php?action=login_deportista', null, 'Error de seguridad CSRF. Intenta nuevamente.');
        }

        $res = Auth::login($_POST['email'] ?? '', $_POST['password'] ?? '', 'deportista');
        if ($res['exito']) {
            redirect('/index.php?action=deportista_panel', 'Bienvenido a tu panel de seguimiento postural.');
        } else {
            redirect('/index.php?action=login_deportista', null, $res['mensaje']);
        }
        break;

    case 'registro_deportista':
        render('auth/registro_deportista', ['tituloPagina' => 'Registro Deportista']);
        break;

    case 'do_registro_deportista':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/index.php?action=registro_deportista');
        if (!Auth::validateCsrf($_POST['csrf_token'] ?? '')) {
            redirect('/index.php?action=registro_deportista', null, 'Error de seguridad CSRF.');
        }

        $res = Deportista::registrar($_POST);
        if ($res['exito']) {
            Auth::login($_POST['email'], $_POST['password'], 'deportista');
            redirect('/index.php?action=deportista_panel', '¡Registro exitoso! Ya puedes ingresar tus mediciones.');
        } else {
            redirect('/index.php?action=registro_deportista', null, $res['mensaje']);
        }
        break;

    // --- AUTENTICACIÓN: ENTRENADOR ---
    case 'login_entrenador':
        if (Auth::estaAutenticado() && Auth::getRol() === 'entrenador') {
            redirect('/index.php?action=entrenador_panel');
        }
        render('auth/login_entrenador', ['tituloPagina' => 'Ingreso Entrenador']);
        break;

    case 'do_login_entrenador':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/index.php?action=login_entrenador');
        if (!Auth::validateCsrf($_POST['csrf_token'] ?? '')) {
            redirect('/index.php?action=login_entrenador', null, 'Error de seguridad CSRF.');
        }

        $res = Auth::login($_POST['email'] ?? '', $_POST['password'] ?? '', 'entrenador');
        if ($res['exito']) {
            redirect('/index.php?action=entrenador_panel', 'Bienvenido a tu panel técnico de entrenador.');
        } else {
            redirect('/index.php?action=login_entrenador', null, $res['mensaje']);
        }
        break;

    case 'registro_entrenador':
        render('auth/registro_entrenador', ['tituloPagina' => 'Registro Entrenador']);
        break;

    case 'do_registro_entrenador':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/index.php?action=registro_entrenador');
        if (!Auth::validateCsrf($_POST['csrf_token'] ?? '')) {
            redirect('/index.php?action=registro_entrenador', null, 'Error de seguridad CSRF.');
        }

        $res = Entrenador::registrar($_POST);
        if ($res['exito']) {
            Auth::login($_POST['email'], $_POST['password'], 'entrenador');
            redirect('/index.php?action=entrenador_panel', '¡Registro de entrenador exitoso! Bienvenido al semillero.');
        } else {
            redirect('/index.php?action=registro_entrenador', null, $res['mensaje']);
        }
        break;

    case 'logout':
        Auth::logout();
        redirect('/index.php?action=inicio', 'Has cerrado sesión correctamente.');
        break;

    // --- PANEL Y FLUJO DEL DEPORTISTA ---
    case 'deportista_panel':
        Auth::requerirRol('deportista');
        render('deportista/panel', ['tituloPagina' => 'Mi Panel - Deportista']);
        break;

    case 'deportista_medicion':
        Auth::requerirRol('deportista');
        render('deportista/medicion', ['tituloPagina' => 'Nueva Medición Postural']);
        break;

    case 'do_guardar_medicion':
        Auth::requerirRol('deportista');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/index.php?action=deportista_medicion');
        if (!Auth::validateCsrf($_POST['csrf_token'] ?? '')) {
            redirect('/index.php?action=deportista_medicion', null, 'Error de token de seguridad CSRF.');
        }

        $res = Medicion::crear(Auth::getId(), $_POST);
        if ($res['exito']) {
            redirect('/index.php?action=deportista_historial&tecnica=' . urlencode($_POST['tecnica'] ?? 'lanzamiento'), 'Medición postural guardada correctamente.');
        } else {
            redirect('/index.php?action=deportista_medicion', null, $res['mensaje']);
        }
        break;

    case 'deportista_historial':
        Auth::requerirRol('deportista');
        render('deportista/historial', ['tituloPagina' => 'Historial y Gráficas de Postura']);
        break;

    case 'solicitar_eliminacion':
        Auth::requerirRol('deportista');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/index.php?action=privacidad');
        if (!Auth::validateCsrf($_POST['csrf_token'] ?? '')) {
            redirect('/index.php?action=privacidad', null, 'Error de validación CSRF.');
        }

        $res = Deportista::solicitarEliminacionDatos(Auth::getId(), $_POST['motivo'] ?? '');
        if ($res['exito']) {
            redirect('/index.php?action=privacidad', $res['mensaje']);
        } else {
            redirect('/index.php?action=privacidad', null, $res['mensaje']);
        }
        break;

    // --- PANEL Y FLUJO DEL ENTRENADOR ---
    case 'entrenador_panel':
        Auth::requerirRol('entrenador');
        render('entrenador/panel', ['tituloPagina' => 'Panel de Entrenador']);
        break;

    case 'entrenador_ver_deportista':
        Auth::requerirRol('entrenador');
        $deportistaId = (int)($_GET['id'] ?? 0);

        // Validación estricta en servidor: solo puede ver a sus deportistas asignados
        if (!Entrenador::esEntrenadorDe(Auth::getId(), $deportistaId)) {
            http_response_code(403);
            die("Acceso denegado: Este deportista no está asignado a tu cuenta de entrenador.");
        }

        render('entrenador/ver_deportista', [
            'tituloPagina' => 'Expediente del Deportista',
            'deportistaId' => $deportistaId
        ]);
        break;

    case 'do_guardar_observacion':
        Auth::requerirRol('entrenador');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/index.php?action=entrenador_panel');
        if (!Auth::validateCsrf($_POST['csrf_token'] ?? '')) {
            redirect('/index.php?action=entrenador_panel', null, 'Error de token CSRF.');
        }

        $deportistaId = (int)($_POST['deportista_id'] ?? 0);
        $res = Observacion::crear(Auth::getId(), $deportistaId, $_POST);
        if ($res['exito']) {
            redirect("/index.php?action=entrenador_ver_deportista&id={$deportistaId}", 'Recomendación técnica registrada y notificada al deportista.');
        } else {
            redirect("/index.php?action=entrenador_ver_deportista&id={$deportistaId}", null, $res['mensaje']);
        }
        break;

    default:
        http_response_code(404);
        render('inicio', ['tituloPagina' => 'Página no encontrada - Baloncesto YOLO']);
        break;
}
