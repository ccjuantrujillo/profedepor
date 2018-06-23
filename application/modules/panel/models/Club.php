<?php
class Panel_Model_Club extends Zend_Db_Table
{
    protected $_name = "depor_club";

    public function listarOpciones(stdClass $filter = null, $required = true){
        $select = $this->select()
            ->from($this->_name, array('key' => 'club_id', 'value' => 'descripcion'))
            ->where('estado = ?', 1);

        $all = $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
        if($required)
            array_unshift($all, array('key' => 0, 'value' => 'SELECCIONE'));

        return $all;
    }

    public function listarOpcionesOut($torneo_id,$equipo_id) {
        $db   = $this->getAdapter();
        $stmt = $db->prepare("CALL pns_obtener_clubes(?,?)");
        $stmt->bindParam(1,$torneo_id);
        $stmt->bindParam(2,$equipo_id);
        $stmt->execute();
        $row  = $stmt->fetchAll(Zend_Db::FETCH_ASSOC);
        $resultado = array();
        foreach($row as $val){
            $club_id = $val['club_id'];
            $resultado[$club_id]=$val['descripcion'];
        }
        $db->closeConnection();
        return $resultado;
    }

    public function listarClubes(){
        $select = $this->select()
                      ->from($this->_name)
                      ->where('estado=?',1);
        $stmt   = $select->query();
        $row    = $stmt->fetchAll();
        $resultado = array();
        if($row)
        {
            $resultado = $row;
        }
        return $resultado;
    }

    public function cantidad_clubes(){
        $select = $this->select()
                      ->from($this->_name, "COUNT(*) AS cantidad")
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

    public function obtener_club($club_id)
    {
         $select = $this->select()
                          ->where('club_id=?' , $club_id);
         $select = $select->query()->fetch();
         return $select;
    }

    public function save($data){
        unset($data['submit']);
        unset($data['MAX_FILE_SIZE']);
        if(isset($data['club_id']) && $data['club_id'] > 0){
            $club_id = $data['club_id'];
            unset($data['club_id']);
            $data['modificacion'] = $this->getFechaActual();
            $this->update($data, "club_id = ".$club_id);
        } else {
            unset($data['club_id']);
            $club_id = $this->insert($data);
        }
        return $club_id;
    }

    public function deleteClub($where){
        /*$mod = $this->getFechaActual();
        $data = array(
            'modificacion' => $mod,
            'estado' => '0'
        );*/
        $this->delete($where);
    }

    public function getFechaActual(){
        return date('Y-m-d h-i-s');
    }

    public function insertar_club($descripcion, $nom_icono, $nom_imagen)
    {
        $data = array(
            'descripcion' => $descripcion,
            'icono' => $nom_icono,
            'imagen'=> $nom_imagen
        );
        return $this->insert($data);
    }

    public function actualizar_club($club_id, $descripcion, $nom_icono, $nom_imagen)
    {
        $data = array(
            'descripcion'=> $descripcion,
            'icono' => $nom_icono,
            'imagen'=> $nom_imagen,
            'modificacion' => new Zend_Db_Expr('NOW()')
        );
        $this->update($data,'club_id='.(int)$club_id);
    }
}