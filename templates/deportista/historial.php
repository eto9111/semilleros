<?php
use App\Medicion;

$tecnicaFiltro = $_GET['tecnica'] ?? 'lanzamiento';
$todasTecnicas = Medicion::getVariablesPorTecnica();
if (!isset($todasTecnicas[$tecnicaFiltro]) && $tecnicaFiltro !== 'todas') {
    $tecnicaFiltro = 'lanzamiento';
}

$historial = Medicion::obtenerHistorial($usuario['id'], $tecnicaFiltro);
$datosGrafica = ($tecnicaFiltro !== 'todas') ? Medicion::prepararDatosGraficas($usuario['id'], $tecnicaFiltro) : null;
?>

<div class="page-header">
    <div>
        <h2>Historial y Gráficas de Postura</h2>
        <p>Evolución de variables posturales a través del tiempo · <?= htmlspecialchars($usuario['nombre']) ?></p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="/index.php?action=deportista_medicion" class="btn btn-primary">+ Nueva Medición</a>
        <a href="/index.php?action=deportista_panel" class="btn btn-outline">← Mi Panel</a>
    </div>
</div>

<!-- FILTRO POR TÉCNICA -->
<div class="card" style="padding: 1rem 1.25rem; margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <span style="font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">Filtrar por Técnica:</span>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="/index.php?action=deportista_historial&tecnica=lanzamiento" class="btn btn-sm <?= $tecnicaFiltro === 'lanzamiento' ? 'btn-primary' : 'btn-outline' ?>">
                 Lanzamiento
            </a>
            <a href="/index.php?action=deportista_historial&tecnica=defensa" class="btn btn-sm <?= $tecnicaFiltro === 'defensa' ? 'btn-primary' : 'btn-outline' ?>">
                 Postura Defensiva
            </a>
            <a href="/index.php?action=deportista_historial&tecnica=drible_inicial" class="btn btn-sm <?= $tecnicaFiltro === 'drible_inicial' ? 'btn-primary' : 'btn-outline' ?>">
                 Drible Inicial
            </a>
            <a href="/index.php?action=deportista_historial&tecnica=todas" class="btn btn-sm <?= $tecnicaFiltro === 'todas' ? 'btn-secondary' : 'btn-outline' ?>">
                Todas las técnicas
            </a>
        </div>
    </div>
</div>

<!-- SECCIÓN DE GRÁFICAS DE LÍNEAS CON CHART.JS -->
<?php if ($tecnicaFiltro !== 'todas' && $datosGrafica && $datosGrafica['total_mediciones'] > 0): ?>
    <div class="card card-court-accent" style="border-top: 5px solid var(--color-primary); margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap;">
            <h3 class="card-title" style="margin-bottom: 0;">
                 Gráficas de Líneas: Evolución de <?= htmlspecialchars($todasTecnicas[$tecnicaFiltro]['nombre']) ?>
            </h3>
            <span class="badge badge-primary"><?= $datosGrafica['total_mediciones'] ?> sesiones registradas</span>
        </div>
        <p style="font-size: 0.85rem; color: var(--color-gray-600); margin-bottom: 1.5rem;">
            Cada punto representa la medición en una fecha. Pasa el cursor sobre la gráfica para inspeccionar los grados o centímetros.
        </p>

        <div style="position: relative; width: 100%; height: 380px;">
            <canvas id="graficaPostura"></canvas>
        </div>
    </div>

    <!-- Script de configuración para Chart.js -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('graficaPostura');
        if (!ctx) return;

        const labels = <?= json_encode($datosGrafica['labels']) ?>;
        const seriesData = <?= json_encode($datosGrafica['series']) ?>;

        const colores = [
            '#E8630A', '#1A1A1A', '#0284C7', '#16A34A', '#9333EA', '#D97706'
        ];

        let colorIndex = 0;
        const datasets = [];

        for (const [clave, serie] of Object.entries(seriesData)) {
            // Solo graficar numéricas en grados/cm
            if (serie.tipo === 'numero_y_valoracion') {
                const color = colores[colorIndex % colores.length];
                colorIndex++;

                datasets.push({
                    label: `${serie.etiqueta} (${serie.unidad})`,
                    data: serie.valores,
                    borderColor: color,
                    backgroundColor: color + '22',
                    borderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: color,
                    fill: false,
                    tension: 0.25
                });
            }
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        padding: 10,
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { color: '#E9ECEF' },
                        title: { display: true, text: 'Magnitud (grados o cm)' }
                    },
                    x: {
                        grid: { display: false },
                        title: { display: true, text: 'Fecha de la medición' }
                    }
                }
            }
        });
    });
    </script>
<?php elseif ($tecnicaFiltro !== 'todas'): ?>
    <div class="alert alert-info">
        <span>ℹ</span>
        <div>No hay mediciones suficientes registradas para generar la gráfica en esta técnica. Registra mediciones para comenzar el seguimiento.</div>
    </div>
<?php endif; ?>

<!-- TABLA DETALLADA DE HISTORIAL -->
<div class="card">
    <h3 class="card-title">
        <span></span> Tabla Histórica de Mediciones
    </h3>

    <?php if (empty($historial)): ?>
        <p style="color: var(--color-gray-600); text-align: center; padding: 2rem;">
            No se encontraron mediciones para el filtro seleccionado.
        </p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Técnica</th>
                        <th>Variables Registradas</th>
                        <th>Notas del Deportista</th>
                        <th>Origen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial as $med): ?>
                        <tr>
                            <td style="white-space: nowrap;">
                                <strong> <?= date('d/m/Y', strtotime($med['fecha_medicion'])) ?></strong>
                            </td>
                            <td>
                                <?php if ($med['tecnica'] === 'lanzamiento'): ?>
                                    <span class="badge badge-primary"> Lanzamiento</span>
                                <?php elseif ($med['tecnica'] === 'defensa'): ?>
                                    <span class="badge" style="background:#2C3E50; color:#fff;"> Defensa</span>
                                <?php else: ?>
                                    <span class="badge" style="background:#D35400; color:#fff;"> Drible Inicial</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem; font-size: 0.85rem;">
                                    <?php foreach ($med['variables'] as $k => $var): ?>
                                        <div style="background: #f8f9fa; padding: 0.35rem 0.55rem; border-radius: 4px; border-left: 3px solid <?= $var['valoracion'] === 'adecuada' ? '#28A745' : '#D97706' ?>;">
                                            <div><strong><?= htmlspecialchars($var['etiqueta']) ?>:</strong></div>
                                            <div>
                                                <?= $var['valor_numerico'] !== null ? htmlspecialchars($var['valor_numerico']) . ' ' . $var['unidad'] : '' ?>
                                                <span class="badge <?= $var['valoracion'] === 'adecuada' ? 'badge-success' : 'badge-warning' ?>" style="font-size: 0.65rem;">
                                                    <?= htmlspecialchars($var['valoracion']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td style="max-width: 250px; font-size: 0.85rem; color: var(--color-gray-800);">
                                <?= !empty($med['notas']) ? htmlspecialchars($med['notas']) : '<em>Sin notas adicionales</em>' ?>
                            </td>
                            <td>
                                <span class="badge" style="background: #e2e8f0; color: #334155;">
                                    <?= htmlspecialchars($med['origen']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
