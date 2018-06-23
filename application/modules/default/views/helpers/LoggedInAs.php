<?php

class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract
{
    public function loggedInAs (){
        $jugador = Zend_Auth::getInstance()->getIdentity();
        $username = $jugador->nombres;
        $puntaje = $jugador->puntaje;

        $html = '<div class="userLeft">
                  <div class="avatar"><img width="50" height="45" src="' . $this->view->Foto($jugador->foto) . '" /></div>
                    <a href="javascript:;" onclick="openWindowDatosJugador()" class="btnGris75 left unitPng" style="margin-top:3px;">Editar perfil</a>
                </div> <!--fin userLeft-->
                <div class="userRight">
                    <p>Hola <span>' . $username .  '</span>, tienes <span>' . $puntaje
                . '</span> puntos, hinchadas <span>' . $jugador->cantidad_grupo . '</span>';

        if($puntaje > 0)
            $html .= ' y estas en el puesto <span>' . $jugador->posicion . '</span> general.';

        $html .= '</p>
                   <a href="/auth/logout/" class="btnGris75 right unitPng">Cerrar sesi&oacute;n</a>
				</div> <!--fin userRight-->';

        return $html;
    }
}