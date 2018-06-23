<?php

class Panel_UsuarioController extends ZF_Controller_Usuario
{
    private $_usuario = null;

    public function init()
    {
        $this->_usuario = new Panel_Model_Usuario();
        $this->_usuariorol = new Panel_Model_UsuarioRol();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/usuario.js');
    }

    public function indexAction()
    {
        $usuarios = $this->_usuario->listar();

        $spec = array('usuarios' => $usuarios);
        $this->view->assign($spec);
    }

    public function registroAction()
    {
        $usuario_id = $this->_request->getQuery('id', 0);
        $isUpdate = ($usuario_id > 0);
        $form = new Panel_Form_Usuario();
        $model_usuario_rol = new Panel_Model_UsuarioRol();

        $request = $this->getRequest();

        $form_title = 'Ingreso';
        $usuario = null;
        if($isUpdate){
            $usuario = $this->_usuario->getUsuario($usuario_id);

            $filter = new stdClass();
            $filter->usuario_id = $usuario_id;

            $usuario_rol = $model_usuario_rol->listar($filter);
            $usuario->roles = array();
            while($rol = $usuario_rol->fetch())
                $usuario->roles[] = $rol->rol_id;

            $form->populate((array)$usuario);
            $form_title = 'Actualizaci&oacute;n';
        }

        $form_title .= ' de Usuarios';

        if($request->isPost()){
            $formValues = $request->getPost();
            $form->post($isUpdate, $this->_usuario, $usuario, $formValues);
            if($form->isValid($formValues)){
                $this->_usuario->save($formValues, $model_usuario_rol);
                $this->_redirect('/panel/usuario/');
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
            $usuario_id = $request->getPost('id');
            $usuario_rol = new Panel_Model_UsuarioRol();
            $this->_usuariorol->deleteUsuarioRol("usuario_id = '$usuario_id'");
            $this->_usuario->deleteUsuario("usuario_id = '$usuario_id'");
            $this->_redirect('/panel/usuario/');
        }
    }
}









