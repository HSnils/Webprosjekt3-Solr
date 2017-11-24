<?php
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
			<a href="">LOGG INN</a>
		</b>
	</div>
</nav>

<div id="search_container">
	<form id="searchfieldform" method="get">
		<input type="search" name="search" id="searchfield">
		<input type="submit" value="Search" id="searchSubmit">
	</form>

</div>


<br>



 <div id="searchresultsbox">

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

		        echo '</table><br><hr><br>';
		    }
	    } else{
	    	echo '<div class="treffbox">
	    		Fant ingen treff! :(
	    	</div>';
	    }
}?>


 </div>
<?php //echo 'Antall treff: '.$antallTreff ?>
</body>
</html>