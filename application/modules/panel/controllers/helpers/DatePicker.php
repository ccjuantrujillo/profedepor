<?php

class Zend_Controller_Action_Helper_DatePicker extends Zend_Controller_Action_Helper_Abstract {
    public function direct($files = array()){
        if( ! is_array($files))
            $files = func_get_args();

        $ctrl = $this->getActionController();
        $path = Zend_Registry::get('path');

        $ctrl->view->headLink()->headLink()->appendStylesheet($path->css . 'datePicker.css');

        $script = $ctrl->view->headScript()->appendFile($path->js . 'date.js')
            ->appendFile($path->js . 'jquery.bgiframe.min.js', 'text/javascript', array('conditional' => 'IE'))
            ->appendFile($path->js . 'jquery.datePicker.js')
            ->appendFile($path->js . 'datePicker.js');

        foreach($files as $file)
            $script->appendFile($path->js . $file);
        return true;
    }
}

