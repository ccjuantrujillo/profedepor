<?php
class Panel_ComentarioController extends ZF_Controller_Comentario {
    private $_articulo = null;
    private $_comentario = null;
    private $_darticulo = null;
    private $_fecha = null;
    private $_fecha_actual = null;
    private $_tipo_articulo = null;

    public function init() {
        $this->_articulo = new Panel_Model_Articulo();
        $this->_darticulo = new Default_Model_Articulo();
        $this->_comentario = new Panel_Model_Comentario();
        $this->_fecha = new Default_Model_Fecha();
        $this->_fecha_actual = $this->_fecha->getFechaHoy();
        $this->_tipo_articulo = new Panel_Model_TipoArticulo();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/comentario.js');
    }

    public function indexAction() {
    	$this->_redirect('/panel/comentario/ver/id/1');
    }

    public function verAction() {
        $tipo = $this->_request->getParam('id', 1);
        #tipo noticia
        $this->view->tipo = $this->_darticulo->getNombreTipo($tipo);
        #noticias
        $archivo = $this->_darticulo->obtenerUltimoArchivo($tipo);
        if($archivo != null){
            $this->view->articulos = $this->_darticulo->listarArticulosMes($archivo->anio, $archivo->mes, $tipo);
        }
        #archivo
        $this->view->archivo = $this->_darticulo->obtenerArchivo($tipo);
    }

    public function archivoAction(){
        $tipo = $this->_request->getParam('id', 0);
        $mes = $this->_request->getParam('mes', 0);
        $anio = $this->_request->getParam('anio', 0);
        #tipo noticia
        $this->view->tipo = $this->_darticulo->getNombreTipo($tipo);
        #noticias por mes
        $this->view->articulos = $this->_darticulo->listarArticulosMes($anio, $mes, $tipo);
        #archivo
        $this->view->archivo = $this->_darticulo->obtenerArchivo($tipo);
    }

    public function editarAction() {
        $tipo = $this->_request->getParam('tipo', 0);
        $id = $this->_request->getParam('id', 0);
        $this->view->tipo = $this->_darticulo->getNombreTipo($tipo);
        $cantidad = $this->_comentario->existenComentarios($id); 
        $cPaginator = Zend_Registry::get('paginator');
        if(($cantidad%$cPaginator->pageRange != 0) && ($cantidad > $cPaginator->pageRange)){
        	$n = ($cantidad/$cPaginator->pageRange)+1;
        	$_numbers = range(1, $n);           
        }else{ 
            $n = $cantidad/$cPaginator->pageRange; 
            $_numbers = array(1);
        }     
        #paginacion
        //$_numbers = range(1, $n);
        $numero = $this->_request->getParam('num', 1);        
        # filtrar por numero
        if (in_array($numero, $_numbers) && strlen($numero) > 0) {
            $data['num'] = $numero;
        }
        $data['articulo_id'] = $id;        
        $_paginator = $this->_comentario->buscar($data);        
                
        $numbers = array();
        foreach ($_numbers as $n) {
            if ($numero == $n)
                $numbers[] = '<b>' . $n . '</b>';
            else
                $numbers[] = '<a href="/panel/comentario/editar/tipo/'.$tipo.'/id/'.$id.'/num/' . $n . '">' .
                 $n . '</a>';
        }
        $numbers = implode(' - ', $numbers);
                
        $this->view->comentarios = $_paginator;
        $this->view->assign(array('num' => $numero, 'numbers' => $numbers));
        
        #archivo
        $this->view->archivo = $this->_darticulo->obtenerArchivo($tipo);
    }

    public function deleteAction() {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $comentario_id = $request->getPost('comentario_id');
            $db = $this->_comentario->getAdapter();
            $db->beginTransaction();
            try {
                $this->_comentario->eliminar_comentario($comentario_id);
                $db->commit();
            } catch (Exception $error) {
                $db->rollBack();
                echo $error->getMessage();
            }
        }
    }

}

?>