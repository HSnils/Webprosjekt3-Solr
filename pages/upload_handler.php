<?php
	
	if (!empty($_FILES['userFile']) && $_FILES['userFile']['type'] == 'application/pdf') {

		var_dump($_FILES['userFile']);

		$uploads_dir = '../solr-6.6.1/uploads/';
        $tmp_name = $_FILES["userFile"]["tmp_name"];
        $name = basename($_FILES["userFile"]["name"]);
        move_uploaded_file($tmp_name, "$uploads_dir/$name");


		$target_url = "http://localhost:8983/solr/safety/update/extract?commit=true";
		$file = file_get_contents($uploads_dir . $name);


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

		echo $result;

	} else {
		echo "This is not a valid pdf file.";
		unset($_FILES['userFile']);

		header("Location: admin.php");
  		exit();
	}
	