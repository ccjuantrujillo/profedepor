<?php
require 'twitteroauth/googleoauth.php';
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

    private function getResource(){
        if(null === $this->_connection){
            $google = Zend_Registry::get('google');
            /* Build TwitterOAuth object with client credentials. */
            $this->_connection = new GoogleOAuth($google->consumerKey, $google->consumerSecret);
        }
        return $this->_connection;
    }    
    
    public function callbackAction()
    {
        # print_r($this->_request->getParams());
        # exit;

        $this->_helper->viewRenderer->setNoRender();
        Zend_Layout::getMvcInstance()->disableLayout();

//        /* If the oauth_token is old redirect to the connect page. */
//        echo $_SESSION['oauth_token']."<br>";
//        if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
//          $_SESSION['oauth_status'] = 'oldtoken';
//          //$this->_redirect('/twitter/clearsessions/');
//          //exit('clear session 1');
//        }
        
        $google = Zend_Registry::get('google');
        
        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
        $connection = new GoogleOAuth($twitter->key, $twitter->secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        $oauth_verifier = $this->_request->getQuery('oauth_verifier');
        
        /* Request access tokens from google */
        $access_token = $connection->getAccessToken($oauth_verifier);
        print_r($access_token);
        die();
        
        $me = new stdClass();
        $request = $this->getRequest();

        print_r($request);die();
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
        $connection = $this->getResource();
        
        /* Get temporary credentials. */
        $request_token = $connection->getRequestToken("http://www.liderbooks.com/google/callback","https://www.google.com/m8/feeds/");
        
        /* Save temporary credentials to session. */
        $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        
        /* If last connection failed don't display authorization link. */
        switch ($connection->http_code) {
          case 200:
            /* Build authorize URL and redirect user to Google. */
            $url = $connection->getAuthorizeURL($token);
            $this->_redirect($url);
            break;
          default:
            /* Show notification if something went wrong. */
            echo 'Could not connect to Google. Refresh the page or try again later.';
            exit;
        }
        
//        print_r($request_token);
//        die();
//        # $url = "https://www.google.com/accounts/o8/id";
//        $url = "https://www.google.com/accounts/o8/ud";
//        $url = "https://www.google.com/accounts/AuthSubRequest";
//        $params = array(
//            'openid.ns' => 'http://specs.openid.net/auth/2.0',
//            'openid.mode' => 'checkid_setup',
//            'openid.assoc_handle' => 'AOQobUckYcgLv1Tyx390m-sMvDIpva4dkbroqkuhT31fRQ-zTNyTB3o1m21L8eBeZ6KFcnLU',
//            'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
//            'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
//            # 'openid.return_to' => "http://$host/google/callback?discovery_token={$token->getToken()}",
//            'openid.return_to' => "http://:host/google/callback/",
//            'openid.realm' => "http://*.:host",
//            'openid.ns.ax' => 'http://openid.net/srv/ax/1.0',
//            'openid.ax.mode' => 'fetch_request',
//            'openid.ax.required' => 'attr,attr1,attr2,attr3,attr4,attr5',
//            'openid.ax.type.attr' => 'http://axschema.org/contact/country/home',
//            'openid.ax.type.attr1' => 'http://axschema.org/contact/email',
//            'openid.ax.type.attr2' => 'http://axschema.org/namePerson/first',
//            'openid.ax.type.attr3' => 'http://axschema.org/namePerson/last',
//            'openid.ax.type.attr4' => 'http://axschema.org/pref/language',
//            'openid.ax.type.attr5' => 'http://schemas.openid.net/ax/api/user_id',
//            'openid.ns.sreg' => 'http://openid.net/extensions/sreg/1.1',
//            'openid.sreg.policy_url' => "http://:host/politica/red/",
//            'openid.sreg.optional' => 'nickname,email,fullname,dob,gender,postcode,country,language,timezone',
//            'openid.ns.ui' => 'http://specs.openid.net/extensions/ui/1.0',
//            'openid.ui.icon' => 'vnd.microsoft.icon_16x16',
//            'openid.ns.ext2' => 'http://specs.openid.net/extensions/oauth/1.0',
//            'openid.ext2.consumer' => 'http://*.:host',
//            'openid.ext2.scope' => 'https://www.google.com/m8/feeds'
//        );     
        
//        $params = array(
//            'openid.ns' => 'http://specs.openid.net/auth/2.0',
//            'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
//            'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
//            'openid.return_to' => 'http://:host/authsub/',
//            'openid.realm' => 'http://*.:host',
//            'openid.assoc_handle' => 'AOQobUckYcgLv1Tyx390m-sMvDIpva4dkbroqkuhT31fRQ-zTNyTB3o1m21L8eBeZ6KFcnLU',
//            'openid.mode' => 'checkid_setup',
//            'openid.ns.oauth'=>'http://specs.openid.net/extensions/oauth/1.0',
//            'openid.oauth.consumer'=>'http://*.:host',
//            'openid.oauth.scope'=>'https://www.google.com/m8/feeds'     
//        ); 
//        $params = array(
//            'scope' => 'https://www.google.com/m8/feeds',
//            'session' => '1',
//            'secure' => '1',
//            'next' => 'http://www.liderbooks.com/authsub',
//            'hd' => 'default'
//        );
//        $url .= $this->_helper->Spread($params);
        //return $this->_redirect($url);
    }


}

