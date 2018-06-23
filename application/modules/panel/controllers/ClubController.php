<?php
require 'ThumbLib.inc.php';
class Panel_ClubController extends ZF_Controller_Maestro {

    private $_club = null;
    private $_equipo = null;

    public function init() {
        $this->_club = new Panel_Model_Club();
        $this->_equipo = new Panel_Model_Equipo();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'panel/club.js');
        $this->view->headScript()->appendFile($path->js . 'ajaxupload.3.6.js');
    }

    public function indexAction() {
        $this->view->clubes      = $this->_club->listarClubes();
        $this->view->cantidad = $this->_club->cantidad_clubes();
        $this->view->title          = "Lista de Clubes";
    }

    public function registroAction() {
        $form_title = 'Ingreso de Club';
        $this->view->form_title = $form_title;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $descripcion = $request->getPost('descripcion');
            $nom_icono = $request->getPost('fotoClub');
            $nom_imagen = $request->getPost('imagenClub');
            $db = $this->_club->getAdapter();
            $db->beginTransaction();
            try {
                $club_id = $this->_club->insertar_club($descripcion, $nom_icono, $nom_imagen);
                $db->commit();
            } catch (Exception $error) {
                $db->rollBack();
                echo $error->getMessage();
            }            
        }
    }

    public function registroimagenAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $path = Zend_Registry::get('path');
            $jugador = Zend_Auth::getInstance()->getIdentity();
            $uploaded_file = new Zend_File_Transfer_Adapter_Http();
            $uploaded_file->setDestination($path->destino_img);
            try {
                $file = $uploaded_file->getFileInfo();
                $name = $file['fotoClub']['name'];
                $nuevo = '_logo_'.$name;
                $uploaded_file->receive();
                $destino_foto1 = $path->destino_img.$name;
                $destino_foto2 = $path->destino_img.$nuevo;
                $thumb = PhpThumbFactory::create($destino_foto1);
                $thumb->resize(26, 28);
                $thumb->save($destino_foto2);
                exit($nuevo);
            } catch (Zend_File_Transfer_Exception $e) {
                $e->getMessage();
            }
        }
        exit('fail');
    }

    public function editarAction() {        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $club_id = $request->getPost('club_id');
            $descripcion = $request->getPost('descripcion');
            $nom_icono = $request->getPost('fotoClub');
            $nom_imagen = $request->getPost('imagenClub');
            $this->_club->actualizar_club($club_id, $descripcion, $nom_icono, $nom_imagen);
        }
        $club_id = $this->_request->getQuery('club_id', 0);
        $form_title = 'Edici&oacute;n de Club';
        $this->view->form_title = $form_title;
        $this->view->club = $this->_club->obtener_club($club_id);
    }
    
    public function deleteAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $club_id = $request->getPost('club_id');
            # consultar si hay equipos asociados
            $cantidad = $this->_equipo->existenEquipos($club_id);
            if($cantidad == 0){
            	$this->_club->deleteClub("club_id = '$club_id'");
            	$msg = "OK";
            }else{
            	$msg = "NO";
            }
            $this->_helper->json(array('msg' => $msg));
        }
    }

}