<?php
/**
 * Template Name: signupmobile
 *
 * @package Listify
 */
require_once("../../../wp-load.php");
$mobile = trim(stripslashes($_POST['mobile']));
if($mobile == '' ){
	$mobile = trim(stripslashes($_GET['mobile']));
}
if($mobile == '' ){
	echo "No_Number";
} else {
	function mobile_exists($mobile){
	//dual checking the database for phone numbers
		$args = apply_filters( 'job_manager_get_dashboard_jobs_args', array(
			'post_type'           => 'job_listing',
			'post_status'         => array( 'publish', 'expired', 'pending' ),
			'ignore_sticky_posts' => 0,
			'posts_per_page'      => -1,
			'orderby'             => 'date',
			'order'               => 'desc',
			) );
		$jobs = new WP_Query;
		$jobs->query( $args );
		$jobs->have_posts();
		while ( $jobs->have_posts() ) {
			$jobs->the_post();
			$listid = '';
			$listid = get_the_ID();
			$phone = '';
			$phone = get_post_meta($listid,'_mobile_num',true);
			if($phone == $mobile){
				echo $listid.'profile';
				return true;
			}
		}
		$blogusers = get_users( 'blog_id='.get_current_blog_id().'&orderby=nicename&role=employer' );
		foreach ( $blogusers as $user ) {
			$phone = '';
			$phone = get_cimyFieldValue($user->ID,'MOBILE');
			if($phone == $mobile){
				echo $user->ID.'user';
				return true;
			}
		}	
		return false;
	}
	if(!mobile_exists($mobile)){
		echo true;
	}
}
?>