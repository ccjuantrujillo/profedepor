<?php
class Panel_PartidoController extends ZF_Controller_Maestro
{
    private $_partido = null;
    private $_fase = null;
    private $_defaultFaseId = 1;
    private $_defaultTorneoId =1;
    public function init()
    {
        $this->_partido   = new Panel_Model_Partido();
        $this->_fase         = new Panel_Model_Fase();
        $this->_fecha      = new Panel_Model_Fecha();
        $this->_torneo   = new Panel_Model_Torneo();
        $this->_juego     = new Default_Model_Juego();
        $this->_puntaje = new Default_Model_Puntaje();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/partido.js');
        $this->_helper->Jqtransform();
        $this->_helper->DatePicker();
        $this->_helper->TimePicker();        
    }

    public function indexAction()
    {
        $fase_id       = $this->_request->getQuery('fase_id', $this->_defaultFaseId);
        $fecha_id    = $this->_request->getQuery('fecha_id', $this->_defaultFaseId);
        $torneo_id = $this->_request->getQuery('torneo_id', $this->_defaultTorneoId);
        $filter = new stdClass();
        $filter->torneo_id = $torneo_id;
        $filter->fecha_id    = $fecha_id;
         $filter->fase_id     = $fase_id;
        $torneo  = new Zend_Form_Element_Select('torneo_id', array(
            'disableLoadDefaultDecorators' => true,
            'attribs' => array('onchange' => 'filterTorneo(this);'),
            'decorators' => array('ViewHelper', 'Label'),
            'label' => 'Campeonato:',
            'multiOptions' => count($this->_torneo->listarOpciones())>0?$this->_torneo->listarOpciones():array('0'=>'Seleccione'),
            'value' => $torneo_id
        ));
        $fase = new Zend_Form_Element_Select('fase_id', array(
            'disableLoadDefaultDecorators' => true,
            'attribs' => array('onchange' => 'filterFase(this);'),
            'decorators' => array('ViewHelper', 'Label'),
            'label' => 'Fase:',
            'multiOptions' => count($this->_fase->listarOpciones($filter))>0?$this->_fase->listarOpciones($filter):array('0'=>'Seleccione'),
            'value' => $fase_id
        ));
        $fecha = new Zend_Form_Element_Select('fecha_id',array(
            'disableLoadDefaultDecorators'=>true,
            'attribs'=>array('onchange'=>'filterFecha(this);'),
            'decorators'=>array('ViewHelper','Label'),
            'label'=>'Fecha:',
            'multiOptions'=>count($this->_fecha->listarOpciones($filter))>0?$this->_fecha->listarOpciones($filter):array('0'=>'Seleccione'),
            'value'=>$fecha_id
        ));
        $partidos = $this->_partido->listar($filter);
        $title = "Lista de Partidos";
        $spec = array('partidos' => $partidos, 'torneo'=>$torneo,'fase' => $fase,'fecha'=>$fecha,'title'=>$title);
        $this->view->assign($spec);
    }

    public function selfaseAction(){
         $torneo_id = $this->_request->getPost('torneo_id');
         $filter = new stdClass();
         $filter->torneo_id = $torneo_id;
        $fase = new Zend_Form_Element_Select('fase_id', array(
            'disableLoadDefaultDecorators' => true,
            'attribs' => array('onchange' => 'filterFase(this);'),
            'decorators' => array('ViewHelper', 'Label'),
            'multiOptions' => count($this->_fase->listarOpciones($filter))>0?$this->_fase->listarOpciones($filter):array('0'=>'Seleccione')
        ));
        $this->view->fase = $fase;
    }
    public function selfechaAction(){
         $torneo_id = $this->_request->getPost('torneo_id');
         $fase_id = $this->_request->getPost('fase_id');
         $filter = new stdClass();
         $filter->torneo_id = $torneo_id;
         $filter->fase_id      = $fase_id;
        $fecha = new Zend_Form_Element_Select('fecha_id',array(
            'disableLoadDefaultDecorators'=>true,
            'attribs'=>array('onchange'=>'filterFecha(this);'),
            'decorators'=>array('ViewHelper','Label'),
            'multiOptions'=>count($this->_fecha->listarOpciones($filter))>0?$this->_fecha->listarOpciones($filter):array('0'=>'Seleccione')
        ));
        $this->view->fecha = $fecha;
    }
    public function verpartidosAction(){
         $torneo_id = $this->_request->getPost('torneo_id');
         $fase_id      = $this->_request->getPost('fase_id');
         $fecha_id   = $this->_request->getPost('fecha_id');
         $filter          = new stdClass();
         $filter->torneo_id = $torneo_id;
         $filter->fase_id = $fase_id;
         $filter->fecha_id = $fecha_id;
         $partidos = $this->_partido->listar($filter);
         $this->view->partidos = $partidos;
    }
    public function registroAction()
    {
        $partido_id = $this->_request->getParam('partido_id', 0);
        if($partido_id>0){
             $oPartido = new Default_Model_Partido();
           $partidos   = $oPartido->getPartido($partido_id);
           $torneo_id  = $partidos['torneo_id'];
           $fase_id       = $partidos['fase_id'];
           $fecha_id    = $partidos['fecha_id'];
           $arrFecha2    = explode(" ",$partidos['fecha_partido']);
           $arrFechaPartido = explode("-",$arrFecha2[0]);
           $arrHoraPartido   = explode(":",$arrFecha2[1]);
           $fecha_partido = $arrFechaPartido[2]."/".$arrFechaPartido[1]."/".$arrFechaPartido[0];
           $hora_partido  =  $arrHoraPartido[0].":".$arrHoraPartido[1];
        }
        else{
            $torneo_id    = $this->_request->getParam('torneo_id',0);
           $fase_id         = $this->_request->getParam('fase_id',0);
           $fecha_id      = $this->_request->getParam('fecha_id',0);
        }
        $filter               = new stdClass();
        $filter->torneo_id    = $torneo_id;
        $filter->fase_id         = $fase_id;
        $filter->fecha_id      = $fecha_id;
        $filter->partido_id  = $partido_id;
        $filter->fecha_partido = "";
        $filter->hora_partido   = "";
        $filter->fase1 = 1;
        $filter->fase2 = 1;
        $filter->fase3 = 1;
        $request = $this->getRequest();
        $form_title = 'Ingreso';
        if($partido_id > 0){
             $filter->fecha_partido = $fecha_partido;
             $filter->hora_partido   = $hora_partido;
             $oPartido = new Default_Model_Partido();
            $partidos  = $oPartido->getPartido($partido_id);
             $filter->fase1 = $partidos['fase1'];
             $filter->fase2 = $partidos['fase2'];
             $filter->fase3 = $partidos['fase3'];
            $partido=array('equipo_local'=>$partidos['equipo_local'],'equipo_visitante'=>$partidos['equipo_visitante']);
             $form = new Panel_Form_Partido($filter);
            $form->populate($partido);
            $form_title = 'Actualizaci&oacute;n';
        }
        else{
          $form = new Panel_Form_Partido($filter);
        }

        $form_title .= ' de Partidos';

        if($request->isPost()){
            $formValues = $request->getPost();
            if($form->isValid($formValues)){
                $this->_partido->save($formValues);
                $this->_redirect('/panel/partido?torneo_id='.$torneo_id.'&fase_id='.$fase_id.'&fecha_id='.$fecha_id);
            } else {
                $form->populate($formValues);
            }
        }

        $spec = array('form' => $form, 'form_title' => $form_title);
        $this->view->assign($spec);
    }

    public function deleteAction()
    {
         $request = $this->getRequest();
            $partido_id = $request->getPost('partido_id');
            $filter = new stdClass();
            $filter->partido_id = $partido_id;
            if(count($this->_puntaje->getPuntaje($filter))==0){
                 $resultado = true;
                 $this->_juego->deleteJuego("partido_id = '$partido_id'");
                 $this->_partido->deletePartido("partido_id = '$partido_id'");
            }
          else {
               $resultado = false;
          }
            $this->_helper->json($resultado);
    }
    public function verpronosticoAction()
    {
         $request = $this->getRequest();
         $partido_id = $request->getPost('partido_id');
         $filter = new stdClass();
         $filter->partido_id = $partido_id;
         if(count($this->_puntaje->getPuntaje($filter))==0){
               $resultado = true;
         }
         else {
              $resultado = false;
         }
         $this->_helper->json($resultado);
    }
}