<?php  
class Default_Model_Puntaje extends Zend_Db_Table
{
    protected $_name = "depor_puntajejuego";
    public function getPuntajeJugadorTipo($jugador,$partido,$tipo)
    {   
        $select = $this->select()
                      ->setIntegrityCheck(false)
                      ->from(array('dpj'=>'depor_puntajejuego'),array('dpj.variable_id','dpj.intervalo_id','dpj.puntaje','dpj.puntajejuego_id','dpj.registro','dpj.partido_id'))
                      ->join(array('di'=>'depor_intervalo'),'dpj.intervalo_id=di.intervalo_id',array('di.descripcion2','descripcion3'=>'di.descripcion'))
                      ->join(array('dv'=>'depor_variable'),'di.variable_id=dv.variable_id',array('dv.descripcion'))
                      ->where('dpj.partido_id=?',(int)$partido)
                     ->where('dpj.jugador_id=? ',(int)$jugador)
                     ->where('dv.tipovariable_id=?',(int)$tipo);
        $stmt   = $select->query();
        $rowset = $stmt->fetchAll();
        $resultado = array();
        if($rowset)
        {
            $i=0;
            foreach($rowset as $indice => $row){
               $partido_id = $row->partido_id;
               $oPartido     = new Default_Model_Partido();
               $partidos      = $oPartido->getPartido($partido_id);
               $resultado[$i]['nombre_equipo']  = ($row->variable_id==2 || $row->variable_id==6 || $row->variable_id==10 || $row->variable_id==11 || $row->variable_id==12 || $row->variable_id==13)?$partidos['nombre_local']:(($row->variable_id==4 || $row->variable_id==8 || $row->variable_id==15 || $row->variable_id==16 || $row->variable_id==17 || $row->variable_id==18)?$partidos['nombre_visitante']:"");
               $resultado[$i]['icono_equipo']       = ($row->variable_id==2 || $row->variable_id==6 || $row->variable_id==10 || $row->variable_id==11 || $row->variable_id==12 || $row->variable_id==13)?$partidos['icono_local']:(($row->variable_id==4 || $row->variable_id==8 || $row->variable_id==15 || $row->variable_id==16 || $row->variable_id==17 || $row->variable_id==18)?$partidos['icono_visitante']:"");
               $resultado[$i]['variable_id']            = $row->variable_id;
               $resultado[$i]['intervalo_id']          = $row->intervalo_id;
               $resultado[$i]['puntaje']                   = $row->puntaje;
               $resultado[$i]['puntajejuego_id'] = $row->puntajejuego_id;
               $resultado[$i]['registro']                   = $row->registro;
               $resultado[$i]['descripcion']           = $row->descripcion;
               $resultado[$i]['descripcion2']         = $row->descripcion2;
               $resultado[$i]['descripcion3']         = $row->descripcion3;
               $i++;
            }
        }
        return $resultado;
    }
    public function getPuntajeTipo($fecha_id,$jugador_id,$tipo_var){
         $db     = $this->getAdapter();
         $stmt = $db->query("select  fns_puntajejugador_fase(?,?,?) as puntaje",array($fecha_id,$jugador_id,$tipo_var));
         $row  =  $stmt->fetchAll();
         $obj  = new stdClass();
         $obj->puntaje = ($row)?$row[0]->puntaje:0;
         return $obj;
    }
   public function getPuntajeJugadorTipoFase3($jugador,$partido)
    {
        $rowset = $this->fetchAll(
                         $this->select()
                              ->where('partido_id='.$partido)
                              ->where('jugador_id='.$jugador)
                        );
        $resultado = array();
        if($rowset)
        {
            $i=0;
            foreach($rowset as $indice => $row){
                $variable          = $row['variable_id'];
                 $intervalo       = $row['intervalo_id'];
                $variables         = new Default_Model_Variable();
                $datos_variable      =  $variables->getVariable($variable);
                $tipovariable_id     =  $datos_variable['tipovariable_id'];
                $nombre_variable = $datos_variable['descripcion'];
                $intervalos               = new Default_Model_Intervalo();
                $datos_intervalo   =$intervalos->getIntervalo($intervalo);
                $nombre_intervalo = $datos_intervalo['descripcion2'];
                if($tipovariable_id==3 || $tipovariable_id==4){
                    $resultado[$i]['variable_id']     = $row['variable_id'];
                    $resultado[$i]['intervalo_id']    = $row['intervalo_id'];
                    $resultado[$i]['puntaje']         = $row['puntaje'];
                    $resultado[$i]['puntajejuego_id'] = $row['puntajejuego_id'];
                    $resultado[$i]['registro']        = $row['registro'];
                    $resultado[$i]['descripcion']     = $nombre_variable;
                    $resultado[$i]['descripcion2']    = $nombre_intervalo;
                    $i++;
                }
            }
        }
        return $resultado;
    }
    public function getPuntaje(stdClass $filter = null)
    {
         $select = $this->select()
                                      ->where('estado=?',1);
         if(isset($filter->partido_id) && $filter->partido_id>0)
                 $select->where ('partido_id=?',$filter->partido_id);
         return $select->query()->fetchAll();
    }
    public function addPuntaje($jugador,$partido,$variable,$intervalo)
    {
        $data = array(
                        'jugador_id'      => $jugador,
                        'partido_id'      => $partido,
                        'variable_id'     => $variable,
                        'intervalo_id'    => $intervalo
                        );
        $this->insert($data);
    }
    public function updatePuntaje($id,$variable,$intervalo)
    {
            $data = array(
                        'variable_id'      => $variable,
                        'intervalo_id'    => $intervalo
                        );
            $this->update($data,'puntajejuego_id='.(int)$id);
    }
    public function deletePuntaje($id)
    {
            $this->delete('puntajejuego_id='.(int)$id);
    }
    public function deletePuntajejuego($fecha_id,$jugador,$fase){
         $db = $this->getAdapter();
         $where = $db->quoteInto('a.fecha_id=?',(int)$fecha_id);
         $select   = $this->select()
                                        ->setIntegrityCheck(false)
                                        ->from(array('a'=>'depor_fecha'))
                                        ->join(array('b'=>'depor_partido'),'a.fecha_id=b.fecha_id')
                                        ->where($where);
         $stmt      = $select->query();
         $rowset = $stmt->fetchAll();
         if($rowset){
              foreach($rowset as $indice=>$value){
                   $partido_id = $value->partido_id;
                    $select2 = $this->select()
                                        ->setIntegrityCheck(false)
                                        ->from(array('a'=>'depor_puntajejuego'))
                                        ->join(array('b'=>'depor_variable'),'a.variable_id=b.variable_id')
                                        ->where("a.jugador_id=$jugador and b.tipovariable_id=$fase and a.partido_id=$partido_id");
                    $rowset2 = $select2->query()->fetchAll();
                    foreach($rowset2 as $indice2=>$value2){
                         //echo $value2->puntajejuego_id."<br>";
                         $puntajejuego_id = $value2->puntajejuego_id;
                         $this->deletePuntaje($puntajejuego_id);
                    }
              }
         }
    }
}