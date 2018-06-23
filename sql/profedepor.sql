# --------------------------------------------------------
# Host:                         127.0.0.1
# Server version:               5.1.41
# Server OS:                    Win32
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-02-21 18:46:06
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping structure for table profedepor.depor_articulo
CREATE TABLE IF NOT EXISTS `depor_articulo` (
  `MENSAJE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PERSONA_ID` int(11) NOT NULL,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT '1',
  PRIMARY KEY (`MENSAJE_ID`),
  KEY `FK_REFERENCE_32` (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_32` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_persona` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_articulo: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_articulo` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_articulo` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_club
CREATE TABLE IF NOT EXISTS `depor_club` (
  `CLUB_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(150) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) DEFAULT '1',
  PRIMARY KEY (`CLUB_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_club: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_club` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_club` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_clubdatos
CREATE TABLE IF NOT EXISTS `depor_clubdatos` (
  `CLUBDATOS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TORNEO_ID` int(11) NOT NULL,
  `CLUB_ID` int(11) NOT NULL,
  `ENTRENADOR` varchar(150) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`CLUBDATOS_ID`),
  KEY `FK_REFERENCE_3` (`TORNEO_ID`),
  KEY `FK_REFERENCE_4` (`CLUB_ID`),
  CONSTRAINT `FK_REFERENCE_3` FOREIGN KEY (`TORNEO_ID`) REFERENCES `depor_torneo` (`TORNEO_ID`),
  CONSTRAINT `FK_REFERENCE_4` FOREIGN KEY (`CLUB_ID`) REFERENCES `depor_club` (`CLUB_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_clubdatos: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_clubdatos` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_clubdatos` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_comentario
CREATE TABLE IF NOT EXISTS `depor_comentario` (
  `COMENTARIO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `MENSAJE_ID` int(11) NOT NULL,
  `PERSONA_ID` int(11) NOT NULL,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`COMENTARIO_ID`),
  KEY `FK_REFERENCE_30` (`PERSONA_ID`),
  KEY `FK_REFERENCE_31` (`MENSAJE_ID`),
  CONSTRAINT `FK_REFERENCE_30` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_persona` (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_31` FOREIGN KEY (`MENSAJE_ID`) REFERENCES `depor_articulo` (`MENSAJE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_comentario: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_comentario` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_comentario` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_equipo
CREATE TABLE IF NOT EXISTS `depor_equipo` (
  `EQUIPO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TORNEO_ID` int(11) NOT NULL,
  `CLUB_ID` int(11) NOT NULL,
  `PUNTAJE` int(11) NOT NULL DEFAULT '0',
  `PUNTAJEDEPOR` int(11) NOT NULL DEFAULT '0',
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`EQUIPO_ID`),
  KEY `FK_REFERENCE_5` (`TORNEO_ID`),
  KEY `FK_REFERENCE_6` (`CLUB_ID`),
  CONSTRAINT `FK_REFERENCE_5` FOREIGN KEY (`TORNEO_ID`) REFERENCES `depor_torneo` (`TORNEO_ID`),
  CONSTRAINT `FK_REFERENCE_6` FOREIGN KEY (`CLUB_ID`) REFERENCES `depor_club` (`CLUB_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_equipo: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_equipo` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_equipo` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_fase
CREATE TABLE IF NOT EXISTS `depor_fase` (
  `FASE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TORNEO_ID` int(11) NOT NULL,
  `DESCRIPCION` varchar(150) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`FASE_ID`,`TORNEO_ID`),
  KEY `FK_REFERENCE_1` (`TORNEO_ID`),
  CONSTRAINT `FK_REFERENCE_1` FOREIGN KEY (`TORNEO_ID`) REFERENCES `depor_torneo` (`TORNEO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_fase: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_fase` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_fase` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_fecha
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

# Dumping data for table profedepor.depor_fecha: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_fecha` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_fecha` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_grupo
CREATE TABLE IF NOT EXISTS `depor_grupo` (
  `GRUPO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `IMAGEN` varchar(250) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`GRUPO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_grupo: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_grupo` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_grupo` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_partido
CREATE TABLE IF NOT EXISTS `depor_partido` (
  `PARTIDO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `FECHA_ID` int(11) NOT NULL,
  `FASE_ID` int(11) NOT NULL,
  `TORNEO_ID` int(11) NOT NULL,
  `EQUIPO_LOCAL` int(11) NOT NULL,
  `EQUIPO_VISITANTE` int(11) NOT NULL,
  `EQUIPO_GANADOR` int(11) NOT NULL,
  `RANGO1` int(11) NOT NULL COMMENT 'Intervalo de calificacion ( por depor)\\r\\n            Para Ataque\\r\\n            \\r\\n            ',
  `RANGO2` int(11) NOT NULL COMMENT 'Para defensa\\r\\n            ',
  `RANGO3` int(11) NOT NULL,
  `RANGO4` int(11) NOT NULL,
  `GOLESLOCAL` int(11) DEFAULT '0',
  `GOLESVISITANTE` int(11) DEFAULT '0',
  `GOLESDIFERENCIA` int(11) DEFAULT '0',
  `TIPO_RESULTADO` char(1) NOT NULL COMMENT '0:Gana Local,1:Gana Visitante,2:Empatan',
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`PARTIDO_ID`),
  KEY `FK_REFERENCE_16` (`EQUIPO_GANADOR`),
  KEY `FK_REFERENCE_18` (`RANGO1`),
  KEY `FK_REFERENCE_19` (`RANGO2`),
  KEY `FK_REFERENCE_21` (`RANGO3`),
  KEY `FK_REFERENCE_22` (`RANGO4`),
  KEY `FK_REFERENCE_7` (`FECHA_ID`,`FASE_ID`,`TORNEO_ID`),
  KEY `FK_REFERENCE_8` (`EQUIPO_LOCAL`),
  KEY `FK_REFERENCE_9` (`EQUIPO_VISITANTE`),
  CONSTRAINT `FK_REFERENCE_16` FOREIGN KEY (`EQUIPO_GANADOR`) REFERENCES `depor_equipo` (`EQUIPO_ID`),
  CONSTRAINT `FK_REFERENCE_18` FOREIGN KEY (`RANGO1`) REFERENCES `depor_rango` (`RANGO_ID`),
  CONSTRAINT `FK_REFERENCE_19` FOREIGN KEY (`RANGO2`) REFERENCES `depor_rango` (`RANGO_ID`),
  CONSTRAINT `FK_REFERENCE_21` FOREIGN KEY (`RANGO3`) REFERENCES `depor_rango` (`RANGO_ID`),
  CONSTRAINT `FK_REFERENCE_22` FOREIGN KEY (`RANGO4`) REFERENCES `depor_rango` (`RANGO_ID`),
  CONSTRAINT `FK_REFERENCE_7` FOREIGN KEY (`FECHA_ID`, `FASE_ID`, `TORNEO_ID`) REFERENCES `depor_fecha` (`FECHA_ID`, `FASE_ID`, `TORNEO_ID`),
  CONSTRAINT `FK_REFERENCE_8` FOREIGN KEY (`EQUIPO_LOCAL`) REFERENCES `depor_equipo` (`EQUIPO_ID`),
  CONSTRAINT `FK_REFERENCE_9` FOREIGN KEY (`EQUIPO_VISITANTE`) REFERENCES `depor_equipo` (`EQUIPO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_partido: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_partido` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_partido` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_persona
CREATE TABLE IF NOT EXISTS `depor_persona` (
  `PERSONA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `UBIGEO_ID` int(11) NOT NULL,
  `TIPOPERSONA_ID` int(11) NOT NULL,
  `TIPO_DOCUMENTO` char(2) NOT NULL,
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
  KEY `FK_REFERENCE_35` (`TIPOPERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_29` FOREIGN KEY (`UBIGEO_ID`) REFERENCES `depor_ubigeo` (`UBIGEO_ID`),
  CONSTRAINT `FK_REFERENCE_35` FOREIGN KEY (`TIPOPERSONA_ID`) REFERENCES `depor_tipopersona` (`TIPOPERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_persona: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_persona` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_persona` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_personagrupo
CREATE TABLE IF NOT EXISTS `depor_personagrupo` (
  `PERSONA_ID` int(11) DEFAULT NULL,
  `GRUPO_ID` int(11) DEFAULT NULL,
  KEY `FK_REFERENCE_33` (`PERSONA_ID`),
  KEY `FK_REFERENCE_36` (`GRUPO_ID`),
  CONSTRAINT `FK_REFERENCE_36` FOREIGN KEY (`GRUPO_ID`) REFERENCES `depor_grupo` (`GRUPO_ID`),
  CONSTRAINT `FK_REFERENCE_33` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_persona` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_personagrupo: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_personagrupo` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_personagrupo` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_rango
CREATE TABLE IF NOT EXISTS `depor_rango` (
  `RANGO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `VARIABLE_ID` int(11) NOT NULL,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `MINIMO` int(11) NOT NULL DEFAULT '0',
  `MAXIMO` int(11) NOT NULL DEFAULT '0',
  `PUNTAJE` int(11) NOT NULL DEFAULT '0',
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`RANGO_ID`),
  KEY `FK_REFERENCE_20` (`VARIABLE_ID`),
  CONSTRAINT `FK_REFERENCE_20` FOREIGN KEY (`VARIABLE_ID`) REFERENCES `depor_variable` (`VARIABLE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_rango: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_rango` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_rango` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_tipopersona
CREATE TABLE IF NOT EXISTS `depor_tipopersona` (
  `TIPOPERSONA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`TIPOPERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_tipopersona: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_tipopersona` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_tipopersona` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_torneo
CREATE TABLE IF NOT EXISTS `depor_torneo` (
  `TORNEO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(150) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`TORNEO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_torneo: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_torneo` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_torneo` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_ubigeo
CREATE TABLE IF NOT EXISTS `depor_ubigeo` (
  `UBIGEO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CODPTO` char(2) DEFAULT NULL,
  `COPROV` char(2) DEFAULT NULL,
  `CODDIST` char(2) DEFAULT NULL,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`UBIGEO_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_ubigeo: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_ubigeo` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_ubigeo` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_usuariopartido
CREATE TABLE IF NOT EXISTS `depor_usuariopartido` (
  `USUARIOPARTIDO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `PARTIDO_ID` int(11) NOT NULL,
  `EQUIPO_GANADOR` int(11) NOT NULL,
  `RANGO1` int(11) NOT NULL,
  `RANGO2` int(11) NOT NULL,
  `RANGO3` int(11) NOT NULL,
  `RANGO4` int(11) NOT NULL,
  `PERSONA_ID` int(11) NOT NULL,
  `DIFGOLES` int(11) NOT NULL DEFAULT '0' COMMENT 'Diferencia de goles',
  `PUNTAJE1` int(11) NOT NULL DEFAULT '0' COMMENT 'Puntaje fase 1',
  `PUNTAJE2` int(11) NOT NULL DEFAULT '0' COMMENT 'Puntaje fase 2',
  `PUNTAJE3` int(11) NOT NULL DEFAULT '0' COMMENT 'Puntaje fase 3',
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`USUARIOPARTIDO_ID`),
  KEY `FK_REFERENCE_15` (`PARTIDO_ID`),
  KEY `FK_REFERENCE_23` (`EQUIPO_GANADOR`),
  KEY `FK_REFERENCE_24` (`RANGO1`),
  KEY `FK_REFERENCE_25` (`RANGO2`),
  KEY `FK_REFERENCE_26` (`RANGO3`),
  KEY `FK_REFERENCE_27` (`RANGO4`),
  KEY `FK_REFERENCE_28` (`PERSONA_ID`),
  CONSTRAINT `FK_REFERENCE_15` FOREIGN KEY (`PARTIDO_ID`) REFERENCES `depor_partido` (`PARTIDO_ID`),
  CONSTRAINT `FK_REFERENCE_23` FOREIGN KEY (`EQUIPO_GANADOR`) REFERENCES `depor_equipo` (`EQUIPO_ID`),
  CONSTRAINT `FK_REFERENCE_24` FOREIGN KEY (`RANGO1`) REFERENCES `depor_rango` (`RANGO_ID`),
  CONSTRAINT `FK_REFERENCE_25` FOREIGN KEY (`RANGO2`) REFERENCES `depor_rango` (`RANGO_ID`),
  CONSTRAINT `FK_REFERENCE_26` FOREIGN KEY (`RANGO3`) REFERENCES `depor_rango` (`RANGO_ID`),
  CONSTRAINT `FK_REFERENCE_27` FOREIGN KEY (`RANGO4`) REFERENCES `depor_rango` (`RANGO_ID`),
  CONSTRAINT `FK_REFERENCE_28` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_persona` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_usuariopartido: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_usuariopartido` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_usuariopartido` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_usuariopuntaje
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
  CONSTRAINT `FK_REFERENCE_34` FOREIGN KEY (`PERSONA_ID`) REFERENCES `depor_persona` (`PERSONA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_usuariopuntaje: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_usuariopuntaje` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_usuariopuntaje` ENABLE KEYS */;


# Dumping structure for table profedepor.depor_variable
CREATE TABLE IF NOT EXISTS `depor_variable` (
  `VARIABLE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(150) DEFAULT NULL,
  `REGISTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFICACION` datetime DEFAULT NULL,
  `ESTADO` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`VARIABLE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# Dumping data for table profedepor.depor_variable: ~0 rows (approximately)
/*!40000 ALTER TABLE `depor_variable` DISABLE KEYS */;
/*!40000 ALTER TABLE `depor_variable` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
