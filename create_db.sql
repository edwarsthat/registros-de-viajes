CREATE DATABASE IF NOT EXISTS `prueba_tecnica`;

USE `prueba_tecnica`;

CREATE TABLE IF NOT EXISTS `carro` ( 
  `idcarro` int NOT NULL AUTO_INCREMENT, 
  `placa` varchar(45) DEFAULT NULL, 
  `color` varchar(45) DEFAULT NULL, 
  `fecha_ingreso` timestamp NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`idcarro`) 
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `ciudad` ( 
  `idciudad` int NOT NULL AUTO_INCREMENT, 
  `nombre` varchar(45) DEFAULT NULL, 
  `activo` tinyint(1) DEFAULT NULL, 
  PRIMARY KEY (`idciudad`) 
) ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `viaje` ( 
  `idviaje` int NOT NULL AUTO_INCREMENT, 
  `idcarro` int NOT NULL, 
  `idciudad_origen` int DEFAULT NULL, 
  `idciudad_destino` int DEFAULT NULL, 
  `tiempo_horas` int DEFAULT NULL, 
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`idviaje`) 
) ENGINE=InnoDB AUTO_INCREMENT=1;

-- Data para poblar la base de datos
INSERT INTO prueba_tecnica.carro (idcarro, placa, color, fecha_ingreso) VALUES(1, 'AAA123', 'Azul', '2025-07-10 16:36:14');
INSERT INTO prueba_tecnica.carro (idcarro, placa, color, fecha_ingreso) VALUES(2, 'BBB456', 'Verde', '2025-07-28 16:36:14');
INSERT INTO prueba_tecnica.carro (idcarro, placa, color, fecha_ingreso) VALUES(3, 'CCC789', 'Rojo', '2025-08-08 16:36:14');
INSERT INTO prueba_tecnica.carro (idcarro, placa, color, fecha_ingreso) VALUES(4, 'DDD963', 'Azul', '2025-08-20 16:36:15');
INSERT INTO prueba_tecnica.carro (idcarro, placa, color, fecha_ingreso) VALUES(5, 'EEE852', 'Rojo', '2025-08-28 16:36:15');
INSERT INTO prueba_tecnica.carro (idcarro, placa, color, fecha_ingreso) VALUES(6, 'FFF741', 'Azul', '2025-10-15 16:36:15');

INSERT INTO prueba_tecnica.ciudad (idciudad, nombre, activo) VALUES(1, 'Cali', 1);
INSERT INTO prueba_tecnica.ciudad (idciudad, nombre, activo) VALUES(2, 'Bogota', 0);
INSERT INTO prueba_tecnica.ciudad (idciudad, nombre, activo) VALUES(3, 'Medellin', 1);

INSERT INTO prueba_tecnica.viaje (idviaje, idcarro, idciudad_origen, idciudad_destino, tiempo_horas, fecha) VALUES(1, 1, 3, 2, 8, '2025-09-15 17:03:53');
INSERT INTO prueba_tecnica.viaje (idviaje, idcarro, idciudad_origen, idciudad_destino, tiempo_horas, fecha) VALUES(2, 2, 2, 3, 6, '2025-09-25 17:03:53');
INSERT INTO prueba_tecnica.viaje (idviaje, idcarro, idciudad_origen, idciudad_destino, tiempo_horas, fecha) VALUES(3, 2, 3, 1, 12, '2025-09-29 17:03:53');
INSERT INTO prueba_tecnica.viaje (idviaje, idcarro, idciudad_origen, idciudad_destino, tiempo_horas, fecha) VALUES(4, 3, 1, 2, 10, '2025-10-08 17:04:35');
INSERT INTO prueba_tecnica.viaje (idviaje, idcarro, idciudad_origen, idciudad_destino, tiempo_horas, fecha) VALUES(5, 1, 1, 3, 15, '2025-10-10 17:23:29');
INSERT INTO prueba_tecnica.viaje (idviaje, idcarro, idciudad_origen, idciudad_destino, tiempo_horas, fecha) VALUES(6, 2, 1, 2, 7, '2025-10-15 17:23:29');
INSERT INTO prueba_tecnica.viaje (idviaje, idcarro, idciudad_origen, idciudad_destino, tiempo_horas, fecha) VALUES(7, 3, 2, 1, 9, '2025-10-29 17:23:54');
