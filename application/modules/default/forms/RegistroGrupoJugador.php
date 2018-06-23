<?php

class Default_Form_RegistroGrupoJugador extends Zend_Form
{

    public function init()
    {
        $this->setName('registro');
        // $this->setAction('/registro/crear');

        $firstName = new Zend_Form_Element_Text('nombres');
        $firstName->setLabel('Nombres:')
                  ->setRequired(true)
                  ->addValidator('NotEmpty');

        $lastName = new Zend_Form_Element_Text('paterno');
        $lastName->setLabel('Apellidos:')
                  ->setRequired(true)
                  ->addValidator('NotEmpty');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email:')
              ->addFilter('StringToLower')
              ->setRequired(true)
              ->addValidator('NotEmpty')
              ->addValidator('EmailAddress');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Clave:')
                 ->setRequired(true)
                 ->addValidator('StringLength', false, array('min' => 6))
                 ->addValidator('NotEmpty');

        $password2 = new Zend_Form_Element_Password('password2');
        $password2->setLabel('Repetir Clave:')
                  ->addValidator('Identical', false, array('token' => 'password'))
                  ->setRequired(true)
                  ->addValidator('NotEmpty');

        /*

        $terminos = new Zend_Form_Element_Checkbox('terminos');
        $terminos->setLabel('Eh leido los terminos:')
                  ->setRequired(true)
                  ->addValidator('NotEmpty');
        */

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Registrar');

        /*

         $submit->setDecorators(array(
            array('ViewHelper'),
            array('Description'),
            array('HtmlTag', array('tag' => 'li', 'class'=>'submit-group')),
        ));

        */


        $this->addElements(array($firstName, $lastName, $email, $password, $password2, $submit));
    }


}

