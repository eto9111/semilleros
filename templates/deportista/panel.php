<?php
use App\Deportista;
use App\Medicion;
use App\Observacion;

$perfil = Deportista::obtenerPerfil($usuario['id']);
$historial = Medicion::obtenerHistorial($usuario['id']);
$observaciones = Observacion::obtenerPorDeportista($usuario['id']);
$totalMediciones = count($historial);
$ultimaMedicion = !empty($historial) ? $historial[0] : null;

// Técnica más usada
$conteoTecnicas = [];
foreach ($historial as $m) {
    $conteoTecnicas[$m['tecnica']] = ($conteoTecnicas[$m['tecnica']] ?? 0) + 1;
}
arsort($conteoTecnicas);
$tecnicaMasUsada = array_key_first($conteoTecnicas) ?? null;
$nombresTecnicas = ['lanzamiento' => 'Lanzamiento', 'defensa' => 'Postura Defensiva', 'drible_inicial' => 'Drible Inicial'];
$imagenesTecnicas = ['lanzamiento' => '/img/tecnica_lanzamiento.png', 'defensa' => '/img/tecnica_defensa.png', 'drible_inicial' => '/img/tecnica_drible.png'];
?>

<!-- BANNER HERO PANEL DEPORTISTA -->
<div style="position: relative; height: 200px; border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 1.75rem; box-shadow: var(--shadow-md);">
    <img src="/img/hero_baloncesto.png" alt="Baloncesto YOLO Semillero FOCUS" style="width: 100%; height: 100%; object-fit: cover; object-position: center 30%;">
    <div style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(26,26,26,0.88) 45%, transparent 100%); display: flex; align-items: center; padding: 2rem;">
        <div>
            <span class="role-tag" style="font-size: 0.78rem; margin-bottom: 0.5rem; display: inline-block;">Deportista</span>
            <h2 style="color: white; font-size: 1.85rem; font-weight: 800; margin: 0.25rem 0; line-height: 1.2;">
                Bienvenido, <?= htmlspecialchars(explode(' ', $usuario['nombre'])[0]) ?> 
            </h2>
            <p style="color: #FFB885; margin: 0; font-size: 0.92rem;">
                <?= htmlspecialchars($usuario['escuela_club']) ?> · Panel de Seguimiento Postural
            </p>
        </div>
    </div>
    <div style="position: absolute; top: 1rem; right: 1rem; display: flex; gap: 0.5rem;">
        <a href="/index.php?action=deportista_medicion" class="btn btn-primary btn-sm">+ Nueva Medición</a>
        <a href="/index.php?action=deportista_historial" class="btn btn-sm" style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3);">Ver Gráficas →</a>
    </div>
</div>

<!-- MÉTRICAS DE RESUMEN -->
<div class="grid-4" style="margin-bottom: 1.75rem;">
    <div class="card" style="padding: 1.25rem; text-align: center; border-top: 4px solid var(--color-primary);">
        <div style="font-size: 2rem; color: var(--color-primary); margin-bottom: 0.25rem;"></div>
        <div style="font-size: 2rem; font-weight: 800; color: var(--color-dark);"><?= $totalMediciones ?></div>
        <div style="font-size: 0.78rem; color: var(--color-gray-600); text-transform: uppercase; font-weight: 700; letter-spacing: 0.04em;">Mediciones</div>
    </div>

    <div class="card" style="padding: 1.25rem; text-align: center; border-top: 4px solid var(--color-dark);">
        <div style="font-size: 2rem; margin-bottom: 0.25rem;"></div>
        <div style="font-size: 0.95rem; font-weight: 800; color: var(--color-dark); margin-top: 0.35rem; line-height: 1.2;">
            Prof. <?= htmlspecialchars($perfil['entrenador_nombres'] ?? '—') ?>
        </div>
        <div style="font-size: 0.78rem; color: var(--color-gray-600); text-transform: uppercase; font-weight: 700; letter-spacing: 0.04em;">Entrenador</div>
    </div>

    <div class="card" style="padding: 1.25rem; text-align: center; border-top: 4px solid #28A745;">
        <div style="font-size: 2rem; color: #28A745; margin-bottom: 0.25rem;"></div>
        <div style="font-size: 2rem; font-weight: 800; color: var(--color-dark);"><?= count($observaciones) ?></div>
        <div style="font-size: 0.78rem; color: var(--color-gray-600); text-transform: uppercase; font-weight: 700; letter-spacing: 0.04em;">Recomendaciones</div>
    </div>

    <div class="card" style="padding: 1.25rem; text-align: center; border-top: 4px solid #0284C7;">
        <div style="font-size: 2rem; color: #0284C7; margin-bottom: 0.25rem;"></div>
        <div style="font-size: 1rem; font-weight: 800; color: var(--color-dark); margin-top: 0.35rem;">
            <?= $ultimaMedicion ? date('d/m/Y', strtotime($ultimaMedicion['fecha_medicion'])) : '—' ?>
        </div>
        <div style="font-size: 0.78rem; color: var(--color-gray-600); text-transform: uppercase; font-weight: 700; letter-spacing: 0.04em;">Última Sesión</div>
    </div>
</div>

<!-- FILA PRINCIPAL: Técnica más usada + Últimas observaciones -->
<div class="grid-2" style="margin-bottom: 1.75rem;">

    <!-- Técnica más frecuente con imagen -->
    <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
        <?php if ($tecnicaMasUsada && isset($imagenesTecnicas[$tecnicaMasUsada])): ?>
            <div style="height: 160px; overflow: hidden; position: relative;">
                <img src="<?= $imagenesTecnicas[$tecnicaMasUsada] ?>" alt="Técnica principal" style="width: 100%; height: 100%; object-fit: cover;">
                <div style="position: absolute; inset: 0; background: linear-gradient(transparent 50%, rgba(26,26,26,0.85)); display: flex; align-items: flex-end; padding: 1rem;">
                    <span style="color: white; font-weight: 800; font-size: 1rem;">
                        Tu técnica más registrada: <?= htmlspecialchars($nombresTecnicas[$tecnicaMasUsada]) ?>
                    </span>
                </div>
            </div>
        <?php else: ?>
            <div style="height: 160px; background: var(--color-court); display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 0.5rem;">
                <span style="font-size: 2.5rem;"></span>
                <span style="color: var(--color-gray-600); font-size: 0.9rem; font-weight: 600;">Aún sin mediciones</span>
            </div>
        <?php endif; ?>

        <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
            <p style="font-size: 0.88rem; color: var(--color-gray-600); margin-bottom: 1rem;">
                Registra mediciones de las tres técnicas para ver tus gráficas de evolución y recibir recomendaciones pedagógicas de tu entrenador.
            </p>
            <a href="/index.php?action=deportista_medicion" class="btn btn-primary" style="width: 100%;">
                 Registrar Nueva Medición
            </a>
        </div>
    </div>

    <!-- Últimas observaciones del entrenador -->
    <div class="card card-court-accent" style="border-left: 5px solid var(--color-primary);">
        <h3 class="card-title" style="font-size: 1.05rem;">
            <span></span> Últimas Recomendaciones de tu Entrenador
        </h3>

        <?php if (empty($observaciones)): ?>
            <div style="text-align: center; padding: 1.5rem 0.5rem; color: var(--color-gray-600);">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;"></div>
                <p style="font-size: 0.88rem;">Tu entrenador aún no ha dejado observaciones. Cuando registres mediciones, él podrá enviarte recomendaciones personalizadas.</p>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <?php foreach (array_slice($observaciones, 0, 3) as $obs): ?>
                    <div style="background-color: var(--color-court-light); border: 1px solid var(--color-court-border); padding: 0.9rem 1rem; border-radius: var(--radius-md);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.4rem; gap: 0.5rem; flex-wrap: wrap;">
                            <strong style="font-size: 0.92rem; color: var(--color-dark);"><?= htmlspecialchars($obs['titulo']) ?></strong>
                            <span class="badge badge-primary" style="font-size: 0.65rem; white-space: nowrap;"><?= date('d/m/Y', strtotime($obs['fecha_observacion'])) ?></span>
                        </div>
                        <div style="background: white; padding: 0.6rem 0.8rem; border-radius: var(--radius-sm); border-left: 3px solid #28A745; font-size: 0.82rem; color: var(--color-dark);">
                             <?= htmlspecialchars(mb_strimwidth($obs['recomendacion'], 0, 110, '...')) ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (count($observaciones) > 3): ?>
                    <a href="/index.php?action=deportista_historial" style="font-size: 0.85rem; color: var(--color-primary); font-weight: 700; text-decoration: none; text-align: center; display: block;">
                        Ver todas las recomendaciones (<?= count($observaciones) ?>) →
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ÚLTIMAS MEDICIONES CON IMAGEN DE FONDO SUTIL -->
<div class="card" style="position: relative; overflow: hidden;">
    <div style="position: absolute; right: -40px; top: -40px; width: 220px; height: 220px; opacity: 0.05; pointer-events: none;">
        <img src="/img/hero_baloncesto.png" alt="" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
        <h3 class="card-title" style="margin-bottom: 0; position: relative; z-index: 1;">
            <span></span> Últimas Mediciones
        </h3>
        <a href="/index.php?action=deportista_historial" class="btn btn-outline btn-sm">Ver todas y gráficas →</a>
    </div>

    <?php if (empty($historial)): ?>
        <div style="text-align: center; padding: 2.5rem; color: var(--color-gray-600);">
            <img src="/img/tecnica_lanzamiento.png" alt="" style="width: 120px; height: 80px; object-fit: cover; border-radius: var(--radius-md); opacity: 0.5; margin-bottom: 1rem; display: block; margin-left: auto; margin-right: auto;">
            <p style="font-size: 0.95rem; font-weight: 600;">Aún no has registrado ninguna medición postural.</p>
            <a href="/index.php?action=deportista_medicion" class="btn btn-primary" style="margin-top: 0.75rem;">+ Registrar Mi Primera Medición</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Técnica</th>
                        <th>Variables Destacadas</th>
                        <th>Notas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($historial, 0, 5) as $med): ?>
                        <tr>
                            <td><strong><?= date('d/m/Y', strtotime($med['fecha_medicion'])) ?></strong></td>
                            <td>
                                <?php
                                $iconos = ['lanzamiento' => '', 'defensa' => '', 'drible_inicial' => ''];
                                $estilos = ['lanzamiento' => 'badge-primary', 'defensa' => '', 'drible_inicial' => ''];
                                $bgExtra = ['defensa' => 'background:#2C3E50; color:#fff;', 'drible_inicial' => 'background:#D35400; color:#fff;'];
                                $ic = $iconos[$med['tecnica']] ?? '';
                                $bg = $bgExtra[$med['tecnica']] ?? '';
                                $cls = $estilos[$med['tecnica']] ?? '';
                                ?>
                                <span class="badge <?= $cls ?>" style="<?= $bg ?>">
                                    <?= $ic ?> <?= htmlspecialchars($nombresTecnicas[$med['tecnica']] ?? $med['tecnica']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 0.2rem; font-size: 0.82rem;">
                                    <?php foreach (array_slice($med['variables'], 0, 2) as $var): ?>
                                        <div>
                                            <strong><?= htmlspecialchars($var['etiqueta']) ?>:</strong>
                                            <?= $var['valor_numerico'] !== null ? htmlspecialchars($var['valor_numerico']) . ' ' . $var['unidad'] : '' ?>
                                            <span class="badge <?= $var['valoracion'] === 'adecuada' ? 'badge-success' : 'badge-warning' ?>" style="font-size:0.62rem;">
                                                <?= htmlspecialchars($var['valoracion']) ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td style="font-size: 0.82rem; color: var(--color-gray-800); max-width: 200px;">
                                <?= !empty($med['notas']) ? htmlspecialchars(mb_strimwidth($med['notas'], 0, 55, '...')) : '<em style="color:#aaa">Sin notas</em>' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
