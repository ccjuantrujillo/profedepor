<?php
class Panel_Model_PuntajeJuego extends Zend_Db_Table
{
    protected $_name = "depor_puntajejuego";
    private $_usuario = null;
    public function init()
    {
         $this->_usuario = 1;
    }
    public function listar_puntajejuego($partido,$jugador){

    }
    public function match_puntajejuego($partido,$jugador){
         //echo "$partido  $jugador<br>";
        $db     = $this->getAdapter();
        $where  = $db->quoteInto('partido_id=?',(int)$partido)
                . $db->quoteInto(' and jugador_id=? ',(int)$jugador);
        $select = $this->select()
                      ->from('depor_puntajejuego')
                      ->where($where);
        $stmt   = $select->query();
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $row       = $stmt->fetchAll();
        $resultado = array();
        $j=0;
        if($row)
        {
             foreach($row as $indice => $value){
                  $partido_id     = $value->partido_id;
                  $variable_id   = $value->variable_id;
                  $intervalo_id = $value->intervalo_id;
                  $claves_mod  = new Panel_Model_Clave();
                  $claves = $claves_mod->listar_clavejuego($partido_id,$variable_id,$intervalo_id);//Matcheo
                  if(count($claves)>0){
                       $variable_id2  = $claves[0]->variable_id;
                       $intervalo_id2 = $claves[0]->intervalo_id;
                       $clave_id2     = $claves[0]->clave_id;
                       $puntaje2      = $claves[0]->puntaje;
                       if($variable_id==$variable_id2 && $intervalo_id==$intervalo_id2){
                           $resultado[$j] = $value;
                           $resultado[$j]->clave_id = $clave_id2;
                           $resultado[$j]->puntaje  = $puntaje2;
                           $j++;
                       }
                  }
             }
        }
        return $resultado;
    }
}