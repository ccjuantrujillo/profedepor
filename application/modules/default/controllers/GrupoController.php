<?php

require 'ThumbLib.inc.php';

class GrupoController extends Zend_Controller_Action {

    private $_fecha = null;
    private $_fecha_actual = null;
    private $_grupo = null;
    private $_mensaje = null;
    private $_jugador = null;
    private $_jugador_grupo = null;
    private $_jugador_id = null;

    public function init() {
        $this->_fecha = new Default_Model_Fecha();
        $this->_fecha_actual = $this->_fecha->getFechaHoy();
        $this->_grupo = new Default_Model_Grupo($this->_request);
        $this->_mensaje = new Default_Model_Muro();
        $this->_jugador = new Default_Model_Jugador();
        $this->_jugador_grupo = new Default_Model_JugadorGrupo();
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'grupo.js');
        $auth = Zend_Auth::getInstance();
        $this->_jugador_id = $auth->getIdentity()->jugador_id;
    }

    public function indexAction() {
        $this->_redirect('/grupo/ver/id/1');
    }

    public function verAction() {
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'comentarios.js');

        $grupo_id = $this->_request->getParam('id', 1);
        $grupo = new stdClass();
        $grupo->grupo_id = $grupo_id;
        # perfil del grupo
        $this->view->pgrupo = $this->_jugador_grupo->obtener_perfil_grupo($grupo, $this->_grupo);
        $nomgrupo = $this->_grupo->getGrupo($grupo_id);
        $this->view->nombregrupo = (strlen($nomgrupo->nombre) > 24)?substr($nomgrupo->nombre, 0, 24)."...":$nomgrupo->nombre;        
        # integrantes por grupo
        $this->view->integrantes = $this->_jugador_grupo->integrantesGrupo($grupo_id);
        # numero de solicitudes
        $this->view->solicitudes = $this->_jugador_grupo->cantidad_integrantes($grupo_id, 0, 2, 1);
        # condicion del jugador: admingrupo o hincha
        $this->view->isOwnerGroup = $this->_jugador_grupo->existeJugadorEnGrupo($this->_jugador_id, $grupo_id, 1);
        # grupos registrados
        $this->view->usuarioGrupos = $this->_grupo->miembros_grupo(1, 1);
        # mensajes en el muro
        $this->view->lista_muro = $this->_mensaje->listar_mensajes_grupo($grupo_id, 10);
        $this->view->fecha_hoy = $this->_fecha->getFecha($this->_fecha_actual);
        #ranking
        $this->view->rankgrupo = $this->_jugador_grupo->listarRankingGrupoGeneral($grupo_id);
        $this->view->countgrupo = $this->_jugador->countRankingGrupoGeneral($grupo_id);
        $this->view->rankgeneral = $this->_jugador->rankingMinGeneral(7);
        $this->view->rankgrupofecha = $this->_fecha->getRankingGrupoFecha($this->_fecha_actual, $grupo_id);        
    }
     
    public function registroAction() {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $nombre = ucwords(strtolower($request->getPost('nombre')));
            $descripcion = htmlentities($request->getPost('descripcion'));
            $tipo = $request->getPost('tipo');
            $nomfoto = $request->getPost('fileFoto');
            if($nomfoto == ""){
                $nomfoto = "icono_mancha.png";
            }
            $data = array(
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'tipo' => $tipo,
                'foto' => $nomfoto
            );
            $id = $this->_grupo->save($data);
            $this->_helper->json(array('grupo' => $id));
        }
        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'creargrupo.js');
        # grupos registrados
        $this->view->usuarioGrupos = $this->_grupo->miembros_grupo(1, 1);
    }

    public function registropopAction() {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $grupo_id = $request->getPost('grupoadmin');
            $admin = $this->_jugador_grupo->getAdminGrupo($grupo_id);
        }
        $this->view->admin = $admin;
        $this->view->grupoid = $grupo_id;
    }

    public function registrofotoAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $path = Zend_Registry::get('path');
            $jugador = Zend_Auth::getInstance()->getIdentity();
            $uploaded_file = new Zend_File_Transfer_Adapter_Http();
            $uploaded_file->setDestination($path->destino_foto.'grupo/');
            try {
                $file = $uploaded_file->getFileInfo();
                $name = $file['fileFoto']['name'];
                $nuevo = 'fgrupo_'.$jugador->jugador_id.".".$name;
                $uploaded_file->receive();
                $destino_foto1 = $path->destino_foto.'grupo/'.$name;
                $destino_foto2 = $path->destino_foto.'grupo/'.$nuevo;
                $thumb = PhpThumbFactory::create($destino_foto1);
                $thumb->resize(50, 45);
                $thumb->save($destino_foto2);
                exit($nuevo);
            } catch (Zend_File_Transfer_Exception $e) {
                $e->getMessage();
            }         
        }
        exit('fail');
    }

	public function guardarAction() {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $jugador_id = $this->_jugador_id;
            $grupo_id = $request->getPost('grupo_id');
            $descripcion = trim(htmlentities($request->getPost('mensaje'), ENT_QUOTES));
            $db = $this->_mensaje->getAdapter();
            $db->beginTransaction();
            try {
                $this->_mensaje->insertar_mensaje($jugador_id, $grupo_id, $descripcion);
                $db->commit();
            } catch (Exception $error) {
                $db->rollBack();
                echo $error->getMessage();
            }
            $this->view->lista_muro = $this->_mensaje->listar_mensajes_grupo($grupo_id, 10);
        }
    }
    
    public function buscarAction() {        
        $_letters = range('A', 'Z');
        array_push($_letters, '0-9');        
        # inicio
        $letra = $this->_request->getQuery('letra');
        $q = $this->_request->getQuery('q');
        $data = array('q' => $q);
        # filtrar por letra
        if (in_array($letra, $_letters) && strlen($letra) > 0){
            $data['letra'] = $letra;
        }        
        $paginator = $this->_grupo->buscar($data);                        
        $letters = array();
        foreach ($_letters as $letter) {
            if ($letra == $letter)
                $letters[] = '<b>' . $letter . '</b>';
            else
                $letters[] = '<a href="/grupo/buscar?letra=' . $letter . '">' . $letter . '</a>';
        }
        $letters = implode(' - ', $letters);
        
        $this->view->paginator = $paginator;
        $this->view->assign(array(
            'q' => $q,            
            'letra' => $letra,
            'letters' => $letters
        ));        
        #mis manchas
        $this->view->usuarioGrupos = $this->_grupo->miembros_grupo(1, 1);
    }

    public function unirteAction() {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $grupo_id = $request->getPost('grupoid');
            $host     = $request->getHttpHost();
            if($grupo_id == null)
                $this->_redirect('/grupo/buscar/');
            // verificar si el jugador ya pertenece a este grupo
            $jugadorGrupo = new Default_Model_JugadorGrupo();
            $auth = Zend_Auth::getInstance();
            $jugador = $auth->getIdentity()->jugador_id;
            $message = "";
            if($jugadorGrupo->existeJugadorEnGrupo($jugador, $grupo_id, 2)){
                $message = "Ya perteneces a este grupo";            
            }else{
                $flag_aprobado = 0;
                $grupo = $this->_grupo->find($grupo_id)->current();
                if ($grupo->tipo > 0) {
                     $objgrupo = new stdClass();
                     $objgrupo->grupo_id    = $grupo->grupo_id;
                     $objgrupo->nombre      = $grupo->nombre;
                     $objgrupo->descripcion = $grupo->descripcion;
                     $objgrupo->tipo        = $grupo->tipo;
                     $objgrupo->foto        = $grupo->foto;
                    $admin_grupo = $jugadorGrupo->getAdmin($objgrupo);
                    $message = "Se ha enviado tu solicitud al Administrador";
                    #datos del usuario
                    #nombre_jugador y nombre_mancha
                    $obj = new stdClass();
                    $obj->jugador_id =  $this->_jugador_id;
                    $datjugador = $this->_jugador->getJugador($obj);
                    $arr_nombres  = explode(" ",$datjugador->nombres);
                    $arr_apellidos = explode(" ",$datjugador->apellidos);
                    $datgrupo = $this->_grupo->getGrupo($grupo_id);
                     $this->view->assign(array(
                         'admin'                       => utf8_decode($admin_grupo->nombres),
                         'nombre_persona' => utf8_decode($arr_nombres[0]) ." ".utf8_decode($arr_apellidos[0]),
                         'nombre_grupo'     => utf8_decode($datgrupo->nombre),
                         'host'               => "http://$host"
                     ));
                    $contentConfirmMail = $this->view->render('grupoConfirmMail.phtml');
                    # se necesita propio host
                    $mail = new Zend_Mail();
                    $mail->addHeader("MIME-Version", "1.0")
                         ->addHeader("Content-type", "text/html; charset=iso-8859-1")
                         ->setBodyHtml($contentConfirmMail)
                         ->addTo($admin_grupo->email, 'Some Recipient')
                         ->setSubject('Quieren unirse a tu grupo')
                         ->send();
                } else {
                    $message = "Ya eres parte de este grupo";
                    $flag_aprobado = 1;
                }
                $data = array(
                    'grupo_id' => $grupo_id,
                    'jugador_id' => $jugador,
                    'tipo' => 2,
                    'flag_aprobado' => $flag_aprobado
                );
                $jugadorGrupo->save($data);
            }
        }
        $this->_helper->json(array('msg' => $message));
    }

	public function unirtepopAction() {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $msg = $request->getPost('mensaje');
            $this->view->mensaje = $msg;
        }
    }
    
    public function requestAction() {
        $request = $this->getRequest();
        $flag_aprobado = $request->getPost('aprobado', false);
        $jugador_id = $request->getPost('jugador_id');
        $grupo_id = $request->getPost('grupo_id');

        $jugadorGrupo = new Default_Model_JugadorGrupo();

        $where = "jugador_id = '$jugador_id' AND grupo_id = '$grupo_id'";
        $data = $flag_aprobado ? array('flag_aprobado' => $flag_aprobado) : array('estado' => false);
        $jugadorGrupo->update($data, $where);
        $this->_helper->json(array('msj' => 'ok'));
    }

    public function mostrarAction() {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $jugador_id = $this->_jugador_id;
            $grupo_id = $request->getPost('grupo_id');
            $muro_id = $request->getPost('lastmuro_id');
            $this->view->lista_muro = $this->_mensaje->listar_mas_mensajes_grupo($grupo_id, $muro_id);
        }
    }

    public function comprobarAction() {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $nomgrupo = $request->getPost('nomgrupo');
            $res = $this->_grupo->comprobarNombre($nomgrupo);
            if($res){
               $msg = "El grupo <strong>".$nomgrupo."</strong> está en uso.";
            }else{
               $msg = "OK";
            }
            $this->_helper->json(array('msg' => $msg));
        }
    }

    public function aprobarpopAction() {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $grupo_id = $request->getPost('grupo_id');
            $solicitudes = $this->_jugador_grupo->solicitudesGrupo($grupo_id);
            $numsolicitudes = $this->_jugador_grupo->cantidad_integrantes($grupo_id, 0, 2, 1);
            $this->view->assign(array(
                'aprobar' => $solicitudes,
                'solicitudes' => $numsolicitudes,
                'grupo_id' => $grupo_id
            ));
        }        
    }

    public function confirmarpopAction() {
        if ($this->_request->isPost()) {
            $request = $this->getRequest();
            $grupo_id = $request->getPost('grupo_id');
            $jugador = $request->getPost('codjugador');
            $db = $this->_jugador_grupo->getAdapter();
            $db->beginTransaction();
            try {
                $res = $this->_jugador_grupo->aprobarSolicitudes($grupo_id, $jugador);
                $db->commit();
            } catch (Exception $error) {
                $db->rollBack();
                echo $error->getMessage();
            }
            $this->view->grupo = $grupo_id;
        }        
    }

}


