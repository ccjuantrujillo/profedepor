<?php
class Default_Model_Intervalo extends Zend_Db_Table
{
    protected $_name = "depor_intervalo";
    public function getIntervalo($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('intervalo_id='.$id);
        if(!$row)
        {
            throw new Exception("No se encuentra la fila $id");
        }
        return $row->toArray();
    }
    public function getIntervaloTipo($tipo)
    {
        $rowset = $this->fetchAll(
                              $this->select()
                                        ->where('estado=?',1)
                              );
        $resultado = array();
        if($rowset)
        {
             $i=0;
            foreach($rowset as $indice => $row){
                 $intervalo_id = $row['intervalo_id'];
                $variable_id = $row['variable_id'];
                $descripcion = $row['descripcion'];
                $descripcion2 = $row['descripcion2'];
                $puntaje         = $row['puntaje'];
                $variables   = new Default_Model_Variable();
                $datos_variable  = $variables->getVariable($variable_id);
                $tipovariable_id = $datos_variable['tipovariable_id'] ;
                if($tipovariable_id==$tipo){
                    $resultado[$i]['intervalo_id'] = $intervalo_id;
                    $resultado[$i]['variable_id']  = $variable_id;
                    $resultado[$i]['descripcion']  = $descripcion;
                    $resultado[$i]['descripcion2'] = $descripcion2;
                    $resultado[$i]['puntaje']      = $puntaje;
                    $resultado[$i]['tipo']         = $tipo;
                    $i++;
                }
            }
        }
        return $resultado;
    }
    public function getIntervaloVariable($variable)
    {
        $row = $this->fetchAll(
                          $this->select()
                                   ->where('variable_id='.$variable)
                                   ->where('estado=?',1)
                     );
        if(!$row)
        {
            throw new Exception("No se encuentra la fila $variable");
        }
        return $row->toArray();
    }
    public function addIntervalo($variable,$descripcion,$puntaje_inter,$puntaje_var)
    {
        $data = array(
                        'variable_id'        => $variable,
                        'descripcion'       => $descripcion,
                        'puntaje_inter'  => $puntaje_inter,
                        'puntaje_var'      => $puntaje_var
                        );
          $this->insert($data);
    }
    public function updateIntervalo($variable,$descripcion,$puntaje_inter,$puntaje_var)
    {
        $data = array(
                        'variable_id'        => $variable,
                        'descripcion'       => $descripcion,
                        'puntaje_inter'  => $puntaje_inter,
                        'puntaje_var'      => $puntaje_var
                        );
            $this->update($data,'intervalo_id='.(int)$id);
    }
    public function deleteIntervalo($id)
    {
            $this->delete('intervalo_id='.(int)$id);
    }
}
