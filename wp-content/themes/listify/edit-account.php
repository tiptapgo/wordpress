<?php
/**
 * @package WordPress
 * @subpackage Listify
 */

require_once("../../../wp-load.php");

global $current_user;
get_currentuserinfo();
$userid = $current_user->ID;

$args     = apply_filters( 'job_manager_get_dashboard_jobs_args', array(
	'post_type'           => 'job_listing',
	'post_status'         => array( 'publish', 'expired', 'pending' ),
	'ignore_sticky_posts' => 1,
	'posts_per_page'      => 10,
	'orderby'             => 'date',
	'order'               => 'desc',
	'author'              => $userid
	) );
$jobs = new WP_Query;
$jobs->query( $args );
$listid = 0;
while ( $jobs->have_posts() ) {
	$jobs->the_post();
	$listid = get_the_ID();
}
function split_name($naam)
{
	$naam = trim($naam);
	if (strpos($naam, ' ') === false) {        
		return array('firstname' => $naam, 'lastname' => '');
	}

	$parts     = explode(" ", $naam);
	$lastname  = array_pop($parts);
	$firstname = implode(" ", $parts);

	return array('firstname' => $firstname, 'lastname' => $lastname);
}

if(isset($_POST['tutor_name']) && $_POST['tutor_name']!='') {
	$name = trim(stripslashes($_POST['tutor_name']));
	update_post_meta((int)$listid,'_tutor_name', $name);
	$nameArray = split_name($name);
	if($nameArray['lastname']=='')
		update_user_meta($userid,'first_name',$nameArray['firstname']);
	else {
		update_user_meta($userid,'first_name',$nameArray['firstname']);
		update_user_meta($userid,'last_name',$nameArray['lastname']);
	}
}
if(isset($_POST['application']) && $_POST['application']!='') {
	$email = trim(stripslashes($_POST['application']));
	update_post_meta((int)$listid,'_application', $email);
	wp_update_user( array( 'ID' => $userid, 'user_email' => $email ) );
}
$currentmob = get_post_meta((int)$listid,'_mobile_num', true);
if(get_user_meta($userid,'active',true) != 'active' || $currentmob==''){
	if(isset($_POST['mobile_num']) && $_POST['mobile_num']!='') {
		$mobile = trim(stripslashes($_POST['mobile_num']));
		update_post_meta((int)$listid,'_mobile_num', $mobile);
		set_cimyFieldValue($userid, 'MOBILE', $mobile);
	}
}
if(isset($_POST['tutor_bio']) && $_POST['tutor_bio']!='') {
	$bio = trim(stripslashes($_POST['tutor_bio']));
	update_post_meta((int)$listid,'_tutor_bio', $bio);
	wp_update_user( array( 'ID' => $userid, 'description' => $bio ) );
}
if(isset($_POST['tutor_dob']) && $_POST['tutor_dob']!='') {
	$dob = trim(stripslashes($_POST['tutor_dob']));
	update_post_meta((int)$listid,'_tutor_dob', $dob);
	set_cimyFieldValue($userid, 'DOB', $dob);
}
if(isset($_POST['tutor_location']) && $_POST['tutor_location']!='') {
	$location = trim(stripslashes($_POST['tutor_location']));
	update_post_meta((int)$listid,'_job_location', $location);
	set_cimyFieldValue($userid, 'LOCATION', $location);
}
if(isset($_POST['tutor_exp']) && $_POST['tutor_exp']!='') {
	$exp = trim(stripslashes($_POST['tutor_exp']));
	update_post_meta((int)$listid,'_tutor_exp', $exp);
	set_cimyFieldValue($userid, 'YOE', $exp);
}
if(isset($_POST['tutor_lang'])) {
	$lang = trim(stripslashes($_POST['tutor_lang']));
	set_cimyFieldValue($userid, 'LANGUAGE', $lang);
}
if(isset($_POST['tutor_high_edu']) && $_POST['tutor_high_edu']!='') {
	$qual = trim(stripslashes($_POST['tutor_high_edu']));
	update_post_meta((int)$listid,'_tutor_high_edu', $qual);
	set_cimyFieldValue($userid, 'QUAL', $qual);
}
if(isset($_POST['tutor_gender']) && $_POST['tutor_gender']!='') {
	$gender = trim(stripslashes($_POST['tutor_gender']));
	update_post_meta((int)$listid,'_tutor_gender', $gender);
	if(strtolower($gender) == 'male'){
		set_cimyFieldValue($userid, 'MALE', true);
		set_cimyFieldValue($userid, 'FEMALE', false);
	}
	if(strtolower($gender) == 'female'){
		set_cimyFieldValue($userid, 'MALE', false);
		set_cimyFieldValue($userid, 'FEMALE', true);
	}
}
/*
require './mixpanel/lib/Mixpanel.php';

$mp = Mixpanel::getInstance("f39c8dc269a848da80cc50761b6ddfe4");

$mp->people->set($userid, array(
    '$first_name'       => $nameArray['firstname'],
    '$last_name'        => $nameArray['lastname'],
    '$email'            => $email,
    '$mobile'           => $mobile,
    "Bio"				=> $bio,
    "Years of Experience" => $exp,
    "Qualification"		=> $qual,
    "Gender"    		=> $gender,
    "Date of Birth"		=> $dob,
    "Address"			=> $location,
    "User Id"			=> $userid,
    "Type"				=> "Tutor"
    ));*/

header("Location: ".get_site_url()."/my-account/?profile=true");
die();

/* SKM -- Please don't change the location of this file on tthe server */
?>