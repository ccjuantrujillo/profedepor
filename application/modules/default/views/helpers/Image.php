<?php

class Zend_View_Helper_Image extends Zend_View_Helper_Abstract
{
    protected static $_path = null;
    public function Image ($file){
        if(null === self::$_path)
            self::$_path = Zend_Registry::get('path');

        return self::$_path->images . $file;
    }
}