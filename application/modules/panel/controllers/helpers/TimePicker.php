<?php
class Zend_Controller_Action_Helper_TimePicker extends Zend_Controller_Action_Helper_Abstract {
    public function direct($files = array()){
        if( ! is_array($files))
         $files = func_get_args();

        $ctrl = $this->getActionController();
        $path = Zend_Registry::get('path');

        $ctrl->view->headLink()->headLink()->appendStylesheet($path->css . 'timePicker.css');

        $script = $ctrl->view->headScript()->appendFile($path->js . 'date.js')
            ->appendFile($path->js . 'jquery.timePicker.min.js');

        foreach($files as $file)
            $script->appendFile($path->js . $file);
        return true;
    }
}
