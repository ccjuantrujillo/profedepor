<?php
class Application_Model_Fecha
{
	protected $_descripcion;
	protected $_tipo;
	public function __construct(array $options = null)
	{
		if(is_array($options)){
			$this->setOptions($options);
		}
	}
	
	public function __set($name,$value)
	{
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid guestbook property');
        }
        $this->$method($value);
	}
	
	public function __get($name)
	{
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid guestbook property');
        }
        return $this->$method();
	}
	
	public function setOptions(array $options)
	{
		$methods = get_class_methods($this);
		foreach($options as $key => $value){
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
		}
		return $this;
	}
	
	public function setDescripcion($descripcion)
	{
		$this->_descripcion = (string) $descripcion;
		return $this;
	}
	
	public function getDescripcion()
	{
		return $this->_descripcion;
	}
	
	public function setTipo($tipo)
	{
		$this->_tipo = (string) $tipo;
		return $this;
	}
	
	public function getTipo()
	{
		return $this->_tipo;
	}	
}
