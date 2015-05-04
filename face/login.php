<?php


 $app = "299398536906086";
 $secret = "21c1ef77f030a7b1374de769488d4db7"; 
 $sessionid = '7778'; 
//todo set setion PER USER ID
session_id ($sessionid);

session_start();
 
// Facebook PHP SDK v4.0.8
 
// path of these files have changes
require_once( 'Facebook/HttpClients/FacebookHttpable.php' );
require_once( 'Facebook/HttpClients/FacebookCurl.php' );
require_once( 'Facebook/HttpClients/FacebookCurlHttpClient.php' );
 
require_once( 'Facebook/Entities/AccessToken.php' );
require_once( 'Facebook/Entities/SignedRequest.php' );
 
// other files remain the same
require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookOtherException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once( 'Facebook/GraphSessionInfo.php' );

require_once( 'Facebook/FacebookServerException.php' );
require_once( 'Facebook/FacebookPermissionException.php' );
require_once( 'Facebook/FacebookClientException.php' );
require_once( 'Facebook/FacebookThrottleException.php' );
  

 
// path of these files have changes
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;
 
use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest;
 
// other files remain the same
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;
   
use Facebook\FacebookServerException;
use Facebook\FacebookPermissionException;
use Facebook\FacebookClientException;
use Facebook\FacebookThrottleException; 

 
/*
 array(
      "message" => "Hi there",
      "picture" => "http://www.mywebsite.com/path/to/an/image.jpg",
      "link"    => "http://www.mywebsite.com/path/to/a/page/",
      "name"    => "My page name",
      "caption" => "And caption"
   )
*/  
FacebookSession::setDefaultApplication( $app, $secret );
 
 include "comun.php";

// login helper with redirect_uri
$helper = new   MyFacebookRedirectLoginHelper( "http://autofacebook.ticoganga.com/face/login.php", $app, $secret );
 
 
 // see if a existing session exists
if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
    // create new session from saved access_token
    $session = new FacebookSession($_SESSION['fb_token']);
    // validate the access_token to make sure it's still valid
    try {
        if (!$session->validate()) {
            $session = null;
        }
    } catch (Exception $e) {
        // catch any exceptions
        $session = null;
    }
} else {
    // no session exists
    try {
        $session = $helper->getSessionFromRedirect();
    } catch (FacebookRequestException $ex) {
        // When Facebook returns an error
    } catch (Exception $ex) {
        // When validation fails or other local issues
        echo $ex->message;
    }
}

// see if we have a session
if (isset($session)) {
    // save the session
    $_SESSION['fb_token'] = $session->getToken();
    // create a session using saved token or the new one we generated at login
    $session = new FacebookSession($session->getToken());
    // graph api request for user data
    $request = new FacebookRequest($session, 'GET', '/me');
    $response = $request->execute();
    $graphObject = $response->getGraphObject()->asArray();
    $_SESSION['valid'] = true;
    $_SESSION['timeout'] = time();
    $_SESSION['FB'] = true;
    $_SESSION['usernameFB'] = $graphObject['name'];
    $_SESSION['idFB'] = $graphObject['id'];
    $_SESSION['first_nameFB'] = $graphObject['first_name'];
    $_SESSION['last_nameFB'] = $graphObject['last_name'];
    $_SESSION['genderFB'] = $graphObject['gender'];
	echo 'logeado'; 
} else { 
	echo '<a href="' . $helper->getLoginUrl(array(  "publish_stream","status_update","manage_pages","publish_actions","read_stream"  )) . '">Login</a>';
	echo 'error necesita sesion';
	die();
}
  
 
  die();