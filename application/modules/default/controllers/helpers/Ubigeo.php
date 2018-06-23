<?php

class Zend_Controller_Action_Helper_Ubigeo extends Zend_Controller_Action_Helper_Abstract {
    private static $_ubigeo = null;

    public function direct($name, Zend_Db_Statement $multiOptions, $value = null){
        $elem = new Zend_Form_Element_Select($name, array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper'),
            'filters' => array('HtmlEntities'),
            'multiOptions' => $multiOptions->fetchAll(),
            'value' => $value
        ));

        return $elem;
    }
}

