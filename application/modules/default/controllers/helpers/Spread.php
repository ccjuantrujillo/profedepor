<?php

class Zend_Controller_Action_Helper_Spread extends Zend_Controller_Action_Helper_Abstract {
    public function direct($params){
        $host = $this->getRequest()->getHttpHost();

        $url_params = array();
        foreach($params as $key => $value)
            $url_params[] = $key . '=' . urlencode(utf8_encode(preg_replace('/:host/', $host, $value)));

        # http_build_query($params, null, '&');
        return count($url_params) > 0 ? ('?' . implode('&', $url_params)) : '';
    }
}

