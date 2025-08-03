CREATE DATABASE SPA_ONCE;

-- Tabla: USUARIOS
CREATE TABLE USUARIOS (
    ID_Usuario INT AUTO_INCREMENT PRIMARY KEY,
    Pass VARCHAR(100) NOT NULL,
    Estado_Usuario ENUM('Habilitado', 'Inhabilitado') NOT NULL,
    Tipo_Rol ENUM('Administrador', 'Gerente', 'Cliente', 'Acompañante', 'Jubilado', 'Pensionista') NOT NULL
);

-- Tabla: PERSONAS
CREATE TABLE PERSONAS (
    CI VARCHAR(20) PRIMARY KEY,
    Nombre_Completo VARCHAR(100) NOT NULL,
    Tipo_Persona ENUM('Jubilado', 'Pensionista', 'Persona Contado') NOT NULL,
    Telefono VARCHAR(15),
    Email VARCHAR(100),
    Estado_Persona ENUM('Activo', 'Inactivo', 'Eliminado') NOT NULL,
    Sexo ENUM('Masculino', 'Femenino', 'Otro') NOT NULL
);

-- Relación: POSEEN (1 persona : N usuarios)
ALTER TABLE USUARIOS
ADD CONSTRAINT FK_UsuarioPersona
FOREIGN KEY (ID_Usuario) REFERENCES PERSONAS(CI); -- Aquí tal vez debas usar otro campo para relacionar

-- Tabla: SERVICIO
CREATE TABLE SERVICIO (
    ID_Servicio INT AUTO_INCREMENT PRIMARY KEY,
    Nombre_Instalacion VARCHAR(100) NOT NULL,
    Tipo_Servicio ENUM('Masaje Tradicional', 'Masaje Oriental', 'Yoga', 'Pilates Meditación', 'Piscina de Barro', 'Sauna', 'Caminata') NOT NULL,
    Costo_Servicio DECIMAL(10,2) NOT NULL,
    Capacidad_Diaria INT NOT NULL,
    Estado_Servicio ENUM('Habilitado', 'Inhabilitado') NOT NULL,
    Imagenes_Servicio TEXT
);

-- Tabla: HORARIOS
CREATE TABLE HORARIOS (
    ID_Horario INT AUTO_INCREMENT PRIMARY KEY,
    Fecha DATE NOT NULL,
    Hora_Inicio TIME NOT NULL,
    Hora_Fin TIME NOT NULL,
    Dia VARCHAR(20) NOT NULL,
    Estado_Horarios ENUM('Disponible', 'Ocupado') NOT NULL
);

-- Tabla: RESERVA
CREATE TABLE RESERVA (
    ID_Reserva INT AUTO_INCREMENT PRIMARY KEY,
    CI_Persona VARCHAR(20) NOT NULL,
    ID_Servicio INT NOT NULL,
    ID_Horario INT NOT NULL,
    Estado_Reserva ENUM('Pendiente', 'Confirmada', 'Cancelada') NOT NULL,
    Tipo_Pago ENUM('Tarjeta de Credito', 'Debito', 'Contado') NOT NULL,
    Pago_Reserva DECIMAL(10,2) NOT NULL,
    Realizacion_Reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (CI_Persona) REFERENCES PERSONAS(CI),
    FOREIGN KEY (ID_Servicio) REFERENCES SERVICIO(ID_Servicio),
    FOREIGN KEY (ID_Horario) REFERENCES HORARIOS(ID_Horario)
);

-- Relación: APRUEBA
CREATE TABLE APRUEBA (
    ID_Usuario INT,
    ID_Reserva INT,
    PRIMARY KEY (ID_Usuario, ID_Reserva),
    FOREIGN KEY (ID_Usuario) REFERENCES USUARIOS(ID_Usuario),
    FOREIGN KEY (ID_Reserva) REFERENCES RESERVA(ID_Reserva)
);

-- Inserts para PERSONAS (20 registros, incluyendo Lucas y Romina)

INSERT INTO PERSONAS (CI, Nombre_Completo, Tipo_Persona, Telefono, Email, Estado_Persona, Sexo) VALUES
('12345678-9', 'Lucas Valerio', 'Jubilado', '099123456', 'lucas.valerio@example.com', 'Activo', 'Masculino'),
('98765432-1', 'Romina Valerio', 'Pensionista', '098765432', 'romina.valerio@example.com', 'Activo', 'Femenino'),
('11111111-1', 'María Pérez', 'Persona Contado', '091111111', 'maria.perez@example.com', 'Activo', 'Femenino'),
('22222222-2', 'Carlos Gómez', 'Jubilado', '092222222', 'carlos.gomez@example.com', 'Activo', 'Masculino'),
('33333333-3', 'Ana Rodríguez', 'Pensionista', '093333333', 'ana.rodriguez@example.com', 'Activo', 'Femenino'),
('44444444-4', 'Jorge Martínez', 'Persona Contado', '094444444', 'jorge.martinez@example.com', 'Activo', 'Masculino'),
('55555555-5', 'Lucía Fernández', 'Jubilado', '095555555', 'lucia.fernandez@example.com', 'Activo', 'Femenino'),
('66666666-6', 'Miguel Torres', 'Pensionista', '096666666', 'miguel.torres@example.com', 'Activo', 'Masculino'),
('77777777-7', 'Sofía Sánchez', 'Persona Contado', '097777777', 'sofia.sanchez@example.com', 'Activo', 'Femenino'),
('88888888-8', 'Pedro Díaz', 'Jubilado', '098888888', 'pedro.diaz@example.com', 'Activo', 'Masculino'),
('99999999-9', 'Valentina López', 'Pensionista', '099999999', 'valentina.lopez@example.com', 'Activo', 'Femenino'),
('10101010-1', 'Diego Herrera', 'Persona Contado', '090101010', 'diego.herrera@example.com', 'Activo', 'Masculino'),
('12121212-1', 'Camila Rojas', 'Jubilado', '091212121', 'camila.rojas@example.com', 'Activo', 'Femenino'),
('13131313-1', 'Esteban Morales', 'Pensionista', '092313131', 'esteban.morales@example.com', 'Activo', 'Masculino'),
('14141414-1', 'Laura Castro', 'Persona Contado', '093414141', 'laura.castro@example.com', 'Activo', 'Femenino'),
('15151515-1', 'Andrés Vega', 'Jubilado', '094515151', 'andres.vega@example.com', 'Activo', 'Masculino'),
('16161616-1', 'Natalia Cruz', 'Pensionista', '095616161', 'natalia.cruz@example.com', 'Activo', 'Femenino'),
('17171717-1', 'Fernando Ruiz', 'Persona Contado', '096717171', 'fernando.ruiz@example.com', 'Activo', 'Masculino'),
('18181818-1', 'Marta Ortiz', 'Jubilado', '097818181', 'marta.ortiz@example.com', 'Activo', 'Femenino'),
('19191919-1', 'Raúl Jiménez', 'Pensionista', '098919191', 'raul.jimenez@example.com', 'Activo', 'Masculino');

-- Inserts para USUARIOS (20 registros, con un solo administrador)

INSERT INTO USUARIOS (Pass, Estado_Usuario, Tipo_Rol) VALUES
('hashed_password_lucas', 'Habilitado', 'Administrador'),  -- Lucas Valerio
('hashed_password_romina', 'Habilitado', 'Gerente'),
('hashed_password_maria', 'Habilitado', 'Cliente'),
('hashed_password_carlos', 'Inhabilitado', 'Acompañante'),
('hashed_password_ana', 'Habilitado', 'Cliente'),
('hashed_password_jorge', 'Habilitado', 'Gerente'),
('hashed_password_lucia', 'Habilitado', 'Cliente'),
('hashed_password_miguel', 'Habilitado', 'Cliente'),
('hashed_password_sofia', 'Habilitado', 'Cliente'),
('hashed_password_pedro', 'Habilitado', 'Cliente'),
('hashed_password_valentina', 'Habilitado', 'Cliente'),
('hashed_password_diego', 'Habilitado', 'Gerente'),
('hashed_password_camila', 'Habilitado', 'Cliente'),
('hashed_password_esteban', 'Habilitado', 'Cliente'),
('hashed_password_laura', 'Habilitado', 'Cliente'),
('hashed_password_andres', 'Habilitado', 'Cliente'),
('hashed_password_natalia', 'Habilitado', 'Cliente'),
('hashed_password_fernando', 'Habilitado', 'Cliente'),
('hashed_password_marta', 'Habilitado', 'Cliente'),
('hashed_password_raul', 'Habilitado', 'Cliente');

