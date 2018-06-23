<?php

class Default_Model_JugadorEmail extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_jugadoremail';

    public function init(){
        $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
    }

    public function hasEmailRegistro($email){
        return $this->select()->where('email = ?', $email)
            ->where('estado = ?', 1)
            ->query()->fetch();
    }

    public function save($data) {
        if(isset($data['email_id']) && $data['email_id'] > 0){
            $email_id = $data['email_id'];
            unset($data['email_id']);
            $this->update($data, "email_id = '$email_id'");
        } else {
            $email_id = $this->insert($data);
        }

        return $email_id;
    }

    public function getEmail(stdClass $jugador, $fields = '*') {
        $select = $this->select()
            ->from($this->_name, $fields);

        if(isset($jugador->jugador_id) && $jugador->jugador_id > 0)
            $select->where('jugador_id = ?', $jugador->jugador_id);

        $select->where('estado = ?', 1);

        return $select->query();
    }
}

