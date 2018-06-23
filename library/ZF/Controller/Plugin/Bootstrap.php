<?php
class ZF_Controller_Plugin_Bootstrap extends Zend_Controller_plugin_Abstract {
    private $_config = null,
            $_moduleName = '';

    private function getOptions($key = null){
        if(null === $this->_config)
            $this->_config = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();

        $rt = $this->_config;

        if(null !== $key && array_key_exists($key, $this->_config))
            $rt = $rt[$key];

        return $rt;
    }

    public function routeStartup(Zend_Controller_Request_Abstract $request){
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request){
       $this->_moduleName = $request->getModuleName();
       $config = $this->getOptions('session');
       $auth = Zend_Auth::getInstance();

       $expirationSeconds = 1;
       if(isset($config['expirationMinutes']))
           $expirationSeconds = $config['expirationMinutes'] * 60;

       $auth_storage = new ZF_Auth_Storage_Session($expirationSeconds, ucfirst($this->_moduleName));
       $auth->setStorage($auth_storage);

	   $front = Zend_Controller_Front::getInstance();
       $class = 'ZF_Controller_Plugin_' . ucfirst($this->_moduleName);
       $front->registerPlugin(new $class());

       $path = APPLICATION_PATH . "/modules/$this->_moduleName/controllers/helpers";
       Zend_Controller_Action_HelperBroker::addPath($path);
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request){
    }

	public function preDispatch(Zend_Controller_Request_Abstract $request){
        # application.ini
        $config = $this->getOptions();

        # configurar layouts de modulos
        $layoutScript = @$config[$this->_moduleName]['resources']['layout']['layout'];
        if ( ! isset($layoutScript))
            $layoutScript = $config['resources']['layout']['layout'];

        $layout = Zend_Layout::getMvcInstance();

        if(strlen($layoutScript) > 0)
            $layout->setLayout($layoutScript);

	    # si es ajax
	    if($request->isXmlHttpRequest())
           return $layout->disableLayout();
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request){
    }

    public function dispatchLoopShutdown(){
    }
}