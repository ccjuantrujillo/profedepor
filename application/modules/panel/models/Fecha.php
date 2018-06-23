<?php

class Panel_Model_Fecha extends Zend_Db_Table_Abstract {

    protected $_name = 'depor_fecha';

    public function init() {

    }

    public function listarOpciones(stdClass $filter = null) {
        $select = $this->select()
                        ->from($this->_name, array('key' => 'fecha_id', 'value' => 'descripcion'))
                        ->where('estado = ?', 1);
        if (isset($filter->torneo_id) && $filter->torneo_id > 0)
            $select->where('torneo_id=?', $filter->torneo_id);
        if (isset($filter->fase_id) && $filter->fase_id > 0)
            $select->where('fase_id = ?', $filter->fase_id);

        return $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
    }

    public function getFechaHoy() {
        $now = date('Y-m-d h:i:s');

        $row = $this->select()
                        ->from('depor_fecha', 'fecha_id')
                        ->where('? >= fecha1', $now)
                        ->where('? < fecha2', $now)
                        ->query()
                        ->fetch();
        return $row->fecha_id;
    }

    public function listarFechas2(stdClass $filter = null) {
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('F' => $this->_name), array(
                            'fecha_id', 'descripcion', 'intervalo', 'grupo_id', 'situacion', 'calculado'))
                        ->join(array('DF' => 'depor_fase'),
                                'F.fase_id = DF.fase_id', array(
                            'nombre_fase' => 'DF.descripcion'))
                        ->join(array('T' => 'depor_torneo'),
                                'F.torneo_id = T.torneo_id', array(
                            'nombre_torneo' => 'T.descripcion'))
                        ->where('F.estado = ?', 1);

        if (isset($filter->torneo_id) && isset($filter->fase_id) &&
                $filter->torneo_id > 0 && $filter->fase_id > 0)
            $select->where('F.torneo_id = ?', $filter->torneo_id)
                    ->where('F.fase_id = ?', $filter->fase_id);

        return $select->query()->fetchAll();
    }

    public function listarFechas(stdClass $filter = null) {
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('F' => $this->_name), array(
                            'fecha_id', 'descripcion', 'intervalo', 'grupo_id', 'situacion', 'calculado'))
                        ->join(array('DF' => 'depor_fase'),
                                'F.fase_id = DF.fase_id', array(
                            'nombre_fase' => 'DF.descripcion'))                        
                        ->where('F.estado = ?', 1);

        if (isset($filter->fase_id) && $filter->fase_id > 0)
            $select->where('F.fase_id = ?', $filter->fase_id);

        return $select->query()->fetchAll();
    }

    public function cantidadFechas($torneo_id, $fase_id) {
        $select = $this->select()
                        ->from($this->_name, "COUNT(*) AS cantidad")
                        ->where('torneo_id=?', $torneo_id)
                        ->where('fase_id=?', $fase_id)
                        ->where('estado=?', 1);
        $stmt = $select->query();
        $row = $stmt->fetch();
        $resultado = array();
        if ($row) {
            $resultado = $row->cantidad;
        }
        return $resultado;
    }

    public function obtener_fecha($fecha_id) {
        $select = $this->select()
                        ->from(array('DF' => $this->_name), array('fecha_id',
                            'fase_id', 'torneo_id', 'tipo',
                            'descripcion', 'intervalo', 'grupo_id',
                            "DATE_FORMAT(fecha1,'%d/%m%/%Y') AS fecha1",
                            "DATE_FORMAT(fecha2,'%d/%m%/%Y') AS fecha2"))
                        ->where('fecha_id=?', $fecha_id);
        $row = $select->query()->fetch();
        return $row;
    }

    public function save($data) {
        unset($data['submit'],
                $data['accion'],
                $data['txt_torneo_id'],
                $data['txt_fase_id']);
        $f1 = $this->formatoFecha($data['fecha1'], 1);
        $f2 = $this->formatoFecha($data['fecha2'], 2);
        unset($data['fecha1']);
        unset($data['fecha2']);
        $data['fecha1'] = $f1;
        $data['fecha2'] = $f2;
        if (isset($data['fecha_id']) && $data['fecha_id'] > 0) {
            $fecha_id = $data['fecha_id'];
            unset($data['fecha_id']);
            unset($data['torneo_id']);
            unset($data['fase_id']);
            $data['modificacion'] = $this->getFechaActual();
            $this->update($data, "fecha_id = " . $fecha_id);
        } else {
            unset($data['fecha_id']);            
            $fecha_id = $this->insert($data);
        }
        return $fecha_id;
    }

    public function formatoFecha($fecha, $tipo) {
        $dato = explode("/", substr($fecha, 0, 10));
        if ($tipo == 1)
            $hora = "00:00:00";
        if ($tipo == 2)
            $hora = "23:59:59";

        $nuevo = "$dato[2]-$dato[1]-$dato[0]" . " " . $hora;

        return $nuevo;
    }

    public function delete($where) {
        $mod = $this->getFechaActual();
        $data = array(
            'modificacion' => $mod,
            'estado' => '0'
        );
        $this->update($data, $where);
    }

    public function getFechaActual() {
        return date('Y-m-d h-i-s');
    }

    public function listarFases(stdClass $filter = null) {
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('F' => $this->_name), 'F.fecha_id')
                        ->join(array('DF' => 'depor_fase'),
                                'F.fase_id = DF.fase_id', array(
                            'key' => 'DF.fase_id',
                            'value' => 'DF.descripcion'))
                        ->where('F.estado = ?', 1);

        if (isset($filter->torneo_id) && $filter->torneo_id > 0)
            $select->where('F.torneo_id = ?', $filter->torneo_id);

        return $select->query()->fetchAll();
    }

    public function listar_fases(stdClass $filter = null) {
        $fases = $this->listarFases($filter);
        $opcion = array();
        foreach ($fases as $f) {
            $opcion[] = array(
                'key' => $f->key,
                'value' => $f->value);
        }

        return $opcion;
    }

    public function existenFases($fase_id){
        $select = $this->select()
                      ->from($this->_name, "COUNT(*) AS cantidad")
                      ->where('fase_id=?',$fase_id)
                      ->where('estado=?',1);
        $stmt   = $select->query();
        $row    = $stmt->fetch();
        $resultado = array();
        if($row)
        {
            $resultado = $row->cantidad;
        }
        return $resultado;
    }

}
