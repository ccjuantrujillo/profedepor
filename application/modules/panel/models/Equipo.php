<?php

class Panel_Model_Equipo extends Zend_Db_Table {

    protected $_name = 'depor_equipo';

    public function init() {

    }

    public function existenEquipos($club_id) {
        $select = $this->select()
                        ->from($this->_name, "COUNT(*) AS cantidad")
                        ->where('club_id=?', $club_id)
                        ->where('estado=?', 1);
        $stmt = $select->query();
        $row = $stmt->fetch();
        $resultado = array();
        if ($row) {
            $resultado = $row->cantidad;
        }
        return $resultado;
    }
    
	public function existenEquiposTorneo($torneo_id) {
        $select = $this->select()
                        ->from($this->_name, "COUNT(*) AS cantidad")
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

    public function listarEquipos(stdClass $filter = null) {
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('E' => $this->_name), 'equipo_id')
                        ->join(array('DC' => 'depor_club'),
                                'DC.club_id = E.club_id', array(
                            'club' => 'DC.descripcion'))
                        ->join(array('T' => 'depor_torneo'),
                                'T.torneo_id = E.torneo_id', array(
                            'torneo' => 'T.descripcion'))
                        ->where('E.estado = ?', 1);

        if (isset($filter->torneo_id) && $filter->torneo_id > 0)
            $select->where('E.torneo_id = ?', $filter->torneo_id);

        return $select->query()->fetchAll();
    }

    public function cantidadEquipos($torneo_id) {
        $select = $this->select()
                        ->from($this->_name, "COUNT(*) AS cantidad")
                        ->where('torneo_id=?', $torneo_id)
                        //->where('club_id=?',$club_id)
                        ->where('estado=?', 1);
        $stmt = $select->query();
        $row = $stmt->fetch();
        $resultado = array();
        if ($row) {
            $resultado = $row->cantidad;
        }
        return $resultado;
    }

    public function obtener_equipo($equipo_id) {
        $select = $this->select()
                        ->where('equipo_id=?', $equipo_id);
        $row = $select->query()->fetch();
        return $row;
    }

    public function save($data){
        unset($data['submit']);
        unset($data['accion']);
        unset($data['txt_torneo_id']);
        if(isset($data['equipo_id']) && $data['equipo_id'] > 0){
            $equipo_id = $data['equipo_id'];
            unset($data['equipo_id']);
            unset($data['torneo_id']);
            $data['modificacion'] = new Zend_Db_Expr('NOW()');
            $this->update($data, "equipo_id = ".$equipo_id);
        } else {
            unset($data['equipo_id']);
            $equipo_id = $this->insert($data);
        }
        return $equipo_id;
    }

    public function deleteEquipo($where){
        /*$data = array(
            'estado' => '0',
            'modificacion' => new Zend_Db_Expr('NOW()')
        );*/
        $this->delete($where);
    }

}

