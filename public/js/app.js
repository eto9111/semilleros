// Lógica de interfaz de usuario para Baloncesto YOLO (FOCUS CESMAG)
document.addEventListener('DOMContentLoaded', () => {

    // 1. Detección de minoría de edad en registro de deportista
    const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
    const seccionAcudiente = document.getElementById('seccion-acudiente');
    const inputNombreAcudiente = document.getElementById('nombre_acudiente');
    const checkAutorizacionAcudiente = document.getElementById('autorizacion_acudiente');
    const avisoEdad = document.getElementById('aviso-edad');

    if (fechaNacimientoInput && seccionAcudiente) {
        function verificarEdad() {
            const val = fechaNacimientoInput.value;
            if (!val) return;

            const nacimiento = new Date(val);
            const hoy = new Date();
            let edad = hoy.getFullYear() - nacimiento.getFullYear();
            const m = hoy.getMonth() - nacimiento.getMonth();
            if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
                edad--;
            }

            if (edad < 18) {
                seccionAcudiente.style.display = 'block';
                if (inputNombreAcudiente) inputNombreAcudiente.required = true;
                if (checkAutorizacionAcudiente) checkAutorizacionAcudiente.required = true;
                if (avisoEdad) {
                    avisoEdad.textContent = `Edad calculada: ${edad} años (Menor de 18 años). Se requiere la información y consentimiento del acudiente / representante legal.`;
                }
            } else {
                seccionAcudiente.style.display = 'none';
                if (inputNombreAcudiente) {
                    inputNombreAcudiente.required = false;
                    inputNombreAcudiente.value = '';
                }
                if (checkAutorizacionAcudiente) {
                    checkAutorizacionAcudiente.required = false;
                    checkAutorizacionAcudiente.checked = false;
                }
                if (avisoEdad) {
                    avisoEdad.textContent = `Edad calculada: ${edad} años (Mayor de edad).`;
                }
            }
        }

        fechaNacimientoInput.addEventListener('change', verificarEdad);
        verificarEdad(); // Ejecutar al cargar si ya tiene valor
    }

    // 2. Cambio dinámico de técnica en formulario de medición
    const selectorTecnica = document.getElementById('selector-tecnica');
    if (selectorTecnica) {
        function alternarCamposTecnica() {
            const tecnicaSeleccionada = selectorTecnica.value;
            const secciones = document.querySelectorAll('.seccion-tecnica-vars');
            secciones.forEach(sec => {
                if (sec.id === 'vars-' + tecnicaSeleccionada) {
                    sec.style.display = 'block';
                    sec.querySelectorAll('input, select').forEach(el => el.disabled = false);
                } else {
                    sec.style.display = 'none';
                    sec.querySelectorAll('input, select').forEach(el => el.disabled = true);
                }
            });
        }

        selectorTecnica.addEventListener('change', alternarCamposTecnica);
        alternarCamposTecnica();
    }

    // 3. Confirmación de solicitud de baja de datos
    const btnEliminarDatos = document.getElementById('btn-solicitar-eliminacion');
    const modalEliminacion = document.getElementById('modal-solicitar-eliminacion');
    const btnCancelarModal = document.getElementById('btn-cancelar-modal');

    if (btnEliminarDatos && modalEliminacion) {
        btnEliminarDatos.addEventListener('click', (e) => {
            e.preventDefault();
            modalEliminacion.style.display = 'flex';
        });

        if (btnCancelarModal) {
            btnCancelarModal.addEventListener('click', () => {
                modalEliminacion.style.display = 'none';
            });
        }
    }
});
