<?php
	if (!empty($_FILES['userFile']) && $_FILES['userFile']['type'] == 'application/pdf') {

		// Set upload directory.
		$uploads_dir = '../solr-6.6.1/uploads/';

		// Fetch file name.
        $tmp_name = $_FILES["userFile"]["tmp_name"];
        $filename = basename($_FILES["userFile"]["name"]);

        // Remove spaces in the filename.
        $name = preg_replace('/\s/', '', $filename);

        // Move the uploaded file to the upload directory.
        move_uploaded_file($tmp_name, "$uploads_dir/$name");

        // Connection url to solr, including the filename.
		$target_url = "http://localhost:8983/solr/safety/update/extract?literal.filename=" . $name . "&commit=true";

		// Reads the file into a string
		$file = file_get_contents($uploads_dir . $name);

		// Initiates and sets up the cURL operation
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $target_url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $file );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$headers = array();
		$headers[] = 'Accept: application/pdf';
		$headers[] = 'Content-Type: application/pdf';

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec ( $ch );
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close ( $ch );
		
		// SELECT QUERY
		require('../solarium/init.php');

		htmlHeader();

		// create a client instance
		$client = new Solarium\Client($config);

		// get a select query instance
		$query = $client->createSelect();

		// set a query
		$query->setQuery('*:*');

		// set start and rows param (comparable to SQL limit) using fluent interface
		$query->setStart(0)->setRows(1);

		// only select id's
		$query->setFields(array('id'));

		// sort the result
		$query->addSort('timestamp', $query::SORT_DESC);

		// this executes the query and returns the result
		$resultset = $client->select($query);

		foreach ($resultset AS $document) {
		    $document->getFields();
		}

		header("Location: editmeta.php?id=" . $document['id']);

	} else {
		echo "This is not a valid pdf file.";
		unset($_FILES['userFile']);

		header("Location: admin.php");
  		exit();
	}
	