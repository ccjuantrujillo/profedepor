<?php

class ZF_Controller_Comentario extends ZF_Controller_Main
{
    protected $_moduloId = 4;

    public function _preDispatch(){
        $this->view->modulo_id = $this->_moduloId;
        $this->view->menu = array(
           array('url' => '/panel/comentario/ver/id/1', 'title' => 'NOTICIAS DEPOR','controlador'=>'comentario1'),
           array('url' => '/panel/comentario/ver/id/2', 'title' => 'EL PROFE OPINA','controlador'=>'comentario2'),
           array('url' => '/panel/comentario/ver/id/3', 'title' => 'EL CUY YIMI','controlador'=>'comentario3')
       );
    }
}