<?php
/**
 * Template Name: signupafter
 *
 * @package Listify
 */
require_once("../../../wp-load.php");
if(!is_user_logged_in()){
	echo false;
} else{
	global $current_user;
	get_currentuserinfo();
	$userid = $current_user->ID;
	set_cimyFieldValue($userid,'INVITE','yes');
	echo true;
}
?>