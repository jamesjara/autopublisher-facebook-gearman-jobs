<?php
	include "J_fb_post.php";

	require 'rb.php';
	R::setup('mysql:host=localhost;dbname=socialcms','root','root'); //for both mysql or mariaDB
	
	$q = " SELECT * FROM jobs where  datetime < now() " ; //echo $q;
	$rows =	R::getAll( $q );	
	//var_dump($rows);die();
	
	//for each EXEC 
	foreach($rows as $row ){
		
		if ($row['type']=='1')
			fbPost($row);
 
	}
	 
	 