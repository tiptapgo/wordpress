<?php
/**
 * Template Name: signupverify1
 *
 * @package Listify
 */
require_once("../../../wp-load.php");
$usrid = trim(stripslashes($_POST['usrid']));
if($usrid == ''){
	echo false;
} else{
	update_user_meta($usrid,"active","active");
	echo true;	
}
?>