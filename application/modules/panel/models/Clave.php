<?php
class Panel_Model_Clave extends Zend_Db_Table_Abstract
{
    protected $_name = "depor_clave";
    protected $_dependetTables = array('depor_juego');
    public function listar_clavejuego($partido,$variable,$intervalo){
          $db         = $this->getAdapter();
          $where = $db->quoteInto('j.partido_id=?',(int)$partido)
                              . $db->quoteInto(' and j.variable_id=? ',(int)$variable)
                              . $db->quoteInto(' and j.intervalo_id=? ',(int)$intervalo);
         $select  = $this->select()
                                      ->setIntegrityCheck(false)
                                      ->from(array('c'=>'depor_clave'))
                                      ->join(array('j'=>'depor_juego'),
                                              'c.juego_id=j.juego_id')
                                      ->where($where);
        $stmt      = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row      = $stmt->fetchAll();
        $resultado = array();
        if($row){
            $resultado = $row;
        }
	return $resultado;
    }
	public function obtener_clave_variable($partido,$variable)
	{
          $db         = $this->getAdapter();
          $where = $db->quoteInto('c.partido_id=?',(int)$partido)
                            . $db->quoteInto(' and j.variable_id=? ',(int)$variable);
         $select  = $this->select()
                                      ->setIntegrityCheck(false)
                                      ->from(array('c'=>$this->_name))
                                      ->join(array('j'=>'depor_juego'),
                                              'c.juego_id=j.juego_id')
                                      ->where($where);
        $stmt      = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
         $row      = $stmt->fetchAll();
		return $row;
	}
    public function insertar_clave($partido,$juego)
	{
		$data = array(
                              'partido_id'  => $partido,
                              'juego_id'     =>$juego
                       );
		$id = $this->insert($data);
        return $id;
	}
	public function modificar_clave($clave,$partido,$juego)
	{
		$data = array(
                              'partido_id'  => $partido,
                              'juego_id'     =>$juego
                       );
		$this->update($data,'clave_id='.(int)$clave);
	}
	public function modificar_clave_partido($partido,$juego)
	{
		$data = array('juego_id'     =>$juego);
		$this->update($data,'partido_id='.(int)$partido);
	}
	public function eliminar_clave($partido_id,$tipovar)
	{
         $db = $this->getAdapter();
         $stmt = $db->query("CALL pns_eliminar_clave(?,?)",array($partido_id,$tipovar));
		$stmt->fetch();
         $db->closeConnection();
         return true;
	}
}
