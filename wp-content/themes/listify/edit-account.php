<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

require_once("../../../wp-load.php");

$userid = get_current_user_id();

$args     = apply_filters( 'job_manager_get_dashboard_jobs_args', array(
	'post_type'           => 'job_listing',
	'post_status'         => array( 'publish', 'expired', 'pending' ),
	'ignore_sticky_posts' => 1,
	'posts_per_page'      => 10,
	'orderby'             => 'date',
	'order'               => 'desc',
	'author'              => get_current_user_id()
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
	update_user_meta($userid,'email',$email);
}
if(isset($_POST['mobile_num']) && $_POST['mobile_num']!='') {
	$mobile = trim(stripslashes($_POST['mobile_num']));
	update_post_meta((int)$listid,'_mobile_num', $mobile);
	set_cimyFieldValue($userid, 'MOBILE', $mobile);
	//update_user_meta($userid,'cimy_uef_MOBILE',$mobile);
}
if(isset($_POST['tutor_bio']) && $_POST['tutor_bio']!='') {
	$bio = trim(stripslashes($_POST['tutor_bio']));
	update_post_meta((int)$listid,'_tutor_bio', $bio);
	update_user_meta($userid,'description',$bio);
}
if(isset($_POST['tutor_gender']) && $_POST['tutor_gender']!='') {
	$gender = trim(stripslashes($_POST['tutor_gender']));
	update_post_meta((int)$listid,'_tutor_gender', $gender);
	if(strtolower($gender) == 'male'){
		set_cimyFieldValue($userid, 'MALE', true);
		set_cimyFieldValue($userid, 'FEMALE', false);
		//update_user_meta($userid,'cimy_uef_MALE','YES');
		//update_user_meta($userid,'cimy_uef_FEMALE','NO');
	}
	if(strtolower($gender) == 'female'){
		set_cimyFieldValue($userid, 'MALE', false);
		set_cimyFieldValue($userid, 'FEMALE', true);
		//update_user_meta($userid,'cimy_uef_FEMALE','YES');
		//update_user_meta($userid,'cimy_uef_MALE','NO');
	}
}

header("Location: ".get_site_url()."/edit-details/");
die();

/* SKM -- Please don't change the location of this file on tthe server */
?>