<?php
class PortadaController extends Zend_Controller_Action
{
    private $_articulo = null;
    private $_fecha = null;
    private $_fecha_actual = null;
    private $_jugadorgrupo = null;
    private $_jugador = null;
    private $_jugador_id = null;
    private $_mensaje = null;
    private $_partido = null;

    public function preDispatch()
    {
    }

    public function init()
    {
        $this->_articulo = new Default_Model_Articulo();
        $this->_jugador = new Default_Model_Jugador();
        $this->_fecha = new Default_Model_Fecha();
        $this->_fecha_actual = $this->_fecha->getFechaHoy();
        $this->_jugadorgrupo = new Default_Model_JugadorGrupo();
        $this->_mensaje = new Default_Model_Muro();
        $this->_partido = new Default_Model_Partido();
        $auth = Zend_Auth::getInstance();
        $this->_jugador_id = $auth->getIdentity()->jugador_id;
    }

    public function indexAction()
    {    	
        //$jugador = Zend_Auth::getInstance();
        # print_r($jugador->getIdentity());
        # exit;
        $this->view->headTitle('Portada');
        $auth = Zend_Auth::getInstance();
        $jugador = $auth->getIdentity();
        $this->view->posicion_rank_jugador = $jugador->posicion;
        $this->view->nombres_jugador = $jugador->nombres;
        $this->view->puntaje_jugador = $jugador->puntaje;
        //$puntaje = $this->_jugador->getPuntaje($this->_jugador_id);
        //$this->view->puntaje_jugador = $this->_jugador->getPuntaje($this->_jugador_id);
        
        $path = Zend_registry::get('path');
        $this->view->headTitle('Portada');

        # si es la primera vez que se logea
        # if(!isset($jugador->session))  $jugador->session="";
        if($jugador->session == 1){
            $this->view->headScript()->appendFile($path->js . 'first_session.js');
            $this->_jugador->firstSession($jugador);
        } elseif($jugador->session == 2){
            $this->view->headScript()->appendFile($path->js . 'recuperar_password.js');
        }
        
        # resultados portada
        $partido = $this->_partido->getPartidoRand();

        $model_club = new Default_Model_Club();
        $equipo_local = $model_club->getClub($partido->equipo_local);
        $equipo_visitante = $model_club->getClub($partido->equipo_visitante);
		
        $model_resultado = new Default_Model_Resultado();
        $this->view->resultados = $model_resultado->obtener_resultado_partido($partido->partido_id);

        $this->view->assign(array(
            'partido' => $partido,
            'equipo_local' => $equipo_local,
            'equipo_visitante' => $equipo_visitante
        ));

        # fixture
        $this->view->fixture = $this->_partido->getPartidoFecha($this->_fecha_actual);
        $this->view->fechoy = $this->_fecha->obtenerFechaPortada($this->_fecha_actual);
        # comentarios de hinchada
        $ultimosGrupos = $this->_jugadorgrupo->getUltimosGrupos($this->_jugador_id);
        $grupo_id = array(0);
        foreach($ultimosGrupos as $j){
            $grupo_id[] = $j->grupo_id;
        }
        $this->view->comentarios = $this->_mensaje->listar_mensajes_usuario($grupo_id);
        # ranking
        $this->view->cgeneral = $this->_jugador->countRankingGeneral();
        $this->view->ranking = $this->_jugador->rankingMinGeneral(9);  
        $this->view->pos = $this->_jugador->posRanking($this->_jugador_id);
        # panel: noticias depor
        $this->view->noticias = $this->_articulo->listar_noticias_portada(1, $this->_fecha_actual);
        $this->view->profedepor = $this->_articulo->listar_noticias_portada(2, $this->_fecha_actual);
        $this->view->pronostico = $this->_articulo->listar_noticias_portada(3, $this->_fecha_actual);
    }
}
?>