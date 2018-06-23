<?php

class Default_Model_Grupo extends Zend_Db_Table_Abstract {
    protected $_name = 'depor_grupo',
              $_request = null;

    public function __construct(Zend_Controller_Request_Abstract $request){
        parent::__construct();
        $this->_request = $request;
    }

    public function usuarioGrupos(){
        $auth = Zend_Auth::getInstance();
        if( ! $auth->hasIdentity())
            return false;

        $jugador = $auth->getIdentity();
        
        $select=
            $this->select()
            ->setIntegrityCheck(false)
            ->from(array('DG' => $this->_name), array('grupo_id', 'nombre', 'descripcion', 'registro'))
            ->join(array('JG' => 'depor_jugadorgrupo'), 'JG.grupo_id = DG.grupo_id', 'jugador_id')
            ->where('JG.jugador_id = ?', $jugador->jugador_id)
            ->where('JG.flag_aprobado = ?', 1)
            ->where('JG.estado = ?', 1);
        
        return $select->query()->fetchAll();
    }

    public function usuarioUltimosGrupos(){
        $auth = Zend_Auth::getInstance();
        if( ! $auth->hasIdentity())
            return false;

        $jugador = $auth->getIdentity();
        $select =  $this->select()
            ->setIntegrityCheck(false)
            ->from(array('DG' => $this->_name), array('grupo_id', 'nombre', 'descripcion', 'registro'))
            ->join(array('JG' => 'depor_jugadorgrupo'), 'JG.grupo_id = DG.grupo_id', 'jugador_id')
            ->where('JG.jugador_id = ?', $jugador->jugador_id)
            ->where('JG.flag_aprobado = ?', 1)
            ->where('JG.tipo = ?', 2);
        return $select->query()->fetchAll();
    }

    public function miembros_grupo($flag_aprobado, $estado){
        $grupos = $this->usuarioGrupos();
        $miembros = new Default_Model_JugadorGrupo();
        $mgrupos  = array();
        foreach ($grupos as $g) {
            $objeto = new stdClass();
            $cantidad = $miembros->cantidad_miembros_grupo($g->grupo_id, $flag_aprobado, $estado);
            $objeto->grupo_id = $g->grupo_id;
            if(strlen($g->nombre)>23){
                $objeto->nombre = substr($g->nombre,0,23)."...";
            }else{
                $objeto->nombre = ucwords(strtolower($g->nombre));
            }
            $objeto->cantidad = $cantidad->cantidad;
            $mgrupos[] = $objeto;
        }
        if(empty ($grupos)){
            $mgrupos = null;
        }
        return $mgrupos;
    }

    public function getGrupo($grupo_id){
        return $this->select()
                ->where('grupo_id=?', (int)$grupo_id)
                ->query()
                ->fetch();
    }

    public function comprobarNombre($nombre){
        return $this->select()
                ->from($this->_name, 'grupo_id')
                ->where('nombre=?', $nombre)
                ->query()
                ->fetch();
    }

    public function getByGUID($GUID){
        return $this->select()->where('GUID = ?', $GUID)
            ->query()->fetch();
    }

    public function disableGUID(){
        return $this->update(array('GUID' => ''));
    }

    public function save($data){
        unset($data['datos']);

        $grupo_id = $this->insert($data);

        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();

        $dataJugadorGrupo = array(
            'grupo_id' => $grupo_id,
            'jugador_id' => $identity->jugador_id,
            'tipo' => 1
        );

        # insertar un jugador en jugadorgrupo
        $jugadorGrupo = new Default_Model_JugadorGrupo();
        $jugadorGrupo->save($dataJugadorGrupo);

        return $grupo_id;
    }

    public function guardarFoto($grupo_id, $nombre){
        $data = array('foto' => $nombre);
        $this->update($data,'grupo_id='.(int)$grupo_id);
    }

    public function buscar($data){
        $jg1 = new Default_Model_JugadorGrupo();
        $columns = array('grupo_id', 'nombre', 'foto', 
        	'cantidad' => $jg1->cantidadJugadorSql(), 
        	'unido' => $jg1->unidoJugadorSQL());

        $select = $this->select()
            ->setIntegrityCheck(false)         
            ->from(array('DG'=> $this->_name), $columns)            
            ->join(array('JG' => 'depor_jugadorgrupo'), 'JG.grupo_id = DG.grupo_id');

        if(isset($data['q']) && strlen($data['q']) > 0){
            $select->where("DG.nombre LIKE ?", "%{$data['q']}%");
        }else if(isset($data['letra']) && strlen($data['letra']) > 0){
            if(preg_match('/[0-9]/', $data['letra']))
                $select->where("DG.nombre REGEXP ?", '^[[:digit:]]');
            else
                $select->where("DG.nombre LIKE ?", "{$data['letra']}%");
        }

        $select->where('DG.estado=?',1)
        		->where('JG.estado=?',1)
        		->group('DG.grupo_id')
        		->order('DG.nombre');
        
        $cPaginator = Zend_Registry::get('paginator');

        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
        $paginator->setItemCountPerPage($cPaginator->itemCountPerPage);
                    
		$_paginator = array();
		foreach ($paginator as $g){
			$jg2 = new Default_Model_JugadorGrupo();
			
            $objeto = new stdClass();
            $objeto->grupo_id = $g->grupo_id;
            $tipo = $this->getGrupo($g->grupo_id);
            $objeto->tipo = $tipo->tipo;
            $objeto->foto = $g->foto;
            $objeto->cant_jugador = $g->cantidad;            
            $nombre = $jg2->getAdminGrupo($g->grupo_id);
        	if($g->grupo_id == 1){
                $nombre = "Admin Depor";
            }
            if(strlen($g->nombre)>25){
                $objeto->nombre = substr($g->nombre,0,23)."...";               
            }else if(strlen($g->nombre)<15 && strlen($nombre)<15){
            	$objeto->nombre = $g->nombre;
            	$objeto->adminnombre = $nombre;
        	}else{
                $objeto->nombre = $g->nombre;                
            }                        
            if(strlen($g->nombre)>25 && strlen($nombre)>12){
                $objeto->adminnombre = substr($nombre,0,10)."...";
            }else{
                $objeto->adminnombre = $nombre;
            }            
            $objeto->unido = $g->unido;           
            $_paginator[] = $objeto;
        }
                    
        return $_paginator;
    }
    	    	
}

