<?php
	require_once 'connect.php';
	
	//redirects user to index if allready logged in
	if($user->is_loggedin()!=""){
		$user->redirect('../index.php');
	}
	
	if(isset($_POST['submit'])){
		$username = htmlentities($_POST['username']);
		$password = htmlentities($_POST['pw']);
		
		//uses login function the the userclass
		if($user->login($username, $password)){
			$user->redirect('../index.php');
		}else{
			$error = "Wrong Login details!";
		}
	}
		
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!--Jquery and UI-->
    <script src="jquery-ui/external/jquery/jquery.js"></script>
    <link rel="stylesheet" href="jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.theme.min.css">
    <script src="jquery-ui/jquery-ui.min.js"></script>
    
    <link rel="stylesheet" href="../css/login.css?<?php echo time(); ?>">
</head>
<body>
	<div id="main">
		<div class="container">
			<h1>SIGN IN</h1>
			<form action="login.php" method="post">
				<?php
					if(isset($error)){?>
						<div>
							&nbsp; <?php echo $error; ?> !
						</div>
				<?php
					}
				?>
				<label for="username">Username</label>
				<input type="text" name="username" id="username" value="" required>

				<br>

				<label for="pass">Password</label>
				<input type="password" name="pw" id="pass" value="" required>

				<br>

				<!--Log in and register buttons -->
				<label>
					<input class="buttonclass left" type="submit" name="submit" value="SIGN IN">
				</label>
			</form>

			<div id="error"></div>
		</div>
	</div>
	
</body>
</html>