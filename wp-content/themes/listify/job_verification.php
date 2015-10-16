<?php
/**
 * Template Name: job_verification_backend
 *
 * @package Listify
 */
require_once("../../../wp-load.php");
global $current_user;
get_currentuserinfo();
$userid = $current_user->ID;
$type = trim(stripslashes($_POST['job_verification_doc_type']));
$file = trim(stripslashes($_POST['job_verification_doc']));

require_once(ABSPATH . "wp-admin" . '/includes/image.php');
require_once(ABSPATH . "wp-admin" . '/includes/file.php');
require_once(ABSPATH . "wp-admin" . '/includes/media.php');

$attachment_id = media_handle_upload('job_verification_doc', 0);

if (is_wp_error( $attachment_id ) ) {
	header("location: ".get_site_url()."/account/?saveerror=true");
} else {
	update_user_meta($userid, 'verification_type', $type);
	update_user_meta($userid, 'verification_image', $attachment_id);
	update_user_meta($userid, 'verification_status', 'pending');

	header("location: ".get_site_url()."/account/?success=true");
}

?>