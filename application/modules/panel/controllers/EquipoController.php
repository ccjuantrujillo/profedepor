<?php

class Panel_EquipoController extends ZF_Controller_Maestro {

    private $_equipo = null;
    private $_club = null;
    private $_fecha = null;
    private $_torneo = null;
    private $_partido = null;
    private $_tipoDefaultTorneoId = 1;

    public function init() {
        $this->_equipo = new Panel_Model_Equipo();
        $this->_club = new Panel_Model_Club();
        $this->_fecha = new Panel_Model_Fecha();
        $this->_torneo = new Panel_Model_Torneo();
        $this->_partido = new Panel_Model_Partido();
        $this->_config = new Panel_Model_Configuracion();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/equipo.js');
    }

    public function indexAction() {
        $torneo_id = $this->_request->getQuery('torneo_id', $this->_tipoDefaultTorneoId);
        $tipoTorneo = new Zend_Form_Element_Select('torneo_id', array(
                    'disableLoadDefaultDecorators' => true,
                    'attribs' => array('onchange' => 'filterTipoTorneo(this);'),
                    'decorators' => array('ViewHelper', 'Label'),
                    'label' => 'Filtrar por Torneo:',
                    'multiOptions' => $this->_torneo->listarOpciones(),
                    'value' => $torneo_id
                ));

        $filter = new stdClass();
        $filter->torneo_id = $torneo_id;

        $equipos = $this->_equipo->listarEquipos($filter);
        $cantidad = $this->_equipo->cantidadEquipos($torneo_id);
        $title          = "Lista de Equipos";

        $spec = array(
            'equipos' => $equipos,
            'cantidad' => $cantidad,
            'tipoTorneo' => $tipoTorneo,
            'title'               => $title
            );
        $this->view->assign($spec);
    }

    public function registroAction() {
        $equipo_id = $this->_request->getQuery('equipo_id', 0);
        $configuracion = $this->_config->getConfiguracion();
        $filter = new stdClass();
        $filter->torneo_id = $configuracion->torneo_id;
        $filter2 = new stdClass();
        $filter2->equipo_id = 0;
        $form = new Panel_Form_Equipo($this->_torneo, $this->_club,$filter,$filter2);
        $this->_helper->DatePicker();
        $request = $this->getRequest();
        $form_title = 'Ingreso';
        if ($equipo_id > 0) {
            $equipo = $this->_equipo->obtener_equipo($equipo_id);
            $equipo->accion = 'UPD';
            $nom = $this->_torneo->obtener_torneo($equipo->torneo_id);
            $equipo->txt_torneo_id = $nom->descripcion;
            $filter2->equipo_id = $equipo_id;
            $form = new Panel_Form_Equipo($this->_torneo, $this->_club,$filter,$filter2);
            $form->populate((array) $equipo);
            $form_title = 'Actualizaci&oacute;n';
        }
        $form_title .= ' de Equipo';
        if ($request->isPost()) {
            $formValues = $request->getPost();
            if ($form->isValid($formValues)) {
                $this->_equipo->save($formValues);
                $this->_redirect('/panel/equipo/');
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
            $equipo_id = $request->getPost('equipo_id');
            # consultar si hay partidos asociados
            $cantidad = $this->_partido->existenPartidosEquipo($equipo_id);
            if($cantidad == 0){
                $this->_equipo->deleteEquipo("equipo_id = '$equipo_id'");
                $msg = "OK";
            }else{
                $msg = "NO";
            }
            $this->_helper->json(array('msg' => $msg));
        }
    }

    public function selclubAction() {
        $equipo_id = 0;
        $torneo_id = $this->_request->getPost('torneo_id');
        $club = new Zend_Form_Element_Select('variable_id', array(
                    'disableLoadDefaultDecorators' => true,
                    'decorators' => array('ViewHelper', 'Label'),
                    'label' => 'Filtrar x Variable:',
                    'multiOptions' => $this->_club->listarOpcionesOut($torneo_id,$equipo_id),
                ));
        $this->view->variable = $club;
    }
}