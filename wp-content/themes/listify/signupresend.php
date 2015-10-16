<?php
/**
 * Template Name: signupverify
 *
 * @package Listify
 */
require_once("../../../wp-load.php");
$mobile = trim(stripslashes($_POST['mobile']));

if($mobile==''){
	echo "ERR: INVALID PARAMETER";;
} else {
	$code = rand(1000, 9999);
	$url = "http://rest.nexmo.com/sms/json?api_key=8fa2959e&api_secret=b6268d96&from=TipTapGo&to=91".$mobile."&text=TipTapGo%20Sign-Up%20Code:%20".$code;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$nexmo = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	if($info['http_code'] != 200){
		echo "ERR: NEMXO ERROR: ".$info['http_code'];
	} else{
		$nexmoParse = json_decode($nexmo->messages, true);
		if($nexmoParse['status'] != 0){
			echo "ERR: NEMXO ERROR: ".$nexmoParse['error-text'];
		} else{ 
			echo $code;
		}
	}
}
?>