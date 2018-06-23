<?php
class Panel_Model_Partido extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_partido';
    private static $_primeraFechaSql = null;

    public function init(){}

    public function _getPrimeraFechaSql(){
        if(self::$_primeraFechaSql === null){
            self::$_primeraFechaSql = $this->select()
                ->from($this->_name, array('fecha_partido' => "DATE_FORMAT(fecha_partido, '%d/%m/%Y')"))
                ->order('fecha_partido ASC')
                ->limit(1);
        }

        return clone self::$_primeraFechaSql;
    }

    public function getPrimeraFecha($fecha_id){
        return $this->_getPrimeraFechaSql()
            ->where("fecha_id = $fecha_id")
            ->query()->fetch();
    }

    public function save($data){
        $arrFecha            = explode("/",$data['fecha_partido']);
        $fecha_partido = $arrFecha[2]."-".$arrFecha[1]."-".$arrFecha[0];
        $data2['torneo_id'] = $data['torneo_id'];
        $data2['fase_id']      = $data['fase_id'];
        $data2['fecha_id']   = $data['fecha_id'];
        $data2['equipo_local'] = $data['equipo_local'];
        $data2['equipo_visitante'] = $data['equipo_visitante'];
        $data2['fecha_partido']       = $fecha_partido." ".$data['hora_partido'].":00";
        $data2['fase1']                         = $data['fase1'];
        $data2['fase2']                         = $data['fase2'];
        $data2['fase3']                         = $data['fase3'];
        unset($data['submit']);
            $oJuego                                     = new Default_Model_Juego();
        if(isset($data['partido_id']) && $data['partido_id'] > 0){
             $partido_id = $data['partido_id'];
             $oJuego->deleteJuego("partido_id = '$partido_id'");
             $oJuego->insertar_juego($partido_id);
           $this->update($data2, "partido_id = '$partido_id'");
        } else {
            $partido_id                               = $this->insert($data2);
            $oJuego->insertar_juego($partido_id);
        }
        return $partido_id;
    }

    public function deletePartido($where){
        $this->delete($where);
        return true;
    }

	public function listar($fase){
		$select = $this->select()
                ->setIntegrityCheck(false)
				->from(array('p'=>'depor_partido'),array('p.partido_id','p.fecha_id','fecha_partido'=>"date_format(p.fecha_partido,'%d/%m/%Y %H:%i:%s')",'p.fase1','p.fase2','p.fase3','p.situacion'))
                ->join(array('el'=>'depor_equipo'),'p.equipo_local=el.equipo_id')
                ->join(array('ev'=>'depor_equipo'),'p.equipo_visitante=ev.equipo_id')
                ->join(array('cl'=>'depor_club'),'el.club_id=cl.club_id',array('local'=>'cl.descripcion','icono_local'=>'cl.icono'))
                ->join(array('cv'=>'depor_club'),'ev.club_id=cv.club_id',array('visitante'=>'cv.descripcion','icono_visitante'=>'cv.icono'))
				->where('p.estado=?',1);
      if(isset($fase->torneo_id) && $fase->torneo_id>0)
              $select->where('p.torneo_id='.$fase->torneo_id);
       if(isset($fase->fase_id) && $fase->fase_id>0)
			$select->where('p.fase_id='.$fase->fase_id);
       if(isset($fase->fecha_id) && $fase->fecha_id>0)
            $select->where('p.fecha_id='.$fase->fecha_id);
		$stmt   = $select->query();
		return $stmt->fetchAll();
	}

    public function getPartido(stdClass $filter = null, stdClass $params = null){
        if(!isset($params->fecha_format)){
        	$params = new stdClass();
            $params->fecha_format = '%d/%m/%Y %r';
        }
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('P' => $this->_name), array(
                'fecha_partido' => "DATE_FORMAT(fecha_partido, '$params->fecha_format')",
                'situacion', 'partido_id', 'fecha_id', 'ficha'
            ))
            ->join(array(
                'F' => 'depor_fecha'
            ), 'P.fecha_id = F.fecha_id', array(
                'fecha' => 'descripcion',
                'intervalo' => 'intervalo'
            ))

            ->join(array(
                'C' => 'depor_club'
            ), 'P.equipo_local = C.club_id', array(
                'local' => 'C.descripcion',
                'icono_local' => 'C.icono'
            ))

            ->join(array(
                'C2' => 'depor_club'
            ), 'P.equipo_visitante = C2.club_id', array(
                'visitante' => 'C2.descripcion',
                'icono_visitante' => 'C2.icono'
            ))

            ->join(array(
                'R' => 'depor_resultado'
            ), 'P.partido_id = R.partido_id', array('goles_local', 'goles_visita')
            )

            ->where('P.estado = ?', 1);

        if(isset($filter->fase_id) && $filter->fase_id > 0)
            $select->where('P.fase_id = ?', $filter->fase_id);

        if(isset($filter->fecha_id) && $filter->fecha_id > 0)
            $select->where('P.fecha_id = ?', $filter->fecha_id);

        if(isset($filter->partido_id) && $filter->partido_id > 0)
            $select->where('P.partido_id = ?', $filter->partido_id);

        # exit($select);
        return $select->query();
    }

    public function existenPartidosFecha($fecha_id){
        $select = $this->select()
                      ->from($this->_name, "COUNT(*) AS cantidad")
                      ->where('fecha_id=?',$fecha_id)
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

    public function existenPartidosEquipo($equipo_id){
        $db     = $this->getAdapter();
        $where  = $db->quoteInto('equipo_local=?',$equipo_id)
                . $db->quoteInto(' OR equipo_visitante=?',$equipo_id);
        $select = $this->select()
                      ->from($this->_name, "COUNT(*) AS cantidad")
                      ->where($where)
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

    public function existenPartidosTorneo($torneo_id){
        $select = $this->select()
                      ->from($this->_name, "COUNT(*) AS cantidad")
                      ->where('torneo_id=?',$torneo_id)
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

    public function existenPartidosFase($fase_id){
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