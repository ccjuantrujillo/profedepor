<?php
class Panel_Form_Variable extends Zend_Form
{
    private $_model = null;

    public function __construct(Zend_Db_Table_Abstract $model){
        $this->_model = $model;
        parent::__construct();
    }

    public function init()
    {
        $this->setMethod('post');       

        $this->addElement('hidden', 'variable_id', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('hidden', 'accion', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper'),
            'value'      => 'INS'
        ));
        
        $this->addElement('select', 'tipovariable_id', array(
            'label'    => 'Tipo de Variable:',
            'multioptions' => $this->_model->listarOpciones(),
            'required' => true
        ));

        $this->addElement('text','txt_tipovariable_id',array(
            'label'    => 'Tipo de Variable:',
            'size'     => '15',
            'maxlength'=> '15',
            'value'   => '',
            'attribs' => array('readonly' => 'true')
        ));
        
        $this->addElement('text', 'descripcion', array(
            'label'    => 'Descripcion:',
            'required' => true,
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
