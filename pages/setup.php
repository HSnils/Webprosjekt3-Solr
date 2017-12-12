<?php
require_once('dblogon.php');

//using only localhost here, no username, pw or database as it just wants to connect to localhost, not any database at this point
try{
	$db = new PDO ("mysql: host='$db_host';charset=utf8",$db_user, $db_pass);
} catch(PDOException $e){
	die ("Error(Could not connect): ".$e->getMessage());
};

//uses $db_database variable from dblogon
//checks if a database with name $db_database exsists, if it does then deletes it
$query = 'DROP DATABASE IF EXISTS '.$db_database.'';
if ($db->exec($query)===false){
	die('Query failed(1):' . $db->errorInfo()[2]);
};

//checks if a database with name $db_database exsists, if it does then creates it
$query = 'CREATE DATABASE IF NOT EXISTS '.$db_database.' DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci';
if ($db->exec($query)===false){
	die('Query failed(1):' . $db->errorInfo()[2]);
};

//Select the database
$query = 'USE '.$db_database.'';
if ($db->exec($query)===false){
	die('Can not select database:' . $db->errorInfo()[2]);
}

//CREATES USERS TABLE
$query = 
	"CREATE TABLE IF NOT EXISTS users(
		username varchar(30) PRIMARY KEY,
		pw varchar(120)
	);
"
;
if ($db->exec($query)===false){
	die('Can not create tables:' . $db->errorInfo()[2]);
}

//Creates the admin account
$query =
	"
	INSERT INTO users (username, pw)
	VALUES ('admin', '$2y$10$.SmmhnJtIQxGRvuD59.JY.vJH2sClwNVKz3wwge2sC4DLXtdEFUoS');
	
	";
if ($db->exec($query)===false){
	die('Can not INSERT INTO tables:' . $db->errorInfo()[2]);
}
?>