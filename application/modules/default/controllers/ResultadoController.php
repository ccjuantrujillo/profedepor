<?php
class ResultadoController extends Zend_Controller_Action
{
    protected $_partido = null;
    public function init()
    {
        $this->_partido = new Default_Model_Partido();
    }

    public function indexAction(){
        $model_fecha = new Default_Model_Fecha();
        if( ! $fecha_id = $this->_request->getQuery('fecha_id'))
            $fecha_id = $model_fecha->getFechaHoy()-1;

        $fecha = $model_fecha->getFecha($fecha_id);
        $primera_fecha = $this->_partido->getPrimeraFecha($fecha_id);

        $filter = new stdClass();
        $filter->fecha_id = $fecha_id;

        $params = new stdClass();
        $params->fecha_format = '%d/%m';

        $fechas = $model_fecha->listar($filter);
        $partidos = $this->_partido->getPartidoDefault($filter, $params)
           ->fetchAll();

        if( ! $partido_id = $this->_request->getQuery('partido_id')){
            if(count($partidos) > 0){
                $partido = current($partidos);
                $partido_id = $partido->partido_id;
            }
        }

        ## SELECT fns_puntajejugador_fase(8, 7, 1)

        $filter->partido_id = $partido_id;
        if(is_object($this->_partido->getPartidoDefault($filter)->fetch())){
             $partido = $this->_partido->getPartidoDefault($filter)->fetch();

             ## filter jugador_id
             $jugador = Zend_Auth::getInstance()->getIdentity();
             $filter->jugador_id = $jugador->jugador_id;

             $model_puntajeFecha = new Default_Model_PuntajeFecha();
             $puntajeFecha = $model_puntajeFecha->getPuntajeJugador($filter);

             ##Puntaje por partido
             $model_puntaje                = new Default_Model_Puntaje();
             $model_respuestaclave = new Default_Model_RespuestaClave();
             $puntaje1 = $model_puntaje->getPuntajeJugadorTipo($jugador->jugador_id, $partido_id, 1);
             $puntaje2 = $model_puntaje->getPuntajeJugadorTipo($jugador->jugador_id, $partido_id, 2);
             if(count( $model_puntaje->getPuntajeJugadorTipo($jugador->jugador_id, $partido_id, 3))>0){
                  $puntaje3             = $model_puntaje->getPuntajeJugadorTipo($jugador->jugador_id, $partido_id, 3);
                  $puntaje_depor = $model_respuestaclave->obtener_respuestaclave_partido($partido_id, 3);
             }
             else{
                  $puntaje3             = $model_puntaje->getPuntajeJugadorTipo($jugador->jugador_id, $partido_id, 4);
                  $puntaje_depor = $model_respuestaclave->obtener_respuestaclave_partido($partido_id, 4);
             }
        }
        else{
             $partido = new stdClass();
             $partido->puntajeFecha = 0;
             $partido->situacion=0;
             $partido->partido_id="";
             $partido->goles_local="";
             $partido->goles_visita="";
             $partido->local = "";
             $partido->visitante="";
             $partido->icono_local="";
             $partido->icono_visitante="";
             $partido->fase1 = "";
             $partido->fase2 = "";
             $partido->fase3 = "";
             $puntajeFecha = new stdClass();
            $puntajeFecha->puntaje_juego=0;
            $puntajeFecha->posicion=0;
            $puntaje1 = 0;
            $puntaje2 = 0;
            $puntaje3 = 0;
            for($i=0;$i<4;$i++){
                 $puntaje_depor[$i] = new stdClass();
                 $puntaje_depor[$i]->variable_id = 0;
                 $puntaje_depor[$i]->intervalo_id = 0;
                  $puntaje_depor[$i]->puntajedepor = 0;
                  $puntaje_depor[$i]->descripcion ="";
                  $puntaje_depor[$i]->partido_id ="";
            }
        }
        $spec = array(
            'fechas' => $fechas->query(),
            'fecha_id' => $fecha_id,
            'fecha' => $fecha,
            'partidos' => $partidos,
            'partido' => $partido,
            'primera_fecha' => $primera_fecha->fecha_partido,
            'puntaje_fecha_jugador' => $puntajeFecha->puntaje_juego,
            'puntaje1' =>$puntaje1,
            'puntaje2' =>$puntaje2,
            'puntaje3' =>$puntaje3,
            'puntajedepor'=>$puntaje_depor
        );

        $path = Zend_Registry::get('path');
        $this->view->assign($spec);
    }
}