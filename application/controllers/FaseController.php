<?php
class FaseController extends Zend_Controller_Action
{
	private $_fase = null;
	public function init()
	{
		$this->_fase = new Application_Model_Fase();
	}
	public function indexAction()
	{
		$this->view->fases = $this->_fase->fetchAll();
	}
}