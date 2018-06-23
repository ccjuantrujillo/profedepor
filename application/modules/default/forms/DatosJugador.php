<?php

class Default_Form_DatosJugador extends Zend_Form
{

    public function init()
    {
        $this->setName('registro');
        #    ->addDecorator('HtmlTag', array('tag' => 'ol'));
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

        $telefono = new Zend_Form_Element_Text('telefono');
        $telefono->setLabel('Telefono:')
                  ->setRequired(true)
                  ->addValidator('NotEmpty');

        $ubigeo = new Default_Model_Ubigeo();

        $columns = array(
            'key' => 'departamento',
            'value' => 'descripcion'
        );

        $departamentos = $ubigeo->select()
            ->setIntegrityCheck(false)
            ->from('depor_ubigeo', $columns)
            ->where('provincia = ?', '00')
            ->where('distrito = ?', '00')
            ->query()
            ->fetchAll();

        $departamento = new Zend_Form_Element_Select('departamento');
        $departamento->setLabel('Departamento:')
                 # ->setAttrib('onchange', '')
                 ->setRequired(true)
                 ->addValidator('NotEmpty')
                 ->addMultiOptions($departamentos)
                 ->removeDecorator('HtmlTag');
                 # ->addDecorator('Label');

        $provincia = new Zend_Form_Element_Select('provincia');
        $provincia->setLabel('Provincia')
                 ->setAttrib('disabled', 'true')
                 ->setRequired(true)
                 ->addValidator('NotEmpty')
                 ->addMultiOption('', '--')
                 # ->addDecorator('HtmlTag', array('tag' => 'label'))
                 ->removeDecorator('HtmlTag')
                 ->removeDecorator('Label');

        $distrito = new Zend_Form_Element_Select('distrito');
        $distrito->setLabel('Distrito:')
                 ->setAttrib('disabled', 'true')
                 ->setRequired(true)
                 ->addValidator('NotEmpty')
                 ->addMultiOption('', '--')
                 ->removeDecorator('HtmlTag')
                 ->removeDecorator('Label');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Guardar');

        $this->addElements(array(
            $firstName, $lastName, $email, $password, $telefono,
            $departamento, $provincia, $distrito, $submit
        ));
    }


}

