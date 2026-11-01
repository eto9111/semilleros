<?php
use App\Auth;
use App\Entrenador;
$entrenadores = Entrenador::obtenerTodos();
?>
<div style="display: grid; grid-template-columns: 5fr 7fr; gap: 0; max-width: 1100px; margin: 1rem auto; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-lg); border: 1px solid var(--color-court-border);">

    <!-- Panel izquierdo: imagen + beneficios -->
    <div style="background-color: var(--color-dark); display: flex; flex-direction: column;">
        <div style="position: relative; flex: 1; min-height: 280px;">
            <img src="/img/tecnica_lanzamiento.png" alt="Técnica de Lanzamiento" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.65; display: block;">
            <div style="position: absolute; inset: 0; padding: 1.75rem; display: flex; flex-direction: column; justify-content: flex-end; background: linear-gradient(transparent 40%, rgba(26,26,26,0.97));">
                <span class="role-tag" style="font-size: 0.78rem; margin-bottom: 0.5rem; display: inline-block; width: fit-content;">Rol: Deportista</span>
                <h3 style="color: white; font-size: 1.2rem; font-weight: 800; margin: 0; line-height: 1.3;">
                    ¡Empieza a registrar tu evolución postural!
                </h3>
            </div>
        </div>
        <div style="padding: 1.5rem; background-color: #1A1A1A; border-top: 3px solid var(--color-primary);">
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                <li style="display: flex; gap: 0.6rem; align-items: flex-start; font-size: 0.85rem; color: #CCC;">
                    <span style="color: var(--color-primary); font-size: 1rem; margin-top: 0.05rem;"></span>
                    Registra mediciones de lanzamiento, defensa y drible
                </li>
                <li style="display: flex; gap: 0.6rem; align-items: flex-start; font-size: 0.85rem; color: #CCC;">
                    <span style="color: var(--color-primary); font-size: 1rem; margin-top: 0.05rem;"></span>
                    Visualiza tus gráficas de evolución técnica
                </li>
                <li style="display: flex; gap: 0.6rem; align-items: flex-start; font-size: 0.85rem; color: #CCC;">
                    <span style="color: var(--color-primary); font-size: 1rem; margin-top: 0.05rem;"></span>
                    Recibe recomendaciones directas de tu entrenador
                </li>
                <li style="display: flex; gap: 0.6rem; align-items: flex-start; font-size: 0.85rem; color: #CCC;">
                    <span style="color: var(--color-primary); font-size: 1rem; margin-top: 0.05rem;"></span>
                    Tus datos protegidos bajo Ley 1581 de 2012
                </li>
            </ul>
        </div>
    </div>

    <!-- Panel derecho: formulario de registro -->
    <div style="background: white; padding: 2rem 2rem; overflow-y: auto; max-height: 90vh;">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.55rem; font-weight: 800; color: var(--color-dark); margin-bottom: 0.2rem;">
                Registro de Deportista
            </h2>
            <p style="font-size: 0.85rem; color: var(--color-gray-600);">
                Completa tu ficha para comenzar el seguimiento postural
            </p>
        </div>

        <form action="/index.php?action=do_registro_deportista" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Auth::csrfToken() ?>">

            <div class="grid-2">
                <div class="form-group">
                    <label for="nombres" class="form-label">Nombres:</label>
                    <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Ej: Mateo" required>
                </div>
                <div class="form-group">
                    <label for="apellidos" class="form-label">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Ej: Rosero" required>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" required>
                    <div id="aviso-edad" style="font-size: 0.78rem; color: var(--color-primary-dark); font-weight: 600; margin-top: 0.3rem;"></div>
                </div>
                <div class="form-group">
                    <label for="escuela_club" class="form-label">Escuela o Club de Baloncesto:</label>
                    <input type="text" id="escuela_club" name="escuela_club" class="form-control" placeholder="Ej: Leones de Pasto" required>
                </div>
            </div>

            <!-- SECCIÓN CONDICIONAL PARA MENORES DE 18 -->
            <div id="seccion-acudiente" style="display: none; background-color: var(--color-court-light); border: 2px dashed var(--color-court-border); padding: 1rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem;">
                <h4 style="font-size: 0.9rem; font-weight: 800; color: var(--color-dark); margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                    <span></span> Datos y Autorización del Acudiente / Representante Legal
                </h4>
                <p style="font-size: 0.78rem; color: var(--color-gray-600); margin-bottom: 0.75rem;">
                    Requerido por ser menor de edad (Ley 1581/2012, Res. 8430/1993)
                </p>
                <div class="form-group">
                    <label for="nombre_acudiente" class="form-label">Nombre completo del acudiente:</label>
                    <input type="text" id="nombre_acudiente" name="nombre_acudiente" class="form-control" placeholder="Ej: Sandra Liliana Portilla">
                </div>
                <div class="form-check">
                    <input type="checkbox" id="autorizacion_acudiente" name="autorizacion_acudiente" value="1">
                    <label for="autorizacion_acudiente" style="font-size: 0.8rem; font-weight: 600; color: var(--color-dark);">
                        Como representante legal, autorizo voluntariamente la participación del menor en el Semillero FOCUS (Universidad CESMAG).
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="entrenador_id" class="form-label">Selecciona a tu Entrenador Responsable:</label>
                <select id="entrenador_id" name="entrenador_id" class="form-control" required>
                    <option value="">-- Elige tu entrenador de la lista --</option>
                    <?php foreach ($entrenadores as $ent): ?>
                        <option value="<?= $ent['id'] ?>">
                            Prof. <?= htmlspecialchars($ent['nombres'] . ' ' . $ent['apellidos']) ?> — <?= htmlspecialchars($ent['escuela_club']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small style="color: var(--color-gray-600); font-size: 0.77rem; display: block; margin-top: 0.2rem;">
                    Solo tu entrenador seleccionado podrá ver tus mediciones y dejarte recomendaciones.
                </small>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="deportista@ejemplo.com" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" minlength="6" required>
                </div>
            </div>

            <div class="form-check" style="margin-top: 0.5rem; background: #f9f9f9; padding: 0.75rem; border: 1px solid var(--color-gray-200); border-radius: var(--radius-sm);">
                <input type="checkbox" id="terminos_aceptados" name="terminos_aceptados" value="1" required>
                <label for="terminos_aceptados" style="font-size: 0.8rem; color: var(--color-dark);">
                    He leído y acepto el <a href="/index.php?action=privacidad" target="_blank" style="color: var(--color-primary); font-weight: 700;">Aviso de Privacidad y Consentimiento Informado</a> (Ley 1581/2012 – Res. 8430/1993).
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1rem;">
                Completar Registro de Deportista
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.25rem; font-size: 0.88rem; border-top: 1px solid #eee; padding-top: 1rem;">
            ¿Ya tienes cuenta?
            <a href="/index.php?action=login_deportista" style="color: var(--color-primary); font-weight: 700; text-decoration: none;">
                Iniciar sesión como deportista
            </a>
        </div>
    </div>
</div>

<style>
@media (max-width: 720px) {
    div[style*="grid-template-columns: 5fr 7fr"] { grid-template-columns: 1fr !important; }
}
</style>
