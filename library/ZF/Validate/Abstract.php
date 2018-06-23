<?php
abstract class ZF_Validate_Abstract extends Zend_Validate_Abstract {
    protected $_model = null;

    public function __construct(Zend_Db_Table_Abstract $model){
        $this->_model = $model;
    }
}

