<?php

class Panel_FechaController extends ZF_Controller_Maestro {

    private $_fecha = null;
    private $_fase = null;
    private $_torneo = null;
    private $_tipofecha = null;
    private $_partido = null;
    private $_tipoDefaultFaseId = 1;
    private $_tipoDefaultTorneoId = 1;

    public function init() {
        $this->_fecha = new Panel_Model_Fecha();
        $this->_fase = new Panel_Model_Fase();
        $this->_torneo = new Panel_Model_Torneo();
        $this->_tipofecha = new Panel_Model_TipoFecha();
        $this->_partido = new Panel_Model_Partido();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/fecha.js');
        $this->_helper->DatePicker();
    }

    public function indexAction() {
        $torneo_id = $this->_request->getQuery('torneo_id', $this->_tipoDefaultTorneoId);
        //$fase_id = $this->_request->getQuery('fase_id', $this->_tipoDefaultFaseId);

        $filter = new stdClass();
        $filter->torneo_id = $torneo_id;
        $filter->fase_id = $fase_id;

        $faseDefault = $this->_fase->getfaseDefault($torneo_id);
        $fase_id = $this->_request->getQuery('fase_id', $faseDefault->fase_id);
        $tipoTorneo = new Zend_Form_Element_Select('torneo_id', array(
                    'disableLoadDefaultDecorators' => true,
                    'attribs' => array('onchange' => 'filterTorneo(this);'),
                    'decorators' => array('ViewHelper', 'Label'),
                    'label' => 'Torneo: ',
                    'multiOptions' => count($this->_torneo->listarOpciones()) > 0 ? $this->_torneo->listarOpciones() : array('0' => 'Seleccione'),
                    'value' => $torneo_id
                ));

        $tipoFase = new Zend_Form_Element_Select('fase_id', array(
                    'disableLoadDefaultDecorators' => true,
                    'attribs' => array('onchange' => 'filterFase(this);'),
                    'decorators' => array('ViewHelper', 'Label'),
                    'label' => 'Fase: ',
                    'multiOptions' => (count($this->_fecha->listar_fases($filter))) > 0 ? $this->_fecha->listar_fases($filter) : array('0' => 'Seleccione'),
                    'value' => $fase_id
                ));

        $filter = new stdClass();
        $filter->fase_id = $fase_id;
        $fechas = $this->_fecha->listarFechas($filter);
        $cantidad = $this->_fecha->cantidadFechas($torneo_id, $fase_id);
        $title = "Lista de Fechas";

        $spec = array(
            'fechas' => $fechas,
            'cantidad' => $cantidad,
            'tipoTorneo' => $tipoTorneo,
            'tipoFase' => $tipoFase,
            'title' => $title);
        $this->view->assign($spec);
    }

    public function registroAction() {
        $fecha_id = $this->_request->getQuery('fecha_id', 0);
        $filter = new stdClass();
        if ($fecha_id > 0) {
            $fecha = $this->_fecha->obtener_fecha($fecha_id);
            $filter->fase_id = $fecha->fase_id;
            $filter->torneo_id = $fecha->torneo_id;
            $filter->tipo = $fecha->tipo;
            $filter->grupo_id = $fecha->grupo_id;
        } else {
            $torneo_id = $this->_request->getParam('torneo_id', 0);
            $fase_id = $this->_request->getParam('fase_id', 0);
        }

        $filter->fecha_id = $fecha_id;
        if (isset($torneo_id))
            $filter->torneo_id = $torneo_id;
        if (isset($fase_id))
            $filter->fase_id = $fase_id;
        $filter->fecha1 = "";
        $filter->fecha2 = "";
        $filter->intervalo = "";
        $filter->descripcion = "";
        $filter->tipo = 1;
        $filter->grupo_id = $this->_tipofecha->listarOpciones();

        $request = $this->getRequest();
        $form_title = 'Ingreso';

        if ($fecha_id > 0) {
            $fecha = $this->_fecha->obtener_fecha($fecha_id);
            $fecha->accion = 'UPD';
            $nom_torneo = $this->_torneo->obtener_torneo($fecha->torneo_id);
            $nom_fase = $this->_fase->getFase($fecha->fase_id);
            $fecha->txt_torneo_id = $nom_torneo->descripcion;
            $fecha->txt_fase_id = $nom_fase->descripcion;
            $form = new Panel_Form_Fecha($filter);
            $form->populate((array) $fecha);
            $form_title = 'Edici&oacute;n';
        } else {
            $form = new Panel_Form_Fecha($filter);
        }

        $form_title .= ' de Fecha';

        if ($request->isPost()) {
            $formValues = $request->getPost();
            if ($form->isValid($formValues)) {
                $torneo_id = $formValues['torneo_id'];
                $fase_id = $formValues['fase_id'];
                $this->_fecha->save($formValues);
                $this->_redirect('/panel/fecha?torneo_id=' . $torneo_id . '&fase_id=' . $fase_id);
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
            $fecha_id = $request->getPost('fecha_id');
            # consultar si hay partidos asociados
            $cantidad = $this->_partido->existenPartidosFecha($fecha_id);
            if ($cantidad == 0) {
                $this->_fecha->delete("fecha_id = '$fecha_id'");
                $msg = "OK";
            } else {
                $msg = "NO";
            }
            $this->_helper->json(array('msg' => $msg));
        }
    }

    public function selfaseAction() {
        $torneo_id = $this->_request->getPost('torneo_id');
        $filter = new stdClass();
        $filter->torneo_id = $torneo_id;
        $fase = new Zend_Form_Element_Select('fase_id', array(
                    'disableLoadDefaultDecorators' => true,
                    'attribs' => array('onchange' => 'filterFase(this);'),
                    'decorators' => array('ViewHelper', 'Label'),
                    'label' => 'Fase: ',
                    'multiOptions' => count($this->_fecha->listar_fases($filter)) > 0 ? $this->_fecha->listar_fases($filter) : array('0' => 'Seleccione')
                ));
        $this->view->tipoFase = $fase;
    }

    public function verfechasAction() {
        $torneo_id = $this->_request->getPost('torneo_id');
        $fase_id = $this->_request->getPost('fase_id');
        $filter = new stdClass();
        $filter->torneo_id = $torneo_id;
        $filter->fase_id = $fase_id;
        $fechas = $this->_fecha->listarFechas($filter);
        $cantidad = $this->_fecha->cantidadFechas($torneo_id, $fase_id);
        $res = array('fechas' => $fechas, 'cantidad' => $cantidad);
        $this->view->assign($res);
    }

}