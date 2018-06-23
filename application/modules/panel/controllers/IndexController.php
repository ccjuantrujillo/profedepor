<?php
class Panel_IndexController extends ZF_Controller_Maestro
{

    public function init()
     {
          $path = Zend_Registry::get('path');
          $this->view->headScript()->appendFile($path->js . 'panel/torneo.js');
     }

    public function indexAction(){
        $this->_redirect('/panel/torneo/');
    }
}