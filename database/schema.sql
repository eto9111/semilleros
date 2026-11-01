-- Schema para el proyecto Baloncesto YOLO - Semillero FOCUS (Universidad CESMAG)
-- Base de datos: baloncesto_yolo

-- Tabla principal de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    rol VARCHAR(20) NOT NULL CHECK (rol IN ('deportista', 'entrenador')),
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    escuela_club VARCHAR(150) NOT NULL,
    terminos_aceptados BOOLEAN NOT NULL DEFAULT FALSE,
    fecha_registro TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN NOT NULL DEFAULT TRUE
);

-- Perfil de entrenador
CREATE TABLE IF NOT EXISTS entrenadores (
    usuario_id INT PRIMARY KEY REFERENCES usuarios(id) ON DELETE CASCADE,
    especialidad VARCHAR(100) DEFAULT 'Entrenador de Baloncesto Formativo',
    telefono VARCHAR(30),
    fecha_creacion TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Perfil de deportista
CREATE TABLE IF NOT EXISTS deportistas (
    usuario_id INT PRIMARY KEY REFERENCES usuarios(id) ON DELETE CASCADE,
    fecha_nacimiento DATE NOT NULL,
    entrenador_id INT NOT NULL REFERENCES entrenadores(usuario_id) ON DELETE RESTRICT,
    nombre_acudiente VARCHAR(150),
    autorizacion_acudiente BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Mediciones posturales (manuales o futuras YOLO)
CREATE TABLE IF NOT EXISTS mediciones (
    id SERIAL PRIMARY KEY,
    deportista_id INT NOT NULL REFERENCES deportistas(usuario_id) ON DELETE CASCADE,
    fecha_medicion DATE NOT NULL,
    tecnica VARCHAR(30) NOT NULL CHECK (tecnica IN ('lanzamiento', 'defensa', 'drible_inicial')),
    variables JSONB NOT NULL,
    notas TEXT,
    origen VARCHAR(20) NOT NULL DEFAULT 'manual' CHECK (origen IN ('manual', 'yolo')),
    fecha_registro TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Observaciones y recomendaciones pedagógicas realizadas por el entrenador
CREATE TABLE IF NOT EXISTS observaciones (
    id SERIAL PRIMARY KEY,
    deportista_id INT NOT NULL REFERENCES deportistas(usuario_id) ON DELETE CASCADE,
    entrenador_id INT NOT NULL REFERENCES entrenadores(usuario_id) ON DELETE CASCADE,
    medicion_id INT REFERENCES mediciones(id) ON DELETE SET NULL,
    fecha_observacion DATE NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    observacion TEXT NOT NULL,
    recomendacion TEXT NOT NULL,
    fecha_registro TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Solicitudes de retiro y eliminación de datos (Habeas Data / Ley 1581 de 2012)
CREATE TABLE IF NOT EXISTS solicitudes_eliminacion (
    id SERIAL PRIMARY KEY,
    deportista_id INT NOT NULL REFERENCES deportistas(usuario_id) ON DELETE CASCADE,
    motivo TEXT,
    fecha_solicitud TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(20) DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'procesada', 'cancelada'))
);

-- Índices de consulta frecuente
CREATE INDEX IF NOT EXISTS idx_deportistas_entrenador ON deportistas(entrenador_id);
CREATE INDEX IF NOT EXISTS idx_mediciones_deportista ON mediciones(deportista_id);
CREATE INDEX IF NOT EXISTS idx_mediciones_tecnica ON mediciones(tecnica);
CREATE INDEX IF NOT EXISTS idx_observaciones_deportista ON observaciones(deportista_id);
CREATE INDEX IF NOT EXISTS idx_observaciones_entrenador ON observaciones(entrenador_id);
