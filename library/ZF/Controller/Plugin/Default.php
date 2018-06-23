<?php
class ZF_Controller_Plugin_Default extends Zend_Controller_plugin_Abstract {
    public function __construct(){
        # $writer = new Zend_Log_Writer_Firebug();
        # $logger = new Zend_Log($writer);
        # $logger->log('This is a log message!', Zend_Log::INFO);
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request){
	    #validar si esta logeado
        $controller = $request->getControllerName();
        $controllers = array('red', 'index', 'auth', 'google', 'twitter', 'facebook', 'ayuda', 'politica');

        if(in_array($controller, $controllers))
            return false;

        # verificar session
        $auth = Zend_Auth::getInstance();
        if( ! $auth->hasIdentity()){
            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirector->gotoUrlAndExit('/');
        }
    }

	public function preDispatch(Zend_Controller_Request_Abstract $request){
	   $request->setBaseurl('/');

       # application.ini
       $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');

       $paginator = (object)$config->getOption('paginator');
       $fb = (object)$config->getOption('fb');
       $twitter = (object)$config->getOption('twitter');
       $google  = (object)$config->getOption('google');

       Zend_Registry::set('paginator', $paginator);
       Zend_Registry::set('fb', $fb);
       Zend_Registry::set('twitter', $twitter);
       Zend_Registry::set('google', $google);
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request){
	    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $view = $viewRenderer->view;
        $path = Zend_Registry::get('path');

        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $session = $config->getOption('session');

        ## issue attrib property meta, zf 1.11
        $view->registerHelper(new ZF_View_Helper_HeadMeta(), 'HeadMeta');

        $type_js = 'text/javascript';
        $params_png = array('conditional' => 'IE 6');

        $view->headMeta()->appendProperty('og:title', '&iexcl;Juega  El Profe Depor!')
                         ->appendProperty('og:description', 'Divi&eacute;rtete jugando con los resultados del campeonato descentralizado. Piensa como t&eacute;cnico fecha a fecha y demuestra cuanto sabes de f&uacute;tbol.')
                         ->appendProperty('og:image', 'http://' . $request->getHttpHost() . $path->images . 'facebook-chico.jpg')
                         ->appendName('keywords', 'El Profe Depor')
                         ->appendName('Description', 'Descripcion El Profe Depor')
                         ->appendName('title', 'El Profe Depor');

        $view->headLink(array('rel' => 'image_src', 'href' => $path->images . 'facebook-chico.jpg'), 'PREPEND');

        $headLink = $view->headLink()->offsetSetStylesheet(-6, $path->css . 'reset.css');
        $script = $view->headScript()
        	 ->offsetSetFile(-9, $path->js . 'unitpngfix.js', $type_js, $params_png);
            //->offsetSetFile(-10, $path->js . 'DD_belatedPNG_0.0.8a-min.js', $type_js, $params_png)
            //->offsetSetFile(-9, $path->js . 'DD_extend.js', $type_js, $params_png);

        $layoutName = Zend_Layout::getMvcInstance()->getLayout();

        if($layoutName == 'home')
            $headLink->offsetSetStylesheet(-5, $path->css . 'styleHome.css');
        elseif($layoutName == 'layout'){
            $headLink->offsetSetStylesheet(-4, $path->css . 'styleProfeDeporSite.css');
            $script = $view->headScript()->offsetSetFile(-8, $path->js . 'ajaxupload.3.6.js')
                ->offsetSetScript(-7, "var expimin = {$session['expirationMinutes']}");

            # js global
            $auth = Zend_Auth::getInstance();
            if($jugador = $auth->getIdentity()){
                if($jugador->session == 1){
                    $view->headScript()->appendFile($path->js . 'first_session.js');
                    $model_jugador = new Default_Model_Jugador();
                    $model_jugador->firstSession($jugador);
                } elseif($jugador->session == 2){
                    $view->headScript()->appendFile($path->js . 'recuperar_password.js');
                }

                # mi posicion general
                $view->posicion_rank_jugador = $jugador->posicion;
                $view->nombres_jugador = $jugador->nombres;
                $view->puntaje_jugador = $jugador->puntaje;
            }
        }

        $view->headTitle(':: El Profe Depor ::');

        $headLink->offsetSetStylesheet(-3, $path->css . 'simplemodal/basic.css')
           ->offsetSetStylesheet(-2, $path->css . 'simplemodal/basic_ie.css', 'screen', 'lt IE 7')
           //->offsetSetStylesheet(-2, $path->css . 'iepngfix.css', 'screen', 'lt IE 7')
           ->offsetSetStylesheet(-1, $path->css . 'main-default.css');

        $script->offsetSetFile(-6, $path->js . 'simplemodal/jquery.simplemodal.js')
           ->offsetSetFile(-5, $path->js . 'jquery.validate.keyPress.js')
           ->offsetSetFile(-4, $path->js . 'jquery.validate.js', $type_js, array('charset' => 'iso-8859-1'))
           ->offsetSetFile(-3, $path->js . 'default_func.js');

        $controller = $request->getControllerName();
        if($controller != 'index')
           $script->offsetSetFile(-2, $path->js . 'default_main.js');

        $script->offsetSetFile(-1, $path->js . 'debug.js');

        ## hora layout
        $date = new Zend_Date();
        $view->layout_date = $date->toString("dd 'de ")
                . $date->toString("MMMM 'de' YYYY - hh:mm ")
                . str_replace('.', '', $date->toString('a')) . '.';
    }

    public function dispatchLoopShutdown(){
    }
}