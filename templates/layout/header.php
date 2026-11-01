<?php
use App\Auth;
$usuario = Auth::getUsuarioActual();
$rol = Auth::getRol();
$paginaActual = $_GET['action'] ?? 'inicio';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina ?? 'Baloncesto YOLO - Semillero FOCUS CESMAG') ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <!-- Chart.js para gráficas de evolución postural -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <!-- Logo y Nombre del Proyecto -->
        <a href="/index.php?action=inicio" class="logo-brand">
            <div class="logo-icon" style="background:none; padding:0; width:48px; height:48px; overflow:hidden; border-radius:50%; box-shadow:0 2px 8px rgba(0,0,0,0.2); border: 2px solid var(--color-primary);">
                <img src="/img/logo_app.png" alt="Logo Baloncesto YOLO" style="width:100%; height:100%; object-fit:cover; display:block;">
            </div>
            <div class="logo-text">
                <h1>Baloncesto YOLO</h1>
                <span>Semillero FOCUS · Univ. CESMAG</span>
            </div>
        </a>

        <!-- Barra de Búsqueda Global en Vivo -->
        <div class="search-container">
            <div class="search-input-wrapper">
                <span class="search-icon"></span>
                <input type="text" id="global-search-input" placeholder="Buscar páginas, técnicas o deportistas..." autocomplete="off">
            </div>
            <div id="search-dropdown" class="search-dropdown"></div>
        </div>

        <!-- Navegación Principal -->
        <nav class="site-nav">
            <ul class="nav-links">
                <li><a href="/index.php?action=inicio" class="<?= $paginaActual === 'inicio' ? 'active' : '' ?>">Inicio</a></li>
                <li><a href="/index.php?action=solucion" class="<?= $paginaActual === 'solucion' ? 'active' : '' ?>">La Solución</a></li>
                <li><a href="/index.php?action=privacidad" class="<?= $paginaActual === 'privacidad' ? 'active' : '' ?>">Privacidad</a></li>

                <?php if ($rol === 'deportista'): ?>
                    <li><a href="/index.php?action=deportista_panel" class="<?= $paginaActual === 'deportista_panel' ? 'active' : '' ?>">Mi Panel</a></li>
                    <li><a href="/index.php?action=deportista_medicion" class="<?= $paginaActual === 'deportista_medicion' ? 'active' : '' ?>">+ Medición</a></li>
                    <li><a href="/index.php?action=deportista_historial" class="<?= $paginaActual === 'deportista_historial' ? 'active' : '' ?>">Historial</a></li>
                <?php elseif ($rol === 'entrenador'): ?>
                    <li><a href="/index.php?action=entrenador_panel" class="<?= $paginaActual === 'entrenador_panel' ? 'active' : '' ?>">Panel Entrenador</a></li>
                <?php else: ?>
                    <li><a href="/index.php?action=login_deportista" class="<?= $paginaActual === 'login_deportista' ? 'active' : '' ?>">Deportista</a></li>
                    <li><a href="/index.php?action=login_entrenador" class="<?= $paginaActual === 'login_entrenador' ? 'active' : '' ?>">Entrenador</a></li>
                <?php endif; ?>
            </ul>

            <?php if ($usuario): ?>
                <div class="user-badge">
                    <span class="role-tag"><?= htmlspecialchars($usuario['rol']) ?></span>
                    <span style="font-weight: 600; font-size: 0.85rem;"><?= htmlspecialchars($usuario['nombre']) ?></span>
                    <a href="/index.php?action=logout" class="btn btn-sm btn-outline" style="padding: 0.2rem 0.6rem; font-size: 0.75rem; border-color: #888; color: #DDD;">Salir</a>
                </div>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="main-content">
<?php if (isset($_GET['exito'])): ?>
    <div class="alert alert-success">
        <span></span>
        <div><?= htmlspecialchars($_GET['exito']) ?></div>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">
        <span></span>
        <div><?= htmlspecialchars($_GET['error']) ?></div>
    </div>
<?php endif; ?>
