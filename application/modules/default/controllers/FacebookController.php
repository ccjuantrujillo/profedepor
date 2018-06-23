<?php

class FacebookController extends Zend_Controller_Action
{
    protected $_session = null,
              $_connection = null;

    //redsocial_id
    protected $id = 1;

    public function init()
    {
        $this->_session = new Zend_Session_Namespace();
        $this->_helper->viewRenderer->setNoRender();
        Zend_Layout::getMvcInstance()->disableLayout();
    }

    private function getResource(){
        if(null === $this->_connection){
            $fb = Zend_Registry::get('fb');

            require_once('fb/facebook.php');

            // Create our Application instance (replace this with your appId and secret).
            $this->_connection = new Facebook(array(
                'appId'  => $fb->appId,
                'secret' => $fb->secret,
                'cookie' => true,
            ));
        }

        return $this->_connection;
    }

    public function getfriendsmodalAction()
    {
        # <a href="/facebook/redirect?type=email" onClick="$(this).openWin({ url: $(this).attr('href'), name: 'Facebook' }); return false;">Invitar A tus amigos de fb</a>
        $this->_helper->viewRenderer->setNoRender(false);
    }

    public function getfriendsAction()
    {
        if( ! isset($this->_session->my_friends))
            return false;

        $my_friends = $this->_session->my_friends['data'];

        $cantidad_friends = count($my_friends);
        $cantidad_x_pag = 12;
        $cantidad_pags = ceil($cantidad_friends / $cantidad_x_pag);
        $pag = $this->_request->getQuery('pag', 1);

        # if($pag > $cantidad_pags)
        #    $pag = 1;

        $from = $pag > 0 ? ($pag-1) * $cantidad_x_pag : 0;
        $my_friends = array_slice($my_friends, $cantidad_x_pag * $pag, $cantidad_x_pag);

        $this->view->assign(array(
            'my_friends' => $my_friends,
            'cantidad_pags' => $cantidad_pags,
            'pag' => $pag+1
        ));

        $this->_helper->viewRenderer->setNoRender(false);
        # javascript: getFbFriendsModal(); void 0
    }

    public function publishAction()
    {
        $my_friends = $this->_request->getPost('friends');
        $host = $this->_request->getHttpHost();
        $facebook = $this->getResource();

        $params = array(
            'message' => utf8_encode('¡Juega  El Profe Depor!  Diviértete jugando con los resultados del campeonato descentralizado. Piensa como técnico fecha a fecha y demuestra cuanto sabes de fútbol.'),
            'name' => utf8_encode('¡Juega  El Profe Depor!'),
            'caption' => utf8_encode('Diviértete jugando con los resultados del campeonato descentralizado. Piensa como técnico fecha a fecha y demuestra cuanto sabes de fútbol.'), # '{*actor*}' .
            'link' => 'http://' . $host . '/',
            'description' => 'bla bla bla',
            'picture' => 'http://' . $host . '/images/facebook-chico.jpg',
            'actions' => array(
                array(
                    'name' => 'Jugar El Profe Depor!',
                    'link' => 'http://' . $host . '/'
                )
            ),

            'properties' => array(
                'category' => array(
                    'text' => 'juego',
                    'href' => 'http://' . $host . '/category/humor'
                ),
                'ratings' => '5 stars'
            ),

            'latitude' => '41.4',
            'longitude' => '2.19'
        );

        # $facebook->api('/100002280709853/feed', 'post', $params);

        foreach($my_friends as $friend)
            $facebook->api("$friend/feed", 'POST', $params);
    }

    public function shareAction()
    {
        $url = 'http://www.facebook.com/sharer.php';

        $params = array(
            'u' => 'http://:host/'
        );

        $url .= $this->_helper->Spread($params);
        return $this->_redirect($url);
    }

    public function redirectAction()
    {
        $type = $this->_request->getQuery('type');
        $this->_session->red_type = $type;

        $fb = Zend_Registry::get('fb');
        $url = 'https://www.facebook.com/login.php';
        $params = array(
            'api_key' => $fb->api_key,
            'next' => "http://:host/facebook/callback/",
            'display' => 'popup',
            'fbconnect' => 1,
            'return_session' => 1,
            'session_version' => 3,
            'req_perms' => 'email,read_stream,publish_stream,offline_access,user_checkins', # ,share_item
            'v' => 1.0
        );

        $url .= $this->_helper->Spread($params);
        return $this->_redirect($url);
    }

    public function callbackAction()
    {
        if( ! isset($this->_session->red_type) || 0 >= strlen($this->_session->red_type))
            $this->_redirect('/');

        $this->_session->me = null;
        $facebook = $this->getResource();

        // We may or may not have this data based on a $_GET or $_COOKIE based session.
        //
        // If we get a session here, it means we found a correctly signed session using
        // the Application Secret only Facebook and the Application know. We dont know
        // if it is still valid until we make an API call using the session. A session
        // can become invalid if it has already expired (should not be getting the
        // session back in this case) or if the user logged out of Facebook.

        $session = $facebook->getSession();

        $me = null;
        // Session based API call.
        if($session) {
            try {
                $uid = $facebook->getUser();
                $me = $facebook->api('/me');
                # $facebook->
            } catch (FacebookApiException $e) {
                # ignore
            }
        }

        $script = '';

        if($me && $this->_session->red_type){
            $user_info = new stdClass();

            $user_info->first_name = $me['first_name'];
            $user_info->last_name = $me['last_name'];
            $user_info->email = $me['email'];
            $user_info->id = $me['id'];
            $user_info->password = $me['id'];
            $user_info->redsocial_id = $this->id;

            switch($this->_session->red_type){
                case 'email':
                    $user_info = null;
                    $_my_friends = array();
                    $my_friends = $facebook->api('/me/friends');

                    foreach($my_friends['data'] as $friends){
                        $friend = new stdClass();
                        $friend->name = $friends['name'];
                        $friend->id = $friends['id'];
                        $friend->profile_image_url = "http://graph.facebook.com/{$friend->id}/picture";

                        $_my_friends[] = $friend;
                    }

                    $this->_session->redsocial_id = $this->id;
                    $this->_session->my_friends = $_my_friends;

                    $script .= 'window.opener.getRedFriendsModal();';
                    break;
                case 'link':
                    $script .= $this->_helper->NetWorkLink($user_info);
                    break;
                case 'login':
                default:
                    $host = $this->_request->getHttpHost();
                    $url = 'http' .((!empty($_SERVER['HTTPS'])) ? 's' : '') . '://' . $host . '/';
                    // logout url will be needed depending on current user state.
                    $logoutUrl = $facebook->getLogoutUrl(array('next' => $url));
                    $user_info->logoutUrl = $logoutUrl;

                    $script .= $this->_helper->NetWorkLogin($user_info);
                    break;
            }

            $this->_session->me = $user_info;

        } else {
            // login url will be needed depending on current user state.
            # $loginUrl = $facebook->getLoginUrl();
            # $me['loginUrl'] = $loginUrl;
        }

        $script .= "self.close();";

        # dragon_geminis17@hotmail.com

        // send
        exit($this->view->headScript()->setScript($script));
    }


}

