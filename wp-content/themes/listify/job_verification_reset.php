<?php
/**
 * Template Name: job_verification_reset
 *
 * @package Listify
 */
require_once("../../../wp-load.php");

$userid = trim(stripslashes($_GET['id']));
if($userid==''){
	$userid = trim(stripslashes($_POST['id']));
}
if($userid==''){
	echo "No User ID";
} else {
	$result = wp_delete_attachment( get_user_meta($userid, 'verification_image', true), true );
	update_user_meta($userid, 'verification_type', '');
	update_user_meta($userid, 'verification_image', '');
	update_user_meta($userid, 'verification_status', '');
	$result1 = get_user_meta($userid, 'verification_type', true);
	$result2 = get_user_meta($userid, 'verification_image', true);
	$result3 = get_user_meta($userid, 'verification_status', true);	
	if($result!=false && $result1=='' && $result2=='' && $result3==''){
		echo "Reset Success for User ID ".$userid;
	} else{
		echo "Reset Failed for User ID ".$userid;
	}
}


?>