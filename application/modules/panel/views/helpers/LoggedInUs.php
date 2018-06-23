<?php
class Zend_View_Helper_LoggedInUs extends Zend_View_Helper_Abstract
{
     public function loggedInUs()
     {
          $auth = Zend_Auth::getInstance();
          if($auth->hasIdentity()){
               $nombre = $auth->getIdentity()->nombre;
               $apellido = $auth->getIdentity()->apellido;
               return 'Bienvenido:'.$nombre.' '.$apellido;
          }
     }
}
?>
