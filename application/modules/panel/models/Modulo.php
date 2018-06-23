<?php

class Panel_Model_Modulo extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_modulo';

    public function init(){}

    public function listarOpcion(stdClass $filter = null){
        $adapter = $this->getAdapter();
        $fetch_mode = $adapter->getFetchMode();

        $select = $this->select()
            ->from($this->_name, array('key' => 'modulo_id', 'value' => 'descripcion'))
            ->where('estado = ?', 1);

        $all = $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
        $adapter->setFetchMode($fetch_mode);

        return $all;
    }

    public function listar(stdClass $filter = null){
        $select = $this->select()
            ->where('estado = ?', 1);

        return $select->query();
    }
}

?>