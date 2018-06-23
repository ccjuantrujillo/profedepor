<?php

class Default_Model_Jugador extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_jugador';

    protected $_dependetTables = array('jugadorGrupo');

    public function resetPassword($jugador){
        $where = "jugador_id = '$jugador->jugador_id' AND estado = 1";
        $this->update(array('password' => $jugador->password, 'session' => 2), $where);
    }

    public function getJugador(&$jugador){
        $jugador = $this->select()
            ->where('jugador_id = ?', $jugador->jugador_id)
            ->where('estado = ?', 1)
            ->query()
            ->fetch();

        return $jugador;
    }

    public function loggedInAs(&$jugador){
        $jugador_grupo = new Default_Model_JugadorGrupo();
        $jugador = $this->select()
            ->from(array('J' => $this->_name), array(
                'posicion' => new Zend_Db_Expr('(' . $this->select()
                    ->from($this->_name, 'COUNT(*)+1')
                    ->where('puntaje > J.puntaje') . ')'),
                'cantidad_grupo' => new Zend_Db_Expr('(' . $jugador_grupo->select()
                    ->from('depor_jugadorgrupo', 'COUNT(*)')
                    ->where('jugador_id = J.jugador_id') . ')')
            ))
            ->where('J.jugador_id = ?', $jugador->jugador_id)
            ->where('J.estado = ?', 1)
            ->query()->fetch();

        return $jugador;
    }

    public function getByEmail(stdClass $filter){
        return $this->select()->where('email = ?', $filter->email)
            ->where('estado = ?', 1)
            ->query()->fetch();

        # ->where('session != ?', 1) # diferente que registro
    }

    public function hasEmailRegistro($email){
        return $this->select()->where('email = ?', $email)
            ->where('estado = ?', 1)
            ->query()->fetch();
    }

    public function puntajeGrupoSQL(){
        return new Zend_Db_Expr('(' . $this->select()
            ->from(array('DJ' => $this->_name), null)
            ->columns('SUM(DJ.puntaje)')
            ->join(array('JG' => 'depor_jugadorgrupo'), 'DJ.jugador_id = JG.jugador_id', null)
            ->where('JG.grupo_id = DG.grupo_id') . ')');
    }

    public function getByGUID($GUID){
        return $this->select()->where('GUID = ?', $GUID)
            # ->where('session = ?', 1)
            ->where('estado = ?', 1)
            ->query()->fetch();
    }

    /**
     * @param jugador
     * desabilita el guid y setea como usuario que ya ingreso
     */
    public function firstSession($jugador){
        $data = array('GUID' => '', 'session' => '0');
        $jugador->session = 0;
        return $this->update($data, "jugador_id = '$jugador->jugador_id'");
    }

    public function getGrupoJugador($jugador_id){
        $jugador = Zend_Auth::getInstance()->getIdentity();

        return $this->select()
            ->setIntegrityCheck(false)
            ->from(array('DJ' => $this->_name), null)
            ->join(array('JG' => 'depor_jugadorgrupo'), 'DJ.jugador_id = JG.jugador_id')
            ->where('JG.grupo_id = ?', $jugador_id)
            ->where('JG.tipo = ?', 1)
            ->query()
            ->fetch();
    }

    public function save($data) {
        if(isset($data['jugador_id']) && $data['jugador_id'] > 0){
            $jugador_id = $data['jugador_id'];
            unset($data['jugador_id']);
            $this->update($data, "jugador_id = '$jugador_id'");
        } else {
            # por defecto dni
            $data['tipodoc_id'] = 1;
            $data['ubigeo_id'] = '000000';
            $data['session'] = 1;
            $data['estado'] = 1;
            # $data['GUID'] = '';

            # try {
                $jugador_id = $this->insert($data);
            # } catch(Zend_Db_Exception $e){
            #    $message = new Zend_Session_Namespace('message');
            #    $message->message =
            #    return false;
            # }

            $grupo = new Default_Model_JugadorGrupo();

            $data = array(
                'grupo_id' => 1,
                'jugador_id' => $jugador_id,
                'tipo' => 2
            );

            $grupo->save($data);
        }

        return $jugador_id;
    }

    public function rankingMinGeneral($limite) {
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->from($this->_name,
                            array('jugador_id',"SUBSTR(nombres,1,12) AS nomjugador", 'puntaje'))
                    ->where('estado = ?', 1);
        if($this->countRankingGeneral() == 0)            
        	$select->where('puntaje > ?', 0);
        	
        $select->order('puntaje DESC')
               ->order('registro')
               ->limit($limite, 0);
          
        $stmt   = $select->query();
        $row    = $stmt->fetchAll();        
        return $row;
    }

    public function rankingGeneral() {
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->from($this->_name,
                            array('jugador_id',"nombres AS nomjugador", 'puntaje'))
                    ->where('estado = ?', 1);
                    
        if($this->countRankingGeneral() == 0)            
        	$select->where('puntaje > ?', 0);
        	
        $select->order('puntaje DESC')
               ->order('registro');
               
        $stmt   = $select->query();
        $row    = $stmt->fetchAll();        
        return $row;
    }
    
    public function posRanking($jugador_id){
    	$jugadores = $this->rankingGeneral();
    	$posicion = 0;
    	foreach ($jugadores as $i => $j){	
    		if($j->jugador_id == $jugador_id){
    			$posicion = $i+1;
    			break;
    		}    			
    	}
    	return $posicion;
    }

    public function cantidad_jugadores() {
        $select =$this->select()
                ->from($this->_name,array('COUNT(*) AS cantidad'))
                ->where('estado = ?', 1)
                ->query();
        $result = $select->fetch();
		return $result;
    }
    
    public function obtenerRankingFecha($fecha_id, $limite) {
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('DJ' => $this->_name), array('DJ.jugador_id', 'DJ.nombres AS nomjugador'))
                    ->join(array('PF' => 'depor_puntajefecha'), 'PF.jugador_id = DJ.jugador_id', 'PF.puntaje_total')
                    ->where('PF.fecha_id = ?', $fecha_id)
                    ->where('DJ.estado = ?', 1)
                    ->order('PF.puntaje_total DESC')
                    ->limit(0,$limite);
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

    public function obtenerRankingGeneralFecha($fecha_id) {
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('DJ' => $this->_name), array('DJ.jugador_id', 'DJ.nombres AS nomjugador'))
                    ->join(array('PF' => 'depor_puntajefecha'), 'PF.jugador_id = DJ.jugador_id', 'PF.puntaje_total')
                    ->where('PF.fecha_id = ?', $fecha_id)
                    ->where('DJ.estado = ?', 1)
                    ->order('PF.puntaje_total DESC');
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

    public function countRankingGeneral(){
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->from($this->_name, 'COUNT(*) AS cantidad')
                    ->where('estado = ?', 1)
                    ->where('puntaje > ?', 0);
        $stmt   = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row    = $stmt->fetch();        
        return $row->cantidad;
    }

    public function countRankingGrupoGeneral($grupo_id){
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('DJ' => $this->_name), 'COUNT(*) AS cantidad')
                    ->join(array('JG' => 'depor_jugadorgrupo'), 'JG.jugador_id = DJ.jugador_id')
                    ->where('JG.grupo_id = ?', $grupo_id)
                    ->where('JG.flag_aprobado = ?', 1)
                    ->where('JG.estado = ?', 1)
                    ->where('DJ.puntaje > ?', 0);
        $stmt   = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row    = $stmt->fetch();
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
    
}

