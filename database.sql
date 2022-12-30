DROP DATABASE IF EXISTS api_rest_central;
CREATE DATABASE IF NOT EXISTS api_rest_central;
USE api_rest_central;

CREATE TABLE roles(
    id INT AUTO_INCREMENT NOT NULL,
    rols VARCHAR(20) not NULL unique,
    created_at DATETIME DEFAULT null,
    updated_at DATETIME DEFAULT null,
    CONSTRAINT pk_role PRIMARY KEY(id)
)ENGINE=INNODB;

INSERT INTO roles(rols, created_at) VALUES('Administrador', now());
INSERT INTO roles(rols, created_at) VALUES('Empleado', now());
INSERT INTO roles(rols, created_at) VALUES('Usuario', now());
INSERT INTO roles(rols, created_at) VALUES('Familiar', now());

CREATE TABLE EMPLOYEES(
    id int AUTO_INCREMENT not NULL,
    name VARCHAR(30) not null,
    lastname varchar(30) not null,
    type_document varchar(30) not null,
    document varchar(20) not null unique,
    phone varchar(20) not null,
    email varchar(50) not null unique,
    password varchar(70) not null,
    id_rol int not null,
    created_at DATETIME DEFAULT null,
    updated_at DATETIME DEFAULT null,
    CONSTRAINT pk_employes PRIMARY KEY(id),
    CONSTRAINT fk_employes_rol FOREIGN KEY(id_rol) REFERENCES roles(id)
)ENGINE=INNODB;

CREATE TABLE specialities(
    id int AUTO_INCREMENT NOT NULL,
    specialitys varchar(30) not null unique,
    created_at DATETIME DEFAULT null,
    updated_at DATETIME DEFAULT null,
    CONSTRAINT pk_specialitys PRIMARY KEY(id)
)ENGINE=INNODB;

CREATE TABLE doctors(
    id int AUTO_INCREMENT not null,
    name varchar(30) not null,
    lastname varchar(30) not null,
    type_document varchar(30) not null,
    document varchar(20) not null unique,
    phone varchar(20) not null,
    id_speciality int  not null,
    created_at DATETIME DEFAULT null,
    updated_at DATETIME DEFAULT null,
    CONSTRAINT pk_doctors PRIMARY KEY(id),
    CONSTRAINT fk_doctors_specialitys FOREIGN KEY(id_speciality) REFERENCES specialities(id)
)ENGINE=INNODB;

CREATE TABLE status(
    id int AUTO_INCREMENT not null,
    status varchar(30) not null unique,
    created_at DATETIME DEFAULT null,
    updated_at DATETIME DEFAULT null,
    CONSTRAINT pk_status PRIMARY KEY(id)
)ENGINE=INNODB;

INSERT INTO status(status, created_at) VALUES('Actualizar', now());
INSERT INTO status(status, created_at) VALUES('Confirmada', now());
INSERT INTO status(status, created_at) VALUES('Eliminar', now());
INSERT INTO status(status, created_at) VALUES('En Proceso', now());
INSERT INTO status(status, created_at) VALUES('Pendiente', now());

CREATE TABLE users(
    id int AUTO_INCREMENT not null,
    name varchar(30) not null,
    lastname varchar(30) not null,
    type_document varchar(30) not null,
    document varchar(20) not null unique,
    phone varchar(10) not null,
    email varchar(40) not null unique,
    password varchar(70) not null,
    id_rol int not null,
    created_at DATETIME DEFAULT null,
    updated_at DATETIME DEFAULT null,
    CONSTRAINT pk_users PRIMARY KEY(id),
    CONSTRAINT fk_users_role FOREIGN KEY (id_rol) REFERENCES roles(id)
)ENGINE=INNODB;

CREATE TABLE families(
    id int AUTO_INCREMENT not null,
    name varchar(30) not null,
    lastname varchar(20) not null,
    type_document varchar(30) not null,
    document varchar(20) not null unique,
    phone varchar(10) not null,
    id_user int not null,
    id_rol int not null,
    CONSTRAINT pk_family PRIMARY KEY(id),
    CONSTRAINT fk_familys_role FOREIGN KEY (id_rol) REFERENCES roles (id),
    CONSTRAINT fk_familys_users FOREIGN KEY (id_user) REFERENCES users(id)
)ENGINE=INNODB;

create TABLE cites(
    id int AUTO_INCREMENT not null,
    name varchar(30) not null,
    lastname varchar(30) not null,
    type_document varchar(30) not null,
    document varchar(30) not null,
    phone varchar(10) not null,
    eps varchar(30) not null,
    id_speciality int not null,
    id_doctors int null,
    required_files varchar(30) null,
    orden varchar(255) null,
    authorization varchar(255) null,
    id_status int not null,
    recommendations varchar(255) null,
    id_family int null,
    id_user int not null,
    id_employee int null,
    created_at DATETIME DEFAULT null,
    updated_at DATETIME DEFAULT null,
    CONSTRAINT pk_cites PRIMARY KEY(id),
    CONSTRAINT fk_cites_speciality FOREIGN KEY(id_speciality) REFERENCES specialities(id),
    CONSTRAINT fk_cites_doctors FOREIGN KEY(id_doctors) REFERENCES doctors(id),
    CONSTRAINT fk_cites_status FOREIGN KEY (id_status) REFERENCES status(id),
    CONSTRAINT fk_cites_familys FOREIGN KEY (id_family) REFERENCES familys(id),
    CONSTRAINT fk_cites_users FOREIGN KEY (id_user) REFERENCES users(id),
    CONSTRAINT fk_cites_employes FOREIGN KEY (id_employee) REFERENCES EMPLOYESS(id)
)ENGINE=INNODB;


create TABLE notifications(
    id int AUTO_INCREMENT not null,
    id_user int not null,
    notification varchar(255) not null,
    destination varchar(20) not null,
    created_at DATETIME DEFAULT null,
    updated_at DATETIME DEFAULT null,
    CONSTRAINT pk_notifications PRIMARY KEY(id),
    CONSTRAINT fk_notification_users FOREIGN KEY(id_user) REFERENCES users(id)
)ENGINE=INNODB;

" pendiente table de cancelacion
