<?php
class Panel_Model_Respuesta extends Zend_Db_Table
{
    protected $_name = "depor_respuesta";
    private $_usuario = null;
    public function init()
    {
         $this->_usuario = 1;
    }
    public function listar_respuestas($fecha){
         $objPartido = new Default_Model_Partido();
         $objPartido->respuesta_id = "";
         $partidos     = $objPartido->getPartidoFecha($fecha);
         return $partidos;
    }
    public function obtener_respuesta_x_tipovar($respuesta,$tipovar)
    {
         $select = $this->select()
                                     ->setIntegrityCheck(false)
                                     ->from(array('dr'=>'depor_respuestaclave'),array('cantidad'=>'count(*)'))
                                     ->join(array('dv'=>'depor_variable'),'dr.variable_id=dv.variable_id',array())
                                     ->where('dr.respuesta_id=?',$respuesta)
                                     ->where('dv.tipovariable_id=?',$tipovar);
         return $select->query()->fetch();
    }
    public function obtener_respuesta($partido)
    {
        $select = $this->select()
                      ->from('depor_respuesta')
                      ->where('partido_id=?',(int)$partido);
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
    public function insertar_respuesta($partido)
    {
        $data = array( 'partido_id'   => $partido,'usuario_id'   => $this->_usuario);
        $id   = $this->insert($data);
        return $id;
    }
    public function modificar_respuesta($respuesta,$partido)
    {
        $data = array('partido_id'   => $partido,'usuario_id'   => $this->_usuario);
        $this->update($data,'respuesta_id='.(int)$respuesta);
    }
    public function eliminar_respuesta($id)
    {
        $this->delete('respuesta_id='.(int)$id);
    }
}
