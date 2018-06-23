<?php
class ZF_Validate_HasUsuarioEmail extends ZF_Validate_Abstract {
    const HAS_EMAIL = 'hasEmail';

    protected $_messageTemplates = array(
        self::HAS_EMAIL => "El email '%value%' ya esta registrado con otra cuenta."
    );

    public function isValid($value)
    {
        if($this->_model->hasEmail($value)){
            $this->_error(self::HAS_EMAIL, $value);
            return false;
        }

        return true;
    }
}

