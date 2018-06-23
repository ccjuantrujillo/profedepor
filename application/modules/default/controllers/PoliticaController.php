<?php

class PoliticaController extends Zend_Controller_Action
{
    protected $_session = null;

    public function init()
    {
        # echo $this->_request->getHttpHost();
        $this->_session = new Zend_Session_Namespace();
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function redAction()
    {
        $request = $this->getRequest();
        $redsocial_id = @$this->_session->me->redsocial_id;
        if(0 >= $redsocial_id)
            exit('no existe session de red');

        # obtener datos red social
        $model_redsocial = new Default_Model_RedSocial();
        $redsocial = $model_redsocial->select()
            ->where('redsocial_id = ?', $redsocial_id)
            ->query();

        if(0 >= $redsocial->rowCount()){
            // mensaje
            exit('No existe red social');
        }

        $this->view->action_form = "/red/registro/";
    }


}



