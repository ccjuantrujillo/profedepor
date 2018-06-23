<?php

class Panel_Model_Configuracion extends Zend_Db_Table_Abstract {

    protected $_name = "depor_configuracion";

    public function init() {

    }

    public function getConfiguracion() {
        return $this->select()
                ->from('depor_configuracion')
                ->limit(1)
                ->query()
                ->fetch();
    }

    public function save($data) {
        $config_id = $data['configuracion_id'];
        unset($data['configuracion_id']);
        $this->update($data, "configuracion_id = " . $config_id);
        return $config_id;
    }

    public function listarConfiguraciones(stdClass $filter = null) {
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('C' => $this->_name), array(
                            'configuracion_id', 'torneo_id', 'fase_id', 'tolerancia'))
                        ->join(array('T' => 'depor_torneo'),
                                'T.torneo_id = C.torneo_id', array(
                            'nom_torneo' => 'T.descripcion'))
                        ->join(array('F' => 'depor_fase'),
                                'F.fase_id = C.fase_id', array(
                            'nom_fase' => 'F.descripcion'))
                        ->where('T.estado = ?', 1)
                        ->where('F.estado = ?', 1);

        if (isset($filter->fase_id) && $filter->fase_id > 0)
            $select->where('C.fase_id = ?', $filter->fase_id);

        if ($filter->fase_id > 0)
            return $select->query()->fetchAll();
        else
            return null;
    }

    public function listarConfigs() {
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('C' => $this->_name), array(
                            'configuracion_id', 'torneo_id', 'fase_id', 'tolerancia'))
                        ->join(array('T' => 'depor_torneo'),
                                'T.torneo_id = C.torneo_id', array(
                            'nom_torneo' => 'T.descripcion'))
                        ->join(array('F' => 'depor_fase'),
                                'F.fase_id = C.fase_id', array(
                            'nom_fase' => 'F.descripcion'))
                        ->where('T.estado = ?', 1)
                        ->where('F.estado = ?', 1);

        return $select->query()->fetchAll();
    }

    public function cantidadConfiguraciones() {
        $select = $this->select()
                        ->from('depor_configuracion', "COUNT(*) AS cantidad");
        //->where('estado=?', 1);
        $stmt = $select->query();
        $row = $stmt->fetch();
        return $row->cantidad;
    }

    public function obtener_config($config_id) {
        $select = $this->select()
                        ->where('configuracion_id=?', $config_id);
        $row = $select->query()->fetch();
        return $row;
    }

}

?>
