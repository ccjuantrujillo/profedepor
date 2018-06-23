<?php
class Panel_Model_PuntajeFecha extends Zend_Db_Table
{
    protected $_name = "depor_puntajefecha";
    private $_usuario = null;
    public function init()
    {
         $this->_usuario = 1;
    }
    public function listar_puntajefecha($fecha,$jugador){
        $db     = $this->getAdapter();
        $where  = $db->quoteInto('fecha_id=?',(int)$fecha)
                          . $db->quoteInto(' and jugador_id=? ',(int)$jugador);
        $select = $this->select()
                      ->from('depor_puntajefecha')
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
    public function get_puntajefecha($fecha,$jugador){
        $db     = $this->getAdapter();
        $where  = $db->quoteInto('fecha_id=?',(int)$fecha)
                          . $db->quoteInto(' and jugador_id=?',(int)$jugador);
        $select = $this->select()
                      ->from('depor_puntajefecha')
                      ->where($where);
        $stmt   = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row    = $stmt->fetch();
        $resultado = array();
        if($row)
        {
            $resultado = $row;
        }
        return $resultado;
    }
    
    public function insertar_puntajefecha_juego($fecha,$fase,$torneo,$jugador,$puntajejuego){
        $data = array(
                      'fecha_id'  => $fecha,
                      'fase_id'        => $fase,
                      'torneo_id'     => $torneo,
                      'jugador_id'  => $jugador,
                      'puntaje_juego'  => $puntajejuego
               );
        $id   = $this->insert($data);
        return $id;
    }
    public function insertar_puntajefecha($fecha_id, $fase_id, $torneo_id, $jugador_id, $puntaje_juego,$puntaje_pregunta,$puntaje_acierto){
        $data = array(
                      'fecha_id'  => $fecha_id,
                      'fase_id'        => $fase_id,
                      'torneo_id'     => $torneo_id,
                      'jugador_id'  => $jugador_id,
                      'puntaje_juego'  => $puntaje_juego,
                      'puntaje_pregunta' => $puntaje_pregunta,
                      'puntaje_acierto' => $puntaje_acierto
               );
        $id   = $this->insert($data);
        return $id;
    }
    public function  obtener_puntajefecha($puntajefecha){
        $row = $this->fetchRow('puntajefecha_id='.(int)$puntajefecha);
        $resultado = array();
        if($row){
             $resultado = $row->toArray();
        }
        return $resultado;
    }
    public function  obtener_puntajefecha2($fecha_id, $jugador_id){//Para el puntajepregunta
        $db     = $this->getAdapter();
        $where  = $db->quoteInto('fecha_id=?',(int)$fecha_id)
                          . $db->quoteInto(' and jugador_id=? ',(int)$jugador_id);
        $select = $this->select()
                      ->from('depor_puntajefecha')
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
    function obtener_puntajejugador($jugador){
         $columnas = array('puntaje_total'=>new Zend_Db_Expr('sum(puntaje_total)'));
          $stmt      = $this->select()->from('depor_puntajefecha',$columnas)->where('jugador_id=?',(int)$jugador);
          $rowset = $stmt->query()->fetchAll();
          $resultado = 0;
          foreach($rowset as $value){
               $resultado = $resultado + $value->puntaje_total;
          }
          return $resultado;
    }
    public function  modificar_puntajefecha($puntajefecha_id,$fecha_id, $jugador_id, $puntaje_juego,$puntaje_pregunta,$puntaje_acierto){
        $data = array(
                      'fecha_id'  => $fecha_id,
                      'jugador_id'  => $jugador_id,
                      'puntaje_juego'  => $puntaje_juego,
                      'puntaje_pregunta' => $puntaje_pregunta,
                      'puntaje_acierto'     => $puntaje_acierto
               );
        $this->update($data,'puntajefecha_id='.(int)$puntajefecha);
    }
    public function eliminar_puntajefecha($puntajefecha){
        $this->delete('puntajefecha_id='.(int)$puntajefecha);
    }
    public function eliminar_puntajefecha_x_fecha($fecha_id){
        $this->delete('fecha_id='.(int)$fecha_id);
    }
}