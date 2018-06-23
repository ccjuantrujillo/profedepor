<?php

class ZF_Controller_Maestro extends ZF_Controller_Main
{
    protected $_moduloId = 1;

    public function _preDispatch(){
       $this->view->modulo_id = $this->_moduloId;
       $this->view->menu = array(
           array('url' => '/panel/torneo/', 'title' => 'CAMPEONATOS','controlador'=>'torneo'),
           array('url' => '/panel/club/', 'title' => 'CLUBES','controlador'=>'club'),
           array('url' => '/panel/equipo/', 'title' => 'EQUIPOS','controlador'=>'equipo'),
           array('url' => '/panel/fase/', 'title' => 'FASES','controlador'=>'fase'),
           array('url' => '/panel/fecha/', 'title' => 'FECHAS','controlador'=>'fecha'),
           array('url' => '/panel/partido/', 'title' => 'PARTIDOS','controlador'=>'partido'),
           array('url' => '/panel/variable/', 'title' => 'VARIABLES','controlador'=>'variable'),
           array('url' => '/panel/intervalo/', 'title' => 'INTERVALOS','controlador'=>'intervalo')
       );
    }
}