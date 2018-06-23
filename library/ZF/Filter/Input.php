<?php
class ZF_Filter_Input extends Zend_Filter_Input {

    protected $_defaultFilterRules = array(
        '*' => array('StringTrim', 'StripTags')
    );

    public function __construct($validators, $data, $filters = null){
        if($filters !== null)
            $this->_defaultFilterRules = array_merge($this->_defaultFilterRules, $filters);

        parent::__construct($this->_defaultFilterRules, $validators, $data);
    }

    public function valid(&$response = null){
        $response = array('message' => 'ok', 'elem' => '');
        $rt = true;

        foreach($this->_validatorRules as $key => $val){
            if( ! $this->isValid($key)){
                $rt = false;
                $message = $this->getMessages();
                if(is_array($message))
                    $message = array_shift($message);

                if(is_array($message))
                    $message = array_shift($message);

                $response['message'] = $message;
                $response['elem'] = $key;
                break;
            }
        }

        return $rt;
    }
}

