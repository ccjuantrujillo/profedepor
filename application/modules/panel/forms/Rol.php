<?php
class Panel_Form_Rol extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');

        $this->addElement('hidden', 'rol_id', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('text', 'descripcion', array(
            'label'    => 'Nombres:',
            'required' => true,
            'filters'  => array('StringTrim')
        ));

        $model_modulo = new Panel_Model_Modulo();

        $this->addElement('MultiCheckbox', 'modulos', array(
            'label'    => 'Modulos:',
            'required' => true,
            'multioptions' => $model_modulo->listarOpcion(),
            'filters'  => array('StringTrim')
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'  => true,
            'label'   => 'Guardar',
            'attribs' => array('class' => 'aceptarlog3')
        ));
    }
}
?>
