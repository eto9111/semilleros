<?php
use App\Auth;
$usuario = Auth::getUsuarioActual();
$esDeportista = Auth::getRol() === 'deportista';
?>

<div class="page-header">
    <div>
        <h2>Privacidad, Protección de Datos y Consentimiento Informado</h2>
        <p>Garantía de derechos para niños, niñas y adolescentes en investigación formativa</p>
    </div>
    <a href="/index.php?action=inicio" class="btn btn-outline btn-sm">← Volver al Inicio</a>
</div>

<!-- AVISO DESTACADO DE PROTECCIÓN INFANTIL -->
<div class="card" style="border-left: 6px solid #28A745; background-color: #FBFDFB;">
    <div style="display: flex; gap: 1rem; align-items: flex-start;">
        <span style="font-size: 2.2rem;"></span>
        <div>
            <h3 style="font-size: 1.3rem; font-weight: 800; color: #1B5E20; margin-bottom: 0.5rem;">
                Protección Prioritaria de los Datos de Menores de Edad
            </h3>
            <p style="color: var(--color-gray-800); line-height: 1.6;">
                La Universidad CESMAG y el Semillero FOCUS están comprometidos con la salvaguarda integral del interés superior del menor. La información recolectada en esta plataforma web se gestiona con los más estrictos estándares éticos, de confidencialidad y bajo el ordenamiento legal colombiano.
            </p>
        </div>
    </div>
</div>

<!-- DETALLE DE PRIVACIDAD EN CUADRÍCULA -->
<div class="grid-2">
    <!-- Qué datos se guardan y para qué -->
    <div class="card">
        <h4 class="card-title"> ¿Qué datos se guardan y para qué?</h4>
        <ul style="padding-left: 1.25rem; line-height: 1.7; color: var(--color-gray-800);">
            <li><strong>Datos de identificación básica:</strong> nombres, apellidos, fecha de nacimiento (para segmentar categoría formativa) y escuela o club de pertenencia.</li>
            <li><strong>Mediciones posturales:</strong> valores numéricos (grados articulares y centímetros de separación) y valoraciones técnicas (adecuada / por corregir).</li>
            <li><strong>Recomendaciones del entrenador:</strong> notas de acompañamiento pedagógico para el fortalecimiento de la técnica.</li>
            <li><strong>Finalidad exclusiva:</strong> apoyo pedagógico individualizado y estudio académico de viabilidad para modelos de visión artificial.</li>
        </ul>
    </div>

    <!-- Quién ve los datos -->
    <div class="card">
        <h4 class="card-title"> ¿Quién tiene acceso a la información?</h4>
        <p style="color: var(--color-gray-800); line-height: 1.7; margin-bottom: 0.75rem;">
            El acceso es estrictamente confidencial y cerrado:
        </p>
        <ul style="padding-left: 1.25rem; line-height: 1.7; color: var(--color-gray-800);">
            <li><strong>El propio deportista:</strong> visualiza sus mediciones, gráficas y notas.</li>
            <li><strong>Su entrenador asignado:</strong> solo el entrenador que el deportista seleccionó al registrarse puede acceder a su expediente.</li>
            <li><strong>Ningún tercero o público general:</strong> el sistema opera 100% en red local, sin conexión a servidores en la nube ni bases de datos públicas.</li>
            <li><strong>No publicación de imágenes:</strong> no se difunden fotografías ni videos con rostros de los menores.</li>
        </ul>
    </div>

    <!-- Principios éticos -->
    <div class="card">
        <h4 class="card-title"> Participación Voluntaria y Riesgo Mínimo</h4>
        <ul style="padding-left: 1.25rem; line-height: 1.7; color: var(--color-gray-800);">
            <li><strong>Participación libre y voluntaria:</strong> el menor y su acudiente pueden retirarse del proyecto en cualquier momento sin penalidad alguna.</li>
            <li><strong>Sin remuneración ni costos:</strong> la participación no genera pagos ni gastos económicos para las familias.</li>
            <li><strong>Investigación sin riesgo o con riesgo mínimo:</strong> no se realizan intervenciones invasivas; se trata exclusivamente del registro de la práctica deportiva habitual de baloncesto.</li>
        </ul>
    </div>

    <!-- Marco Normativo -->
    <div class="card" style="border-left: 5px solid var(--color-primary);">
        <h4 class="card-title" style="color: var(--color-primary-dark);"> Marco Normativo Colombiano</h4>
        <div style="line-height: 1.7; color: var(--color-gray-800);">
            <p style="margin-bottom: 0.6rem;">
                <strong>Ley Estatutaria 1581 de 2012 (Habeas Data):</strong> Regula el derecho constitucional de los ciudadanos a conocer, actualizar y rectificar los datos personales almacenados en bases de datos, con especial énfasis en el Artículo 7 sobre el tratamiento de datos de niños, niñas y adolescentes.
            </p>
            <p>
                <strong>Resolución 8430 de 1993 (Ministerio de Salud):</strong> Establece las pautas éticas para la investigación en seres humanos en Colombia, catalogando esta actividad como investigación sin riesgo físico directo y exigiendo el consentimiento informado de padres y asentimiento del menor.
            </p>
        </div>
    </div>
</div>

<!-- DERECHO AL RETIRO Y BOTÓN DE ELIMINACIÓN -->
<div class="card" style="background-color: #FFFDF9; border: 2px solid var(--color-court-border); margin-top: 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem;">
        <div style="flex: 1; min-width: 300px;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--color-dark); margin-bottom: 0.35rem;">
                Derecho a la Supresión y Retiro de Datos
            </h3>
            <p style="color: var(--color-gray-800); font-size: 0.95rem;">
                En cualquier momento, el deportista o su acudiente legal pueden solicitar la desvinculación total de sus datos y mediciones de la base de datos local.
            </p>
        </div>

        <div>
            <?php if ($esDeportista): ?>
                <button type="button" id="btn-solicitar-eliminacion" class="btn btn-danger btn-lg">
                     Solicitar eliminación de mis datos
                </button>
            <?php else: ?>
                <div style="font-size: 0.88rem; color: var(--color-gray-600); background: #eee; padding: 0.6rem 1rem; border-radius: var(--radius-md);">
                    ℹ Los deportistas autenticados disponen aquí del botón directo para solicitar la eliminación de su expediente.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- MODAL DE SOLICITUD DE ELIMINACIÓN (SOLO PARA DEPORTISTAS) -->
<?php if ($esDeportista): ?>
<div id="modal-solicitar-eliminacion" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; align-items: center; justify-content: center; padding: 1.25rem;">
    <div style="background: white; border-radius: var(--radius-lg); max-width: 500px; width: 100%; padding: 2rem; box-shadow: var(--shadow-lg);">
        <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--color-danger); margin-bottom: 0.75rem;">
             Confirmar Solicitud de Supresión de Datos
        </h3>
        <p style="font-size: 0.92rem; color: var(--color-gray-800); margin-bottom: 1rem;">
            De acuerdo con la Ley 1581 de 2012, al solicitar la eliminación de tus datos, tu cuenta será desvinculada y tus mediciones posturales no se usarán en el semillero FOCUS.
        </p>

        <form action="/index.php?action=solicitar_eliminacion" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Auth::csrfToken() ?>">
            <div class="form-group">
                <label for="motivo_baja" class="form-label">Motivo o comentario (opcional):</label>
                <textarea id="motivo_baja" name="motivo" class="form-control" rows="3" placeholder="Indica el motivo de retiro o solicitud de supresión de datos..."></textarea>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" id="btn-cancelar-modal" class="btn btn-outline" style="border-color: #888; color: #555;">Cancelar</button>
                <button type="submit" class="btn btn-danger">Confirmar Solicitud de Retiro</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
