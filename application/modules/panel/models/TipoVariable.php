<?php

class Panel_Model_TipoVariable extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_tipovariable';

    public function init(){}

    public function delete($where){
        $data = array(
            'estado' => 0
        );

        $this->update($data, $where);
    }

    public function listarOpciones(stdClass $filter = null){
        $select = $this->select()
            ->from($this->_name, array('key' => 'tipovariable_id', 'value' => 'descripcion'))
            ->where('estado = ?', 1);

        return $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
    }

    public function getTipoVariable($tipovariable_id){
        return $this->select()
            ->where('tipovariable_id = ?', $tipovariable_id)
            ->where('estado = ?', 1)
            ->query()->fetch();
    }
}

