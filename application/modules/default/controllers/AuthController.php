<?php

class AuthController extends Zend_Controller_Action
{

    protected $_jugador = null,
              $_session = null;

    public function init()
    {
        $this->_session = new Zend_Session_Namespace();
        $this->_jugador = new Default_Model_Jugador();
    }

    // registro
    public function successAction(){}

    public function isloginAction(){
        $response = array();

        if( ! Zend_Auth::getInstance()->hasIdentity())
            $response = array('message' => 'error');
        else
            $response = array('message' => 'ok');

        $this->_helper->json($response);
    }

    public function recuperarAction()
    {
        $password = $this->_request->getQuery('l');
        $jugador_id = $this->_request->getQuery('i');
        if(strlen($password) > 5 && $jugador_id > 0){
            $jugador = new stdClass();
            $jugador->jugador_id = $jugador_id;
            $jugador->password = $password;
            $this->_jugador->resetPassword($jugador);
        }

        $this->_redirect('/');
    }

    public function recoverpassAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $validators = array(
                'email' => array('EmailAddress', new ZF_Validate_HasEmail(true))
            );

            $formData = $request->getPost();

            $response = array('message' => 'No Existe Email en el Sistema.', 'elem' => '');
            $input = new Zend_Filter_Input(null, $validators, $formData);

            if( ! $input->isValid()){
                $filter = new stdClass();
                $filter->email = $formData['email'];
                $jugador = $this->_jugador->getByEmail($filter);

                # $jugador->guid = $this->_helper->UniqId();
                $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz";
                $password = "";
                for($i = 0; $i < 12; $i++)
                    $password .= substr($str, rand(0, 62), 1);

                # $this->_jugador->resetPassword($jugador);
                # $this->view->guid = $jugador->guid;
                $this->view->password = $password;
                $this->view->nombres = $jugador->nombres;
                $this->view->email = $jugador->email;
                $password_md5 = md5($password);
                $host = $this->_request->getHttpHost();
                $this->view->host = $host;
                $this->view->link = "http://$host/auth/recuperar?i=$jugador->jugador_id&l=$password_md5";
                $contentConfirmMail = $this->view->render('jugadorResetPassword.phtml');

                // enviar mail
                $mail = new Zend_Mail('UTF-8');
                $mail->addHeader("MIME-Version", "1.0")
                    ->addHeader("Content-type", "text/html; charset=UTF-8")
                    ->setBodyHtml($contentConfirmMail)
                    ->addTo($formData['email'], '')
                    ->setSubject('Cambio de contraseña Cuenta Profe Depor')
                    ->send();

                // echo $mail->getSubject();

                $response['message'] = 'ok';
            }

            $this->_helper->json($response);
        }

        $path = Zend_Registry::get('path');
        $this->view->headScript()->appendFile($path->js . 'rpassword.js');
    }

    public function registroAction()
    {
        $request = $this->getRequest();
        if( ! $request->isXmlHttpRequest())
            exit;

        # sleep(5);

        if($request->isPost()){
            $data = array(
                'nombres' => $request->getPost('name'),
                'apellidos' => $request->getPost('last_name'),
                'email' => $request->getPost('email'),
                'confirm_email' => $request->getPost('confirm_email'),
                'password' => $request->getPost('password'),
                'confirm_password' => $request->getPost('confirm_password')
            );

            $validators = array(
                # '*' => array('NotEmpty', 'Alpha'),
                'email' => array('EmailAddress', new ZF_Validate_HasEmail(true))
            );

            $input = new ZF_Filter_Input($validators, $data);
            if($input->valid($response)){
                $uniqId = $this->_helper->UniqId();
                $data['GUID'] = $uniqId;

                unset($data['confirm_password'], $data['confirm_email']);

                $password = $data['password'];
                $data['password'] = md5($password);

                try {
                    $jugador_id = $this->_jugador->save($data);
                } catch(Zend_Exception $e){
                    return $this->_helper->json(array('message' => 'Error al registrar.', 'elem' => ''));
                }

                $host = $request->getHttpHost();

                $data['password'] = $password;
                $this->view->assign(array(
                    'host' => $host,
                    'jugador' => $data,
                    'uId' => $uniqId,
                    'link' => "http://$host/auth/confirm?guid=$uniqId"
                ));

                $contentConfirmMail = $this->view->render('jugadorConfirmMail.phtml');

                // enviar mail
                $mail = new Zend_Mail('UTF-8');
                $mail->addHeader("MIME-Version", "1.0")
                    ->addHeader("Content-type", "text/html; charset=UTF-8")
                    ->setBodyHtml($contentConfirmMail)
                    ->addTo($data['email'], '')
                    ->setSubject('Confirmación de registro elprofedepor')
                    ->send();
            }

            $this->_helper->json($response);
        }
    }

    public function confirmAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $guid = $this->_request->getQuery('guid');
        if($row = $this->_jugador->getByGUID($guid)){
            $this->_request->setParam('login_email', $row->email)
                ->setParam('login_password', $row->password);

            return $this->_login(false);
        }

        # return $this->_redirect('/auth/login/');
        return $this->_forward('login', 'auth');
    }

    public function loginAction()
    {
        Zend_Layout::getMvcInstance()->disableLayout();

        # $form = new Default_Form_Login();
        $request = $this->getRequest();
        if($request->isPost()){
            # $formData = $this->_request->getPost();
            $email = $request->getPost('login_email');
            $password = $request->getPost('login_password');
            if(strlen($email) > 0 && strlen($password) > 0)
                $this->_login();
        } else
            $this->_redirect('/');
    }

    private function _login($md5 = true)
    {
        $request = $this->getRequest();
        $auth = Zend_Auth::getInstance();

        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table_Abstract::getDefaultAdapter());

        $authAdapter->setTableName('depor_jugador')
                    ->setIdentityColumn('email')
                    ->setCredentialColumn('password');

        if($md5)
            $authAdapter->setCredentialTreatment('MD5(?)');

        $authAdapter->setIdentity($request->getParam('login_email'))
                    ->setCredential($request->getParam('login_password'));

        $result = $auth->authenticate($authAdapter);
        if($result->isValid()){
            $data = $authAdapter->getResultRowObject(null, 'password');

            if($request->isPost()){
                if($data->session == 1 && strlen($data->GUID) > 0){
                    $this->view->login_error = 'Correo no a sido validado.';
                    $auth->clearIdentity();
                    $this->_forward('index', 'index');
                    return;
                }
            }

            $jugador = new stdClass();
            $jugador->jugador_id = $data->jugador_id;
            $this->_jugador->loggedInAs($jugador);

            $data->posicion = $data->puntaje > 0 ? $jugador->posicion : '';
            $data->cantidad_grupo = $jugador->cantidad_grupo;
            $auth->getStorage()->write($data);
            $this->_redirect('/portada/');
        } else {
            $this->view->login_error = 'Combinaci&oacute;n de Correo electr&oacute;nico err&oacute;nea.';
            $this->_forward('index', 'index');
        }

        return false;
    }

    public function logoutAction()
    {
        Zend_Session::namespaceUnset('fase1');
        Zend_Session::namespaceUnset('fase2');
        Zend_Session::namespaceUnset('fase3');
        $me = $this->_session->me;

        if(isset($me->logoutUrl) && strlen($me->logoutUrl) > 0){
            require_once 'fb/facebook.php';

            $fb = Zend_Registry::get('fb');

            $facebook = new Facebook(array(
                'base_domain' => $this->_request->getHttpHost(),
                'appId' => $fb->appId,
                'secret' => $fb->secret,
                'cookie' => true
            ));

            $facebook->setSession(null);
            $this->_redirect($me->logoutUrl);

            return false;
        }

        Zend_Auth::getInstance()->clearIdentity();

        $this->_redirect('/');
    }
}

