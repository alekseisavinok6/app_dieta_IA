create database prueba_dietaapp;
use prueba_dietaapp;

create table clientes(
    id_cliente int auto_increment primary key,
    nombre varchar(30) not null,
    apellido varchar(30) not null,
    correo varchar(50) unique not null,
    password varchar(255) not null,
    edad int not null,
    sexo enum('Hombre','Mujer') not null,
    altura int not null,
    peso int not null,
    peso_deseado int,
    enfermedades text,
    alergias text,
    intolerancias text
);

insert into clientes (nombre, apellido, correo, password, edad, sexo, altura,peso) 
values ('Israel', 'Quiroz', 'isra@hotmail.com', '123456789012',24,'Hombre', 178, 60);

-- LA TABLA CLIENTES DOS, PENSEMOS SI EN VDD ES NECESARIA, AL FINAL LO QUE QUEREMOS ES DARLE LA DIETA DEPENDIENDO LO QUE CALCULEMOS
-- DEL CLIENTE, INCLUSO YO CREO QUE NO LES INTERESARÍA A LOS CLIENTES SABER VALORES QUE PROBABLEMENTE NO SEPAN LO QUE SON.
-- create table clientes2 (
--     IMC INT,
-- );

-- MEJOR USAR UNA ESTRUCTURA PLANA PARA PODER HACER CONSULTAS MÁS FÁCILES Y 
create table alimentos(
    id_alimentos int auto_increment primary key,
    nombre varchar(50) not null,
    alergenos text,
    intolerancias text,
    valor_calórico int not null,
    peso_neto varchar(10) not null,
    peso_bruto varchar(10) not null
    cantidad_vp varchar(10) not null,
    -- nutriente enum('Carbohidratos','Proteínas','Grasas') not null,
    carbohidratos decimal(2,2) not null,
    proteinas decimal(2,2) not null,
    grasas decimal(2,2) not null,
    -- lipidos enum('Saturados','Monoinsaturados','Poliinsaturados') not null,
    saturados decimal(2,2) not null,
    monoinsaturados decimal(2,2) not null,
    poliinsaturados decimal(2,2) not null,
);


create table dieta (
  id int AUTO_INCREMENT primary key,
  fecha date not null,
  datos_json json not null
);

-- SE ME OCURRE QUE EN LA DIETA CREEMOS ALGO ASÍ PARA SIMPLIFICARNOS, YA QUE UNA VEZ QUE NOS DE 
-- LA DIETA LA IA NO VAMOS A OPERAR CON LOS DATOS. Y PODRÍAMOS MANEJAR MEJOR LAS COSAS CON UN JSON EN JS.
-- INSERT INTO dieta (fecha, datos_json)
-- VALUES (
--   CURDATE(),
--   '{
--     "dia1": {
--       "desayuno": {
--         "nombre_plato": "Avena con frutas y semillas",
--         "ingredientes": [
--           {
--             "nombre": "Avena",
--             "nutriente": "Carbohidratos",
--             "valorCalórico": 194,
--             "alergenos": ["Gluten"],
--             "cantidad_vp": "50g",
--             "peso_neto": "50g",
--             "peso_bruto": "50g"
--           }
--         ],
--         "valor_calórico_total": 363,
--         "recomendación": "Ideal para un desayuno completo y energético sin lácteos."
--       }
--     }
--   }'
-- );