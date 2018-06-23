<?php
class Panel_Model_Articulo extends Zend_Db_Table
{
    protected $_name = "depor_articulo";
    
    public function init()
    {
         
    }
    
    public function listarArticulos(stdClass $filter = null) {
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('A' => $this->_name), array('articulo_id',
                            'titulo',
                            'descripcion',
                            "DATE_FORMAT(A.registro,'%d/%m%/%Y') AS registro"))
                        ->join(array('T' => 'depor_tipoarticulo'),
                                'T.tipoarticulo_id = A.tipoarticulo_id', array(
                            'nombre_articulo' => 'T.nombre'))
                        ->where('A.estado = ?', 1);

        if (isset($filter->tipoarticulo_id) && $filter->tipoarticulo_id > 0)
            $select->where('A.tipoarticulo_id = ?', $filter->tipoarticulo_id);

         $select->order('A.registro DESC');

        return $select->query()->fetchAll();
    }
    	    
    public function obtener_articulo($articulo_id)
    {
        $row = $this->fetchRow('articulo_id='.(int)$articulo_id);
        $resultado = array();
        if($row)
        {
            $resultado = $row;
        }
        return $resultado;
    }

    public function insertar_articulo($tipoarticulo_id, $usuario_id, $torneo_id, $fase_id, $fecha_id, $titulo, $descripcion, $contenido)
    {
        $data = array(
            'tipoarticulo_id' => $tipoarticulo_id,
            'usuario_id' => $usuario_id,
            'torneo_id' => $torneo_id,
            'fase_id' => $fase_id,
            'fecha_id' => $fecha_id,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'contenido' => htmlentities($contenido)
        );        
        return $this->insert($data);
    }

    public function modificar_articulo($articulo_id, $titulo, $descripcion, $contenido)
    {
        $data = array(
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'contenido' => htmlentities($contenido),
            'modificacion' => new Zend_Db_Expr('NOW()')
        );
        $this->update($data, 'articulo_id='.$articulo_id);
    }

    public function eliminar_articulo($id)
    {        
        $data = array(
            'estado' => '0',
            'modificacion' => new Zend_Db_Expr('NOW()')
        );
        $this->update($data, 'articulo_id='.$id);
    }

    public function cantidadArticulos($tipoarticulo_id) {
        $select = $this->select()
                        ->from($this->_name, "COUNT(*) AS cantidad")
                        ->where('tipoarticulo_id=?', $tipoarticulo_id)
                        ->where('estado=?', 1);
        $stmt = $select->query();
        $row = $stmt->fetch();
        $resultado = array();
        if ($row) {
            $resultado = $row->cantidad;
        }
        return $resultado;
    }

}
?>
