<?php
/**
 * Template Name: signupafter
 *
 * @package Listify
 */
require_once("../../../wp-load.php");
$email = trim(stripslashes($_POST['email']));
if($email == '' ){
	echo false;
	die();
}
if(email_exists($email)){
	echo false;
} else{
	echo true;
}
?>