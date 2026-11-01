<?php
use App\Auth;
use App\Deportista;
use App\Medicion;
use App\Observacion;

$deportistaId = (int)($_GET['id'] ?? 0);
$perfil = Deportista::obtenerPerfil($deportistaId);

if (!$perfil) {
    echo "<div class='alert alert-danger'>Deportista no encontrado.</div>";
    return;
}

$tecnicaGrafica = $_GET['tecnica'] ?? 'lanzamiento';
$todasTecnicas = Medicion::getVariablesPorTecnica();
if (!isset($todasTecnicas[$tecnicaGrafica])) {
    $tecnicaGrafica = 'lanzamiento';
}

$historial = Medicion::obtenerHistorial($deportistaId);
$historialTecnica = Medicion::obtenerHistorial($deportistaId, $tecnicaGrafica);
$datosGrafica = Medicion::prepararDatosGraficas($deportistaId, $tecnicaGrafica);
$observaciones = Observacion::obtenerPorDeportista($deportistaId);

$imagenesTecnicas = [
    'lanzamiento'    => '/img/tecnica_lanzamiento.png',
    'defensa'        => '/img/tecnica_defensa.png',
    'drible_inicial' => '/img/tecnica_drible.png',
];
$iconosTecnicas = ['lanzamiento' => '', 'defensa' => '', 'drible_inicial' => ''];
?>

<!-- BANNER DE PERFIL CON FOTO DE CANCHA -->
<div style="position: relative; height: 180px; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 1.75rem; box-shadow: var(--shadow-md);">
    <img src="<?= $imagenesTecnicas[$tecnicaGrafica] ?? '/img/hero_baloncesto.png' ?>" alt="Técnica" style="width: 100%; height: 100%; object-fit: cover; object-position: center 30%;">
    <div style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(26,26,26,0.92) 50%, rgba(26,26,26,0.3) 100%); display: flex; align-items: center; padding: 1.5rem 2rem; gap: 1.25rem;">
        <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--color-primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800; color: white; flex-shrink: 0; border: 3px solid rgba(255,255,255,0.35);">
            <?= strtoupper(mb_substr($perfil['nombres'], 0, 1) . mb_substr($perfil['apellidos'], 0, 1)) ?>
        </div>
        <div style="flex: 1;">
            <span class="role-tag" style="font-size: 0.72rem; margin-bottom: 0.3rem; display: inline-block;">Deportista</span>
            <h2 style="color: white; font-size: 1.6rem; font-weight: 800; margin: 0.1rem 0; line-height: 1.2;">
                <?= htmlspecialchars($perfil['nombres'] . ' ' . $perfil['apellidos']) ?>
            </h2>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; font-size: 0.82rem; color: #CCC; margin-top: 0.2rem;">
                <span> <?= htmlspecialchars($perfil['escuela_club']) ?></span>
                <span> <?= $perfil['edad'] ?> años</span>
                <?php if (!empty($perfil['nombre_acudiente'])): ?>
                    <span> Acudiente autorizado</span>
                <?php endif; ?>
            </div>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.4rem; align-items: flex-end;">
            <span class="badge badge-primary" style="font-size: 0.8rem; padding: 0.35rem 0.7rem;"><?= count($historial) ?> mediciones</span>
            <a href="/index.php?action=entrenador_panel" class="btn btn-sm" style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3); font-size: 0.8rem;">← Mis Deportistas</a>
        </div>
    </div>
</div>
                <?php endif; ?>
            </div>
        </div>
        <div>
            <span class="badge badge-primary" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                Total Mediciones: <?= count($historial) ?>
            </span>
        </div>
    </div>
</div>

<!-- GRÁFICAS DE EVOLUCIÓN CON CHART.JS -->
<div class="card card-court-accent" style="border-top: 5px solid var(--color-primary); margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
        <h3 class="card-title" style="margin-bottom: 0;">
             Evolución de Postura: <?= htmlspecialchars($todasTecnicas[$tecnicaGrafica]['nombre']) ?>
        </h3>
        <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
            <?php foreach ($todasTecnicas as $clave => $info): ?>
                <a href="/index.php?action=entrenador_ver_deportista&id=<?= $deportistaId ?>&tecnica=<?= $clave ?>"
                   class="btn btn-sm <?= $tecnicaGrafica === $clave ? 'btn-primary' : 'btn-outline' ?>"
                   style="display: flex; align-items: center; gap: 0.4rem; overflow: hidden; <?= $tecnicaGrafica !== $clave ? 'position:relative;' : '' ?>">
                    <?php if ($tecnicaGrafica !== $clave): ?>
                        <img src="<?= $imagenesTecnicas[$clave] ?>" alt="" style="width: 22px; height: 22px; object-fit: cover; border-radius: 3px; opacity: 0.75; flex-shrink: 0;">
                    <?php endif; ?>
                    <?= $iconosTecnicas[$clave] ?> <?= htmlspecialchars($info['nombre']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($datosGrafica && $datosGrafica['total_mediciones'] > 0): ?>
        <p style="font-size: 0.85rem; color: var(--color-gray-600); margin-bottom: 1.5rem;">
            Visualización longitudinal de variables biomecánicas a través de las sesiones de entrenamiento.
        </p>
        <div style="position: relative; width: 100%; height: 380px;">
            <canvas id="graficaEntrenador"></canvas>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('graficaEntrenador');
            if (!ctx) return;

            const labels = <?= json_encode($datosGrafica['labels']) ?>;
            const seriesData = <?= json_encode($datosGrafica['series']) ?>;
            const colores = ['#E8630A', '#1A1A1A', '#0284C7', '#16A34A', '#9333EA', '#D97706'];

            let colorIdx = 0;
            const datasets = [];

            for (const [clave, serie] of Object.entries(seriesData)) {
                if (serie.tipo === 'numero_y_valoracion') {
                    const c = colores[colorIdx % colores.length];
                    colorIdx++;
                    datasets.push({
                        label: `${serie.etiqueta} (${serie.unidad})`,
                        data: serie.valores,
                        borderColor: c,
                        backgroundColor: c + '22',
                        borderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: c,
                        fill: false,
                        tension: 0.25
                    });
                }
            }

            new Chart(ctx, {
                type: 'line',
                data: { labels: labels, datasets: datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', labels: { font: { size: 12, weight: '600' } } }
                    },
                    scales: {
                        y: { title: { display: true, text: 'Magnitud (grados o cm)' } },
                        x: { title: { display: true, text: 'Fecha de la sesión' } }
                    }
                }
            });
        });
        </script>
    <?php else: ?>
        <div class="alert alert-info">
            <span>ℹ</span>
            <div>El deportista aún no cuenta con mediciones registradas para la técnica de <?= htmlspecialchars($todasTecnicas[$tecnicaGrafica]['nombre']) ?>.</div>
        </div>
    <?php endif; ?>
</div>

<!-- VISTA COMPARATIVA DE LAS ÚLTIMAS MEDICIONES -->
<div class="card" style="margin-bottom: 2rem;">
    <h3 class="card-title">
        <span></span> Vista Comparativa de las Últimas Mediciones (<?= htmlspecialchars($todasTecnicas[$tecnicaGrafica]['nombre']) ?>)
    </h3>
    <p style="font-size: 0.85rem; color: var(--color-gray-600); margin-bottom: 1rem;">
        Comparación directa entre las mediciones registradas por el deportista para analizar el progreso.
    </p>

    <?php if (count($historialTecnica) < 1): ?>
        <p style="color: var(--color-gray-600); text-align: center; padding: 1.5rem;">No hay mediciones para comparar en esta técnica.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Variable Postural</th>
                        <?php foreach (array_slice($historialTecnica, 0, 4) as $m): ?>
                            <th>Sesión <?= date('d/m/Y', strtotime($m['fecha_medicion'])) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $varsDef = $todasTecnicas[$tecnicaGrafica]['variables'];
                    foreach ($varsDef as $clave => $def): 
                    ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($def['etiqueta']) ?></strong>
                                <div style="font-size: 0.75rem; color: #777;"><?= htmlspecialchars($def['unidad']) ?></div>
                            </td>
                            <?php foreach (array_slice($historialTecnica, 0, 4) as $m): 
                                $v = $m['variables'][$clave] ?? null;
                            ?>
                                <td>
                                    <?php if ($v): ?>
                                        <div style="font-size: 0.95rem; font-weight: 700;">
                                            <?= $v['valor_numerico'] !== null ? htmlspecialchars($v['valor_numerico']) : '-' ?>
                                        </div>
                                        <span class="badge <?= ($v['valoracion'] ?? '') === 'adecuada' ? 'badge-success' : 'badge-warning' ?>" style="font-size: 0.68rem;">
                                            <?= htmlspecialchars($v['valoracion'] ?? 'N/A') ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #999;">-</span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- FORMULARIO PARA AGREGAR OBSERVACIONES - con imagen lateral -->
<div style="display: grid; grid-template-columns: 220px 1fr; gap: 1.5rem; margin-bottom: 2rem; align-items: start;">

    <!-- Imagen decorativa lateral -->
    <div>
        <div style="border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
            <img src="<?= $imagenesTecnicas[$tecnicaGrafica] ?? '/img/hero_baloncesto.png' ?>" alt="Técnica" style="width: 100%; height: 180px; object-fit: cover; display: block;">
        </div>
        <div style="margin-top: 0.75rem; background: var(--color-court-light); border: 1px solid var(--color-court-border); border-radius: var(--radius-md); padding: 0.75rem; font-size: 0.78rem; color: var(--color-gray-800);">
            <strong style="color: var(--color-dark); display: block; margin-bottom: 0.25rem;">ℹ Recuerda:</strong>
            Esta herramienta apoya tu criterio como entrenador. Las correcciones pedagógicas que registres aquí llegarán directamente al panel del deportista.
        </div>
    </div>

    <div class="card card-court-accent" style="border-top: 5px solid #28A745; margin-bottom: 0;">
        <h3 class="card-title" style="color: #155724;">
            <span></span> Formular Retroalimentación Técnica
        </h3>
        <p style="font-size: 0.88rem; color: var(--color-gray-600); margin-bottom: 1.25rem;">
            Las observaciones que registres aparecerán directamente en el panel personal del deportista.
        </p>

        <form action="/index.php?action=do_guardar_observacion" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Auth::csrfToken() ?>">
            <input type="hidden" name="deportista_id" value="<?= $deportistaId ?>">

            <div class="grid-2">
                <div class="form-group">
                    <label for="titulo" class="form-label">Título de la Retroalimentación:</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" placeholder="Ej: Corrección de codo en tiro libre" required>
                </div>
                <div class="form-group">
                    <label for="fecha_observacion" class="form-label">Fecha:</label>
                    <input type="date" id="fecha_observacion" name="fecha_observacion" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="observacion" class="form-label">Diagnóstico / Observación Postural:</label>
                <textarea id="observacion" name="observacion" class="form-control" rows="3" placeholder="Describe la falla biomecánica o el aspecto observado..." required></textarea>
            </div>

            <div class="form-group">
                <label for="recomendacion" class="form-label">Recomendación Técnica y Ejercicio Sugerido:</label>
                <textarea id="recomendacion" name="recomendacion" class="form-control" rows="3" placeholder="Indica el ejercicio pedagógico específico para corregir..." required></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">
                 Registrar y Enviar al Deportista
            </button>
        </form>
    </div>
</div>

<style>
@media (max-width: 720px) {
    div[style*="grid-template-columns: 220px 1fr"] { grid-template-columns: 1fr !important; }
    div[style*="grid-template-columns: 220px 1fr"] > div:first-child { display: none; }
}
</style>

<!-- HISTORIAL DE OBSERVACIONES EMITIDAS -->
<div class="card">
    <h3 class="card-title">
        <span></span> Historial de Observaciones Entregadas a este Deportista
    </h3>

    <?php if (empty($observaciones)): ?>
        <p style="color: var(--color-gray-600); text-align: center; padding: 1.5rem;">
            Aún no has registrado observaciones para este deportista.
        </p>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($observaciones as $obs): ?>
                <div style="background-color: var(--color-court-light); border: 1px solid var(--color-court-border); padding: 1.25rem; border-radius: var(--radius-md);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap;">
                        <h4 style="font-size: 1.05rem; font-weight: 800; color: var(--color-dark);">
                            <?= htmlspecialchars($obs['titulo']) ?>
                        </h4>
                        <span class="badge badge-primary">
                             <?= date('d/m/Y', strtotime($obs['fecha_observacion'])) ?>
                        </span>
                    </div>

                    <div style="margin-bottom: 0.6rem;">
                        <strong style="font-size: 0.85rem; color: #555;">Observación:</strong>
                        <p style="font-size: 0.92rem; color: var(--color-dark);"><?= nl2br(htmlspecialchars($obs['observacion'])) ?></p>
                    </div>

                    <div style="background: white; padding: 0.75rem 1rem; border-radius: var(--radius-sm); border-left: 3px solid #28A745;">
                        <strong style="font-size: 0.85rem; color: #155724;">Recomendación técnica:</strong>
                        <p style="font-size: 0.92rem; color: var(--color-dark);"><?= nl2br(htmlspecialchars($obs['recomendacion'])) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
