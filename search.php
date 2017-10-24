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

<div class="search">
    <form method="get">
        <input type="text" name="search" placeholder="Search...">
        <input type="submit" value="Submit">
    </form>
</div>

<?php 

    // ----- SEARCH EXECUTION -----

if (!empty($_GET["search"])) {

    require(__DIR__.'/solarium/init.php');

    // create a client instance
    $client = new Solarium\Client($config);

    // get a select query instance
    $query = $client->createSelect();

    // set a query (all prices starting from 12)
    $query->setQuery('Document:' . $_GET["search"]);

    // set start and rows param (comparable to SQL limit) using fluent interface
    $query->setStart(0)->setRows(20);

    // this executes the query and returns the result
    $resultset = $client->select($query);


    // ----- RESULTS -----

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
}

 ?>