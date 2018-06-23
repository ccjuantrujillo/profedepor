<?php
class Panel_Form_Partido extends Zend_Form
{
    private $_model = null;

    public function __construct($model){
        $this->_model = $model;
        parent::__construct();
    }

    public function init()
    {
        $equipo = new Default_Model_Equipo();
        $fecha     = new Panel_Model_Fecha();
        $fase       = new Panel_Model_Fase();
        $torneo = new Panel_Model_Torneo();
        $this->setMethod('post');
        $this->addElement('hidden', 'partido_id', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper'),
            'value'           => $this->_model->partido_id
        ));

        $this->addElement('select','torneo_id',array(
            'label'    => 'Campeonato:',
            'attribs' => array('onchange'=>"torneo=this.value;fase=(torneo==1)?1:0;fecha=(torneo==1)?1:0;location.href='/panel/partido/registro?torneo_id='+torneo+'&fase_id='+fase+'&fecha_id='+fecha;"),
            'multioptions' => count($torneo->listarOpciones())>0?$torneo->listarOpciones():array('0'=>'Seleccione'),
            'required'         => true,
            'filters'              => array('int'),
            'value'               => $this->_model->torneo_id
        ));

        $this->addElement('select', 'fase_id', array(
            'label'    => 'Fase:',
            'attribs' => array('onchange'=>"location.href='/panel/partido/registro?torneo_id='+$('#torneo_id').val()+'&fase_id='+$('#fase_id').val()+'&fecha_id=0'"),
            'multioptions' => count($fase->listarOpciones($this->_model))>0?$fase->listarOpciones($this->_model):array('0'=>'Seleccione'),
            'required' => true,
            'filters' => array('Int'),
            'value'  => $this->_model->fase_id
        ));

        $this->addElement('select', 'fecha_id', array(
            'label'    => 'Fecha:',
            'attribs' => array('onchange'=>"location.href='/panel/partido/registro?torneo_id='+$('#torneo_id').val()+'&fase_id='+$('#fase_id').val()+'&fecha_id='+this.value"),
            'multioptions' => count($fecha->listarOpciones($this->_model))>0?$fecha->listarOpciones($this->_model):array('0'=>'Seleccione'),
            'required' => true,
            'filters'      => array('int'),
            'value'       => $this->_model->fecha_id
        ));

        $this->addElement('select', 'equipo_local', array(
            'label'    => 'Equipo local:',
            'multioptions' => $equipo->listarOpciones($this->_model),
            'required' => true
        ));

        $this->addElement('select', 'equipo_visitante', array(
            'label'    => 'Equipo visitante:',
            'multioptions' => $equipo->listarOpciones($this->_model),
            'required' => true
        ));

        $this->addElement('text', 'fecha_partido', array(
            'label'    => 'Fecha Partido:',
            'attribs' => array('class' => 'date-pick'),
            'required' => true,
            'filters'  => array('StringTrim'),
            'value'   => $this->_model->fecha_partido
        ));

        $this->addElement('text', 'hora_partido', array(
            'label'    => 'Hora Partido:',
            'attribs' => array('autocomplete' => 'OFF','readonly'=>'readonly'),
            'required' => true,
            'filters'  => array('StringTrim'),
            'value'   => $this->_model->hora_partido
        ));

        $this->addElement('checkbox','fase1',array(
             'attribs' =>array( "checked"=>($this->_model->fase1==1)?"checked":""),
            'label'  => 'Fase 1',
        ));

        $this->addElement('checkbox','fase2',array(
            'attribs' =>array( 'checked'=>($this->_model->fase2==1)?"checked":""),
            'label'  => 'Fase 2',
        ));

        $this->addElement('checkbox','fase3',array(
            'attribs' =>array( 'checked'=>($this->_model->fase3==1)?"checked":""),
            'label'  => 'Fase 3',
        ));

        $this->addElement('button', 'button', array(
            'ignore'  => true,
            'attribs' => array('onclick'=>"history.back(-1);"),
            'label'   => 'Regresar',
            'decorators' => array('Viewhelper', array('HtmlTag', array(
                'tag' => 'span'
            ))),
            'attribs' => array('class' => 'aceptarlog3')
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'  => true,
            'label'   => 'Guardar',
            'decorators' => array('Viewhelper',array('HtmlTag', array(
                'tag' => 'span'
            ))),
            'attribs' => array('class' => 'aceptarlog3')
        ));
    }
}
?>