<?php
class Default_Model_PuntajeFecha extends Zend_Db_Table {
    protected $_name = "depor_puntajefecha";

    public function getPuntajeJugador(stdClass $filter = null){

        $select = $this->select()
            ->from(array('PF' => $this->_name), array(
                'puntaje_juego', 'posicion' => new Zend_Db_Expr('(' .
                    $this->select()
                        ->from($this->_name, 'COUNT(*)')
                        ->where('puntaje_juego > PF.puntaje_juego')
                 . ')')
            ))
            ->where('PF.fecha_id = ?', $filter->fecha_id)
            ->where('PF.jugador_id = ?', $filter->jugador_id)
            ->order('posicion');

        # exit($select);

        $row = $select->query()->fetch();

        if( ! $row)
            $row = new stdClass();

        $puntaje_juego = 0;
        $posicion = null;
        if(isset($row->puntaje_juego) && $row->puntaje_juego > 0){
            $puntaje_juego = $row->puntaje_juego;
            $posicion = isset($row->posicion) && $row->posicion > 0 ? $row->posicion : 1;
        }

        $row->puntaje_juego = $puntaje_juego;
        $row->posicion = $posicion;

        return $row;
    }

    public function get_puntajefecha($fecha,$jugador){
        $db     = $this->getAdapter();
        $where  = $db->quoteInto('fecha_id=?',(int)$fecha)
                          . $db->quoteInto(' and jugador_id=?',(int)$jugador);
        $select = $this->select()
                      ->from('depor_puntajefecha')
                      ->where($where);
        $stmt   = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row    = $stmt->fetch();
        $resultado = array();
        if($row)
        {
            $resultado = $row;
        }
        return $resultado;
    }
}
