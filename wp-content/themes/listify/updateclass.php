<?php
/**
 * @package WordPress
 * @subpackage Listify
 */

require_once("../../../wp-load.php");

global $current_user;
get_currentuserinfo();
$userid = $current_user->ID;

$dummyid = trim(stripcslashes($_POST['id']));

$url = get_site_url();
if(!strpos($url, 'qa')){
	$dbname = "wordpress";
} else{
	$dbname = "qa";
}
$con=mysqli_connect("localhost","yoda","starwars",$dbname);
function filterData($data,$conn) {
	if (is_array($data)) {
		foreach ($data as $elem) {
			filterData($conn,$elem);
		}
	} else {
		$data = trim(htmlentities(strip_tags($data)));
		if (get_magic_quotes_gpc())
			$data = stripslashes($data);
		
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

		$data = mysqli_real_escape_string($conn,$data);
	}

	return $data;
}

$values = filterData($_POST,$con);
mysqli_close($con);

$job_slug   = array();
if ( ! empty( $values['company_name'] ) ) {
	$job_slug[] = $values['company_name'];
}
if ( ! empty( $values['job_location'] ) ) {
	$job_slug[] = $values['job_location'];
}
if ( ! empty( $values['job_type'] ) ) {
	$job_slug[] = $values['job_type'];
}

$job_slug[] = $post_title;

$final_slug = sanitize_title( implode( '-', $job_slug ) );

$my_post = array(
	'ID'			=> $dummyid,
	'post_title'    => $values['job_title'],
	'post_name'		=> $final_slug,
	'post_content'  => $values['job_description'],
	'post_author'	=> $user_id,	
	'post_type' 	=> 'job_listing'
	);

wp_update_post( $my_post, false);

if(isset($_POST['tutor_name']) && $_POST['tutor_name']!='') {
	$name = trim(stripslashes($_POST['tutor_name']));
	update_post_meta($dummyid,'_tutor_name', $name);
}

if(isset($_POST['application']) && $_POST['application']!='') {
	$email = trim(stripslashes($_POST['application']));
	update_post_meta($dummyid,'_application', $email);
}
if(isset($_POST['mobile_num']) && $_POST['mobile_num']!='') {
	$mobile = trim(stripslashes($_POST['mobile_num']));
	update_post_meta($dummyid,'_mobile_num', $mobile);
	set_cimyFieldValue($userid, 'MOBILE', $mobile);
}

if($current_user->description != ''){
	update_post_meta($dummyid,'_tutor_bio', $current_user->description);
}

$temp = get_cimyFieldValue($userid,'MALE');
if ($temp == "YES"){
	update_post_meta($dummyid,'_listing_gender', "Male");
}
$temp1 = get_cimyFieldValue($userid,'FEMALE');
if ($temp1 == "YES"){
	update_post_meta($dummyid,'_listing_gender', "Female");
}

$dob = get_cimyFieldValue($userid,'DOB');
if($dob!=''){
	update_post_meta($dummyid,'_tutor_dob', $dob);
}

$education = get_cimyFieldValue($userid,'QUAL');
if($education!=''){
	update_post_meta($dummyid,'_tutor_high_edu', $education);
}

$catterms = array_map('intval', $values['job_category']);
if( $values['job_category'] !='') {
	if(is_array($catterms)){
		wp_set_object_terms( $dummyid, $catterms, 'job_listing_category', false );
	} else{
		wp_set_object_terms( $dummyid, array($catterms), 'job_listing_category', false );
	}
}
$typeterms = array_map('intval', explode(',', $values['job_type']));
if($values['job_type'] != ''){
	if(is_array($typeterms)){
		wp_set_object_terms( $dummyid, $typeterms, 'job_listing_type', false );
	} else{
		wp_set_object_terms( $dummyid, array($typeterms), 'job_listing_type', false );
	}
}

update_post_meta($dummyid,'_job_classtype', $values['job_classtype']);

if($values['job_classtype'] == "regular"){

	if($values['newjobhrs'] != ''){
		update_post_meta($dummyid,'multi_job_hrs', $values['newjobhrs']);
	}
	if($values['job_monthly_classes'] != ''){
		update_post_meta($dummyid,'_job_monthly_classes', $values['job_monthly_classes']);
	}
	if($values['job_monthly_fees'] != ''){
		update_post_meta($dummyid,'_job_monthly_fees', $values['job_monthly_fees']);
	}
	if($values['job_no_of_seats'] != ''){
		update_post_meta($dummyid,'_job_no_of_seats', $values['job_no_of_seats']);
	}
	if($values['hourly_rate'] != ''){
		update_post_meta($dummyid,'_hourly_rate', $values['hourly_rate']);
	}
	/*
	if($values['tutor_exp'] != ''){
		update_post_meta($dummyid,'_tutor_exp', $values['tutor_exp']);
	}*/
} else if($values['job_classtype'] == "course"){
	if($values['job_date'] != ''){
		update_post_meta($dummyid,'_job_date', $values['job_date']);
	}
	if($values['job_start_time'] != ''){
		update_post_meta($dummyid,'_job_start_time', $values['job_start_time']);
	}
	if($values['job_end_time'] != ''){
		update_post_meta($dummyid,'_job_end_time', $values['job_end_time']);
	}
	if($values['hourly_rate'] != ''){
		update_post_meta($dummyid,'_hourly_rate', $values['hourly_rate']);
	}
	if($values['job_no_of_seats'] != ''){
		update_post_meta($dummyid,'_job_no_of_seats', $values['job_no_of_seats']);
	}	
			
} else if($values['job_classtype'] == "batch"){
	if($values['job_date'] != ''){
		update_post_meta($dummyid,'_job_date', $values['job_date']);
	}
	if($values['job_monthly_classes'] != ''){
		update_post_meta($dummyid,'_job_monthly_classes', $values['job_monthly_classes']);
	}
	if($values['job_day_dump'] != ''){
		update_post_meta($dummyid,'_job_day_dump', $values['job_day_dump']);
	}
	if($values['job_time_dump'] != ''){
		update_post_meta($dummyid,'_job_time_dump', $values['job_time_dump']);
	}	
	if($values['hourly_rate'] != ''){
		update_post_meta($dummyid,'_hourly_rate', $values['hourly_rate']);
	}
	if($values['session_duration'] != ''){
		update_post_meta($dummyid,'_session_duration', $values['session_duration']);
	}
	if($values['job_monthly_fees'] != ''){
		update_post_meta($dummyid,'_job_monthly_fees', $values['job_monthly_fees']);
	}		
	if($values['job_no_of_seats'] != ''){
		update_post_meta($dummyid,'_job_no_of_seats', $values['job_no_of_seats']);
	}	
			
}
if($values['tutor_gender_pref'] != ''){
	update_post_meta($dummyid,'_tutor_gender_pref', $values['tutor_gender_pref']);
}

if($values['job_location'] != ''){
	update_post_meta($dummyid,'_job_location', $values['job_location']);
}

if($values['company_website'] != ''){
	update_post_meta($dummyid,'_company_website', $values['company_website']);
}
if(isset($_POST['featured_image'])){
	if ( is_array( $_POST['featured_image'] ) ) {
		foreach ( $_POST['featured_image'] as $file_url ) {
			if ( strstr( $file_url, WP_CONTENT_URL ) ) {
				$maybe_attach[] = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $file_url );
			}
		}
	//	echo "arr\n";
	} else {
		if ( strstr( $_POST['featured_image'], WP_CONTENT_URL ) ) {
			$maybe_attach[] = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $_POST['featured_image'] );
		}
	//	echo "single\n";
	}
}
if ( sizeof( $maybe_attach ) ) {
	//echo "attsta\n";
	include_once( ABSPATH . 'wp-admin/includes/image.php' );
	$attachments     = get_posts( 'post_parent=' . $dummyid . '&post_type=attachment&fields=ids&post_mime_type=image&numberposts=-1' );
	$attachment_urls = array();
	foreach ( $attachments as $attachment_key => $attachment ) {
		$attachment_urls[] = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, wp_get_attachment_url( $attachment ) );
	}
	foreach ( $maybe_attach as $attachment_url ) {
		if ( ! in_array( $attachment_url, $attachment_urls ) ) {
			$attachment = array(
				'post_title'   => get_the_title( $dummyid ),
				'post_content' => '',
				'post_status'  => 'inherit',
				'post_parent'  => $dummyid,
				'guid'         => $attachment_url
				);
			if ( $info = wp_check_filetype( $attachment_url ) ) {
				$attachment['post_mime_type'] = $info['type'];
			}
			$attachment_id = wp_insert_attachment( $attachment, $attachment_url, $dummyid );
			if ( ! is_wp_error( $attachment_id ) ) {
				wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $attachment_url ) );
			}
		}
	}
	//echo "attend\n";
}

if ( defined('WPSEO_VERSION') ) {

	$title = $values['job_title'];
	update_post_meta($dummyid,'_yoast_wpseo_title', $title);

	$content = $values['job_description'];
	$flag = false;
	if(strlen($content) > 150){
		$flag = true;
	}
	$pos = strpos($content, ' ', 150);
	$content = substr($content,0,$pos ); 
	$content = rtrim($content, ',');
	if($flag){
		$content = $content.'…';
	}
	update_post_meta($dummyid,'_yoast_wpseo_metadesc', $content);
}
header("location: ".get_site_url()."/edit-classes/?edit=true");
?>