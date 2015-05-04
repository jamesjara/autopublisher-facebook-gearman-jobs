<?php
function fbPost($jobinfo){
		//	D:\windows\xampp\php/php.exe cb334.php 1494097094149931 2464380f6730323245c94f02aa545152 387953724607663 asd
		//	cb334.php  1<app> 2<secret> 3<ID> 4<message> 5<picture> 6<link> 7<name> 8<description> 9<sessionid> 10<action_link> 11<action_name>
		$php 	= "D:\windows/xampp\php/php.exe "; //CONST
		$shell 	= " ../war/face/cb334.php "; //CONST
	 
		//get row status
		$row  =	R::getRow( 'SELECT * FROM getFbJobsInfo where idfb_posts = ? limit 1' ,  [ $jobinfo['dataId']]);	
 
		//for each EXEC   
		$cmd  = ' "'.$row['app'].'"';
		$cmd .= ' "'.$row['secret'].'"';
		$cmd .= ' "'.$row['target'].'"';
		$cmd .= ' "'.$row['message'].'"';
		$cmd .= ' "'.$row['picture'].'"';
		$cmd .= ' "'.$row['link'].'"';
		$cmd .= ' "'.$row['name'].'"';
		$cmd .= ' "'.$row['description'].'"';
		$cmd .= ' "'.$row['sessionid'].'"';
		$cmd .= ' "'.$row['action_link'].'"';
		$cmd .= ' "'.$row['action_name'].'"';
		
		$execResult = 'X';
		$line = $php.$shell.$cmd ; //" >> log_file.log 2>&1 &";
		exec( $line , $execResult );   
		
		//insert into historical
		$jobs_historical = R::dispense( 'jobshistorical' ); 
		$jobs_historical->idjobs 	=	$jobinfo['idjobs'];
		$jobs_historical->status 	= 	$jobinfo['status'] ;
		$jobs_historical->type 		= 	$jobinfo['type'] ;
		$jobs_historical->dataId 	= 	$jobinfo['dataId'] ;
		$jobs_historical->datetime 	= 	$jobinfo['datetime'] ;
		$jobs_historical->resultado	= 	implode('|',$execResult) ;   
		$jobs_historical->exec 		= 	$line ;   
		$id = R::store( $jobs_historical );
		
		//remove job
		//R::exec( 'delete from jobs WHERE idjobs = ? '  ,  [ $jobinfo['idjobs'] ]);	 
		 
		
}

 