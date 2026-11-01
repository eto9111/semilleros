-- ==============================================================================
-- SISTEMA DE VISIÓN POR COMPUTADOR BASADO EN YOLO PARA CORRECCIÓN DE POSTURAS
-- Semillero FOCUS · Ingeniería de Sistemas · Universidad CESMAG (Pasto, Nariño)
-- Base de datos: baloncesto_yolo
-- ==============================================================================

-- 1. TABLA: escuela
CREATE TABLE IF NOT EXISTS escuela (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL UNIQUE,
    ciudad VARCHAR(100) NOT NULL DEFAULT 'Pasto',
    fecha_registro TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 2. TABLA: usuario
CREATE TABLE IF NOT EXISTS usuario (
    id SERIAL PRIMARY KEY,
    rol VARCHAR(20) NOT NULL CHECK (rol IN ('deportista', 'entrenador')),
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    id_escuela INT NOT NULL REFERENCES escuela(id) ON DELETE RESTRICT,
    acepta_privacidad BOOLEAN NOT NULL DEFAULT FALSE CHECK (acepta_privacidad = TRUE),
    fecha_acepta_priv TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_registro TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN NOT NULL DEFAULT TRUE
);

-- 3. TABLA: entrenador
CREATE TABLE IF NOT EXISTS entrenador (
    id_usuario INT PRIMARY KEY REFERENCES usuario(id) ON DELETE CASCADE,
    especialidad VARCHAR(150) DEFAULT 'Entrenador de Baloncesto Formativo',
    telefono VARCHAR(30),
    fecha_creacion TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 4. TABLA: deportista
CREATE TABLE IF NOT EXISTS deportista (
    id_usuario INT PRIMARY KEY REFERENCES usuario(id) ON DELETE CASCADE,
    fecha_nacimiento DATE NOT NULL,
    id_entrenador INT NOT NULL REFERENCES entrenador(id_usuario) ON DELETE RESTRICT,
    nombre_acudiente VARCHAR(150),
    autorizacion_acudiente BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- TRIGGER: Validación de menor de 18 años para deportistas
CREATE OR REPLACE FUNCTION fn_validar_menor_deportista()
RETURNS TRIGGER AS $$
DECLARE
    edad INT;
BEGIN
    edad := EXTRACT(YEAR FROM age(CURRENT_DATE, NEW.fecha_nacimiento));
    IF edad < 18 THEN
        IF NEW.nombre_acudiente IS NULL OR trim(NEW.nombre_acudiente) = '' OR NEW.autorizacion_acudiente IS NOT TRUE THEN
            RAISE EXCEPTION 'Los deportistas menores de 18 años requieren registrar el nombre del acudiente y la casilla de autorización.';
        END IF;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_validar_menor_deportista ON deportista;
CREATE TRIGGER trg_validar_menor_deportista
BEFORE INSERT OR UPDATE ON deportista
FOR EACH ROW
EXECUTE FUNCTION fn_validar_menor_deportista();

-- 5. TABLA: tecnica
CREATE TABLE IF NOT EXISTS tecnica (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(30) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);

-- 6. TABLA: variable_postural
CREATE TABLE IF NOT EXISTS variable_postural (
    id SERIAL PRIMARY KEY,
    id_tecnica INT NOT NULL REFERENCES tecnica(id) ON DELETE CASCADE,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    unidad VARCHAR(20) NOT NULL CHECK (unidad IN ('grados', 'cm', 'valoracion')),
    CONSTRAINT uq_tecnica_variable UNIQUE (id_tecnica, nombre)
);

-- 7. TABLA: medicion
CREATE TABLE IF NOT EXISTS medicion (
    id SERIAL PRIMARY KEY,
    id_deportista INT NOT NULL REFERENCES deportista(id_usuario) ON DELETE CASCADE,
    id_tecnica INT NOT NULL REFERENCES tecnica(id) ON DELETE RESTRICT,
    fecha_medicion DATE NOT NULL DEFAULT CURRENT_DATE,
    notas TEXT,
    origen VARCHAR(20) NOT NULL DEFAULT 'manual' CHECK (origen IN ('manual', 'yolo')),
    fecha_registro TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 8. TABLA: detalle_medicion
CREATE TABLE IF NOT EXISTS detalle_medicion (
    id SERIAL PRIMARY KEY,
    id_medicion INT NOT NULL REFERENCES medicion(id) ON DELETE CASCADE,
    id_variable INT NOT NULL REFERENCES variable_postural(id) ON DELETE RESTRICT,
    valor_numerico NUMERIC(6,2),
    valoracion VARCHAR(20) CHECK (valoracion IN ('adecuada', 'por_corregir')),
    CONSTRAINT uq_medicion_variable UNIQUE (id_medicion, id_variable)
);

-- 9. TABLA: observacion
CREATE TABLE IF NOT EXISTS observacion (
    id SERIAL PRIMARY KEY,
    id_deportista INT NOT NULL REFERENCES deportista(id_usuario) ON DELETE CASCADE,
    id_entrenador INT NOT NULL REFERENCES entrenador(id_usuario) ON DELETE CASCADE,
    id_medicion INT REFERENCES medicion(id) ON DELETE SET NULL,
    fecha_observacion DATE NOT NULL DEFAULT CURRENT_DATE,
    titulo VARCHAR(150) NOT NULL,
    observacion TEXT NOT NULL,
    recomendacion TEXT NOT NULL,
    fecha_registro TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 10. TABLA: solicitud_eliminacion
CREATE TABLE IF NOT EXISTS solicitud_eliminacion (
    id SERIAL PRIMARY KEY,
    id_deportista INT NOT NULL REFERENCES deportista(id_usuario) ON DELETE CASCADE,
    motivo TEXT,
    fecha_solicitud TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(20) DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'atendida', 'rechazada')),
    fecha_atencion TIMESTAMP WITH TIME ZONE,
    respuesta TEXT
);

-- 11. TABLA: analisis_yolo (preparada para fase futura con IA)
CREATE TABLE IF NOT EXISTS analisis_yolo (
    id SERIAL PRIMARY KEY,
    id_medicion INT REFERENCES medicion(id) ON DELETE SET NULL,
    datos_crudos JSONB,
    confianza NUMERIC(5,2),
    fecha_analisis TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- VISTA: v_historial_mediciones
CREATE OR REPLACE VIEW v_historial_mediciones AS
SELECT 
    m.id AS id_medicion,
    m.id_deportista,
    u.nombres || ' ' || u.apellidos AS deportista_nombre,
    u.correo AS deportista_correo,
    m.fecha_medicion,
    t.id AS id_tecnica,
    t.codigo AS tecnica_codigo,
    t.nombre AS tecnica_nombre,
    m.origen,
    m.notas,
    vp.id AS id_variable,
    vp.nombre AS variable_nombre,
    vp.unidad AS variable_unidad,
    dm.valor_numerico,
    dm.valoracion
FROM medicion m
JOIN deportista d ON m.id_deportista = d.id_usuario
JOIN usuario u ON d.id_usuario = u.id
JOIN tecnica t ON m.id_tecnica = t.id
JOIN detalle_medicion dm ON m.id = dm.id_medicion
JOIN variable_postural vp ON dm.id_variable = vp.id
ORDER BY m.fecha_medicion DESC, m.id DESC;

-- ÍNDICES DE RENDIMIENTO
CREATE INDEX IF NOT EXISTS idx_usuario_correo ON usuario(correo);
CREATE INDEX IF NOT EXISTS idx_deportista_entrenador ON deportista(id_entrenador);
CREATE INDEX IF NOT EXISTS idx_medicion_deportista ON medicion(id_deportista);
CREATE INDEX IF NOT EXISTS idx_medicion_tecnica ON medicion(id_tecnica);
CREATE INDEX IF NOT EXISTS idx_observacion_deportista ON observacion(id_deportista);
CREATE INDEX IF NOT EXISTS idx_observacion_entrenador ON observacion(id_entrenador);

-- ==============================================================================
-- INSERCIÓN DE CATÁLOGOS BASE (Escuela DEMO, 3 Técnicas y 15 Variables)
-- ==============================================================================

-- Escuela DEMO
INSERT INTO escuela (id, nombre, ciudad)
VALUES (1, 'Club Deportivo Leones de Pasto (DEMO)', 'Pasto')
ON CONFLICT (id) DO NOTHING;

-- 3 Técnicas de Baloncesto Formativo
INSERT INTO tecnica (id, codigo, nombre, descripcion) VALUES
(1, 'lanzamiento', 'Técnica de Lanzamiento', 'Mecánica de tiro a canasta: flexión, alineación, extensión y seguimiento.'),
(2, 'defensa', 'Técnica de Posición Defensiva', 'Postura básica defensiva: base amplia, flexión, centro de gravedad bajo y manos activas.'),
(3, 'drible_inicial', 'Técnica de Drible Inicial', 'Control de bote de iniciación: postura equilibrada, balón al costado y visión periférica.')
ON CONFLICT (id) DO NOTHING;

-- 15 Variables Posturales (5 por técnica)
-- Lanzamiento:
INSERT INTO variable_postural (id_tecnica, nombre, descripcion, unidad) VALUES
(1, 'Flexión de rodillas', 'Ángulo de flexión de las rodillas antes de iniciar el impulso.', 'grados'),
(1, 'Alineación del codo', 'Ángulo vertical del codo respecto a la línea hombro-canasta.', 'grados'),
(1, 'Extensión del brazo', 'Ángulo de apertura del brazo en el punto de máxima altura de salida.', 'grados'),
(1, 'Seguimiento de la muñeca', 'Golpe de muñeca hacia abajo al soltar el balón ("quiebre de muñeca").', 'valoracion'),
(1, 'Estabilidad de la base', 'Equilibrio de los pies separados al ancho de hombros al saltar y caer.', 'valoracion')
ON CONFLICT (id_tecnica, nombre) DO NOTHING;

-- Defensa:
INSERT INTO variable_postural (id_tecnica, nombre, descripcion, unidad) VALUES
(2, 'Separación de pies', 'Distancia entre ambos pies para garantizar una base firme y estable.', 'cm'),
(2, 'Flexión de rodillas y caderas', 'Ángulo de flexión que determina el centro de gravedad defensivo.', 'grados'),
(2, 'Inclinación del tronco', 'Ángulo del torso ligeramente inclinado hacia adelante sin perder equilibrio.', 'grados'),
(2, 'Posición de las manos', 'Manos activas a la altura del pecho o balón listas para interceptar.', 'valoracion'),
(2, 'Pies sin cruzarse', 'Desplazamiento lateral manteniendo los pies en deslizamiento sin cruzar.', 'valoracion')
ON CONFLICT (id_tecnica, nombre) DO NOTHING;

-- Drible inicial:
INSERT INTO variable_postural (id_tecnica, nombre, descripcion, unidad) VALUES
(3, 'Flexión de rodillas', 'Flexión para mantener postura baja y controlada durante el bote.', 'grados'),
(3, 'Inclinación del tronco', 'Espalda recta inclinada hacia adelante protegiendo el balón.', 'grados'),
(3, 'Posición del balón al costado', 'Bote al lado del cuerpo fuera de la línea de los pies para protegerlo.', 'valoracion'),
(3, 'Altura del bote', 'Altura máxima alcanzada por el balón respecto al suelo.', 'cm'),
(3, 'Cabeza levantada', 'Mirada al frente controlando el juego sin mirar fijamente el balón.', 'valoracion')
ON CONFLICT (id_tecnica, nombre) DO NOTHING;
