<?php
/**
 * Template Name: autologin
 *
 * @package Listify
 */
require_once("../../../wp-load.php");
$usrid = trim(stripslashes($_POST['usrid']));
wp_set_current_user ( $usrid, $user->user_login );
wp_set_auth_cookie  ( $usrid );
do_action( 'wp_login', $user->user_login );	
echo true;
?>