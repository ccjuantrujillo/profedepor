<?php
class Panel_Form_Fecha extends Zend_Form
{
    private $_model = null;

    public function __construct($model){
        $this->_model = $model;
        parent::__construct();
    }

    public function init()
    {
    	$fase   = new Panel_Model_Fase();
        $torneo = new Panel_Model_Torneo();
        $tipofecha = new Panel_Model_TipoFecha();
        $this->setMethod('post');

        $this->addElement('hidden', 'fecha_id', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper'),
        	'value'      => $this->_model->fecha_id
        ));

        $this->addElement('hidden', 'accion', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper'),
            'value'      => 'INS'
        ));
        
        $this->addElement('select', 'torneo_id', array(
            'label'   => 'Campeonato:',
            'attribs' => array('onchange'=>"location.href='/panel/fecha/registro?torneo_id='+$('#torneo_id').val()+'&fase_id='+$('#fase_id').val()"),
            'multioptions' => count($torneo->listarOpciones())>0?$torneo->listarOpciones():array('0'=>'Seleccione'),
            'required' => true,
            'value'    => $this->_model->torneo_id
        ));

        $this->addElement('text','txt_torneo_id',array(
            'label'    => 'Campeonato:',
            'size'     => '30',
            'maxlength'=> '30',
            'value'   => '',
            'attribs' => array('readonly' => 'true')
        ));

        $this->addElement('select', 'fase_id', array(
            'label'    => 'Fase:',
            'attribs' => array('onchange'=>"location.href='/panel/fecha/registro?torneo_id='+$('#torneo_id').val()+'&fase_id='+$('#fase_id').val()"),
            'multioptions' => count($fase->listarOpciones($this->_model))>0?$fase->listarOpciones($this->_model):array('0'=>'Seleccione'),
            'required' => true,
            'value'    => $this->_model->fase_id
        ));
        
        $this->addElement('text','txt_fase_id',array(
            'label'    => 'Fase:',
            'size'     => '15',
            'maxlength'=> '15',
            'value'   => '',
            'attribs' => array('readonly' => 'true')
        ));

        $this->addElement('text','fecha1',array(
            'label'    => 'Fecha Inicio:',
            'attribs'  => array('class' => 'date-pick'),
            'required' => true,
            'filters'  => array('StringTrim'),
        	'value'   => $this->_model->fecha1
        ));

        $this->addElement('text','fecha2',array(
            'label'    => 'Fecha Fin:',
            'attribs'  => array('class' => 'date-pick'),
            'required' => true,
            'filters'  => array('StringTrim'),
        	'value'   => $this->_model->fecha2
        ));

        $this->addElement('text','intervalo',array(
            'label'    => 'Intervalo:',
            'size'     => '30',
            'maxlength'=> '30',
            'required' => true,
            'filters'  => array('StringTrim'),
        	'value'   => $this->_model->intervalo
        ));

        $this->addElement('text','descripcion',array(
            'label'    => 'Descripción:',
            'size'     => '30',
            'maxlength'=> '30',
            'required' => true,
            'filters'  => array('StringTrim'),
        	'value'   => $this->_model->descripcion
        ));

        $this->addElement('select', 'tipo', array(
            'label'    => 'Tipo:',
            'multioptions' => array('1' => 'IDA', '2' => 'VUELTA'),
            'required' => true
        ));

        $this->addElement('select', 'grupo_id', array(
            'label'    => 'Tipo Fecha:',
            'multioptions' => count($tipofecha->listarOpciones($this->_model))>0?$tipofecha->listarOpciones($this->_model):array('0'=>'Seleccione'),
            'required' => true,
        	'value'    => $this->_model->tipo
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'  => true,
            'label'   => 'Guardar',
            'attribs' => array('class' => 'aceptarlog3')
        ));
    }
}
?>
