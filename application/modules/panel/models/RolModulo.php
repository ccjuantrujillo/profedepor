<?php

class Panel_Model_RolModulo extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_rolmodulo';

    public function init(){}

    public function listar(stdClass $filter = null){
        $select = $this->select()
            ->where('estado = ?', 1);

        if(isset($filter->rol_id) && $filter->rol_id > 0){
            $select->where('rol_id = ?', $filter->rol_id);
        }

        return $select->query();
    }

    public function saveMulti(array $modulos, $rol_id){
        parent::delete("rol_id = $rol_id");

        foreach($modulos as $modulo_id){
            $data = array('modulo_id' => $modulo_id, 'rol_id' => $rol_id);
            $this->insert($data);
        }

        return $this;
    }

    public function deleteRolMod($where){
        $this->delete($where);
    }
}

?>