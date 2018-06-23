<?php

class Panel_Model_UsuarioRol extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_usuariorol';

    public function init(){
        $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
    }

    public function saveMulti(array $roles, $usuario_id){
        parent::delete("usuario_id = $usuario_id");

        foreach($roles as $rol_id){
            $data = array('rol_id' => $rol_id, 'usuario_id' => $usuario_id);
            $this->insert($data);
        }

        return $this;
    }

    public function listar(stdClass $filter = null){
        $select = $this->select()
            ->where('estado = ?', 1);
        if(isset($filter->usuario_id) && $filter->usuario_id>0)
            $select->where('usuario_id = ?', $filter->usuario_id);        
        if(isset($filter->rol_id) && $filter->rol_id>0)
            $select->where('rol_id = ?', $filter->rol_id);
        return $select->query();
    }

    public function getModulos($usuario_id){
        $cols = array('modulo_id', 'descripcion', 'controller');

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('UR' => $this->_name), null)
            ->join(array('RM' => 'depor_rolmodulo'), 'UR.rol_id = RM.rol_id AND RM.estado = 1', null)
            ->join(array('M' => 'depor_modulo'), 'RM.modulo_id = M.modulo_id AND M.estado = 1', $cols)
            ->where('UR.estado = ?', 1)
            ->where('UR.usuario_id = ?', $usuario_id)
            ->group('M.modulo_id');

        # exit($select);

        return $select->query();
    }

    public function deleteUsuarioRol($where){
        $this->delete($where);
    }
}
?>