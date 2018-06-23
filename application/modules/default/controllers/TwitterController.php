<?php

require 'twitteroauth/twitteroauth.php';

class TwitterController extends Zend_Controller_Action
{

    protected $_session = null,
              $_connection = null;

    // redsocial_id
    protected $id = 2;

    public function init()
    {
        # echo $this->_request->getHttpHost();
        /* Start session and load lib */
        @session_start();

        $this->_session = new Zend_Session_Namespace();
        # $this->_helper->viewRenderer->setNoRender();
    }

    private function getResource(){
        if(null === $this->_connection){
            $twitter = Zend_Registry::get('twitter');
            /* Build TwitterOAuth object with client credentials. */
            $this->_connection = new TwitterOAuth($twitter->key, $twitter->secret);
        }

        return $this->_connection;
    }

    public function publishAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $my_friends = $this->_request->getPost('friends');
        $host = $this->_request->getHttpHost();
        //$connection = $this->getResource();
        foreach($my_friends as $friend_id){
            $numero = rand(0,100);
            $friend = $this->_session->my_friends[$friend_id];
            $connection = unserialize($_SESSION['connection']);
            $connection->post("direct_messages/new", array(
                'user_id' => $friend->id,
                'screen_name' => $friend->name,
                'text' => 'Te invito a jugar el profe depor una vez mas.'.$numero
            ));
            unset($connection);
        }
    }

    public function shareAction()
    {
        $url = 'http://twitter.com/intent/tweet';

        $params = array(
            'url' => 'http://:host/',
            'lang' => 'es',
            'text' => 'Juega El Profe Depor y divi�rtete con los resultados del campeonato descentralizado. �Demuestra cuanto sabes de f�tbol!'
        );

        $url .= $this->_helper->Spread($params);
        return $this->_redirect($url);
    }

    public function registroAction()
    {
        if( ! isset($this->_session->me))
            # $this->_redirect('/jugador/login/');
            exit('no existe session red - registro');

        $me = $this->_session->me;
        $request = $this->getRequest();
        if($request->isPost()){
            $formValues = $request->getPost();

            $validators = array(
                'email' => array('EmailAddress', new ZF_Validate_HasEmail(true))
            );

            $input = new ZF_Filter_Input($validators, $formValues);

            if($input->valid($response)){
                $me->email = $request->getPost('email');
            }

            $this->_helper->json($response);
        }

        $this->view->assign(array(
            # 'form' => $form,
            'profile_image_url' => $me->profile_image_url,
            'name' => $me->first_name
        ));
    }

    public function redirectAction()
    {
        $type = $this->_request->getQuery('type');
        $this->_session->red_type = $type;

        $this->_session->me = null;
        $connection = $this->getResource();

        /* Get temporary credentials. */
        $request_token = $connection->getRequestToken();

        # print_r($connection);
        # exit;

        /* Save temporary credentials to session. */
        $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        /* If last connection failed don't display authorization link. */
        switch ($connection->http_code) {
          case 200:
            /* Build authorize URL and redirect user to Twitter. */
            $url = $connection->getAuthorizeURL($token);
            $this->_redirect($url);
            break;
          default:
            /* Show notification if something went wrong. */
            echo 'Could not connect to Twitter. Refresh the page or try again later.';
            exit;
        }
    }

    /**
     * @file
     * Take the user when they return from Twitter. Get access tokens.
     * Verify credentials and redirect to based on response from Twitter.
     */
    public function callbackAction()
    {
        # print_r($this->_request->getParams());
        # exit;

        $this->_helper->viewRenderer->setNoRender();
        Zend_Layout::getMvcInstance()->disableLayout();

        /* If the oauth_token is old redirect to the connect page. */
//        if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
//          $_SESSION['oauth_status'] = 'oldtoken';
//          $this->_redirect('/twitter/clearsessions/');
//          //exit('clear session 1');
//        }

        $twitter = Zend_Registry::get('twitter');

        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
        $connection = new TwitterOAuth($twitter->key, $twitter->secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        $oauth_verifier = $this->_request->getQuery('oauth_verifier');
	$_SESSION['oauth_verifier'] = $oauth_verifier;
        /* Request access tokens from twitter */
        $access_token = $connection->getAccessToken($oauth_verifier);
	/* Save the access tokens. Normally these would be saved in a database for future use. */
        $_SESSION['access_token'] = $access_token;
        /* Remove no longer needed request tokens */
        //unset($_SESSION['oauth_token']);
        //unset($_SESSION['oauth_token_secret']);

        /* If HTTP response is 200 continue otherwise send to connect page to retry */
        if (200 == $connection->http_code) {
               /* The user has been verified and the access tokens can be saved for future use */
               $_SESSION['status'] = 'verified';
               $twitter = $connection->get('account/verify_credentials');
               $_SESSION['connection'] = serialize($connection);
               $me = new stdClass();
               $me->first_name = $twitter->name;
               $me->last_name = '';
               $me->email = '';
               $me->id = $twitter->id;
               $me->password = $twitter->id;
               $me->redsocial_id = $this->id;
               $me->profile_image_url = $twitter->profile_image_url;
               $script = "";
               switch($this->_session->red_type){
                 case 'email':
                     //$me = null;
                     $_my_friends = array();
                     $my_friends = $connection->get('statuses/followers');
                     foreach($my_friends as $friends){
                         $friend = new stdClass();
                         $friend->name = $friends->screen_name;
                         $friend->id = $friends->id; 
                         $friend->profile_image_url = $friends->profile_image_url;
                         $_my_friends[$friends->id] = $friend;
                     }
                     $this->_session->redsocial_id = $this->id;
                     $this->_session->my_friends = $_my_friends;
                     $script.= 'window.opener.getRedFriendsModal();';
                     break;
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
	else {
          /* Save HTTP status for error dialog on connnect page.*/
          $this->_redirect('/twitter/clearsessions');
          exit('clear session 2');
        }

        # dragon_geminis17@hotmail.com
    }

    public function clearsessionsAction()
    {
        @session_destroy();

        /* Redirect to page with the connect to Twitter option. */
        //$this->_redirect('/');
    }


}