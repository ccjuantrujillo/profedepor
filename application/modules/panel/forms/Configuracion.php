<?php
class Panel_Form_Configuracion extends Zend_Form{

    private $_model = null;

    public function __construct($model)
    {	
        $this->_model = $model;
        parent::__construct();
    }
    
    public function init()
    {
        $fase   = new Panel_Model_Fase();
        $torneo = new Panel_Model_Torneo();

        $this->setMethod('post');
                
        $this->addElement('hidden', 'configuracion_id', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper'),
            'value'      => $this->_model->configuracion_id
        ));
        
        $this->addElement('select','torneo_id',array(
             'label' => 'Campeonato:',
             'attribs' => array('onchange' => "location.href='/panel/config/registro?torneo_id='+this.value;"),
             'multiOptions' => $torneo->listarOpciones(),
             'required'     => true,
             'value'       => $this->_model->torneo_id
        ));
                
        $this->addElement('select','fase_id',array(
             'label'    => 'Fase:',
             'attribs' => array('onchange' => "tipo=$('#torneo_id').val();location.href='/panel/config/registro?torneo_id='+tipo+'&fase_id='+this.value;"),
             'multioptions' => (count($fase->listarOpciones()) > 0) ? $fase->listarOpciones() : array('0' => 'Seleccione'),
             'required'     => true,
             'value'       => $this->_model->fase_id
        ));
        
        $this->addElement('text', 'tolerancia', array(
            'label'    => 'Tolerancia:',
            'size'     => '20',
            'maxlength'=> '20',
            'required' => true,
            'filters'  => array('StringTrim'),
            'value'    => $this->_model->tolerancia
        ));
        
        $this->addElement('submit','submit',array(
            'ignore'  => true,
            'label'   => 'Guardar',
            'attribs' => array('class'=>'aceptarlog3')
        ));
    }
}
?>