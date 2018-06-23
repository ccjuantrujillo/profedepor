<?php

class Panel_RolController extends ZF_Controller_Usuario
{
    private $_rol = null;
    private $_rolmodulo  = null;
    private $_rolusuario = null;

    public function init()
    {
        $this->_rol = new Panel_Model_Rol();
        $this->_rolmodulo = new Panel_Model_RolModulo();
        $this->_rolusuario = new Panel_Model_UsuarioRol();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/rol.js');
        # print_r($this->_rol);
        # exit;
    }

    public function indexAction()
    {
        $roles = $this->_rol->listar();

        $spec = array('roles' => $roles);
        $this->view->assign($spec);
    }

    public function registroAction()
    {
        $rol_id = $this->_request->getQuery('id', 0);
        $form = new Panel_Form_Rol();
        $model_rol_modulo = new Panel_Model_RolModulo();

        $request = $this->getRequest();

        $form_title = 'Ingreso';
        $rol = null;
        if($rol_id > 0){
            $rol = $this->_rol->getRol($rol_id);

            $filter = new stdClass();
            $filter->rol_id = $rol_id;

            $rol_modulo = $model_rol_modulo->listar($filter);
            $rol->modulos = array();
            while($modulo = $rol_modulo->fetch())
                $rol->modulos[] = $modulo->modulo_id;

            $form->populate((array)$rol);
            $form_title = 'Actualizaci&oacute;n';
        }

        $form_title .= ' de Roles';

        if($request->isPost()){
            $formValues = $request->getPost();
            if($form->isValid($formValues)){
                $this->_rol->save($formValues, $model_rol_modulo);
                $this->_redirect('/panel/rol/');
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
        if($request->isPost()){
            $rol_id = $request->getPost('id');
            $filter = new stdClass();
            $filter->rol_id = $rol_id;
            $rol_usuario = $this->_rolusuario->listar($filter)->fetchAll();
            if(count($rol_usuario)==0){
                $this->_rolmodulo->deleteRolMod("rol_id = '$rol_id'");
                $this->_rol->deleteRol("rol_id = '$rol_id'");
            }
            $this->_redirect('/panel/rol/');
        }
    }
}









