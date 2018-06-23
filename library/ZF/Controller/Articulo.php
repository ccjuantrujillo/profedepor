<?php

class ZF_Controller_Articulo extends ZF_Controller_Main
{
    protected $_moduloId = 3;

    public function _preDispatch(){
        $this->view->modulo_id = $this->_moduloId;
    	$this->view->menu = array(
           array('url' => '/panel/articulo/index/tipo=1', 'title' => 'NOTICIAS DEPOR'),
           array('url' => '/panel/articulo/index/tipo=2', 'title' => 'EL PROFE OPINA'),
           array('url' => '/panel/articulo/index/tipo=3', 'title' => 'EL CUY YIMI')
       );
    }
}

