<?php

class AyudaController extends Zend_Controller_Action
{
    public function init()
    {
        $request = $this->getRequest();
        $actionName = $request->getActionName();
        if($actionName != 'index' && ! $request->isXmlHttpRequest())
            exit('no access allow');
    }

    public function primeroAction(){}

    public function segundoAction(){}

    public function terceroAction(){}

    public function mapaAction(){
        # sleep(5);
    }

    public function indexAction(){
        $this->_helper->layout->setLayout('home');
    }

    public function modalAction(){}

    public function successmailAction(){}

    public function shareAction(){
        $request = $this->getRequest();
        if($request->isPost()){
            $formData = $request->getPost();

            $validators = array(
                'my_name' => 'NotEmpty',
                'my_email' => array('EmailAddress', 'NotEmpty'),
                'his_name' => 'NotEmpty',
                'his_email' => array('EmailAddress', 'NotEmpty')
            );

            $input = new ZF_Filter_Input($validators, $formData);
            if($input->valid($response)){

                $host = $request->getHttpHost();
                $this->view->assign(array(
                    'jugador' => $formData['my_name'],
                    'amigo' => $formData['his_name'],
                    'host' => $host,
                    'link' => "http://$host/"
                ));

                $contentConfirmMail = $this->view->render('invitacionMail.phtml');

                // enviar mail
                $mail = new Zend_Mail();
                $mail->addHeader("MIME-Version", "1.0")
                    ->addHeader("Content-type", "text/html; charset=iso-8859-1")
                    ->setBodyHtml($contentConfirmMail)
                    ->addTo($formData['his_email'], $formData['his_name'])
                    ->setFrom($formData['my_email'], $formData['my_name'])
                    ->setSubject("Invitacion de tu amigo {$formData['my_name']} El Profe Depor")
                    ->send();
            }

            $this->_helper->json($response);
        }
    }
}

