<?php
class PartidoController extends Zend_Controller_Action
{
	 private $_partido = null;
	 private $_equipo  = null;
	 private $_club    = null;
	 private $_fecha   = null;
     public function init()
     {
		$this->_partido = new Default_Model_Partido();
		$this->_equipo  = new Default_Model_Equipo();
		$this->_club    = new Default_Model_Club();
		$this->_fecha   = new Default_Model_Fecha();
     }
     public function indexAction()
     {
//          $listado = array();
//          foreach ($this->_partido->fetchAll() as $partido)
//          {
//          	$fecha        = $partido->fecha_id;
//          	$fase         = $partido->fase_id;
//          	$torneo       = $partido->torneo_id;
//          	$local        = $partido->equipo_local;
//          	$visitante    = $partido->equipo_visitante;
//          	$datos_local  = $this->_equipo->getEquipo($local);
//          	$datos_visitante = $this->_equipo->getEquipo($visitante);
//          	$datos_fecha  = $this->_fecha->getFecha($fecha);
//          	$nombre_fecha = $datos_fecha['descripcion'];
//          	$intervalo    = $datos_fecha['intervalo'];
//          	$club_local     = $datos_local['club_id'];
//          	$club_visitante = $datos_visitante['club_id'];
//          	$datos_club_local     = $this->_club->getClub($club_local);
//          	$datos_club_visitante = $this->_club->getClub($club_visitante);
//          	$nombre_local     = $datos_club_local['descripcion'];
//          	$nombre_visitante = $datos_club_visitante['descripcion'];
//          	$objeto             = new stdClass();
//          	$objeto->fecha      = $fecha;
//          	$objeto->fase       = $fase;
//          	$objeto->local      = $nombre_local;
//          	$objeto->visitante  = $nombre_visitante;
//          	$listado[] = $objeto;
//          }
//          $this->view->fecha     = $nombre_fecha;
//          $this->view->intervalo = $intervalo;
//          $this->view->partidos  = $listado;
     }
}