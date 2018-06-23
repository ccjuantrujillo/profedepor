<?php

class IndexController extends Zend_Controller_Action
{

    private $_equipo = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->_equipo = new Application_Model_DeporEquipo();
    }

    public function indexAction()
    {
        $this->view->equipos = $this->_equipo->fetchAll();
        # $autoloader = Zend_Loader_Autoloader::getInstance();
        # $autoloader->
        # $equipo = Zend_Loader::loadClass('Equipo');
        // action body

        # $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        # $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        # $dbSelect = $authAdapter->getDbSelect();

        # $row = $dbSelect->from('prueba')->fetchAll();

        # print_r($equipo);

    }

    public function addAction()
    {
        $this->view->titleForm = 'Ingreso Equipos';
        # $mail = Zend_Loader('Zend_Mail');

        if( ! $this->_request->isPost())
            return;

        $request = $this->getRequest();


        $nombre = $request->getPost('nombre');
        $eq_id = intval($request->getPost('eq_id'));

        $data = array(
            'DESCRIPCION' => $nombre
        );

        if($eq_id > 0){
            $this->_equipo->update($data, "eq_id = '$eq_id'");
        } else {
            $this->_equipo->insert($data);
        }

        $this->_helper->redirector('index');

        # insertar
        # print_r(get_class_methods(get_class($this->_request)));

        // action body
    }

    public function deleteAction()
    {
        // action body
        $eq_id = $this->_request->getParam('eq_id');
        $this->_equipo->delete("eq_id = '$eq_id'");

        $this->_helper->redirector('index');
    }

    public function updateAction()
    {
        $this->view->titleForm = 'Actualizar Equipos';

        # $this->_equipo->setFetchMode('');

        $eq_id = $this->_request->getParam('eq_id');
        $this->view->equipo = $this->_equipo->select()
            ->where("eq_id = ?", $eq_id)
            ->query()
            ->fetch();

        # exit($eq_id);

        echo $this->view->render('index/add.phtml');
        # exit;
        // action body
    }


}









