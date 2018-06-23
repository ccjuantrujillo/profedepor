<?php
class Zend_Controller_Action_Helper_Jqtransform extends Zend_Controller_Action_Helper_Abstract
{
     public function direct()
     {
          $ctrl = $this->getActionController();
          $path = Zend_Registry::get('path');
          $ctrl->view->headLink()->headLink()->appendStylesheet($path->css.'jqtransform.css');
          $ctrl->view->headScript()->appendFile($path->js . 'jquery.jqtransform.js');
          return true;
     }
}
?>