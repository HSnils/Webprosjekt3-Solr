<?php
	//starts session
	session_start();
	
	//takes the login details
	require_once('dblogon.php');
	//connects to database with the variables defined in "dblogon.php"
	try{
		$db = new PDO ("mysql: host=$db_host;dbname=$db_database;charset=utf8",$db_user, $db_pass);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	}catch(PDOException $e){
		die ("Error(Could not connect): ".$e->getMessage());
	};

	//includes the classes.php file
	include_once ('classes.php');

	//creates a new user to be used (not register)
	$user = new user($db);
?>