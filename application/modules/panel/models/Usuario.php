<?php

class Panel_Model_Usuario extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_usuario';

    public function init(){}

    public function hasNick($nick){
        $select = $this->select()
            ->where('login = ?', $nick)
            ->where('estado = ?', 1);

        return $select->query()->rowCount() > 0;
    }

    public function hasEmail($email){
        $select = $this->select()
            ->where('email = ?', $email)
            ->where('estado = ?', 1);

        return $select->query()->rowCount() > 0;
    }

    public function save($data, $model_usuario_rol){
        $usuario_id = $data['usuario_id'];

        if(isset($data['password']) && strlen($data['password']) > 0)
            $data['password'] = md5($data['password']);
        else
            unset($data['password']);

        $roles = $data['roles'];
        unset($data['usuario_id'], $data['submit'], $data['roles']);

        if($usuario_id > 0){
            $data['modificacion'] = new Zend_Db_Expr('NOW()');
            $this->update($data, "usuario_id = '$usuario_id'");
        } else {
            $usuario_id = $this->insert($data);
        }

        $model_usuario_rol->saveMulti($roles, $usuario_id);

        return $usuario_id;
    }

    public function getUsuario($usuario_id){
        return $this->select()
            ->where('usuario_id = ?', $usuario_id)
            ->where('estado = ?', 1)
            ->query()->fetch();
    }

    public function deleteUsuario($where){
        $this->delete($where);
    }

    public function listar(stdClass $filter = null){
        $select = $this->select()
            ->where('estado = ?', 1);

        return $select->query();
    }
}

