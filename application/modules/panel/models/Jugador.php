<?php
class Panel_Model_Jugador extends Zend_Db_Table_Abstract
{
    protected $_name = "depor_jugador";
    
    public function init(){}
    
    public function listar_jugadores(){
        $db     = $this->getAdapter();
        $where  = $db->quoteInto('estado=?',1);
        $select = $this->select()
                      ->from('depor_jugador')
                      ->where($where);
        $stmt   = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row    = $stmt->fetchAll();
        $resultado = array();
        if($row)
        {
            $resultado = $row;
        }
        return $resultado;
    }
    
    public function cantidad_jugadores() {
        $select = $this->select()
                        ->from($this->_name, "COUNT(*) AS cantidad")
                        ->where('estado=?', 1);
        $stmt = $select->query();
        $row = $stmt->fetch();
        $resultado = array();
        if ($row) {
            $resultado = $row->cantidad;
        }
        return $resultado;
    }
        
    public function obtenerJugador($jugador_id)
    {
        $select = $this->select()
                        ->from(array('DJ' => $this->_name), array('jugador_id',
                            'tipodoc_id', 'numero_doc', 'nombres', 'apellidos',
                            'telefono', 'foto', 'email', 'dni_apoderado'))
                        ->where('jugador_id=?', $jugador_id);
        $select = $select->query()->fetch();
        return $select;
    }

    public function insertar_jugador($partido,$tipo,$valor_a,$valor_b)
    {
        $data = array(
                      'partido_id'   => $partido,
                      'tipo'         => $tipo,
                      'valor_a'      => $valor_a,
                      'valor_b'      => $valor_b,
                      'usuario_id'   => $this->_usuario
               );
        $id   = $this->insert($data);
        return $id;
    }
    
    public function actualizar_jugador($data)
    {
        $jugador_id = $data['jugador_id'];
        unset($data['jugador_id']);
        $this->update($data,'jugador_id='.(int)$jugador_id);
    }
        
    public function delete($jugador_id)
    {
    	#eliminar grupos a los que se unió
        $juggrupo = new Panel_Model_JugadorGrupo();
        $datagrupos = $juggrupo->gruposSeguidor($jugador_id);
        foreach($datagrupos as $d){
        	$juggrupo->delete($d->jugadorgrupo_id);
        }        
        #eliminar jugador
        $data = array(
            'estado'   => '0',
            'modificacion' => new Zend_Db_Expr('NOW()'));
        $this->update($data,'jugador_id='.(int)$jugador_id);
    }
    
    public function buscar($data){         
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('DJ' => $this->_name), array('jugador_id',
                            'nombres', 'apellidos', 'email', 'puntaje'))
            ->where('estado=?',1);
		
        if(isset($data['letra']) && strlen($data['letra']) > 0){
            if(preg_match('/[0-9]/', $data['letra']))
                $select->where("apellidos REGEXP ?", '^[[:digit:]]');
            else
                $select->where("apellidos LIKE ?", "{$data['letra']}%");
        }
                
        $path = Zend_Registry::get('paginator');
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
        $paginator->setItemCountPerPage($path->itemCountPerPage);

        return $paginator;
    }
    
    public function existeJugador($jugador_id, $tname){
    	$select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('J' => $this->_name), "COUNT(J.jugador_id) AS cantidad")
                        ->join(array('T' => $tname), 'T.jugador_id = J.jugador_id', 'T.jugador_id')
                        ->where('J.jugador_id = ?', $jugador_id)
                        ->where('J.estado = ?', 1);
        $row = $select->query()->fetch();                
        return $row->cantidad;
    }
        
}

