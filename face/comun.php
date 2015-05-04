<?php

class MyFacebookRedirectLoginHelper extends \Facebook\FacebookRedirectLoginHelper{
  protected function storeState($state)  {
	global  $sessionid;
		$_SESSION['FBRLH_state'] = $state; 
		$PATH1 	= "../sessiones/fb/"; //CONST
		$sessionfile = fopen($PATH1.$sessionid."_sessionfile.txt", "w");
		fputs($sessionfile, session_encode( ) );
		fclose($sessionfile);
  } 
  protected function loadState()  {
  global  $sessionid;
	$PATH1 	= "../sessiones/fb/"; //CONST
  	$sessionfile = fopen($PATH1.$sessionid."_sessionfile.txt", "r");
	session_decode(fputs($sessionfile,  4096) );
	fclose($sessionfile);
	$this->state = $_SESSION['FBRLH_state'] ;
    return $this->state;
  }
}