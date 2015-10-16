<?php
/**
 * Template Name: bankclear
 *
 * @package Listify
 */

require_once("../../../wp-load.php");

$bankflag = "true";
$userid = trim(stripslashes($_GET['usrid']));
$key = trim(stripslashes($_GET['key']));

if($userid != '' && $key='starwars'){
	update_user_meta($userid,'bankflag',"false");
	update_user_meta($userid,'bankername','');
	update_user_meta($userid,'accountno','');
	update_user_meta($userid,'accounttype','');
	update_user_meta($userid,'ifsc','');
	update_user_meta($userid,'bankname','');
	echo "SUCCESS: Bank records cleared";
} else{
	echo "ERR: REQUIRED PARAMETER MISSING";
}