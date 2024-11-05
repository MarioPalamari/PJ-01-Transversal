CREATE DATABSASE bd_restaurante;

use bd_restaurante;

create table tbl_camareros {
    id_camarero int auto_increment not null,
    nombre_camarero varchar(50) not null,
    apellido_camarero varchar(50) not null,
    password_camarero varchar(255) not null;
}

