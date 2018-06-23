<?php
class Panel_FaseController extends ZF_Controller_Maestro {
    private $_fase = null;
    private $_fecha = null;
    private $_torneo = null;
    private $_partido = null;
    private $_tipoDefaultTorneoId = 1;

    public function init() {
        $this->_fecha = new Panel_Model_Fecha();
        $this->_fase = new Panel_Model_Fase();
        $this->_torneo = new Panel_Model_Torneo();
        $this->_partido = new Panel_Model_Partido();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/fase.js');
    }

    public function indexAction() {
        $torneo_id = $this->_request->getQuery('torneo_id', $this->_tipoDefaultTorneoId);
        $tipo = new Zend_Form_Element_Select('torneo_id', array(
            'disableLoadDefaultDecorators' => true,
            'attribs' => array('onchange' => 'filterTipo(this);'),
            'decorators' => array('ViewHelper', 'Label'),
            'label' => 'Filtrar por Torneo:',
            'multiOptions' => $this->_torneo->listarOpciones(),
            'value' => $torneo_id
        ));

        $filter = new stdClass();
        $filter->torneo_id = $torneo_id;

        $fases = $this->_fase->listarFases($filter);
        $cantidad = $this->_fase->cantidadFasesTorneo($torneo_id);
        $title          = "Lista de Fases";

        $spec = array('fases' => $fases, 'cantidad' => $cantidad, 'tipo' => $tipo,'title'=>$title);
        $this->view->assign($spec);
    }

    public function registroAction() {
        $fase_id = $this->_request->getQuery('fase_id', 0);
        $form = new Panel_Form_Fase($this->_torneo);
        $request = $this->getRequest();

        $form_title = 'Ingreso';
        if ($fase_id > 0) {
            $fase = $this->_fase->obtener_fase($fase_id);
            $fase->accion = 'UPD';
            $nom = $this->_torneo->obtener_torneo($fase->torneo_id);
            $fase->txt_torneo_id = $nom->descripcion;
            $form->populate((array) $fase);
            $form_title = 'Edici&oacute;n';
        }

        $form_title .= ' de Fase';

        if ($request->isPost()) {
            $formValues = $request->getPost();
            if ($form->isValid($formValues)) {
                $this->_fase->save($formValues);
                $this->_redirect('/panel/fase/');
            } else {
                $form->populate($formValues);
            }
        }

        $spec = array('form' => $form, 'form_title' => $form_title);
        $this->view->assign($spec);
    }
    
    public function deleteAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $fase_id = $request->getPost('fase_id');
            # consultar si hay partidos asociados
            $cantidad = $this->_fecha->existenFases($fase_id);
            if($cantidad == 0){
                $this->_fase->deleteFase("fase_id = '$fase_id'");
                $msg = "OK";
            }else{
                $msg = "NO";
            }
            $this->_helper->json(array('msg' => $msg));
        }
    }

}