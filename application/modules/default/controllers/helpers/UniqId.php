<?php

class Zend_Controller_Action_Helper_UniqId extends Zend_Controller_Action_Helper_Abstract {
    public function direct(){
	   return md5(uniqid(rand(), true));
    }
}

