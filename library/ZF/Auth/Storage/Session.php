<?php
class ZF_Auth_Storage_Session extends Zend_Auth_Storage_Session {

    public function __construct($expirationSeconds, $namespace = self::NAMESPACE_DEFAULT){
        parent::__construct($namespace);
        $this->_session->setExpirationSeconds($expirationSeconds);
        # $this->_session->lock();
    }
}

