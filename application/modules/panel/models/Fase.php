<?php

class Panel_Model_Fase extends Zend_Db_Table_Abstract {

    protected $_name = 'depor_fase';

    public function init() {

    }

    public function save($data) {
        unset($data['submit']);
        unset($data['accion']);
        unset($data['txt_torneo_id']);
        if (isset($data['fase_id']) && $data['fase_id'] > 0) {
            $fase_id = $data['fase_id'];
            unset($data['fase_id']);
            unset($data['torneo_id']);
            $data['modificacion'] = $this->getFechaActual();
            $this->update($data, "fase_id = " . $fase_id);
        } else {
            unset($data['fase_id']);
            $fase_id = $this->insert($data);
        }
        return $fase_id;
    }

    public function listarOpciones(stdClass $filter = null) {
        $select = $this->select()
                        ->from($this->_name, array('key' => 'fase_id', 'value' => 'descripcion'))
                        ->where('estado = ?', 1);
        if (isset($filter->torneo_id) && $filter->torneo_id > 0)
            $select->where('torneo_id=?', $filter->torneo_id);
        return $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
    }

    public function listarFases(stdClass $filter = null) {
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('F' => $this->_name), array('fase_id', 'torneo_id', 'descripcion'))
                        ->join(array('T' => 'depor_torneo'),
                                'F.torneo_id = T.torneo_id', array(
                            'nombre_torneo' => 'T.descripcion'))
                        ->where('F.estado = ?', 1);

        if (isset($filter->torneo_id) && $filter->torneo_id > 0)
            $select->where('F.torneo_id = ?', $filter->torneo_id);

        return $select->query()->fetchAll();
    }

    public function listar_fases($torneo_id) {
        $select = $this->select()
                        ->from($this->_name, array('key' => 'fase_id', 'value' => 'descripcion'))
                        ->where('torneo_id = ?', $torneo_id)
                        ->where('estado = ?', 1);

        return $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
    }

    public function listarFasesTorneo($torneo_id) {
        $select = $this->select()
                        ->from($this->_name, array('fase_id', 'descripcion'))
                        ->where('torneo_id = ?', $torneo_id)
                        ->where('estado = ?', 1);

        return $select->query()->fetchAll();
    }

    public function cantidadFases() {
        $select = $this->select()
                        ->from('depor_fase', "COUNT(*) AS cantidad")
                        ->where('estado=?', 1);
        $stmt = $select->query();
        $row = $stmt->fetch();
        $resultado = array();
        if ($row) {
            $resultado = $row->cantidad;
        }
        return $resultado;
    }

    public function cantidadFasesTorneo($torneo_id) {
        $select = $this->select()
                        ->from('depor_fase', "COUNT(*) AS cantidad")
                        ->where('torneo_id=?', $torneo_id)
                        ->where('estado=?', 1);
        $stmt = $select->query();
        $row = $stmt->fetch();
        $resultado = array();
        if ($row) {
            $resultado = $row->cantidad;
        }
        return $resultado;
    }

    public function obtener_fase($id) {
        $select = $this->select()
                        ->where('fase_id=' . $id);
        $row = $select->query()->fetch();
        return $row;
    }

    public function getFase($fase_id) {
        $select = $this->select()
                        ->where('fase_id=?', $fase_id);
        $row = $select->query()->fetch();
        return $row;
    }

    public function insertar_fase($torneo, $descripcion) {
        $data = array(
            'descripcion' => $descripcion,
            'torneo_id' => $torneo
        );
        $id = $this->insert($data);
        return $id;
    }

    public function deleteFase($where) {
//        $mod = $this->getFechaActual();
//        $data = array(
//            'modificacion' => $mod,
//            'estado' => '0'
//        );
        echo $where;
        $this->delete($where);
    }

    public function fase_actual() {
        $select = $this->select()
                        ->where('estado=1')
                        ->where(' principal=1');
        $stmt = $select->query();
        $row = $stmt->fetch();
        return $row;
    }

    public function getFechaActual() {
        return date('Y-m-d h-i-s');
    }

    public function getFaseDefault($torneo_id) {
        $select = $this->select()
                        ->where('estado=?', 1)
                        ->where('torneo_id=?', $torneo_id)
                        ->limit(1);
        return $select->query()->fetch();
    }

}
