/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     21/02/2011 04:27:03 p.m.                     */
/*==============================================================*/


drop table if exists DEPOR_ARTICULO;

drop table if exists DEPOR_CLUB;

drop table if exists DEPOR_CLUBDATOS;

drop table if exists DEPOR_COMENTARIO;

drop table if exists DEPOR_EQUIPO;

drop table if exists DEPOR_FASE;

drop table if exists DEPOR_FECHA;

drop table if exists DEPOR_GRUPO;

drop table if exists DEPOR_PARTIDO;

drop table if exists DEPOR_PERSONA;

drop table if exists DEPOR_PERSONAGRUPO;

drop table if exists DEPOR_RANGO;

drop table if exists DEPOR_TIPOPERSONA;

drop table if exists DEPOR_TORNEO;

drop table if exists DEPOR_UBIGEO;

drop table if exists DEPOR_USUARIOPARTIDO;

drop table if exists DEPOR_USUARIOPUNTAJE;

drop table if exists DEPOR_VARIABLE;

/*==============================================================*/
/* Table: DEPOR_ARTICULO                                        */
/*==============================================================*/
create table DEPOR_ARTICULO
(
   MENSAJE_ID           int not null AUTO_INCREMENT,
   PERSONA_ID           int,
   DESCRIPCION          varchar(250),
   REGISTRO             timestamp,
   MODIFICACION         datetime,
   ESTADO               char(1) default '1',
   primary key (MENSAJE_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_CLUB                                            */
/*==============================================================*/
create table DEPOR_CLUB
(
   CLUB_ID              int not null AUTO_INCREMENT,
   DESCRIPCION          varchar(150),
   REGISTRO             timestamp default CURRENT_TIMESTAMP,
   MODIFICACION         datetime,
   ESTADO               char(1) default '1',
   primary key (CLUB_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_CLUBDATOS                                       */
/*==============================================================*/
create table DEPOR_CLUBDATOS
(
   CLUBDATOS_ID         int not null AUTO_INCREMENT,
   TORNEO_ID            int,
   CLUB_ID              int,
   ENTRENADOR           varchar(150),
   REGISTRO             timestamp default CURRENT_TIMESTAMP,
   MODIFICACION         datetime,
   ESTADO               char(1),
   primary key (CLUBDATOS_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_COMENTARIO                                      */
/*==============================================================*/
create table DEPOR_COMENTARIO
(
   COMENTARIO_ID        int not null AUTO_INCREMENT,
   MENSAJE_ID           int,
   PERSONA_ID           int,
   DESCRIPCION          varchar(250),
   REGISTRO             timestamp default CURRENT_TIMESTAMP,
   MODIFICACION         datetime,
   ESTADO               char(1) default '1',
   primary key (COMENTARIO_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_EQUIPO                                          */
/*==============================================================*/
create table DEPOR_EQUIPO
(
   EQUIPO_ID            int not null AUTO_INCREMENT,
   TORNEO_ID            int,
   CLUB_ID              int,
   PUNTAJE              int default 0,
   PUNTAJEDEPOR         int default 0,
   REGISTRO             timestamp default CURRENT_TIMESTAMP,
   MODIFICACION         datetime,
   ESTADO               char(1) default '1',
   primary key (EQUIPO_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_FASE                                            */
/*==============================================================*/
create table DEPOR_FASE
(
   FASE_ID              int not null AUTO_INCREMENT,
   TORNEO_ID            int not null,
   DESCRIPCION          varchar(150),
   REGISTRO             timestamp,
   MODIFICACION         datetime,
   ESTADO               char(1) default '1',
   primary key (FASE_ID, TORNEO_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_FECHA                                           */
/*==============================================================*/
create table DEPOR_FECHA
(
   FECHA_ID             int not null AUTO_INCREMENT,
   FASE_ID              int not null,
   TORNEO_ID            int not null,
   DESCRIPCION          varchar(150),
   TIPO                 char(1) comment '0: Fecha de ida, 1:Fecha de vuelta',
   REGISTRO             timestamp default CURRENT_TIMESTAMP,
   MODIFICACION         datetime,
   ESTADO               char(1) default '1',
   primary key (FECHA_ID, FASE_ID, TORNEO_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_GRUPO                                           */
/*==============================================================*/
create table DEPOR_GRUPO
(
   GRUPO_ID             int not null AUTO_INCREMENT,
   DESCRIPCION          varchar(250),
   IMAGEN               varchar(250),
   REGISTRO             timestamp,
   MODIFICACION         datetime,
   ESTADO               char(1),
   primary key (GRUPO_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_PARTIDO                                         */
/*==============================================================*/
create table DEPOR_PARTIDO
(
   PARTIDO_ID           int not null AUTO_INCREMENT,
   FECHA_ID             int,
   FASE_ID              int,
   TORNEO_ID            int,
   EQUIPO_LOCAL         int,
   EQUIPO_VISITANTE     int,
   EQUIPO_GANADOR       int,
   RANGO1               int comment 'Intervalo de calificacion ( por depor)
            Para Ataque
            
            ',
   RANGO2               int comment 'Para defensa
            ',
   RANGO3               int,
   RANGO4               int,
   GOLESLOCAL           int default 0,
   GOLESVISITANTE       int default 0,
   GOLESDIFERENCIA      int default 0,
   TIPO_RESULTADO       char(1) comment '0:Gana Local,1:Gana Visitante,2:Empatan',
   REGISTRO             timestamp,
   MODIFICACION         datetime,
   ESTADO               char(1),
   primary key (PARTIDO_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_PERSONA                                         */
/*==============================================================*/
create table DEPOR_PERSONA
(
   PERSONA_ID           int not null AUTO_INCREMENT,
   UBIGEO_ID            int,
   TIPOPERSONA_ID       int,
   TIPO_DOCUMENTO       char(2),
   NUMERO_DOCUMENTO     varchar(15),
   NOMBRES              varchar(150),
   PATERNO              varchar(150),
   MATERNO              varchar(150),
   TELEFONO             varchar(50),
   MOVIL                varchar(50),
   FOTO                 varchar(250),
   DIRECCION            varchar(150),
   SEXO                 char(1),
   EMAIL                varchar(250),
   PASSWORD             varchar(100),
   FLAGENVIADO          char(1),
   NACIMIENTO           datetime,
   REGISTRO             timestamp default CURRENT_TIMESTAMP,
   MODIFICACION         datetime,
   ESTADO               char(1) default '1',
   primary key (PERSONA_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_PERSONAGRUPO                                    */
/*==============================================================*/
create table DEPOR_PERSONAGRUPO
(
   PERSONA_ID           int,
   GRUPO_ID             int
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

/*==============================================================*/
/* Table: DEPOR_RANGO                                           */
/*==============================================================*/
create table DEPOR_RANGO
(
   RANGO_ID             int not null AUTO_INCREMENT,
   VARIABLE_ID          int,
   DESCRIPCION          varchar(250),
   MINIMO               int,
   MAXIMO               int,
   PUNTAJE              int,
   REGISTRO             timestamp,
   MODIFICACION         datetime,
   ESTADO               char(1),
   primary key (RANGO_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_TIPOPERSONA                                     */
/*==============================================================*/
create table DEPOR_TIPOPERSONA
(
   TIPOPERSONA_ID       int not null AUTO_INCREMENT,
   DESCRIPCION          varchar(250),
   ESTADO               char(1) default '1',
   primary key (TIPOPERSONA_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_TORNEO                                          */
/*==============================================================*/
create table DEPOR_TORNEO
(
   TORNEO_ID            int not null AUTO_INCREMENT,
   DESCRIPCION          varchar(150),
   REGISTRO             timestamp default CURRENT_TIMESTAMP,
   MODIFICACION         datetime,
   ESTADO               char(1) default '1',
   primary key (TORNEO_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_UBIGEO                                          */
/*==============================================================*/
create table DEPOR_UBIGEO
(
   UBIGEO_ID            int not null AUTO_INCREMENT,
   CODPTO               char(2),
   COPROV               char(2),
   CODDIST              char(2),
   DESCRIPCION          varchar(250),
   ESTADO               char(1) default '1',
   primary key (UBIGEO_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_USUARIOPARTIDO                                  */
/*==============================================================*/
create table DEPOR_USUARIOPARTIDO
(
   USUARIOPARTIDO_ID    int not null AUTO_INCREMENT,
   PARTIDO_ID           int,
   EQUIPO_GANADOR       int,
   RANGO1               int,
   RANGO2               int,
   RANGO3               int,
   RANGO4               int,
   PERSONA_ID           int,
   DIFGOLES             int comment 'Diferencia de goles',
   PUNTAJE1             int default 0 comment 'Puntaje fase 1',
   PUNTAJE2             int default 0 comment 'Puntaje fase 2',
   PUNTAJE3             int default 0 comment 'Puntaje fase 3',
   REGISTRO             timestamp,
   MODIFICACION         datetime,
   ESTADO               char(1) default '1',
   primary key (USUARIOPARTIDO_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_USUARIOPUNTAJE                                  */
/*==============================================================*/
create table DEPOR_USUARIOPUNTAJE
(
   USUPUNTAJE_ID        int not null AUTO_INCREMENT,
   FECHA_ID             int,
   FASE_ID              int,
   TORNEO_ID            int,
   PERSONA_ID           int,
   PUNTAJE              int default 0,
   REGISTRO             timestamp,
   MODIFICACION         datetime,
   ESTADO               char(1),
   primary key (USUPUNTAJE_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/*==============================================================*/
/* Table: DEPOR_VARIABLE                                        */
/*==============================================================*/
create table DEPOR_VARIABLE
(
   VARIABLE_ID          int not null AUTO_INCREMENT,
   DESCRIPCION          varchar(150),
   REGISTRO             timestamp default CURRENT_TIMESTAMP,
   MODIFICACION         datetime,
   ESTADO               char(1),
   primary key (VARIABLE_ID)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

alter table DEPOR_ARTICULO add constraint FK_REFERENCE_32 foreign key (PERSONA_ID)
      references DEPOR_PERSONA (PERSONA_ID) on delete restrict on update restrict;

alter table DEPOR_CLUBDATOS add constraint FK_REFERENCE_3 foreign key (TORNEO_ID)
      references DEPOR_TORNEO (TORNEO_ID) on delete restrict on update restrict;

alter table DEPOR_CLUBDATOS add constraint FK_REFERENCE_4 foreign key (CLUB_ID)
      references DEPOR_CLUB (CLUB_ID) on delete restrict on update restrict;

alter table DEPOR_COMENTARIO add constraint FK_REFERENCE_30 foreign key (PERSONA_ID)
      references DEPOR_PERSONA (PERSONA_ID) on delete restrict on update restrict;

alter table DEPOR_COMENTARIO add constraint FK_REFERENCE_31 foreign key (MENSAJE_ID)
      references DEPOR_ARTICULO (MENSAJE_ID) on delete restrict on update restrict;

alter table DEPOR_EQUIPO add constraint FK_REFERENCE_5 foreign key (TORNEO_ID)
      references DEPOR_TORNEO (TORNEO_ID) on delete restrict on update restrict;

alter table DEPOR_EQUIPO add constraint FK_REFERENCE_6 foreign key (CLUB_ID)
      references DEPOR_CLUB (CLUB_ID) on delete restrict on update restrict;

alter table DEPOR_FASE add constraint FK_REFERENCE_1 foreign key (TORNEO_ID)
      references DEPOR_TORNEO (TORNEO_ID) on delete restrict on update restrict;

alter table DEPOR_FECHA add constraint FK_REFERENCE_2 foreign key (FASE_ID, TORNEO_ID)
      references DEPOR_FASE (FASE_ID, TORNEO_ID) on delete restrict on update restrict;

alter table DEPOR_PARTIDO add constraint FK_REFERENCE_16 foreign key (EQUIPO_GANADOR)
      references DEPOR_EQUIPO (EQUIPO_ID) on delete restrict on update restrict;

alter table DEPOR_PARTIDO add constraint FK_REFERENCE_18 foreign key (RANGO1)
      references DEPOR_RANGO (RANGO_ID) on delete restrict on update restrict;

alter table DEPOR_PARTIDO add constraint FK_REFERENCE_19 foreign key (RANGO2)
      references DEPOR_RANGO (RANGO_ID) on delete restrict on update restrict;

alter table DEPOR_PARTIDO add constraint FK_REFERENCE_21 foreign key (RANGO3)
      references DEPOR_RANGO (RANGO_ID) on delete restrict on update restrict;

alter table DEPOR_PARTIDO add constraint FK_REFERENCE_22 foreign key (RANGO4)
      references DEPOR_RANGO (RANGO_ID) on delete restrict on update restrict;

alter table DEPOR_PARTIDO add constraint FK_REFERENCE_7 foreign key (FECHA_ID, FASE_ID, TORNEO_ID)
      references DEPOR_FECHA (FECHA_ID, FASE_ID, TORNEO_ID) on delete restrict on update restrict;

alter table DEPOR_PARTIDO add constraint FK_REFERENCE_8 foreign key (EQUIPO_LOCAL)
      references DEPOR_EQUIPO (EQUIPO_ID) on delete restrict on update restrict;

alter table DEPOR_PARTIDO add constraint FK_REFERENCE_9 foreign key (EQUIPO_VISITANTE)
      references DEPOR_EQUIPO (EQUIPO_ID) on delete restrict on update restrict;

alter table DEPOR_PERSONA add constraint FK_REFERENCE_29 foreign key (UBIGEO_ID)
      references DEPOR_UBIGEO (UBIGEO_ID) on delete restrict on update restrict;

alter table DEPOR_PERSONA add constraint FK_REFERENCE_35 foreign key (TIPOPERSONA_ID)
      references DEPOR_TIPOPERSONA (TIPOPERSONA_ID) on delete restrict on update restrict;

alter table DEPOR_PERSONAGRUPO add constraint FK_REFERENCE_33 foreign key (PERSONA_ID)
      references DEPOR_PERSONA (PERSONA_ID) on delete restrict on update restrict;

alter table DEPOR_PERSONAGRUPO add constraint FK_REFERENCE_36 foreign key (GRUPO_ID)
      references DEPOR_GRUPO (GRUPO_ID) on delete restrict on update restrict;

alter table DEPOR_RANGO add constraint FK_REFERENCE_20 foreign key (VARIABLE_ID)
      references DEPOR_VARIABLE (VARIABLE_ID) on delete restrict on update restrict;

alter table DEPOR_USUARIOPARTIDO add constraint FK_REFERENCE_15 foreign key (PARTIDO_ID)
      references DEPOR_PARTIDO (PARTIDO_ID) on delete restrict on update restrict;

alter table DEPOR_USUARIOPARTIDO add constraint FK_REFERENCE_23 foreign key (EQUIPO_GANADOR)
      references DEPOR_EQUIPO (EQUIPO_ID) on delete restrict on update restrict;

alter table DEPOR_USUARIOPARTIDO add constraint FK_REFERENCE_24 foreign key (RANGO1)
      references DEPOR_RANGO (RANGO_ID) on delete restrict on update restrict;

alter table DEPOR_USUARIOPARTIDO add constraint FK_REFERENCE_25 foreign key (RANGO2)
      references DEPOR_RANGO (RANGO_ID) on delete restrict on update restrict;

alter table DEPOR_USUARIOPARTIDO add constraint FK_REFERENCE_26 foreign key (RANGO3)
      references DEPOR_RANGO (RANGO_ID) on delete restrict on update restrict;

alter table DEPOR_USUARIOPARTIDO add constraint FK_REFERENCE_27 foreign key (RANGO4)
      references DEPOR_RANGO (RANGO_ID) on delete restrict on update restrict;

alter table DEPOR_USUARIOPARTIDO add constraint FK_REFERENCE_28 foreign key (PERSONA_ID)
      references DEPOR_PERSONA (PERSONA_ID) on delete restrict on update restrict;

alter table DEPOR_USUARIOPUNTAJE add constraint FK_REFERENCE_17 foreign key (FECHA_ID, FASE_ID, TORNEO_ID)
      references DEPOR_FECHA (FECHA_ID, FASE_ID, TORNEO_ID) on delete restrict on update restrict;

alter table DEPOR_USUARIOPUNTAJE add constraint FK_REFERENCE_34 foreign key (PERSONA_ID)
      references DEPOR_PERSONA (PERSONA_ID) on delete restrict on update restrict;

