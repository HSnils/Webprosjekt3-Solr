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

 	<?php 

	    // ----- SEARCH EXECUTION -----

	if (!empty($_GET["search"])) {

	    require('../solarium/init.php');
 
	    // create a client instance
	    $client = new Solarium\Client($config);

	    // get a select query instance
	    $query = $client->createSelect();

	    // gets facets
	    $facetSet = $query->getFacetSet();

	   /* // create a facet query instance and set options
		$facetSet->createFacetQuery('date')->setQuery('year');*/

		//highlighting
		//get highlighting component and apply settings
		$hl = $query->getHighlighting();
		$hl->setFields('Date, text, Title, Author, Responsible, Operator');
		$hl->setSimplePrefix('<element style=" padding: 2px; background-color: #56a2aa; color: #f3f3f3;"><b>');
		$hl->setSimplePostfix('</b></element>');
		$hl->setSnippets(5);
		

	    // set a query (all prices starting from 12)
	    $query->setQuery($_GET["search"]);

	    // Initiate a DisMax query (Query multiple fields)
	    $dismax = $query->getDisMax();

	    // Select the fields we wish to use the search for
	    $dismax->setQueryFields('title Date Author Responsible Operator text id');

	    /*
	    // Example of how you can weigh each field differently.
	    $dismax->setQueryFields('title^3 cast^2 synopsis^1');
	    */

	    // this executes the query and returns the result
	    $resultset = $client->select($query);

	   // var_dump($resultset->getComponet());
	    //highlighting
	    $highlighting = $resultset->getHighlighting();

	    /*// display facet query count
		$count = $resultset->getFacetSet()->getFacet('date')->getValue();
		echo '<hr/>Facet query count : ' . $count;*/

	 	  // ----- RESULTS -----

	    $antallTreff = $resultset->getNumFound();
	    
	  
	    if($antallTreff != 0) {
	    	// display the total number of documents found by solr
	   		 echo '<div class="treffbox"> Antall treff på <u>'.$_GET['search'].'</u>: '.$antallTreff.'</div>';
		    // show documents using the resultset iterator
		    foreach ($resultset as $document) {

		        echo $document->title;
		        echo "<h2>hey</h2>";
		        echo $document->author;
		        echo substr($document->date, 0,10);
		        
				// highlighting results can be fetched by document id (the field defined as uniquekey in this schema)
				$highlightedDoc = $highlighting->getResult($document->id);
				if ($highlightedDoc) {
				    foreach ($highlightedDoc as $field => $highlight) {
				        echo implode(' (...) ', $highlight) . '<br/><hr>';
				    }
				}

				echo'<a target="_blank" href="../solr-6.6.1/uploads/assignment.pdf"><img src="../images/pdf_icon.svg" alt="CLICK TO OPEN PDF"></a>';
		    }
	    } else{
	    	echo '<div class="treffbox">
	    		Fant ingen treff på <u>'.$_GET['search'].'</u>!
	    	</div>';
	    }
}?>


 </div>
<?php //echo 'Antall treff: '.$antallTreff ?>
</body>
</html>