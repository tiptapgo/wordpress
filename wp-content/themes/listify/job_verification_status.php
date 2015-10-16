<?php
/**
 * Template Name: job_verification_update
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
	$init1 = get_user_meta($userid, 'verification_image', true);
	$init2 = get_user_meta($userid, 'verification_type', true);
	update_user_meta($userid, 'verification_status', 'verified');
	$result = get_user_meta($userid, 'verification_status', true);
	if($result!='' && $result!='pending' && $init1!='' && $init2!=''){
		echo "Verification Success for User ID ".$userid;
	} else{
		echo "Verification Failed for User ID ".$userid;
	}
}

?>