<?php

class UbigeoController extends Zend_Controller_Action
{
    protected $_ubigeo = null,
              $elem_options = array('disableLoadDefaultDecorators' => true);

    public function init()
    {
        $this->_ubigeo = new Default_Model_Ubigeo();
    }

    public function distritoAction()
    {
        $departamento = $this->_request->getQuery('departamento');
        $provincia = $this->_request->getQuery('provincia', '01');

        $distritos = $this->_ubigeo->getDistritos($departamento, $provincia);

        $combo = new Zend_Form_Element_Select('distrito', $this->elem_options);
        $combo->setMultiOptions($distritos->fetchAll())
            ->addDecorator('ViewHelper');

        $this->_helper->json(array('html' => $combo->render()));
    }

    public function provinciaAction()
    {
        $departamento = $this->_request->getQuery('departamento');

        $provincias = $this->_ubigeo->getProvincias($departamento);

        $combo = new Zend_Form_Element_Select('provincia', $this->elem_options);
        $combo->setMultiOptions($provincias->fetchAll())
            ->addDecorator('ViewHelper')
            ->setAttrib('onchange', 'cambiar_provincia(this)');

        $this->_helper->json(array('html' => $combo->render()));
    }


}



