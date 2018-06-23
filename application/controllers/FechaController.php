<?php
class FechaController extends Zend_Controller_Action
{
	public function init()
	{
	
	}
	
	public function indexAction()
	{
		$fecha = new Application_Model_FechaMapper();
		$this->view->entries = $fecha->fetchAll();
	}
}