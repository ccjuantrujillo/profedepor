<?php

class Zend_Controller_Action_Helper_NetWorkLogin extends Zend_Controller_Action_Helper_Abstract {
    public function direct($me){
        $jugadorRed = new Default_Model_JugadorRed();
        $model_jugador = new Default_Model_Jugador();

        // verificar si existe usuario en la base
        $filter = new stdClass();
        $filter->redsocial_user = $me->id;
        $filter->redsocial_id = $me->redsocial_id;
        $red_jugador = $jugadorRed->listar($filter)->fetch();

        $slogin = "window.opener.location = '/red/login/';";

        # si es twitter
        if($me->redsocial_id == 2)
            $script = 'window.opener.openRegistroTwitter();';
        else {
            # verificar si ya existe email
            $jugador = $model_jugador->select()
                ->where('email = ?', $me->email)
                ->where('estado = ?', 1)
                ->query();

            $existe_jugador = $jugador->rowCount() > 0;
            $jugador = $jugador->fetch();

            $script = $slogin;
        }

        # no existe jugadorRed
        if($red_jugador){
            $model_jugador->getJugador($red_jugador);

            $me->email = $red_jugador->email;
            $me->password = $red_jugador->password;

            $script = $slogin;
        } else {
            # si no es twitter
            if($me->redsocial_id != 2){
                # si existe email
                if($existe_jugador){
                    // registrar un nuevo vinculo al jugador
                    $me->jugador_id = $jugador->jugador_id;
                    # $me->password = $jugador->password;

                    $jugadorRed->save($me);
                } else {
                    $script = "window.opener.openTerminos();";
                }
            }
        }

        return $script;
    }
}

