<?php

class Panel_LoginController extends Zend_Controller_Action
{
    protected $_auth = null;

    public function init()
    {
        Zend_Layout::getMvcInstance()->disableLayout();
        $this->_auth = Zend_Auth::getInstance();
    }

    public function indexAction()
    {
        $form = new Panel_Form_Login();
        $request = $this->getRequest();
        $message = '';

        if($request->isPost()){
            $formData = $request->getPost();
            if($form->isValid($formData)){
                $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table_Abstract::getDefaultAdapter());

                $authAdapter->setTableName('depor_usuario')
                            ->setIdentityColumn('login')
                            ->setCredentialColumn('password');

                $authAdapter->setCredentialTreatment('MD5(?)');

                $authAdapter->setIdentity($request->getPost('username'))
                            ->setCredential($request->getPost('password'));

                $result = $this->_auth->authenticate($authAdapter);
                if($result->isValid()){
                    $data = $authAdapter->getResultRowObject(null, 'password');
                    $this->_auth->getStorage()->write($data);
                    $this->_redirect('/panel/');
                } else {
                    $message = 'Combinaci&oacute;n de Datos err&oacute;nea.';
                }
            } else {
                $form->populate($formData);
            }
        }

        $this->view->login_error = $message;
        $this->view->form = $form;
    }

    public function logoutAction()
    {
        $this->_auth->clearIdentity();
        $this->_redirect('/panel/');
    }
}









