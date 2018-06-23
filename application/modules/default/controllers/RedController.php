<?php

class RedController extends Zend_Controller_Action
{

    protected $_session = null;

    public function init()
    {
        $this->_session = new Zend_Session_Namespace();
    }

    public function publishAction()
    {
        $this->_helper->viewRenderer->setNoRender();

        $controller = '';
        switch($this->_session->redsocial_id){
            case 1: $controller = 'facebook'; break;
            case 2: $controller = 'twitter'; break;
            case 3: $controller = 'google'; break;
        }

        if(strlen($controller) > 0)
            $this->_forward('publish', $controller);

        # $this->_session->my_friends = null;
        # $this->_session->redsocial_id = null;
    }

    public function getfriendsAction()
    {
        # javascript: getRedFriendsModal(); void 0
        Zend_Layout::getMvcInstance()->disableLayout();

        if( ! isset($this->_session->my_friends))
            return false;

        $my_friends = $this->_session->my_friends;

        $cantidad_friends = count($my_friends);

        $cantidad_x_pag = 12;
        $cantidad_pags = ceil($cantidad_friends / $cantidad_x_pag);
        $pag = $this->_request->getQuery('pag', 1);

        $from = $pag > 0 ? ($pag-1) * $cantidad_x_pag : 0;
        $my_friends = array_slice($my_friends, $from, $cantidad_x_pag);

        $this->view->assign(array(
            'my_friends' => $my_friends,
            'cantidad_pags' => $cantidad_pags,
            'pag' => $pag
        ));
    }

    public function getfriendsmodalAction()
    {
        Zend_Layout::getMvcInstance()->disableLayout();
    }

    private function _login()
    {
        if( ! isset($this->_session->me))
            exit('no existe session de red');

        $me =& $this->_session->me;

        $request = $this->getRequest();
        $auth = Zend_Auth::getInstance();

        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table_Abstract::getDefaultAdapter());

        $authAdapter->setTableName('depor_jugador')
            ->setIdentityColumn('email')
            ->setCredentialColumn('password');

        $authAdapter->setIdentity($me->email)
            ->setCredential($me->password);

        $result = $auth->authenticate($authAdapter);
        if($result->isValid()){
            $data = $authAdapter->getResultRowObject(null, 'password');

            $jugador = new stdClass();
            $_jugador = new Default_Model_Jugador();

            $jugador->jugador_id = $data->jugador_id;
            $_jugador->loggedInAs($jugador);

            $data->posicion = intval($jugador->posicion);
            $data->cantidad_grupo = intval($jugador->cantidad_grupo);
            $auth->getStorage()->write($data);
        }

        $me = null;

        $this->_redirect('/');
    }

    public function registroAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $model_jugador = new Default_Model_Jugador();
            $jugadorRed = new Default_Model_JugadorRed();

            $me =& $this->_session->me;
            $me->password = md5($me->password);

            $data = array(
                'nombres' => $me->first_name,
                'apellidos' => $me->last_name,
                'email' => $me->email,
                'password' => $me->password
            );

            $me->jugador_id = $model_jugador->save($data);

            $jugadorred_id = $jugadorRed->save($me);
            $this->_login();
        }

        $this->_redirect('/');
    }

    public function loginAction()
    {
        if( ! isset($this->_session->me)){
            $this->_redirect('/');
        }

        $this->_login();
    }
}







