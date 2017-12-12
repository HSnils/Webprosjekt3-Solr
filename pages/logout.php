<?php
	require_once 'connect.php';
	
	$user->logout();

	if(!$user->is_loggedin()){
		$user->redirect('../index.php');
	}
?>