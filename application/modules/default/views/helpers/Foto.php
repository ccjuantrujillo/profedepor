<?php

class Zend_View_Helper_Foto extends Zend_View_Helper_Abstract
{
    protected static $_path = null;
    public function Foto ($file){
        if(null === self::$_path)
            self::$_path = Zend_Registry::get('path');

        return self::$_path->fotos . $file .  '?l=' . mt_rand();
    }
}