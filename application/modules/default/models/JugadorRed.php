<?php

class Default_Model_JugadorRed extends Zend_Db_Table_Abstract {

    protected $_name = 'depor_jugadorred';

    public function save($jugadorRed){
        $data = array(
            'redsocial_id' => $jugadorRed->redsocial_id,
            'jugador_id' => $jugadorRed->jugador_id,
            'redsocial_user' => $jugadorRed->id
        );

        return $this->insert($data);
    }

    public function listar(stdClass $filter = null){
        $select = $this->select()
            ->where('estado = ?', 1);

        if(isset($filter->jugador_id) && $filter->jugador_id > 0)
            $select->where('jugador_id = ?', $filter->jugador_id);

        if(isset($filter->redsocial_id) && $filter->redsocial_id > 0)
            $select->where('redsocial_id = ?', $filter->redsocial_id);

        if(isset($filter->redsocial_user) && $filter->redsocial_user > 0)
            $select->where('redsocial_user = ?', $filter->redsocial_user);

        # exit($select);

        return $select->query();
    }
}

