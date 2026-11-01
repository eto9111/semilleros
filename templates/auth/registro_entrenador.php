<?php
use App\Auth;
?>
<div style="display: grid; grid-template-columns: 5fr 7fr; gap: 0; max-width: 1100px; margin: 1rem auto; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-lg); border: 1px solid var(--color-court-border);">

    <!-- Panel izquierdo: imagen + beneficios -->
    <div style="background-color: var(--color-dark); display: flex; flex-direction: column;">
        <div style="position: relative; flex: 1; min-height: 300px;">
            <img src="/img/yolo_vision_ia.png" alt="Visión IA YOLO Baloncesto" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.65; display: block; position: absolute; inset: 0;">
            <div style="position: absolute; inset: 0; padding: 1.75rem; display: flex; flex-direction: column; justify-content: flex-end; background: linear-gradient(transparent 35%, rgba(26,26,26,0.96));">
                <span class="role-tag" style="background-color: var(--color-dark); font-size: 0.78rem; margin-bottom: 0.5rem; display: inline-block; width: fit-content; border: 1px solid #555;">Rol: Entrenador</span>
                <h3 style="color: white; font-size: 1.25rem; font-weight: 800; margin: 0; line-height: 1.3;">
                    Regístrate y comienza a hacer seguimiento postural a tus deportistas
                </h3>
            </div>
        </div>
        <div style="padding: 1.5rem; background-color: #1A1A1A; border-top: 3px solid var(--color-primary);">
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                <li style="display: flex; gap: 0.6rem; align-items: flex-start; font-size: 0.85rem; color: #CCC;">
                    <span style="color: var(--color-primary); font-size: 1rem;"></span>
                    Gestiona todos tus deportistas asignados desde un solo panel
                </li>
                <li style="display: flex; gap: 0.6rem; align-items: flex-start; font-size: 0.85rem; color: #CCC;">
                    <span style="color: var(--color-primary); font-size: 1rem;"></span>
                    Accede a gráficas comparativas de evolución postural individual
                </li>
                <li style="display: flex; gap: 0.6rem; align-items: flex-start; font-size: 0.85rem; color: #CCC;">
                    <span style="color: var(--color-primary); font-size: 1rem;"></span>
                    Registra observaciones y recomendaciones pedagógicas personalizadas
                </li>
                <li style="display: flex; gap: 0.6rem; align-items: flex-start; font-size: 0.85rem; color: #CCC;">
                    <span style="color: var(--color-primary); font-size: 1rem;"></span>
                    Preparado para análisis automático con YOLO Pose en fase futura
                </li>
            </ul>
        </div>
    </div>

    <!-- Panel derecho: formulario de registro -->
    <div style="background: white; padding: 2rem; overflow-y: auto; max-height: 90vh;">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.55rem; font-weight: 800; color: var(--color-dark); margin-bottom: 0.2rem;">
                Registro de Entrenador
            </h2>
            <p style="font-size: 0.85rem; color: var(--color-gray-600);">
                Habilita tu perfil profesional para el semillero FOCUS
            </p>
        </div>

        <form action="/index.php?action=do_registro_entrenador" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Auth::csrfToken() ?>">

            <div class="grid-2">
                <div class="form-group">
                    <label for="nombres" class="form-label">Nombres:</label>
                    <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Ej: Carlos Alberto" required>
                </div>
                <div class="form-group">
                    <label for="apellidos" class="form-label">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Ej: Gómez" required>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="escuela_club" class="form-label">Escuela o Club:</label>
                    <input type="text" id="escuela_club" name="escuela_club" class="form-control" placeholder="Ej: Leones de Pasto" required>
                </div>
                <div class="form-group">
                    <label for="especialidad" class="form-label">Especialidad / Cargo (Opcional):</label>
                    <input type="text" id="especialidad" name="especialidad" class="form-control" placeholder="Ej: Entrenador Minibaloncesto">
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="entrenador@ejemplo.com" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" minlength="6" required>
                </div>
            </div>

            <div class="form-group">
                <label for="telefono" class="form-label">Teléfono de Contacto (Opcional):</label>
                <input type="text" id="telefono" name="telefono" class="form-control" placeholder="Ej: 3151234567">
            </div>

            <div class="form-check" style="margin-top: 0.5rem; background: #f9f9f9; padding: 0.75rem; border: 1px solid var(--color-gray-200); border-radius: var(--radius-sm);">
                <input type="checkbox" id="terminos_aceptados" name="terminos_aceptados" value="1" required>
                <label for="terminos_aceptados" style="font-size: 0.8rem; color: var(--color-dark);">
                    Acepto el <a href="/index.php?action=privacidad" target="_blank" style="color: var(--color-primary); font-weight: 700;">Aviso de Privacidad y Consentimiento Ético</a> bajo la Ley 1581 de 2012 y la Resolución 8430 de 1993, comprometiéndome a usar los datos de los menores exclusivamente con fines formativos.
                </label>
            </div>

            <button type="submit" class="btn btn-secondary btn-lg" style="width: 100%; margin-top: 1rem;">
                 Completar Registro de Entrenador
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.25rem; font-size: 0.88rem; border-top: 1px solid #eee; padding-top: 1rem;">
            ¿Ya tienes cuenta?
            <a href="/index.php?action=login_entrenador" style="color: var(--color-dark); font-weight: 700; text-decoration: none;">
                Iniciar sesión como entrenador
            </a>
        </div>
    </div>
</div>

<style>
@media (max-width: 720px) {
    div[style*="grid-template-columns: 5fr 7fr"] { grid-template-columns: 1fr !important; }
}
</style>
