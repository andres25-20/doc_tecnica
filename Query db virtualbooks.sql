-- Crear la base de datos virtualbooks
CREATE DATABASE IF NOT EXISTS virtualbooks;

USE virtualbooks;

-- Crear tabla de estados
CREATE TABLE estado (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);


-- Crear tabla de autor
CREATE TABLE autor (
    id_autor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Crear tabla de categoria
CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Crear tabla de libro
CREATE TABLE libro (
    id_libro INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    id_estado INT,
    id_autor INT,
    id_categoria INT,
    FOREIGN KEY (id_estado) REFERENCES estado(id_estado),
    FOREIGN KEY (id_autor) REFERENCES autor(id_autor),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
);

-- Crear tabla de miembro
CREATE TABLE miembro (
    id_miembro INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_estado INT,
    FOREIGN KEY (id_estado) REFERENCES estado(id_estado)
);


-- Crear tabla de relacion miembro con libros miembro_libro
CREATE TABLE miembro_libro (
    id_miembro INT,
    id_libro INT,
    PRIMARY KEY (id_miembro, id_libro),
    FOREIGN KEY (id_miembro) REFERENCES miembro(id_miembro),
    FOREIGN KEY (id_libro) REFERENCES libro(id_libro)
);


-- Crear tabla biblioteca
CREATE TABLE biblioteca (
    id_biblioteca INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

