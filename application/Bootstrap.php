<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    private $_config = null;

    protected function _initAppConfig(){
         return $this->getOptions();
    }

    protected function _initConfigGeneral(){
        date_default_timezone_set('America/Lima');
        Zend_Registry::set('path', (object)$this->getOption('path'));
	}

    protected function _initSessions(){
        Zend_Session::start();
	}

    protected function _initPlugin(){
		$front = Zend_Controller_Front::getInstance();
		$front->registerPlugin(new ZF_Controller_Plugin_Bootstrap());
	}

    protected function _initDb(){
        $rs = $this->getPluginResource('db');
        $db = Zend_Db::factory($rs->getAdapter(), $rs->getParams());
        Zend_Db_Table::setDefaultAdapter($db);

        $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
        $profiler->setEnabled(true);
        $db->setProfiler($profiler);

        $db->setFetchMode(Zend_Db::FETCH_OBJ);
    }

    protected function _initViewResources(){
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
        # $view->doctype('XHTML1_RDFA');

        $front = Zend_Controller_Front::getInstance();
        $front->setBaseUrl('/')
              ->setControllerDirectory(APPLICATION_PATH);

        $view->headTitle()->setSeparator(' ');

        $path = Zend_Registry::get('path');
        $view->headScript()->offsetSetFile(-11, $path->js . 'jquery-1.5.js');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');

        Zend_Registry::set('google', null);

		# $view->navigation($navContainer)
        #    ->setAcl($this->_acl)
        #    ->setRole($this->_auth->getStorage()->read()->role);
        return $view;
    }
}

