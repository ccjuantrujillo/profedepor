<?php

class Default_Model_JugadorGrupo extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_jugadorgrupo',
              $_referenceMap = array(
        'grupo' => array(
            'columns' => array('grupo_id'),
            'refTableClass' => 'depor_grupo',
            'refColumns' => array('grupo_id')
        ),
        'jugador' => array(
            'columns' => array('jugador_id'),
            'refTableClass' => 'depor_jugador',
            'refColumns' => array('jugador_id')
        )
    ),

              $_integrantesSql = null;

    private function _integrantes($grupo){
        if(null === $this->_integrantesSql){
            $this->_integrantesSql = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('JG' => $this->_name), 'grupo_id')
                ->join(array('DJ' => 'depor_jugador'), 'JG.jugador_id = DJ.jugador_id')
                ->where('grupo_id = ?', $grupo->grupo_id);
        }

        return clone $this->_integrantesSql;
    }

    public function integrantes($grupo){
        return $this->_integrantes($grupo)
            ->where('flag_aprobado = ?', 1)
            ->where('JG.estado = ?', 1)
            ->where('tipo = ?', 2);
        # exit;
    }

    public function integrantesGrupo($grupo_id){
        return $this->select()
            ->setIntegrityCheck(false)
            ->from(array('JG' => $this->_name), 'JG.jugador_id')
            ->join(array('DJ' => 'depor_jugador'), 'JG.jugador_id = DJ.jugador_id', array("CONCAT(DJ.nombres,' ',DJ.apellidos) AS nomjugador"))
            ->where('JG.grupo_id = ?', $grupo_id)
            ->where('JG.flag_aprobado = ?', 1)
            ->where('JG.estado = ?', 1)
            ->where('JG.tipo = ?', 2)
            ->query()
            ->fetchAll();
    }

    public function solicitudesGrupo($grupo_id){
        return $this->select()
            ->setIntegrityCheck(false)
            ->from(array('JG' => $this->_name), 'JG.jugador_id')
            ->join(array('DJ' => 'depor_jugador'), 'JG.jugador_id = DJ.jugador_id', array("CONCAT(DJ.nombres,' ',DJ.apellidos) AS nomjugador"))
            ->where('JG.grupo_id = ?', $grupo_id)
            ->where('JG.flag_aprobado = ?', 0)
            ->where('JG.estado = ?', 1)
            ->where('JG.tipo = ?', 2)
            ->query()
            ->fetchAll();
    }

    public function cantidad_integrantes($grupo_id, $flag_aprobado, $tipo, $estado){
        return $this->select()
            ->from($this->_name)
            ->columns('COUNT(*) as cantidad')
            ->where('grupo_id = ?', $grupo_id)
            ->where('flag_aprobado = ?', $flag_aprobado)
            ->where('tipo = ?', $tipo)
            ->where('estado = ?', $estado)
            ->query()
            ->fetch();
    }

    public function cantidad_miembros_grupo($grupo_id, $flag_aprobado, $estado){
        return $this->select()
            ->from($this->_name)
            ->columns('COUNT(*) as cantidad')
            ->where('grupo_id = ?', $grupo_id)
            ->where('flag_aprobado = ?', $flag_aprobado)
            ->where('estado = ?', $estado)
            ->query()
            ->fetch();
    }

    public function request($grupo){
        return $this->_integrantes($grupo)
            ->where('flag_aprobado = ?', 0)
            ->query()
            ->fetchAll();
    }

    public function getAdmin($grupo){
        $jugador = Zend_Auth::getInstance()->getIdentity();
        return $this->select()
            ->setIntegrityCheck(false)
            ->from(array('JG' => $this->_name), null)
            ->join(array('DJ' => 'depor_jugador'), 'JG.jugador_id = DJ.jugador_id')
            ->where('JG.grupo_id = ?', $grupo->grupo_id)
            ->where('JG.tipo = ?', 1)
            ->query()
            ->fetch();
    }

    public function getAdminGrupo($grupo_id){
        $objgrupo = new stdClass();
        $objgrupo->grupo_id = $grupo_id;
        $row  = $this->getAdmin($objgrupo);
        if($row){
            $nom = $row->nombres;
            return $nom;
        }
    }

    public function existeJugadorEnGrupo($jugador_id, $grupo_id, $tipo){
        return $this->select()
            ->where('jugador_id = ?', (int)$jugador_id)
            ->where('grupo_id = ?', (int)$grupo_id)
            ->where('tipo = ?', (int)$tipo)
            ->query()
            ->fetchAll();
    }

    public function unidoJugadorSQL(){
        $auth_jugador = Zend_Auth::getInstance()->getIdentity();

        return new Zend_Db_Expr('(' . $this->select()
            ->from($this->_name, null)
            ->columns('COUNT(*)')
            ->where('grupo_id = DG.grupo_id')
            ->where('jugador_id = ?', $auth_jugador->jugador_id)
            // ->where('flag_aprobado = ?', 1)
             . ')');
    }
    
	public function cantidadJugadorSQL(){
        return new Zend_Db_Expr('(' . $this->select()
            ->from($this->_name, null)
            ->columns('COUNT(*)')
            ->where('grupo_id = DG.grupo_id') 
            ->where('estado=?',1). ')');
    }
    
    public function save($data){
        unset($data['datos']);
        # $data['password'] = md5($data['password']);

        # por defecto dni
        # $data['tipodoc_id'] = 1;
        return $this->insert($data);
    }
   
    public function obtener_perfil_grupo($grupo, $modgrupo){
        $numhinchas = $this->cantidad_integrantes($grupo->grupo_id, 1, 2, 1);
        $nomgrupo = $modgrupo->getGrupo($grupo->grupo_id);
        $objgrupo = new stdClass();
        $objgrupo->grupo_id = $nomgrupo->grupo_id;
        $nom = explode(" ",$nomgrupo->nombre);
        if(count($nom)<3)
        	$objgrupo->nomgrupo = $this->formatoTexto($nomgrupo->nombre, 24);
        else 
        	$objgrupo->nomgrupo = $nomgrupo->nombre;
        	
        if($grupo->grupo_id != 1){
            $admin = $this->getAdmin($grupo);
            $adminnom = ucfirst($admin->nombres);
            $adminape = ucfirst($admin->apellidos);
            $objgrupo->adminnombre = $adminnom." ".$adminape;
        }else{
            $objgrupo->adminnombre = "Administrador";
        }
        $dia = substr($nomgrupo->registro, 8, 2);
        $mes = substr($nomgrupo->registro, 5, 2);
        $anio = substr($nomgrupo->registro, 2, 2);
        $objgrupo->creado = "$dia/$mes/$anio";
        $objgrupo->hinchas = $numhinchas->cantidad + 1;
        $objgrupo->descripcion = $nomgrupo->descripcion;
        $objgrupo->foto = $nomgrupo->foto;
        $datosgrupo = $objgrupo;
        return $datosgrupo;
    }

    public function listarRankingGrupoGeneral($grupo_id){
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('JG' => $this->_name), 'JG.jugador_id')
                    ->join(array('DJ' => 'depor_jugador'), 'DJ.jugador_id = JG.jugador_id', array("SUBSTR(DJ.nombres,1,12) AS nomjugador", 'DJ.puntaje'))
                    ->where('JG.grupo_id = ?', $grupo_id)
                    ->where('JG.flag_aprobado = ?', 1)
                    ->where('JG.estado = ?', 1)
                    ->order('DJ.puntaje DESC')
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

    public function getUltimosGrupos($jugador_id){
        $db     = $this->getAdapter();
        $select = $this->select()
            ->from($this->_name, 'grupo_id')
            ->where('jugador_id = ?', $jugador_id)
            ->where('flag_aprobado = ?', 1)
            ->order('registro DESC');
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

    public function aprobarSolicitudes($grupo_id, $jugador){
        foreach ($jugador as $j) {
            $jugadorgrupo = $this->existeJugadorEnGrupo($j, $grupo_id, 2);
            $id = $jugadorgrupo[0]->jugadorgrupo_id;
            $data = array(
                'flag_aprobado' => '1'
            );
            $res = $this->update($data, 'jugadorgrupo_id='.$id);
        }
        return $res;
    }

    public function formatoTexto($texto, $num){
        if(strlen($texto)>$num){
                $newtexto = substr($texto, 0, $num)."...";
            }else{
                $newtexto = $texto;
        }
        return $newtexto;
    }
}