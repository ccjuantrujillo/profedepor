<?php
class Default_Model_Variable extends Zend_Db_Table
{
    protected $_name = "depor_variable";
    public function getVariables()
    {
        $rowset = $this->fetchAll(
                              $this->select()
                                       ->where('estado=1')
                              );
        $row       = $rowset->toArray();
        $resultado = array();
        if($row){
             foreach($row as $indice=>$value){
                  $variable_id = $value['variable_id'];
                  $oIntervalo  = new Default_Model_Intervalo();
                  $intervalos = $oIntervalo->getIntervaloVariable($variable_id);
                  $row[$indice]['intervalos'] = $intervalos;
             }
             $resultado = $row;
        }
        return $resultado;
    }
    public function getVariable($id)
    {
        $row = $this->fetchRow('variable_id='.(int)$id);
        $resultado = array();
        if($row)
        {
            $resultado = $row->toArray();
        }
        return $resultado;
    }
    public function getVariableTipo($tipo)
    {
        $rowset = $this->fetchAll(
                            $this->select()
                                     ->where('tipovariable_id='.(int)$tipo)
                                     ->where('estado=?',1)
                );
       $row = $rowset->toArray();
        $resultado = array();
        if($row)
        {
             foreach($row as $indice=>$value){
                  $variable_id = $value['variable_id'];
                  $oIntervalo  = new Default_Model_Intervalo();
                  $intervalos = $oIntervalo->getIntervaloVariable($variable_id);
                  $row[$indice]['intervalos'] = $intervalos;
             }
             $resultado = $row;
        }
        return $resultado;
    }
    public function getVariableSigla($sigla)
    {
        $row = $this->fetchAll('sigla='.$sigla);
        $resultado = array();
        if($row)
        {
            $resultado = $row->toArray();
        }
        return $resultado;
    }
    public function addVariable($tipo,$descripcion,$puntaje)
    {
        $data = array(
                        'tipovariable_id'  => $tipo,
                        'descripcion'         => $descripcion,
                        'puntaje'                 => $puntaje
                        );
         $this->insert($data);
    }
    public function updateVariable($id,$tipo,$descripcion,$puntaje)
    {
        $data = array(
                        'tipovariable_id'  => $tipo,
                        'descripcion'         => $descripcion,
                        'puntaje'                 => $puntaje
                        );
            $this->update($data,'variable_id='.(int)$id);
    }
    public function deleteVariable($id)
    {
            $this->delete('variable_id='.(int)$id);
    }
}
