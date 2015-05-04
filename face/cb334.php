<?php


$app = "";
$secret = "";
$sid = ""; 
 
if(isset( $_GET['deb'])){
	 $app = "1494097094149931";
	 $secret = "d800a58b115444a9097b5cfbe323c2ab"; 
	$sid =  '387953724607663';
	$message =  'message';
	$picture =  'https://fbcdn-photos-c-a.akamaihd.net/hphotos-ak-xpa1/t39.2081-0/p128x128/851578_455087414601994_1601110696_n.png';
	$link =  'https://developers.facebook.com';
	$name =  'name';
	$description = 'description';
	$sessionid = '7777'; 
	$action_link =  'action_link';
	$action_name =  'action_name';
} else if(count($argv)!= 12){
	var_dump($argv);
	echo 'usage: 1<app> 2<secret> 3<ID> 4<message> 5<picture> 6<link> 7<name>  8<description> 9<sessionid> 10<action_link> 11<action_name>';
	exit;
} else {
	$app = $argv[1];
	$secret =  $argv[2];
	$sid =  $argv[3];
	$message =  $argv[4];
	$picture =  $argv[5];
	$link =  $argv[6];
	$name =  $argv[7];
	$description =  $argv[8];
	$sessionid =  $argv[9];
	$action_link =  $argv[10];
	$action_name =  $argv[11];
 
					 
}

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
$helper = new MyFacebookRedirectLoginHelper( "http://poster.innosystem.org/face/cb334.php?deb" );
$helper->disableSessionStatusCheck();
 
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
} else { 
	echo '<a href="' . $helper->getLoginUrl() . '">Login</a>';
	echo 'error necesita sesion';
	die();
} 
 
// see if we have a session
if ( isset( $session ) ) {
 
    		  
	 
 	  print_r( "=======================================================" );
	
	 
	  print_r( $session );
	  
	  
	$accessToken = $session->getAccessToken();
	print_r( $accessToken );
	
	if (!empty( $accessToken )) {
				 $attachment = array(
					 'access_token' => $accessToken,
					 'message' => $message,
					 'name' => $name,
					 'link' => $link,
					 'description' => $description,
					 'picture'=>$picture
					 //,'actions' => json_encode(array('name' => $action_name,'link' => $action_link))
			 	  );
				   
					 
	  print_r( "-----------------------------------------------------------" );
    print_r( $attachment );
	
				$status = (new FacebookRequest( $session, 'POST', '/'.$sid.'/feed', $attachment  ))->execute()->getGraphObject()->asArray();
	 } else {
	 			$status = 'No access token recieved';
	}  
	  print_r( "-----------------------------------------------------------" );
	  print_r( $status );
	  print_r( "=======================================================" );
			 
		}   
 
  die();