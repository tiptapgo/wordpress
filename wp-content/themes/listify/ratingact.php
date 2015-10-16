<?php
/**
 * Template Name: Ratings
 *
 * @package Listify
 */

require_once("../../../wp-load.php");

$name = trim(stripslashes($_POST['review_name']));
$email = trim(stripslashes($_POST['review_email']));
if($name=='' || $email==''){
	echo false;
	die();
}
$tutor = trim(stripslashes($_POST['rev_tutor']));
$listid = trim(stripslashes($_POST['rev_tutorid']));
$comm = trim(stripslashes($_POST['hidden-comm']));
$sk = trim(stripslashes($_POST['hidden-sk']));
$ts = trim(stripslashes($_POST['hidden-ts']));
$dis = trim(stripslashes($_POST['hidden-dis']));
$cat = trim(stripslashes($_POST['review_cat']));
$review = trim(stripslashes($_POST['review_comment']));
$mobile = trim(stripslashes($_POST['review_mobile']));
$anonymous = trim(stripslashes($_POST['review_anonymous']));

if($anonymous == "true")
	$anonymous = 'true';
else
	$anonymous = 'false';

$my_post = array(
  'post_title'    => 'Review by '.$name.' for '.$tutor,
  'post_content'  => $review,
  'post_status'   => 'pending',
  'post_author'	=> '15',	
  'post_type' => 'reviews',
  'post_category' => strtolower($cat),
);

$postid = wp_insert_post( $my_post, false);

if($postid == 0){
	echo false;
	die();
}

update_post_meta($postid, 'review_id', $listid);
update_post_meta($postid, 'review_name', $name);
update_post_meta($postid, 'review_email', $email);
update_post_meta($postid, 'review_mobile', $mobile);
update_post_meta($postid, 'review_comm', $comm);
update_post_meta($postid, 'review_sk', $sk);
update_post_meta($postid, 'review_ts', $ts);
update_post_meta($postid, 'review_dis', $dis);
update_post_meta($postid, 'review_cat', $cat);
update_post_meta($postid, 'review_tutor', $tutor);
update_post_meta($postid, 'review_anonymous', $anonymous);

echo true;
die();
?>