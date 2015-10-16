<?php
/**
* Template Name: Profile
*
* @package Listify
*/

if(isset($_POST['id']) && $_POST['id']!='')
	$userid = trim(stripslashes($_POST['id']));
else if(isset($_GET['id']) && $_GET['id']!='')
	$userid = trim(stripslashes($_GET['id']));
if(isset($_POST['nick']) && $_POST['nick']!=''){
	$nick = trim(stripslashes($_POST['nick']));
	$user = get_userdatabylogin(trim(stripslashes($_POST['nick'])));
	$userid = $user->ID;
}
else if(isset($_GET['nick']) && $_GET['nick']!=''){
	$nick = trim(stripslashes($_GET['nick']));	
	$user = get_userdatabylogin(trim(stripslashes($_GET['nick'])));
	$userid = $user->ID;
}

if($nick == '' || $userid == ''){
	header("location: ".get_site_url()."/sign-up/");
	die();
}

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
$listArray = array();
$urlArray = array();
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
	$name = $user->display_name;
}


add_filter( 'wp_title', 'yoast_add_title');
add_filter( 'wpseo_title', 'yoast_add_title');
function yoast_add_title( $str ) {
	return $GLOBALS['name']." Tutor Profile | TipTapGo!";
}

add_filter( 'wpseo_metadesc', 'yoast_add_meta_desc');
function yoast_add_meta_desc( $str ) {
	return $GLOBALS['name'] ." is on TipTapGo!. Join TipTapGo! to book ". $GLOBALS['name'] ."'s classes and connect with thousands of other tutors near you. TipTapGo! is an online marketplace for booking home classes in your area.";
}

get_header();

$pincode = get_post_meta((int)$listid,'_company_website', true);

$phone = get_cimyFieldValue($userid,'MOBILE');
if($phone == ''){
	$phone = get_post_meta((int)$listid,'_mobile_num',true);
}

$regdate = date("M Y", strtotime($user->user_registered));

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
	$about = $user->description;
}

$about = nl2br($about);

$travel = get_the_terms((int)$listid, 'job_listing_type');
$travel = $travel[0]->name;

$profilepic = get_avatar( $userid , 200 );

$langArray = array();
$profArray = array();
$langctr = 0;
$langdump = get_cimyFieldValue($userid,'LANGUAGE');
if($langdump != ''){
	$langdata = explode('|', $langdump);
	foreach ($langdata as $langunit) {
		$langunit = explode(',', $langunit);
		if($langunit[0] != ''){
			array_push($langArray, $langunit[0]);
			array_push($profArray, $langunit[1]);
			$langctr++;
		}
	}
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

<style type="text/css">
	.listify_widget_panel_listing_map.widget {
	    margin: 0;
	}
	.no-pad{
		padding:0 !important;
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
	.profile-info{
		padding: 0 25px;
	}	
	.profile-img{
		text-align: center;
		border: 5px solid #fff;
	}
	.profile-img img{
		width: 100%;
	}
	.infbox{
		border: 1px solid #ccc;
		text-align: center;
	}
	.infbox h3{
		background-color: #d4e5f2;
		padding: 15px 0;
		border-bottom: 1px solid #ccc;
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
	@media (max-width: 768px){
		li.tabs1li.ui-state-default.ui-corner-top {
			width: 100% !important;
			width: calc(100%/1) !important;
			margin-bottom: 3px !important;
			padding: 0 30% !important;
		}
	}		
	li.tabs1li.ui-state-default.ui-corner-top {
		margin: 0 !important;
		text-align: center !important;
		padding: 0 !important;
		border: none !important;
		width: 25%;
		width: calc(100%/4);
	}
	@media (max-width: 768px){
		li.tabs1li.ui-state-default.ui-corner-top {
			width: 100% !important;
			width: calc(100%/1) !important;
			margin-bottom: 3px !important;
			padding: 0 30% !important;
		}
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
	.ghost.button {
		background: #fff !important;
		color: #3396d1 !important;
		box-shadow: none !Important;
		border: 1px solid #3396d1 !important;
		border-radius: 5px !important;
		padding: 5px 30px !important;
		font-weight: bold !important;
		font-size: 16px !important;
	}
	input#submit{
		padding:10px 30px !important;
	}	
	.classaside{
		padding:15px 0;
	}
	.asidepad{
		margin:20px 40px;
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
	#listify-new-gallery-images {
		font-size: 0;
		position: relative;
		width: 100%;
		height: 0;
	}

	<?php if(count( $galleryArray ) > 2) { ?>
		#listify-new-gallery-images::before{
			margin-top:-35px !important;
		}
		#gallerysubmit{
			top:-46px !important;
		}
		<?php } else if(count( $galleryArray ) <= 2 && count( $galleryArray ) > 0 ) { ?>
			#listify-new-gallery-images::before{
				margin-top:-4px !important;
			}
			#gallerysubmit{
				top: -16px !important;
			}
			<?php } ?>	
			<?php if(empty( $galleryArray ) && is_user_logged_in() && $userid == get_current_user_id()) { ?>
				#listify-new-gallery-images::before, #gallerysubmit {
					right:50% !important;
					-ms-transform: translate(50%,0);
					-moz-transform: translate(50%,0);
					-webkit-transform: translate(50%,0);
					transform: translate(50%,0);
				}
				#listify-new-gallery-images::before{
					margin-top:-25px !important;
				}
				#gallerysubmit{
					top: -35px !important;
				}
				<?php } ?>	
				#listify-new-gallery-images::before {
					content: 'Add Images to Gallery';
					display: inline-block;
					background: #fff;
					position: absolute;
					margin-top:-80px;
					right: 20px;
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
					padding: 10px 30px !important;
					position: absolute;
					top: -80px;
					right: 20px;
				}

				#gallerysubmitdiv{
					position:relative;
				}
				@media and screen (max-width: 768px){
					.container {
						padding:0
					}
				}	
				.contact-side{
					margin: 0 0 40px;
				}
				.contact-btn{
					width: 100%;
					border: 1px solid #77c04b !important;
					-webkit-transition: all 0.3s ease-out;
					transition: all 0.3s ease-out;
				}
				.contact-btn.active{
					border: 1px solid #77c04b !important;
					background: #fff !important;
					color: #77c04b !important;
					box-shadow: none !important;
				}
				.contact-box{
					border: 1px solid #77c04b !important;
					border-top: 0 !important; 
					background: #eee !important;
					color: #77c04b !important;
					font-size: 14px;
					padding: 20px;
					-webkit-transition: all 1s linear;
					transition: all 1s linear;					
				}
				.infbox.maininf h3 {
				    text-align: left !important;
				    font-size: 16px !important;
				    padding: 15px 0 15px 40px;
				    color: #262626 !important;
				}
				.maininf h3 i {
				    font-size: 26px !important;
				    position: absolute;
				    margin: -5px 0 0 -27px;
				}				
			</style>
			<div id="primary" class="container">
				<div class="content-area">

					<main id="main" class="site-main" role="main">

						<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
							<div id="secondary" class="widget-area col-md-3 col-sm-3 col-xs-12 col-lg-3" role="complementary">
								<aside class="widget aside-fix">
									<div class="profile-img"><?php echo $profilepic; ?></div>
								</aside>

								<?php if(false) { //hidden?>
								<div class="contact-side">
									<button class="contact-btn">Contact <?php echo $name; ?></button>
									<div class="col-xs-12 hide contact-box text-center">
										<div>To contact <?php echo $name; ?>,</div>
										<div>SMS or WhatsApp</div>
										<div><?php echo $listid; ?> to 0 9901 079 974</div>
									</div>
								</div>	
								<?php } ?>

								<?php if($about != '') { ?>
								<aside class="widget aside-fix">		
									<div class="infbox">
										<h3>About Me</h3>
										<p><?php echo $about; ?></p>
									</div>
								</aside>

								<?php
								if($langdump != ''){ ?>
								<aside class="widget aside-fix">		
									<div class="infbox">
										<h3>Languages</h3>
										<p>
											<?php
											for($i = 0; $i < $langctr; $i++){ ?>

											<?php echo $langArray[$i]; ?><br>
											<?php echo $profArray[$i]; ?>
											<?php if($i != $langctr-1) { ?>
											<br><br>													
											<?php } } ?>
										</p>
									</div>
								</aside>
								<?php } ?>

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
										<p><a style="color: #3396d1" href="<?php echo get_site_url()."/tutor/".$nick; ?>" target="_blank" title=""><?php echo get_site_url()."/tutor/".$nick; ?></a></p>
									</div>
								</aside>							
							</div>
							<div id="primary" class="container no-pad">
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
															<span class="fa fa-map-marker iconsg"></span> <span id="tutaddress"><?php echo $address;?></span>
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
															<span class="fa fa-trophy iconsg"></span>
															<?php
															echo trim($gender);
															if($age !=""){
																echo ", ".$age.' years old';
															}
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
												<div>
													<?php if($listid == 0){  ?>
													<aside class="widget no-pad">
														<div class="infbox maininf"><h3><i class="ion-ios-folder-outline"></i> Classes</h3><?php echo $name ?> currently has no classes</div>
													</aside>
													<?php } ?>
													<ul class="job_listings">
														<?php $jobs->have_posts(); ?>
														<?php while ( $jobs->have_posts() ) : $jobs->the_post(); ?>
															<?php get_template_part( 'content', 'job_listing' ); ?>
														<?php endwhile; ?>
													</ul>						
												</div>
											</div>
											<?php if((int)$listid != 0) { ?>
											<div id="tabs1-2">
												<?php if($totalReviews > 0): ?>
													<aside class="widget no-pad">
														<div class="infbox maininf" style="padding-bottom: 65px;">
															<h3><i class="ion-ios-chatbubble-outline"></i> Reviews</h3>
															<p style="font-size: 16px;margin: 10px 43px;padding: 0;"><?php echo $totalReviews; echo ($totalReviews > 1)?' Reviews':' Review';?></p>
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

																<div class="pull-right" style="margin-right:20px">
																	<form action="<?php echo get_site_url()."/ratings/"; ?>" method="POST">
																		<input type="hidden" name="type" id="type" value="tutor">
																		<input type="hidden" name="id" id="id" value="<?php echo $listid; ?>">
																		<input type="submit" name="submit" id="submit" value="Add Review" class="ghost button">
																	</form>
																</div>

															</div>																		
														</aside>
													<?php else: ?>
														<aside class="widget no-pad">
															<div class="infbox maininf" style="padding-bottom: 60px;">
																<h3><i class="ion-ios-chatbubble-outline"></i> Reviews</h3>
																<p style="font-size: 16px;margin: 10px 43px;padding: 0;">Be the first one to review</p>
																<div class="pull-right" style="margin-right:20px">
																	<form action="<?php echo get_site_url()."/ratings/"; ?>" method="POST">
																		<input type="hidden" name="type" id="type" value="tutor">
																		<input type="hidden" name="id" id="id" value="<?php echo $listid; ?>">
																		<input type="submit" name="submit" id="submit" value="Add Review" class="ghost button">
																	</form>
																</div>

															</div>													
														</aside>											
													<?php endif; ?>		
												</div>
												<div id="tabs1-3" class="infbox maininf" style="margin: 50px 0;padding-bottom: 0;background-color:#fff;">
													<h3><i class="ion-ios-camera"></i> Gallery</h3>
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
													<?php if(is_user_logged_in() && $userid == get_current_user_id()) { 
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
														<?php } ?>
														<?php if(count( $galleryArray ) <= 2 && count( $galleryArray ) > 0) { ?>
														<div style="height: 20px;"></div>
														<?php } ?>										
													</div>
													<?php } ?>									
													<div id="tabs1-4">				    
														<div class="infbox maininf">
															<h3><i class="ion-ios-location-outline"></i> Location</h3>
															<?php
															if(!$jobs->have_posts()){
																dynamic_sidebar( 'mapr' );
															} else{
																while ( $jobs->have_posts() ) {
																	$jobs->the_post();
																	dynamic_sidebar( 'mapr' );									
																}
															}
															?>
														</div>
													</div>									
												</div>					
											</div>
										</div>
									</div>
								</main>
							</div>
						</div>

						<?php
						function print_my_inline_style() {  
							?>
							<!--<link rel='stylesheet' id='jqueryuicss-css'  href='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css?ver=4.2.2' type='text/css' media='all' />-->
							<?php  
						}

						function print_my_inline_script() {
							if ( wp_script_is( 'jquery', 'done' ) ) {
								?>

								<link rel='stylesheet' id='owl-carousel-css'  href='https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css' type='text/css' media='all' />
								<link rel='stylesheet' id='owl-carousel-css1'  href='https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css' type='text/css' media='all' />
								<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js'></script>
								<script type="text/javascript">
									mixpanelflag = 0;
									jQuery(document).ready(function(){
										jQuery(".contact-btn").click(function(){
											jQuery(this).toggleClass('active');
											jQuery('.contact-box').toggleClass('hide');
											if(mixpanelflag == 0){
												mixpanel.track("profile_contact");
												mixpanelflag = 1;
											}
										})
										jQuery("#owl-example").owlCarousel({
											items : 2
										});
										jQuery('.owl-item img').each(function(){
											jQuery(this).css({"height": jQuery('.owl-wrapper').outerHeight() + "px"});
										});	
									});
									<?php if(empty( $GLOBALS['galleryArray']) && (!is_user_logged_in() || $GLOBALS['userid'] != get_current_user_id())) {?>
										window.onload = function(){
											jQuery('#owl-example').parent('div').hide();
											jQuery('#tabs1-3').hide();
											jQuery('.tabs1li').each(function(){
												jQuery(this).css({"width":"33.33%"}).css({"width":"calc(100% / 3)"});
												if(jQuery(this).attr('aria-controls') == 'tabs1-3')
													jQuery(this).hide()
											})							
										}
										<?php } else if(empty( $GLOBALS['galleryArray'] )) { ?>
											window.onload = function(){
												if(jQuery('#owl-example').children().length == 0){
													jQuery('#owl-example').parent('div').hide();
												}
												jQuery("#tabs1-3").append("<aside class=\"widget\" id=\"galleryaside\" style=\"margin-bottom: 0;\"></aside>");
												jQuery("#galleryaside").append(jQuery(".listify-add-to-gallery"));
											}
											<?php } else { ?>
												window.onload = function(){	
													jQuery("#owl-example img").each(function(){
														jQuery(this).css({"height":"250px"});
													});
												}
												<?php } ?>	
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
												jQuery('.listify_widget_panel_listing_map').not(':eq(0)').hide();
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
												jQuery(window).load(function(){
													posArray = [];						
													function initialize() {									
														var geocoder = new google.maps.Geocoder();			
														if(jQuery('#maplink a').attr('href') == 'http://maps.google.com/maps?q=%2C'){
															geocoder.geocode( { 'address': jQuery("#tutaddress").text()}, function(results, status) {
																if (status == google.maps.GeocoderStatus.OK) {
																	lata = results[0].geometry.location.lat();
																	lnga = results[0].geometry.location.lng();
																	jQuery('#maplink a').attr('href','http://maps.google.com/maps?q=' + lata + '%2C' + lnga );
																	jQuery('#maplink a').each(function(){
																		newstr = jQuery(this).attr('href');
																		newstr = newstr.substring(newstr.indexOf("=")+1,newstr.length);
																		newstr = newstr.replace("%2C",",");
																		newstr = newstr.split(',');
																	});
																	addmap();													
																} 
															}); 				
														}
														else{
															addmap();
														}
													}
													function addmap() {
														if(jQuery('#maplink a').attr('href') != 'http://maps.google.com/maps?q=%2C'){
															jQuery('.google_map_link').each(function(){
																str = jQuery(this).attr('href');
																str = str.substring(str.indexOf("=")+1, str.length);
																str = str.replace("%2C",",");
																str = str.split(',');
																posArray.push(str);
															})
														}
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
														} else if(posArray.length/2 == 0.5){
															var mapOptions = {
																zoom: 15,
																center: new google.maps.LatLng(lata,lnga)
															}
															map = new google.maps.Map(document.getElementById('listing-contact-map'),
																mapOptions);
															myLatLng = new google.maps.LatLng(lata,lnga);
															image = new RichMarker({
																position: myLatLng,
																map: map,
																content: '<div class="marker-wrap" style="box-shadow: none;"><div class="map-marker type-37"><i class="ion-ios-home"></i></div></div>',
															});
															image.setShadow('none');
															return;									
														}
														map = new google.maps.Map(document.getElementById('listing-contact-map'),
															mapOptions);
														for(i = 0; i < posArray.length/2; i++){

															myLatLng = new google.maps.LatLng(posArray[i][0],posArray[i][1]);
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
</script>				
<?php
}
}
add_action( 'wp_footer', 'print_my_inline_script' );

add_action( 'wp_head', 'print_my_inline_style' );

get_footer(); ?> 
