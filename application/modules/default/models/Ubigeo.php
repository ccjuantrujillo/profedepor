<?php

class Default_Model_Ubigeo extends Zend_Db_Table_Abstract {

    protected $_name = 'depor_ubigeo';

    public function init(){
        $this->getAdapter()->setFetchMode(Zend_Db::FETCH_ASSOC);
    }

    public function getDistritos($departamento, $provincia, $distrito = '00'){
        return $this->select()
            ->setIntegrityCheck(false)
            ->from($this->_name, array('key' => 'distrito', 'value' => 'descripcion'))
            ->where('departamento = ?', $departamento)
            ->where("provincia = ?", $provincia)
            ->where("distrito != ?", $distrito)
            ->query();
    }

    public function getProvincias($departamento, $provincia = '00', $distrito = '00'){
        return $this->select()
            ->setIntegrityCheck(false)
            ->from($this->_name, array('key' => 'provincia', 'value' => 'descripcion'))
            ->where('departamento = ?', $departamento)
            ->where("provincia != ?", $provincia)
            ->where("distrito = ?", $distrito)
            ->query();
    }

    public function getDepartamentos(){
        return $this->select()
            ->setIntegrityCheck(false)
            ->from($this->_name, array('key' => 'departamento', 'value' => 'descripcion'))
            ->where('provincia = ?', '00')
            ->where('distrito = ?', '00')
            ->query();
    }

}

