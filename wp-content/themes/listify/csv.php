<?php

if (!isset($_FILES["fileToUpload"]['tmp_name'])){
	echo 'wrong data';
	exit;
}

function csv_to_array($filename='', $delimiter=',')
{
	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;
	
	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
		{
			if(!$header)
				$header = $row;
			else
				$data[] = array_combine($header, $row);
		}
		fclose($handle);
	}
	return $data;
}
session_start();
$testfile= $_FILES['fileToUpload']['name'];
$ext = pathinfo($testfile, PATHINFO_EXTENSION);
if( $ext !== 'csv' )  {
	$_SESSION['success'] = false;
	$_SESSION['message'] = 'Wrong file extention. No correct data found';
	header("Location: http://tiptapgo.co/index.php/tutor-form-links/");
} else {
	$urlArray = array();
	$nameArray = array();
	$csvfile= $_FILES['fileToUpload']['tmp_name'];
	$result = csv_to_array($csvfile);
	$url = "http://tiptapgo.co/index.php/become-a-tutor/";
	foreach ($result as $key => $value) {
		array_push($nameArray, $value["tutor_name"]);
		$final = $url . "?" . http_build_query($value);
		array_push($urlArray, $final);
	}
	$_SESSION['nameArr'] = $nameArray;
	$_SESSION['success'] = true;
	$_SESSION['tutor_form'] = $urlArray;
	$_SESSION['message'] = 'Your links have been successfully created';
	header("Location: http://tiptapgo.co/index.php/tutor-form-links/");
}
?>