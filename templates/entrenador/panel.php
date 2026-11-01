<?php
use App\Entrenador;

$busqueda = $_GET['q'] ?? '';
$deportistas = Entrenador::obtenerDeportistasAsignados($usuario['id'], $busqueda);
$totalDeportistas = count($deportistas);
$totalMedicionesGrupo = array_sum(array_column($deportistas, 'total_mediciones'));
$totalObservacionesGrupo = array_sum(array_column($deportistas, 'total_observaciones'));

$imagenesTecnicas = [
    'lanzamiento'    => '/img/tecnica_lanzamiento.png',
    'defensa'        => '/img/tecnica_defensa.png',
    'drible_inicial' => '/img/tecnica_drible.png',
];
?>

<!-- BANNER HERO PANEL ENTRENADOR -->
<div style="position: relative; height: 210px; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 2rem; box-shadow: var(--shadow-md);">
    <img src="/img/yolo_vision_ia.png" alt="Panel Técnico Entrenador" style="width: 100%; height: 100%; object-fit: cover; object-position: center 20%;">
    <div style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(26,26,26,0.92) 50%, rgba(26,26,26,0.35) 100%); display: flex; align-items: center; padding: 2rem;">
        <div>
            <span class="role-tag" style="background-color: var(--color-dark); border: 1px solid #555; font-size: 0.78rem; margin-bottom: 0.5rem; display: inline-block;">Cuerpo Técnico</span>
            <h2 style="color: white; font-size: 1.85rem; font-weight: 800; margin: 0.25rem 0; line-height: 1.2;">
                Panel de Entrenador
            </h2>
            <p style="color: #CCC; margin: 0; font-size: 0.92rem;">
                Prof. <?= htmlspecialchars($usuario['nombre']) ?> · <?= htmlspecialchars($usuario['escuela_club']) ?>
            </p>
        </div>
    </div>
</div>

<!-- MÉTRICAS DEL GRUPO -->
<div class="grid-3" style="margin-bottom: 2rem;">
    <div class="card" style="text-align: center; padding: 1.5rem; border-top: 4px solid var(--color-primary); position: relative; overflow: hidden;">
        <div style="position: absolute; inset: 0; opacity: 0.06; pointer-events: none;">
            <img src="/img/tecnica_lanzamiento.png" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div style="position: relative; z-index: 1;">
            <div style="font-size: 2.2rem; margin-bottom: 0.25rem;"></div>
            <div style="font-size: 2.5rem; font-weight: 800; color: var(--color-primary);"><?= $totalDeportistas ?></div>
            <div style="font-size: 0.82rem; font-weight: 700; color: var(--color-gray-600); text-transform: uppercase; letter-spacing: 0.04em;">Deportistas a Cargo</div>
        </div>
    </div>

    <div class="card" style="text-align: center; padding: 1.5rem; border-top: 4px solid var(--color-dark); position: relative; overflow: hidden;">
        <div style="position: absolute; inset: 0; opacity: 0.06; pointer-events: none;">
            <img src="/img/tecnica_defensa.png" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div style="position: relative; z-index: 1;">
            <div style="font-size: 2.2rem; color: var(--color-primary); margin-bottom: 0.25rem;"></div>
            <div style="font-size: 2.5rem; font-weight: 800; color: var(--color-dark);"><?= $totalMedicionesGrupo ?></div>
            <div style="font-size: 0.82rem; font-weight: 700; color: var(--color-gray-600); text-transform: uppercase; letter-spacing: 0.04em;">Mediciones del Grupo</div>
        </div>
    </div>

    <div class="card" style="text-align: center; padding: 1.5rem; border-top: 4px solid #28A745; position: relative; overflow: hidden;">
        <div style="position: absolute; inset: 0; opacity: 0.06; pointer-events: none;">
            <img src="/img/tecnica_drible.png" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div style="position: relative; z-index: 1;">
            <div style="font-size: 2.2rem; color: #28A745; margin-bottom: 0.25rem;"></div>
            <div style="font-size: 2.5rem; font-weight: 800; color: #155724;"><?= $totalObservacionesGrupo ?></div>
            <div style="font-size: 0.82rem; font-weight: 700; color: var(--color-gray-600); text-transform: uppercase; letter-spacing: 0.04em;">Recomendaciones Emitidas</div>
        </div>
    </div>
</div>

<!-- BUSCADOR + ENCABEZADO LISTA -->
<div class="card card-court-accent" style="margin-bottom: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h3 class="card-title" style="margin-bottom: 0.2rem;">
                <span></span> Mis Deportistas a Cargo
            </h3>
            <p style="font-size: 0.85rem; color: var(--color-gray-600); margin: 0;">
                Solo puedes acceder a los deportistas que te eligieron como entrenador responsable.
            </p>
        </div>
        <form action="/index.php" method="GET" style="display: flex; gap: 0.5rem; max-width: 360px; width: 100%;">
            <input type="hidden" name="action" value="entrenador_panel">
            <input type="text" name="q" class="form-control" placeholder="Buscar por nombre o club..." value="<?= htmlspecialchars($busqueda) ?>" style="font-size: 0.9rem;">
            <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
            <?php if ($busqueda !== ''): ?>
                <a href="/index.php?action=entrenador_panel" class="btn btn-outline btn-sm"></a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- LISTA DE DEPORTISTAS: CARDS CON IMAGEN -->
<?php if (empty($deportistas)): ?>
    <div class="card" style="text-align: center; padding: 3rem; color: var(--color-gray-600);">
        <div style="font-size: 3.5rem; margin-bottom: 0.75rem;"></div>
        <p style="font-size: 1.05rem; font-weight: 600;">
            <?= $busqueda !== '' ? 'Sin resultados para "' . htmlspecialchars($busqueda) . '".' : 'Aún no tienes deportistas asignados.' ?>
        </p>
        <p style="font-size: 0.88rem; margin-top: 0.25rem;">
            Los deportistas aparecerán aquí cuando se registren y te elijan como entrenador responsable.
        </p>
    </div>
<?php else: ?>
    <div class="grid-2">
        <?php foreach ($deportistas as $dep):
            $edadLabel = $dep['edad'] . ' años';
            $iniciales = strtoupper(mb_substr($dep['nombres'], 0, 1) . mb_substr($dep['apellidos'], 0, 1));
        ?>
            <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; border: 1px solid var(--color-court-border);">
                <!-- Franja superior con imagen de fondo y datos del deportista -->
                <div style="position: relative; height: 110px; background: var(--color-dark);">
                    <img src="/img/hero_baloncesto.png" alt="" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.3; position: absolute; inset: 0;">
                    <div style="position: absolute; inset: 0; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 52px; height: 52px; border-radius: 50%; background: var(--color-primary); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 800; color: white; flex-shrink: 0; border: 3px solid rgba(255,255,255,0.3);">
                            <?= htmlspecialchars($iniciales) ?>
                        </div>
                        <div>
                            <div style="color: white; font-weight: 800; font-size: 1.05rem; line-height: 1.2;">
                                <?= htmlspecialchars($dep['nombres'] . ' ' . $dep['apellidos']) ?>
                            </div>
                            <div style="color: #CCC; font-size: 0.8rem; margin-top: 0.2rem;">
                                <?= htmlspecialchars($dep['escuela_club']) ?> · <?= $edadLabel ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div style="padding: 0.9rem 1.25rem; display: flex; gap: 1.5rem; border-bottom: 1px solid var(--color-gray-200);">
                    <div style="text-align: center;">
                        <div style="font-size: 1.4rem; font-weight: 800; color: var(--color-primary);"><?= $dep['total_mediciones'] ?></div>
                        <div style="font-size: 0.72rem; color: var(--color-gray-600); text-transform: uppercase; font-weight: 700;">Mediciones</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 1.4rem; font-weight: 800; color: #28A745;"><?= $dep['total_observaciones'] ?></div>
                        <div style="font-size: 0.72rem; color: var(--color-gray-600); text-transform: uppercase; font-weight: 700;">Retroalimentaciones</div>
                    </div>
                    <div style="text-align: center; flex: 1;">
                        <div style="font-size: 0.95rem; font-weight: 700; color: var(--color-dark);">
                            <?= $dep['ultima_medicion'] ? date('d/m/Y', strtotime($dep['ultima_medicion'])) : '—' ?>
                        </div>
                        <div style="font-size: 0.72rem; color: var(--color-gray-600); text-transform: uppercase; font-weight: 700;">Última Sesión</div>
                    </div>
                </div>

                <!-- Botón acción -->
                <div style="padding: 0.85rem 1.25rem; margin-top: auto;">
                    <a href="/index.php?action=entrenador_ver_deportista&id=<?= $dep['id'] ?>" class="btn btn-primary" style="width: 100%;">
                        Ver Expediente Postural →
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
