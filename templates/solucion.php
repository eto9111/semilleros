<div class="page-header">
    <div>
        <h2>La Solución Técnica y Metodológica</h2>
        <p>Proyecto de Investigación Formativa · Semillero FOCUS · Universidad CESMAG (Pasto, Nariño)</p>
    </div>
    <a href="/index.php?action=inicio" class="btn btn-outline btn-sm">← Volver al Inicio</a>
</div>

<!-- PROBLEMA Y JUSTIFICACIÓN -->
<div class="card card-court-accent">
    <h3 class="card-title"> El Problema en las Escuelas de Formación</h3>
    <p style="margin-bottom: 1rem; color: var(--color-gray-800);">
        En las escuelas formativas de baloncesto de Pasto (Nariño), los entrenadores trabajan habitualmente con grupos numerosos (15 a 30 niños simultáneamente). Durante una sesión de entrenamiento resulta sumamente complejo observar y retroalimentar de manera individualizada y simultánea los detalles biomecánicos de cada niño en ejecución dinámica.
    </p>
    <p style="color: var(--color-gray-800);">
        La fijación temprana de posturas inadecuadas en la infancia reduce la efectividad del tiro, disminuye el balance defensivo y puede generar vicios motores o sobrecargas articulares a largo plazo. Se requiere una herramienta tecnológica accesible, económica y local que apoye al entrenador en la toma de datos y el seguimiento evolutivo.
    </p>
</div>

<!-- OBJETIVOS -->
<div class="grid-2">
    <div class="card" style="border-left: 5px solid var(--color-primary);">
        <h3 class="card-title" style="color: var(--color-primary-dark);"> Objetivo General</h3>
        <p style="font-size: 1.05rem; line-height: 1.7; color: var(--color-dark); font-weight: 600;">
            Desarrollar un sistema de apoyo informático basado en el modelo de visión artificial YOLO para la detección y corrección de posturas en las técnicas de lanzamiento, defensa y drible inicial de baloncesto en niños de escuelas de formación de la ciudad de Pasto.
        </p>
    </div>

    <div class="card" style="border-left: 5px solid var(--color-dark);">
        <h3 class="card-title"> Objetivos Específicos</h3>
        <ul style="padding-left: 1.25rem; line-height: 1.8; color: var(--color-gray-800);">
            <li><strong>Caracterizar biomecánicamente</strong> las variables posturales críticas de las técnicas de lanzamiento, defensa y drible inicial en edades formativas.</li>
            <li><strong>Desarrollar la plataforma web local</strong> orientada a objetos (PHP + PostgreSQL) que permita el registro de deportistas, entrenadores y mediciones posturales.</li>
            <li><strong>Diseñar el contrato de integración JSON</strong> para el modelo YOLO Pose, permitiendo que la versión manual actual se conecte transparentemente con el módulo de visión artificial.</li>
            <li><strong>Facilitar la retroalimentación pedagógica</strong> continua mediante un canal exclusivo para observaciones y recomendaciones del entrenador hacia el deportista.</li>
        </ul>
    </div>
</div>

<!-- CÓMO FUNCIONARÁ CON YOLO EN EL FUTURO -->
<div class="card" style="background-color: #FAFAFA; border: 2px solid var(--color-court-border);">
    <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.25rem;">
        <div style="flex: 1.2; min-width: 300px;">
            <h3 class="card-title"> Futura Integración con el Modelo YOLO Pose</h3>
            <p style="color: var(--color-gray-800); line-height: 1.6; margin-bottom: 0.75rem;">
                El proyecto está diseñado bajo un modelo desacoplado utilizando la interfaz <code style="background:#eee; padding:2px 6px; border-radius:4px;">AnalizadorPosturaInterface</code>. En esta versión de investigación formativa, el ingreso de datos es manual. El flujo futuro con visión por computador operará mediante inferencia en tiempo real de YOLO Pose.
            </p>
            <p style="color: var(--color-gray-800); line-height: 1.6;">
                La red neuronal detectará 17 puntos anatómicos clave en el deportista (hombros, codos, muñecas, caderas, rodillas, tobillos), calculando vectores geométricos y alimentando automáticamente el esquema de la base de datos vía el contrato formal JSON.
            </p>
        </div>
        <div style="flex: 1; min-width: 280px; text-align: center;">
            <div style="border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); border: 2px solid var(--color-court-border);">
                <img src="/img/yolo_vision_ia.png" alt="Esquema de Visión Artificial YOLO Pose Baloncesto" style="width: 100%; height: auto; display: block;">
            </div>
            <small style="color: #666; display: block; margin-top: 0.35rem; font-size: 0.78rem;">Detección esquelética de 17 puntos clave articulares con cálculo angular</small>
        </div>
    </div>

    <div class="grid-3" style="margin-top: 1.25rem;">
        <div style="background: white; padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--color-gray-200); text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 0.5rem;"></div>
            <strong style="display:block; margin-bottom:0.25rem;">1. Captura de Video</strong>
            <small style="color: var(--color-gray-600);">Cámara estándar (30 fps) grabando la ejecución técnica lateral o frontal del deportista.</small>
        </div>

        <div style="background: white; padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--color-gray-200); text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 0.5rem;"></div>
            <strong style="display:block; margin-bottom:0.25rem;">2. YOLOv8 / Pose Estimation</strong>
            <small style="color: var(--color-gray-600);">Extracción de 17 puntos clave articulares (hombros, codos, muñecas, caderas, rodillas, tobillos).</small>
        </div>

        <div style="background: white; padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--color-gray-200); text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 0.5rem;"></div>
            <strong style="display:block; margin-bottom:0.25rem;">3. Ingesta JSON Automática</strong>
            <small style="color: var(--color-gray-600);">Cálculo de ángulos articulares que se transfieren automáticamente a la base de datos vía el contrato JSON documentado.</small>
        </div>
    </div>

    <div style="margin-top: 1.25rem; padding: 1rem; background-color: var(--color-court-light); border-radius: var(--radius-md); font-size: 0.88rem; border-left: 4px solid var(--color-primary);">
         El contrato JSON de datos estructurados para visión artificial se encuentra formalmente documentado en <code>docs/contrato_yolo.json</code> dentro del repositorio.
    </div>
</div>

<!-- DETALLE BIOMECÁNICO DE LAS TRES TÉCNICAS -->
<div style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--color-dark); margin-bottom: 1.25rem;">
        Variables Biomecánicas por Técnica
    </h3>

    <!-- Técnica 1: Lanzamiento -->
    <div id="lanzamiento" class="card" style="margin-bottom: 1.5rem;">
        <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;">
            <div style="width: 220px; flex-shrink: 0; border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--color-gray-200);">
                <img src="/img/tecnica_lanzamiento.png" alt="Mecánica de Lanzamiento" style="width: 100%; height: auto; display: block;">
            </div>
            <div style="flex: 1; min-width: 280px;">
                <h4 style="font-size: 1.25rem; font-weight: 800; color: var(--color-dark); margin-bottom: 0.35rem;">
                     1. Lanzamiento (Mecánica de Tiro)
                </h4>
                <p style="color: var(--color-gray-800); font-size: 0.92rem; margin-bottom: 0.75rem;">
                    Evalúa la coordinación y sincronización de la cadena cinética desde las piernas hasta la liberación del balón.
                </p>
                <ul style="padding-left: 1.25rem; font-size: 0.88rem; line-height: 1.7; color: var(--color-gray-800);">
                    <li><strong>Flexión de rodillas (°):</strong> Grado de flexión inicial para la generación del impulso vertical.</li>
                    <li><strong>Alineación del codo (°):</strong> Posición del codo ejecutor bajo el balón alineado hacia el aro.</li>
                    <li><strong>Extensión del brazo (°):</strong> Grado de extensión al momento de la máxima elevación y despegue.</li>
                    <li><strong>Seguimiento de la muñeca (°):</strong> Quiebre final de la muñeca ("mano de ganso") que da rotación (backspin) al balón.</li>
                    <li><strong>Estabilidad de la base (cm):</strong> Separación simétrica de los pies al ancho de los hombros.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Técnica 2: Postura Defensiva -->
    <div id="defensa" class="card" style="margin-bottom: 1.5rem;">
        <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;">
            <div style="width: 220px; flex-shrink: 0; border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--color-gray-200);">
                <img src="/img/tecnica_defensa.png" alt="Postura Defensiva" style="width: 100%; height: auto; display: block;">
            </div>
            <div style="flex: 1; min-width: 280px;">
                <h4 style="font-size: 1.25rem; font-weight: 800; color: var(--color-dark); margin-bottom: 0.35rem;">
                     2. Postura Defensiva
                </h4>
                <p style="color: var(--color-gray-800); font-size: 0.92rem; margin-bottom: 0.75rem;">
                    Evalúa la postura base para reacciones rápidas, equilibrio dinámico y cobertura lateral sin infracción.
                </p>
                <ul style="padding-left: 1.25rem; font-size: 0.88rem; line-height: 1.7; color: var(--color-gray-800);">
                    <li><strong>Separación de pies (cm):</strong> Base ancha (ligeramente superior al ancho de hombros) para estabilidad.</li>
                    <li><strong>Flexión de rodillas y caderas (°):</strong> Descenso del centro de gravedad corporal.</li>
                    <li><strong>Inclinación del tronco (°):</strong> Pecho erguido orientado hacia el atacante para evitar caídas hacia adelante.</li>
                    <li><strong>Posición de las manos:</strong> Palmas activas, una orientada al balón y otra a la línea de pase.</li>
                    <li><strong>Pies sin cruzarse:</strong> Desplazamientos laterales con paso de caída, manteniendo la base sin perder el balance.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Técnica 3: Drible Inicial -->
    <div id="drible" class="card" style="margin-bottom: 1.5rem;">
        <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;">
            <div style="width: 220px; flex-shrink: 0; border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--color-gray-200);">
                <img src="/img/tecnica_drible.png" alt="Drible Inicial" style="width: 100%; height: auto; display: block;">
            </div>
            <div style="flex: 1; min-width: 280px;">
                <h4 style="font-size: 1.25rem; font-weight: 800; color: var(--color-dark); margin-bottom: 0.35rem;">
                     3. Drible Inicial (Bote)
                </h4>
                <p style="color: var(--color-gray-800); font-size: 0.92rem; margin-bottom: 0.75rem;">
                    Evalúa el dominio del bote de protección y la capacidad de mantener la visión de juego periférica.
                </p>
                <ul style="padding-left: 1.25rem; font-size: 0.88rem; line-height: 1.7; color: var(--color-gray-800);">
                    <li><strong>Flexión de rodillas (°):</strong> Nivel de agachamiento atlético para proteger el balón del defensor.</li>
                    <li><strong>Inclinación del tronco (°):</strong> Tronco en ángulo que favorece el avance sin perder el control.</li>
                    <li><strong>Posición del balón al costado:</strong> Balón rebotando al lateral exterior del cuerpo, fuera del alcance rival.</li>
                    <li><strong>Altura del bote (cm):</strong> Rebote controlado a la altura de la cadera/cintura sin muñequeo excesivo.</li>
                    <li><strong>Cabeza levantada:</strong> Mirada al frente para lectura de defensas y compañeros, sin mirar fijamente al balón.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ALCANCE Y LIMITACIONES -->
<div class="grid-2">
    <div class="card">
        <h4 class="card-title"> Alcance del Sistema</h4>
        <ul style="padding-left: 1.25rem; line-height: 1.7; color: var(--color-gray-800);">
            <li>Herramienta 100% local, garantizando que los datos de los niños permanezcan bajo estricta reserva institucional.</li>
            <li>Cobertura en 3 técnicas específicas fundamentales: lanzamiento, postura defensiva y drible inicial.</li>
            <li>Seguimiento histórico a través de gráficas de líneas para evidenciar cambios posturales en el tiempo.</li>
            <li>Orientado específicamente a escuelas de formación de baloncesto infantil (categorías mini y pasarela).</li>
        </ul>
    </div>

    <div class="card">
        <h4 class="card-title"> Limitaciones Técnicas y Éticas</h4>
        <ul style="padding-left: 1.25rem; line-height: 1.7; color: var(--color-gray-800);">
            <li><strong>No reemplaza la mirada experta:</strong> El criterio formativo, la empatía y la corrección pedagógica recaen siempre en el entrenador.</li>
            <li><strong>Sin diagnósticos médicos:</strong> La plataforma no detecta lesiones, asimetrías patológicas ni emite conceptos ortopédicos.</li>
            <li><strong>Limitaciones de campo de visión:</strong> Depende de ángulos de toma claros y sin oclusiones severas en la cancha.</li>
            <li><strong>Enfoque no comercial:</strong> Software desarrollado en el marco de la investigación académica de pregrado.</li>
        </ul>
    </div>
</div>
