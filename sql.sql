CREATE SCHEMA IF NOT EXISTS museo_natural DEFAULT CHARACTER SET utf8;
USE museo_natural;

CREATE TABLE rol (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(45) NOT NULL
);

CREATE TABLE usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(45) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  contraseña VARCHAR(255) NOT NULL,
  rol_id INT NOT NULL,
  FOREIGN KEY (rol_id) REFERENCES rol(id)
);

CREATE TABLE categoria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(45) NOT NULL
);

CREATE TABLE imagen (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(100) NOT NULL,
  autor VARCHAR(100),
  descripcion TEXT,
  fecha DATE,
  archivo VARCHAR(255),
  categoria_id INT,
  FOREIGN KEY (categoria_id) REFERENCES categoria(id)
);

CREATE TABLE comentario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  texto VARCHAR(250),
  estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
  usuario_id INT,
  imagen_id INT,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id),
  FOREIGN KEY (imagen_id) REFERENCES imagen(id)
);

CREATE TABLE evento (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  fecha DATETIME NOT NULL,
  descripcion TEXT,
  imagen_id INT NULL,
  FOREIGN KEY (imagen_id) REFERENCES imagen(id)
);
