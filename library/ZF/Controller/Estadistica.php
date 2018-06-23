<?php

class ZF_Controller_Estadistica extends ZF_Controller_Main
{
    protected $_moduloId = 2;

    public function _preDispatch(){
        $this->view->modulo_id = $this->_moduloId;
        $this->view->menu = array(
            array('url' => '/panel/resultado/', 'title' => 'RESULTADOS PARTIDO','controlador'=>'resultado'),
            array('url' => '/panel/puntaje/', 'title' => 'CALCULO DE PUNTOS','controlador'=>'puntaje')
        );
    }
}