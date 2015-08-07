<?php

/**

 * My Account page

 *

 * @author 		WooThemes

 * @package 	WooCommerce/Templates

 * @version     2.0.0

 */



if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly

}

wc_print_notices();

if(isset($_GET['resetform']) && isset($_GET['resetform'])!=''){
	$reset = trim(stripslashes($_GET['resetform']));

	if((string)$reset == 'true' && is_user_logged_in()){
		header("location: http://tiptapgo.co/my-account/change-password/");
		die();
	}
}

if(isset($_GET['profile']) && isset($_GET['profile'])!=''){
	$profileupdate = trim(stripslashes($_GET['profile']));
}

global $current_user;
get_currentuserinfo();
$userid = $current_user->ID;

$listid = 0;
$listArray = array();
$urlArray = array();
$latArray = array();
$longArray = array();

$args = apply_filters( 'job_manager_get_dashboard_jobs_args', array(
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
$jobs->have_posts();
while ( $jobs->have_posts() ) {
	$jobs->the_post();
	array_push($listArray, get_the_ID());
	array_push($urlArray, get_permalink(get_the_ID()));	
	if(get_post_meta((int)get_the_ID(),'_mobile_num',true)!='' && $listid == 0){
		$listid = get_the_ID();
	}
}

$tutorLocation = get_post_meta((int)$listid,'_job_location', true);
if($tutorLocation == ''){
	$tutorLocation = get_cimyFieldValue($userid,'LOCATION');
}

$name = get_post_meta((int)$listid,'_tutor_name', true);
if($name ==''){
	$name = $current_user->display_name;
}

$pincode = get_post_meta((int)$listid,'_company_website', true);

$phone = get_post_meta((int)$listid,'_mobile_num',true);
if($phone == ''){
	$phone = get_cimyFieldValue($userid,'MOBILE');
}

$regdate = date("M Y", strtotime(get_userdata(get_current_user_id( ))->user_registered));

$gender = get_post_meta((int)$listid,'_listing_gender', true);
if($gender == ''){
	$temp = get_cimyFieldValue($userid,'MALE');
	if ($temp == "YES"){
		$gender = "Male";
	}
	$temp = get_cimyFieldValue($userid,'FEMALE');
	if ($temp == "YES"){
		$gender = "Female";
	}
}

$dob = get_post_meta((int)$listid,'_tutor_dob', true);
if($dob == ''){
	$dob = get_cimyFieldValue($userid,'DOB');
}

$age = '';
if($dob != '') {
	$tz  = new DateTimeZone('Asia/Calcutta');
	$age = DateTime::createFromFormat('d/m/Y', $dob, $tz)->diff(new DateTime('now', $tz))->y;
}
//force int to use age

$education = get_post_meta((int)$listid,'_tutor_high_edu', true);
if($education == ''){
	$education = get_cimyFieldValue($userid,'QUAL');
}

$experience = get_post_meta((int)$listid,'_tutor_exp', true);
if($experience == ''){
	$experience = get_cimyFieldValue($userid,'YOE');
}

$desc = get_post_meta((int)$listid,'job_description', true);

$about = get_post_meta((int)$listid,'_tutor_bio', true);
if($about == ''){
	$about = $current_user->description;
}
$about = nl2br($about);

$travel = get_the_terms((int)$listid, 'job_listing_type');
$travel = $travel[0]->name;

$profilepic = get_avatar( $userid , 200 );

// updating user profile for pre-existing accounts
if($current_user->description != '')
	update_user_meta($userid,'description',$current_user->description);
if($gender!=''){
	if(strtolower($gender) == 'male'){
		set_cimyFieldValue($userid, 'MALE', true);
		set_cimyFieldValue($userid, 'FEMALE', false);
	}
	else if(strtolower($gender) == 'female'){
		set_cimyFieldValue($userid, 'FEMALE', true);
		set_cimyFieldValue($userid, 'MALE', false);
	}
}
if($phone!=''){
	set_cimyFieldValue($userid, 'MOBILE', $phone);
}
if($dob!=''){
	set_cimyFieldValue($userid, 'DOB', $dob);
}
if($education!=''){
	set_cimyFieldValue($userid, 'QUAL', $education);
}
if($experience!=''){
	set_cimyFieldValue($userid, 'YOE', $experience);
}
if($tutorLocation!=''){
	set_cimyFieldValue($userid, 'LOCATION', $tutorLocation);
}

function is_decimal( $val )
{
	return is_numeric( $val ) && floor( $val ) != $val;
}			
$type = 'reviews';
$args=array(
	'meta_query' => array(
		array(
			'key' => 'review_tutor',
			'value' => $name,
			'compare' => 'LIKE'
			)
		),
	'post_type' => $type,
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'caller_get_posts'=> 1
	);

$my_query = null;
$my_query = new WP_Query($args);
$revArray = array();
$totalReviews = $my_query->post_count;
$comm = 0;
$sk = 0;
$ts = 0;
$dis = 0;
if( $my_query->have_posts() ) {
	while ($my_query->have_posts()) {
		$my_query->the_post();
		$revid = get_the_ID();
		$comm += get_post_meta($revid, 'review_comm', true);
		$sk += get_post_meta($revid, 'review_sk', true);
		$ts += get_post_meta($revid, 'review_ts', true);
		$dis += get_post_meta($revid, 'review_dis', true);									
	}
}
if($totalReviews != 0){
	$orcomm = ($comm/$totalReviews);
	$orsk = ($sk/$totalReviews);
	$orts = ($ts/$totalReviews);
	$ordis = ($dis/$totalReviews);			
	$comm = floor($comm/$totalReviews);
	$sk = floor($sk/$totalReviews);
	$ts = floor($ts/$totalReviews);
	$dis = floor($dis/$totalReviews);
}
else{
	$orcomm = 0;
	$orsk = 0;
	$orts = 0;
	$ordis = 0;
	$comm = 0;
	$sk = 0;
	$ts = 0;
	$dis = 0;
}
$commempty = ((is_decimal($orcomm))? (4 - $comm) : (5 - $comm));
$skempty = ((is_decimal($orsk))? (4 - $sk) : (5 - $sk));
$tsempty = ((is_decimal($orts))? (4 - $ts) : (5 - $ts));
$disempty = ((is_decimal($ordis))? (4 - $dis) : (5 - $dis));
$tutorAvgRating = (round(($orcomm + $orsk + $orts + $ordis) / 2) /2);
update_post_meta($listid,'_rating',$tutorAvgRating);
update_post_meta($listid,'rating',$tutorAvgRating);
wp_reset_query();

$i = 0;
$galleryArray = [];
for($i = 0; $i < count($listArray); $i++) {	
	$gallery = Listify_WP_Job_Manager_Gallery::get( $listArray[$i], false );
	if ( empty( $gallery ) ) {
		$gallery = array(0);
	}
	$gallery = new WP_Query( array(
		'post__in' => $gallery,
		'post_type' => 'attachment',
		'post_status' => 'inherit',
		'nopaging' => true
		) );
	while ( $gallery->have_posts() ) {
		$gallery->the_post();
		array_push($galleryArray, wp_get_attachment_image( $gallery->the_ID(), 'large' ));
	}
}	
?>

<?php if($profileupdate == 'true'){ ?>
	<div class="woocommerce-message">Your Profile is Updated Successfully!</div>
<?php } ?>

<style type="text/css">
	.ghost.button {
		background: #fff !important;
		color: #3396d1 !important;
		box-shadow: none !Important;
		border: 1px solid #3396d1 !important;
		border-radius: 5px !important;
		padding: 5px 30px;
		font-weight: bold !important;
		font-size: 16px !important;
	}
	input#submit{
		padding:10px 30px !important;
	}		
	h2{
		font-size:18px;
	}
	.fileinp:before{
		margin-top: -2px;
	}
	.showthis{
		display: block !important;
	}
	.aside-fix{
		padding:0;
	}
	.profile-img{
		text-align: center;
		border: 5px solid #fff;
	}
	.profile-img img{
		width: 100%;
	}
	.profile-info{
		padding: 0 25px;
	}
	.infbox{
		border: 1px solid #eee;
		border-radius: 5px;
		text-align: center;
	}
	.infbox h3{
		background-color: #eee;
		padding: 15px 0;
		border-radius: 5px;
		margin: 0;
		font-size: 14px !important;
	}
	.infbox p{
		text-align: left !important;
		padding: 20px;
	}
	.name{
		font-size: 20px;
	}
	li.tabs1li.ui-state-default.ui-corner-top {
		margin: 0 !important;
		text-align: center !important;
		padding: 0 !important;
		border: none !important;
		width: 25%;
		width: calc(100%/4);
	}

	.hide{
		display:none;
	}

	.content-single-job_listing-gallery-wrapper .type-attachment img {
		min-width: 100%;
		max-width: 100%;
		height: 170px;
		border-radius: 4px;
	}	

	.content-single-job_listing-hero-company{
		width: 100% !important;
	}
	#wpua-images-existing, .attachment-overlay, .single_job_listing>h1, h6, .job_listing-rating-count, .content-single-job_listing-title-category, .content-single-job_listing-hero-actions,#tabs1 .cta-button, .whatsapp{
		display:none !important;
	}

	.#tabs1 aside{
		display: none;
	}

	#wpua-add-existing{
		display: inline;
		background: rgba(0,0,0,0.5);
		padding: 12px 34px;
	}
	.wpua-edit-container, #wpua-add-button-existing, .submit{
		display:inline;
	}

	.submit input{
		padding: 13.5px 15px;
	}

	/*.change-img {
		opacity:0.3;
	}
	.profile-img:hover ~ .change-img{
		opacity:1;
	}
	.change-img:hover{
		opacity:1;
	}*/

	.job_listings .content-box {
		-webkit-box-shadow: inset 0px 0px 3px 3px rgba(204,204,204,1);
		-moz-box-shadow: inset 0px 0px 3px 3px rgba(204,204,204,1);
		box-shadow: inset 0px 0px 3px 3px rgba(204,204,204,1);
	}	

	.listing-cover.has-image {
		padding: 20px;
		height:300px;
	}
	.listify-add-to-gallery input[type=submit] {
		width: 155px;
		margin-top: 15px;
	}
	#listify-new-gallery-images::-webkit-file-upload-button {
		visibility: hidden;
		font-size:0;
	}
	<?php if(count( $galleryArray ) == 0 ) { ?>
		#listify-new-gallery-images::before, #gallerysubmit {
			right:50% !important;
			-ms-transform: translate(50%,0);
			-moz-transform: translate(50%,0);
			-webkit-transform: translate(50%,0);
			transform: translate(50%,0);
		}
		#listify-new-gallery-images::before{
			margin-top:-35px !important;
		}
		#gallerysubmit{
			top: -35px !important;
		}
		<?php } ?>	
		.listify-add-to-gallery{
			height:0;
		}

		<?php if(count( $galleryArray ) > 2) { ?>
			#listify-new-gallery-images::before{
				margin-top:-50px !important;
			}
			#gallerysubmit{
				top:-50px !important;
			}
			<?php } else if(count( $galleryArray ) <= 2 && count( $galleryArray ) > 0 ) { ?>
				#listify-new-gallery-images::before{
					margin-top:-20px !important;
				}
				#gallerysubmit{
					top: -20px !important;
				}
				<?php } ?>			

				#listify-new-gallery-images {
					font-size: 0;
					position: relative;
					width: 100%;
					height: 0;
				}
				#listify-new-gallery-images::before {
					content: 'Add Images to Gallery';
					display: inline-block;
					background: #fff;
					position: absolute;
					margin-top:-100px;
					right:0;
					border: 1px solid #3396d1;
					border-radius: 5px;
					padding: 10px 20px;
					outline: none;
					white-space: nowrap;
					-ms-user-select: none;
					-moz-user-select: none;
					-webkit-user-select: none;
					user-select: none;
					cursor: pointer;
					font-weight: bold;
					font-size: 16px;
					opacity: 1;
					color: #3396d1;
				}
				#gallerysubmit{
					padding: 10px 30px;
					position: absolute;
					top: -100px;
					right: 0;
				}

				#gallerysubmitdiv{
					position:relative;
				}

				.no-pad{
					padding:0 !important;
				}
				.classaside{
					padding:35px 20px;
				}
				.asidepad{
					margin: -10px 15px 0;
				}
				@media and screen (max-width: 768px){
					.container {
						padding:0
					}
				}

			</style>

			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
				<div id="secondary" class="widget-area col-md-3 col-sm-3 col-xs-12" role="complementary">
					<aside class="widget aside-fix">
						<div class="profile-img"><?php echo $profilepic; ?></div>
					</aside>
					<?php if($about != '') { ?>
					<aside class="widget aside-fix">		
						<div class="infbox">
							<h3>About Me</h3>
							<p><?php echo $about; ?></p>
						</div>
					</aside>
					<?php } ?>
					<?php if($travel != '') { ?>
					<aside class="widget aside-fix"> 
						<div class="infbox">
							<h3>Travel Policy</h3>
							<p><?php echo $travel; ?></p>
						</div>
					</aside>
					<?php } ?>
					<aside class="widget aside-fix"> 
						<div class="infbox">
							<h3>Share your Profile</h3>
							<p><a style="color: #3396d1" href="http://tiptapgo.co/tutor/<?php echo $current_user->user_login; ?>" target="_blank" title="">http://tiptapgo.co/tutor/<?php echo $current_user->user_login; ?></a></p>
						</div>
					</aside>					
				</div>
				<div id="primary" class="container">
					<div class="col-md-9 col-xs-12 col-sm-9 col-lg-9">
						<aside class="widget">
							<div class="profile-top">
								<div class="name"><?php echo $name; ?></div>
								<div class="profile-info">
									<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 no-pad">
										<div class="row">
											<?php
											$address = '';
											if($tutorLocation !='')
												$address = $address.$tutorLocation;
											if($pincode !='')
												$address = $address.", ".$pincode;
											if($address !='') { ?>
											<div class="col-md-6 col-xs-12 col-sm-6 col-lg-6">
												<span class="fa fa-map-marker iconsg"></span> <?php echo $address;?>
											</div>
											<?php }
											if($education !='') { ?>
											<div class="col-md-6 col-xs-12 col-sm-6 col-lg-6">
												<span class="fa fa-certificate iconsg"></span> <?php echo $education;?>
											</div>
											<?php }	?>	
										</div>
										<div class="row">			
											<?php
											if($regdate !='') { ?>
											<div class="col-md-6 col-xs-12 col-sm-6 col-lg-6">
												<span class="fa fa-clock-o iconsg"></span> <?php echo 'Member since '.$regdate;?>
											</div>									
											<?php }				 	 
											if($gender !='' || $age !='') { ?>
											<div class="col-md-6 col-xs-12 col-sm-6 col-lg-6">
												<span class="fa fa-trophy iconsg"></span><?php echo $gender; ?>
												<?php if($age !="")
												echo ", ".$age.' years old';
												?>
											</div>										
											<?php }								
											?>
										</div>
										<div class="row">			
											<?php
											if($experience !='') { ?>
											<div class="col-md-6 col-xs-12 col-sm-6 col-lg-6">										
												<span class="fa fa-calendar iconsg"></span> <?php echo $experience." years of tutoring experience";?>
											</div>	
											<?php }	?>
											<div class="col-md-6 col-xs-12 col-sm-6 col-lg-6">
												<?php if($totalReviews >1){ ?>										
												<span class="fa fa-star-o iconsg"></span><?php echo $tutorAvgRating." (".$totalReviews." Ratings)"; ?> 
												<?php  } else if($totalReviews == 1){ ?>
												<span class="fa fa-star-o iconsg"></span><?php echo $tutorAvgRating." (1 Rating)"; ?>
												<?php } else { ?>
												<span class="fa fa-star-o iconsg"></span>No ratings yet	 	
												<?php } ?>
											</div>	
										</div>								
									</div>
								</div>
								<div class="pull-right">
									<a id="ghost-profile" class="ghost button" href="http://tiptapgo.co/edit-profile/">Edit Profile</a>
								</div>	
							</div>	
						</aside>			
						<div class="middle-tabs">
							<div id="tabs1" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
								<?php if((int)$listid != 0) { ?>
								<ul style="padding:0 !important; margin:0 !important;margin-bottom: 40px !important;" class="aside-reset ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
									<li class="tabs1li ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="tabs1-1" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="#tabs1-1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">Classes</a></li>
									<li class="tabs1li ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs1-2" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="#tabs1-2" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">Reviews</a></li>
									<li class="tabs1li ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs1-3" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="#tabs1-3" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3">Gallery</a></li>
									<li class="tabs1li ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs1-4" aria-labelledby="ui-id-4" aria-selected="false" aria-expanded="false"><a href="#tabs1-4" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-4">Map</a></li>
								</ul>
								<?php } ?>
								<div id="tabs1-1">
									<aside class="widget classaside">
										<ul class="job_listings">
											<?php $jobs->have_posts(); ?>
											<?php while ( $jobs->have_posts() ) : $jobs->the_post(); ?>
												<?php echo get_template_part( 'content', 'job_listing' ); ?>
											<?php endwhile; ?>
										</ul>
										<?php if($listid == 0){ ?>
										<div class="text-center">
											<a id="ghost-classes" class="ghost button asidepad" href="http://tiptapgo.co/add-class/">Add Class</a>
										</div>
										<?php } else { ?>										
										<div class="pull-right">
											<a id="ghost-classes" class="ghost button asidepad" href="http://tiptapgo.co/edit-classes/">Edit Classes</a>
										</div>	
										<?php } ?>							
									</aside>
								</div>
								<?php if((int)$listid != 0) { ?>
								<div id="tabs1-2">
									<?php if($totalReviews > 0): ?>
										<aside class="widget">
											<h1 class="widget-title widget-title-job_listing ion-ios-chatbubble-outline"><?php echo $totalReviews; echo ($totalReviews > 1)?' Reviews':' Review';?></h1>
											<div class="row">
												<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
													<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
														<div class="ratinglabel">Communication</div>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
														<div class="star-wrap">
															<?php
															for ($i=0; $i < $comm ; $i++){
																echo '<span class="fa fa-star ratingstar"></span>';
															}
															if(is_decimal($orcomm)){
																echo '<span class="fa fa-star-half-o ratingstar"></span>';
															}
															for ($i=0; $i < $commempty ; $i++){
																echo '<span class="fa fa-star-o ratingstar"></span>';
															}
															?>								
														</div>
													</div>
												</div>
												<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">									
													<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
														<div class="ratinglabel">Subject Knowledge</div>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
														<div class="star-wrap">
															<?php
															for ($i=0; $i < $sk ; $i++){
																echo '<span class="fa fa-star ratingstar"></span>';
															}
															if(is_decimal($orsk)){
																echo '<span class="fa fa-star-half-o ratingstar"></span>';
															}								
															for ($i=0; $i < $skempty ; $i++){
																echo '<span class="fa fa-star-o ratingstar"></span>';
															}
															?>								
														</div>
													</div>	
												</div>								
											</div>	
											<div class="row">
												<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
													<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
														<div class="ratinglabel">Teaching Style</div>									
													</div>
													<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
														<div class="star-wrap">
															<?php
															for ($i=0; $i < $ts ; $i++){
																echo '<span class="fa fa-star ratingstar"></span>';
															}
															if(is_decimal($orts)){
																echo '<span class="fa fa-star-half-o ratingstar"></span>';
															}								
															for ($i=0; $i < $tsempty ; $i++){
																echo '<span class="fa fa-star-o ratingstar"></span>';
															}
															?>								
														</div>
													</div>
												</div>
												<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">									
													<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
														<div class="ratinglabel">Discipline</div>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
														<div class="star-wrap">
															<?php
															for ($i=0; $i < $dis ; $i++){
																echo '<span class="fa fa-star ratingstar"></span>';
															}
															if(is_decimal($ordis)){
																echo '<span class="fa fa-star-half-o ratingstar"></span>';
															}								
															for ($i=0; $i < $disempty ; $i++){
																echo '<span class="fa fa-star-o ratingstar"></span>';
															}
															?>								
														</div>
													</div>
												</div>									
											</div>
											<div class="review-body">
												<?php 
												if( $my_query->have_posts() ) {
													while ($my_query->have_posts()) {
														$my_query->the_post(); ?>
														<?php if(get_the_content() == ''){
															continue;
														}
														?>											
														<div>
															<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
																<div><?php the_content(); ?></div>
																<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 rev-meta">
																	<?php 
																	$revid1 = get_the_ID();
																	$anonymous = get_post_meta($revid1, 'review_anonymous', true);
																	$revname = get_post_meta($revid1, 'review_name', true);
																	if($anonymous == 'true')
																		echo "- Anonymous";
																	else if($anonymous == 'false')
																		echo "-".$revname." on ".date("d-M, Y", strtotime(get_the_date('Y-m-d')));
																	?>
																</div>
															</div>
														</div>	
														<hr>						
														<?php }
													}			
													wp_reset_query();
													?>
												</div>
												<div class="pull-right">					
													<form action="http://tiptapgo.co/invite-reviews/" method="POST" id="invite-reviews-form">
														<input type="hidden" name="id" id="id" value="<?php echo $listid; ?>">
														<input type="submit" name="submit" id="submit" value="Ask for Reviews" class="ghost button">
													</form>
												</div>													
											</aside>
										<?php else: ?>
											<aside class="widget">
												<h1 class="widget-title widget-title-job_listing ion-ios-chatbubble-outline">No Reviews Yet</h1>
												<h2>Be the first one to review</h2>									
												<div class="pull-right">					
													<form action="http://tiptapgo.co/invite-reviews/" method="POST" id="invite-reviews-form">
														<input type="hidden" name="id" id="id" value="<?php echo $listid; ?>">
														<input type="submit" name="submit" id="submit" value="Ask for Reviews" class="ghost button">
													</form>
												</div>							
											</aside>											
										<?php endif; ?>		
									</div>

									<div id="tabs1-3">

										<div>
											<div id="owl-example" class="owl-carousel owl-theme">
												<?php
												$k = 0;
												for($k = 0; $k < count($galleryArray); $k++){ ?>
												<div class="owl-item item">
													<?php echo $galleryArray[$k]; ?>
												</div>
												<?php } ?>
											</div>
											<br>											
										</div>


										<?php $gallery_url = esc_url( Listify_WP_Job_Manager_Gallery::url( $listArray[$i] ) );
										global $wp;
										$current_url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
										?>

										<form action="<?php echo $urlArray[0]; ?>" method="post" class="listify-add-to-gallery" enctype= "multipart/form-data">
											<input type="file" multiple="true" name="listify_gallery_images[]" id="listify-new-gallery-images" value="" />
											<div id="gallerysubmitdiv">
												<input class="ghost button" type="submit" id="gallerysubmit" name="submit" value="<?php esc_attr_e( 'Upload', 'listify' ); ?>" />
											</div>
											<input type="hidden" name="post_id" id="post_id" value="<?php echo $listArray[0]; ?>" />
											<input type="hidden" name="redirect" id="gallery-redirect" value="<?php echo $current_url.'#tabs1-3'?>" />
											<input type="hidden" name="listify_action" value="listify_add_to_gallery" />
											<?php wp_nonce_field( 'listify_add_to_gallery' ) ?>
										</form>
										<?php if(count( $galleryArray ) > 0) { ?>
										<div style="height: 40px;"></div>
										<?php } ?>										
										<?php if(count( $galleryArray ) <= 2 && count( $galleryArray ) > 0) { ?>
										<div style="height: 20px;"></div>
										<?php } ?>				
									</div>	
									<div id="tabs1-4">				    
										<div>
											<?php
											while ( $jobs->have_posts() ) {
												$jobs->the_post();
												dynamic_sidebar( 'mapr' );									
											} ?>
										</div>
									</div>
									<?php } ?>
								</div>					
							</div>
						</div>
					</div>
					<?php

					function print_my_inline_script() {
						if ( wp_script_is( 'jquery', 'done' ) ) {
							?>

							<link rel='stylesheet' id='owl-carousel-css'  href='https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css' type='text/css' media='all' />
							<link rel='stylesheet' id='owl-carousel-css1'  href='https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css' type='text/css' media='all' />
							<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js?ver=4.2.2'></script>
							<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js'></script>
							<script type="text/javascript">
								window.onload = function(){
									jQuery('#ui-id-1').click(function(){
										mixpanel.track("profile_view_classes");
									});
									jQuery('#ui-id-2').click(function(){
										mixpanel.track("profile_view_reviews");
									})
									jQuery('#ui-id-3').click(function(){
										mixpanel.track("profile_view_gallery");
									})	
									jQuery('#ui-id-4').click(function(){
										mixpanel.track("profile_view_maps");
									});
									jQuery('#invite-reviews-form').submit(function(){
										mixpanel.track("profile_ask_review");
									})
									jQuery('#ghost-profile').click(function(){
										mixpanel.track("edit_profile_btn");
									});
									jQuery('#ghost-classes').click(function(){
										mixpanel.track("edit_classes_btn");
									})
									jQuery('#invite-reviews-form').submit(function(){
										mixpanel.track("ask_reviews_btn");
									})
									jQuery('#gallerysubmit').click(function(){
										mixpanel.track("upload_photos_btn");
									});	
									jQuery('#listify_widget_panel_listing_map-2 .ghost').click(function(){
										mixpanel.track("edit_location_btn");
									});																																																			
									jQuery("#owl-example").owlCarousel({
										items : 2
									});
									jQuery('.owl-item img').each(function(){
										jQuery(this).css({"height": jQuery('.owl-wrapper').outerHeight() + "px"});
									});
									setTimeout(function(){
										jQuery('.woocommerce-message').hide();	
									}, 5000);
									if(jQuery('#owl-example').children().length == 0){
										jQuery('#owl-example').parent('div').hide();
										jQuery("#tabs1-3").append("<aside class='widget' id='galleryaside'></aside>");
										jQuery("#galleryaside").append(jQuery(".listify-add-to-gallery"));
									}																	

								}
								jQuery("#gallerysubmit").hide();
								setInterval(function(){
									if(jQuery('#listify-new-gallery-images').val() != ''){
										jQuery('#listify-new-gallery-images').hide();
										jQuery("#gallerysubmit").show();
									} else{
										jQuery('#listify-new-gallery-images').show();
										jQuery("#gallerysubmit").hide();
									}
								}, 100);


								jQuery(function() {
									jQuery('a[href*=#]:not([href=#])').click(function() {
										if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
											var target = jQuery(this.hash);
											target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
											if (target.length) {
												jQuery('html,body').animate({
													scrollTop: target.offset().top - 150
												}, 500);
												return false;
											}
										}
									});
								});
								jQuery('.tabs1li').click(function(){
									jQuery('.tabs1li').each(function(){
										jQuery(this).removeClass('ui-tabs-active ui-state-active');
									})
									jQuery(this).addClass('ui-tabs-active ui-state-active');					
								});
								jQuery(".tabs1li").hover(
									function () {
										jQuery(this).addClass("ui-state-hover");
									},
									function () {
										jQuery(this).removeClass("ui-state-hover");
									}
									);	
								jQuery('.listify_widget_panel_listing_map').not(':eq(0)').hide();
							</script>			
							<?php
						}
					}
					add_action( 'wp_footer', 'print_my_inline_script' );

					add_action( 'wp_head', 'print_my_inline_style' );

//do_action( 'woocommerce_before_my_account' ); ?>



<?php //wc_get_template( 'myaccount/my-downloads.php' ); ?>



<?php //wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>



<?php //wc_get_template( 'myaccount/my-address.php' ); ?>



<?php //do_action( 'woocommerce_after_my_account' ); ?>

<?php if($listid != 0) { ?>	
<script type="text/javascript">								
	jQuery(window).load(function(){						
		function initialize() {
			posArray = [];
			jQuery('.google_map_link').each(function(){
				str = jQuery(this).attr('href');
				str = str.substring(str.indexOf("=")+1, str.length);
				str = str.replace("%2C",",");
				str = str.split(',');
				posArray.push(str);
			})
			var i = 0;
			if(posArray.length/2 > 1)
			{
				var mapOptions = {
					zoom: 10,
					center: new google.maps.LatLng(12.9539974,77.6309395)
				}
			}
			else if(posArray.length/2 == 1){
				var mapOptions = {
					zoom: 15,
					center: new google.maps.LatLng(posArray[0][0],posArray[0][1])
				}
			}
			var map = new google.maps.Map(document.getElementById('listing-contact-map'),
				mapOptions);
			for(i = 0; i < posArray.length/2; i++){

				var myLatLng = new google.maps.LatLng(posArray[i][0],posArray[i][1]);
				image = new RichMarker({
					position: myLatLng,
					map: map,
					content: '<div class="marker-wrap" style="box-shadow: none;"><div class="map-marker type-37"><i class="ion-ios-home"></i></div></div>',
				});
				image.setShadow('none');
			}
		}
		initialize();

	});
<?php } ?>
</script>	