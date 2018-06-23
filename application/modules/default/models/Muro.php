<?php
class Default_Model_Muro extends Zend_Db_Table
{
    protected $_name = "depor_muro";

    public function listar_mensajes_grupo($grupo_id, $limite){
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('DM' => $this->_name),
                    array('muro_id', 'jugador_id','mensaje', 'registro',
                        "DATE_FORMAT(dm.registro,'%d/%m%/%Y') AS fecha",
                        "DATE_FORMAT(dm.registro,'%H:%i') AS hora",
                        "TIMESTAMPDIFF(HOUR,dm.registro,NOW()) AS dhora",
                        "TIMESTAMPDIFF(MINUTE,dm.registro,NOW()) AS dmin"))
                    ->join(array('DJ' => 'depor_jugador'), 'DJ.jugador_id = DM.jugador_id', array("DJ.nombres AS nomjugador", 'foto'))
                    ->where('DM.grupo_id = ?', $grupo_id)
                    ->order('DM.registro DESC')
                    ->limit($limite,0);
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

    public function listar_mas_mensajes_grupo($grupo_id, $muro_id){
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('DM' => $this->_name),
                    array('muro_id', 'jugador_id','mensaje', 'registro',
                        "DATE_FORMAT(dm.registro,'%d/%m%/%Y') AS fecha",
                        "DATE_FORMAT(dm.registro,'%H:%i') AS hora",
                        "TIMESTAMPDIFF(HOUR,dm.registro,NOW()) AS dhora",
                        "TIMESTAMPDIFF(MINUTE,dm.registro,NOW()) AS dmin"))
                    ->join(array('DJ' => 'depor_jugador'), 'DJ.jugador_id = DM.jugador_id', array("DJ.nombres AS nomjugador", 'foto'))
                    ->where('DM.grupo_id = ?', $grupo_id)
                    ->where('DM.muro_id < ?', (int)$muro_id)
                    ->order('DM.registro DESC')
                    ->limit(10,0);;
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

    public function listar_mensajes_usuario($grupo){
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('DM' => $this->_name),
                    array('muro_id', 'jugador_id','mensaje', 'registro',
                        "DATE_FORMAT(DM.registro,'%H:%i') AS hora",
                        "DATE_FORMAT(DM.registro,'%w') AS dsem",
                        "DATE_FORMAT(DM.registro,'%m') AS mes",
                        "TIMESTAMPDIFF(HOUR,DM.registro,NOW()) AS dhora",
                        "TIMESTAMPDIFF(MINUTE,DM.registro,NOW()) AS dmin"))
                    ->join(array('DG' => 'depor_grupo'), 'DG.grupo_id = DM.grupo_id', 'DG.nombre')
                    ->join(array('DJ' => 'depor_jugador'), 'DJ.jugador_id = DM.jugador_id', array("DJ.nombres AS nomjugador", 'DJ.foto'))
                    ->where('DM.grupo_id IN(?)', $grupo)
                    ->where('DM.estado = ?', 1)
                    ->order('DM.registro DESC')
                    ->limit(20, 0);
        $stmt   = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row    = $stmt->fetchAll();
        $resultado = array();
        $listado   = array();
        if($row)
        {
            $resultado = $row;
            foreach($resultado as $m){
                $objgrupo = new stdClass();
                $objgrupo->muro_id = $m->muro_id;
                $objgrupo->jugador_id = $m->jugador_id;
                $objgrupo->mensaje = $this->formatoTexto(html_entity_decode($m->mensaje), 145);
                $objgrupo->nomjugador = $this->formatoTexto($m->nomjugador, 20);
                $objgrupo->nomgrupo = $this->formatoTexto($m->nombre, 17);
                $objgrupo->fecha   = $this->formatoFechaPortada($m->registro, $m->dsem, $m->mes);
                if($m->dhora > 24){
                    $hora = $m->hora;
                }else if($m->dhora == 0){
                    if($m->dmin == 0)
                        $hora = "hace 1 min";
                    else
                        $hora = "hace $m->dmin min";
                }else{
                    if($m->dhora == 1)
                        $hora = "hace $m->dhora hora";
                    else
                        $hora = "hace $m->dhora horas";
                }
                $objgrupo->hora = $hora;
                $listado[] = $objgrupo;
            }
            if(empty ($resultado)){
                $listado = null;
            }
        }
        return $listado;
    }

    public function insertar_mensaje($jugador_id, $grupo_id, $mensaje)
    {
        $data = array(
            'jugador_id' => $jugador_id,
            'grupo_id' => $grupo_id,
            'mensaje'=> $mensaje
        );
        return $this->insert($data);
    }

    public function modificar_mensaje($mensaje, $descripcion)
    {
        $data = array(
            'descripcion'=> $descripcion
        );
        $this->update($data,'muro_id='.(int)$mensaje);
    }

    public function eliminar_mensaje($id){
        $this->delete('muro_id='.(int)$id);
    }

    public function formatoFechaPortada($registro, $dia, $mes){
        $ndia = substr($registro, 8, 2);
        $nmes = substr($registro, 5, 2);
        $nanio = substr($registro, 0, 4);
        switch ((int)$mes){
            case 01: $mes="Enero";break;
            case 02: $mes="Febrero";break;
            case 03: $mes="Marzo";break;
            case 04: $mes="Abril";break;
            case 05: $mes="Mayo";break;
            case 06: $mes="Junio";break;
            case 07: $mes="Julio";break;
            case 08: $mes="Agosto";break;
            case 09: $mes="Septiembre";break;
            case 10: $mes="Octubre";break;
            case 11: $mes="Noviembre";break;
            case 12: $mes="Diciembre";break;
        }
        switch($dia){
            case 0: $dia="Domingo";break;
            case 1: $dia="Lunes";break;
            case 2: $dia="Martes";break;
            case 3: $dia="Miercoles";break;
            case 4: $dia="Jueves";break;
            case 5: $dia="Viernes";break;
            case 6: $dia="Sábado";break;
        }
        $formato = "$dia $ndia de $mes del $nanio";
        return $formato;
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
?>