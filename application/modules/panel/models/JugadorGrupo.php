<?php
class Panel_Model_JugadorGrupo extends Zend_Db_Table_Abstract
{
    protected $_name = "depor_jugadorgrupo";
    
    public function init (){}
    
    public function delete($jugadorgrupo_id)
    {
        $data = array('estado' => '0', 
        'modificacion' => new Zend_Db_Expr('NOW()'));
        $this->update($data, 'jugadorgrupo_id=' . (int) $jugadorgrupo_id);
    }
    
    public function jugadorAdmin ($jugador_id)
    {
        $select = $this->select()
            ->from($this->_name, "COUNT(*) AS cantidad")
            ->where('jugador_id=?', $jugador_id)
            ->where('tipo=?', 1)
            ->where('estado=?', 1);
        $row = $select->query()->fetch();
        return $row->cantidad;
    }
    
    public function gruposSeguidor ($jugador_id)
    {
        $select = $this->select()
            ->from($this->_name, "jugadorgrupo_id")
            ->where('jugador_id=?', $jugador_id)
            ->where('tipo=?', 2)
            ->where('estado=?', 1);
        $row = $select->query()->fetchAll();
        return $row;
    }
    
    public function gruposAdmin ($jugador_id)
    {
        $select = $this->select()
            ->from($this->_name, "jugadorgrupo_id")
            ->where('jugador_id=?', $jugador_id)
            ->where('tipo=?', 1)
            ->where('estado=?', 1);
        $row = $select->query()->fetchAll();
        return $row;
    }
}

