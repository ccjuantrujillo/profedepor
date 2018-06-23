<?php

class Panel_Model_Torneo extends Zend_Db_Table_Abstract {

    protected $_name = 'depor_torneo';

    public function init() {

    }

    public function save($data) {
        unset($data['submit']);
        if (isset($data['torneo_id']) && $data['torneo_id'] > 0) {
            $torneo_id = $data['torneo_id'];
            unset($data['torneo_id']);
            $data['modificacion'] = $this->getFechaActual();
            $this->update($data, "torneo_id = " . $torneo_id);
        } else {
            unset($data['torneo_id']);
            $torneo_id = $this->insert($data);
        }
        return $torneo_id;
    }

    public function listar_torneos() {
        $select = $this->select()
                        ->from('depor_torneo')
                        ->where('estado=?', 1);
        $row = $select->query()->fetchAll();
        return $row;
    }

    public function listarOpciones(stdClass $filter = null) {
        $select = $this->select()
                        ->from($this->_name, array('key' => 'torneo_id', 'value' => 'descripcion'))
                        ->where('estado = ?', 1);

        return $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
    }

    public function cantidad_torneos() {
        $select = $this->select()
                        ->from('depor_torneo', "COUNT(*) AS cantidad")
                        ->where('estado=?', 1);
        $stmt = $select->query();
        $row = $stmt->fetch();
        $resultado = array();
        if ($row) {
            $resultado = $row->cantidad;
        }
        return $resultado;
    }

    public function obtener_torneo($torneo_id) {
        return $this->select()
                ->where('torneo_id = ?', $torneo_id)
                ->where('estado = ?', 1)
                ->query()
                ->fetch();
    }

    public function deleteTorneo($where) {
        $this->delete($where);
    }

    public function getFechaActual() {
        return date('Y-m-d h-i-s');
    }

}
