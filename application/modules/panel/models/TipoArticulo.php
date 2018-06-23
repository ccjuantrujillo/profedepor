<?php
class Panel_Model_TipoArticulo extends Zend_Db_Table
{
    protected $_name = "depor_tipoarticulo";
    private $_usuario = null;
    public function init()
    {
         $this->_usuario = 1;
    }

    public function listar_tipo_articulo()
    {        
        $db     = $this->getAdapter();
        $select = $this->select()
                      ->from('depor_tipoarticulo');
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

    public function listarOpciones(stdClass $filter = null){
        $select = $this->select()
            ->from($this->_name, array('key' => 'tipoarticulo_id', 'value' => 'nombre'))
            ->where('estado = ?', 1);

        return $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
    }
    
	public function obtener_tipo($id) {
        $select = $this->select()
                        ->from($this->_name, array('tipoarticulo_id', 'nombre'))
                        ->where('tipoarticulo_id=?', $id);
        $select = $select->query()->fetch();
        return $select;
    }

}
?>
