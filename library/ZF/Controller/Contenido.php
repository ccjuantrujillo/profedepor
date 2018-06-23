<?php

class ZF_Controller_Contenido extends ZF_Controller_Main
{
    public function preDispatch(){
       $this->view->menu = array(
           array('url' => '/panel/articulo/index', 'title' => 'INGRESAR'),
           array('url' => '/panel/articulo/editar', 'title' => 'EDITAR')
       );
    }
}

