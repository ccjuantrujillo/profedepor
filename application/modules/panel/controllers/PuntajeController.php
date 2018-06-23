<?php
class Panel_PuntajeController extends ZF_Controller_Estadistica
{
     private $_resultado = null;
     private $_fecha_actual = null;
     private $_clave         = null;
     private $_jugador    = null;
     public function init(){
          $this->_resultado = new Panel_Model_Resultado();
          $this->_clave         = new Panel_Model_Clave();
          $this->_puntaje    = new Panel_Model_Puntaje();
          $this->_puntajejuego   = new Panel_Model_PuntajeJuego();
          $this->_puntajefecha   = new Panel_Model_PuntajeFecha();
          $this->_jugador      = new Panel_Model_Jugador();
          $this->_partido       = new Default_Model_Partido();
           $this->_fecha         = new Default_Model_Fecha();
           $this->_fase            = new Panel_Model_Fase();
           $this->_variable    = new Default_Model_Variable();
           $this->_intervalo  = new Default_Model_Intervalo();
           $this->_juego         = new Default_Model_Juego();
           $this->_califica      = new Panel_Model_Califica();
           $this->_respuesta               = new Panel_Model_Respuesta();
           $this->_respuestaclave    = new Panel_Model_RespuestaClave();
           $this->_equipo                    = new Default_Model_Equipo();
          $this->view->headScript()->appendFile("/js/jquery.validate.keyPress.js");
           $this->view->headScript()->appendFile("/js/panel/panel.js");
           $this->view->headScript()->appendFile("/js/panel/puntaje.js");
     }
     public function indexAction()
     {
          $fase_id="";
          if($this->_request->isPost()){
               $request = $this->getRequest();
               $fase_id = $request->getParam('fase');
          }
          $fase = $fase_id==''? $this->_fase->fase_actual()->fase_id:$fase_id;
          $request  = $this->getRequest();
          $fila_id     = $request->getParam('id');
           $fechas = $this->_fecha->listar_fechas($fase);
           foreach($fechas as $indice => $value){
                $fechas[$indice]->partidos  = $this->_partido->getPartidoFecha($value->fecha_id);
                $verpartido = ($indice==$fila_id && $fila_id!='')?"1":"0";
                $fechas[$indice]->verpartidos = $verpartido;
           }
           $this->view->fila_id = $fila_id;
           $this->view->fechas = $fechas;
          $this->view->fases    = $this->_fase->listarFases();
           $this->view->fase_id  = $fase;
           $this->view->title        = "Calculo de Puntos";
     }
     public function calcularAction(){
          if($this->_request->isPost()){
               $request   = $this->getRequest();
               $fecha_id  = $request->getPost('fecha_id');
               $fechas = $this->_fecha->getFecha($fecha_id);
               if($fechas['situacion']==1){
                  echo true;
                  $this->_puntaje->calcular_puntos_total($fecha_id);
               }
               else{
                  echo false; 
               }
          }
     }
     public function calculardetalleAction(){
          if($this->_request->isPost()){
               $request                = $this->getRequest();
               $partido_id          = $request->getPost('partido_id');
               $this->_puntaje->calcular_puntos($partido_id);
          }
     }
}
