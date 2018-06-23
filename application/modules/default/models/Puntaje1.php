<?php  
class Application_Model_Puntaje1 extends Zend_Db_Table
{
	protected $_name = "depor_puntajejuego";
	public function deletePuntaje1($id)
	{
		$this->delete('intervalo_id='.(int)$id);
	}
}