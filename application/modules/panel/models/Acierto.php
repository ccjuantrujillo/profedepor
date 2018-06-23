<?php
class Panel_Model_Acierto extends Zend_Db_Table
{
    protected $_name = "depor_acierto";
	public function listar_aciertos($fecha_id)
	{
            $row = $this->fetchRow('fecha_id='.(int)$fecha_id);
            if($row)
            {
               $row = $this->fetchRow('fecha_id='.(int)$fecha_id);
            }
            return $row->toArray();
	}
   public function obtener_acierto($acierto_id)
   {
       $row = $this->fetchRow('acierto_id='.(int)$acierto_id);
       $resultado = array();
       if($row)
       {
          $resultado = $row->toArray();
       }
       return $resultado;
   }
   public function obtener_acierto_intervalo($fecha_id,$valor){
            $row = $this->fetchAll('fecha_id='.(int)$fecha_id);
            $resultado = array();
            foreach($row->toArray() as $value){
                 if($valor>=$value['valor_inicial'] && $valor<$value['valor_final']){
                      $resultado['acierto_id'] = $value['acierto_id'];
                      break;
                 }
            }
            return $resultado;
   }
   public function obtener_acierto_fecha($fecha,$valor_inicial){
        $db     = $this->getAdapter();
        $where  = $db->quoteInto('fecha_id=?',(int)$fecha)
                          . $db->quoteInto(' and valor_inicial=? ',(int)$valor_inicial);
        $select = $this->select()
                      ->from('depor_acierto')
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
	public function insertar_acierto($fecha,$valor_inicial,$valor_final,$puntaje)
	{
         $objFecha = new Default_Model_Fecha();
         $fechas = $objFecha->getFecha($fecha);
         $fase_id        = $fechas->fase_id;
         $torneo_id  =  $fechas->torneo_id;
		$data = array(
					'fecha_id'          => $fecha,
					'fase_id'            => $fase_id,
					'torneo_id'      => $torneo_id,
					'valor_inicial' => $valor_inicial,
					'valor_final'   => $valor_final,
                    'puntaje'         => $puntaje
					);
		$id = $this->insert($data);
        return $id;
	}
	public function modificar_acierto($acierto,$fecha,$valor_inicial,$valor_final,$puntaje)
	{
        $objFecha = new Default_Model_Fecha();
         $fechas = $objFecha->getFecha($fecha);
         $fase_id        = $fechas->fase_id;
         $torneo_id  =  $fechas->torneo_id;
		$data = array(
					'fecha_id'          => $fecha,
					'fase_id'            => $fase_id,
					'torneo_id'      => $torneo_id,
					'valor_inicial' => $valor_inicial,
					'valor_final'   => $valor_final,
                    'puntaje'         => $puntaje
					);
		$this->update($data,'acierto_id='.(int)$acierto);
	}
	public function eliminar_acierto($acierto_id)
	{
		$this->delete('acierto_id='.(int)$acierto_id);
	}
}
