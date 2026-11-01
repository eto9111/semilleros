<div class="card card-court-accent" style="border-top: 5px solid var(--color-primary); margin-bottom: 2.5rem; padding: 2rem;">
    <div style="display: flex; gap: 2rem; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1.2; min-width: 320px;">
            <span class="badge badge-primary" style="margin-bottom: 0.75rem;">Semillero FOCUS · Universidad CESMAG</span>
            <h2 style="font-size: 2.1rem; font-weight: 800; line-height: 1.2; margin-bottom: 0.85rem; color: var(--color-dark);">
                Sistema de Apoyo Postural para Baloncesto Formativo
            </h2>
            <p style="font-size: 1.05rem; color: var(--color-gray-800); margin-bottom: 1.25rem; line-height: 1.6;">
                Herramienta de seguimiento biomecánico y pedagógico para niños y niñas en escuelas de formación de Pasto (Nariño). 
                Orientada a tres técnicas fundamentales: <strong>lanzamiento, defensa y drible inicial</strong>.
            </p>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="#roles" class="btn btn-primary btn-lg">Acceso por Rol</a>
                <a href="/index.php?action=solucion" class="btn btn-outline btn-lg">Conoce la Solución</a>
            </div>
        </div>
        <div style="flex: 1; min-width: 280px; text-align: center;">
            <div style="position: relative; overflow: hidden; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); border: 3px solid var(--color-court-border);">
                <img src="/img/hero_baloncesto.png" alt="Sistema Baloncesto YOLO Semillero FOCUS CESMAG" style="width: 100%; height: auto; display: block; object-fit: cover;">
                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(26,26,26,0.85)); color: white; padding: 0.75rem 1rem; font-size: 0.82rem; text-align: left;">
                     <strong>Fase Actual:</strong> Registro Manual &middot; Preparado para acople con IA YOLO Pose
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TARJETAS GRANDES DE ACCESO POR ROL -->
<section id="roles" style="margin-bottom: 3rem;">
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.6rem; font-weight: 800; color: var(--color-dark);">¿Cómo deseas ingresar al sistema?</h3>
        <p style="color: var(--color-gray-600);">Selecciona tu rol para acceder a tu panel personalizado o registrarte.</p>
    </div>

    <div class="hero-role-cards">
        <!-- Tarjeta Deportista -->
        <div class="role-card">
            <span class="role-card-badge">Jugadores</span>
            <div>
                <div class="role-card-icon" style="background:none; padding:0; display:flex; justify-content:center; align-items:center;">
                    <img src="/img/deportista_icono.png" alt="Deportista" style="width:90px; height:90px; object-fit:contain;">
                </div>
                <h4 class="role-card-title">Soy Deportista</h4>
                <p class="role-card-desc">
                    Ingresa tus mediciones posturales en lanzamiento, defensa y drible, visualiza tus gráficas de evolución a lo largo del tiempo y consulta las recomendaciones de tu entrenador.
                </p>
            </div>
            <div>
                <a href="/index.php?action=login_deportista" class="btn btn-primary btn-lg" style="width: 100%; margin-bottom: 0.75rem;">
                    Iniciar Sesión (Deportista)
                </a>
                <div style="font-size: 0.88rem; color: var(--color-gray-600);">
                    ¿Eres nuevo en la escuela? <a href="/index.php?action=registro_deportista" style="color: var(--color-primary); font-weight: 700; text-decoration: none;">Crear cuenta de deportista</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta Entrenador -->
        <div class="role-card">
            <span class="role-card-badge">Cuerpo Técnico</span>
            <div>
                <div class="role-card-icon" style="background:none; padding:0; display:flex; justify-content:center; align-items:center;">
                    <img src="/img/entrenador_icono.jpg" alt="Entrenador" style="width:90px; height:90px; object-fit:contain;">
                </div>
                <h4 class="role-card-title">Soy Entrenador</h4>
                <p class="role-card-desc">
                    Consulta el listado exclusivo de tus deportistas asignados, revisa sus historiales y gráficas comparativas, y registra observaciones y recomendaciones pedagógicas individuales.
                </p>
            </div>
            <div>
                <a href="/index.php?action=login_entrenador" class="btn btn-secondary btn-lg" style="width: 100%; margin-bottom: 0.75rem;">
                    Iniciar Sesión (Entrenador)
                </a>
                <div style="font-size: 0.88rem; color: var(--color-gray-600);">
                    ¿Entrenador nuevo? <a href="/index.php?action=registro_entrenador" style="color: var(--color-primary); font-weight: 700; text-decoration: none;">Registrarse como entrenador</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- QUÉ HACE Y QUÉ NO HACE (PRINCIPIOS CLAVE) -->
<section style="margin-bottom: 3rem;">
    <div class="grid-2">
        <div class="card" style="border-left: 5px solid var(--color-success);">
            <h4 class="card-title" style="color: var(--color-success);">
                <span></span> Qué hace este sistema
            </h4>
            <ul style="padding-left: 1.25rem; line-height: 1.8; color: var(--color-gray-800);">
                <li><strong>Herramienta pedagógica de apoyo:</strong> asiste al entrenador con datos objetivos sobre ángulos y posturas corporales.</li>
                <li><strong>Registro longitudinal:</strong> permite registrar y graficar la evolución técnica de los niños a través del tiempo.</li>
                <li><strong>Comunicación técnico-deportista:</strong> canaliza retroalimentaciones y ejercicios recomendados por el entrenador.</li>
                <li><strong>Preparación para IA:</strong> arquitectura desacoplada lista para recibir detecciones de poses de YOLO en fases futuras.</li>
            </ul>
        </div>

        <div class="card" style="border-left: 5px solid var(--color-danger);">
            <h4 class="card-title" style="color: var(--color-danger);">
                <span></span> Qué NO hace este sistema
            </h4>
            <ul style="padding-left: 1.25rem; line-height: 1.8; color: var(--color-gray-800);">
                <li><strong>NO reemplaza al entrenador:</strong> la interpretación pedagógica y formativa siempre corresponde al ser humano.</li>
                <li><strong>NO emite diagnósticos médicos ni ortopédicos:</strong> no evalúa patologías, lesiones ni condiciones clínicas.</li>
                <li><strong>NO es un detector de talento deportivo:</strong> su fin es el aprendizaje formativo, no la exclusión ni el juicio de potencial.</li>
                <li><strong>NO genera correcciones automáticas invasivas:</strong> evita sesgos no supervisados respetando el desarrollo motor infantil.</li>
            </ul>
        </div>
    </div>
</section>

<!-- LAS TRES TÉCNICAS BASE -->
<section style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--color-dark); margin-bottom: 1.25rem;">
        Técnicas Biomecánicas Analizadas
    </h3>
    <div class="grid-3">
        <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
            <div style="width: 100%; height: 190px; overflow: hidden; background: #000;">
                <img src="/img/tecnica_lanzamiento.png" alt="Técnica de Lanzamiento de Baloncesto" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <h4 style="font-size: 1.15rem; font-weight: 800; color: var(--color-dark);">1. Lanzamiento</h4>
                        <span class="badge badge-primary">5 Variables</span>
                    </div>
                    <p style="font-size: 0.88rem; color: var(--color-gray-600); margin-bottom: 0.75rem;">
                        Mecánica de tiro: flexión de rodillas, alineación del codo, extensión del brazo, quiebre de muñeca y estabilidad de la base de apoyo.
                    </p>
                </div>
                <a href="/index.php?action=solucion#lanzamiento" class="btn btn-outline btn-sm" style="width: 100%;">Ver variables técnicas →</a>
            </div>
        </div>

        <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
            <div style="width: 100%; height: 190px; overflow: hidden; background: #000;">
                <img src="/img/tecnica_defensa.png" alt="Técnica de Postura Defensiva de Baloncesto" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <h4 style="font-size: 1.15rem; font-weight: 800; color: var(--color-dark);">2. Postura Defensiva</h4>
                        <span class="badge badge-primary">5 Variables</span>
                    </div>
                    <p style="font-size: 0.88rem; color: var(--color-gray-600); margin-bottom: 0.75rem;">
                        Centro de gravedad bajo: separación de pies, flexión articular de caderas y rodillas, inclinación del tronco y palmas activas.
                    </p>
                </div>
                <a href="/index.php?action=solucion#defensa" class="btn btn-outline btn-sm" style="width: 100%;">Ver variables técnicas →</a>
            </div>
        </div>

        <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
            <div style="width: 100%; height: 190px; overflow: hidden; background: #000;">
                <img src="/img/tecnica_drible.png" alt="Técnica de Drible Inicial de Baloncesto" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <h4 style="font-size: 1.15rem; font-weight: 800; color: var(--color-dark);">3. Drible Inicial</h4>
                        <span class="badge badge-primary">5 Variables</span>
                    </div>
                    <p style="font-size: 0.88rem; color: var(--color-gray-600); margin-bottom: 0.75rem;">
                        Control y bote de balón: altura a la cintura, protección lateral, flexión de rodillas, inclinación de tronco y cabeza levantada.
                    </p>
                </div>
                <a href="/index.php?action=solucion#drible" class="btn btn-outline btn-sm" style="width: 100%;">Ver variables técnicas →</a>
            </div>
        </div>
    </div>
</section>
