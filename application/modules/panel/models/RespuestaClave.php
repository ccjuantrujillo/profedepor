<?php
class Panel_Model_RespuestaClave extends Zend_Db_Table
{
    protected $_name = "depor_respuestaclave";
    private $_usuario = null;
    public function init()
    {
         $this->_usuario = 1;
    }
    public function listar_respuestaclave($respuesta){
        $db     = $this->getAdapter();
        $where  = $db->quoteInto('respuesta_id=?',(int)$respuesta);
        $select = $this->select()
                      ->from('depor_respuestaclave')
                      ->where($where);
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
    public function obtener_respuestaclave($id)
    {
            $row = $this->fetchRow('respuestaclave_id='.(int)$id);
            if(!$row)
            {
                throw new Exception("No se encuentra la fila $id");
            }
            return $row->toArray();
    }
    public function obtener_respuestaclave_respuesta($respuesta)
    {
          $row = $this->fetchAll('respuesta_id='.(int)$respuesta);
          $resultado = array();
          if($row)
          {
           $resultado = $row->toArray();
          }
          return $resultado;
    }
    public function obtener_respuestaclave_partido($partido_id,$tipovar){
         $select = $this->select()
                                       ->setIntegrityCheck(false)
                                       ->from(array('drc'=>'depor_respuestaclave'),array('drc.variable_id','drc.intervalo_id','drc.puntajedepor'))
                                       ->join(array('di'=>'depor_intervalo'),'drc.intervalo_id=di.intervalo_id',array('di.descripcion','di.descripcion2','di.valori','di.valorf'))
                                       ->join(array('dv'=>'depor_variable'),'di.variable_id=dv.variable_id',array('descripcion3'=>'dv.descripcion'))
                                       ->join(array('dr'=>'depor_respuesta'),'drc.respuesta_id=dr.respuesta_id',array('dr.partido_id'))
                                       ->where('dr.partido_id=?',$partido_id)
                                        ->where('dv.tipovariable_id=?',$tipovar);
         return $select->query()->fetchAll();
    }
    public function insertar_respuestaclave($respuesta,$variable,$puntos)
	{
         $db     = $this->getAdapter();
         $stmt = $db->query("CALL pns_insertar_respuestaclave(?,?,?)",array($respuesta,$variable,$puntos));
         $row  = $stmt->fetchAll();
         $db->closeConnection();
         return 1;
	}
	public function modificar_respuestaclave($id,$respuesta,$variable,$intervalo)
	{
            $data = array(
                          'respuesta_id' => $respuesta,
                          'variable_id'  => $variable,
                          'intervalo_id' => $intervalo
                   );
            $this->update($data,'respuestaclave_id='.(int)$id);
	}
	public function eliminar_respuestaclave($respuesta_id)
	{
            $this->delete('respuesta_id='.(int)$respuesta_id);
	}
    public function eliminar_respuestaclave_tipo($respuesta_id,$tipo){
         $db     = $this->getAdapter();
         $stmt = $db->query("CALL pns_eliminar_respuestaclave(?,?)",array($respuesta_id,$tipo));
         $row  = $stmt->fetchAll();
         $db->closeConnection();
         return 1;
    }
}