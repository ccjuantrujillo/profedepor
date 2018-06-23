<?php

class Panel_Model_Juego extends Zend_Db_Table_Abstract {

    protected $_name = "depor_juego";

    public function actualizar_juego($fecha, $intervalo, $puntos) {
        $db = $this->getAdapter();
        $stmt = $db->query("CALL pns_actualizar_juego (?,?,?)", array($fecha, $intervalo, $puntos));
        $row = $stmt->fetch();
        $db->closeConnection();
        return $row;
    }

    public function existeIntervalo($intervalo_id) {
        $select = $this->select()
                        ->from($this->_name, "COUNT(*) AS cantidad")
                        ->where('intervalo_id=?', $intervalo_id)
                        ->where('estado=?', 1);
        $stmt = $select->query();
        $row = $stmt->fetch();
        $resultado = array();
        if ($row) {
            $resultado = $row->cantidad;
        }
        return $resultado;
    }

}
