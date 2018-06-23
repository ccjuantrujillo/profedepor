<?php
class ZF_Validate_HasEmail extends Zend_Validate_Abstract {
    const HAS_EMAIL = 'hasEmail';
    const HAS_ME_EMAIL = 'hasMeEmail';

    protected $_messageTemplates = array(
        self::HAS_EMAIL => "El email '%value%' ya esta registrado con otra cuenta.",
        self::HAS_ME_EMAIL => "El email '%value%' ya esta relacionado a tu cuenta."
    );

    private $_validateJugadorEmail,
            $_jugador = null;

    public function __construct($validateJugadorEmail = false){
        $this->_validateJugadorEmail = $validateJugadorEmail;
        $this->_jugador = Zend_Auth::getInstance()->getIdentity();
    }

    final private function error($row, $value){
        if($this->_validateJugadorEmail)
            if(isset($this->_jugador) && $this->_jugador->jugador_id == $row->jugador_id)
                return $this->_error(self::HAS_ME_EMAIL, $value);

        return $this->_error(self::HAS_EMAIL, $value);
    }

    public function isValid($value)
    {
        $jugador = new Default_Model_Jugador();
        if($row = $jugador->hasEmailRegistro($value)){
            $this->error($row, $value);
            return false;
        }

        if($this->_validateJugadorEmail){
            $jugadorEmail = new Default_Model_JugadorEmail();
            if($row = $jugadorEmail->hasEmailRegistro($value)){
                $this->error($row, $value);
                return false;
            }
        }

        $this->_error($value, $value);
        return true;
    }
}

