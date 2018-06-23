<?php

class Panel_Model_TipoFecha extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_grupof';

    public function init(){}

    public function listarOpciones(stdClass $filter = null){
        $select = $this->select()
            ->from($this->_name, array('key' => 'grupof_id', 'value' => 'descripcion'))
            ->where('estado = ?', 1);

        return $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
    }

}

