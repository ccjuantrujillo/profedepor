<?php
class Default_Model_Articulo extends Zend_Db_Table
{
    protected $_name = "depor_articulo";
    
    public function listarArticulosMes($anio, $mes, $tipo)
    {
        $db = $this->getAdapter();
        $select = $this->select()
                    ->from($this->_name, array('articulo_id', 'titulo', 'descripcion', 'registro'))
                    ->where('EXTRACT(YEAR FROM registro)=?',(int)$anio)
                    ->where('EXTRACT(MONTH FROM registro)=?',(int)$mes)
                    ->where('tipoarticulo_id=?',(int)$tipo)
                    ->where('estado=?',1)
                    ->order('registro DESC');
        $stmt = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row      = $stmt->fetchAll();
        $resultado = array();
        $res = array();
        if($row)
        {
            $resultado = $row;
            $com = new Default_Model_Comentario();
            foreach ($resultado as $r) {
                $objeto = new stdClass();
                $num = $com->existenComentarios($r->articulo_id);
                $fecha = $this->formatoFecha($r->registro);
                $objeto->num = $num;
                $objeto->articulo_id = $r->articulo_id;
                $objeto->titulo = $r->titulo;
                $objeto->descripcion = $r->descripcion;
                $objeto->registro = $fecha;
                $res[] = $objeto;
            }
            if($row == null){
                $res = null;
            }
        }
        return $res;
    }

    public function obtenerArchivo($tipo)
    {
        $db = $this->getAdapter();
        $select = $this->select()
                ->from($this->_name, array('mes' =>
                    'EXTRACT(MONTH FROM registro)', 'anio' =>
                    'EXTRACT(YEAR FROM registro)'
                ))
                ->where('tipoarticulo_id = ?', $tipo)
                ->where('estado = ?', 1)
                ->group(array('mes', 'anio'))
                ->order(array('anio DESC', 'mes DESC'));
        $stmt = $select->query();
        $row  = $stmt->fetchAll();
        $resultado = array();
        if($row)
        {
            foreach ($row as $r) {
                switch($r->mes){
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
                $objarchivo = new stdClass();
                $objarchivo->mes = $r->mes;
                $objarchivo->nombre = $mes;
                $objarchivo->anio = $r->anio;
                $resultado[] = $objarchivo;
            }
            if($row == null){
                $resultado = null;
            }
        }
        return $resultado;
    }

    public function obtenerUltimoArchivo($tipo)
    {
        $db = $this->getAdapter();
        $select = $this->select()
                ->from($this->_name, array('mes' =>
                    'EXTRACT(MONTH FROM registro)', 'anio' =>
                    'EXTRACT(YEAR FROM registro)'
                ))
                ->where('tipoarticulo_id = ?', $tipo)
                ->where('estado = ?', 1)
                ->group(array('mes', 'anio'))
                ->order(array('anio DESC', 'mes DESC'))
                ->limit(1,0);
        $stmt = $select->query()->fetch();
        return $stmt;
    }

    public function listar_noticias_portada($tipo, $fecha_id)
    {
        $db = $this->getAdapter();
        $select = $this->select()
                    ->from('depor_articulo')
                    ->where('tipoarticulo_id=?',(int)$tipo)
                    ->where('fecha_id=?',(int)$fecha_id)
                    ->where('estado=?',1)
                    ->order(new Zend_Db_Expr('RAND()'))
                    ->limit(1);
        $stmt = $select->query();
        $row  = $stmt->fetch();
        $portada = new stdClass();
        if($row)
        {
            $portada->articulo_id = $row->articulo_id;
            if($tipo == 1)
            	$portada->titulo = $this->formatoTexto($row->titulo, 47);
            else
            	$portada->titulo = $this->formatoTexto($row->titulo, 55);
            	
            $portada->descripcion = $this->formatoTexto($row->descripcion, 180);

        }else{
            $portada = null;
        }
        return $portada;
    }

    public function obtenerArticulo($articulo_id, $tipo)
    {
        $db = $this->getAdapter();
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('DA' => $this->_name), array('articulo_id', 'titulo', 'descripcion', 'contenido', 'registro', 'tipoarticulo_id'))
                    ->join(array('DC' => 'depor_comentario'), 'DC.articulo_id = DA.articulo_id', 'COUNT(DC.comentario_id) AS num')
                    ->where('DA.articulo_id=?',(int)$articulo_id)
                    ->where('DA.tipoarticulo_id=?',(int)$tipo)
                    ->where('DC.estado=?', 1);
        $stmt = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row      = $stmt->fetch();
        $resultado = array();
        if($row)
        {
            $resultado = $row;
        }
        return $resultado;
    }

    public function getNombreTipo($tipo_id){
        return $this->select()
                ->setIntegrityCheck(false)
                ->from('depor_tipoarticulo',  array('tipoarticulo_id','nombre'))
                ->where('tipoarticulo_id=?', (int)$tipo_id)
                ->query()
                ->fetch();
    }

    public function formatoFecha($fecha){
        $dia = substr($fecha, 8, 2);
        $mes = substr($fecha, 5, 2);
        $anio = substr($fecha, 0, 4);
        $hora = substr($fecha, 11, 2);
        $min = substr($fecha, 14, 2);
        $nFecha = "$dia/$mes/$anio";
        return $nFecha;
    }

    public function formatoTexto($texto, $num){
        if(strlen($texto)>$num){
                $newtexto = substr($texto, 0, $num)."...";
        }else{
                $newtexto = $texto;
        }
        return $newtexto;
    }
    
	public function insertar_articulo($tipoarticulo_id, $usuario_id, $torneo_id, $fase_id, $fecha_id, $titulo, $descripcion)
    {
        $data = array(
            'tipoarticulo_id' => $tipoarticulo_id,
            'usuario_id' => $usuario_id,
            'torneo_id' => $torneo_id,
            'fase_id' => $fase_id,
            'fecha_id' => $fecha_id,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
        );
        return $this->insert($data);
    }
}
?>