<?php
class Panel_Model_FechaMapper
{
	protected $_dbTable;
	public function setDbTable($dbTable)
	{
		if(is_string($dbTable)){
			$dbTable = new $dbTable();
		}
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
	}

	public function getDbTable()
	{
		if(null === $this->_dbTable){
			$this->setDbTable('Application_Model_DbTable_Fecha');
		}
		return $this->_dbTable;
	}

	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries   = array();
		foreach($resultSet as $row){
			$entry = new Panel_Model_DbTable_Fecha();
			$entry->setDescripcion($row->DESCRIPCION)
				  ->setTipo($row->TIPO_ID);
			$entries[] = $entry;
		}
		return $entries;
	}
}
