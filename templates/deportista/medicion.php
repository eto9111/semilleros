<?php
use App\Auth;
use App\Medicion;
$todasTecnicas = Medicion::getVariablesPorTecnica();
$tecnicaPrevia = $_GET['tecnica'] ?? 'lanzamiento';
if (!isset($todasTecnicas[$tecnicaPrevia])) {
    $tecnicaPrevia = 'lanzamiento';
}

$imagenesTecnicas = [
    'lanzamiento'  => '/img/tecnica_lanzamiento.png',
    'defensa'      => '/img/tecnica_defensa.png',
    'drible_inicial' => '/img/tecnica_drible.png',
];
$iconosTecnicas = ['lanzamiento' => '', 'defensa' => '', 'drible_inicial' => ''];
?>

<div class="page-header">
    <div>
        <h2>Nueva Medición Postural Manual</h2>
        <p>Registra tus ángulos y valoraciones técnicas para evaluar tu evolución biomecánica</p>
    </div>
    <a href="/index.php?action=deportista_panel" class="btn btn-outline btn-sm">← Mi Panel</a>
</div>

<div style="display: grid; grid-template-columns: 280px 1fr; gap: 1.5rem; max-width: 1100px; margin: 0 auto; align-items: start;">

    <!-- SIDEBAR: Selector de Técnica con Imágenes -->
    <div style="display: flex; flex-direction: column; gap: 1rem; position: sticky; top: 1rem;">
        <div style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-gray-600); padding: 0 0.25rem;">
            Selecciona la Técnica:
        </div>
        <?php foreach ($todasTecnicas as $clave => $info): ?>
            <a href="/index.php?action=deportista_medicion&tecnica=<?= $clave ?>"
               style="border-radius: var(--radius-md); overflow: hidden; display: block; text-decoration: none; border: 3px solid <?= $clave === $tecnicaPrevia ? 'var(--color-primary)' : 'var(--color-court-border)' ?>; box-shadow: <?= $clave === $tecnicaPrevia ? 'var(--shadow-md)' : 'var(--shadow-sm)' ?>; transition: all 0.2s;">
                <div style="height: 100px; overflow: hidden; position: relative;">
                    <img src="<?= $imagenesTecnicas[$clave] ?>" alt="<?= htmlspecialchars($info['nombre']) ?>" style="width: 100%; height: 100%; object-fit: cover; <?= $clave !== $tecnicaPrevia ? 'filter: grayscale(30%)' : '' ?>">
                    <div style="position: absolute; inset: 0; background: linear-gradient(transparent 30%, rgba(26,26,26,<?= $clave === $tecnicaPrevia ? '0.7' : '0.5' ?>)); display: flex; align-items: flex-end; padding: 0.6rem 0.75rem;">
                        <span style="color: white; font-weight: 800; font-size: 0.9rem;">
                            <?= $iconosTecnicas[$clave] ?> <?= htmlspecialchars($info['nombre']) ?>
                        </span>
                    </div>
                    <?php if ($clave === $tecnicaPrevia): ?>
                        <div style="position: absolute; top: 0.5rem; right: 0.5rem; background: var(--color-primary); color: white; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.4rem; border-radius: 999px; text-transform: uppercase;">
                            Activa
                        </div>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>

        <!-- Mini-info de la técnica seleccionada -->
        <div style="background: var(--color-court-light); border: 1px solid var(--color-court-border); border-radius: var(--radius-md); padding: 0.9rem; font-size: 0.82rem; color: var(--color-gray-800);">
            <strong style="color: var(--color-dark); display: block; margin-bottom: 0.35rem;">
                <?= $iconosTecnicas[$tecnicaPrevia] ?> <?= htmlspecialchars($todasTecnicas[$tecnicaPrevia]['nombre']) ?>
            </strong>
            <?= htmlspecialchars($todasTecnicas[$tecnicaPrevia]['descripcion']) ?>
        </div>
    </div>

    <!-- FORMULARIO PRINCIPAL -->
    <div class="card card-court-accent" style="border-top: 5px solid var(--color-primary);">

        <!-- Fecha + técnica oculta -->
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--color-gray-200);">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 56px; height: 56px; border-radius: var(--radius-md); overflow: hidden; flex-shrink: 0; box-shadow: var(--shadow-sm);">
                    <img src="<?= $imagenesTecnicas[$tecnicaPrevia] ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div>
                    <div style="font-size: 1.2rem; font-weight: 800; color: var(--color-dark);">
                        <?= $iconosTecnicas[$tecnicaPrevia] ?> <?= htmlspecialchars($todasTecnicas[$tecnicaPrevia]['nombre']) ?>
                    </div>
                    <div style="font-size: 0.82rem; color: var(--color-gray-600);">
                        <?= count($todasTecnicas[$tecnicaPrevia]['variables']) ?> variables posturales a registrar
                    </div>
                </div>
            </div>
        </div>

        <form action="/index.php?action=do_guardar_medicion" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Auth::csrfToken() ?>">
            <input type="hidden" name="tecnica" value="<?= htmlspecialchars($tecnicaPrevia) ?>">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="fecha_medicion" class="form-label">Fecha de la Sesión:</label>
                    <input type="date" id="fecha_medicion" name="fecha_medicion" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div style="background: var(--color-court-light); border: 1px solid var(--color-court-border); border-radius: var(--radius-md); padding: 0.75rem 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
                    <span style="font-size: 1.25rem;"></span>
                    <span style="color: var(--color-gray-800);">No existen rangos automáticos. Tu entrenador escribe las correcciones específicas para ti.</span>
                </div>
            </div>

            <!-- VARIABLES DE LA TÉCNICA SELECCIONADA -->
            <h3 style="font-size: 1rem; font-weight: 800; color: var(--color-dark); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <span></span> Variables Posturales a Registrar
            </h3>

            <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                <?php foreach ($todasTecnicas[$tecnicaPrevia]['variables'] as $codVar => $meta): ?>
                    <div style="background: #fff; border: 1px solid var(--color-gray-200); padding: 1rem 1.25rem; border-radius: var(--radius-md); border-left: 4px solid var(--color-court-border);">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.6rem;">
                            <strong style="color: var(--color-dark); font-size: 0.92rem;">
                                <?= htmlspecialchars($meta['etiqueta']) ?>
                            </strong>
                            <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 0.72rem;">
                                <?= htmlspecialchars($meta['unidad']) ?>
                            </span>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                            <?php if ($meta['tipo'] === 'numero_y_valoracion'): ?>
                                <div>
                                    <label class="form-label" style="font-size: 0.78rem; color: #666; margin-bottom: 0.3rem;">
                                        Valor medido (<?= htmlspecialchars($meta['unidad']) ?>):
                                    </label>
                                    <input type="number" step="0.1" name="val_<?= $codVar ?>" class="form-control" placeholder="<?= htmlspecialchars($meta['ejemplo']) ?>">
                                </div>
                            <?php else: ?>
                                <div>
                                    <label class="form-label" style="font-size: 0.78rem; color: #666; margin-bottom: 0.3rem;">Tipo:</label>
                                    <input type="text" class="form-control" value="Evaluación observacional directa" disabled style="background:#f8f9fa; font-size: 0.85rem;">
                                </div>
                            <?php endif; ?>

                            <div>
                                <label class="form-label" style="font-size: 0.78rem; color: #666; margin-bottom: 0.3rem;">Valoración postural:</label>
                                <select name="eval_<?= $codVar ?>" class="form-control">
                                    <option value="adecuada"> Adecuada</option>
                                    <option value="por corregir"> Por corregir</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- NOTAS -->
            <div class="form-group">
                <label for="notas" class="form-label">Notas o sensaciones del deportista (Opcional):</label>
                <textarea id="notas" name="notas" class="form-control" rows="3" placeholder="Ej: Me sentí cómodo con el impulso, pero noté que el codo se abrió un poco en los tiros largos..."></textarea>
            </div>

            <!-- BOTONES -->
            <div style="display: flex; gap: 1rem; align-items: center; justify-content: space-between; flex-wrap: wrap; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--color-gray-200);">
                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-primary btn-lg">
                         Guardar Medición Manual
                    </button>
                    <button type="button" class="btn btn-disabled btn-lg" disabled title="Módulo de visión IA en preparación">
                         Analizar con YOLO (próximamente)
                    </button>
                </div>
                <a href="/index.php?action=deportista_panel" class="btn btn-outline" style="border-color: #bbb; color: #666;">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<style>
@media (max-width: 860px) {
    div[style*="grid-template-columns: 280px 1fr"] { grid-template-columns: 1fr !important; }
    div[style*="position: sticky"] { position: static !important; display: grid !important; grid-template-columns: repeat(3, 1fr) !important; }
}
</style>
