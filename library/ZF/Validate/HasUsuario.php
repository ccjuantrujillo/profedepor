<?php
class ZF_Validate_HasUsuario extends ZF_Validate_Abstract {
    const HAS_USUARIO = 'hasUsuario';

    protected $_messageTemplates = array(
        self::HAS_USUARIO => "El usuario '%value%' no esta disponible."
    );

    public function isValid($value)
    {
        if($this->_model->hasNick($value)){
            $this->_error(self::HAS_USUARIO, $value);
            return false;
        }

        return true;
    }
}

