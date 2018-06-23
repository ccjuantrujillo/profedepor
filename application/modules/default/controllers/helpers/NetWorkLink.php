<?php

class Zend_Controller_Action_Helper_NetWorkLink extends Zend_Controller_Action_Helper_Abstract {
    public function direct($me){
        $jugadorRed = new Default_Model_JugadorRed();

        // verificar si existe usuario en la base
        $filter = new stdClass();
        $filter->redsocial_user = $me->id;
        $filter->redsocial_id = $me->redsocial_id;
        $red = $jugadorRed->listar($filter);

        $script = '';

        if(0 >= $red->rowCount()){
            $model_jugador = new Default_Model_Jugador();
            $identity = Zend_Auth::getInstance()->getIdentity();

            $me->jugador_id = $identity->jugador_id;
            $jugadorRed->save($me);

            $script .= 'window.opener.NetWorkLink();';
        }

        return $script;
    }
}

