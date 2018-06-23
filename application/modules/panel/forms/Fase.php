<?php
class Panel_Form_Fase extends Zend_Form
{
    private $_model = null;

    public function __construct(Zend_Db_Table_Abstract $model){
        $this->_model = $model;
        parent::__construct();
    }

    public function init()
    {
        $this->setMethod('post');
        
        $this->addElement('hidden', 'fase_id', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('hidden', 'accion', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper'),
            'value'      => 'INS'
        ));

        $this->addElement('select', 'torneo_id', array(
            'label'    => 'Campeonato:',
            'multioptions' => $this->_model->listarOpciones(),
            'required' => true
        ));

        /*$this->addElement('text','txt_torneo_id',array(
            'label'    => 'Torneo:',
            'size'     => '30',
            'maxlength'=> '30',
            'value'   => '',
            'attribs' => array('readonly' => 'true')
        ));*/

        $this->addElement('text', 'descripcion', array(
            'label'    => 'Descripcion:',
            'size'     => '50',
            'maxlength'=> '50',
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
