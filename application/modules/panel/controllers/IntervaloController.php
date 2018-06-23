<?php
class Panel_IntervaloController extends ZF_Controller_Maestro {
    private $_variable = null,
    $_intervalo = null,
    $_tipoDefaultVariableId = 1,
    $_fecha = null,
    $_fecha_actual = null;

    public function init() {
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/intervalo.js');
        $this->_tipoVariable = new Panel_Model_TipoVariable();
        $this->_variable = new Panel_Model_Variable();
        $this->_intervalo = new Panel_Model_Intervalo();
        $this->_fecha = new Panel_Model_Fecha();
        $this->_juego = new Panel_Model_Juego();
        $this->_fecha_actual = $this->_fecha->getFechaHoy();
    }

    public function indexAction() {
        $tipovariable_id = $this->_request->getQuery('tipovariable_id', $this->_tipoDefaultVariableId);
        $filter = new stdClass();
        $filter->tipovariable_id = $tipovariable_id;
        $variableDefault = $this->_variable->getVariableDefault($tipovariable_id);
        $variable_id = $this->_request->getQuery('variable_id', $variableDefault->variable_id);
        $tipo = new Zend_Form_Element_Select('tipovariable_id', array(
                    'disableLoadDefaultDecorators' => true,
                    'attribs' => array('onchange' => "location.href='/panel/intervalo?tipovariable_id='+this.value;"),
                    'decorators' => array('ViewHelper', 'Label'),
                    'label' => 'Tipo Variable:',
                    'multiOptions' => $this->_tipoVariable->listarOpciones(),
                    'value' => $tipovariable_id
                ));
        $variable = new Zend_Form_Element_Select('variable_id', array(
                    'disableLoadDefaultDecorators' => true,
                    'attribs' => array('onchange' => "tipo=$('#tipovariable_id').val();location.href='/panel/intervalo?tipovariable_id='+tipo+'&variable_id='+this.value;"),
                    'decorators' => array('ViewHelper', 'Label'),
                    'label' => 'Variable:',
                    'multiOptions' => $this->_variable->listarOpciones($filter),
                    'value' => $variable_id
                ));

        $filter = new stdClass();
        $filter->variable_id = $variable_id;
        $intervalos = $this->_intervalo->listar($filter);
        $title = "Lista de Intervalos";

        $spec = array('intervalos' => $intervalos, 'variable' => $variable, 'tipo' => $tipo, "title" => $title);
        $this->view->assign($spec);
    }

    public function selvariableAction() {
        $tipovariable_id = $this->_request->getPost('tipovariable_id');
        $filter = new stdClass();
        $filter->tipovariable_id = $tipovariable_id;
        $variable = new Zend_Form_Element_Select('variable_id', array(
                    'disableLoadDefaultDecorators' => true,
                    'attribs' => array('onchange' => "tipo=$('#tipovariable_id').val();location.href='/panel/intervalo?tipovariable_id='+tipo+'&variable_id='+this.value;"),
                    'decorators' => array('ViewHelper', 'Label'),
                    'label' => 'Filtrar x Variable:',
                    'multiOptions' => $this->_variable->listarOpciones($filter),
                ));
        $this->view->variable = $variable;
    }

    public function registroAction() {
        $intervalo_id = $this->_request->getQuery('id', 0);
        $form = new Panel_Form_Intervalo($this->_variable);
        $request = $this->getRequest();
        $form_title = 'Ingreso';
        if ($intervalo_id > 0) {
            $intervalo = $this->_intervalo->getIntervalo($intervalo_id);
            $tipovariable_id = $this->_variable->getVariable($intervalo->variable_id);
            $datos = new stdClass();
            $datos->intervalo_id = $intervalo->intervalo_id;
            $datos->variable_id = $intervalo->variable_id;
            $datos->descripcion = $intervalo->descripcion;
            $datos->descripcion2 = $intervalo->descripcion2;
            $datos->valori = $intervalo->valori;
            $datos->valorf = $intervalo->valorf;
            $datos->puntaje = $intervalo->puntaje;
            $datos->tipovariable_id = $tipovariable_id->tipovariable_id;
            $datos->accion = 'UPD';
            $nom_tipovariable = $this->_tipoVariable->getTipoVariable($tipovariable_id->tipovariable_id);
            $nom_variable = $this->_variable->getVariable($intervalo->variable_id);
            $datos->txt_tipovariable_id = $nom_tipovariable->descripcion;
            $datos->txt_variable_id = $nom_variable->descripcion;
            $form->populate((array) $datos);
            $form_title = 'Edici&oacute;n';
        }

        $form_title .= ' de Intervalo';

        if ($request->isPost()) {
            $formValues = $request->getPost();
            if ($form->isValid($formValues)) {
                $intervalo_id = $formValues['intervalo_id'];
                $puntaje = $formValues['puntaje'];
                if ($intervalo_id > 0) {
                    $this->_juego->actualizar_juego($this->_fecha_actual, $variable_id, $puntaje);
                }
                $this->_intervalo->save($formValues);
                $this->_redirect('/panel/intervalo?tipovariable_id=' . $formValues['tipovariable_id'] . '&variable_id=' . $formValues['variable_id']);
            } else {
                # $formValues = $form->getValues();
                $form->populate($formValues);
            }
        }

        $spec = array('form' => $form, 'form_title' => $form_title);
        $this->view->assign($spec);
    }

    public function deleteAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $intervalo_id = $request->getPost('id');            
            # validar si esta asociado con un intervalo
            $cantidad = $this->_juego->existeIntervalo($intervalo_id);
            if($cantidad == 0){
                $this->_intervalo->delete("intervalo_id = '$intervalo_id'");
                $msg = "OK";
            }else{
                $msg = "NO";
            }
            $this->_helper->json(array('msg' => $msg));
        }
    }

}