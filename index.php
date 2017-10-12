<?php
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
?>


<!-- Starts Solarium and checks the connection to Solr -->
<?php
require(__DIR__.'/solarium/init.php');
htmlHeader();

// check solarium version available
echo 'Solarium library version: ' . Solarium\Client::VERSION . ' - ';

// create a client instance
$client = new Solarium\Client($config);

// create a ping query
$ping = $client->createPing();

// execute the ping query
try {
    $result = $client->ping($ping);
    echo 'Ping query successful';
    echo '<br/><pre>';
    var_dump($result->getData());
    echo '</pre>';
} catch (Solarium\Exception $e) {
    echo 'Ping query failed';
}

htmlFooter();
?>


<!-- Query builder example. Searching for documents from the year 2015. -->
<?php
htmlHeader();

// create a client instance
$client = new Solarium\Client($config);

// get a select query instance
$query = $client->createSelect();

// set a query (all prices starting from 12)
$query->setQuery('Year:2015');

// set start and rows param (comparable to SQL limit) using fluent interface
$query->setStart(0)->setRows(20);

// this executes the query and returns the result
$resultset = $client->select($query);

// display the total number of documents found by solr
echo 'NumFound: '.$resultset->getNumFound();

// show documents using the resultset iterator
foreach ($resultset as $document) {

    echo '<hr/><table>';

    // the documents are also iterable, to get all fields
    foreach ($document as $field => $value) {
        // this converts multivalue fields to a comma-separated string
        if (is_array($value)) {
            $value = implode(', ', $value);
        }

        echo '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
    }

    echo '</table>';
}

htmlFooter();
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<title>Webproject 3</title>

	<!--Jquery and UI-->
    <script src="jquery-ui/external/jquery/jquery.js"></script>
    <link rel="stylesheet" href="jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="jquery-ui/jquery-ui.theme.min.css">
    <script src="jquery-ui/jquery-ui.min.js"></script>

	<!--Css-->
	<link rel="stylesheet" href="css/main.css?<?php echo time(); ?>">

</head>
<body>
<nav>
	<div id="logo" class="navitem">LOGO</div>
	<div id="navbuttons" class="navitem">
		<b>
			<a href="">COOKIES OG PERSONVERN</a>
			<a href="">LOGG INN</a>
		</b>
	</div>
</nav>

<div id="search_container">
	<h2>FINN RAPPORTENE DU LETER ETTER</h2>
	<input type="search" name="searchfield" id="searchfield">
</div>

<div id="icon_container">
	<div>
		<img src="images/sikkerhet.svg" alt="ICON" class="icon">
		<span class="icon_heading">SIKKERHET</span> 
		<p>Textte xtt extet extetex tTex tt exttex te textetex tTextt extt extet extetext</p>
		<a href="">View collection</a>
	</div>
	<div>
		<img src="images/alarm_icon.svg" alt="ICON" class="icon">
		<span class="icon_heading">KRISEFORSIKRING</span>
		<p>Text Text text tex tetext etext Texttextte xtetexte textText te xtte xtetex tetext</p>
		<a href="">View collection</a>
	</div>
	<div>
		<img src="images/arbeidsmiljo_icon.svg" alt="ICON" class="icon">
		<span class="icon_heading">ARIBEIDSMILJØ</span>
		<p>Pass på </p>
		<a href="">View collection</a>
	</div>
</div>

<div id="aktuelt_container">
	<h2>AKTUELT</h2>
</div>


<br>

</body>
</html>