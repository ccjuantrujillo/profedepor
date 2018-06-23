<?php
class Panel_VariableController extends ZF_Controller_Maestro
{

    private $_variable = null,
            $_intervalo = null,
            $_tipoVariable = null,
            $_tipoDefaultVariableId = 1;

    public function init()
    {
        $this->_variable = new Panel_Model_Variable();
        $this->_intervalo = new Panel_Model_Intervalo();
        $this->_tipoVariable = new Panel_Model_TipoVariable();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/variable.js');
    }

    public function indexAction()
    {
        $tipovariable_id = $this->_request->getQuery('tipovariable_id', $this->_tipoDefaultVariableId);
        $tipo = new Zend_Form_Element_Select('tipovariable_id', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper', 'Label'),
            'label' => 'Tipo Variable:',
            'multiOptions' => $this->_tipoVariable->listarOpciones(),
            'value' => $tipovariable_id
        ));

        $filter = new stdClass();
        $filter->tipovariable_id = $tipovariable_id;
        $variables = $this->_variable->listar($filter);
        $title     = "Lista de Variables";

        $spec = array('variables' => $variables, 'tipo' => $tipo,'title'=>$title);
        $this->view->assign($spec);
    }

    public function registroAction()
    {
        $variable_id = $this->_request->getQuery('id', 0);
        $form = new Panel_Form_Variable($this->_tipoVariable);
        $request = $this->getRequest();

        $form_title = 'Ingreso';
        if($variable_id > 0){
            $variable = $this->_variable->getVariable($variable_id);
            $variable->accion = 'UPD';
            $nom = $this->_tipoVariable->getTipoVariable($variable->tipovariable_id);
            $variable->txt_tipovariable_id = $nom->descripcion;
            $form->populate((array)$variable);
            $form_title = 'Edici&oacute;n';
        }

        $form_title .= ' de Variable';

        if($request->isPost()){
            $formValues = $request->getPost();
            if($form->isValid($formValues)){
                $this->_variable->save($formValues);
                $this->_redirect('/panel/variable?tipovariable_id='.$formValues['tipovariable_id']);
            } else {
                # $formValues = $form->getValues();
                $form->populate($formValues);
            }
        }

        $spec = array('form' => $form, 'form_title' => $form_title);
        $this->view->assign($spec);
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $variable_id = $request->getPost('variable_id');            
            # validar si esta asociado con un intervalo
            $cantidad = $this->_intervalo->existeVariable($variable_id);
            if($cantidad == 0){
                $this->_variable->deleteVariable("variable_id = '$variable_id'");
                $msg = "OK";
            }else{
                $msg = "NO";
            }
            $this->_helper->json(array('msg' => $msg));            
        }
    }
}