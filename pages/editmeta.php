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
	echo "Ping Status: ";
	if (empty($data['status'])) {
		echo "No response";
	} else {
		print_r($data['status'].PHP_EOL);
	}

	if (!empty($_POST['upload'])) {
		echo "yeeeshellow";
		/*$ch = curl_init('http://localhost:8010/solr/update/extract?literal.id=1&literal.name=Name&commit=true');
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, array('myfile'=>'@'.$post->filepath));
		$result= curl_exec ($ch);*/

	} else {
		echo "lolno";
		var_dump($_FILES);
		unset($_FILES['userFile']);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<title>Webproject 3</title>

	<!--Jquery and UI-->
    <script src="../jquery-ui/external/jquery/jquery.js"></script>
    <link rel="../stylesheet" href="jquery-ui/jquery-ui.min.css">
    <link rel="../stylesheet" href="jquery-ui/jquery-ui.structure.min.css">
    <link rel="../stylesheet" href="jquery-ui/jquery-ui.theme.min.css">
    <script src="../jquery-ui/jquery-ui.min.js"></script>

    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">

	<!--Css-->
	<link rel="stylesheet" href="../css/main.css?<?php echo time(); ?>">

</head>
<body>

<!--includes navigation-->
<?php include('partials/nav.php') ?>

<?php if(isset($_GET['id'])) : ?>

<?php 
require('../solarium/init.php');

htmlHeader();

// create a client instance
$client = new Solarium\Client($config);

// get a select query instance
$query = $client->createSelect();

// set a query (all prices starting from 12)
$query->setQuery('id:' . $_GET['id']);

// set start and rows param (comparable to SQL limit) using fluent interface
$query->setStart(0)->setRows(1);

// this executes the query and returns the result
$resultset = $client->select($query);

//print_r($resultset);

foreach ($resultset AS $document) {
    $document->getFields();
}

 ?>

<div class="contentbox">
	
	<h4>Edit pdf metadata</h4>
	<form id="editform" action="edit_handler.php" method="POST" enctype="multipart/form-data">
		<div>
			<label for="title">ID:</label>
		    <input type="text" name="ID" required <?php if(isset($document['id'])) : ?> readonly="readonly" value="<?php echo $document['id']; endif; ?>">

			<label for="title">Title:</label>
		    <input type="text" name="title" required>

		    <label for="title">Author:</label>
		    <input type="text" name="author" required>
		    
		    <label for="title">Operator:</label>
		    <input type="text" name="operator" required>

		    <label for="title">Responsible:</label>
		    <input type="text" name="responsible" required>
	    </div>

	    <input type="submit" name="edit_btn" value="Edit Metadata" class="inputbuttons">
	</form>

	
</div>

<?php endif; ?>

<?php //echo 'Antall treff: '.$antallTreff ?>
</body>
</html>