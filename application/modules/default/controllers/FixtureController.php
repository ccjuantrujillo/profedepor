<?php
class FixtureController extends Zend_Controller_Action
{
     private $_partido               = null;
     private $_fecha                  = null;
     private $_resultado         = null;
     private $_puntaje             = null;
     private $_variable            = null;
     private $_partido2           = null;
     public function init()
     {
        $date = new Zend_Date();
        $this->_hoy       = $date->get(Zend_Date::ISO_8601);
        $this->view->headScript()->appendFile('/js/jquery.ezmark.js');
        $this->view->headScript()->appendFile("/js/jquery.qtip-1.0.0-rc3.js");
        $this->view->headScript()->appendFile("/js/juego.js");
        $this->view->headScript()->appendFile('/js/fixture.js');
        $this->view->headScript()->appendFile('/js/jquery.jqtransform.js');
        $this->view->headLink()->appendStylesheet("/css/jqtransform.css");
        $this->view->headLink()->appendStylesheet("/css/ezmark.css");
        $this->view->headTitle(':: Fixture');
         $this->_partido   = new Default_Model_Partido();
         $this->_partido2 = new Default_Model_Partido();
         $this->_fecha      = new Default_Model_Fecha();
        $this->_resultado = new Default_Model_Resultado();
        $this->_puntaje     = new Default_Model_Puntaje();
        $this->_intervals  = new Default_Model_Intervalo();
        $this->_variable   = new Default_Model_Variable();
        $this->_puntajefecha   = new Default_Model_PuntajeFecha();
        $this->_fecha_actual  = $this->_fecha->getFechaHoy();
        $auth = Zend_Auth::getInstance();
        $this->_jugador   = $auth->getIdentity()->jugador_id;
     }
     public function indexAction(){
          $this->_redirect('/fixture/nivel1');
     }
     public function nivel1Action()
     {
          if($this->_request->isGet()){
               $formData = $this->_request->getParams();
               $fecha_id  = $formData['id']==''?$this->_fecha_actual:$formData['id'];
               $partidos = $this->_partido->getPartidoFase1($fecha_id, $this->_jugador);
               $partidos2 = array();
               foreach($partidos as $indice=>$value){
                    $partido_id  = $value->id;
                    $resultados = $this->_resultado->obtener_resultado_partido($partido_id);
                    $partidos2[$indice] = $value;
                    if(isset($resultados['goles_local']) && isset($resultados['goles_visita'])){
                         $partidos2[$indice]->resultados = array($resultados['goles_local'],$resultados['goles_visita']);
                    }
                    else{
                         $partidos2[$indice]->resultados=array('','');
                    }
               }
              $puntaje_nivel1 = $this->_puntaje->getPuntajeTipo($fecha_id,$this->_jugador,1)->puntaje;
              $puntaje_fecha=0;
               $puntaje_total=0;
               $puntaje_juego = $this->_puntajefecha->get_puntajefecha($fecha_id, $this->_jugador);
               if(is_object($puntaje_juego)){
                    $puntaje_total  = $puntaje_juego->puntaje_juego;
               }
                $this->view->puntajes = array($puntaje_nivel1,$puntaje_total);
               $this->view->primera_fecha = $this->_partido2->getPrimeraFecha($fecha_id);
               $this->view->variables = $this->_variable->getVariableTipo(1);
               $this->view->partidos  = $partidos2;
               $this->view->fechas      = $this->_fecha->getFecha($fecha_id);
          }
     }
     public function nivel2Action(){
          if($this->_request->isGet()){
               $formData = $this->_request->getParams();
               $fecha_id   = $formData['id'];
               $fecha_id   = $formData['id']==''?$this->_fecha_actual:$formData['id'];
               $partidos    = $this->_partido->getPartidoFase2($fecha_id, $this->_jugador);
               $partidos2 = array();
               $puntaje_total=0;
               $nombre_equipo="";
               $icono_equipo    = "";
               $nom_resulado  = "";
               $descripcion        = "";
               foreach($partidos as $indice=>$value){
                    $partidos2[$indice] = $value;
                    $partido_id = $value->id;
                    $intervalos  = $value->intervalos;
                    $nombre_local   = $value->local;
                    $nombre_visita = $value->visita;
                    $icono_local       = $value->icono_local;
                    $icono_visita     = $value->icono_visita;
                    $variable_id                = 5;
                    $intervalo_id              = 5;
                    $intervalo_id_array = array();
                    foreach($intervalos as $indice2=>$value2){
                         if($value2['checked']){
                              $variable_id   = $value2['variable_id'];
                              $intervalo_id = $value2['intervalo_id'];
                              $descripcion  = $value2['descripcion'];
                              switch($variable_id){
                                   case 5:
                                        $puntajejuego = $this->_puntaje->getPuntajeJugadorTipo( $this->_jugador, $partido_id, 1);
                                        $variable_id1   = $puntajejuego[0]['variable_id'];
                                        $intervalo_id1 =$puntajejuego[0]['intervalo_id'];
                                        switch ($variable_id1){
                                             case 1:
                                                  $nombre_equipo = "";$icono_equipo      = "";$nom_resulado    = "";$intervalo_id_array=array();$descripcion="";break;
                                             case 2://Gana Local
                                                  $variable_id=6;$nombre_equipo = $nombre_local;$icono_equipo      = $icono_local;$nom_resulado    = "Gana";$intervalo_id_array = $this->_intervals->getIntervaloVariable(6);break;
                                             case 3://Empatan
                                                  $variable_id=7;$nombre_equipo = "";$icono_equipo      = "";$nom_resulado    = "Empata";$intervalo_id_array = $this->_intervals->getIntervaloVariable(7);break;
                                             case 4://Gana visita
                                                  $variable_id=8;$nombre_equipo = $nombre_visita;$icono_equipo      = $icono_visita;$nom_resulado    = "Gana";$intervalo_id_array = $this->_intervals->getIntervaloVariable(8);break;
                                        }
                                        break;
                                   case 6: $nombre_equipo=$nombre_local;$icono_equipo=$icono_local;$nom_resulado="Gana";$intervalo_id_array = $this->_intervals->getIntervaloVariable($variable_id);break;
                                   case 7: $nombre_equipo="";$icono_equipo="";$nom_resulado="Empata";$intervalo_id_array = $this->_intervals->getIntervaloVariable($variable_id);break;
                                   case 8: $nombre_equipo=$nombre_visita;$icono_equipo=$icono_visita;$nom_resulado="Gana";$intervalo_id_array = $this->_intervals->getIntervaloVariable($variable_id);break;
                              }
                         }
                         $intervalo_id_array = $intervalo_id_array;
                    }
               ///
                    $puntaje_nivel2 = $this->_puntaje->getPuntajeTipo($fecha_id,$this->_jugador,2)->puntaje;
                    $puntaje_juego = $this->_puntajefecha->get_puntajefecha($fecha_id, $this->_jugador);
                    if(is_object($puntaje_juego)){
                         $puntaje_total  = $puntaje_juego->puntaje_juego;
                    }
                   $this->view->puntajes = array($puntaje_nivel2,$puntaje_total);
                    $partidos2[$indice]->intervalos      = $intervalo_id_array;
                    $partidos2[$indice]->variable_id   = $variable_id;
                    $partidos2[$indice]->intervalo_id = $intervalo_id;
                    $partidos2[$indice]->nombre_equipo        = $nombre_equipo;
                    $partidos2[$indice]->icono_equipo             = $icono_equipo;
                    $partidos2[$indice]->nombre_resultado   = $nom_resulado;
                    $partidos2[$indice]->descripcion                 = $descripcion;
               }
                $this->view->primera_fecha = $this->_partido2->getPrimeraFecha($fecha_id);
               $this->view->variables = $this->_variable->getVariableTipo(2);
               $this->view->partidos = $partidos2;
               $this->view->fechas    = $this->_fecha->getFecha($fecha_id);
          }
     }
     public function nivel3Action(){
          if($this->_request->isGet()){
               $formData = $this->_request->getParams();
               $fecha_id  = $formData['id']==''?$this->_fecha_actual:$formData['id'];
               $partidos  = $this->_partido->getPartidoFase3($fecha_id, $this->_jugador);
               $partidos2 = array();
               $puntaje_total=0;
               foreach($partidos as $indice=>$value){
                    $partidos2[$indice]   = $value;
                    $partido_id = $value->id;
                    $intervalos = $value->intervalos;
                    $variable_id   = array();
                    $intervalo_id = array();
                    $descripcion  = array();
                    foreach($intervalos as $indice2=>$value2){
                         if($value2['checked']){
                              $variable_id[]    = $value2['variable_id'];
                              $intervalo_id[]  = $value2['intervalo_id'];
                              $descripcion[]   = $value2['descripcion2'];
                         }
                    }
                    $partidos2[$indice]->variable_res   = $variable_id;
                    $partidos2[$indice]->intervalo_res = $intervalo_id;
                    $partidos2[$indice]->descripcion    = $descripcion;

                    $puntaje_nivel3 = $this->_puntaje->getPuntajeTipo($fecha_id,$this->_jugador,3)->puntaje;
                    $puntaje_juego = $this->_puntajefecha->get_puntajefecha($fecha_id, $this->_jugador);
                    if(is_object($puntaje_juego)){
                         $puntaje_total   = $puntaje_juego->puntaje_juego;
                    }
                   $this->view->puntajes = array($puntaje_nivel3,$puntaje_total);
               }
               $this->view->primera_fecha = $this->_partido2->getPrimeraFecha($fecha_id);
               $this->view->partidos              = $partidos2;
               $this->view->fechas                 = $this->_fecha->getFecha($fecha_id);
          }
     }
}
?>