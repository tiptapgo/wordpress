<?php
/**
 * Template Name: send
 *
 * @package Listify
 */
require_once('../wp-load.php');
$mobile = trim(stripslashes($_POST['mobile']));
if($mobile == '' ){
	$mobile = trim(stripslashes($_GET['mobile']));
}
$email = trim(stripslashes($_POST['email']));
if($email == '' ){
	$email = trim(stripslashes($_GET['email']));
}
$name = trim(stripslashes($_POST['name']));
if($name == '' ){
	$name = trim(stripslashes($_GET['name']));
}
$category = trim(stripslashes($_POST['category']));
if($category == '' ){
	$category = trim(stripslashes($_GET['category']));
}

if($category == '' || $name == '' || $email == '' || $mobile == ''){
	echo "ERR: REQUIRED PARAMETER MISSING";
} else {
	$to = 'help@tiptapgo.co';
	$subject = "Landing Page Form - ".$category;				
	$txt = '<!doctype html><html><head><title>'.$subject.'</title><style>body{padding: 30px; background-color: #F0F8FF !important; color: #3396d1; font-size: 18px;} strong{color: #2f3339;}h1{color: #3396d1}h2, h3{color: #72bb46;}</style></head><body style="background-color: #F0F8FF !important; color: #3396d1; font-size: 18px; padding: 30px;" bgcolor="#F0F8FF !important"> <h1 style="color: #3396d1;">'.$category.' landing page form</h1> <h2 style="color: #72bb46;">Info Recorded</h2> <div><strong style="color: #2f3339;">Name: </strong> '.$name.'</div><br><div><strong style="color: #2f3339;">Email: </strong>'.$email.'</div><br><div><strong style="color: #2f3339;">Phone No: </strong>'.$mobile.'</div><br></body></html>';
	$headers = "From: TipTapGo <help@tiptapgo.co>\r\n";
	$headers .= "Reply-To: TipTapGo <help@tiptapgo.co> help@tiptapgo.co\r\n";
	$headers .= "Cc: shivam_jpr@hotmail.com\r\n";				
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	wp_mail($to,$subject,$txt,$headers);
	echo true;
}
?>