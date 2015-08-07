<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

require_once("../../../wp-load.php");


if(!isset($_POST['phone']) || $_POST['phone']=='')
	echo "wrong data";
else{
	$phone = trim(stripslashes($_POST['phone']));
	$user_ID = get_current_user_id();
	//$res = wp_update_user( array( 'ID' => $user_ID, 'user_phone' => $phone ) );
	$res = update_user_meta($user_ID, 'user_phone', $phone);
	header('Location: '.$_POST['urlto']);
}

/* SKM -- Please don't change the location of this file on tthe server */
?>