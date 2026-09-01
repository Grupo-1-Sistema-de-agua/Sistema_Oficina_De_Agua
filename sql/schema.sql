-- NOTA: archivo solo como referencia del esquema.
-- La fuente de verdad ahora son las migraciones en app/Database/Migrations/

CREATE TABLE Tb_Roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE Tb_Usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    rol_id INT NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES Tb_Roles(id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE Tb_Clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    direccion_principal VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Tb_Tipos_Servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    volumen_incluido_litros INT,
    es_servicio BOOLEAN NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

CREATE TABLE Tb_Sectores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE Tb_Contadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_fisico VARCHAR(30) NOT NULL UNIQUE,
    direccion_servicio VARCHAR(255) NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    cliente_id INT NOT NULL,
    tipo_servicio_id INT NOT NULL,
    sector_id INT NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES Tb_Clientes(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (tipo_servicio_id) REFERENCES Tb_Tipos_Servicios(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (sector_id) REFERENCES Tb_Sectores(id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE Tb_Tarifas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    precio DECIMAL(10,2) NOT NULL,
    vigente_desde DATETIME NOT NULL,
    vigente_hasta DATETIME,
    tipo_servicio_id INT NOT NULL,
    FOREIGN KEY (tipo_servicio_id) REFERENCES Tb_Tipos_Servicios(id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE Tb_Lecturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_recibo VARCHAR(20) NOT NULL UNIQUE,
    lectura_anterior INT NOT NULL DEFAULT 0,
    lectura_actual INT NOT NULL,
    consumo_litros INT NOT NULL,
    fecha DATETIME NOT NULL,
    contador_id INT NOT NULL,
    tarifa_base_id INT NOT NULL,
    tarifa_exceso_id INT,
    usuario_lector_id INT NOT NULL,
    FOREIGN KEY (contador_id) REFERENCES Tb_Contadores(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (tarifa_base_id) REFERENCES Tb_Tarifas(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (tarifa_exceso_id) REFERENCES Tb_Tarifas(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (usuario_lector_id) REFERENCES Tb_Usuarios(id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;

CREATE TABLE Tb_Metodos_Pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE Tb_Pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    monto DECIMAL(10,2) NOT NULL,
    fecha_pago DATETIME NOT NULL,
    lectura_id INT NOT NULL UNIQUE,
    metodo_id INT NOT NULL,
    usuario_registro_id INT NOT NULL,
    FOREIGN KEY (lectura_id) REFERENCES Tb_Lecturas(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (metodo_id) REFERENCES Tb_Metodos_Pago(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
    FOREIGN KEY (usuario_registro_id) REFERENCES Tb_Usuarios(id) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;
