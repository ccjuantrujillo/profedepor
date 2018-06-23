<?php

class Default_Form_TwitterRegistro extends Zend_Form
{

    public function init()
    {
        $this->setName('registro_twitter');

        $q = new Zend_Form_Element_Text('q');
        $q->setLabel('Unete a mi inchada:')
                  ->setRequired(true)
                  ->addValidator('NotEmpty');

        $q = new Zend_Form_Element_Text('q');
        $q->setLabel('Unete a mi inchada:')
                  ->setRequired(true)
                  ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('buscar');
        $submit->setLabel('Buscar');

        $this->addElements(array($foto, $q, $submit));
    }


}

