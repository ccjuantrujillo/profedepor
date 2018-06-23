<?php
class Panel_Form_Equipo extends Zend_Form
{
    private $_model_1 = null;
    private $_model_2 = null;
    private $_filter = null;
    private $_filter2 = null;
    public function __construct(Zend_Db_Table_Abstract $model_1,
            Zend_Db_Table_Abstract $model_2,stdClass $filter,$filter2){
        $this->_model_1 = $model_1;
        $this->_model_2 = $model_2;
        $this->_filter  = $filter;
        $this->_filter2  = $filter2;
        parent::__construct();
    }

    public function init()
    {
        $this->setMethod('post');
        $this->addElement('hidden', 'equipo_id', array(
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
            'multioptions' => $this->_model_1->listarOpciones(),
            'attribs'  => array('onchange'=>'filterTorneo(this);'),
            'required' => true
        ));

        $this->addElement('text','txt_torneo_id',array(
            'label'    => 'Campeonato:',
            'size'     => '30',
            'maxlength'=> '30',
            'value'   => '',
            'attribs' => array('readonly' => 'true')
        ));

        $this->addElement('select', 'club_id', array(
            'label'    => 'Club:',
            'multioptions' => $this->_model_2->listarOpcionesOut($this->_filter->torneo_id,$this->_filter2->equipo_id),
            'required' => true,
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'  => true,
            'label'   => 'Guardar',
            'attribs' => array('class' => 'aceptarlog3')
        ));
    }
}
?>
