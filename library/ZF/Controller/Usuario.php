<?php

class ZF_Controller_Usuario extends ZF_Controller_Main
{
    protected $_moduloId = 5;

    public function _preDispatch(){
        $this->view->modulo_id = $this->_moduloId;
       $this->view->menu = array(
           array('url' => '/panel/usuario/', 'title' => 'USUARIOS','controlador'=>'usuario'),
           array('url' => '/panel/rol/', 'title' => 'ROLES','controlador'=>'rol'),
           array('url' => '/panel/jugador/', 'title' => 'JUGADORES','controlador'=>'jugador'),
           array('url' => '/panel/config/', 'title' => 'CONFIG','controlador'=>'config')
       );
    }
}