<?php
/**
 * Template Name: yoastupdate
 *
 * @package Listify
 */

require_once("../../../wp-load.php");

get_header();
if ( defined('WPSEO_VERSION') ) {
	$listArray = array();
	$args = apply_filters( 'job_manager_get_dashboard_jobs_args', array(
		'post_type'           => 'job_listing',
		'post_status'         => array( 'publish', 'expired', 'pending' ),
		'ignore_sticky_posts' => 0,
		'posts_per_page'      => 200,
		'orderby'             => 'date',
		'order'               => 'desc',
		) );
	$jobs = new WP_Query;
	$jobs->query( $args );
	$jobs->have_posts();
	while ( $jobs->have_posts() ) {
		$jobs->the_post();
		array_push($listArray, get_the_ID());
	}

	foreach ($listArray as $id) {
		$title = get_the_title( $id )." - TipTapGo!";
		update_post_meta($id,'_yoast_wpseo_title', $title);

		$content_post = get_post($id);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
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
		update_post_meta($id,'_yoast_wpseo_metadesc', $content);
	}

	echo "<aside class='widget'><h1>post meta updated for seo<h1></aside>";
} else{
	echo "<aside class='widget'><h1>Yoast plugin is not installed or activated<h1></aside>";
}
get_footer();
?>