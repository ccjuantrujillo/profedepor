<?php

class Panel_TorneoController extends ZF_Controller_Maestro {

    private $_torneo = null;
    private $_partido = null;
   private $_fase        = null;
    public function init() {
        $this->_torneo = new Panel_Model_Torneo();
        $this->_partido = new Panel_Model_Partido();
        $this->_fase       = new Panel_Model_Fase();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/torneo.js');
    }

    public function indexAction() {

        $this->view->cantidad = $this->_torneo->cantidad_torneos();
        $this->view->torneos = $this->_torneo->listar_torneos();
        $this->view->title        = "Lista de Campeonatos";
    }

    public function registroAction() {
        $torneo_id = $this->_request->getQuery('torneo_id', 0);
        $form = new Panel_Form_Torneo($this->_torneo);
        $request = $this->getRequest();

        $form_title = 'Ingreso';
        if ($torneo_id > 0) {
            $torneo = $this->_torneo->obtener_torneo($torneo_id);
            $form->populate((array) $torneo);
            $form_title = 'Edici&oacute;n';
        }

        $form_title .= ' de Campeonato';

        if ($request->isPost()) {
            $formValues = $request->getPost();
            if ($form->isValid($formValues)) {
                $this->_torneo->save($formValues);
                $this->_redirect('/panel/torneo/');
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
            $torneo_id = $request->getPost('torneo_id');
            # consultar si hay partidos asociados
            $cantidad = $this->_fase->cantidadFasesTorneo($torneo_id);
            if($cantidad == 0){
                $this->_torneo->deleteTorneo("torneo_id = '$torneo_id'");
                $msg = "OK";
            }else{
                $msg = "NO";
            }
            $this->_helper->json(array('msg' => $msg));
        }
    }

}