<?php
class Panel_Form_Intervalo extends Zend_Form
{
    private $_model = null;
    private $_tipoVar = null;
    private $_variable = null;
    
    public function __construct(Zend_Db_Table_Abstract $model){
        $this->_model = $model;
        $this->_tipoVar  = new Panel_Model_TipoVariable();
        $this->_variable = new Panel_Model_Variable();
        parent::__construct();
    }

    public function init()
    {
        $this->setMethod('post');

        $this->addElement('hidden', 'intervalo_id', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('hidden', 'accion', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper'),
            'value'      => 'INS'
        ));

        $this->addElement('text', 'valori', array(
            'label'    => 'Valor Inicial:',
            'required' => true,
            'filters'  => array('StringTrim'),
            'validators' => array('Digits')
        ));

        $this->addElement('text', 'valorf', array(
            'label'    => 'Valor Final:',
            'required' => true,
            'filters'  => array('StringTrim'),
            'validators' => array('Digits')
        ));

        $this->addElement('text', 'descripcion', array(
            'label'    => 'Descripcion:',
            'required' => true,
            'filters'  => array('StringTrim')
        ));

        $this->addElement('select', 'tipovariable_id', array(
            'attribs' => array('onchange'=>''),
            'label'    => 'Tipo Variable:',
            'multioptions' => $this->_tipoVar->listarOpciones(),
            'required' => true
        ));

        $this->addElement('text','txt_tipovariable_id',array(
            'label'    => 'Tipo Variable:',
            'size'     => '15',
            'maxlength'=> '15',
            'value'    => '',
            'attribs'  => array('readonly' => 'true')
        ));

        $this->addElement('select', 'variable_id', array(
            'label'    => 'Variable:',
            'multioptions' => $this->_variable->listarOpciones(),
            'required' => true
        ));

        $this->addElement('text','txt_variable_id',array(
            'label'    => 'Variable:',
            'size'     => '30',
            'maxlength'=> '30',
            'value'    => '',
            'attribs'  => array('readonly' => 'true')
        ));

        $this->addElement('text', 'puntaje', array(
            'label'    => 'Puntaje:',
            'required' => true,
            'filters'  => array('StringTrim'),
            'validators' => array('Digits')
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'  => true,
            'label'   => 'Guardar',
            'attribs' => array('class' => 'aceptarlog3')
        ));
    }
}
?>
