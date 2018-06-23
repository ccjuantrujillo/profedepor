<?php
class Panel_ResultadoController extends ZF_Controller_Estadistica
{
     private $_resultado = null;
     private $_jugador     = null;
     private $_fecha_actual = null;
     private $_clave         = null;
     private $_fase  = null;
     public function init(){
          $this->_resultado = new Panel_Model_Resultado();
          $this->_clave         = new Panel_Model_Clave();
          $this->_fase           = new Panel_Model_Fase();
          $this->_partido    = new Default_Model_Partido();
           $this->_fecha        = new Default_Model_Fecha();
           $this->_variable   = new Default_Model_Variable();
           $this->_intervalo = new Default_Model_Intervalo();
           $this->_juego        = new Default_Model_Juego();
           $this->_califica      = new Panel_Model_Califica();
           $this->_respuesta      = new Panel_Model_Respuesta();
           $this->_respuestaclave      = new Panel_Model_RespuestaClave();
           $this->_equipo      = new Default_Model_Equipo();
          $this->view->headScript()->appendFile("/js/jquery.validate.keyPress.js");
           $this->view->headScript()->appendFile("/js/panel/panel.js");
            $this->view->headScript()->appendFile("/js/panel/resultado.js");
     }
     public function indexAction()
     {
          $fase_id="";
          if($this->_request->isPost()){
               $request = $this->getRequest();
               $fase_id = $request->getParam('fase');
          }
          $fase = $fase_id==''? $this->_fase->fase_actual()->fase_id:$fase_id;
          $this->view->fases       = $this->_fase->listarFases();
           $this->view->fechas    = $this->_fecha->listar_fechas($fase);
           $this->view->title         = "Resultados Partido";
           $this->view->fase_id  = $fase;
     }
     public function fechaAction(){
          $request  = $this->getRequest();
          $fecha       = $request->getParam('id');
          $partidos = $this->_resultado->listar_resultados($fecha);
          $this->view->fechas = $this->_fecha->getFecha($fecha);
          $this->view->partidos = $partidos;
     }
     public function grabarAction(){
          if($this->_request->isPost()){
               $request           = $this->getRequest();
               $resultado      = $request->getPost('resultado_id');
               $partidos          = $request->getPost('partido_id');
               $goles_local    = $request->getPost('gol_local');
               $goles_visita  = $request->getPost('gol_visita');
               $claves_id1     = $request->getPost('clave_id1');
               $claves_id2     = $request->getPost('clave_id2');
               $fecha_id         = $request->getPost('fecha_id');
               foreach($partidos as $indice=>$value){
                    $partido_id      = $partidos[$indice];
                    $resultado_id = $resultado[$indice];
                    $gol_local         = $goles_local[$indice];
                    $gol_visita        = $goles_visita[$indice];
                    $gol_local         = $gol_local==""?"0":$gol_local;
                    $gol_visita       = $gol_visita==""?"0":$gol_visita;
                    $clave_id1        = $claves_id1[$indice];
                    $clave_id2        = $claves_id2[$indice];
                    $delta_local     = $gol_local - $gol_visita;
                    $delta_visita   = $gol_visita - $gol_local;
                    $delta_empate = $gol_local-$gol_visita==0?$gol_local:$gol_visita;
                    //Fase 1.
                    if($gol_local   >   $gol_visita)  {$variable_id=2;$intervalo_id=2;}
                    if($gol_local ==  $gol_visita)  {$variable_id=3;$intervalo_id=3;}
                    if ($gol_local  <   $gol_visita)  {$variable_id=4;$intervalo_id=4;}
                    $juegos            = $this->_juego->obtener_juego_pvi($partido_id,$variable_id,$intervalo_id);
                    $juego_id       =  $juegos['juego_id'];
                    //Fase2
                    if($gol_local   >   $gol_visita)  {$variable_id2=6;$delta=$delta_local;}
                    if($gol_local ==  $gol_visita)  {$variable_id2=7;$delta=$delta_empate;}
                    if ($gol_local  <   $gol_visita)  {$variable_id2=8;$delta=$delta_visita;}
                     $intervalos2 = $this->_intervalo->getIntervaloVariable($variable_id2);
                     //echo $delta."<br>";
                     foreach($intervalos2 as $value){
                          $inicial = $value['valori'];
                          $final    = $value['valorf'];
                          //echo "$inicial $final $value[variable_id] $value[intervalo_id]<br>";
                          if($variable_id2==$value['variable_id']){
                               if($delta>=$inicial && $delta<=$final) {$intervalo_id2=$value['intervalo_id'];break;}
                          }
                     }
                    $juegos2         = $this->_juego->obtener_juego_pvi($partido_id,$variable_id2,$intervalo_id2);
                    $juego_id2     =  $juegos2['juego_id'];
                    //echo "$partido_id $juego_id $juego_id2 $clave_id1 $clave_id2<br>";
                    //Graba resutlados.
                   if(count($this->_resultado->obtener_resultado_partido($partido_id))==0){
                         $clave_id1  = $this->_clave->insertar_clave($partido_id,$juego_id);
                        $clave_id2 = $this->_clave->insertar_clave($partido_id,$juego_id2);
                        $this->_resultado->insertar_resultado($partido_id, $gol_local, $gol_visita,$clave_id1,$clave_id2);
                        $this->_partido->updatePartidoSituacion($partido_id, 1);
                    }
                    else{
                         $this->_clave->modificar_clave($clave_id1,$partido_id,$juego_id);
                         $this->_clave->modificar_clave($clave_id2,$partido_id,$juego_id2);
                         $this->_resultado->modificar_resultado($resultado_id, $partido_id, $gol_local, $gol_visita);
                    }
               }
               if(count($partidos)==8)   $this->_fecha->updateFechaSituacion ($fecha_id,1);
          }
     }
     public function calificarAction(){
          $request         = $this->getRequest();
          $fecha             = $request->getParam('id');
          $partidos       = $this->_partido->getPartidoFecha($fecha);
          $partidos2 = array();
          foreach($partidos as $indice=>$value){
               if($value->fase3==1){
                    $partidos2[$indice] = $value;
                    $claves[$indice]  = $this->_respuestaclave->obtener_respuestaclave_partido($partidos[$indice]->id,3);
                    $claves4[$indice]  = $this->_respuestaclave->obtener_respuestaclave_partido($partidos[$indice]->id,4);
               }
          }
           $vars_total = array_merge($this->_variable->getVariableTipo(3), $this->_variable->getVariableTipo(4));
           $vars_total3 = $this->_variable->getVariableTipo(3);
          foreach($vars_total as $indice => $value){$vars_total[$indice]['intervalos'] = $this->_intervalo->getIntervaloVariable($value['variable_id']);}
          $this->view->fechas           = $this->_fecha->getFecha($fecha);
           $this->view->partidos      = $partidos2;
           $this->view->claves          = $claves;
           $this->view->claves4          = $claves4;
           $this->view->vars_total   = $vars_total;
           $this->view->vars_total3 = $vars_total3;
     }
     public function grabacalificacionAction(){
          if($this->getRequest()->isPost()){
               $request                  = $this->getRequest();
                $puntos                  = $request->getPost('puntos');
               $respuestas           = $request->getPost('respuesta_id');
               $partidos                 = $request->getPost('partido_id');
               $tipovar                   = $request->getPost('tipovar');
               foreach($puntos as $indice=>$value){
                    $respuesta_id     = $respuestas[$indice];
                    $partido_id          = $partidos[$indice] ;
                    if(count($this->_respuesta->obtener_respuesta($partido_id))==0){
                         $respuesta_id = $this->_respuesta->insertar_respuesta($partido_id);
                         foreach($value as $indice2=>$value2){
                             $this->_respuestaclave->insertar_respuestaclave($respuesta_id, $indice2, $value2);
                              $juegos         = $this->_juego->obtener_juego($partido_id,$indice2,$value2);
                              $juego_id     =  $juegos->juegoid;
                              $this->_clave->insertar_clave($partido_id, $juego_id);
                         }
                    }
                    else{
                         $respuesta_tipo = $this->_respuesta->obtener_respuesta($partido_id);
                         $respuesta_id     = $respuesta_tipo[0]->respuesta_id;
                         $this->_respuestaclave->eliminar_respuestaclave_tipo($respuesta_id,$tipovar);
                         $this->_clave->eliminar_clave($partido_id,$tipovar);//Verificar...
                         foreach($value as $indice2=>$value2){
                             $this->_respuestaclave->insertar_respuestaclave($respuesta_id, $indice2, $value2);
                              $juegos         = $this->_juego->obtener_juego($partido_id,$indice2,$value2);
                              $juego_id     =  $juegos->juegoid;
                              $this->_clave->insertar_clave($partido_id, $juego_id);
                         }
                    }
               }
               $this->_redirect("/panel/resultado");
          }
     }
}
?>