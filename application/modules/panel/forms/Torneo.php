<?php
class Panel_Form_Torneo extends Zend_Form
{
    private $_model = null;

    public function __construct(Zend_Db_Table_Abstract $model){
        $this->_model = $model;
        parent::__construct();
    }

    public function init()
    {      
        $this->setMethod('post');

        $this->addElement('hidden', 'torneo_id', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper')
        ));
        
        $this->addElement('text', 'descripcion', array(
            'label'    => 'Nombre:',
            'size'     => '50',
            'maxlength'=> '50',
            'required' => true,
            'filters'  => array('StringTrim')
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'  => true,
            'label'   => 'Guardar',
            'attribs' => array('class' => 'aceptarlog2')
        ));        
    }
}
?>
