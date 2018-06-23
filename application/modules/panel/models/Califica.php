<?php
class Panel_Model_Califica extends Zend_Db_Table
{
    protected $_name = "depor_califica";
    private $_usuario = null;
    public function init()
    {
         $this->_usuario = 1;
    }
    public function listar_calificaciones($torneo,$fase,$fecha){
          $db         = $this->getAdapter();
          $where = $db->quoteInto('p.torneo_id=?',(int)$torneo)
                            . $db->quoteInto(' and p.fase_id=? ',(int)$fase)
                            . $db->quoteInto(' and p.fecha_id=? ',(int)$fecha);
         $select  = $this->select()
                                       ->setIntegrityCheck(false)
                                      ->from(array('p'=>'depor_partido'))
                                      ->joinLeft(array('r'=>'depor_respuesta'), 'p.partido_id=r.partido_id')
                                      ->where($where);
        $stmt      = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
         $row      = $stmt->fetchAll();
         $resultado = array();
		if($row)
		{
			$resultado = $row;
		}
		return $resultado;
    }
    public function insertar_calificacion($partido,$intervalo_a1,$intervalo_a2,$intervalo_a3,$intervalo_a4,$intervalo_b1,$intervalo_b2,$intervalo_b3,$intervalo_b4){
		$data = array(
                              'partido_id'  => $partido,
                              'intervalo_id1'     =>$intervalo_a1,
                              'intervalo_id2'     =>$intervalo_a2,
                              'intervalo_id3'     =>$intervalo_a3,
                              'intervalo_id4'     =>$intervalo_a4,
                              'intervalo2_id1'     =>$intervalo_b1,
                              'intervalo2_id2'     =>$intervalo_b2,
                              'intervalo2_id3'     =>$intervalo_b3,
                              'intervalo2_id4'     =>$intervalo_b4
                       );
		$id = $this->insert($data);
        return $id;
    }
	public function obtener_calificacion($califica_id)
	{
		$row             = $this->fetchRow('califica_id='.(int)$califica_id);
        $resultado = array();
		if($row)
		{
			$resultado = $row;
		}
		return $resultado;
	}
	public function modificar_calificacion($califica_id,$partido,$intervalo_a1,$intervalo_a2,$intervalo_a3,$intervalo_a4,$intervalo_b1,$intervalo_b2,$intervalo_b3,$intervalo_b4)
	{
		$data = array(
                              'partido_id'  => $partido,
                              'intervalo_id1'     =>$intervalo_a1,
                              'intervalo_id2'     =>$intervalo_a2,
                              'intervalo_id3'     =>$intervalo_a3,
                              'intervalo_id4'     =>$intervalo_a4,
                              'intervalo2_id1'     =>$intervalo_b1,
                              'intervalo2_id2'     =>$intervalo_b2,
                              'intervalo2_id3'     =>$intervalo_b3,
                              'intervalo2_id4'     =>$intervalo_b4
                       );
		$this->update($data,'califica_id='.(int)$califica_id);
	}
	public function eliminar_calificacion($califica_id)
	{
		$this->delete('califica_id='.(int)$califica_id);
	}
}