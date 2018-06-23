<?php
class Default_Model_Comentario extends Zend_Db_Table_Abstract
{
    protected $_name = "depor_comentario";
            
	public function listar_comentarios($data){
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('DC' => $this->_name), array('comentario_id', 'articulo_id', 'descripcion', 'registro', 'DATE_FORMAT(DC.registro,\'%W\') AS dletra'))
                    ->join(array('DJ' => 'depor_jugador'), 'DJ.jugador_id = DC.jugador_id', array('jugador_id',"CONCAT(nombres,' ',apellidos) AS nomjugador", 'foto'))
                    ->where('DC.articulo_id=?',(int)$data['articulo_id'])
                    ->where('DC.estado=?',1)
                    ->order('DC.registro DESC');
                            
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
        $paginator->setItemCountPerPage(3)
        		->setCurrentPageNumber($data['num']);
            
        $res = array();
    	foreach ($paginator as $r) {
                $objeto = new stdClass();
                $fecha = $this->formatoFechaLetras($r->registro, $r->dletra);
                $objeto->comentario_id = $r->comentario_id;
                $objeto->articulo_id = $r->articulo_id;
                $objeto->descripcion = $r->descripcion;
                $objeto->registro = $fecha;
                $objeto->jugador_id = $r->jugador_id;     
                $objeto->nomjugador = $r->nomjugador;
                $objeto->foto = $r->foto;
                $res[] = $objeto;
        }
        $_paginator = $res;
        $numero = $data['num'];
        $numbers = array();
        foreach ($data['_numbers'] as $n) {
            if ($numero == $n)
                $numbers[] = '<b>' . $n . '</b>';
            else
                $numbers[] = '<a href="/articulo/mostrar/tipo/'.$data['tipo'].'/id/'.$data['articulo_id'].'/num/' . $n . '">' .
                 $n . '</a>';
        }
        $numbers = implode(' - ', $numbers);
        
        $pag = array('comentarios' => $_paginator, 
        		'num' => $numero, 
        		'numbers' => $numbers); 
        
        return $pag;
    }
    
	public function ver_comentarios($articulo_id, $limite){
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('DC' => $this->_name), array('comentario_id', 'articulo_id', 'descripcion', 'registro', 'DATE_FORMAT(DC.registro,\'%W\') AS dletra'))
                    ->join(array('DJ' => 'depor_jugador'), 'DJ.jugador_id = DC.jugador_id', array('jugador_id',"CONCAT(nombres,' ',apellidos) AS nomjugador", 'foto'))
                    ->where('DC.articulo_id=?',(int)$articulo_id)
                    ->where('DC.estado=?',1)
                    ->order('DC.registro DESC')
                    ->limit($limite,0);
        $stmt   = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row    = $stmt->fetchAll();
        $resultado = array();
        $res = array();
        if($row)
        {
            $resultado = $row;
            foreach ($resultado as $r) {
                $objeto = new stdClass();
                $fecha = $this->formatoFechaLetras($r->registro, $r->dletra);
                $objeto->comentario_id = $r->comentario_id;
                $objeto->articulo_id = $r->articulo_id;
                $objeto->descripcion = $r->descripcion;
                $objeto->registro = $fecha;
                $objeto->jugador_id = $r->jugador_id;     
                $objeto->nomjugador = $r->nomjugador;
                $objeto->foto = $r->foto;
                $res[] = $objeto;
            }
            if($row == null){
                $res = null;
            }
        }
        return $res;
    }

    public function formatoFechaLetras($fecha, $dletra){
        $dia = substr($fecha, 8, 2);
        $mesn = substr($fecha, 5, 2);
        $anio = substr($fecha, 0, 4);
        switch($dletra){
                    case "Monday": $dian = "Lunes"; break;
                    case "Tuesday": $dian = "Martes"; break;
                    case "Wednesday": $dian = "Miercoles"; break;
                    case "Thursday": $dian = "Jueves"; break;
                    case "Friday": $dian = "Viernes"; break;
                    case "Saturday": $dian = "Sábado"; break;
                    case "Sunday": $dian = "Domingo"; break;
        }
        switch($mesn){
                    case 1: $mes = "Enero"; break;
                    case 2: $mes = "Febrero"; break;
                    case 3: $mes = "Marzo"; break;
                    case 4: $mes = "Abril"; break;
                    case 5: $mes = "Mayo"; break;
                    case 6: $mes = "Junio"; break;
                    case 7: $mes = "Julio"; break;
                    case 8: $mes = "Agosto"; break;
                    case 9: $mes = "Septiembre"; break;
                    case 10: $mes = "Octubre"; break;
                    case 11: $mes = "Noviembre"; break;
                    case 12: $mes = "Diciembre"; break;
        }
        $nFecha = "$dian $dia de $mes de $anio";
        return $nFecha;
    }
        
    public function insertar_comentario($articulo_id, $jugador_id, $descripcion)
    {
        $data = array(
            'articulo_id'   => $articulo_id,
            'jugador_id'    => $jugador_id,
            'descripcion'   => $descripcion
        );
        return $this->insert($data);
    }
            
	public function existenComentarios($articulo_id){
        $select = $this->select()
                      ->from($this->_name, "COUNT(*) AS cantidad")
                      ->where('articulo_id=?',$articulo_id)
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
?>
