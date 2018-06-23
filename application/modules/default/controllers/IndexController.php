<?php

class IndexController extends Zend_Controller_Action
{
    private $_jugador = null;

    public function init()
    {
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_jugador = new Default_Model_Jugador();
        $this->_helper->layout->setLayout('home');
    }

    public function preDispatch()
    {
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity())
            $this->_redirect('/portada/');
    }

    public function indexAction()
    {
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'inicio.js');

        $this->view->total = $this->_jugador->cantidad_jugadores();
        $this->view->ranking = $this->_jugador->rankingMinGeneral(4);
    }
}



