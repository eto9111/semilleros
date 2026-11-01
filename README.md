# Sistema de Apoyo Postural para Baloncesto Formativo (Baloncesto YOLO)

Proyecto de investigación formativa de la **Universidad CESMAG** (Pasto, Nariño).  
**Facultad de Ingeniería · Programa de Ingeniería de Sistemas**  
**Semillero de Investigación FOCUS**

> **Título del Proyecto:**  
> *"Sistema de visión por computador basado en el modelo YOLO para la detección y corrección de posturas en las técnicas de lanzamiento, defensa y drible inicial de baloncesto en niños de escuelas de formación"*.

---

##  Principios del Sistema
- **Herramienta pedagógica de apoyo:** Asiste al entrenador en la toma de decisiones técnicas basadas en datos objetivos.
- **No reemplaza al entrenador:** La mirada experta, la empatía y la retroalimentación pedagógica siempre corresponden al ser humano.
- **Sin diagnósticos clínicos ni médicos:** No evalúa patologías, lesiones ni talento deportivo excluyente.
- **100% Local:** Corre en el computador local para máxima privacidad y reserva de datos de niños y niñas bajo la **Ley 1581 de 2012** y la **Resolución 8430 de 1993**.

---

##  Requisitos del Entorno
- **Sistema Operativo:** Windows, macOS o Linux.
- **PHP:** Versión 8.1 o superior (probado y verificado en PHP 8.2.12) con la extensión `pdo_pgsql` activada.
- **Composer:** Con soporte de autoloader PSR-4 (`App\` -> `src/`).
- **PostgreSQL:** Versión 15+ (probado y verificado en PostgreSQL 18.2).
- **Servidor Web Local:** Servidor integrado de PHP (`php -S`).

---

##  Base de Datos

- **Nombre de la base de datos:** `baloncesto_yolo`
- **Gestor:** PostgreSQL (administrado con pgAdmin o terminal `psql`).
- **Parámetros de conexión locales (`.env`):**
  ```env
  DB_HOST=localhost
  DB_PORT=5432
  DB_NAME=baloncesto_yolo
  DB_USER=postgres
  DB_PASS=1
  APP_ENV=local
  APP_URL=http://localhost:8000
  ```

### Creación y Carga de Esquema
Si necesitas recrear la base de datos:
```powershell
# En Windows PowerShell (con usuario postgres y contraseña 1)
$env:PGPASSWORD='1'
psql -U postgres -h localhost -p 5432 -c "CREATE DATABASE baloncesto_yolo;"
psql -U postgres -h localhost -p 5432 -d baloncesto_yolo -f "database/schema.sql"

# Cargar datos DEMO iniciales (entrenador, deportistas y mediciones)
php database/seed_demo.php
```

---

##  Cómo Iniciar la Aplicación

1. **Instalar dependencias y generar autoload:**
   ```bash
   composer dump-autoload
   ```

2. **Arrancar el servidor local:**
   ```bash
   php -S localhost:8000 -t public
   ```

3. **Abrir en el navegador:**
   Ingresa a: [http://localhost:8000](http://localhost:8000)

---

##  Usuarios DEMO Listos para Probar

Todos los usuarios de demostración tienen precargados historiales, gráficas y retroalimentaciones:

| Rol | Nombre Completo | Correo Electrónico | Contraseña | Detalles |
| :--- | :--- | :--- | :--- | :--- |
| **Entrenador** | Prof. Carlos Alberto Gómez (DEMO) | `entrenador.demo@cesmag.edu.co` | `password123` | Entrenador titular de Leones de Pasto. Tiene asignados a Mateo y Sofía. |
| **Deportista** | Mateo Rosero Portilla (DEMO) | `mateo.demo@cesmag.edu.co` | `password123` | 13 años (menor con acudiente). Tiene mediciones de Lanzamiento y Drible. |
| **Deportista** | Sofía Benavides Insuasty (DEMO) | `sofia.demo@cesmag.edu.co` | `password123` | 14 años (menor con acudiente). Tiene mediciones de Postura Defensiva. |

---

##  Ejecución de Pruebas Automatizadas (End-to-End)

Con el servidor corriendo en `localhost:8000`, ejecuta la suite de pruebas completa:
```bash
php tests/smoke_test.php
```
Valida 10 flujos críticos:
1. Carga de página de inicio con tarjetas de roles.
2. Contenido de "La Solución" (objetivos, problema, módulo YOLO).
3. Contenido de "Privacidad y Datos" (Ley 1581 de 2012, Resolución 8430 de 1993, eliminación de datos).
4. API de búsqueda global en vivo.
5. Autenticación y navegación del Deportista (panel, historial, gráficas Chart.js).
6. Registro de nueva medición postural manual.
7. Autenticación del Entrenador y aislamiento estricto de deportistas.
8. Expediente individual, gráficas y formulación de recomendaciones técnicas.
9. Búsqueda de deportistas en vivo por el entrenador.
10. Validación de seguridad en servidor (bloqueo HTTP 403 a accesos no autorizados por rol).

---

##  Preparación para Visión Artificial (YOLO)

El sistema ya está desacoplado y listo para recibir el modelo YOLO Pose:
- **Interfaz PHP:** `src/AnalizadorPosturaInterface.php`
- **Implementación Manual Actual:** `src/AnalizadorPosturaManual.php` (devuelve el estado `"análisis automático pendiente"`).
- **Contrato JSON Documentado:** `docs/contrato_yolo.json` (especifica los 17 puntos clave articulares, coordenadas $x,y$, confianza y cálculo de ángulos).
- **Interfaz de Usuario:** Botón `"Analizar con YOLO (próximamente)"` en la pantalla de nueva medición.

---

##  Qué Falta por Hacer (Fases Futuras)

1. **Entrenamiento y Despliegue del Modelo YOLOv8-Pose:**
   - Entrenar la red neuronal con secuencias de video de deportistas infantiles locales de Pasto en las 3 técnicas.
   - Ejecutar inferencia en tiempo real en Python y enviar el payload JSON documentado en `docs/contrato_yolo.json` hacia la API del sistema.
2. **Captura Directa desde Cámara / Video:**
   - Módulo de subida de video grabado en formato `.mp4` o conexión con cámara lateral mediante WebRTC / OpenCV.
3. **Módulo de Reportes PDF:**
   - Generación de informes impresos de progreso postural para entregar a los padres o acudientes en reuniones de escuela formativa.
4. **Historial de Solicitudes de Eliminación:**
   - Bandeja de administración para auditar el cumplimiento del retiro de datos conforme a la Ley de Habeas Data.
