<?php
class JuegoController extends Zend_Controller_Action
{
    private $_fecha_actual = null;
    private $_partido = null;
    private $_fecha    = null;
    private $_puntaje = null;
    private $_jugador = null;
    private $_variable = null;
    private $_intervalo = null;
    private $_torneo_actual = null;
    private $_fase_actual       = null;
    private $_fase_id               = null;
    private $_torneo_id         = null;
    private $_hoy                     = null;
    private $_namespace1  = null;
    private $_namespace2  = null;
    private $_namespace3  = null;
    public function init()
    {
        $date = new Zend_Date();
        $this->_hoy       = $date->get(Zend_Date::ISO_8601);
        $this->_partido   = new Default_Model_Partido();
        $this->_fecha     = new Default_Model_Fecha();
        $this->_puntaje   = new Default_Model_Puntaje();
        $this->_variable  = new Default_Model_Variable();
        $this->_intervalo = new Default_Model_Intervalo();
        $this->_fecha_actual    = $this->_fecha->getFechaHoy();
        $fechas  = $this->_fecha->getFecha($this->_fecha_actual);
        $this->_fase_id        = $fechas['fase_id'];
         $this->_torneo_id  = $fechas['torneo_id'];
        $this->view->headScript()->appendFile("/js/jquery.qtip-1.0.0-rc3.js");
        $this->view->headScript()->appendFile("/js/jquery.floatingbox.js");
        $this->view->headScript()->appendFile("/js/juego.js");
        $this->view->headTitle(':: Juega');
        $auth = Zend_Auth::getInstance();
        $this->_jugador   = $auth->getIdentity()->jugador_id;
         $this->_namespace1 = new Zend_Session_Namespace('fase1');
    }
    public function indexAction()
    {
      if($this->_request->isGet()){
        $formData = $this->_request->getParams();
      }
      $fecha_id  = !isset($formData['id'])?$this->_fecha_actual:$formData['id'];
      $this->_fecha->getFechaHoy();
      $fase = 1;
      $fechas = $this->_fecha->listar_fechas( $fase);
      foreach($fechas as $indice=>$value){
           $arrFecha[] = $value->fecha_id;
      }
      $minimo  = $arrFecha[0];
      $maximo = $arrFecha[count($arrFecha)-1];
      if($fecha_id<$minimo)  $fecha_id = $minimo;
      if($fecha_id>$maximo)  $fecha_id = $maximo;
      $oPartido   = new Default_Model_Partido();
      $texto0 = "";
      $texto1 = "";
      $texto2 = "";
      $link0    = "#";
      $link1    = "#";
      $link2    = "#";
      if($this->_fecha_actual==$fecha_id){
           $texto1 = "Ingresar";
           $link1    = "/juego/fase1";
      }
      elseif($this->_fecha_actual>$fecha_id){
           $texto1 = "Ver";
           $link1    = "/fixture/nivel1/id/".$fecha_id;
      }
      if($this->_fecha_actual==$fecha_id-1){
           $texto0 = "Ingresar";
           $link0    = "/juego/fase1";
      }
      elseif($this->_fecha_actual>$fecha_id-1){
           $texto0 = "Ver";
           $link0    = "/fixture/nivel1/id/".($fecha_id-1);
      }
      if($this->_fecha_actual==$fecha_id+1){
           $texto2 = "Ingresar";
           $link2    = "/juego/fase1";
      }
      elseif($this->_fecha_actual>$fecha_id+1){
           $texto2 = "Ver";
           $link2    = "/fixture/nivel1/id/".($fecha_id+1);
      }
      $this->view->assign(array(
               'fecha0' => $oPartido->getPrimeraFecha($fecha_id-1),
               'fecha1' => $oPartido->getPrimeraFecha($fecha_id),
               'fecha2' => $oPartido->getPrimeraFecha($fecha_id+1),
               'texto0' => $texto0,
               'texto1' => $texto1,
               'texto2' => $texto2,
               'link0'       => $link0,
               'link1'       => $link1,
               'link2'       => $link2,
               'minimo' => $minimo,
               'maximo' =>$maximo,
               'partidos'=>$this->_partido->getPartidoFecha($fecha_id),
               'fecha_id' => $fecha_id
      ));
    }
    public function fase1Action()
    {
         //Muestro todo lo de la session1
         $partidos                   = $this->_partido->getPartidoFase1($this->_fecha_actual,$this->_jugador);
         $namespace1          = new Zend_Session_Namespace('fase1');
         $partidos1                = $namespace1->partido;
         $hora1                       = $namespace1->hora;
         $puntajejuego_id = $namespace1->puntajejuego_id;
         $intervalos1            = $namespace1->intervalos;
         $partidos2                = array();
         foreach($partidos as $indice=>$value){
              $partidos2[$indice] = $value;
              $partido_id                 = $value->id;
              if(isset($intervalos1[$indice])){
                   foreach($value->intervalos as $indice2=>$value2){
                        foreach($intervalos1[$indice] as $indice3=>$value3){$intervalo_id1 = $indice3;$variable_id1=$value3;}
                        if($value2['intervalo_id']==$intervalo_id1  && $value2['variable_id']==$variable_id1){
                              $partidos2[$indice]->intervalos[$indice2]['checked']=true;
                        }
                        else{
                              $partidos2[$indice]->intervalos[$indice2]['checked']=false;
                        }
                   }
              }
         }
         $this->view->variables = $this->_variable->getVariableTipo(1);
        $this->view->fecha         = $this->_fecha->getFecha($this->_fecha_actual);
        $this->view->partidos   = $partidos2;
    }
    public function mensaje1Action()
    {
        if($this->_request->isPost()) {
          $request     = $this->getRequest();
          $partidos    = $request->getPost('partido_id');
          $intervalos  = $request->getPost('intervalos');
          $puntajejuego_id = $request->getPost('puntajejuego_id');
          $fecha_registro  = $request->getPost('fecha_registro');
          //Guardo matriz de horas en session.
          $ntervalos2 = array();
          $msg = 0;
          foreach($partidos as $indice=>$value){
               //Para la fecha registro
              if($fecha_registro[$indice]==""){
                  $hora[$indice]     = $this->_hoy;
                 $accion[$indice]  = "verifica";
              }
               else{
                    $hora[$indice]     = $fecha_registro[$indice];
                    $accion[$indice] = "";
               }
               //Para los intervalos
               if(!isset($intervalos[$indice])){
                    $intervalos2[$indice][1]=1;
               }
               else{
                    $intervalos2[$indice] = $intervalos[$indice];
               }
               $puntaje2 = $this->_puntaje->getPuntajeJugadorTipo($this->_jugador, $value, 2);
               if(count($puntaje2)>0){
                    $msg = 1;
               }
          }
          $namespace = New Zend_Session_Namespace('fase1');
          $namespace->partido         = $partidos;
          $namespace->intervalos      = $intervalos2;
          $namespace->puntajejuego_id = $puntajejuego_id;
          $namespace->hora            = $hora;
          $namespace->accion          = $accion;
          $this->view->msg            = $msg;
        }
    }
     public function mensaje1soloAction()
    {
        if($this->_request->isPost()) {
          $request       = $this->getRequest();
          $partidos      = $request->getPost('partido_id');
          $intervalos  = $request->getPost('intervalos');
          $puntajejuego_id = $request->getPost('puntajejuego_id');
          $fecha_registro     = $request->getPost('fecha_registro');
          $this->_save1($partidos,$intervalos,$hora,$puntajejuego_id);
        }
    }
     public function advertencia1Action()
    {
    }
    public function partidosxfaseAction(){
        if($this->_request->isPost()) {
             $request     = $this->getRequest();
             $fase            = $request->getPost('fase');
             $partidos_x_fase = $this->_partido->getPartidosFechaFase($this->_fecha_actual, $fase);
             $cantidad = count($partidos_x_fase);
             $this->_helper->json(array('resultado' => $cantidad));
        }
    }
    public function putajefase2Action()
    {
         $puntaje = $this->_puntaje->getPuntajeTipo($fecha_id, $jugador_id, 2);
         

    }
    public function guardar1Action(){
           $namespace1 = New Zend_Session_Namespace('fase1');
           $partidos          = $namespace1->partido ;
           $intervalos      = $namespace1->intervalos;
           $hora                 = $namespace1->hora;
           $accion             = $namespace1->accion;
           $puntajejuego_id = $namespace1->puntajejuego_id;
           /***********************************************/
           $partidos2  = $this->_partido->getPartidoFase2($this->_fecha_actual,$this->_jugador);
           foreach($accion as $indice=>$value){
                $puntajejuego_id2 = $partidos2[$indice]->puntajejuego_id;
               if($value=="verifica")        $this->_puntaje->updatePuntaje($puntajejuego_id2,5,5);
           }
           if(isset($namespace1->partido)){
               $resultado = $this->_save1($partidos,$intervalos,$hora,$puntajejuego_id);
               Zend_Session::namespaceUnset('fase1');
               Zend_Session::namespaceUnset('fase2');
               Zend_Session::namespaceUnset('fase3');
               $this->_helper->json($resultado);
           }
     }
    public function fase2Action()
    {
         $partidos  = $this->_partido->getPartidoFase2($this->_fecha_actual,$this->_jugador);
         //Muestro todo lo de la session2
         $namespace2 = New Zend_Session_Namespace('fase2');
         $partidos2  = $namespace2->partido;
         $hora2      = $namespace2->hora;
         $puntajejuego_id2 = $namespace2->puntajejuego_id;
         $intervalos2      = $namespace2->intervalos;
          $partidos2_       = array();
          if(isset($partidos2)){
              foreach($partidos as $indice2=>$value2){
                   $partido_id    = $value2->id;
                   $intervalos    = $value2->intervalos;
                   $partido_nuevo[$indice2] = $value2;
                   foreach($partidos2 as $i => $partido_id2){
                       if($partido_id == $partido_id2){
                           foreach($intervalos2[$i] as $intervalo_id2=>$variable_id2){}
                           foreach($intervalos as $indice4=>$value4){
                             if($value4['intervalo_id']==$intervalo_id2 && $value4['variable_id']==$variable_id2){
                                  $partido_nuevo[$indice2]->intervalos[$indice4]['checked']=true;break;
                             }
                           }
                           break;
                       }
                   }
                   
              }
              $partidos = $partido_nuevo; 
          }
          //Dehabilito todo
          foreach($partidos as $value){
               $partido_id = $value->id;
               $intervalos = $value->intervalos;
               foreach($intervalos as $indice2=>$value2){
                    if($indice2>0){
                         $intervalo_id = $value2['intervalo_id'];
                         $variable_id  = $value2['variable_id'];
                         $disabled[$partido_id][$variable_id] = "disabled='disabled';";
                    }
               }
          }
          //Habilito lo de la sesion1
          $namespace1       = New Zend_Session_Namespace('fase1');
          $partidos1        = $namespace1->partido ;
          $hora1            = $namespace1->hora;
          $puntajejuego_id1 = $namespace1->puntajejuego_id;
          $intervalos1      = $namespace1->intervalos;
           if(count($intervalos1)>0){
             $partidos_new = array();
             foreach($intervalos1 as $indice2 => $value2){
                  foreach($value2 as $indice3=>$value3){}
                  if($indice3!=1 && $value3!=1){
                       //if(isset($partidos[$indice2])){
                           foreach($partidos as $jj=>$value){
                               if($value->id==$partidos1[$indice2]){
                                   $partidos_new[] = $partidos[$jj];
                                   break;
                               }
                           }
                           $partido_id1            = $partidos1[$indice2];
                           $datos_partido          = $this->_partido->getPartido($partido_id1);
                           $nombre_local           = $datos_partido['nombre_local'] ;
                           $nombre_visitante       = $datos_partido['nombre_visitante'];
                           $icono_local            = $datos_partido['icono_local'];
                           $icono_visitante        = $datos_partido['icono_visitante'];
                           $flagFase2              = $datos_partido['fase2'];
                           if($flagFase2!=0){
                               foreach($value2 as $indice3=>$value3){
                                    switch($value3){
                                        case 1:$variable_id = 5;$arrSituacion[$partido_id1]=0;$arrNomVar[$partido_id1]=" ";$arrNombreVar[$partido_id1]="";$arrIconoVar[$partido_id1]="";break;
                                        case 2:$variable_id = 6;$arrSituacion[$partido_id1]=1;$arrNomVar[$partido_id1]="Gana  ";$arrNombreVar[$partido_id1]=$nombre_local;$arrIconoVar[$partido_id1]=$icono_local;break;
                                        case 3:$variable_id = 7;$arrSituacion[$partido_id1]=2;$arrNomVar[$partido_id1]="Empatan ";$arrNombreVar[$partido_id1]="";$arrIconoVar[$partido_id1]="";break;
                                        case 4:$variable_id = 8;$arrSituacion[$partido_id1]=3;$arrNomVar[$partido_id1]="Gana ";$arrNombreVar[$partido_id1]=$nombre_visitante;$arrIconoVar[$partido_id1]=$icono_visitante;break;
                                    }
                               }
                               $disabled[$partido_id1][$variable_id] = "";
                           }
                       //}
                  }
             }
             $partidos = $partidos_new;
             //Cambio situacion de los partidos.
             $partidos2 = array();
             foreach($partidos as $indice4=>$value4){
                 $partido_id = $value4->id;
                 if(isset($arrSituacion[$partido_id])){
                      $partidos2[$indice4]=$value4;
                      $partidos2[$indice4]->situacion       = $arrSituacion[$partido_id];
                      $partidos2[$indice4]->nom_variable    = $arrNomVar[$partido_id];
                      $partidos2[$indice4]->nombre_variable = $arrNombreVar[$partido_id];
                      $partidos2[$indice4]->icono_variable  = $arrIconoVar[$partido_id];
                 }
                 else{
                      if($value4->situacion!=0){
                         $partidos2[$indice4]=$value4;
                      }
                 }
             }
             $partidos = $partidos2;
               $this->view->disabled      = $disabled;
               $this->view->variables     = $this->_variable->getVariableTipo(1);
               $this->view->puntuacion    = $this->_intervalo->getIntervaloTipo(2);
               $this->view->fecha_actual  = $this->_fecha->getFecha($this->_fecha_actual);
               $this->view->partidos      = $partidos;
          }
          else{
               $this->_redirect('/juego/fase1');
          }
    }
    public function mensaje2Action()
    {
           if($this->_request->isPost()) {
               //$partidos         = $this->_partido->getPartidosFechaFase($this->_fecha_actual, 2);
               $request          = $this->getRequest();
               $partido          = $request->getPost('partido_id');
               $intervalos       = $request->getPost('intervalos');
               $puntajejuego_id  = $request->getPost('puntajejuego_id');
               $fecha_registro   = $request->getPost('hora');
               $intervalos2      = array();
               $partido2         = array();
               $puntajejuego_id2=array();
               $fecha_registro2       = array();
               foreach($partido as $indice=>$val){
                    $partido2[$indice] = $val;
                    //Intervalos
                    if(!isset($intervalos[$indice]))
                         $intervalos2 [$indice][5]=5;
                    else
                         $intervalos2[$indice] = $intervalos[$indice];
                    //Puntaje juego.
                    if(!isset($puntajejuego_id[$indice]))
                         $puntajejuego_id2[$indice]="";
                    else
                         $puntajejuego_id2[$indice]=$puntajejuego_id[$indice];
                    //Hoa
                    if(!isset($fecha_registro[$indice]))
                         $fecha_registro2[$indice] =  $this->_hoy;
                    else
                         $fecha_registro2[$indice] =$fecha_registro[$indice];
               }
               $namespace2        = New Zend_Session_Namespace('fase2');
               $namespace2->partido                     = $partido2;
               $namespace2->intervalos               = $intervalos2;
               $namespace2->puntajejuego_id = $puntajejuego_id2;
               $namespace2->hora                          = $fecha_registro2;
           }
    }
    public function mensaje2soloAction()
    {

    }
    public function guardar2Action(){
       $namespace1 = New Zend_Session_Namespace('fase1');
       $namespace2 = New Zend_Session_Namespace('fase2');
       //Para la fase1
       $partidos        = $namespace1->partido ;
       $intervalos     = $namespace1->intervalos;
       $hora                = $namespace1->hora;
       $puntajejuego_id = $namespace1->puntajejuego_id;
        //Para la fase2
       $partidos2     = $namespace2->partido;
       $intervalos2 = $namespace2->intervalos;
       $hora2            = $namespace2->hora;
       $puntajejuego_id2 = $namespace2->puntajejuego_id;
       $this->_puntaje->deletePuntajejuego($this->_fecha_actual,$this->_jugador,2);
       if(isset($namespace1->partido)) $this->_save1($partidos,$intervalos,$hora,$puntajejuego_id);
       if(isset($namespace2->partido)) $this->_save2($partidos2,$intervalos2,$hora2,$puntajejuego_id2);
    }
    public function cerrar2Action()
    {
      $namespace1 = New Zend_Session_Namespace('fase1');
      $namespace2 = New Zend_Session_Namespace('fase2');
      $namespace3 = New Zend_Session_Namespace('fase3');
      Zend_Session::namespaceUnset('fase3');
      //Para la fase1
      $partidos   = $namespace1->partido ;
      $intervalos = $namespace1->intervalos;
      $hora       = $namespace1->hora;
      $puntajejuego_id = $namespace1->puntajejuego_id;
       //Para la fase2
      $partidos2   = $namespace2->partido;
      $intervalos2 = $namespace2->intervalos;
      $hora2       = $namespace2->hora;
      $puntajejuego_id2 = $namespace2->puntajejuego_id;
      if(isset($namespace1->partido)) $this->_save1($partidos,$intervalos,$hora,$puntajejuego_id);
      if(isset($namespace2->partido)) $this->_save2($partidos2,$intervalos2,$hora2,$puntajejuego_id2);
      $this->_redirect('/juego');
    }
    public function fase3Action()
    {
    	$path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'juegomensaje.js');
        $partidos     = $this->_partido->getPartidoFase3($this->_fecha_actual,$this->_jugador);
          $namespace3 = New Zend_Session_Namespace('fase3');
          $fila0 = $namespace3->p0;
          $fila1 = $namespace3->p1;
          $fila2 = $namespace3->p2;
          $fila3 = $namespace3->p3;
          $fila4 = $namespace3->p4;
          $fila5 = $namespace3->p5;
          $fila6 = $namespace3->p6;
          $fila7 = $namespace3->p7;
          $fila_session = array($fila0,$fila1,$fila2,$fila3,$fila4,$fila5,$fila6,$fila7);
          foreach($partidos as $indice=>$value){
           $partido_id = $value->id;
           if(!isset($fila_session[$indice])){
            //Si tiene puntaje tipo 3 y 4, entonces grabo en la session
            $intervalos   = $value->intervalos;
            $tipo         = "";
            $variable_id  = array();
            $intervalo_id = array();
            $puntajejuego_id = array();
            $hota                = "";
            foreach($intervalos as $indice2=>$value2){
                 if($value2['checked']){
                   $tipo           = $value2['tipo'];
                   $variable_id[]  = $value2['variable_id'];
                   $intervalo_id[] = $value2['intervalo_id'];
                 }
            }
            if($tipo==3 || $tipo==4){
                 $varp="p".$indice;
                 $namespace3->$varp = array(
                                          'partido_id' =>$partido_id,
                                          'variables'    =>$variable_id,
                                          'intervalos' =>$intervalo_id,
                                          'tipo'             =>$tipo,
                                          'puntajejuego' =>array(),
                                          'hora'            =>''
                                         );
            }
           }
          }
          //Habilito lo de la session2
          $namespace2        = New Zend_Session_Namespace('fase2');
          $partidos2         = $namespace2->partido;
          $hora2            = $namespace2->hora;
          $puntajejuego_id2 = $namespace2->puntajejuego_id;
          $intervalos2      = $namespace2->intervalos;
          $partidos2_       = array();
          if(count($intervalos2)>0){
               foreach($partidos as $indice=>$value){
                $partido_id = $value->id;
                foreach($partidos2 as $indice2=>$partido_id2){
                    if($partido_id==$partido_id2){
                       foreach($intervalos2[$indice2] as $variable_id=>$intervalo_id){}
                         if($intervalo_id!=5 && $variable_id!=5 ){
                          $partidos2_[$indice] = $value;
                         }
                    }
                }
                
//                if(isset($intervalos2[$indice])){
//                 foreach($intervalos2[$indice] as $indice3=>$value3){
//                  $variable_id   = $indice3;
//                  $intervalo_id = $value3;
//                  break;
//                 }
//                 if($intervalo_id!=5 && $variable_id!=5 ){
//                  $partido_id = $value->id;
//                  $partidos2_[$indice] = $value;
//                 }
//                }
              }
              $partidos = $partidos2_;             
              //Recupero la session.
              $fila0 = $namespace3->p0;
              $fila1 = $namespace3->p1;
              $fila2 = $namespace3->p2;
              $fila3 = $namespace3->p3;
              $fila4 = $namespace3->p4;
              $fila5 = $namespace3->p5;
              $fila6 = $namespace3->p6;
              $fila7 = $namespace3->p7;
              $fila_session = array($fila0,$fila1,$fila2,$fila3,$fila4,$fila5,$fila6,$fila7);
              if(is_array($fila0) || is_array($fila1) || is_array($fila2) || is_array($fila3) || is_array($fila4) || is_array($fila5) || is_array($fila6) || is_array($fila7)){
                   foreach($fila_session as $indice0=>$value0){
                       $partido_id   = $value0['partido_id'];
                       $variables    = $value0['variables'];
                       $intervalos   = $value0['intervalos'];
                       $tipo         = $value0['tipo'];
                       $puntajejuego = $value0['puntajejuego'];
                       $hora         = $value0['hora'];
                       $datos_session[$partido_id] = array($variables,$intervalos,$tipo,$puntajejuego,$hora);
                   }
                   //Mostrando la session3
                    foreach($partidos as $indice=>$value){
                       $fila                = $fila_session[$indice];
                       $partido_id = $value->id;
                       $intervalos = $value->intervalos;
                       $califica       = "";
                       if(isset($datos_session[$partido_id])){
                           foreach($intervalos as $indice2=>$value2){
                               $intervalo_id = $value2['intervalo_id'];
                               $variable_id   = $value2['variable_id'];
                               $variables2    = $datos_session[$partido_id][0];
                               $intervalos2  = $datos_session[$partido_id][1];
                               $tipo2              = $datos_session[$partido_id][2];
                               if(count($intervalos2)>0){
                                   $checked=false;
                                   foreach($intervalos2 as $indice3=>$value3){
                                       $intervalo_id2 = $value3;
                                       if($intervalo_id==$intervalo_id2){
                                           $califica    = $tipo2;
                                           $checked = true;
                                       }
                                       $intervalos[$indice2]['checked'] = $checked;
                                   }
                               }
                           }
                       }
                       $partidos[$indice]->califica        = $califica;
                       $partidos[$indice]->intervalos = $intervalos;
                   }
               }
              $this->view->filas    = array($fila0,$fila1,$fila2,$fila3,$fila4,$fila5,$fila6,$fila7);
              $this->view->fecha    = $this->_fecha->getFecha($this->_fecha_actual);
              $this->view->partidos = $partidos;
          }
          else{
               $this->_redirect('/juego/fase1');
          }
    }
    public function fase3popAction(){
         //Leo resultados de la session
          $request     = $this->getRequest();
          $partido_id  = $request->getParam('partido');
          $tipo        = $request->getParam('tipo');
          $indice      = $request->getParam('indice');
          $namespace3   = New Zend_Session_Namespace('fase3');
          $fila0 = $namespace3->p0;
          $fila1 = $namespace3->p1;
          $fila2 = $namespace3->p2;
          $fila3 = $namespace3->p3;
          $fila4 = $namespace3->p4;
          $fila5 = $namespace3->p5;
          $fila6 = $namespace3->p6;
          $fila7 = $namespace3->p7;
          $fila_session = array($fila0,$fila1,$fila2,$fila3,$fila4,$fila5,$fila6,$fila7);
          $partidos    = $this->_partido->getPartido($partido_id);
          $puntajejuego_ataque_id       = "";
          $puntajejuego_defensa_id      = "";
          $puntajejuego_fairplay_id     = "";
          $puntajejuego_puntajedepor_id = "";

          $ataque_id       = "";
          $defensa_id      = "";
          $fairplay_id     = "";
          $puntajedepor_id = "";

          $intervalo_ataque_id       = "";
          $intervalo_defensa_id      = "";
          $intervalo_fairplay_id     = "";
          $intervalo_puntajedepor_id = "";
          if(isset($fila_session[$indice])){
               $data = $fila_session[$indice];
               if($tipo==$data['tipo']){
                   if(count($data['puntajejuego'])!=0){
                         $puntajejuego = $data['puntajejuego'];
                         $puntajejuego_ataque_id       = $puntajejuego[0];
                         $puntajejuego_defensa_id     = $puntajejuego[1];
                         $puntajejuego_fairplay_id      = $puntajejuego[2];
                         $puntajejuego_puntajedepor_id = $puntajejuego[3];
                   }
                   if(count($data['variables'])!=0){
                        $variables         = $data['variables'];
                         $ataque_id      = $variables[0];
                         $defensa_id    = $variables[1];
                         $fairplay_id     = $variables[2];
                         $puntajedepor_id = $variables[3];
                   }
                   if(count($data['intervalos'])!=0){
                        $intervalos = $data['intervalos'];
                         $intervalo_ataque_id      = $intervalos[0];
                         $intervalo_defensa_id    = $intervalos[1];
                         $intervalo_fairplay_id     = $intervalos[2];
                         $intervalo_puntajedepor_id = $intervalos[3];
                   }
               }
          }
          $this->view->respuestas = array(
                                         'ataque'       => array('puntajejuego_id'=>$puntajejuego_ataque_id,'variable_id'=>$ataque_id,'intervalo_id'=>$intervalo_ataque_id),
                                         'defensa'      => array('puntajejuego_id'=>$puntajejuego_defensa_id,'variable_id'=>$defensa_id,'intervalo_id'=>$intervalo_defensa_id),
                                         'fairplay'     => array('puntajejuego_id'=>$puntajejuego_fairplay_id,'variable_id'=>$fairplay_id,'intervalo_id'=>$intervalo_fairplay_id),
                                         'puntajedepor' => array('puntajejuego_id'=>$puntajejuego_puntajedepor_id,'variable_id'=>$puntajedepor_id,'intervalo_id'=>$intervalo_puntajedepor_id)
                                        );
          if($tipo==3){//Calificacion a equipo Local
              $nombre_equipo  = $partidos['nombre_local'];
              $this->view->icono = $partidos['icono_local'];
              $this->view->ataque   = $this->_intervalo->getIntervaloVariable(10);
              $this->view->defensa = $this->_intervalo->getIntervaloVariable(11);
              $this->view->fairplay  = $this->_intervalo->getIntervaloVariable(12);
               $this->view->puntajedepor  = $this->_intervalo->getIntervaloVariable(13);
          }
          elseif($tipo==4){//Calificacion a equipo visitante.
              $nombre_equipo = $partidos['nombre_visitante'];
              $this->view->icono = $partidos['icono_visitante'];
              $this->view->ataque   = $this->_intervalo->getIntervaloVariable(15);
              $this->view->defensa = $this->_intervalo->getIntervaloVariable(16);
              $this->view->fairplay  = $this->_intervalo->getIntervaloVariable(17);
               $this->view->puntajedepor  = $this->_intervalo->getIntervaloVariable(18);
          }
         $this->view->nombre_equipo = $nombre_equipo;
         $this->view->partido_id    = $partido_id;
         $this->view->tipo          = $tipo;
         $this->view->indice        = $indice;

    }
    public function guardar3Action(){
         if($this->_request->isPost()){
               $request    = $this->getRequest();
               $variables  = $request->getPost('variable');
               $intervalos = $request->getPost('intervalo');
               $tipo       = $request->getPost('tipo');//3 o 4 ( local o visita)
               $partido_id   = $request->getPost('partido_id');
               $puntajejuego = $request->getPost('puntajejuego_id');
               $indice       = $request->getPost('indice');
               $namespace3   = New Zend_Session_Namespace('fase3');
               $data         = array(
                                      "partido_id" => $partido_id,
                                      "variables"   => $variables,
                                      "intervalos" => $intervalos,
                                      "tipo"             => $tipo,
                                      "puntajejuego" => $puntajejuego,
                                      "hora"           => $this->_hoy
                                     );
               switch($indice){
                    case 0: $namespace3->p0 = $data;break;
                    case 1: $namespace3->p1 = $data;break;
                    case 2: $namespace3->p2 = $data;break;
                    case 3: $namespace3->p3 = $data;break;
                    case 4: $namespace3->p4 = $data;break;
                    case 5: $namespace3->p5 = $data;break;
                    case 6: $namespace3->p6 = $data;break;
                    case 7: $namespace3->p7 = $data;break;
               }
               $this->_redirect("/juego/fase3");
         }
    }
    public function guardar3bdAction()
    {
      if($this->_request->isPost()){
          $request = $this->getRequest();
          $lista_partidos = $request->getPost('partido_id');
      }
      $namespace1 = New Zend_Session_Namespace('fase1');
      $namespace2 = New Zend_Session_Namespace('fase2');
      $namespace3 = New Zend_Session_Namespace('fase3');
      //Para la fase1
      $partidos   = $namespace1->partido ;
      $intervalos = $namespace1->intervalos;
      $hora            = $namespace1->hora;
      $puntajejuego_id = $namespace1->puntajejuego_id;
       //Para la fase2
      $partidos2   = $namespace2->partido;
      $intervalos2 = $namespace2->intervalos;
      $hora2       = $namespace2->hora;
      $puntajejuego_id2 = $namespace2->puntajejuego_id;
      //Para la fase3
      $fila0 = $namespace3->p0;
      $fila1 = $namespace3->p1;
      $fila2 = $namespace3->p2;
      $fila3 = $namespace3->p3;
      $fila4 = $namespace3->p4;
      $fila5 = $namespace3->p5;
      $fila6 = $namespace3->p6;
      $fila7 = $namespace3->p7;
      $filas_session  = array($fila0,$fila1,$fila2,$fila3,$fila4,$fila5,$fila6,$fila7);
      $filas_session2 = array();
      foreach($lista_partidos as $indice=>$value){
           $filas_session2[$indice]=$filas_session[$indice];
      }
      if(isset($namespace1->partido)) $this->_save1($partidos,$intervalos,$hora,$puntajejuego_id);
      if(isset($namespace2->partido)) $this->_save2($partidos2,$intervalos2,$hora2,$puntajejuego_id2);
      $this->_save3($filas_session2);
      Zend_Session::namespaceUnset('fase1');
      Zend_Session::namespaceUnset('fase2');
      Zend_Session::namespaceUnset('fase3');
    }
    public function mensaje3Action()
    {
    }
    protected function _save1($partido,$intervalos,$hora,$puntajejuego_id){
         $db = $this->_puntaje->getAdapter();
         $db->beginTransaction();
         try{
              foreach($partido as $indice => $value){
                   $fecha_hoy     = new Zend_Date($hora[$indice]);
                   $partido_id    = $value;
                   $datos_partido = $this->_partido->getPartido($partido_id);
                    $nombre_local         = $datos_partido['nombre_local'];
                    $nombre_visitante = $datos_partido['nombre_visitante'];
                   $fecha_partido = new Zend_Date($datos_partido['fecha_partido']);
                   $intervalo = 1;$variable  = 1;
                   if(isset($intervalos[$indice])){
                       $arrIntervalo = $intervalos[$indice];
                        foreach($arrIntervalo as $index=>$value2){
                             $intervalo = $index;
                             $variable   = $value2;
                        }
                   }
                   $grabo=array();
                  $partidos_tipo1 = $this->_puntaje->getPuntajeJugadorTipo($this->_jugador,$partido_id,1);
                  if(count($partidos_tipo1)==0){
                      if($this->_partido->valida_fecha($fecha_partido, $fecha_hoy)){
                           //$grabo[] = $hora[$indice];
                           $this->_puntaje->addPuntaje($this->_jugador,$partido_id,$variable,$intervalo);
                      }
                      else{
                           $grabo[] = "$nombre_local vs  $nombre_visitante";
                          $this->_puntaje->addPuntaje($this->_jugador,$partido_id,1,1);
                      }
                  }
                  else{
                      if($this->_partido->valida_fecha($fecha_partido, $fecha_hoy)){
                          //$grabo[] = $hora[$indice];
                          $this->_puntaje->updatePuntaje($puntajejuego_id[$indice],$variable,$intervalo);
                      }
                      else{
                           $grabo[] = "$nombre_local vs  $nombre_visitante";
                          $this->_puntaje->updatePuntaje($puntajejuego_id[$indice],1,1);
                      }
                   }
              }
              $db->commit();
               return $grabo;
           }
           catch(Exception $error){
               $db->rollBack();
               echo $error->getMessage();
           }
    }
    protected function _save2($partido2,$intervalos2,$hora2,$puntajejuego_id2){
         $db = $this->_puntaje->getAdapter();
         $db->beginTransaction();
         try{
          foreach($partido2 as $indice => $partido_id){
            $intervalo = 5;
            $variable  = 5;
            if(isset($intervalos2[$indice])){
                $arrIntervalo = $intervalos2[$indice];
                foreach($arrIntervalo as $indice2=>$value2){
                    $intervalo = $indice2;
                    $variable  = $value2;
                }
            }
            $lista_partidos_tipo2 = $this->_puntaje->getPuntajeJugadorTipo($this->_jugador,$partido_id,2);
            if(count($lista_partidos_tipo2)==0){
                $this->_puntaje->addPuntaje($this->_jugador,$partido_id,$variable,$intervalo);
            }
            else{
                $this->_puntaje->updatePuntaje($puntajejuego_id2[$indice],$variable,$intervalo);
            }
          }
          $db->commit();
         }
         catch(Exception $error){
              $db->rollBack();
              echo $error->getMessage();
         }
    }
    protected function _elimina_fase($partidos,$fase){//Solo fase 2 o 3
         foreach($partidos as $partido_id){
                $lista_partidos_tipo2=$this->_puntaje->getPuntajeJugadorTipo($this->_jugador,$partido_id,2);
                $lista_partidos_tipo3=$this->_puntaje->getPuntajeJugadorTipo($this->_jugador,$partido_id,3);
                $lista_partidos_tipo4=$this->_puntaje->getPuntajeJugadorTipo($this->_jugador,$partido_id,4);
                if((count($lista_partidos_tipo2))>0){
                     foreach($lista_partidos_tipo2 as $lista){
                         $this->_puntaje->deletePuntaje($lista['puntajejuego_id']);
                     }
                }
                if($fase==3){
                     if((count($lista_partidos_tipo3))>0){
                          foreach($lista_partidos_tipo3 as $lista){
                              $this->_puntaje->deletePuntaje($lista['puntajejuego_id']);
                          }
                     }
                     if((count($lista_partidos_tipo4))>0){
                          foreach($lista_partidos_tipo4 as $lista){
                              $this->_puntaje->deletePuntaje($lista['puntajejuego_id']);
                          }
                     }
                }
         }
    }
      protected function  _save3($filas_session){
          if(count($filas_session)>0){
                $this->_puntaje->deletePuntajejuego($this->_fecha_actual, $this->_jugador, 3);
                $this->_puntaje->deletePuntajejuego($this->_fecha_actual, $this->_jugador, 4);
                foreach($filas_session as $indice=>$value){
                    if(count($value)>0){
                               $partido_id = $value['partido_id'];
                               $tipo              = $value['tipo'];
                               $variables    = $value['variables'];
                               $intervalos  = $value['intervalos'];
                               $hora             = $value['hora'];
                               $puntajejuego = $value['puntajejuego'];
                               $lista_partidos  = $this->_puntaje->getPuntajeJugadorTipoFase3($this->_jugador,$partido_id);
                               if(count($lista_partidos)==0){
                                    $accion ='grabar';
                               }
                               else{
                                    $accion = 'modificar';
                               }
                               $bd = $this->_puntaje->getAdapter();
                               $bd->beginTransaction();
                               try{
                                   foreach($variables as $indice=>$variable){
                                        $variable_id     = $variables[$indice];
                                        $intervalo_id    = $intervalos[$indice];
                                        $puntajejuego_id = $puntajejuego[$indice];
                                        if($accion=='grabar'){
                                             $this->_puntaje->addPuntaje($this->_jugador,$partido_id,$variable_id,$intervalo_id);
                                        }
                                        elseif($accion=='modificar'){
                                             $this->_puntaje->updatePuntaje($puntajejuego_id,$variable_id,$intervalo_id);
                                        }
                                   }
                                   $bd->commit();
                               }
                               catch(Exception $error){
                                    $bd->rollBack();
                                    echo $error->getMessage();
                               }
                      }
                }
          }
     }
}
?>