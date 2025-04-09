-- Crear la base de datos db_prueba_afpe
CREATE DATABASE IF NOT EXISTS db_prueba_tecnica_afpe;

USE db_prueba_tecnica_afpe;

-- Crear tabla de estados
CREATE TABLE estados (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre_estado VARCHAR(50) NOT NULL,
    descripcion TEXT
);

-- Agregar estados por defecto
INSERT INTO estados (nombre_estado, descripcion)
VALUES 
    ('activo', 'Elemento actualmente activo y disponible'),
    ('inactivo', 'Elemento inactivo, no visible o disponible'),
	('publicado', 'Elemento publicado y visual'),
	('sin publicar', 'Elemento no publicado');

-- Crear tabla de roles
CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL,
    descripcion TEXT
);

-- Agregar roles por defecto
INSERT INTO roles (nombre_rol, descripcion)
VALUES 
    ('admin', 'Administrador con permisos completos'),
    ('autor', 'Usuario autorizado para crear publicaciones'),
    ('lector', 'Usuario con permisos de solo lectura');

-- Crear tabla de usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    id_rol INT,
    id_estado INT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol),
    FOREIGN KEY (id_estado) REFERENCES estados(id_estado)
);

-- Crear tabla de entradas
CREATE TABLE entradas (
    id_entrada INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_estado INT,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    fecha_entrada DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_estado) REFERENCES estados(id_estado)
);

-- Crear tabla de comentarios
CREATE TABLE comentarios (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_entrada INT,
    id_usuario INT,
    id_estado INT,
    comentario TEXT NOT NULL,
    fecha_comentario DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_entrada) REFERENCES entradas(id_entrada),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_estado) REFERENCES estados(id_estado)
);

-- Crear tabla de etiquetas
CREATE TABLE etiquetas (
    id_etiqueta INT AUTO_INCREMENT PRIMARY KEY,
    id_estado INT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    FOREIGN KEY (id_estado) REFERENCES estados(id_estado)
);

-- Agregar etiquetas por defecto
INSERT INTO etiquetas (nombre, descripcion, id_estado)
VALUES 
    ('tecnología', 'Etiquetas relacionadas con avances tecnológicos', 1),
    ('salud', 'Etiquetas sobre bienestar y salud', 1),
    ('educación', 'Etiquetas sobre el ámbito educativo', 1);

-- Crear tabla relacion entradas y etiquetas
CREATE TABLE entradas_etiquetas (
    id_entrada INT,
    id_etiqueta INT,
    PRIMARY KEY (id_entrada, id_etiqueta),
    FOREIGN KEY (id_entrada) REFERENCES entradas(id_entrada),
    FOREIGN KEY (id_etiqueta) REFERENCES etiquetas(id_etiqueta)
);

