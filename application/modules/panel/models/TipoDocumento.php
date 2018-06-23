<?php
class Panel_Model_TipoDocumento extends Zend_Db_Table_Abstract
{
    protected $_name = "depor_tipodocumento";
    private $_usuario = null;
    public function init()
    {
         $this->_usuario = 1;
    }

    public function listarOpcionesDoc(stdClass $filter = null){
        $select = $this->select()
            ->from($this->_name, array('key' => 'tipodoc_id', 'value' => 'descripcion'))
            ->where('estado = ?', 1);

        return $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
    }

	public function obtener_tipo($id) {
        $select = $this->select()
                        ->from($this->_name, array('tipodoc_id', 'descripcion'))
                        ->where('tipodoc_id=?', $id);
        $select = $select->query()->fetch();
        return $select;
    }
}
?>
