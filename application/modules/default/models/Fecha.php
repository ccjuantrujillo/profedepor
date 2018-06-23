<?php

class Default_Model_Fecha extends Zend_Db_Table {

    protected $_name = "depor_fecha";

    private static $_pagSelect = null;

    public function init(){
        $this->getAdapter()->setFetchMode(Zend_Db::FETCH_OBJ);
    }

    public function getFecha($id) {
        $id = (int) $id;
        $row = $this->fetchRow('fecha_id=' . $id);
        if ($row) {
            $row = $this->fetchRow('fecha_id=' . $id);
        }
        return $row->toArray();
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

    private function _pagSelect(){
        if(null === self::$_pagSelect){
            self::$_pagSelect = $this->select()
                ->from($this->_name, 'fecha_id')
                ->where('estado = ?', 1)
                ->where('calculado = ?', 1)
                ->limit(1);
        }

        return clone self::$_pagSelect;
    }

    public function getPag($fecha_id){
        $prev = $this->_pagSelect()
            ->where('fecha_id < ?', $fecha_id)
            ->order('fecha_id DESC')
            ->query()
            ->fetch();

        $next = $this->_pagSelect()
            ->where('fecha_id > ?', $fecha_id)
            ->query()
            ->fetch();

        $rt = new stdClass();
        $rt->prev = isset($prev->fecha_id) ? $prev->fecha_id : '';
        $rt->next = isset($next->fecha_id) ? $next->fecha_id : '';

        return $rt;
    }

    public function listar(stdClass $filter = null){
        $partido = new Default_Model_Partido();

        $select = $this->select()
            ->from(array('F' => $this->_name), array(
                'fecha_id', 'descripcion',
                'fecha_partido' => new Zend_Db_Expr('(' .
                    $partido->_getPrimeraFechaSql('F.fecha_id')
                        ->where("fecha_id = F.fecha_id")
                . ')')
            ))
            ->where('F.situacion = ?', 1)
            ->where('F.estado = ?', 1);

        if(isset($filter->fecha_id) && $filter->fecha_id > 0){
            # $filter->fecha_id = $this->getFechaHoy();
            $fecha = $this->getFecha($filter->fecha_id);
            $select->where('F.fase_id = ?', $fecha['fase_id']);
               # ->join(array('P' => 'depor_partido'), 'F.fecha_id = P.fecha_id', array('fecha_partido'))
        }

        if(isset($filter->calculado))
            $select->where('F.calculado = ?', $filter->calculado);

        $select->order('F.descripcion DESC');

        # exit($select);

        return $select;
    }

    public function addFecha($torneo, $fase, $tipo, $descripcion, $intervalo) {
        $data = array(
            'torneo_id' => $torneo,
            'fase_id' => $fase,
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'intervalo' => $intervalo
        );
        $this->insert($data);
    }

    public function updateFecha($id, $tipo, $descripcion, $intervalo) {
        $data = array(
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'intervalo' => $intervalo
        );
        $this->update($data, 'fecha_id=' . (int) $id);
    }

    public function updateFechaSituacion($id, $situacion) {
        $data = array('situacion' => $situacion);
        $this->update($data, 'fecha_id=' . (int) $id);
    }

    public function updateFechaProceso($id, $fecha) {
        $data = array('fecha_proceso' => $fecha);
        $this->update($data, 'fecha_id=' . (int) $id);
    }

    public function deleteFecha($id) {
        $this->delete('fecha_id=' . (int) $id);
    }

    public function listar_fechas($fase) {
        $db = $this->getAdapter();
        $select = $this->select()
                         ->setIntegrityCheck(false)
                        ->from(array('f' => 'depor_fecha'),array("fecha_proceso2"=>"date_format(fecha_proceso,'%d/%m/%Y')","hora_proceso2"=>"date_format(fecha_proceso,'%H:%i:%s')","f.*"))
                        ->join(array('fs'=>'depor_fase'),'f.fase_id=fs.fase_id',array('descripcion2'=>'descripcion'))
                        ->where('f.fase_id=?',(int)$fase)
                        ->where('f.estado=?',1);
        $stmt = $select->query();
        $row = $stmt->fetchAll();
        return $row;
    }
    
    public function getRankingGrupoFecha($fecha_id, $grupo_id) {
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('PF' => 'depor_puntajefecha'), 'puntaje_total')
                    ->join(array('JG' => 'depor_jugadorgrupo'), 'JG.jugador_id = PF.jugador_id', 'PF.jugador_id')
                    ->join(array('DJ' => 'depor_jugador'), 'DJ.jugador_id = JG.jugador_id', array("SUBSTR(DJ.nombres,1,12) AS nomjugador"))
                    ->where('PF.fecha_id = ?', $fecha_id)
                    ->where('JG.grupo_id = ?', $grupo_id)
                    ->where('JG.flag_aprobado = ?', 1)
                    ->where('JG.estado = ?', 1)
                    ->order('PF.puntaje_total DESC')
                    ->limit(7, 0);
        $stmt   = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row    = $stmt->fetchAll();
        $resultado = array();
        if($row)
        {
            $resultado = $row;
        }
        if($row == null){
            $resultado = null;
        }
        return $resultado;
    }
    
    public function obtenerFechaPortada($id){
        $ufecha = $this->getFecha($id);
        $numfecha = substr($ufecha['descripcion'], 0, 2);
        $anio = substr($ufecha['fecha1'], 0, 4);
        $intervalo = explode(" ",$ufecha['intervalo']);
        if(count($intervalo)<4){
        	$fecha = $ufecha['intervalo']." de ".$anio;
        }else{        	
        	$fecha = $ufecha['intervalo']." de ".$anio;
        }    
        $objfecha = new stdClass();
        $objfecha->numfecha = $numfecha;
        $objfecha->fecha = $fecha;    
        return $objfecha;
    }
}
