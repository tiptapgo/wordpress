<?php
/**
 * Template Name: bankadd
 *
 * @package Listify
 */

require_once("../../../wp-load.php");

if(!is_user_logged_in()){
	echo "ERR: REQUIRED PARAMETER MISSING";
} else {
	$userid = get_current_user_id();
	$bankername = trim(stripslashes($_POST['bank_holder_name']));
	$accountno = trim(stripslashes($_POST['bank_account_no']));
	$accounttype = trim(stripslashes($_POST['bank_type']));
	$ifsc = trim(stripslashes($_POST['bank_ifsc']));
	$bankname = trim(stripslashes($_POST['bank_name']));

	if($bankername != '' && $accountno != '' && $accounttype != '' && $ifsc != '' && $bankname != ''){
		update_user_meta($userid,'bankflag',"true");
		update_user_meta($userid,'bankername',$bankername);
		update_user_meta($userid,'accountno',$accountno);
		update_user_meta($userid,'accounttype',$accounttype);
		update_user_meta($userid,'ifsc',$ifsc);
		update_user_meta($userid,'bankname',$bankname);
		echo true;
	} else{
		echo "ERR: REQUIRED PARAMETER MISSING";
	}
}