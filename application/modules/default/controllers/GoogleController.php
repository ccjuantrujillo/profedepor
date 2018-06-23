<?php

class GoogleController extends Zend_Controller_Action
{

    protected $_session = null;

    // redsocial_id
    protected $id = 3;

    public function init()
    {
        $this->_session = new Zend_Session_Namespace();
        $this->_helper->viewRenderer->setNoRender();
        Zend_Layout::getMvcInstance()->disableLayout();
    }

    public function callbackAction()
    {
        # openid_ext1_value_attr5 -> id
        # openid_ext1_value_attr1 -> email
        # openid_ext1_value_attr3 -> apellidos

        $me = new stdClass();
        $request = $this->getRequest();

        $email = urldecode($request->getQuery('openid_ext1_value_attr1'));
        $id = $request->getQuery('openid_ext1_value_attr5');

        $me->first_name = substr($email, 0, strpos($email, '@'));
        $me->last_name = $request->getQuery('openid_ext1_value_attr3');
        $me->email = $email;
        $me->id = $id;
        $me->password = $id;
        $me->redsocial_id = $this->id;

        $script = '';

        switch($this->_session->red_type){
            case 'link':
                $script .= $this->_helper->NetWorkLink($me);
                break;
            case 'login':
            default:
                $script .= $this->_helper->NetWorkLogin($me);
                break;
        }

        $this->_session->me = $me;
        $script .= "self.close();";
        exit($this->view->headScript()->setScript($script));
    }

    public function redirectAction()
    {
        $type = $this->_request->getQuery('type');
        $this->_session->red_type = $type;

        $this->_session->me = null;

        # $url = "https://www.google.com/accounts/o8/id";
        $url = "https://www.google.com/accounts/o8/ud";

        $params = array(
            'openid.ns' => 'http://specs.openid.net/auth/2.0',
            'openid.mode' => 'checkid_setup',
            'openid.assoc_handle' => 'AOQobUckYcgLv1Tyx390m-sMvDIpva4dkbroqkuhT31fRQ-zTNyTB3o1m21L8eBeZ6KFcnLU',
            'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
            'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
            # 'openid.return_to' => "http://$host/google/callback?discovery_token={$token->getToken()}",
            'openid.return_to' => "http://:host/google/callback/",
            'openid.realm' => "http://*.:host",
            'openid.ns.ax' => 'http://openid.net/srv/ax/1.0',
            'openid.ax.mode' => 'fetch_request',
            'openid.ax.required' => 'attr,attr1,attr2,attr3,attr4,attr5',
            'openid.ax.type.attr' => 'http://axschema.org/contact/country/home',
            'openid.ax.type.attr1' => 'http://axschema.org/contact/email',
            'openid.ax.type.attr2' => 'http://axschema.org/namePerson/first',
            'openid.ax.type.attr3' => 'http://axschema.org/namePerson/last',
            'openid.ax.type.attr4' => 'http://axschema.org/pref/language',
            'openid.ax.type.attr5' => 'http://schemas.openid.net/ax/api/user_id',
            'openid.ns.sreg' => 'http://openid.net/extensions/sreg/1.1',
            'openid.sreg.policy_url' => "http://:host/politica/red/",
            'openid.sreg.optional' => 'nickname,email,fullname,dob,gender,postcode,country,language,timezone',
            'openid.ns.ui' => 'http://specs.openid.net/extensions/ui/1.0',
            'openid.ui.icon' => 'vnd.microsoft.icon_16x16',
        );

        $url .= $this->_helper->Spread($params);
        return $this->_redirect($url);
    }


}

