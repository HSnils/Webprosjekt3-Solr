<?php
	
	if (isset($_GET['id'])) {

		
		require('../../solarium/init.php');

		$documentID = htmlentities($_GET['id']);
		//$documentFileName = htmlentities($_GET['fileName']);
		htmlHeader();

		// create a client instance
		$client = new Solarium\Client($config);

		// get a select query instance
		$query = $client->createSelect();

		// get an update query instance
		$update = $client->createUpdate();

		// add the delete query and a commit command to the update query
		$update->addDeleteById($documentID);
		$update->addCommit();

		// this executes the query and returns the result
		$result = $client->update($update);

		//unlink("../../solr-6.6.1/uploads/".$documentFileName);

		//echo $documentFileName;
		echo '<b>Update query executed</b><br/>';
		echo 'Query status: ' . $result->getStatus(). '<br/>';
		echo 'Query time: ' . $result->getQueryTime();

		//header("Location: ../admin.php");

	} else {
		echo "This is not a valid pdf file.";

		header("Location: ../../index.php");
  		exit();
	}
	