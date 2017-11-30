<?php
//user class object
class user{
	private $db;
	
	//constructor to get/use database
	function __construct($db){
		$this->db = $db;
	}
	
	//loginfunction to log in users and set the session
	public function login($username,$pass){
		try{
			//prepare statement to find a user that is the same the username inputted
			$stmt = $this->db->prepare("SELECT * FROM users WHERE username=:uname");
			$stmt->execute(array(':uname'=>$username));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0){
				//checks if the password written is correct
				if(password_verify($pass, $userRow['pw'])){
					//puts users name into session
					$_SESSION['username'] = $userRow['username'];
					return true;
				}else{
					return false;
				}
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	
	//checks if user is logged in by checking if the session is set
	public function is_loggedin(){
		if(isset($_SESSION['username'])){
			return true;
		}
	}
	
	//just a function to redirect users to another url
	public function redirect($url){
		header("Location: $url");
	}
	
	//function to log out an user
	public function logout(){
		session_destroy();
		unset($_SESSION['username']);
		return true;
	}
}
?>