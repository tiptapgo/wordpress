<?php
/**
 * Template Name: yoastupdate
 *
 * @package Listify
 */

require_once("../../../wp-load.php");

get_header();

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
	$weekstr = '';
	$jobhrs = get_post_meta( $id, '_job_hours', true );
	for ($i=1; $i < 8 ; $i++) { 
		$start =''; $end = ''; $ap =''; $ape=''; $hour=''; $houre=''; $temp1=''; $temp='';
		if($jobhrs[$i]['start']!="") {
			$start = $jobhrs[$i]['start'];
			$temp = $start;
			$start = str_split($jobhrs[$i]['start']);
			if($start[1]==':'){
				$hour = $start[0];
				$ap = $start[5].$start[6];
			} elseif ($start[2]==':') {
				$hour = $start[0].$start[1];
				$ap = $start[6].$start[7];
			}
			if(((int)$hour < 8 || (int)$hour ==12 ) && $ap == 'am') {
				$ap = 'pm';
			}				
		}
		if($jobhrs[$i]['end']!="") {
			$end = $jobhrs[$i]['end'];
			$end = str_split($jobhrs[$i]['end']);
			$temp1 = $end;
			if($end[1]==':'){
				$houre = $end[0];
				$ape = $end[5].$end[6];
			} elseif ($end[2]==':') {
				$houre = $end[0].$end[1];
				$ape = $end[6].$end[7];
			}
			if(((int)$houre < 8 || (int)$houre ==12 ) && $ape == 'am'){
				$ape = 'pm';
			}				
		}
		if(strtolower($temp) == "closed" || strtolower($temp1) == "closed") {
			$weekstr .= '%closed%';
			continue;
		}
		if( $end == "" || $start == "") {
			$weekstr .= '%empty%';
			continue;
		}					

		if($hour > $houre && $ap == $ape){
			$ap = 'am';
		}
		if($ape == 'am' && $ap=='pm'){
			$ape = 'pm';
		}
		if($ap == 'pm'){
			$hour += 12;
		}
		if($ape == 'pm'){
			$houre += 12;
		}		
		$slots = '';
		if($hour >= 8 && $hour < 12){
			$slots = '|8-12|';
			if($houre >= 12){
				$slots .= '12-16|';
			} elseif ($houre >=16) {
				$slots .= '16-20|';
			} elseif ($houre >=20) {
				$slots .= '20-22|';
			}
		}
		if($hour >= 12 && $hour < 16){
			$slots .= '|12-16|';
			if ($houre >=16) {
				$slots .= '16-20|';
			} elseif ($houre >=20) {
				$slots .= '20-22|';
			}			
		}
		if($hour >= 16 && $hour < 20){
			$slots .= '|16-20|';
			if ($houre >=20) {
				$slots .= '20-22|';
			}				
		}
		if($hour >= 20 && $hour < 22){
			$slots .= '|20-22|';
		}
		if($ap == 'pm'){
			$hour -= 12;
		}	
		if($ape == 'pm'){
			$houre -= 12;
		}
		if($houre == $hour && $hour == 12){
			$ap = 'pm';
			$houre = 4;
			$ape = 'pm';
			$slots = '|12-16|';
		}	
		if($slots !=''){			
			$slots = str_replace("||", "|", $slots);	
			$slots = substr($slots, 1, -1);			
		}
		$weekstr .= '%'.$slots.'%';					
	}
		if($weekstr!=''){			
			$weekstr = str_replace("%%", "%", $weekstr);	
			$weekstr = substr($weekstr, 1, -1);			
		}	
		if(substr_count($weekstr, '%') != 6)
			echo 'error';
	echo $weekstr.'<br>';
	update_post_meta($id,'multi_job_hrs', $weekstr);
}

get_footer();
?>