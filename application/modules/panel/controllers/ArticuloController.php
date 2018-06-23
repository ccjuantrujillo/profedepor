<?php
class Panel_ArticuloController extends ZF_Controller_Articulo
{
    private $_articulo = null;
    private $_comentario = null;
    private $_fecha = null;
    private $_fecha_actual = null;
    private $_tipo_articulo = null;
    private $_tipoDefaultArticuloId = 1;
    public function init ()
    {
        $this->_articulo = new Panel_Model_Articulo();
        $this->_comentario = new Panel_Model_Comentario();
        $this->_fecha = new Default_Model_Fecha();
        $this->_fecha_actual = $this->_fecha->getFechaHoy();
        $this->_tipo_articulo = new Panel_Model_TipoArticulo();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/noticia.js');
        $this->view->headLink()->appendStylesheet($path->css . 'editor.css');
        $this->view->headLink()->appendStylesheet($path->css . 'articulo.css');
        $this->view->headScript()
            ->appendFile($path->js . 'tiny_mce/jquery.tinymce.js')
            ->appendFile($path->js . 'tiny_mce/tiny_mce.js')
            ->appendFile($path->js . 'jquery.tinymce.js');
        $auth = Zend_Auth::getInstance();
        $this->_usuario_id = $auth->getIdentity()->usuario_id;
    }
    public function indexAction ()
    {
        $tipo = $this->_request->getQuery('tipo', 1);
        $filter = new stdClass();
        $filter->tipoarticulo_id = $tipo;
        $articulos = $this->_articulo->listarArticulos($filter);
        $cantidad = $this->_articulo->cantidadArticulos($tipo);
        $spec = array('articulos' => $articulos, 'cantidad' => $cantidad);
        $this->view->assign($spec);
    }
    public function registroAction ()
    {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $usuario_id = $this->_usuario_id;
            $fecha = $this->_fecha->getFecha($this->_fecha_actual);
            $torneo_id = $fecha['torneo_id'];
            $fase_id = $fecha['fase_id'];
            $fecha_id = $this->_fecha_actual;
            $tipoarticulo_id = $request->getPost('tipoarticulo_id');
            $titulo = $request->getPost('titulo');
            $descripcion = $request->getPost('descripcion');
            $contenido = $request->getPost('contenido');
            $db = $this->_articulo->getAdapter();
            $db->beginTransaction();
            try {
                $id = $this->_articulo->insertar_articulo($tipoarticulo_id, 
                $usuario_id, $torneo_id, $fase_id, $fecha_id, $titulo, 
                $descripcion, $contenido);
                $db->commit();
            } catch (Exception $error) {
                $db->rollBack();
                echo $error->getMessage();
            }
            $this->_helper->json(array('id' => $id));
        }
        $tipoarticulo_id = $this->_request->getQuery('tipoarticulo_id', 
        $this->_tipoDefaultArticuloId);
        $tipoArticulo = new Zend_Form_Element_Select('tipoarticulo_id', 
        array('disableLoadDefaultDecorators' => true, 
        'decorators' => array('ViewHelper', 'Label'), 'label' => '', 
        'multiOptions' => $this->_tipo_articulo->listarOpciones(), 
        'value' => $tipoarticulo_id));
        $this->view->form_title = "Ingreso de Contenido";
        $this->view->tipoArticulo = $tipoArticulo;
    }
    public function verAction ()
    {
        $articulo_id = $this->_request->getQuery('id');
        $articulo = $this->_articulo->obtener_articulo($articulo_id);
        $tipo = $this->_tipo_articulo->obtener_tipo($articulo->tipoarticulo_id);
        $this->view->assign(
        array('articulo_id' => $articulo->articulo_id, 'tipo' => $tipo, 
        'titulo' => $articulo->titulo, 'descripcion' => $articulo->descripcion, 
        'contenido' => $articulo->contenido));
        $this->view->title = "Visualizaci&oacute;n de Contenido";
    }
    public function editarAction ()
    {
        $articulo_id = $this->_request->getQuery('id');
        $articulo = $this->_articulo->obtener_articulo($articulo_id);
        $tipoArticulo = new Zend_Form_Element_Select('tipoarticulo_id', 
        array('disableLoadDefaultDecorators' => true, 
        'decorators' => array('ViewHelper', 'Label'), 'label' => '', 
        'multiOptions' => $this->_tipo_articulo->listarOpciones(), 
        'value' => $articulo->tipoarticulo_id));
        $this->view->assign(
        array('articulo_id' => $articulo->articulo_id, 
        'titulo' => $articulo->titulo, 'descripcion' => $articulo->descripcion, 
        'contenido' => $articulo->contenido));
        $this->view->form_title = "Actualizaci&oacute;n de Contenido";
        $this->view->tipoArticulo = $tipoArticulo;
    }
    public function updateAction ()
    {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $usuario_id = 1; // $this->_usuario_id;
            $articulo_id = $request->getPost('articulo_id');
            $titulo = $request->getPost('titulo');
            $descripcion = $request->getPost('descripcion');
            $contenido = $request->getPost('contenido');
            $db = $this->_articulo->getAdapter();
            $db->beginTransaction();
            try {
                $this->_articulo->modificar_articulo($articulo_id, $titulo, 
                $descripcion, $contenido);
                $db->commit();
            } catch (Exception $error) {
                $db->rollBack();
                echo $error->getMessage();
            }
        }
    }
    public function deleteAction ()
    {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $articulo_id = $request->getPost('articulo_id');
            $cantidad = $this->_comentario->existenComentarios($articulo_id);
            $db = $this->_articulo->getAdapter();
            $db->beginTransaction();
            try {
                if ($cantidad == 0) {
                    $this->_articulo->eliminar_articulo($articulo_id);
                    $db->commit();
                    $msg = "OK";
                } else {
                    $msg = "NO";
                }
            } catch (Exception $error) {
                $db->rollBack();
                echo $error->getMessage();
            }
            $this->_helper->json(array('msg' => $msg));
        }
    }
}
?>
