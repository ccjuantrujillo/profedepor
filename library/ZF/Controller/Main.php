<?php

class ZF_Controller_Main extends Zend_Controller_Action
{
    protected $_moduloId = 0;

    final public function preDispatch(){
        $acl = new ZF_Acl_Panel();
        $identity = Zend_Auth::getInstance()->getIdentity();

        # usuario roles
        $model_usuario_rol = new Panel_Model_UsuarioRol();

        $filter = new stdClass();
        $filter->usuario_id = $identity->usuario_id;
        $usuario_roles = $model_usuario_rol->listar($filter);

        $isAllowed = false;

        while($usuario_role = $usuario_roles->fetch()){
            if($acl->isAllowed($usuario_role->rol_id, $this->_moduloId)){
                $isAllowed = true;
                break;
            }
        }

        $modulos = $model_usuario_rol->getModulos($identity->usuario_id)->fetchAll();

        if( ! $isAllowed){
            if(count($modulos) > 0){
                $controllerDefault = current($modulos);
                $controller = 'index';
                if(isset($controllerDefault->controller) && strlen($controllerDefault->controller) > 0)
                    $controller = $controllerDefault->controller;
                $this->_forward('index', $controllerDefault->controller);
            } else
                exit('no tienes acceso');
        }
        $this->view->modulos = $modulos;
        $this->view->menu = array();
        $this->_preDispatch();
    }

    public function _preDispatch(){}
}