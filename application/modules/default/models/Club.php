<?php
class Default_Model_Club extends Zend_Db_Table
{
    protected $_name = "depor_club";
	public function getClub($id)
	{
		$id = (int)$id;
		$row = $this->fetchRow('club_id='.$id);
		if(!$row)
		{
			throw new Exception("No se encuentra la fila $id");
		} 
		return $row->toArray();
	}
	public function addClub($descripcion)
	{
		$data = array('descripcion'=> $descripcion);
		$this->insert($data);
	}
	public function updateClub($id)
	{
		$data = array('descripcion'=> $descripcion);	
		$this->update($data,'club_id='.(int)$id);	
	}
	public function deleteClub($id)
	{
		$this->delete('club_id='.(int)$id);
	}
    public function listarOpciones(stdClass $filter = null, $required = true){
        $select = $this->select()
            ->from($this->_name, array('key' => 'club_id', 'value' => 'descripcion'))
            ->where('estado = ?', 1);

        $all = $select->query()->fetchAll(Zend_Db::FETCH_ASSOC);
        if($required)
            array_unshift($all, array('key' => 0, 'value' => 'SELECCIONE'));

        return $all;
    }
}