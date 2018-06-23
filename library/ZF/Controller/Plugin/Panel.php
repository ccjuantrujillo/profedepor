<?php
class ZF_Controller_Plugin_Panel extends Zend_Controller_plugin_Abstract {

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request){
        $auth = Zend_Auth::getInstance();
        $controllerName = $request->getControllerName();

	    #validar si esta logeado
        $controllers = array();

        if(in_array($controllerName, $controllers))
            return false;

        # verificar session
        if( ! $auth->hasIdentity()){
            $request->setControllerName('login')
                ->setActionName('index');
        }
    }

	public function preDispatch(Zend_Controller_Request_Abstract $request){
	   $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
       $view = $viewRenderer->view;

       $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
       $paginator = (object)$config->getOption('paginator');
       Zend_Registry::set('paginator', $paginator);
       
       $path = Zend_Registry::get('path');       
       
       $view->headTitle('Administrador :: El Profe Depor');
       $view->headLink()->appendStylesheet($path->css . 'main-panel.css');

       $controllerName = $request->getControllerName();
       $controllers = array('login', 'index');

       if( ! in_array($controllerName, $controllers))
           $view->headScript()->appendFile($path->js . 'panel/main.js');
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request){
    }

    public function dispatchLoopShutdown(){
    }
}