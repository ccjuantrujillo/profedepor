<?php
class Default_Model_Voto extends Zend_Db_Table_Abstract
{
    protected $_name = "depor_voto";
        
    public function obtenerTotal($tipovoto_id, $articulo_id)
    {
        return $this->select()
                ->from($this->_name, array('total' =>
                    'COUNT(voto_id)'))
                ->where('tipovoto_id = ?', $tipovoto_id)
                ->where('articulo_id = ?', $articulo_id)
                ->query()
                ->fetch();
    }
    
    public function insertarVoto($tipovoto_id, $articulo_id, $jugador_id, $valor)
    {
        $data = array(
            'tipovoto_id'   => $tipovoto_id,
            'articulo_id'   => $articulo_id,
            'jugador_id'    => $jugador_id,
            'valor'   => $valor
        );
        return $this->insert($data);
    }

    
    public function obtenerFecVoto($articulo_id, $jugador_id)
    {
        return $this->select()
                ->from($this->_name, array("DATE_FORMAT(registro,'%Y-%m-%d') AS fecha",
                    "TIMESTAMPDIFF(HOUR,registro,NOW()) AS dhora"))
                ->where('articulo_id = ?', $articulo_id)
                ->where('jugador_id = ?', $jugador_id)
                ->order('registro DESC')
                ->query()
                ->fetchAll();
    }

}
?>
