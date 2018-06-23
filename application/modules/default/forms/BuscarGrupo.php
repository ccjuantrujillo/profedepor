<?php

class Default_Form_BuscarGrupo extends Zend_Form
{

    public function init()
    {
        $this->setName('buscar');

        $q = new Zend_Form_Element_Text('q');
        $q->setLabel('Unete a mi hinchada:')
                  ->setRequired(true)
                  ->addValidator('NotEmpty');
        $submit = new Zend_Form_Element_Submit('buscar');
        $submit->setLabel('Buscar');

        $this->addElements(array($q, $submit));
    }


}

