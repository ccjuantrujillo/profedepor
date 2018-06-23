<?php

class ArticuloController extends Zend_Controller_Action {
    
    private $_articulo = null;    
    private $_comentario = null;
    private $_fecha = null;
    private $_voto = null;
    private $_jugador = null;    
    private $_jugador_id = null;

    public function init()
    {
        $this->_articulo = new Default_Model_Articulo();
        $this->_comentario = new Default_Model_Comentario();
        $this->_jugador = new Default_Model_Jugador();
        $this->_fecha = new Default_Model_Fecha();
        $this->_fecha_actual = $this->_fecha->getFechaHoy();
        $this->_voto = new Default_Model_Voto();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'articulo.js');
        $this->view->headLink()->appendStylesheet($path->css . 'articulo.css');
        $auth = Zend_Auth::getInstance();
        $this->_jugador_id = $auth->getIdentity()->jugador_id;
    }

    public function indexAction()
    {
        $tipo = $this->_request->getParam('id', 0);
        $fecha = $this->_fecha_actual;
        
        #tipo noticia
        $this->view->tipo = $this->_articulo->getNombreTipo($tipo);
        #noticias
        $archivo = $this->_articulo->obtenerUltimoArchivo($tipo);
        $this->view->articulos = $this->_articulo->listarArticulosMes($archivo->anio, $archivo->mes, $tipo);
        #ranking
        $this->view->rankgeneral = $this->_jugador->rankingMinGeneral(7);
        #archivo
        $this->view->archivo = $this->_articulo->obtenerArchivo($tipo);
    }

    public function archivoAction()
    {
        $tipo = $this->_request->getParam('id', 0);
        $mes = $this->_request->getParam('mes', 0);
        $anio = $this->_request->getParam('anio', 0);
        #tipo noticia
        $this->view->tipo = $this->_articulo->getNombreTipo($tipo);
        #noticias por mes # $mes, $tipo : 2, 1
        $this->view->articulos = $this->_articulo->listarArticulosMes($anio, $mes, $tipo);
        #ranking
        $this->view->rankgeneral = $this->_jugador->rankingMinGeneral(7);
        #archivo
        $this->view->archivo = $this->_articulo->obtenerArchivo($tipo);
    }

    public function mostrarAction()
    {
        $tipo = $this->_request->getParam('tipo', 0);
        $id = $this->_request->getParam('id', 0);
        #jugador_id
        $this->view->jugador = $this->_jugador_id;
        #tipo noticia
        $this->view->tipo = $this->_articulo->getNombreTipo($tipo);
        #noticia detalle # $articulo_id, $tipo
        $articulo = $this->_articulo->obtenerArticulo($id, $tipo); 
        $this->view->articulo = $articulo;
        $this->view->fecha = $this->_articulo->formatoFecha($articulo->registro);
        #ranking
        $this->view->rankgeneral = $this->_jugador->rankingMinGeneral(7);
        #archivo
        $this->view->archivo = $this->_articulo->obtenerArchivo($tipo);
        #comentarios ingresados :        
        $cantidad = $this->_comentario->existenComentarios($id); 
        
        $cPaginator = Zend_Registry::get('paginator');
        if(($cantidad%3 != 0) && ($cantidad > 3)){
        	$n = (int)($cantidad/3)+1;
        	$_numbers = range(1, $n);           
        }else if($cantidad > 3){ 
            $n = (int)($cantidad/3); 
            $_numbers = range(1, $n);
        }else{
            $_numbers = array(1);
        }     
        #paginacion       
        $numero = $this->_request->getParam('num', 1);        
        # filtrar por numero
        if (in_array($numero, $_numbers) && strlen($numero) > 0) {
            $data['num'] = $numero;
        }
        $data['articulo_id'] = $id; 
        $data['tipo'] = $tipo; 
        $data['_numbers'] = $_numbers;
        
        $_paginator = $this->_comentario->listar_comentarios($data);                        
        $this->view->assign($_paginator);
                
        #votos
        $this->view->tot1 = $this->_voto->obtenerTotal(1, $id);
        $this->view->tot2 = $this->_voto->obtenerTotal(2, $id);
        $this->view->tot3 = $this->_voto->obtenerTotal(3, $id);
    }

    public function guardarAction() {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $jugador_id = $this->_jugador_id;
            $descripcion = trim(htmlentities($request->getPost('comentario'), ENT_QUOTES));
            $articulo_id = $request->getPost('articulo_id');
            $tipo_id = $request->getPost('tipoarticulo_id');
            $db = $this->_comentario->getAdapter();
            $db->beginTransaction();
            try {
                $this->_comentario->insertar_comentario($articulo_id, $jugador_id, $descripcion);
                $db->commit();
            } catch (Exception $error) {
                $db->rollBack();
                echo $error->getMessage();
            }  
            
            #comentarios ingresados
            $cantidad = $this->_comentario->existenComentarios($articulo_id);
            
	        $cPaginator = Zend_Registry::get('paginator');
	        if(($cantidad%3 != 0) && ($cantidad > 3)){
	        	$n = (int)($cantidad/3)+1;
	        	$_numbers = range(1, $n);           
	        }else if($cantidad > 3){ 
	            $n = (int)($cantidad/3); 
	            $_numbers = range(1, $n);
	        }else{
	            $_numbers = array(1);
	        }        
	        #paginacion       
	        $numero = $this->_request->getParam('num', 1);        
	        # filtrar por numero
	        if (in_array($numero, $_numbers) && strlen($numero) > 0) {
	            $data['num'] = $numero;
	        }
	        $data['articulo_id'] = $articulo_id; 
	        $data['tipo'] = $tipo_id; 
	        $data['_numbers'] = $_numbers;
	        
	        $_paginator = $this->_comentario->listar_comentarios($data);                        
	        $this->view->assign($_paginator);        
		}
    }

    public function votarAction() {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $jugador_id = $this->_jugador_id;
            $articulo_id = $request->getPost('articulo_id');
            $tipo = $request->getPost('tipo');
            $valor = 1;
            $dfecha = $this->_voto->obtenerFecVoto($articulo_id, $jugador_id);
            if($dfecha != null){
                foreach ($dfecha as $d) {
                    if($d->dhora < 24){
                        $dhora = 0;
                        break;
                    }else{
                        $dhora = 1;
                    }
                }
            }else{
                $dhora = 1;
            }
            
            $db = $this->_voto->getAdapter();
            $db->beginTransaction();
            try {
                if($dhora == 1){
                    $this->_voto->insertarVoto($tipo, $articulo_id, $jugador_id, $valor);
                    $db->commit();
                }
            } catch (Exception $error) {
                $db->rollBack();
                echo $error->getMessage();
            }
            #comentarios ingresados
            $totaltipo = $this->_voto->obtenerTotal($tipo, $articulo_id);            
            $this->_helper->json(array(
                'total' => $totaltipo->total,
                'dhora'=> $dhora));
        }
    }
    
}