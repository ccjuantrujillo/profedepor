<?php

class Panel_NoticiaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        # Zend_Layout::getMvcInstance()->disableLayout();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'tiny_mce/jquery.tinymce.js')
            ->appendFile($path->js . 'tiny_mce/tiny_mce.js')
            ->appendFile($path->js . 'jquery.tinymce.js');

        /*
		$navContainerConfig = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation_grupo.xml', 'nav');
		$navContainer = new Zend_Navigation($navContainerConfig);
        $this->view->navigation($navContainer);
        */
    }


}

