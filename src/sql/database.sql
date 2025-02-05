CREATE DATABASE StreamWeb;
USE StreamWeb;

-- Tabla de usuarios
CREATE TABLE Usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    edad INT NOT NULL,
    plan_base ENUM('Básico', 'Estándar', 'Premium') NOT NULL,
    duracion_suscripcion ENUM('Mensual', 'Anual') NOT NULL
);

-- Tabla de paquetes
CREATE TABLE Paquetes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre ENUM('Deporte', 'Cine', 'Infantil') NOT NULL UNIQUE,
    precio DECIMAL(5,2) NOT NULL
);

-- Insertar precios de paquetes
INSERT INTO Paquetes (nombre, precio) VALUES
('Deporte', 6.99),
('Cine', 7.99),
('Infantil', 4.99);

-- Tabla de suscripciones de usuarios a paquetes adicionales
CREATE TABLE Suscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    paquete_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES Usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (paquete_id) REFERENCES Paquetes(id) ON DELETE CASCADE
);

-- Tabla de precios de planes base
CREATE TABLE Planes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre ENUM('Básico', 'Estándar', 'Premium') NOT NULL UNIQUE,
    precio DECIMAL(5,2) NOT NULL
);

-- Insertar precios de planes
INSERT INTO Planes (nombre, precio) VALUES
('Básico', 9.99),
('Estándar', 13.99),
('Premium', 17.99);