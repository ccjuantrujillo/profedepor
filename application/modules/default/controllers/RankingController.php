<?php
class RankingController extends Zend_Controller_Action
{
    private $_fecha = null;
    private $_fecha_actual = null;
    private $_grupo = null;
    private $_model_jugador = null;
    private $_jugador_id = null;
    private $_jugador = null;

    public function init()
    {
        $this->_fecha = new Default_Model_Fecha();
        $this->_fecha_actual = $this->_fecha->getFechaHoy();
        $this->_grupo = new Default_Model_Grupo($this->_request);
        $this->_model_jugador = new Default_Model_Jugador();
        $auth = Zend_Auth::getInstance();
        $this->_jugador = $auth->getIdentity();
        $this->_jugador_id = $this->_jugador->jugador_id;
    }

    public function indexAction()
    {
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'ranking.js');

        #fecha actual
        $this->view->fecha_id = $this->_fecha_actual;
        #ranking general        
        $this->view->cgeneral = $this->_model_jugador->countRankingGeneral();
        $this->view->rankgeneral = $this->_model_jugador->rankingGeneral();
        $this->view->pos = $this->_model_jugador->posRanking($this->_jugador_id);
        # mi posicion general
        $this->view->posicion_rank_jugador = $this->_jugador->posicion;
        $this->view->nombre_jugador = $this->_jugador->nombres;
        $this->view->puntaje_jugador = $this->_jugador->puntaje;
    }

    public function listemAction()
    {
    	$fec_id = $this->_fecha_actual;
    	if($fec_id != 1)
    		$fec_id = $fec_id - 1;
    		
        $fecha_id = $this->_request->getQuery('position', $fec_id);

        $fecha_hoy = $this->_fecha->select()
            ->from('depor_fecha', array('*', 'fecha1' => "DATE_FORMAT(fecha1, '%d/%m/%Y')"))
            ->where('fecha_id = ?', $fecha_id)
            ->query()
            ->fetch();

        $model_partido = new Default_Model_Partido();
        $primera_fecha = $model_partido->getPrimeraFecha($fecha_hoy->fecha_id);

        $filter = new stdClass();
        $filter->fecha_id = $fecha_hoy->fecha_id;
        $filter->jugador_id = $this->_jugador_id;

        ## puntaje fecha jugador
        $model_puntajeFecha = new Default_Model_PuntajeFecha();
        $puntajeFecha = $model_puntajeFecha->getPuntajeJugador($filter);

        # print_r($puntajeFecha);
        # exit;

        $this->view->puntaje_fecha_jugador = $puntajeFecha->puntaje_juego;
        $this->view->rank_fecha_jugador = $puntajeFecha->posicion;

        # mi posicion general
        $this->view->nombre_jugador = $this->_jugador->nombres;

        $this->view->rankfecha = $this->_model_jugador->obtenerRankingGeneralFecha($fecha_id);

        $pag = $this->_fecha->getPag($fecha_id);
        $content = $this->view->render('ranking/listem.phtml');

        $data = array(
            'primera_fecha' => $primera_fecha->fecha_partido,
            'fecha_desc' => $fecha_hoy->descripcion,
            'pag' => $pag,
            'slide' => $content
        );

        $this->_helper->json($data);
    }
}
?>
