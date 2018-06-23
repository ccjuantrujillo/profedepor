<?php

class Panel_ConfigController extends ZF_Controller_Usuario {

    private $_usuario = null;
    private $_configuracion = null;
    private $_fase = null;
    private $_torneo = null;

    public function init() {
        $this->_configuracion = new Panel_Model_Configuracion();
        $this->_usuario = new Panel_Model_Usuario();
        $this->_fase = new Panel_Model_Fase();
        $this->_torneo = new Panel_Model_Torneo();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/config.js');
    }

    public function indexAction() {         
        $configs = $this->_configuracion->listarConfigs();
        $title = 'Configuraci&oacute;n';
        $spec = array(
            'configs' => $configs,
            'title' => $title
        );
        $this->view->assign($spec);
    }

    public function editarAction() {        
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $config_id = $request->getPost('configuracion_id');
            $torneo_id = $request->getPost('torneo_id');
            $fase_id = $request->getPost('fase_id');
            $tolerancia = $request->getPost('tolerancia');            
            $data = array(
                'configuracion_id' => $config_id,
                'torneo_id' => $torneo_id,
                'fase_id' => $fase_id,
                'tolerancia' => $tolerancia
            );
            $id = $this->_configuracion->save($data);
            $this->_helper->json(array('config' => $id));
        }
        $configuracion_id = $this->_request->getQuery('id', 1);
        $torneoid = $this->_request->getQuery('torneo');
        $form_title = 'Actualizaci&oacute;n de Configuraci&oacute;n';
        $config = $this->_configuracion->obtener_config($configuracion_id);
        $torneos = $this->_torneo->listar_torneos();        
        if ($torneoid == null) {            
            $torneo_id = $config->torneo_id;            
        }  else {
            $torneo_id = $torneoid;
        }                
        $fases = $this->_fase->listarFasesTorneo($torneo_id);
        $spec = array(
            'config' => $config,
            'torneos' => $torneos,
            'fases' => $fases,
            'form_title' => $form_title);
        $this->view->assign($spec);
    }

}