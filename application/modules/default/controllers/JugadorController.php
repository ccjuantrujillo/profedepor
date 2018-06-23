<?php

class JugadorController extends Zend_Controller_Action
{
    protected $_jugador = null,
              $_identity = null;

    public function init()
    {
        $this->_jugador = new Default_Model_Jugador();
        $this->_identity = Zend_Auth::getInstance()->getIdentity();
    }

    public function loggedinasAction(){}

    // actualizacion
    public function actualizadoAction(){}

    public function linkAction(){
        $jugadorRed = new Default_Model_JugadorRed();
        $model_red = new Default_Model_Red();

        $filter = new stdClass();
        $filter->jugador_id = $this->_identity->jugador_id;

        $redes = $model_red->listar();

        $redes_jugador = new stdClass();
        $redes_jugador->asoc = array();
        $redes_jugador->noasoc = array();

        $red_elmClass = array('icoEditCuenta_Fb', 'icoEditCuenta_Tw', 'icoEditCuenta_Gg');
        $red_elmId = array('btnFbLink', 'btnTwLink', 'btnGgLink');

        foreach($redes->fetchAll() as $red){
            $filter->redsocial_id = $red->redsocial_id;
            $jugador_red = $jugadorRed->listar($filter);

            $red->class = $red_elmClass[$red->redsocial_id-1];
            $red->id = $red_elmId[$red->redsocial_id-1];

            if($jugador_red->rowCount() > 0)
                $redes_jugador->asoc[] = $red;
            else
                $redes_jugador->noasoc[] = $red;
        }

        $this->view->redes_jugador = $redes_jugador;
    }

    public function emailAction()
    {
        $request = $this->getRequest();
        if( ! $request->isXmlHttpRequest())
            exit('404');

        $jugadorEmail = new Default_Model_JugadorEmail();

        if($request->isPost()){
            $data = $request->getPost();
            $validators = array(
                'otro_email' => array('EmailAddress', new ZF_Validate_HasEmail(true))
            );

            $input = new ZF_Filter_Input($validators, $data);
            if($input->valid($response)){
                $data = array(
                    'jugador_id' => $this->_identity->jugador_id,
                    'email' => $data['otro_email']
                );

                $jugadorEmail->save($data);
            }

            $this->_helper->json($response);
        }

        # get jugador emails
        $this->view->email = $jugadorEmail->getEmail($this->_identity, 'email');
    }

    public function registrofotoAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $path = Zend_Registry::get('path');

            $uploaded_file = new Zend_File_Transfer_Adapter_Http();
            $uploaded_file->addValidator('IsImage', false, 'jpeg,png,gif');
            #    ->addValidator('Size', false, array('max' => '512kB'));

            try {
                $uploaded_file->receive();

                $file = $uploaded_file->getFileInfo();

                $file_name = $this->_identity->jugador_id . '.jpg';
                $destino_foto = $path->destino_foto . $file_name;

                $session = new Zend_Session_Namespace();
                $session->user_foto = new stdClass();
                $session->user_foto->file_name = $file_name;

                require 'ThumbLib.inc.php';

                $thumb = PhpThumbFactory::create($file['fotoJugador']['tmp_name']);
                $thumb->resize(50, 45);
                $thumb->save($destino_foto);
                exit($file_name);
            } catch (Zend_File_Transfer_Exception $e) {
                # $e->getMessage();
            }
        }

        exit('fail');
    }

    public function popcambiarpassAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            // $this->_helper->json(array('message' => 'ok'));
            $old_password = $request->getPost('password');
            $new_password = $request->getPost('new_password');
            # $confirm_password = $request->getPost('confirm_password');

            $response = array('message' => 'ok');

            $ljugador = $this->_jugador->select()
                ->where('password = ?', md5($old_password))
                ->where('email = ?', $this->_identity->email)
                ->where('estado = ?', 1)
                ->query()
                ->fetch();

            if($ljugador){
                $data = array(
                    'session' => 0,
                    'GUID' => '',
                    'password' => md5($new_password)
                );

                $this->_identity->session = 0;

                $where = "jugador_id = '{$this->_identity->jugador_id}'";
                $this->_jugador->update($data, $where);
            } else
                $response = array(
                    'message' => 'Verifique que su contrase&ntilde;a sea correcta.',
                    'elem' => ''
                );

            $this->_helper->json($response);
        }
    }

    public function editarAction()
    {
        $session = new Zend_Session_Namespace();

        if($this->_request->isPost()){
            $formData = $this->_request->getPost();

            $filters = array(
                'jugador_id' => 'Digits',
                'club_id' => 'Digits'
            );

            $dni = array('Digits', new Zend_Validate_StringLength(8));

            $validators = array(
                'nombres' => 'NotEmpty',
                'apellidos' => 'NotEmpty',
                # 'email' => array('EmailAddress', 'NotEmpty'),
                'telefono' => array(new Zend_Validate_StringLength(7, 9), 'allowEmpty' => true),
                'numero_doc' => $dni,
                'dni_apoderado' => array_merge($dni, array('allowEmpty' => true))
            );

            $input = new ZF_Filter_Input($validators, $formData, $filters);
            if($input->valid($response)){
                # guardar
                $ubigeo_id = $formData['departamento'] . $formData['provincia'] . $formData['distrito'];
                $formData['ubigeo_id'] = $ubigeo_id;

                unset($formData['departamento'], $formData['provincia']);
                unset($formData['distrito'], $formData['otro_email']);

                # disable edit email
                if(isset($formData['email']))
                    unset($formData['email']);

                # session foto
                $user_foto = $session->user_foto;

                if(isset($user_foto->file_name) && strlen($user_foto->file_name) > 0){
                    $formData['foto'] = $user_foto->file_name;
                    $this->_identity->foto = $user_foto->file_name;
                }

                $this->_identity->nombres = $formData['nombres'];
                Zend_Auth::getInstance()->getStorage()->write($this->_identity);

                # validar si existe mail, reescribir session auth
                $this->_jugador->save($formData);
            }

            return $this->_helper->json($response);
        }

        Zend_Layout::getMvcInstance()->disableLayout();
        $session->user_foto = null;

        # $this->view->form = $form;
        $this->_jugador->getJugador($this->_identity);

        if(intval($this->_identity->ubigeo_id) > 0){
            $departamento = substr($this->_identity->ubigeo_id, 0, 2);
            $provincia = substr($this->_identity->ubigeo_id, 2, 2);
            $distrito = substr($this->_identity->ubigeo_id, 4, 2);
        } else {
            # ubigeo por defecto
            $departamento = '15';
            $provincia = '01';
            $distrito = '00';
        }

        # select club
        $model_club = new Default_Model_Club();
        $club = new Zend_Form_Element_Select('club_id', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('ViewHelper'),
            'multiOptions' => $model_club->listarOpciones(),
            'value' => $this->_identity->club_id
        ));

        # $path = Zend_Registry::get('path');

        $model_ubigeo = new Default_Model_Ubigeo();

        # departamentos
        $departamentos = $model_ubigeo->getDepartamentos();
        $_departamento = $this->_helper->ubigeo('departamento', $departamentos, $departamento);
        $_departamento->setAttrib('onchange', 'cambiar_departamento(this)');

        # provincias
        $provincias = $model_ubigeo->getProvincias($departamento);
        $_provincia = $this->_helper->ubigeo('provincia', $provincias, $provincia);
        $_provincia->setAttrib('onchange', 'cambiar_provincia(this)');

        # distritos
        $distritos = $model_ubigeo->getDistritos($departamento, $provincia);
        $_distrito = $this->_helper->ubigeo('distrito', $distritos, $distrito);

        $jugadorEmail = new Default_Model_JugadorEmail();
        $this->view->assign(array(
            'distrito' => $distrito,
            'departamento' => $_departamento,
            'provincia' => $_provincia,
            'distrito' => $_distrito,
            'jugador' => $this->_identity,
            'club' => $club,
            'email' => $jugadorEmail->getEmail($this->_identity, 'email')
        ));
    }
}


