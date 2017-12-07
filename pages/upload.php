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
<nav>
	<a href="../index.php" class="navitem"><img id="logo" src="../images/logo.png"></a>
	<div id="navbuttons" class="navitem">
		<b>
			<a href="">COOKIES OG PERSONVERN</a>
			<?php 
				if($user->is_loggedin()){
					echo "<a href='logout.php?logout=true'>LOGG UT</a>";
				}else{
					echo "<a href='login.php'>LOGG INN</a>";
				}

			?>
		</b>
	</div>
</nav>

<?php 
require('../solarium/init.php');

htmlHeader();

// create a client instance
$client = new Solarium\Client($config);

// get a select query instance
$query = $client->createSelect();

// set a query (all prices starting from 12)
$query->setQuery('*:*');

// set start and rows param (comparable to SQL limit) using fluent interface
$query->setStart(0)->setRows(1);

$query->setFields(array('id'));

// sort the result
$query->addSort('timestamp', $query::SORT_ASC);

// this executes the query and returns the result
$resultset = $client->select($query);

//print_r($resultset);

foreach ($resultset AS $document) {
    $document->getFields();
}

echo $document['id'];


	    // Initiate a DisMax query (Query multiple fields)
	    $dismax = $query->getDisMax();

	    // Select the fields we wish to use the search for
	    $dismax->setQueryFields('Document Summary Year Responsible');

	    /*
	    // Example of how you can weigh each field differently.
	    $dismax->setQueryFields('title^3 cast^2 synopsis^1');
	    */

	    // this executes the query and returns the result
	    $resultset = $client->select($query);

	 	  // ----- RESULTS -----

	    $antallTreff = $resultset->getNumFound();
	    
	  
	    if($antallTreff != 0) {
	    	// display the total number of documents found by solr
	   		 echo '<div class="treffbox"> Antall treff: '.$antallTreff.'</div>';
		    // show documents using the resultset iterator
		    foreach ($resultset as $document) {

		        echo '<table>';

		        // the documents are also iterable, to get all fields
		        foreach ($document as $field => $value) {
		            // this converts multivalue fields to a comma-separated string
		            if (is_array($value)) {
		                $value = implode(', ', $value);
		            }

		            echo '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
		        }

<?php //echo 'Antall treff: '.$antallTreff ?>
</body>
</html>