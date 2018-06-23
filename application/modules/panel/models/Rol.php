<?php

class Panel_Model_Rol extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_rol';

    public function init(){}

    public function listar(stdClass $filter = null){
        $select = $this->select()
            ->where('estado = ?', 1);

        return $select->query();
    }

    public function listarOpcion(stdClass $filter = null){
        $adapter = $this->getAdapter();
        $fetch_mode = $adapter->getFetchMode();

        $select = $this->select()
            ->from($this->_name, array('key' => 'rol_id', 'value' => 'descripcion'))
            ->where('estado = ?', 1);

        $all = $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
        $adapter->setFetchMode($fetch_mode);

        return $all;
    }

    public function save($data, $model_rol_modulo){
        $rol_id = $data['rol_id'];
        $modulos = $data['modulos'];
        unset($data['rol_id'], $data['submit'], $data['modulos']);

        if($rol_id > 0){
            $data['modificacion'] = new Zend_Db_Expr('NOW()');
            $this->update($data, "rol_id = '$rol_id'");
        } else {
            $rol_id = $this->insert($data);
        }

        $model_rol_modulo->saveMulti($modulos, $rol_id);

        return $rol_id;
    }

    public function getRol($rol_id){
        return $this->select()
            ->where('rol_id = ?', $rol_id)
            ->where('estado = ?', 1)
            ->query()->fetch();
    }

    public function deleteRol($where){
        $this->delete($where);
    }
}

?>