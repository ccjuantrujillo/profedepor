<?php
class Panel_Model_Puntaje extends Zend_Db_Table
{
    protected $_name = "depor_puntaje";
    private $_usuario = null;
    public function init()
    {
         $this->_usuario = 1;
    }
    public function calcular_puntos($partido_id){
         $db     = $this->getAdapter();
         $stmt = $db->query("CALL pns_calculo_puntaje_x_partido(?)",array($partido_id));
         $row  = $stmt->fetchAll();
          $db->closeConnection();
         return true;
    }
    public function calcular_puntos_total($fecha_id){
         $db     = $this->getAdapter();
         $stmt = $db->query("CALL pns_calculo_puntaje(?)",array($fecha_id));
         $row  = $stmt->fetchAll();
          $db->closeConnection();
         return true;
    }
}