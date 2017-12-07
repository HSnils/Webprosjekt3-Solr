<?php
	require_once 'connect.php';


	if($user->is_loggedin()){
		//gets username from the session
		$userID = $_SESSION['username'];

		//changes the username to allways appear in uppercase when printed (and using th e variable)
		$printableUsername = strtoupper($userID);
	}

	$curl = curl_init("http://localhost:8983/solr/safety/admin/ping?wt=json");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($curl);
	$data = json_decode($output, true);
	/*echo "Ping Status: ";
	if (empty($data['status'])) {
		echo "No response";
	} else {
		print_r($data['status'].PHP_EOL);
	}*/
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

<div id="search_container">
	<form id="searchfieldform" method="GET">
		<input type="text" name="search" id="searchfield">
		<input type="submit" value="Søk" id="searchSubmit">
	</form>

</div>

<br>

<div class="contentbox">

 	<?php require('partials/search_query.php'); ?>


</div>
<?php //echo 'Antall treff: '.$antallTreff ?>
</body>
</html>