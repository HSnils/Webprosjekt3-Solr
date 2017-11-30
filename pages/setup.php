<?php
/*CREATE USER 'snils'@'localhost'
IDENTIFIED BY 'snils';
*/
require_once('dblogon.php');
//include_once("connect.php");

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

//CREATES USERS TABLE AND NEWS TABLE (using cascade FK only on update, to not delete the news of accounts that changed name)
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
//in the ratings table i would have liked to use ON DELETE SET NULL, but i can not since username is primarykey, i was considering making ratingID, but opted to not do so as it might be usefull to automatichally delete all the ratings a user has made, if for example they were rating everything 0 and thats why they got banned, could be changed though easily if needed.



//makes 2 dummy accounts with username "Henrik" with password test and "admin" with password "admin" 


$query =
	"
	INSERT INTO users (username, pw)
	VALUES ('admin', '$2y$10$.SmmhnJtIQxGRvuD59.JY.vJH2sClwNVKz3wwge2sC4DLXtdEFUoS');
	
	";
if ($db->exec($query)===false){
	die('Can not INSERT INTO tables:' . $db->errorInfo()[2]);
}


?>