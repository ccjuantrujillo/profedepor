<?php

class Default_Model_Red extends Zend_Db_Table_Abstract {

    protected $_name = 'depor_redsocial';

    public function listar(stdClass $filter = null){
        $select = $this->select()
            ->where('estado = ?', 1);

        return $select->query();
    }
}

