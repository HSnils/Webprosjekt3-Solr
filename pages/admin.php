<?php
	
	require_once ('connect.php');


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

	if(isset($_POST['submit']) && $_POST['search'] != ''){

		$search = $_POST['search'];
		$url = 'search.php?search='.$search;
		header('Location: ' . $url);

	} /*else if(isset($_POST['submit'])){
		echo '<script type="text/javascript">alert("Fyll inn søkefeltet");</script>';
	}*/
?>

<!DOCTYPE html>
<html>
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

	<!--Css-->
	<link rel="stylesheet" href="../css/main.css?<?php echo time(); ?>">

</head>
<body>

<!--includes navigation-->
<?php include('partials/nav.php') ?>

<h2>Admin dashboard</h2>
<div class="contentbox">
	
	<h4>Last opp en .pdf-fil</h4>
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

 	<?php 

	    // ----- SEARCH EXECUTION -----

	if (!empty($_GET["search"])) {

	    require('../solarium/init.php');

	    // create a client instance
	    $client = new Solarium\Client($config);

	    // get a select query instance
	    $query = $client->createSelect();

	    // set a query (all prices starting from 12)
	    $query->setQuery($_GET["search"]);

	    // Initiate a DisMax query (Query multiple fields)
	    $dismax = $query->getDisMax();

	    // Select the fields we wish to use the search for
	    $dismax->setQueryFields('Document Summary Year Responsible id');

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
	   		 echo '<div class="treffbox"> Antall treff på <u>'.$_GET['search'].'</u>: '.$antallTreff.'</div>';
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

		        echo '</table><br><div class="inputbuttons">Edit</div><hr><br>';
		    }
	    } else{
	    	echo '<div class="treffbox">
	    		Fant ingen treff på <u>'.$_GET['search'].'</u>!
	    	</div>';
	    }
}?>


 </div>
</body>
</html>