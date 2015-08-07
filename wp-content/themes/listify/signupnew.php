<?php
/**
 * Template Name: signupnew
 *
 * @package Listify
 */

require_once("../../../wp-load.php");

$name = trim(stripslashes($_POST['signup_name']));
$email = trim(stripslashes($_POST['signup_email']));
if($name=='' || $email==''){
	echo false;
	die();
}
$mobile = trim(stripslashes($_POST['signup_mobile']));
$dob = trim(stripslashes($_POST['signup_dob']));
$gender = trim(stripslashes($_POST['signup_gender']));
$location = trim(stripslashes($_POST['signup_location']));
$category = trim(stripslashes($_POST['signup_category']));
$password = trim(stripslashes($_POST['signup_password']));

preg_match('#^(\w+\.)?\s*([\'\’\w]+)\s+([\'\’\w]+)\s*(\w+\.?)?$#', $name, $namesplit);

//lastname not provided
//implementing hack to fix a wrong regex
if($namesplit[2] == '') {
	$namesplit[2] = $name;
	$namesplit[3] = '';
}

$username = '';

	//checking for best username
if(!username_exists( $namesplit[2] ) && !email_exists($email)){ 
	$userdata = array(
		'user_login'  =>  $namesplit[2],
		'user_pass'   =>  $password,
		'user_email'  =>  $email,
		'first_name'  =>  $namesplit[2],
		'last_name'	  =>  $namesplit[3],
		'role'        =>  'employer',
		'display_name'=>  $name,
		);	
	$user_id = wp_insert_user( $userdata );
	$username = $namesplit[2];
}
	//checking for full name as username
else if(!username_exists( $namesplit[2].$namesplit[3] ) && !email_exists($email)){ 
	$userdata = array(
		'user_login'  =>  $namesplit[2].$namesplit[3],
		'user_pass'   =>  $password,
		'user_email'  =>  $email,		
		'first_name'  =>  $namesplit[2],
		'last_name'	  =>  $namesplit[3],
		'role'        =>  'employer',
		'display_name'=>  $name,
		);	
	$user_id = wp_insert_user( $userdata );
	$username = $namesplit[2].$namesplit[3];
}

	//running helter-skelter to register user
else{
	do {
		$random_str = wp_generate_password( $length=2, $include_standard_special_chars=false ); //increase length if a failure is seen.
		$userdata = array(
			'user_login'  =>  $namesplit[2].'.'.$random_str,
			'user_pass'   =>  $password,
			'user_email'  =>  $email,			
			'first_name'  =>  $namesplit[2],
			'last_name'	  =>  $namesplit[3],
			'role'        =>  'employer',
			'display_name'=>  $name,
			);	
		$user_id = wp_insert_user( $userdata );
		$username = ' ';
		$username = $namesplit[2].'.'.$random_str;
	} while ( is_wp_error( $user_id ));
}

if(is_wp_error( $user_id )){
	echo false;
	die();
}

$initdata = $gender.'@'.$mobile.'@'.$dob.'@'.$location.'@'.$category;
update_user_meta($user_id,'initdata',$initdata);

if(is_wp_error($user_id)){
	$result = $user_id->get_error_message();
} else if(!is_object($user_id)){
	$result = $user_id.'@'.$username;
}

echo $result;
?>