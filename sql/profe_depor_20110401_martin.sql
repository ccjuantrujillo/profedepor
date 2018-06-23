/*
SQLyog Ultimate - MySQL GUI v8.2 
MySQL - 5.1.41 : Database - elprofe_depor
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `depor_acierto` */

DROP TABLE IF EXISTS `depor_acierto`;

CREATE TABLE `depor_acierto` (
  `acierto_id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_id` int(11) NOT NULL,
  `fase_id` int(11) NOT NULL,
  `torneo_id` int(11) NOT NULL,
  `valor_inicial` int(11) NOT NULL DEFAULT '0',
  `valor_final` int(11) NOT NULL DEFAULT '0',
  `puntaje` int(11) NOT NULL DEFAULT '0',
  `registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`acierto_id`),
  UNIQUE KEY `fecha_id_valor_inicial_valor_final` (`fecha_id`,`valor_inicial`,`valor_final`),
  KEY `fk_depor_acierto_depor_fecha1` (`fecha_id`,`fase_id`,`torneo_id`),
  CONSTRAINT `fk_depor_acierto_depor_fecha1` FOREIGN KEY (`fecha_id`, `fase_id`, `torneo_id`) REFERENCES `depor_fecha` (`fecha_id`, `fase_id`, `torneo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8;

/*Table structure for table `depor_adicional` */

DROP TABLE IF EXISTS `depor_adicional`;

CREATE TABLE `depor_adicional` (
  `adicional_id` int(11) NOT NULL,
  `torneo_id` int(11) NOT NULL,
  `fase_id` int(11) NOT NULL,
  `fecha_id` int(11) NOT NULL,
  `cantidad_aciertos` int(11) DEFAULT NULL,
  `puntaje` int(11) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` varchar(45) DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`adicional_id`),
  KEY `fk_depor_adicional_depor_fecha1` (`fecha_id`,`fase_id`,`torneo_id`),
  CONSTRAINT `fk_depor_adicional_depor_fecha1` FOREIGN KEY (`fecha_id`, `fase_id`, `torneo_id`) REFERENCES `depor_fecha` (`fecha_id`, `fase_id`, `torneo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `depor_alternativa` */

DROP TABLE IF EXISTS `depor_alternativa`;

CREATE TABLE `depor_alternativa` (
  `alternativa_id` int(11) NOT NULL AUTO_INCREMENT,
  `pregunta_id` int(11) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `puntaje` int(11) DEFAULT NULL,
  `flag_respuesta` char(1) DEFAULT '0',
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`alternativa_id`),
  KEY `fk_depor_alternativa_depor_pregunta1` (`pregunta_id`),
  CONSTRAINT `fk_depor_alternativa_depor_pregunta1` FOREIGN KEY (`pregunta_id`) REFERENCES `depor_pregunta` (`pregunta_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Alternativas a preguntas por partido.';

/*Table structure for table `depor_articulo` */

DROP TABLE IF EXISTS `depor_articulo`;

CREATE TABLE `depor_articulo` (
  `articulo_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipoarticulo_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `torneo_id` int(11) NOT NULL,
  `fase_id` int(11) NOT NULL,
  `fecha_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `contenido` varchar(600) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`articulo_id`),
  KEY `fk_depor_articulo_depor_tipoarticulo1` (`tipoarticulo_id`),
  KEY `fk_depor_articulo_depor_usuario1` (`usuario_id`),
  KEY `fk_depor_articulo_depor_fecha1` (`fecha_id`,`fase_id`,`torneo_id`),
  CONSTRAINT `fk_depor_articulo_depor_fecha1` FOREIGN KEY (`fecha_id`, `fase_id`, `torneo_id`) REFERENCES `depor_fecha` (`fecha_id`, `fase_id`, `torneo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_articulo_depor_tipoarticulo1` FOREIGN KEY (`tipoarticulo_id`) REFERENCES `depor_tipoarticulo` (`tipoarticulo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_articulo_depor_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `depor_usuario` (`usuario_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='Articulos de los usuarios.';

/*Table structure for table `depor_clave` */

DROP TABLE IF EXISTS `depor_clave`;

CREATE TABLE `depor_clave` (
  `clave_id` int(11) NOT NULL AUTO_INCREMENT,
  `partido_id` int(11) NOT NULL,
  `juego_id` int(11) NOT NULL,
  `respuestaclave_id` int(11) DEFAULT NULL,
  `registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`clave_id`),
  KEY `fk_depor_resultado_depor_partido1` (`partido_id`),
  KEY `fk_depor_resultado_depor_juego1` (`juego_id`),
  CONSTRAINT `fk_depor_resultado_depor_juego1` FOREIGN KEY (`juego_id`) REFERENCES `depor_juego` (`juego_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_resultado_depor_partido1` FOREIGN KEY (`partido_id`) REFERENCES `depor_partido` (`partido_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='Resultados por partido por fase';

/*Table structure for table `depor_club` */

DROP TABLE IF EXISTS `depor_club`;

CREATE TABLE `depor_club` (
  `club_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(250) DEFAULT NULL,
  `icono` varchar(100) DEFAULT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`club_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='Clubes de football peruano';

/*Table structure for table `depor_comentario` */

DROP TABLE IF EXISTS `depor_comentario`;

CREATE TABLE `depor_comentario` (
  `comentario_id` int(11) NOT NULL AUTO_INCREMENT,
  `articulo_id` int(11) NOT NULL,
  `jugador_id` int(11) NOT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`comentario_id`),
  KEY `fk_depor_comentario_depor_articulo1` (`articulo_id`),
  KEY `fk_depor_comentario_depor_jugador1` (`jugador_id`),
  CONSTRAINT `fk_depor_comentario_depor_articulo1` FOREIGN KEY (`articulo_id`) REFERENCES `depor_articulo` (`articulo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_comentario_depor_jugador1` FOREIGN KEY (`jugador_id`) REFERENCES `depor_jugador` (`jugador_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Comentarios de los participantes.';

/*Table structure for table `depor_equipo` */

DROP TABLE IF EXISTS `depor_equipo`;

CREATE TABLE `depor_equipo` (
  `equipo_id` int(11) NOT NULL AUTO_INCREMENT,
  `torneo_id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `puntaje` int(11) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`equipo_id`),
  KEY `fk_depor_equipo_depor_club1` (`club_id`),
  KEY `fk_depor_equipo_depor_torneo1` (`torneo_id`),
  CONSTRAINT `fk_depor_equipo_depor_club1` FOREIGN KEY (`club_id`) REFERENCES `depor_club` (`club_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_equipo_depor_torneo1` FOREIGN KEY (`torneo_id`) REFERENCES `depor_torneo` (`torneo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='Equipos que participan en el torneo.';

/*Table structure for table `depor_fase` */

DROP TABLE IF EXISTS `depor_fase`;

CREATE TABLE `depor_fase` (
  `fase_id` int(11) NOT NULL AUTO_INCREMENT,
  `torneo_id` int(11) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`fase_id`,`torneo_id`),
  KEY `fk_depor_fase_depor_torneo1` (`torneo_id`),
  CONSTRAINT `fk_depor_fase_depor_torneo1` FOREIGN KEY (`torneo_id`) REFERENCES `depor_torneo` (`torneo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Fases de un torneo descentralizado.';

/*Table structure for table `depor_fecha` */

DROP TABLE IF EXISTS `depor_fecha`;

CREATE TABLE `depor_fecha` (
  `fecha_id` int(11) NOT NULL AUTO_INCREMENT,
  `fase_id` int(11) NOT NULL,
  `torneo_id` int(11) NOT NULL,
  `cantidad_jugadores` int(11) NOT NULL DEFAULT '0',
  `fecha1` datetime NOT NULL,
  `fecha2` datetime NOT NULL,
  `fecha_proceso` datetime NOT NULL,
  `tipo` char(1) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `intervalo` varchar(250) DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime NOT NULL,
  `situacion` char(1) NOT NULL DEFAULT '0' COMMENT '0: Por jugar,1:Jugada',
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`fecha_id`,`fase_id`,`torneo_id`),
  KEY `fk_depor_fecha_depor_fase1` (`fase_id`,`torneo_id`),
  KEY `FK_depor_fecha_depor_grupof` (`grupo_id`),
  CONSTRAINT `fk_depor_fecha_depor_fase1` FOREIGN KEY (`fase_id`, `torneo_id`) REFERENCES `depor_fase` (`fase_id`, `torneo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_depor_fecha_depor_grupof` FOREIGN KEY (`grupo_id`) REFERENCES `depor_grupof` (`grupof_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='Fechas de los partidos.';

/*Table structure for table `depor_galeria` */

DROP TABLE IF EXISTS `depor_galeria`;

CREATE TABLE `depor_galeria` (
  `galeria_id` int(11) NOT NULL AUTO_INCREMENT,
  `articulo_id` int(11) NOT NULL,
  `imagen` varchar(150) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`galeria_id`),
  KEY `fk_depor_galeria_depor_articulo1` (`articulo_id`),
  CONSTRAINT `fk_depor_galeria_depor_articulo1` FOREIGN KEY (`articulo_id`) REFERENCES `depor_articulo` (`articulo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Imagenes de los articulos.';

/*Table structure for table `depor_grupo` */

DROP TABLE IF EXISTS `depor_grupo`;

CREATE TABLE `depor_grupo` (
  `grupo_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `tipo` char(1) DEFAULT '0' COMMENT '0: Publico\n1: Privado...(Requiere aprobacion)\n',
  `foto` varchar(50) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`grupo_id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='Grupos de participantes.';

/*Table structure for table `depor_grupof` */

DROP TABLE IF EXISTS `depor_grupof`;

CREATE TABLE `depor_grupof` (
  `grupof_id` int(11) NOT NULL AUTO_INCREMENT,
  `torneo_id` int(11) NOT NULL DEFAULT '0',
  `fase_id` int(11) NOT NULL DEFAULT '0',
  `descripcion` varchar(250) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`grupof_id`),
  KEY `FK_depor_grupof_depor_fase` (`torneo_id`),
  KEY `FK_depor_grupof_depor_fase_2` (`fase_id`),
  CONSTRAINT `FK_depor_grupof_depor_fase` FOREIGN KEY (`torneo_id`) REFERENCES `depor_fase` (`torneo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_depor_grupof_depor_fase_2` FOREIGN KEY (`fase_id`) REFERENCES `depor_fase` (`fase_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Grupos de una fase. (Opcional)';

/*Table structure for table `depor_intervalo` */

DROP TABLE IF EXISTS `depor_intervalo`;

CREATE TABLE `depor_intervalo` (
  `intervalo_id` int(11) NOT NULL AUTO_INCREMENT,
  `variable_id` int(11) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `descripcion2` varchar(250) DEFAULT NULL,
  `valori` int(11) NOT NULL DEFAULT '0',
  `valorf` int(11) NOT NULL DEFAULT '0',
  `puntaje` int(11) NOT NULL DEFAULT '0',
  `puntaje_var` int(11) NOT NULL DEFAULT '0',
  `puntaje_tot` int(11) NOT NULL DEFAULT '0',
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`intervalo_id`,`variable_id`),
  KEY `fk_depor_intervalo_depor_columna1` (`variable_id`),
  CONSTRAINT `fk_depor_intervalo_depor_columna1` FOREIGN KEY (`variable_id`) REFERENCES `depor_variable` (`variable_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='Intervalos por variable.';

/*Table structure for table `depor_juego` */

DROP TABLE IF EXISTS `depor_juego`;

CREATE TABLE `depor_juego` (
  `juego_id` int(11) NOT NULL AUTO_INCREMENT,
  `partido_id` int(11) NOT NULL,
  `variable_id` int(11) NOT NULL,
  `intervalo_id` int(11) NOT NULL,
  `puntaje` varchar(45) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`juego_id`),
  KEY `fk_depor_juego_depor_partido1` (`partido_id`),
  KEY `fk_depor_juego_depor_intervalo1` (`intervalo_id`,`variable_id`),
  CONSTRAINT `fk_depor_juego_depor_intervalo1` FOREIGN KEY (`intervalo_id`, `variable_id`) REFERENCES `depor_intervalo` (`intervalo_id`, `variable_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_juego_depor_partido1` FOREIGN KEY (`partido_id`) REFERENCES `depor_partido` (`partido_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11521 DEFAULT CHARSET=utf8 COMMENT='Fixtures por fecha.';

/*Table structure for table `depor_jugador` */

DROP TABLE IF EXISTS `depor_jugador`;

CREATE TABLE `depor_jugador` (
  `jugador_id` int(11) NOT NULL AUTO_INCREMENT,
  `ubigeo_id` char(6) NOT NULL DEFAULT '000000',
  `tipodoc_id` int(11) NOT NULL,
  `numero_doc` char(8) DEFAULT NULL,
  `nickname` varchar(15) DEFAULT NULL,
  `nombres` varchar(250) DEFAULT NULL,
  `apellidos` char(70) DEFAULT NULL,
  `paterno` varchar(250) DEFAULT NULL,
  `materno` varchar(250) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `movil` varchar(50) DEFAULT NULL,
  `foto` varchar(250) DEFAULT NULL,
  `direccion` varchar(250) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) DEFAULT NULL,
  `GUID` varchar(32) DEFAULT NULL,
  `session` char(1) NOT NULL DEFAULT '1' COMMENT '1:primera session, 2:cambio password',
  `dni_apoderado` char(8) DEFAULT NULL,
  `club_id` int(11) NOT NULL DEFAULT '0',
  `nacimiento` datetime DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  `puntaje` int(11) DEFAULT '0',
  PRIMARY KEY (`jugador_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nick_UNIQUE` (`nickname`),
  KEY `fk_depor_usuario_depor_ubigeo` (`ubigeo_id`),
  KEY `fk_depor_usuario_depor_tipodocumento1` (`tipodoc_id`),
  KEY `club_jugador` (`club_id`),
  CONSTRAINT `depor_jugador_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `depor_club` (`club_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_usuario_depor_tipodocumento1` FOREIGN KEY (`tipodoc_id`) REFERENCES `depor_tipodocumento` (`tipodoc_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='Jugador del juego.';

/*Table structure for table `depor_jugadoremail` */

DROP TABLE IF EXISTS `depor_jugadoremail`;

CREATE TABLE `depor_jugadoremail` (
  `email_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jugador_id` int(10) NOT NULL,
  `email` varchar(70) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`email_id`),
  UNIQUE KEY `email` (`email`),
  KEY `jugador_id` (`jugador_id`),
  CONSTRAINT `fk_jugador_emails` FOREIGN KEY (`jugador_id`) REFERENCES `depor_jugador` (`jugador_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `depor_jugadorgrupo` */

DROP TABLE IF EXISTS `depor_jugadorgrupo`;

CREATE TABLE `depor_jugadorgrupo` (
  `jugadorgrupo_id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_id` int(11) NOT NULL,
  `jugador_id` int(11) NOT NULL,
  `tipo` char(1) NOT NULL COMMENT '1: Administrador de grupo.\n2:Partiipante de grupo.',
  `flag_aprobado` char(1) DEFAULT '1' COMMENT '0: Desaprobado\n1:Aprobado',
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`jugadorgrupo_id`),
  KEY `fk_depor_jugadorgrupo_depor_grupo1` (`grupo_id`),
  KEY `fk_depor_jugadorgrupo_depor_jugador1` (`jugador_id`),
  CONSTRAINT `fk_depor_jugadorgrupo_depor_grupo1` FOREIGN KEY (`grupo_id`) REFERENCES `depor_grupo` (`grupo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_jugadorgrupo_depor_jugador1` FOREIGN KEY (`jugador_id`) REFERENCES `depor_jugador` (`jugador_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='Jugadores por grupo\n';

/*Table structure for table `depor_jugadorred` */

DROP TABLE IF EXISTS `depor_jugadorred`;

CREATE TABLE `depor_jugadorred` (
  `jugadorred_id` int(11) NOT NULL AUTO_INCREMENT,
  `redsocial_id` int(11) NOT NULL,
  `jugador_id` int(11) NOT NULL,
  `redsocial_user` varchar(50) NOT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`jugadorred_id`),
  UNIQUE KEY `redsocial_user` (`redsocial_user`),
  KEY `fk_id_jugadorred_depor_redsocial1` (`redsocial_id`),
  KEY `fk_id_jugadorred_depor_jugador1` (`jugador_id`),
  CONSTRAINT `fk_id_jugadorred_depor_jugador1` FOREIGN KEY (`jugador_id`) REFERENCES `depor_jugador` (`jugador_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_id_jugadorred_depor_redsocial1` FOREIGN KEY (`redsocial_id`) REFERENCES `depor_redsocial` (`redsocial_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Redes Sociales de un jugador.';

/*Table structure for table `depor_mail` */

DROP TABLE IF EXISTS `depor_mail`;

CREATE TABLE `depor_mail` (
  `mail_id` int(11) NOT NULL AUTO_INCREMENT,
  `jugador_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipomail_id` int(11) NOT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`mail_id`),
  KEY `fk_depor_mail_depor_jugador1` (`jugador_id`),
  KEY `fk_depor_mail_depor_usuario1` (`usuario_id`),
  KEY `fk_depor_mail_depor_tipomail1` (`tipomail_id`),
  CONSTRAINT `fk_depor_mail_depor_jugador1` FOREIGN KEY (`jugador_id`) REFERENCES `depor_jugador` (`jugador_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_mail_depor_tipomail1` FOREIGN KEY (`tipomail_id`) REFERENCES `depor_tipomail` (`tipomail_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_mail_depor_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `depor_usuario` (`usuario_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Mails a los participantes.';

/*Table structure for table `depor_modulo` */

DROP TABLE IF EXISTS `depor_modulo`;

CREATE TABLE `depor_modulo` (
  `modulo_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(250) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`modulo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Modulos del sistema';

/*Table structure for table `depor_muro` */

DROP TABLE IF EXISTS `depor_muro`;

CREATE TABLE `depor_muro` (
  `muro_id` int(11) NOT NULL AUTO_INCREMENT,
  `jugador_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `mensaje` varchar(250) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`muro_id`),
  KEY `fk_depor_muro_depor_grupo1` (`grupo_id`),
  KEY `fk_depor_muro_depor_jugador1` (`jugador_id`),
  CONSTRAINT `fk_depor_muro_depor_grupo1` FOREIGN KEY (`grupo_id`) REFERENCES `depor_grupo` (`grupo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_muro_depor_jugador1` FOREIGN KEY (`jugador_id`) REFERENCES `depor_jugador` (`jugador_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `depor_partido` */

DROP TABLE IF EXISTS `depor_partido`;

CREATE TABLE `depor_partido` (
  `partido_id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_id` int(11) NOT NULL,
  `fase_id` int(11) NOT NULL,
  `torneo_id` int(11) NOT NULL,
  `equipo_local` int(11) NOT NULL,
  `equipo_visitante` int(11) NOT NULL,
  `fecha_partido` datetime DEFAULT NULL,
  `fecha_proceso` datetime NOT NULL,
  `fase1` char(1) NOT NULL DEFAULT '1',
  `fase2` char(1) NOT NULL DEFAULT '1',
  `fase3` char(1) NOT NULL DEFAULT '0',
  `registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `situacion` char(1) NOT NULL DEFAULT '0' COMMENT '0: Por jugar,1:Jugada',
  `ficha` mediumtext,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`partido_id`),
  KEY `fk_depor_partido_depor_fecha1` (`fecha_id`,`fase_id`,`torneo_id`),
  KEY `fk_depor_partido_depor_equipo1` (`equipo_local`),
  KEY `fk_depor_partido_depor_equipo2` (`equipo_visitante`),
  CONSTRAINT `fk_depor_partido_depor_equipo1` FOREIGN KEY (`equipo_local`) REFERENCES `depor_equipo` (`equipo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_partido_depor_equipo2` FOREIGN KEY (`equipo_visitante`) REFERENCES `depor_equipo` (`equipo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_partido_depor_fecha1` FOREIGN KEY (`fecha_id`, `fase_id`, `torneo_id`) REFERENCES `depor_fecha` (`fecha_id`, `fase_id`, `torneo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=utf8 COMMENT='Partidos del torneo descentralizado.';

/*Table structure for table `depor_permiso` */

DROP TABLE IF EXISTS `depor_permiso`;

CREATE TABLE `depor_permiso` (
  `permiso_id` int(11) NOT NULL AUTO_INCREMENT,
  `rol_id` int(11) NOT NULL,
  `modulo_id` int(11) NOT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`permiso_id`),
  KEY `fk_depor_permiso_depor_rol1` (`rol_id`),
  KEY `fk_depor_permiso_depor_modulo1` (`modulo_id`),
  CONSTRAINT `fk_depor_permiso_depor_modulo1` FOREIGN KEY (`modulo_id`) REFERENCES `depor_modulo` (`modulo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_permiso_depor_rol1` FOREIGN KEY (`rol_id`) REFERENCES `depor_rol` (`rol_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Permisos del sistema';

/*Table structure for table `depor_pregunta` */

DROP TABLE IF EXISTS `depor_pregunta`;

CREATE TABLE `depor_pregunta` (
  `pregunta_id` int(11) NOT NULL AUTO_INCREMENT,
  `partido_id` int(11) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`pregunta_id`),
  KEY `fk_depor_pregunta_depor_partido1` (`partido_id`),
  CONSTRAINT `fk_depor_pregunta_depor_partido1` FOREIGN KEY (`partido_id`) REFERENCES `depor_partido` (`partido_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Preguntas por partido.';

/*Table structure for table `depor_puntaje` */

DROP TABLE IF EXISTS `depor_puntaje`;

CREATE TABLE `depor_puntaje` (
  `puntaje_id` int(11) NOT NULL AUTO_INCREMENT,
  `partido_id` int(11) NOT NULL,
  `jugador_id` int(11) NOT NULL,
  `puntaje_juego` int(11) NOT NULL DEFAULT '0',
  `registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`puntaje_id`),
  UNIQUE KEY `partido_id_jugador_id` (`partido_id`,`jugador_id`),
  KEY `fk_depor_puntaje_depor_partido1` (`partido_id`),
  KEY `fk_depor_puntaje_depor_jugador1` (`jugador_id`),
  CONSTRAINT `fk_depor_puntaje_depor_jugador1` FOREIGN KEY (`jugador_id`) REFERENCES `depor_jugador` (`jugador_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_puntaje_depor_partido1` FOREIGN KEY (`partido_id`) REFERENCES `depor_partido` (`partido_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Puntaje de cada jugador por partido\n';

/*Table structure for table `depor_puntajefecha` */

DROP TABLE IF EXISTS `depor_puntajefecha`;

CREATE TABLE `depor_puntajefecha` (
  `puntajefecha_id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_id` int(11) NOT NULL,
  `fase_id` int(11) NOT NULL,
  `torneo_id` int(11) NOT NULL,
  `jugador_id` int(11) NOT NULL,
  `puntaje_juego` int(11) NOT NULL DEFAULT '0',
  `puntaje_pregunta` int(11) NOT NULL DEFAULT '0',
  `puntaje_acierto` int(11) NOT NULL DEFAULT '0',
  `puntaje_total` int(11) NOT NULL DEFAULT '0',
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  PRIMARY KEY (`puntajefecha_id`),
  UNIQUE KEY `fecha_id_jugador_id` (`fecha_id`,`jugador_id`),
  KEY `fk_depor_puntajefecha_depor_fecha1` (`fecha_id`,`fase_id`,`torneo_id`),
  KEY `fk_depor_puntajefecha_depor_jugador1` (`jugador_id`),
  CONSTRAINT `fk_depor_puntajefecha_depor_fecha1` FOREIGN KEY (`fecha_id`, `fase_id`, `torneo_id`) REFERENCES `depor_fecha` (`fecha_id`, `fase_id`, `torneo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_puntajefecha_depor_jugador1` FOREIGN KEY (`jugador_id`) REFERENCES `depor_jugador` (`jugador_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

/*Table structure for table `depor_puntajejuego` */

DROP TABLE IF EXISTS `depor_puntajejuego`;

CREATE TABLE `depor_puntajejuego` (
  `puntajejuego_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `jugador_id` int(11) NOT NULL,
  `partido_id` int(11) NOT NULL,
  `variable_id` int(11) NOT NULL,
  `intervalo_id` int(11) NOT NULL,
  `anterior` int(11) NOT NULL DEFAULT '0',
  `puntaje` int(11) NOT NULL DEFAULT '0',
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`puntajejuego_id`),
  KEY `fk_depor_puntajedetalle_depor_intervalo1` (`intervalo_id`,`variable_id`),
  KEY `FK_depor_puntajejuego_depor_jugador` (`jugador_id`),
  KEY `FK_depor_puntajejuego_depor_partido` (`partido_id`),
  CONSTRAINT `fk_depor_puntajedetalle_depor_intervalo1` FOREIGN KEY (`intervalo_id`, `variable_id`) REFERENCES `depor_intervalo` (`intervalo_id`, `variable_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_depor_puntajejuego_depor_jugador` FOREIGN KEY (`jugador_id`) REFERENCES `depor_jugador` (`jugador_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_depor_puntajejuego_depor_partido` FOREIGN KEY (`partido_id`) REFERENCES `depor_partido` (`partido_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8 COMMENT='Puntaje del jugador por partido por fase\n';

/*Table structure for table `depor_puntajepregunta` */

DROP TABLE IF EXISTS `depor_puntajepregunta`;

CREATE TABLE `depor_puntajepregunta` (
  `puntajepregunta_id` int(11) NOT NULL AUTO_INCREMENT,
  `jugador_id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `alternativa_id` int(11) NOT NULL,
  `puntaje` int(11) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`puntajepregunta_id`),
  KEY `fk_depor_respuesta_depor_pregunta1` (`pregunta_id`),
  KEY `fk_depor_respuesta_depor_alternativa1` (`alternativa_id`),
  KEY `fk_depor_respuesta_depor_jugador1` (`jugador_id`),
  CONSTRAINT `fk_depor_respuesta_depor_alternativa1` FOREIGN KEY (`alternativa_id`) REFERENCES `depor_alternativa` (`alternativa_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_respuesta_depor_jugador1` FOREIGN KEY (`jugador_id`) REFERENCES `depor_jugador` (`jugador_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_respuesta_depor_pregunta1` FOREIGN KEY (`pregunta_id`) REFERENCES `depor_pregunta` (`pregunta_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Resupuestas de los jugadores a las preguntas.';

/*Table structure for table `depor_redsocial` */

DROP TABLE IF EXISTS `depor_redsocial`;

CREATE TABLE `depor_redsocial` (
  `redsocial_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(250) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`redsocial_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Redes Sociales existentes.';

/*Table structure for table `depor_respuesta` */

DROP TABLE IF EXISTS `depor_respuesta`;

CREATE TABLE `depor_respuesta` (
  `respuesta_id` int(11) NOT NULL AUTO_INCREMENT,
  `partido_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` char(1) NOT NULL COMMENT '1:vPrimera\n2:segunda',
  `valor_a` int(11) NOT NULL DEFAULT '0',
  `valor_b` int(45) NOT NULL DEFAULT '0',
  `registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` varchar(45) DEFAULT NULL,
  `estado` varchar(45) NOT NULL DEFAULT '1',
  PRIMARY KEY (`respuesta_id`),
  KEY `fk_depor_respuesta_depor_partido1` (`partido_id`),
  KEY `FK_depor_respuesta_depor_usuario` (`usuario_id`),
  CONSTRAINT `fk_depor_respuesta_depor_partido1` FOREIGN KEY (`partido_id`) REFERENCES `depor_partido` (`partido_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_depor_respuesta_depor_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `depor_usuario` (`usuario_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `depor_respuestaclave` */

DROP TABLE IF EXISTS `depor_respuestaclave`;

CREATE TABLE `depor_respuestaclave` (
  `respuestaclave_id` int(11) NOT NULL AUTO_INCREMENT,
  `respuesta_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `variable_id` int(11) NOT NULL,
  `intervalo_id` int(11) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`respuestaclave_id`),
  KEY `fk_depor_respuestaclave_depor_respuesta1` (`respuesta_id`),
  KEY `FK_depor_respuestaclave_depor_usuario` (`usuario_id`),
  KEY `FK_depor_respuestaclave_depor_variable` (`variable_id`),
  KEY `FK_depor_respuestaclave_depor_intervalo` (`intervalo_id`),
  CONSTRAINT `FK_depor_respuestaclave_depor_intervalo` FOREIGN KEY (`intervalo_id`) REFERENCES `depor_intervalo` (`intervalo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_respuestaclave_depor_respuesta1` FOREIGN KEY (`respuesta_id`) REFERENCES `depor_respuesta` (`respuesta_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_depor_respuestaclave_depor_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `depor_usuario` (`usuario_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_depor_respuestaclave_depor_variable` FOREIGN KEY (`variable_id`) REFERENCES `depor_variable` (`variable_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `depor_resultado` */

DROP TABLE IF EXISTS `depor_resultado`;

CREATE TABLE `depor_resultado` (
  `resultado_id` int(11) NOT NULL AUTO_INCREMENT,
  `partido_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `clave_id1` int(11) NOT NULL,
  `clave_id2` int(11) NOT NULL,
  `goles_local` int(45) NOT NULL DEFAULT '0',
  `goles_visita` int(45) NOT NULL DEFAULT '0',
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`resultado_id`),
  KEY `fk_depor_resultado_depor_partido2` (`partido_id`),
  KEY `FK_depor_resultado_depor_usuario` (`usuario_id`),
  KEY `FK_depor_resultado_depor_clave` (`clave_id1`),
  KEY `FK_depor_resultado_depor_clave_2` (`clave_id2`),
  CONSTRAINT `FK_depor_resultado_depor_clave` FOREIGN KEY (`clave_id1`) REFERENCES `depor_clave` (`clave_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_depor_resultado_depor_clave_2` FOREIGN KEY (`clave_id2`) REFERENCES `depor_clave` (`clave_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_resultado_depor_partido2` FOREIGN KEY (`partido_id`) REFERENCES `depor_partido` (`partido_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_depor_resultado_depor_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `depor_usuario` (`usuario_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Table structure for table `depor_rol` */

DROP TABLE IF EXISTS `depor_rol`;

CREATE TABLE `depor_rol` (
  `rol_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(250) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`rol_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Listado de Roles';

/*Table structure for table `depor_tipoarticulo` */

DROP TABLE IF EXISTS `depor_tipoarticulo`;

CREATE TABLE `depor_tipoarticulo` (
  `tipoarticulo_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`tipoarticulo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Tipos de articulo';

/*Table structure for table `depor_tipodocumento` */

DROP TABLE IF EXISTS `depor_tipodocumento`;

CREATE TABLE `depor_tipodocumento` (
  `tipodoc_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(250) NOT NULL,
  `sigla` varchar(30) NOT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`tipodoc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Tipos de documento.';

/*Table structure for table `depor_tipomail` */

DROP TABLE IF EXISTS `depor_tipomail`;

CREATE TABLE `depor_tipomail` (
  `tipomail_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(250) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`tipomail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista de tipos de mail.';

/*Table structure for table `depor_tipovariable` */

DROP TABLE IF EXISTS `depor_tipovariable`;

CREATE TABLE `depor_tipovariable` (
  `tipovariable_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(250) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`tipovariable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `depor_tipovoto` */

DROP TABLE IF EXISTS `depor_tipovoto`;

CREATE TABLE `depor_tipovoto` (
  `tipovoto_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`tipovoto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `depor_torneo` */

DROP TABLE IF EXISTS `depor_torneo`;

CREATE TABLE `depor_torneo` (
  `torneo_id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(250) DEFAULT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`torneo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Torneos de futball';

/*Table structure for table `depor_ubigeo` */

DROP TABLE IF EXISTS `depor_ubigeo`;

CREATE TABLE `depor_ubigeo` (
  `ubigeo_id` char(6) NOT NULL,
  `departamento` char(2) NOT NULL,
  `provincia` char(2) NOT NULL,
  `distrito` char(2) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ubigeo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de distritos del Peru.';

/*Table structure for table `depor_usuario` */

DROP TABLE IF EXISTS `depor_usuario`;

CREATE TABLE `depor_usuario` (
  `usuario_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) DEFAULT NULL,
  `paterno` varchar(250) DEFAULT NULL,
  `materno` varchar(250) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Usuarios del sistema';

/*Table structure for table `depor_usuariorol` */

DROP TABLE IF EXISTS `depor_usuariorol`;

CREATE TABLE `depor_usuariorol` (
  `usuariorol_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`usuariorol_id`),
  KEY `fk_depor_usuariorol_depor_usuario1` (`usuario_id`),
  KEY `fk_depor_usuariorol_depor_rol1` (`rol_id`),
  CONSTRAINT `fk_depor_usuariorol_depor_rol1` FOREIGN KEY (`rol_id`) REFERENCES `depor_rol` (`rol_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_usuariorol_depor_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `depor_usuario` (`usuario_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Roles de los usuarios.';

/*Table structure for table `depor_variable` */

DROP TABLE IF EXISTS `depor_variable`;

CREATE TABLE `depor_variable` (
  `variable_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipovariable_id` int(11) NOT NULL DEFAULT '0',
  `descripcion` varchar(250) DEFAULT NULL,
  `sigla` varchar(100) DEFAULT NULL,
  `anterior` int(11) NOT NULL,
  `puntaje` int(11) NOT NULL DEFAULT '0',
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`variable_id`),
  KEY `FK_depor_variable_depor_tipovariable` (`tipovariable_id`),
  CONSTRAINT `FK_depor_variable_depor_tipovariable` FOREIGN KEY (`tipovariable_id`) REFERENCES `depor_tipovariable` (`tipovariable_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='Variables de evaluacion.\nLocal,Empate,Gano';

/*Table structure for table `depor_voto` */

DROP TABLE IF EXISTS `depor_voto`;

CREATE TABLE `depor_voto` (
  `voto_id` int(11) NOT NULL AUTO_INCREMENT,
  `articulo_id` int(11) NOT NULL,
  `jugador_id` int(11) NOT NULL,
  `tipovoto_id` int(11) NOT NULL,
  `valor` int(11) NOT NULL,
  `registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificacion` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT '1',
  PRIMARY KEY (`voto_id`),
  KEY `fk_depor_voto_depor_articulo1` (`articulo_id`),
  KEY `fk_depor_voto_depor_jugador1` (`jugador_id`),
  KEY `fk_depor_voto_depor_tipovoto1` (`tipovoto_id`),
  CONSTRAINT `fk_depor_voto_depor_articulo1` FOREIGN KEY (`articulo_id`) REFERENCES `depor_articulo` (`articulo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_voto_depor_jugador1` FOREIGN KEY (`jugador_id`) REFERENCES `depor_jugador` (`jugador_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_depor_voto_depor_tipovoto1` FOREIGN KEY (`tipovoto_id`) REFERENCES `depor_tipovoto` (`tipovoto_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Function  structure for function  `fns_puntajejugador_fase` */

/*!50003 DROP FUNCTION IF EXISTS `fns_puntajejugador_fase` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `fns_puntajejugador_fase`(`fechaid` INT, `jugadorid` INT, `tipovar` INT) RETURNS int(11)
BEGIN
DECLARE puntaje INT DEFAULT 0;
	IF (tipovar=1 OR tipovar=2) THEN
		select 
		sum(dpj.puntaje)  into puntaje
		from depor_puntajejuego as dpj
		inner join depor_partido as dp on dpj.partido_id=dp.partido_id
		inner join depor_variable as dvar on dpj.variable_id=dvar.variable_id
		where dvar.tipovariable_id=tipovar
		and dpj.jugador_id=jugadorid
		and dp.fecha_id=fechaid
		order by dpj.puntaje desc 
		limit 6;
	ELSEIF(tipovar=3) THEN
		select 
		sum(dpj.puntaje)  into puntaje
		from depor_puntajejuego as dpj
		inner join depor_partido as dp on dpj.partido_id=dp.partido_id
		inner join depor_variable as dvar on dpj.variable_id=dvar.variable_id
		where dvar.tipovariable_id in (3,4)
		and dpj.jugador_id=jugadorid
		and dp.fecha_id=fechaid
		order by dpj.puntaje desc 
		limit 6;
	END IF;
	IF puntaje IS NULL THEN
		set puntaje=0;
	END IF;
	return puntaje;
END */$$
DELIMITER ;

/* Procedure structure for procedure `matcheo_porjugador` */

/*!50003 DROP PROCEDURE IF EXISTS  `matcheo_porjugador` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `matcheo_porjugador`(IN jugadorid INT)
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE puntajejuego,jugador,partido,variable,intervalo,puntaje,cantidad,pfecha,fechaid,faseid,torneoid INT;
    DECLARE cur_1 CURSOR FOR 
	SELECT puntajejuego_id, jugador_id, partido_id, variable_id, intervalo_id 
	FROM depor_puntajejuego	
	WHERE jugador_id = jugadorid;
    OPEN cur_1;
	REPEAT
	FETCH cur_1 INTO puntajejuego,jugador,partido,variable,intervalo;
		IF NOT done THEN
			SELECT b.puntaje INTO puntaje
			FROM depor_clave AS a 
			INNER JOIN depor_juego AS b ON a.juego_id=b.juego_id
			WHERE a.partido_id=partido 
			AND b.variable_id=variable
			AND b.intervalo_id=intervalo;
			IF puntaje<>0 THEN				
			   UPDATE depor_puntaje 
			   SET puntaje_juego=puntaje 
			   WHERE puntajejuego_id = puntajejuego;
			   SELECT COUNT(*) INTO cantidad 
				FROM depor_puntaje 
				WHERE partido_id=partido 
					AND jugador_id=jugador;
			   IF cantidad<>NULL THEN
			   	INSERT INTO depor_puntaje SET partido_id=partido,jugador_id=jugador,puntaje_juego=puntaje;
			   END IF;
			   
			   SELECT fecha_id INTO fechaid	 
				FROM depor_partido
				WHERE partido_id = partido;
			   			
			   SELECT SUM(t.puntaje_juego) INTO pfecha
				FROM (SELECT dp.puntaje_juego	 
				FROM depor_puntaje AS dp
				INNER JOIN depor_partido AS dpa ON dpa.partido_id = dp.partido_id
				WHERE dpa.fecha_id = fechaid AND dp.jugador_id = jugador
				ORDER BY dp.puntaje_juego DESC
				LIMIT 0,6) t;	
			   			
			   IF pfecha<>NULL THEN
				UPDATE depor_puntajefecha 
					SET puntaje_juego=pfecha WHERE fecha_id=fechaid AND jugador_id=jugador; 
				UPDATE depor_puntajefecha SET puntaje_total = puntaje_juego + puntaje_pregunta + puntaje_acierto
					WHERE fecha_id = fechaid AND jugador_id=jugadorid;
			   END IF;
			END IF;
		END IF;
	UNTIL done END REPEAT;
    CLOSE cur_1;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `matcheo_porpartido` */

/*!50003 DROP PROCEDURE IF EXISTS  `matcheo_porpartido` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `matcheo_porpartido`(IN partidoid INT)
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE puntajejuego,jugador,partido,variable,intervalo,puntaje,cantidad,fechaid INT;
    DECLARE cur_1 CURSOR FOR 
	SELECT puntajejuego_id, jugador_id, partido_id, variable_id, intervalo_id 
	FROM depor_puntajejuego	
	WHERE partido_id = partidoid;
    OPEN cur_1;
	REPEAT
	FETCH cur_1 INTO puntajejuego,jugador,partido,variable,intervalo;
		IF NOT done THEN
			SELECT b.puntaje INTO puntaje
			FROM depor_clave AS a 
			INNER JOIN depor_juego AS b ON a.juego_id=b.juego_id
			WHERE a.partido_id=partido 
			AND b.variable_id=variable
			AND b.intervalo_id=intervalo;
			IF puntaje<>0 THEN				
			   UPDATE depor_puntaje 
			   SET puntaje_juego=puntaje 
			   WHERE puntajejuego_id = puntajejuego;
			   SELECT COUNT(*) INTO cantidad 
				FROM depor_puntaje 
				WHERE partido_id=partido 
					AND jugador_id=jugador;
			   IF cantidad<>NULL THEN
				if cantidad=0 then
				   INSERT INTO depor_puntaje SET partido_id=partido,jugador_id=jugador,puntaje_juego=puntaje;
				end if;
			   	if cantdad<>0 then
			   	   UPDATE depor_puntaje SET puntaje_juego=puntaje
			   	   WHERE partido_id=partidoid AND jugador_id=jugadorid;
			   	end if;
			   END IF;					
			END IF;
		END IF;
	UNTIL done END REPEAT;
    CLOSE cur_1;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `pns_calculo_puntaje` */

/*!50003 DROP PROCEDURE IF EXISTS  `pns_calculo_puntaje` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `pns_calculo_puntaje`(IN `fechaid` INT)
BEGIN
	CALL pns_matcheo_puntajejuego(fechaid);
	CALL pns_puntos_puntajefecha(fechaid);
	CALL pns_puntaje_jugador();
END */$$
DELIMITER ;

/* Procedure structure for procedure `pns_getpartidofase` */

/*!50003 DROP PROCEDURE IF EXISTS  `pns_getpartidofase` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `pns_getpartidofase`(IN `fechaid` INT, IN `jugadorid` INT, IN `tipoid` INT)
BEGIN
DECLARE fase char(5);
DECLARE tipo_id INT;
IF tipoid=1 THEN
	set fase="fase1";
	set tipo_id=tipoid;
ELSEIF tipoid=2 THEN
	set fase="fase2";
		set tipo_id=tipoid-1;
ELSEIF tipoid=3 THEN
	set fase="fase3";	
	set tipo_id=tipoid-1;
END IF;	
set @s= CONCAT('select 
		*
		from depor_partido 
		where estado=1 
		and ',fase,'=1 
		and fecha_id=',fechaid
		);
PREPARE stmt FROM @s;
EXECUTE stmt;
END */$$
DELIMITER ;

/* Procedure structure for procedure `pns_matcheo_puntajejuego` */

/*!50003 DROP PROCEDURE IF EXISTS  `pns_matcheo_puntajejuego` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `pns_matcheo_puntajejuego`(IN `fechaid` INT)
BEGIN
DECLARE done INT DEFAULT 0;
DECLARE puntajejuego,jugador,partido,variable,intervalo,cantidad,pfecha, cantfecha INT;
DECLARE puntaje INT DEFAULT 0;
DECLARE cur_1 CURSOR FOR 
	SELECT pj.puntajejuego_id, pj.jugador_id, pj.partido_id, pj.variable_id, pj.intervalo_id 
	FROM depor_puntajejuego AS pj
	INNER JOIN depor_partido AS dp ON dp.partido_id = pj.partido_id
	WHERE dp.fecha_id = fechaid; 
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
OPEN cur_1;
	REPEAT
	FETCH cur_1 INTO puntajejuego,jugador,partido,variable,intervalo;
		IF NOT done THEN
			IF NOT EXISTS (
				SELECT *
				FROM depor_clave AS a 
				INNER JOIN depor_juego AS b ON a.juego_id=b.juego_id
				WHERE a.partido_id=partido 
				AND b.variable_id=variable
				AND b.intervalo_id=intervalo) THEN
			set puntaje=0;
			ELSE
				SELECT b.puntaje into puntaje
				FROM depor_clave AS a 
				INNER JOIN depor_juego AS b ON a.juego_id=b.juego_id
				WHERE a.partido_id=partido 
				AND b.variable_id=variable
				AND b.intervalo_id=intervalo;			
			END IF;
			IF puntaje<>0 THEN
				UPDATE depor_puntajejuego 
				SET puntaje=puntaje 
				WHERE puntajejuego_id=puntajejuego;
			END IF;
		END IF;		
	UNTIL done END REPEAT;
CLOSE cur_1;
END */$$
DELIMITER ;

/* Procedure structure for procedure `pns_puntaje_jugador` */

/*!50003 DROP PROCEDURE IF EXISTS  `pns_puntaje_jugador` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `pns_puntaje_jugador`()
BEGIN
DECLARE done INT DEFAULT 0;
DECLARE jugador,puntajetotal INT;
DECLARE cur_1 CURSOR FOR select jugador_id from depor_puntajejuego group by jugador_id;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
update depor_jugador set puntaje=0;
OPEN cur_1;
	REPEAT
	FETCH cur_1 into jugador;
		IF NOT done THEN
			select sum(puntaje_total) into puntajetotal from depor_puntajefecha where jugador_id=jugador group by fecha_id;
			IF puntajetotal IS NOT NULL THEN
				update depor_jugador set puntaje=puntajetotal where jugador_id=jugador;
			END IF;
		END IF;
	UNTIL done END REPEAT;
CLOSE cur_1;
END */$$
DELIMITER ;

/* Procedure structure for procedure `pns_puntos_puntajefecha` */

/*!50003 DROP PROCEDURE IF EXISTS  `pns_puntos_puntajefecha` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `pns_puntos_puntajefecha`(IN `fechaid` INT)
BEGIN
	DECLARE done,puntaje_f1,puntaje_f2,puntaje_f3,puntaje_juego,puntaje_pregunta,puntaje_acierto,puntaje_total INT DEFAULT 0;
	DECLARE jugador,fase,torneo INT;
	DECLARE cur_1 CURSOR FOR select jugador_id from depor_puntajejuego group by jugador_id;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	update depor_partido set fecha_proceso=now();
	OPEN cur_1;
		REPEAT
		FETCH cur_1 INTO jugador;
			IF NOT done THEN
				/*Elimino registros*/
				delete from depor_puntajefecha where fecha_id=fechaid and jugador_id=jugador;
				/*Insertar registros*/
				select torneo_id,fase_id into torneo,fase from depor_fecha where fecha_id=fechaid;
				set puntaje_f1 = fns_puntajejugador_fase(fechaid,jugador,1);
				set puntaje_f2 = fns_puntajejugador_fase(fechaid,jugador,2);
				set puntaje_f3 = fns_puntajejugador_fase(fechaid,jugador,3);								
				set puntaje_juego    = puntaje_f1+puntaje_f2+puntaje_f3;
				IF puntaje_juego IS NULL THEN
					set puntaje_juego=0;
				END IF;
				set puntaje_total    = puntaje_juego+puntaje_pregunta+puntaje_acierto;
				insert into depor_puntajefecha (fecha_id,fase_id,torneo_id,jugador_id,puntaje_juego,puntaje_pregunta,puntaje_acierto,puntaje_total) 
				values (fechaid,fase,torneo,jugador,puntaje_juego,puntaje_pregunta,puntaje_acierto,puntaje_total);
								
			END IF;
		UNTIL done END REPEAT;
	CLOSE cur_1;
END */$$
DELIMITER ;

/* Procedure structure for procedure `ranking_grupo_fecha` */

/*!50003 DROP PROCEDURE IF EXISTS  `ranking_grupo_fecha` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `ranking_grupo_fecha`(IN `fechaid` INT, IN `grupoid` INT, IN `liminf` INT, IN `limsup` INT)
BEGIN
	PREPARE statement FROM
	"SELECT pf.fecha_id, 
	jg.jugador_id, 
	pf.puntaje_juego, 
	pf.puntaje_total, 
	pf.registro, 	 
	jg.grupo_id,
	dj.nombres,
	dj.paterno,
	dj.nickname
	FROM depor_puntajefecha pf 
	INNER JOIN depor_jugadorgrupo jg 
		ON jg.jugador_id = pf.jugador_id
	INNER JOIN depor_jugador dj 
		ON dj.jugador_id = jg.jugador_id
	WHERE pf.fecha_id = ? 
		AND jg.grupo_id = ? 
		AND jg.flag_aprobado = 1
		AND jg.estado = 1
	ORDER BY pf.puntaje_total DESC
	LIMIT ?, ?";
	
	SET @p1 = fechaid;
	SET @p2 = grupoid;
	SET @p3 = liminf;
	SET @p4 = limsup;
	
	EXECUTE statement USING @p1, @p2, @p3, @p4;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `ranking_min_general` */

/*!50003 DROP PROCEDURE IF EXISTS  `ranking_min_general` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `ranking_min_general`(IN liminf INT, IN limsup INT)
BEGIN
	PREPARE statement FROM
	"SELECT jugador_id, 
	nombres, 
	paterno, 
	materno, 
	nickname, 	 
	puntaje
	FROM depor_jugador
	ORDER BY puntaje DESC
	LIMIT ?, ?";
	
	SET @p1 = liminf;
	SET @p2 = limsup;
	
	EXECUTE statement USING @p1, @p2;
    END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
