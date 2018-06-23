<?php
class FechaController extends Zend_Controller_Action
{
	private $_fecha = null;
	public function init()
	{
		$this->_fecha = new Default_Model_Fecha();
	}

	public function indexAction()
	{
            $this->view->fechas = $this->_fecha->fetchAll();
	}
}