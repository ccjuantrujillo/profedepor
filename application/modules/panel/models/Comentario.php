<?php

class Panel_Model_Comentario extends Zend_Db_Table
{
    protected $_name = "depor_comentario";
    private $_usuario = null;
    
    public function init()
    {
         $this->_usuario = 1;
    }
        
	public function listarComentariosPanel($articulo_id){
        $db     = $this->getAdapter();
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('DC' => $this->_name), 
                    		array('comentario_id', 'articulo_id', 'descripcion', 
                    		'DATE_FORMAT(DC.registro,\'%d/%m%/%Y\') AS fecha',
                    		'DATE_FORMAT(DC.registro,\'%H:%i:%s\') AS hora'))
                    ->join(array('DJ' => 'depor_jugador'), 'DJ.jugador_id = DC.jugador_id', array('jugador_id',"CONCAT(nombres,' ',apellidos) AS nomjugador", 'foto'))
                    ->where('DC.articulo_id=?', (int)$articulo_id)
                    ->where('DC.estado=?', 1)
                    ->order('DC.registro DESC');
        $stmt   = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row    = $stmt->fetchAll();        
        return $row;
    }

    public function eliminar_comentario($comentario_id){
        $data = array(
            'estado' => '0',
            'modificacion' => new Zend_Db_Expr('NOW()')
        );
        $this->update($data,'comentario_id='.(int)$comentario_id);
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

	public function buscar($data){         
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from(array('DC' => $this->_name), 
                    		array('comentario_id', 'articulo_id', 'descripcion', 
                    		'DATE_FORMAT(DC.registro,\'%d/%m%/%Y\') AS fecha',
                    		'DATE_FORMAT(DC.registro,\'%H:%i:%s\') AS hora'))
                    ->join(array('DJ' => 'depor_jugador'), 'DJ.jugador_id = DC.jugador_id', array('jugador_id',"CONCAT(nombres,' ',apellidos) AS nomjugador", 'foto'))
                    ->where('DC.articulo_id=?', (int)$data['articulo_id'])
                    ->where('DC.estado=?', 1)
                    ->order('DC.registro DESC');

        $cPaginator = Zend_Registry::get('paginator');
                    
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
        $paginator->setItemCountPerPage($cPaginator->itemCountPerPage/2)
        		->setCurrentPageNumber($data['num'])
        		->setPageRange($cPaginator->pageRange);

        return $paginator;
    }

}
?>