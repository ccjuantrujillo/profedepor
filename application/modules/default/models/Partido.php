<?php
class Default_Model_Partido extends Zend_Db_Table
{
    protected $_name = "depor_partido";
private static $_primeraFechaSql = null;

    public function _getPrimeraFechaSql(){
        if(self::$_primeraFechaSql === null){
            self::$_primeraFechaSql = $this->select()
                ->from($this->_name, array('fecha_partido' => "DATE_FORMAT(fecha_partido, '%d/%m/%Y')"))
                ->order('fecha_partido ASC')
                ->limit(1);
        }

        return clone self::$_primeraFechaSql;
    }

    public function getPrimeraFecha($fecha_id){
        return $this->_getPrimeraFechaSql()
            ->where("fecha_id = $fecha_id")
            ->query()->fetch();
    }

    public function getPartidoRand(){
        $fecha = new Default_Model_Fecha();

        $fecha_id = $fecha->getFechaHoy()-1;
        return $this->select()
            ->from(array('P' => $this->_name), array(
                '*', 'fecha_partido' => "DATE_FORMAT(fecha_partido, '%m/%d')"))
            ->where('P.fecha_id = ?', $fecha_id)
            ->order('RAND()')
            ->limit(1)
            ->query()
            ->fetch();
    }

	public function getPartido($partido_id)
	{
         $select = $this->select()
                                       ->setIntegrityCheck(false)
                                       ->from(array('dp'=>'depor_partido'),array('dp.fecha_partido','dp.fecha_id','dp.torneo_id','dp.fase_id','dp.fase1','dp.fase2','dp.fase3'))
                                       ->join(array('el'=>'depor_equipo'),'dp.equipo_local=el.equipo_id',array('equipo_local'=>'el.equipo_id'))
                                       ->join(array('ev'=>'depor_equipo'),'dp.equipo_visitante=ev.equipo_id',array('equipo_visitante'=>'ev.equipo_id'))
                                        ->join(array('cl'=>'depor_club'),'el.club_id=cl.club_id',array('club_local'=>'cl.club_id','nombre_local'=>'cl.descripcion','icono_local'=>'cl.icono'))
                                        ->join(array('cv'=>'depor_club'),'ev.club_id=cv.club_id',array('club_visitante'=>'cv.club_id','nombre_visitante'=>'cv.descripcion','icono_visitante'=>'cv.icono'))
                                        ->where('dp.estado=?',1)
                                        ->where('dp.partido_id=?',(int)$partido_id);
         $stmt   = $select->query();
         $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
         return $stmt->fetch();
	}

 public function getPartidoDefault(stdClass $filter = null, stdClass $params = null){
        if(!isset($params->fecha_format)){
        	$params = new stdClass();
            $params->fecha_format = '%d/%m/%Y %r';
        }
        $select = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('P' => $this->_name), array(
                'fecha_partido' => "DATE_FORMAT(fecha_partido, '$params->fecha_format')",
                'situacion', 'partido_id', 'fecha_id', 'ficha','fase1','fase2','fase3'
            ))
            ->join(array(
                'F' => 'depor_fecha'
            ), 'P.fecha_id = F.fecha_id', array(
                'fecha' => 'descripcion',
                'intervalo' => 'intervalo'
            ))

            ->join(array(
                'C' => 'depor_club'
            ), 'P.equipo_local = C.club_id', array(
                'local' => 'C.descripcion',
                'icono_local' => 'C.icono'
            ))

            ->join(array(
                'C2' => 'depor_club'
            ), 'P.equipo_visitante = C2.club_id', array(
                'visitante' => 'C2.descripcion',
                'icono_visitante' => 'C2.icono'
            ))

            ->join(array(
                'R' => 'depor_resultado'
            ), 'P.partido_id = R.partido_id', array('goles_local', 'goles_visita')
            )

            ->where('P.estado = ?', 1);

        if(isset($filter->fase_id) && $filter->fase_id > 0)
            $select->where('P.fase_id = ?', $filter->fase_id);

        if(isset($filter->fecha_id) && $filter->fecha_id > 0)
            $select->where('P.fecha_id = ?', $filter->fecha_id);

        if(isset($filter->partido_id) && $filter->partido_id > 0)
            $select->where('P.partido_id = ?', $filter->partido_id);

        # exit($select);
        return $select->query();
    }
    
	public function getPartidoFecha($fecha)
	{
            $row     = $this->select()
                                       ->where('fecha_id='.$fecha)
                                       ->query()
                                       ->fetchAll();
             $listado = array();
            if($row)
            {
                   foreach($row as $fila){
                          $fecha         = $fila->fecha_id;
                          $fecha_partido = $fila->fecha_partido;
                          $local          = $fila->equipo_local;
                          $visita         = $fila->equipo_visitante;
                          $date1        = new Zend_Date($fila->fecha_proceso);
                          $fecha_proceso = $date1->get(Zend_Date::DATES);
                          $calculado  = $fila->calculado;
                          $equipo = new Default_Model_Equipo();
                          $datos_local  = $equipo->getEquipo($local);
                          $datos_visita = $equipo->getEquipo($visita);
                          $club_local   = $datos_local['club_id'];
                          $club_visita  = $datos_visita['club_id'];
                          $club   = new Default_Model_Club();
                          $datos_club_local  = $club->getClub($club_local);
                          $datos_club_visita = $club->getClub($club_visita);
                          $nombre_local  = $datos_club_local['descripcion'];
                          $nombre_visita = $datos_club_visita['descripcion'];
                          $icono_local       = $datos_club_local['icono'];
                          $icono_visita      = $datos_club_visita['icono'];
                          $dia = substr($fecha_partido, 8, 2);
                          $mes = substr($fecha_partido, 5, 2);
                          $obj    = new stdClass();
                          $obj->id                 = $fila->partido_id;
                          $obj->fecha          = $fecha;
                          $obj->fase            = $fila->fase_id;
                          $obj->torneo       = $fila->torneo_id;
                          $obj->local           = $nombre_local;
                          $obj->visita          = $nombre_visita;
                          $obj->situacion  = $fila->situacion;
                          $obj->fecha_partido  = $fecha_partido;
                          $obj->fecha_proceso = $fecha_proceso;
                          $obj->fecha_formato = "$dia/$mes";
                          $obj->icono_local       = $icono_local;
                          $obj->icono_visita     = $icono_visita;
                          $obj->calculado         = $calculado;
                          $obj->fase1                 = $fila->fase1;
                          $obj->fase2                 = $fila->fase2;
                          $obj->fase3                 = $fila->fase3;
                          $listado[]=$obj;
                      }
            }
            return $listado;
	}
     public function getPartidoFase1($fecha,$jugador)
     {
        $db   = $this->getAdapter();
        $stmt = $db->prepare("CALL pns_getpartidofase(?,?,1)");
        $stmt->bindParam(1,$fecha);
        $stmt->bindParam(2,$jugador);
        $stmt->execute();
        $row  = $stmt->fetchAll();
        $db->closeConnection();
        $listado = array();
         if($row)
         {
             foreach($row as $fila){
                 $partido = $fila->partido_id;
                 $oDate         = new Zend_Date($fila->fecha_partido);
                 $oDate2       = new Zend_Date();
                 $fecha_partido = $oDate->get(Zend_Date::DATES);
                 $hora_partido  = $oDate->get(Zend_Date::TIMES);
                 if($fila->calculado==1){
                      $disabled = false;
                 }
                 elseif($fila-> calculado==0){
                    $disabled      = $this->valida_fecha($oDate, $oDate2);
                 }
               $puntaje_jugador = $this->puntaje_jugador($jugador,$partido,1);
               $intervalo_res        = "";
               $variable_res          = "";
               $puntajejuego_id = "";
               if(count($puntaje_jugador)>0){
                    $puntajejuego_id  = $puntaje_jugador[$partido]['puntajejuego_id'];
                    $intervalo_res        = $puntaje_jugador[$partido]['intervalo_res'];
                    $variable_res          = $puntaje_jugador[$partido]['variable_res'];
               }
               //Armo matriz de intervalos
               $oIntervalo = new Default_Model_Intervalo();
               $intervalos = $oIntervalo->getIntervaloTipo(1);
               foreach($intervalos as $indice2=>$value2){
                    $intervalo_id = $value2['intervalo_id'];
                    $variable_id  = $value2['variable_id'];
                    $puntaje      = $value2['puntaje'];
                    if($intervalo_id==$intervalo_res && $variable_id==$variable_res){
                        $intervalos[$indice2]['checked']=true;
                    }
                    else{
                        $intervalos[$indice2]['checked']=false;
                    }
                 }
                 $arrFec = explode("/",$fecha_partido);
                 $obj                                         = new stdClass();
                 $obj->id                                = $partido;
                 $obj->fecha                        = $fila->fecha_id;
                 $obj->fecha_partido       = $arrFec[0]."/".$arrFec[1];
                 $obj->fase                           = $fila->fase_id;
                 $obj->torneo                      = $fila->torneo_id;
                 $obj->local                          = $fila->nombre_local;
                 $obj->visita                         = $fila->nombre_visita;
                 $obj->puntajejuego_id  = $puntajejuego_id;
                 $obj->registro                    = $fila->registro;
                 $obj->intervalos               = $intervalos;
                 $obj->readonly                  = $disabled;
                 $obj->icono_local            = $fila->icono_local;
                 $obj->icono_visita          = $fila->icono_visita;
                 $listado[]=$obj;
             }
         }
         return $listado;
    }
      public function getPartidoFase2($fecha,$jugador)
     {
        $db   = $this->getAdapter();
        $stmt = $db->prepare("CALL pns_getpartidofase(?,?,2)");
        $stmt->bindParam(1,$fecha);
        $stmt->bindParam(2,$jugador);
        $stmt->execute();
        $row  = $stmt->fetchAll();
        $db->closeConnection();
        $listado = array();
         if($row)
         {
             foreach($row as $fila){
                 $partido = $fila->partido_id;
                 $oDate         = new Zend_Date($fila->fecha_partido);
                 $oDate2       = new Zend_Date();
                 $fecha_partido = $oDate->get(Zend_Date::DATES);
                 $hora_partido  = $oDate->get(Zend_Date::TIMES);
                 if($fila->calculado==1){
                      $disabled = false;
                 }
                 elseif($fila-> calculado==0){
                    $disabled      = $this->valida_fecha($oDate, $oDate2);
                 }
                 $nombre_local     = $fila->nombre_local;
                 $nombre_visita    = $fila->nombre_visita;
                 $icono_local          = $fila->icono_local;
                 $icono_visita         = $fila->icono_visita;
                 $puntajejuego_id  = "";
                 $partido_res            = "";
                 $intervalo_res        = "";
                 $variable_res          = "";
                 $registro_res          =  "";
                 $puntaje_res          =  "";
                 $puntaje_jugador = $this->puntaje_jugador($jugador,$partido,2);
                if(count($puntaje_jugador)>0){
                    $puntajejuego_id  = $puntaje_jugador[$partido]['puntajejuego_id'];
                    $partido_res            = $puntaje_jugador[$partido]['partido_res'];
                    $intervalo_res        = $puntaje_jugador[$partido]['intervalo_res'];
                    $variable_res          = $puntaje_jugador[$partido]['variable_res'];
                    $registro_res          =  $puntaje_jugador[$partido]['registro_res'];
                    $puntaje_res          =  $puntaje_jugador[$partido]['puntaje_res'];
               }
               $nom_variable     = "";
               $equipo_variable  = "";
               $icono_variable   = "";
               $situacion        = 0;
               for($j=2;$j<5;$j++){
                      switch($j){
                           case 2:     $situacion=1; $nom_variable = "Gana ";$equipo_variable=$nombre_local;$icono_variable=$icono_local;break;
                           case 3:     $situacion=2; $nom_variable = "Empate ";$equipo_variable="";$icono_variable="";break;
                           case 4:      $situacion=3;$nom_variable = "Gana  ";$equipo_variable=$nombre_visita;$icono_variable=$icono_visita;break;
                           default:   $situacion=0;$nom_variable = "";$equipo_variable="";$icono_variable="";break;
                      }
               }
                 //Armo matriz de intervalos
                 $oIntervalo = new Default_Model_Intervalo();
                 $intervalos = $oIntervalo->getIntervaloTipo(2);
                 foreach($intervalos as $indice2=>$value2){
                    $intervalo_id = $value2['intervalo_id'];
                    $variable_id  = $value2['variable_id'];
                    $puntaje      = $value2['puntaje'];
                         if($intervalo_id==$intervalo_res && $variable_id==$variable_res){
                             $intervalos[$indice2]['checked']=true;
                         }
                         else{
                             $intervalos[$indice2]['checked']=false;
                         }
                 }
                 $arrFec = explode("/",$fecha_partido);
                 $obj                                         = new stdClass();
                 $obj->id                                = $partido;
                 $obj->fecha                        = $fila->fecha_id;
                 $obj->fecha_partido       = $arrFec[0]."/".$arrFec[1];
                 $obj->fase                           = $fila->fase_id;
                 $obj->torneo                      = $fila->torneo_id;
                 $obj->local                          = $nombre_local;
                 $obj->visita                         = $nombre_visita;
                 $obj->intervalos               = $intervalos;
                 $obj->puntaje                    = 0;
                 $obj->puntajejuego_id  = $puntajejuego_id;
                 $obj->readonly                  = $disabled;
                 $obj->registro                    = $registro_res;
                 $obj->icono_local            = $icono_local;
                 $obj->icono_visita          = $icono_visita;
                 $obj->nom_variable      = $nom_variable;
                 $obj->nombre_variable = $equipo_variable;
                 $obj->icono_variable    = $icono_variable;
                 $obj->situacion                = $situacion;
                 $listado[]=$obj;
             }
         }
         return $listado;
    }
    public function getPartidoFase3($fecha,$jugador)
     {
        $db   = $this->getAdapter();
        $stmt = $db->prepare("CALL pns_getpartidofase(?,?,3)");
        $stmt->bindParam(1,$fecha);
        $stmt->bindParam(2,$jugador);
        $stmt->execute();
        $row  = $stmt->fetchAll();
        $db->closeConnection();
        $listado = array();
         if($row)
         {
             foreach($row as $fila){
                 $partido = $fila->partido_id;
                 $fecha  = $fila->fecha_id;
                 $fase   = $fila->fase_id;
                 $torneo = $fila->torneo_id;
                 $oDate         = new Zend_Date($fila->fecha_partido);
                 $oDate2       = new Zend_Date();
                 $fecha_partido = $oDate->get(Zend_Date::DATES);
                 $hora_partido  = $oDate->get(Zend_Date::TIMES);
                 if($fila->calculado==1){
                      $disabled = false;
                 }
                 elseif($fila-> calculado==0){
                    $disabled      = $this->valida_fecha($oDate, $oDate2);
                 }                 
                 $local  = $fila->equipo_local;
                 $visita = $fila->equipo_visitante;
                 $datos_local  = $this->datos_equipo($local);
                 $datos_visita = $this->datos_equipo($visita);
                 $nombre_local   = $fila->nombre_local;
                 $nombre_visita  = $fila->nombre_visita;
                 $icono_local        = $fila->icono_local;
                 $icono_visita       = $fila->icono_visita;
                   $puntaje_jugador    = $this->puntaje_jugadorNivel3($jugador,$partido);
                   $puntaje_jugador1 = $this->puntaje_jugador($jugador,$partido,1);
                   if(count($puntaje_jugador)>0){
                         $puntajejuego_id  = $puntaje_jugador[$partido]['puntajejuego_id'];
                         $partido_res            = $puntaje_jugador[$partido]['partido_res'];
                         $intervalo_res        = $puntaje_jugador[$partido]['intervalo_res'];
                         $variable_res          = $puntaje_jugador[$partido]['variable_res'];
                         $registro_res          =  $puntaje_jugador[$partido]['registro_res'];
                         $puntaje_res          =  $puntaje_jugador[$partido]['puntaje_res'];
                         $situacion                = 1;
                   }
                   else{
                        //Busco datos en la sesion
                        $situacion                 = count($puntaje_jugador1)>0?"2":"3";
                        $partido_res            = array();
                        $puntajejuego_id  = array();
                         $intervalo_res        = array();
                         $variable_res          = array();
                         $registro_res          =  array();
                         $puntaje_res          =  array();
                   }
                   if(count($variable_res)>0 && count($intervalo_res)>0){
                        $arrVariables = array_combine($variable_res, $intervalo_res);
                   }
                  else{
                       $arrVariables = array();
                  }
                 $oIntervalo = new Default_Model_Intervalo();
                 $intervalos = $oIntervalo->getIntervaloTipo(3);
                 $oIntervalo2 = new Default_Model_Intervalo();
                 $intervalos2 = $oIntervalo2->getIntervaloTipo(4);
                 $intervalos  = array_merge($intervalos,$intervalos2);
                 //Veo si a ganado local, empate o visita,solo tipo2
                 $nom_variable      = "";
                 $equipo_variable = "";
                 $icono_variable    = "";
                 $califica                   = "";
                 foreach($intervalos as $indice2=>$value2){
                    $intervalo_id = $value2['intervalo_id'];
                    $variable_id  = $value2['variable_id'];
                    $puntaje         = $value2['puntaje'];
                    $intervalos[$indice2]['checked']=false;
                    $tipo                = $value2['tipo'];
                    if(count($arrVariables)>0){
                         foreach($arrVariables as $vars_respuesta=>$intervals_respuesta){
                              if($intervalo_id==$intervals_respuesta && $variable_id==$vars_respuesta){
                                   $califica          = $tipo;
                                  $intervalos[$indice2]['checked']=true;
                                  break;
                              }
                              else{
                                  $intervalos[$indice2]['checked']=false;
                              }
                         }
                    }
                 }
                 $arrFec = explode("/",$fecha_partido);
                 $obj                                         = new stdClass();
                 $obj->id                                = $partido;
                 $obj->fecha                        = $fecha;
                 $obj->fecha_partido       = $arrFec[0]."/".$arrFec[1];
                 $obj->fase                           = $fase;
                 $obj->torneo                      = $torneo;
                 $obj->local                          = $nombre_local;
                 $obj->visita                         = $nombre_visita;
                 $obj->intervalos               = $intervalos;
                 $obj->puntaje                    = 0;
                 $obj->puntajejuego_id  = $puntajejuego_id;
                 $obj->readonly                  = $disabled;
                 $obj->registro                    = $registro_res;
                 $obj->icono_local            = $icono_local;
                 $obj->icono_visita          = $icono_visita;
                 $obj->nom_variable      = $nom_variable;
                 $obj->nombre_variable = $equipo_variable;
                 $obj->icono_variable    = $icono_variable;
                 $obj->situacion                = $situacion;
                 $obj->califica                    = $califica;
                 $listado[]=$obj;
             }
         }
         return $listado;
    }
    public function valida_fecha($fecha_partido,$fecha_hoy){
        $hora_partido_timestamp = $fecha_partido->get(Zend_Date::TIMESTAMP);
        $hora_hoy_timestamp     = $fecha_hoy->get(Zend_Date::TIMESTAMP);
        $oConfiguracion = new Default_Model_Configuracion();
        $configuracion = $oConfiguracion->getConfiguracion();
        $tolerancia        = 60*$configuracion->tolerancia;
        if($fecha_hoy->equals($fecha_partido->get(Zend_Date::DATES),Zend_Date::DATES)){
            if(($hora_hoy_timestamp-$hora_partido_timestamp)>0){
                 $resultado = false;
            }
            else{
                 if(($hora_partido_timestamp-$hora_hoy_timestamp)>$tolerancia){
                      $resultado = true;
                 }
                else{
                     $resultado=false;
                }
            }
        }
        else {
            if(($hora_partido_timestamp-$hora_hoy_timestamp)>0){
                $resultado = true;
            }
            else{
                $resultado = false;
            }

        }
        return $resultado;
    }
    public function nombre_equipo($id){
          $equipo = new Default_Model_Equipo();
          $datos_equipo  = $equipo->getEquipo($id);
          $club_id               = $datos_equipo['club_id'];
          $club   = new Default_Model_Club();
          $datos_club       = $club->getClub($club_id);
          return $datos_club['descripcion'];
    }
    public function datos_equipo($id){
          $equipo = new Default_Model_Equipo();
          $datos_equipo  = $equipo->getEquipo($id);
          $club_id               = $datos_equipo['club_id'];
          $club   = new Default_Model_Club();
          $datos_club       = $club->getClub($club_id);
          return $datos_club;
    }
	public function updatePartidoSituacion($partido,$situacion)
	{
		$data = array('situacion'  => $situacion);
		$this->update($data,'partido_id='.(int)$partido);
	}
	public function updatePartidoFechaProceso($partido,$fecha)
	{
		$data = array('fecha_proceso'  => $fecha);
		$this->update($data,'partido_id='.(int)$partido);
	}
    public function puntaje_jugador($jugador,$partido,$tipo){
               $oPuntaje            = new Default_Model_Puntaje();
                 $puntajejuego = $oPuntaje->getPuntajeJugadorTipo($jugador,$partido,$tipo);
                 $resultado         = array();
                 if(count($puntajejuego)>0){
                      foreach($puntajejuego as $i=>$valor){
                           $variable_res   = $valor['variable_id'];
                          $intervalo_res = $valor['intervalo_id'];
                           $puntaje_res   =  $valor['puntaje'];
                           $registro_res   =  $valor['registro'];
                           $puntajejuego_id = $valor['puntajejuego_id'];
                           $resultado[$partido] =  array(
                                                       'puntajejuego_id' => $puntajejuego_id,
                                                       'partido_res'           => $partido,
                                                       'intervalo_res'       => $intervalo_res,
                                                       'variable_res'         => $variable_res,
                                                       'registro_res'         => $registro_res,
                                                       'puntaje_res' => ""
                                                  );
                      }
                 }
                 return $resultado;
    }
      public function puntaje_jugadorNivel3($jugador,$partido){
               $oPuntaje            = new Default_Model_Puntaje();
               if(count($oPuntaje->getPuntajeJugadorTipo($jugador,$partido,3))>0){
                 $puntajejuego = $oPuntaje->getPuntajeJugadorTipo($jugador,$partido,3);
               }
               else{
                 $puntajejuego = $oPuntaje->getPuntajeJugadorTipo($jugador,$partido,4);
               }
               $resultado = array();
               if(count($puntajejuego)>0){
                 $k=0;
                 foreach($puntajejuego as $i=>$valor){
                      $variable_res   = $valor['variable_id'];
                      $intervalo_res[$k] = $valor['intervalo_id'];
                      $variable_res2[$k] = $valor['variable_id'];
                      $puntaje_res   =  $valor['puntaje'];
                      $puntajejuego_id = $valor['puntajejuego_id'];
                      $registro_res   =  $valor['registro'];
                      $resultado[$partido] =  array('puntajejuego_id' => $puntajejuego_id,'partido_res' => $partido,'intervalo_res' => $intervalo_res,'variable_res' => $variable_res2,'registro_res' => $registro_res,'puntaje_res' => "");
                      $k++;
                  }
               }
               return $resultado;
    }
   public function getPartidosFechaFase($fecha,$fase){
        switch ($fase){
             case 1: $nombre_fase='fase1';break;
             case 2: $nombre_fase='fase2';break;
             case 3: $nombre_fase='fase3';break;
        }
        $row     = $this->fetchAll(
                           $this->select()
                                     ->where('fecha_id='.$fecha)
                                     ->where($nombre_fase.'=1')
                                     ->where('estado=1')
                           );
         $resultado = array();
         if($row){    $resultado = $row->toArray();}
         return $resultado;
   }
}