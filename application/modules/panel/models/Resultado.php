<?php
class Panel_Model_Resultado extends Zend_Db_Table
{
    protected $_name = "depor_resultado";
    private $_usuario = null;
    public function init()
    {
         $this->_usuario = 1;
    }
    public function listar_resultados($fecha){
         $objPartido = new Default_Model_Partido();
         $partidos     = $objPartido->getPartidoFecha($fecha);
         $resultado  = array();
         foreach ($partidos as $indice => $value){
              $resultado[$indice] = $value;
              $resultado[$indice]->resultados  = $this->obtener_resultado_partido($value->id);
         }
         return $resultado;
    }
	public function obtener_resultado($resultado)
	{
		$row = $this->fetchRow('resultado_id='.(int)$id);
		if(!$row)
		{
			throw new Exception("No se encuentra la fila $id");
		}
		return $row->toArray();
	}
    public function obtener_resultado_partido($partido){
		$row = $this->fetchRow('partido_id='.(int)$partido);
        //$resultado = array("goles_local"=>"","goles_visita"=>"","resultado_id"=>"","partido_id"=>"","usuario_id"=>"","clave_id1"=>"","clave_id2"=>"","registro"=>"","modificacion"=>"","estado"=>"");
		$resultado = array();
        if($row)
		{
			$resultado = $row->toArray();
		}
		return $resultado;
    }
    public function insertar_resultado($partido,$goles_local,$goles_visita,$clave_id1,$clave_id2)
	{
		$data = array(
                              'partido_id'     => $partido,
                              'goles_local'   => $goles_local,
                              'goles_visita'  => $goles_visita,
                              'clave_id1'      =>  $clave_id1,
                              'clave_id2'      =>  $clave_id2,
                              'usuario_id'    => $this->_usuario
                       );
		$this->insert($data);
	}
	public function modificar_resultado($resultado,$partido,$goles_local,$goles_visita)
	{
		$data = array(
                              'partido_id'     => $partido,
                              'goles_local'   =>$goles_local,
                              'goles_visita'  =>$goles_visita,
                              'usuario_id'    => $this->_usuario
                       );
		$this->update($data,'resultado_id='.(int)$resultado);
	}
	public function eliminar_resultado($id)
	{
		$this->delete('resultado_id='.(int)$id);
	}
}