<?php
	require_once ('connect.php');

	if($user->is_loggedin()){
		//gets username from the session
		$userID = $_SESSION['username'];

		//changes the username to allways appear in uppercase when printed (and using th e variable)
		$printableUsername = strtoupper($userID);
	} else {
		header("Location: login.php");
	}

	if(isset($_POST['submit']) && $_POST['search'] != ''){

		$search = $_POST['search'];
		$url = 'search.php?search='.$search;
		header('Location: ' . $url);

	} 
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Webproject 3</title>

		<!--Jquery and UI-->
	    <script src="../jquery-ui/external/jquery/jquery.js"></script>
	    <link rel="../stylesheet" href="jquery-ui/jquery-ui.min.css">
	    <link rel="../stylesheet" href="jquery-ui/jquery-ui.structure.min.css">
	    <link rel="../stylesheet" href="jquery-ui/jquery-ui.theme.min.css">
	    <script src="../jquery-ui/jquery-ui.min.js"></script>

	    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">

	    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

		<!--Css-->
		<link rel="stylesheet" href="../css/main.css?<?php echo time(); ?>">

	</head>
	<body>

		<!--includes navigation-->
		<?php include('partials/nav.php') ?>

		<h2>Admin dashboard</h2>
		<div class="contentbox">
			<h4>Last opp en .pdf-fil </h4>
			<form id="uploadform" action="upload_handler.php" method="POST" enctype="multipart/form-data">
			    <input type="file" name="userFile" ><br>
			    <input type="submit" name="upload_btn" value="Last opp" class="inputbuttons">
			</form>
		</div>

		<div id="search_container">
			<h4>Søk gjennom rapporter</h4>
			<form id="searchfieldform" method="get">
				<input type="text" name="search" id="searchfield">
				<input type="submit" value="Søk" id="searchSubmit">
			</form>
		</div>

		<div class="contentbox">
			<?php require('partials/search_query.php'); ?>
		</div>
	</body>
</html>