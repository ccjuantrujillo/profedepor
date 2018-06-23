<?php
class Panel_JugadorController extends ZF_Controller_Usuario
{
    private $_jugador = null;
    private $_jugadorgrupo = null;
    private $_tipodoc = null;
    
    public function init ()
    {
        $this->_jugador = new Panel_Model_Jugador();
        $this->_jugadorgrupo = new Panel_Model_JugadorGrupo();
        $this->_tipodoc = new Panel_Model_TipoDocumento();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/jugador.js');
        //$this->view->headScript()->appendFile($path->js . 'panel/ajaxupload.3.6.js');
    }
    
    public function indexAction ()
    {    	     	
    	#paginacion
        $_letters = range('A', 'Z');
        $letra = $this->_request->getQuery('letra');
        $data['letra'] = 'A';
        # filtrar por letra
        if (in_array($letra, $_letters) && strlen($letra) > 0) {
            $data['letra'] = $letra;
        } else {
            $data['letra'] = '';
        }
        $_paginator = $this->_jugador->buscar($data);
        $letters = array();
        foreach ($_letters as $letter) {
            if ($letra == $letter)
                $letters[] = '<b>' . $letter . '</b>';
            else
                $letters[] = '<a href="/panel/jugador?letra=' . $letter . '">' .
                 $letter . '</a>';
        }
        $letters = implode(' - ', $letters);
        $this->view->paginator = $_paginator;
        $this->view->assign(
        array('letra' => $letra, 'letters' => $letters));
    }
    
    public function verAction ()
    {
        $jugador_id = $this->_request->getQuery('jugador_id', 0);
        $form_title = 'Detalle de jugador';
        $jugador = $this->_jugador->obtenerJugador($jugador_id);
        $tipo = $this->_tipodoc->obtener_tipo($jugador->tipodoc_id);
        $spec = array(
        			'form_title' => $form_title, 
        			'jugador' => $jugador, 
        			'tipo' => $tipo);
        $this->view->assign($spec);
    }
    
    public function editarAction ()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $jugador_id = $request->getPost('jugador_id');
            $nombres = $request->getPost('nombres');
            $apellidos = $request->getPost('apellidos');
            $tipodoc_id = $request->getPost('tipodoc_id');
            $numero_doc = $request->getPost('numero_doc');
            $telefono = $request->getPost('telefono');
            $dni_apoderado = $request->getPost('dni_apoderado');
            $data = array('jugador_id' => $jugador_id, 'nombres' => $nombres, 
            			'apellidos' => $apellidos, 'tipodoc_id' => $tipodoc_id, 
            			'numero_doc' => $numero_doc, 'telefono' => $telefono, 
            			'dni_apoderado' => $dni_apoderado);
            $this->_jugador->actualizar_jugador($data);
        }
        $jugador_id = $this->_request->getQuery('jugador_id', 0);
        $form_title = 'Actualizaci&oacute;n de jugador';
        $jugador = $this->_jugador->obtenerJugador($jugador_id);
        $tipo = new Zend_Form_Element_Select('tipodoc_id', 
        array('disableLoadDefaultDecorators' => true, 
        			'decorators' => array('ViewHelper', 'Label'), 'label' => '', 
        			'multiOptions' => $this->_tipodoc->listarOpcionesDoc(), 
        			'value' => $jugador->tipodoc_id));
        $spec = array('form_title' => $form_title, 'jugador' => $jugador, 
        'tipo' => $tipo);
        $this->view->assign($spec);
    }
    
    public function deleteAction ()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $jugador_id = $request->getPost('jugador_id');
            #comprobar grupos creados
            $agrupo = $this->_jugadorgrupo->jugadorAdmin($jugador_id); 
            if($agrupo == 0){
            	$this->_jugador->delete($jugador_id);  
            	$msg = "OK"; 
            }else{
            	$msg = "NO";
            }                        
            $this->_helper->json(array('msg' => $msg));
        }
    }
    
}