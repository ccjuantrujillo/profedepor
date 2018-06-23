<?php
class Default_Model_Configuracion extends Zend_Db_Table
{
     protected $_name = "depor_configuracion";
     public function getConfiguracion(){
          return $this->select()
            ->from('depor_configuracion')
            ->limit(1)
            ->query()
            ->fetch();
     }
}
?>
