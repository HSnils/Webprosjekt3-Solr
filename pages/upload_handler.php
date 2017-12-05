<?php
	
	if (!empty($_FILES['userFile']) && $_FILES['userFile']['type'] == 'application/pdf') {
/*		$filepath = 'webproj3/documents/';
		$filename = $_FILES['userFile']['name'];
		$ch = curl_init('http://localhost:8983/solr/safety/update/extract?literal.id=1&literal.name=Name&uprefix=attr_&fmap.content=attr_content&defaultField=text&commit=true');
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, array('myfile'=>"@webproj3/documents/assignment.pdf"));
		$result= curl_exec ($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close ( $ch );*/

		//var_dump($result);
		//echo $http_status;
		//echo $filepath . $filename;
		//echo $_FILES['userFile']['tmp_name'];

		var_dump($_FILES['userFile']);

		$uploads_dir = '../solr-6.6.1/uploads';
        $tmp_name = $_FILES["userFile"]["tmp_name"];
        $name = basename($_FILES["userFile"]["name"]);
        move_uploaded_file($tmp_name, "$uploads_dir/$name");


		$target_url = "http://localhost:8983/solr/safety/update/extract?commit=true";
		$file_path = "C:/xampp/htdocs/Webprosjekt3/solr-6.6.1/webproj3/documents/assignment.pdf";

		$post = array (
		   'myFile' => '@' . $uploads_dir . '/' . $name
		);

		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $target_url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post );
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