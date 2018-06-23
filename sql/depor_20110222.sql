# --------------------------------------------------------
# Host:                         127.0.0.1
# Server version:               5.1.41
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-02-22 19:59:09
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping structure for table depor.depor_administrador
CREATE TABLE IF NOT EXISTS `depor_administrador` (
  `PERSONA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT '1',
  PRIMARY KEY (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_38` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_persona` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_administrador: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_administrador` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_administrador` ENABLE KEYS */;


# Dumping structure for table depor.depor_articulo
CREATE TABLE IF NOT EXISTS `depor_articulo` (
  `ARTICULO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PERSONA_ID` int(11) NOT NULL,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT '1',
  PRIMARY KEY (`ARTICULO_ID`),
  KEY `FK_REFERENCE_32` (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_32` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_experto` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_articulo: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_articulo` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_articulo` ENABLE KEYS */;


# Dumping structure for table depor.depor_club
CREATE TABLE IF NOT EXISTS `depor_club` (
  `CLUB_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(150) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT '1',
  PRIMARY KEY (`CLUB_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_club: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_club` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_club` ENABLE KEYS */;


# Dumping structure for table depor.depor_clubdatos
CREATE TABLE IF NOT EXISTS `depor_clubdatos` (
  `CLUBDATOS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TORNEO_ID` int(11) NOT NULL,
  `CLUB_ID` int(11) NOT NULL,
  `ENTRENADOR` varchar(150) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT NULL,
  PRIMARY KEY (`CLUBDATOS_ID`),
  KEY `FK_REFERENCE_3` (`TORNEO_ID`),
  KEY `FK_REFERENCE_4` (`CLUB_ID`),
  CONSTRAINT `FK_REFERENCE_3` FOREIGN KEY (`TORNEO_ID`) REFERENCES `depor_torneo` (`TORNEO_ID`),
  CONSTRAINT `FK_REFERENCE_4` FOREIGN KEY (`CLUB_ID`) REFERENCES `depor_club` (`CLUB_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_clubdatos: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_clubdatos` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_clubdatos` ENABLE KEYS */;


# Dumping structure for table depor.depor_comentario
CREATE TABLE IF NOT EXISTS `depor_comentario` (
  `COMENTARIO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ARTICULO_ID` int(11) NOT NULL,
  `PERSONA_ID` int(11) NOT NULL,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT '1',
  PRIMARY KEY (`COMENTARIO_ID`),
  KEY `FK_REFERENCE_31` (`ARTICULO_ID`),
  KEY `FK_REFERENCE_40` (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_31` FOREIGN KEY (`ARTICULO_ID`) REFERENCES `depor_articulo` (`ARTICULO_ID`),
  CONSTRAINT `FK_REFERENCE_40` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_usuario` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_comentario: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_comentario` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_comentario` ENABLE KEYS */;


# Dumping structure for table depor.depor_concepto
CREATE TABLE IF NOT EXISTS `depor_concepto` (
  `CONCEPTO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT '1',
  PRIMARY KEY (`CONCEPTO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_concepto: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_concepto` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_concepto` ENABLE KEYS */;


# Dumping structure for table depor.depor_equipo
CREATE TABLE IF NOT EXISTS `depor_equipo` (
  `EQUIPO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TORNEO_ID` int(11) NOT NULL,
  `CLUB_ID` int(11) NOT NULL,
  `PUNTAJE` int(11) DEFAULT '0',
  `PUNTAJEDEPOR` int(11) DEFAULT '0',
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT '1',
  PRIMARY KEY (`EQUIPO_ID`),
  KEY `FK_REFERENCE_5` (`TORNEO_ID`),
  KEY `FK_REFERENCE_6` (`CLUB_ID`),
  CONSTRAINT `FK_REFERENCE_5` FOREIGN KEY (`TORNEO_ID`) REFERENCES `depor_torneo` (`TORNEO_ID`),
  CONSTRAINT `FK_REFERENCE_6` FOREIGN KEY (`CLUB_ID`) REFERENCES `depor_club` (`CLUB_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_equipo: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_equipo` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_equipo` ENABLE KEYS */;


# Dumping structure for table depor.depor_experto
CREATE TABLE IF NOT EXISTS `depor_experto` (
  `PERSONA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT NULL,
  PRIMARY KEY (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_39` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_persona` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_experto: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_experto` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_experto` ENABLE KEYS */;


# Dumping structure for table depor.depor_fase
CREATE TABLE IF NOT EXISTS `depor_fase` (
  `FASE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TORNEO_ID` int(11) NOT NULL,
  `DESCRIPCION` varchar(150) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT '1',
  PRIMARY KEY (`FASE_ID`,`TORNEO_ID`),
  KEY `FK_REFERENCE_1` (`TORNEO_ID`),
  CONSTRAINT `FK_REFERENCE_1` FOREIGN KEY (`TORNEO_ID`) REFERENCES `depor_torneo` (`TORNEO_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_fase: ~2 rows (approximately)
/*!40000 ALTER TABLE `depor_fase` DISABLE KEYS */;
INSERT INTO `depor_fase` (`FASE_ID`, `TORNEO_ID`, `DESCRIPCION`, `REGISTRO`, `MODIFICACION`, `ESTADO`) VALUES
	(1, 1, 'fase1', '2011-02-22 18:26:24', NULL, '1'),
	(2, 1, 'fase2', '2011-02-22 18:26:32', NULL, '1');
/*!40000 ALTER TABLE `depor_fase` ENABLE KEYS */;


# Dumping structure for table depor.depor_fecha
CREATE TABLE IF NOT EXISTS `depor_fecha` (
  `FECHA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `FASE_ID` int(11) NOT NULL,
  `TORNEO_ID` int(11) NOT NULL,
  `DESCRIPCION` varchar(150) DEFAULT NULL,
  `TIPO` char(1) DEFAULT NULL COMMENT '0: Fecha de ida, 1:Fecha de vuelta',
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT '1',
  PRIMARY KEY (`FECHA_ID`,`FASE_ID`,`TORNEO_ID`),
  KEY `FK_REFERENCE_2` (`FASE_ID`,`TORNEO_ID`),
  CONSTRAINT `FK_REFERENCE_2` FOREIGN KEY (`FASE_ID`, `TORNEO_ID`) REFERENCES `depor_fase` (`FASE_ID`, `TORNEO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_fecha: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_fecha` DISABLE KEYS */;
INSERT INTO `depor_fecha` (`FECHA_ID`, `FASE_ID`, `TORNEO_ID`, `DESCRIPCION`, `TIPO`, `REGISTRO`, `MODIFICACION`, `ESTADO`) VALUES
	(1, 1, 1, 'FECHA 1', '1', '2011-02-22 19:40:24', NULL, '1'),
	(3, 1, 1, 'FECHA 2', '2', '2011-02-22 19:40:44', NULL, '1'),
	(4, 1, 1, 'FECHA 3', '1', '2011-02-22 19:40:55', NULL, '1'),
	(5, 1, 1, 'FECHA 4', '1', '2011-02-22 19:41:06', NULL, '1'),
	(6, 1, 1, 'FECHA 5', '1', '2011-02-22 19:41:16', NULL, '1'),
	(7, 1, 1, 'FECHA 6', '1', '2011-02-22 19:41:28', NULL, '1'),
	(8, 1, 1, 'FECHA 7', '2', '2011-02-22 19:41:38', NULL, '1'),
	(9, 1, 1, 'FECHA 8', '2', '2011-02-22 19:41:48', NULL, '1'),
	(10, 1, 1, 'FECHA 9', '1', '2011-02-22 19:42:00', NULL, '1');
/*!40000 ALTER TABLE `depor_fecha` ENABLE KEYS */;


# Dumping structure for table depor.depor_grupo
CREATE TABLE IF NOT EXISTS `depor_grupo` (
  `GRUPO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `IMAGEN` varchar(250) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`GRUPO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_grupo: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_grupo` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_grupo` ENABLE KEYS */;


# Dumping structure for table depor.depor_noticia
CREATE TABLE IF NOT EXISTS `depor_noticia` (
  `NOTICIA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PERSONA_ID` int(11) NOT NULL,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT '1',
  PRIMARY KEY (`NOTICIA_ID`),
  KEY `FK_REFERENCE_46` (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_46` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_administrador` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_noticia: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_noticia` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_noticia` ENABLE KEYS */;


# Dumping structure for table depor.depor_noticia_comentario
CREATE TABLE IF NOT EXISTS `depor_noticia_comentario` (
  `NOTICIA_COMENTARIO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOTICIA_ID` int(11) NOT NULL,
  `PERSONA_ID` int(11) NOT NULL,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`NOTICIA_COMENTARIO_ID`),
  KEY `FK_REFERENCE_47` (`NOTICIA_ID`),
  KEY `FK_REFERENCE_48` (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_47` FOREIGN KEY (`NOTICIA_ID`) REFERENCES `depor_noticia` (`NOTICIA_ID`),
  CONSTRAINT `FK_REFERENCE_48` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_usuario` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_noticia_comentario: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_noticia_comentario` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_noticia_comentario` ENABLE KEYS */;


# Dumping structure for table depor.depor_partido
CREATE TABLE IF NOT EXISTS `depor_partido` (
  `PARTIDO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `FECHA_ID` int(11) NOT NULL,
  `FASE_ID` int(11) NOT NULL,
  `TORNEO_ID` int(11) NOT NULL,
  `EQUIPO_LOCAL` int(11) NOT NULL,
  `EQUIPO_VISITANTE` int(11) NOT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`PARTIDO_ID`),
  KEY `FK_REFERENCE_7` (`FECHA_ID`,`FASE_ID`,`TORNEO_ID`),
  KEY `FK_REFERENCE_8` (`EQUIPO_LOCAL`),
  KEY `FK_REFERENCE_9` (`EQUIPO_VISITANTE`),
  CONSTRAINT `FK_REFERENCE_7` FOREIGN KEY (`FECHA_ID`, `FASE_ID`, `TORNEO_ID`) REFERENCES `depor_fecha` (`FECHA_ID`, `FASE_ID`, `TORNEO_ID`),
  CONSTRAINT `FK_REFERENCE_8` FOREIGN KEY (`EQUIPO_LOCAL`) REFERENCES `depor_equipo` (`EQUIPO_ID`),
  CONSTRAINT `FK_REFERENCE_9` FOREIGN KEY (`EQUIPO_VISITANTE`) REFERENCES `depor_equipo` (`EQUIPO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_partido: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_partido` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_partido` ENABLE KEYS */;


# Dumping structure for table depor.depor_partido_resultado
CREATE TABLE IF NOT EXISTS `depor_partido_resultado` (
  `PARTIDO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `RESULTADO_DETALLE_ID` int(11) NOT NULL,
  `RESULTADO_ID` int(11) NOT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `GOLESLOCAL` int(11) DEFAULT NULL,
  `GOLESVISITANTE` int(11) DEFAULT NULL,
  `GOLESDIFERENCIA` int(11) DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`PARTIDO_ID`),
  KEY `FK_REFERENCE_59` (`RESULTADO_DETALLE_ID`,`RESULTADO_ID`),
  CONSTRAINT `FK_REFERENCE_58` FOREIGN KEY (`PARTIDO_ID`) REFERENCES `depor_partido` (`PARTIDO_ID`),
  CONSTRAINT `FK_REFERENCE_59` FOREIGN KEY (`RESULTADO_DETALLE_ID`, `RESULTADO_ID`) REFERENCES `depor_resultado_detalle` (`RESULTADO_DETALLE_ID`, `RESULTADO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_partido_resultado: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_partido_resultado` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_partido_resultado` ENABLE KEYS */;


# Dumping structure for table depor.depor_partido_variablefecha
CREATE TABLE IF NOT EXISTS `depor_partido_variablefecha` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PARTIDO_ID` int(11) NOT NULL,
  `VARIABLEFECHA_ID` int(11) NOT NULL,
  `RANGO_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_REFERENCE_52` (`PARTIDO_ID`),
  KEY `FK_REFERENCE_53` (`VARIABLEFECHA_ID`),
  KEY `FK_REFERENCE_54` (`RANGO_ID`),
  CONSTRAINT `FK_REFERENCE_52` FOREIGN KEY (`PARTIDO_ID`) REFERENCES `depor_partido` (`PARTIDO_ID`),
  CONSTRAINT `FK_REFERENCE_53` FOREIGN KEY (`VARIABLEFECHA_ID`) REFERENCES `depor_variablefecha` (`VARIABLEFECHA_ID`),
  CONSTRAINT `FK_REFERENCE_54` FOREIGN KEY (`RANGO_ID`) REFERENCES `depor_rango` (`RANGO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_partido_variablefecha: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_partido_variablefecha` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_partido_variablefecha` ENABLE KEYS */;


# Dumping structure for table depor.depor_persona
CREATE TABLE IF NOT EXISTS `depor_persona` (
  `PERSONA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `UBIGEO_ID` int(11) NOT NULL,
  `TIPODOC_ID` int(11) NOT NULL,
  `NUMERO_DOCUMENTO` varchar(15) DEFAULT NULL,
  `NOMBRES` varchar(150) DEFAULT NULL,
  `PATERNO` varchar(150) DEFAULT NULL,
  `MATERNO` varchar(150) DEFAULT NULL,
  `TELEFONO` varchar(50) DEFAULT NULL,
  `MOVIL` varchar(50) DEFAULT NULL,
  `FOTO` varchar(250) DEFAULT NULL,
  `DIRECCION` varchar(150) DEFAULT NULL,
  `SEXO` char(1) DEFAULT NULL,
  `EMAIL` varchar(250) DEFAULT NULL,
  `PASSWORD` varchar(100) DEFAULT NULL,
  `FLAGENVIADO` char(1) DEFAULT NULL,
  `NACIMIENTO` datetime DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`PERSONA_ID`),
  KEY `FK_REFERENCE_29` (`UBIGEO_ID`),
  KEY `FK_REFERENCE_41` (`TIPODOC_ID`),
  CONSTRAINT `FK_REFERENCE_29` FOREIGN KEY (`UBIGEO_ID`) REFERENCES `depor_ubigeo` (`UBIGEO_ID`),
  CONSTRAINT `FK_REFERENCE_41` FOREIGN KEY (`TIPODOC_ID`) REFERENCES `depor_tipodocumento` (`TIPODOC_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_persona: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_persona` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_persona` ENABLE KEYS */;


# Dumping structure for table depor.depor_personagrupo
CREATE TABLE IF NOT EXISTS `depor_personagrupo` (
  `GRUPO_ID` int(11) NOT NULL,
  `PERSONA_ID` int(11) NOT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`GRUPO_ID`,`PERSONA_ID`),
  KEY `FK_REFERENCE_37` (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_36` FOREIGN KEY (`GRUPO_ID`) REFERENCES `depor_grupo` (`GRUPO_ID`),
  CONSTRAINT `FK_REFERENCE_37` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_usuario` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_personagrupo: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_personagrupo` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_personagrupo` ENABLE KEYS */;


# Dumping structure for table depor.depor_rango
CREATE TABLE IF NOT EXISTS `depor_rango` (
  `RANGO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `VARIABLEFECHA_ID` int(11) NOT NULL,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `MINIMO` int(11) DEFAULT NULL,
  `MAXIMO` int(11) DEFAULT NULL,
  `PUNTAJE` int(11) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`RANGO_ID`),
  KEY `FK_REFERENCE_51` (`VARIABLEFECHA_ID`),
  CONSTRAINT `FK_REFERENCE_51` FOREIGN KEY (`VARIABLEFECHA_ID`) REFERENCES `depor_variablefecha` (`VARIABLEFECHA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_rango: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_rango` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_rango` ENABLE KEYS */;


# Dumping structure for table depor.depor_redsocial
CREATE TABLE IF NOT EXISTS `depor_redsocial` (
  `REDSOCIAL_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`REDSOCIAL_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_redsocial: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_redsocial` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_redsocial` ENABLE KEYS */;


# Dumping structure for table depor.depor_resultado
CREATE TABLE IF NOT EXISTS `depor_resultado` (
  `RESULTADO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `PUNTAJE` int(11) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`RESULTADO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_resultado: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_resultado` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_resultado` ENABLE KEYS */;


# Dumping structure for table depor.depor_resultado_detalle
CREATE TABLE IF NOT EXISTS `depor_resultado_detalle` (
  `RESULTADO_DETALLE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `RESULTADO_ID` int(11) NOT NULL,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `PUNTAJE` int(11) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT '1',
  PRIMARY KEY (`RESULTADO_DETALLE_ID`,`RESULTADO_ID`),
  KEY `FK_REFERENCE_60` (`RESULTADO_ID`),
  CONSTRAINT `FK_REFERENCE_60` FOREIGN KEY (`RESULTADO_ID`) REFERENCES `depor_resultado` (`RESULTADO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_resultado_detalle: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_resultado_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_resultado_detalle` ENABLE KEYS */;


# Dumping structure for table depor.depor_tipodocumento
CREATE TABLE IF NOT EXISTS `depor_tipodocumento` (
  `TIPODOC_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`TIPODOC_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_tipodocumento: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_tipodocumento` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_tipodocumento` ENABLE KEYS */;


# Dumping structure for table depor.depor_torneo
CREATE TABLE IF NOT EXISTS `depor_torneo` (
  `TORNEO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(150) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`TORNEO_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_torneo: ~2 rows (approximately)
/*!40000 ALTER TABLE `depor_torneo` DISABLE KEYS */;
INSERT INTO `depor_torneo` (`TORNEO_ID`, `DESCRIPCION`, `REGISTRO`, `MODIFICACION`, `ESTADO`) VALUES
	(1, 'wqe', '2011-02-22 17:46:25', NULL, '1'),
	(2, 'ulaula', '2011-02-22 17:48:13', NULL, '1');
/*!40000 ALTER TABLE `depor_torneo` ENABLE KEYS */;


# Dumping structure for table depor.depor_ubigeo
CREATE TABLE IF NOT EXISTS `depor_ubigeo` (
  `UBIGEO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CODPTO` char(2) NOT NULL,
  `COPROV` char(2) NOT NULL,
  `CODDIST` char(2) NOT NULL,
  `DESCRIPCION` varchar(250) NOT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`UBIGEO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_ubigeo: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_ubigeo` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_ubigeo` ENABLE KEYS */;


# Dumping structure for table depor.depor_usuario
CREATE TABLE IF NOT EXISTS `depor_usuario` (
  `PERSONA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_33` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_persona` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_usuario: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_usuario` ENABLE KEYS */;


# Dumping structure for table depor.depor_usuariopartido
CREATE TABLE IF NOT EXISTS `depor_usuariopartido` (
  `USUARIOPARTIDO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PARTIDO_ID` int(11) NOT NULL,
  `PERSONA_ID` int(11) DEFAULT NULL,
  `EQUIPO_GANADOR` int(11) DEFAULT NULL,
  `DIFGOLES` int(11) DEFAULT NULL COMMENT 'Diferencia de goles',
  `PUNTAJE1` int(11) DEFAULT '0' COMMENT 'Puntaje fase 1',
  `PUNTAJE2` int(11) DEFAULT '0' COMMENT 'Puntaje fase 2',
  `PUNTAJE3` int(11) DEFAULT '0' COMMENT 'Puntaje fase 3',
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`USUARIOPARTIDO_ID`,`PARTIDO_ID`),
  KEY `FK_REFERENCE_15` (`PARTIDO_ID`),
  KEY `FK_REFERENCE_23` (`EQUIPO_GANADOR`),
  KEY `FK_REFERENCE_35` (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_15` FOREIGN KEY (`PARTIDO_ID`) REFERENCES `depor_partido` (`PARTIDO_ID`),
  CONSTRAINT `FK_REFERENCE_23` FOREIGN KEY (`EQUIPO_GANADOR`) REFERENCES `depor_equipo` (`EQUIPO_ID`),
  CONSTRAINT `FK_REFERENCE_35` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_usuario` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_usuariopartido: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_usuariopartido` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_usuariopartido` ENABLE KEYS */;


# Dumping structure for table depor.depor_usuariopartido_variablefecha
CREATE TABLE IF NOT EXISTS `depor_usuariopartido_variablefecha` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `VARIABLEFECHA_ID` int(11) NOT NULL,
  `RANGO_ID` int(11) NOT NULL,
  `USUARIOPARTIDO_ID` int(11) NOT NULL,
  `PARTIDO_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_REFERENCE_55` (`VARIABLEFECHA_ID`),
  KEY `FK_REFERENCE_56` (`USUARIOPARTIDO_ID`,`PARTIDO_ID`),
  KEY `FK_REFERENCE_57` (`RANGO_ID`),
  CONSTRAINT `FK_REFERENCE_55` FOREIGN KEY (`VARIABLEFECHA_ID`) REFERENCES `depor_variablefecha` (`VARIABLEFECHA_ID`),
  CONSTRAINT `FK_REFERENCE_56` FOREIGN KEY (`USUARIOPARTIDO_ID`, `PARTIDO_ID`) REFERENCES `depor_usuariopartido` (`USUARIOPARTIDO_ID`, `PARTIDO_ID`),
  CONSTRAINT `FK_REFERENCE_57` FOREIGN KEY (`RANGO_ID`) REFERENCES `depor_rango` (`RANGO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_usuariopartido_variablefecha: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_usuariopartido_variablefecha` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_usuariopartido_variablefecha` ENABLE KEYS */;


# Dumping structure for table depor.depor_usuariopuntaje
CREATE TABLE IF NOT EXISTS `depor_usuariopuntaje` (
  `USUPUNTAJE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `FECHA_ID` int(11) NOT NULL,
  `FASE_ID` int(11) NOT NULL,
  `TORNEO_ID` int(11) NOT NULL,
  `PERSONA_ID` int(11) NOT NULL,
  `PUNTAJE` int(11) DEFAULT '0',
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`USUPUNTAJE_ID`),
  KEY `FK_REFERENCE_17` (`FECHA_ID`,`FASE_ID`,`TORNEO_ID`),
  KEY `FK_REFERENCE_34` (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_17` FOREIGN KEY (`FECHA_ID`, `FASE_ID`, `TORNEO_ID`) REFERENCES `depor_fecha` (`FECHA_ID`, `FASE_ID`, `TORNEO_ID`),
  CONSTRAINT `FK_REFERENCE_34` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_usuario` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_usuariopuntaje: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_usuariopuntaje` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_usuariopuntaje` ENABLE KEYS */;


# Dumping structure for table depor.depor_usuariored
CREATE TABLE IF NOT EXISTS `depor_usuariored` (
  `USUARIORED_ID` int(11) NOT NULL AUTO_INCREMENT,
  `REDSOCIAL_ID` int(11) NOT NULL,
  `PERSONA_ID` int(11) NOT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`USUARIORED_ID`),
  KEY `FK_REFERENCE_42` (`REDSOCIAL_ID`),
  KEY `FK_REFERENCE_43` (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_42` FOREIGN KEY (`REDSOCIAL_ID`) REFERENCES `depor_redsocial` (`REDSOCIAL_ID`),
  CONSTRAINT `FK_REFERENCE_43` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_usuario` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_usuariored: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_usuariored` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_usuariored` ENABLE KEYS */;


# Dumping structure for table depor.depor_usuariored_concepto
CREATE TABLE IF NOT EXISTS `depor_usuariored_concepto` (
  `USUREDCONCEPTO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USUARIORED_ID` int(11) NOT NULL,
  `CONCEPTO_ID` int(11) NOT NULL,
  `VALOR` char(1) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`USUREDCONCEPTO_ID`),
  KEY `FK_REFERENCE_44` (`USUARIORED_ID`),
  KEY `FK_REFERENCE_45` (`CONCEPTO_ID`),
  CONSTRAINT `FK_REFERENCE_44` FOREIGN KEY (`USUARIORED_ID`) REFERENCES `depor_usuariored` (`USUARIORED_ID`),
  CONSTRAINT `FK_REFERENCE_45` FOREIGN KEY (`CONCEPTO_ID`) REFERENCES `depor_concepto` (`CONCEPTO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_usuariored_concepto: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_usuariored_concepto` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_usuariored_concepto` ENABLE KEYS */;


# Dumping structure for table depor.depor_variable
CREATE TABLE IF NOT EXISTS `depor_variable` (
  `VARIABLE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(150) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL,
  PRIMARY KEY (`VARIABLE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_variable: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_variable` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_variable` ENABLE KEYS */;


# Dumping structure for table depor.depor_variablefecha
CREATE TABLE IF NOT EXISTS `depor_variablefecha` (
  `VARIABLEFECHA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `VARIABLE_ID` int(11) NOT NULL,
  `FECHA_ID` int(11) NOT NULL,
  `FASE_ID` int(11) NOT NULL,
  `TORNEO_ID` int(11) NOT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`VARIABLEFECHA_ID`),
  KEY `FK_REFERENCE_49` (`VARIABLE_ID`),
  KEY `FK_REFERENCE_50` (`FECHA_ID`,`FASE_ID`,`TORNEO_ID`),
  CONSTRAINT `FK_REFERENCE_49` FOREIGN KEY (`VARIABLE_ID`) REFERENCES `depor_variable` (`VARIABLE_ID`),
  CONSTRAINT `FK_REFERENCE_50` FOREIGN KEY (`FECHA_ID`, `FASE_ID`, `TORNEO_ID`) REFERENCES `depor_fecha` (`FECHA_ID`, `FASE_ID`, `TORNEO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table depor.depor_variablefecha: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_variablefecha` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_variablefecha` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
