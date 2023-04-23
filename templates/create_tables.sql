//Sentencias de creación de tablas

CREATE TABLE SCIENTIST(
    idScientist int AUTO_INCREMENT primary key,
    name varchar(300),
    fechas text,
    content text,
    fotoPortada text
)

CREATE table FOTO(
    idFoto int AUTO_INCREMENT primary key,
    direccion varchar(200),
    idScientist int,
    FOREIGN KEY (idScientist) REFERENCES SCIENTIST(idScientist)
);

CREATE table CENSURA(
    id int AUTO_INCREMENT primary key,
    palabra varchar(200)
);
create table COMENTARIO(
    idComentario int AUTO_INCREMENT primary key,
    content text,
    fecha datetime,
    user varchar(256),
    mail varchar(256),
    idScientist int,
    FOREIGN KEY (idScientist) REFERENCES SCIENTIST(idScientist)
);

create table USUARIO(
    idUsuario int AUTO_INCREMENT primary key,
    username varchar(200) not null,
    email varchar(256) not null,
    pass varchar(200) not null,
    tipo varchar(200) not null
);

create table HASHTAG(
    idHashtag int AUTO_INCREMENT primary key,
    hashtag varchar(200) not null,
    idScientist int,
    FOREIGN KEY (idScientist) REFERENCES SCIENTIST(idScientist)
);

