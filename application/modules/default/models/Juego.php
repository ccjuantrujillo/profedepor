<?php
class Default_Model_Juego extends Zend_Db_Table_Abstract
{
    protected $_name = "depor_juego";
    public function obtener_juego($partido,$variable,$puntos)
    {
         $db = $this->getAdapter();
         $stmt = $db->query("CALL pns_obtener_juego (?,?,?)",array($partido,$variable,$puntos));
         $row  = $stmt->fetch();
         $db->closeConnection();
         return $row;
    }
	public function obtener_juego_pvi($partido,$variable,$intervalo)
	{
		$row = $this->fetchRow(
                         $this->select()
                                   ->where('partido_id=?',(int)$partido)
                                   ->where('variable_id=?',(int)$variable)
                                   ->where('intervalo_id=?',(int)$intervalo)
                                   );
		$resultado = array();
        if($row)
		{
               $resultado = $row->toArray();
		}
		return $resultado;
	}
    public function insertar_juego($partido_id)
	{
          $db     = $this->getAdapter();
          $stmt = $db->query("CALL pns_insertar_juego(?)",array($partido_id));
          $row  = $stmt->fetchAll();
          $db->closeConnection();
          if($row){
               return true;
          }
	}
	public function deleteJuego($where)
	{
		$this->delete($where);
	}
}
