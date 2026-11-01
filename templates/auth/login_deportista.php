<?php
use App\Auth;
?>
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0; max-width: 920px; margin: 1.5rem auto; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-lg); border: 1px solid var(--color-court-border);">

    <!-- Panel izquierdo: imagen ilustrativa -->
    <div style="position: relative; background-color: var(--color-dark); min-height: 480px; display: flex; flex-direction: column; justify-content: flex-end;">
        <img src="/img/tecnica_lanzamiento.png" alt="Técnica de Lanzamiento" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.75;">
        <div style="position: relative; z-index: 1; padding: 2rem; background: linear-gradient(transparent, rgba(26,26,26,0.92));">
            <span class="role-tag" style="font-size: 0.78rem; padding: 0.25rem 0.75rem; margin-bottom: 0.6rem; display: inline-block;">Rol: Deportista</span>
            <h3 style="color: white; font-size: 1.3rem; font-weight: 800; margin-bottom: 0.35rem; line-height: 1.25;">
                Zona de Deportistas
            </h3>
            <p style="color: #FFB885; font-size: 0.85rem; margin: 0;">
                Registra tus mediciones posturales y sigue tu evolución técnica en lanzamiento, defensa y drible.
            </p>
        </div>
    </div>

    <!-- Panel derecho: formulario de login -->
    <div style="background: white; padding: 2.5rem 2rem; display: flex; flex-direction: column; justify-content: center;">
        <div style="text-align: center; margin-bottom: 1.75rem;">
            <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--color-dark); margin-bottom: 0.25rem;">
                Iniciar Sesión
            </h2>
            <p style="font-size: 0.88rem; color: var(--color-gray-600);">
                Ingresa con tu correo y contraseña registrados
            </p>
        </div>

        <form action="/index.php?action=do_login_deportista" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Auth::csrfToken() ?>">

            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="deportista@ejemplo.com" required autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Tu contraseña" required>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 0.75rem;">
                 Ingresar a Mi Panel
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--color-gray-200); font-size: 0.9rem;">
            <p style="color: var(--color-gray-600); margin-bottom: 0.5rem;">
                ¿Eres nuevo en la escuela?
                <a href="/index.php?action=registro_deportista" style="color: var(--color-primary); font-weight: 700; text-decoration: none;">
                    Crear cuenta de deportista
                </a>
            </p>
            <p style="font-size: 0.82rem; color: #777;">
                ¿Eres entrenador? <a href="/index.php?action=login_entrenador" style="color: var(--color-dark); font-weight: 600;">Ingresa por aquí</a>
            </p>
        </div>
    </div>
</div>

<style>
@media (max-width: 640px) {
    div[style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; }
    div[style*="min-height: 480px"] { min-height: 220px !important; }
}
</style>
