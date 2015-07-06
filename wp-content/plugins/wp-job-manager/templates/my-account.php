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
$listArray = array();
$urlArray = array();
$latArray = array();
$longArray = array();
while ( $jobs->have_posts() ) {
	$jobs->the_post();
	if($listid == 0)
		$listid = get_the_ID();
	array_push($listArray, get_the_ID());
	array_push($urlArray, get_the_permalink());
	array_push($latArray, get_post_meta(get_the_ID(),'geolocation_lat',true));
	array_push($longArray, get_post_meta(get_the_ID(),'geolocation_long',true));				
}

	//print_r($longArray);
	//print_r($latArray);

$tutorLocation = get_post_meta((int)$listid,'_job_location', true);

$name = $current_user->display_name;

$pincode = get_post_meta((int)$listid,'_company_website', true);

$phone = get_post_meta((int)$listid,'_mobile_num',true);

$regdate = date("M Y", strtotime(get_userdata(get_current_user_id( ))->user_registered));

$gender = get_post_meta((int)$listid,'_listing_gender', true);

$dob = get_post_meta((int)$listid,'_tutor_dob', true);

$age = '';
if($dob != '') {
	$tz  = new DateTimeZone('Asia/Calcutta');
	$age = DateTime::createFromFormat('d/m/Y', $dob, $tz)->diff(new DateTime('now', $tz))->y;
}
//force int to use age

$education = get_post_meta((int)$listid,'_tutor_high_edu', true);

$experience = get_post_meta((int)$listid,'_tutor_exp', true);

$desc = get_post_meta((int)$listid,'job_description', true);

$about = get_post_meta((int)$listid,'_tutor_bio', true);

$travel = get_the_terms((int)$listid, 'job_listing_type');
$travel = $travel[0]->name;

$profilepic = get_avatar( $userid , 200 );

// updating user profile
if(get_user_meta($userid,'description',true) == '')
	update_user_meta($userid,'description',$about);
if(get_cimyFieldValue($userid,'MALE',true) == '' && get_cimyFieldValue($userid,'FEMALE',true) == ''){
	if(strtolower($gender) == 'male'){
		set_cimyFieldValue($userid, 'MALE', true);
		set_cimyFieldValue($userid, 'FEMALE', false);
	}
	if(strtolower($gender) == 'female'){
		set_cimyFieldValue($userid, 'FEMALE', true);
		set_cimyFieldValue($userid, 'MALE', false);
	}
}
if(get_cimyFieldValue($userid,'MOBILE',true) == '')
	set_cimyFieldValue($userid, 'MOBILE', $phone);

?>

<style type="text/css">
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
		font-size: 16px !important;
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
		width: 33.33%;
		width: calc(100%/3);
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
	#listify-new-gallery-images::before {
		content: 'Select Image Files';
		display: inline-block;
		background: #3396d1;
		border: none;
		border-radius: 3px;
		padding: 10px 20px;
		outline: none;
		white-space: nowrap;
		-webkit-user-select: none;
		cursor: pointer;
		font-weight: normal;
		font-size: 10pt;
		opacity:1;
		color:#fff;
	}
	#listify-new-gallery-images:active::before {
		background: #2878a7;
	}
	
</style>

<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
	<div id="secondary" class="widget-area col-md-3 col-sm-4 col-xs-12" role="complementary">
		<aside class="widget aside-fix">
			<div class="profile-img"><?php echo $profilepic; ?></div>
		</aside>
		<aside class="widget aside-fix">		
			<div class="infbox">
				<h3>About Me</h3>
				<p><?php echo $about; ?></p>
			</div>
		</aside>
		<aside class="widget aside-fix"> 
			<div class="infbox">
				<h3>Travel Policy</h3>
				<p><?php echo $travel; ?></p>
			</div>
		</aside>		
	</div>
	<div id="primary" class="container">
		<div class="col-md-9 col-xs-12 col-sm-9 col-lg-9">
			<aside class="widget">
				<div class="profile-top">
					<div class="name"><?php echo $name; ?></div>
					<div class="profile-info">
						<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
							<div class="row">
									<?php
									$address = '';
									if($tutorLocation !='')
										$address += $tutorLocation;
									if($pincode !='')
										$address += ", ".$pincode;
									if($address !='') { ?>
										<div class="col-md-6 col-xs-12 col-sm-6 col-lg-6">
											<span class="fa fa-map-marker iconsg"></span> <?php echo $address;?>
										</div>
									<?php } if($education !='') { ?>
										<div class="col-md-6 col-xs-12 col-sm-6 col-lg-6">
											<span class="fa fa-certificate iconsg"></span> <?php echo $education;?>
										</div>
							</div>
							<div class="row">			
									<?php }	
									if($regdate !='') { ?>
										<div class="col-md-6 col-xs-12 col-sm-6 col-lg-6">
											<span class="fa fa-clock-o iconsg"></span> <?php echo 'Member Since '.$regdate;?>
										</div>									
									<?php }				 	 
									if($gender !='' || $age !='') { ?>
										<div class="col-md-6 col-xs-12 col-sm-6 col-lg-6">
											<span class="fa fa-trophy iconsg"></span>											 <?php echo $gender;
											 	if($age !="")
											 		echo ", ".$age.'years old';
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
								</div>
							</div>	
						</div>	
					</div>
				</div>
			</aside>		
			<div class="middle-tabs">
				<div id="tabs1" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
					<ul style="padding:0 !important; margin:0 !important;margin-bottom: 40px !important;" class="aside-reset ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
						<li class="tabs1li ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="tabs1-1" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="#tabs1-1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">Listings</a></li>
						<li class="tabs1li ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs1-2" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="#tabs1-2" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">Gallery</a></li>
						<li class="tabs1li ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs1-3" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="#tabs1-3" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3">Map</a></li>
					</ul>
					<div id="tabs1-1">
						<div>
							<ul class="job_listings">
								<?php
								while ( $jobs->have_posts() ) {
									$jobs->the_post();
									//$curid = get_the_ID();
									//$type == get_post_type($curid);
									//echo $type;
									get_template_part( 'content', 'job_listing' );
								}	
								?>
							</ul>
						</div>
					</div>
					<aside class="widget showthis">	
						<div id="tabs1-2">
							<div>
								<?php $i = 0;
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
										?>	<h2> <?php echo get_the_title( $listArray[$i] ); ?> </h2>
										<?php								
										if ( $gallery->have_posts() ) : ?>
										<div class="content-single-job_listing-gallery-wrapper row" data-columns>
											<?php while ( $gallery->have_posts() ) : $gallery->the_post(); ?>
												<?php get_template_part( 'content-job_listing', 'attachment' ); ?>
											<?php endwhile; ?>
										</div>
									<?php else : ?>
										<p>No images found for this listing.</p>							
									<?php endif; ?>
									<?php $gallery_url = esc_url( Listify_WP_Job_Manager_Gallery::url( $listArray[$i] ) );
									global $wp;
									$current_url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
									?>
									<div id="add-photo">
										<?php _e( 'Upload Images', 'listify' ); ?>
										<div class="content-single-job_listing-upload-area">
											<form action="<?php echo $urlArray[$i]; ?>" method="post" class="listify-add-to-gallery" enctype= "multipart/form-data">
												<input class="fileinp" type="file" multiple="true" name="listify_gallery_images[]" id="listify-new-gallery-images" value="" />
												<input type="submit" name="submit" value="<?php esc_attr_e( 'Add Images', 'listify' ); ?>" />
												<input type="hidden" name="post_id" id="post_id" value="<?php echo $listArray[$i]; ?>" />
												<input type="hidden" name="redirect" id="gallery-redirect" value="<?php echo $current_url.'#tabs1-2'?>" />
												<input type="hidden" name="listify_action" value="listify_add_to_gallery" />
												<?php wp_nonce_field( 'listify_add_to_gallery' ) ?>
											</form>
										</div>
									</div>

									<?php } ?>
								</div>
							</div>
						</aside>
						<div id="tabs1-3">				    
							<div>
								<?php
								while ( $jobs->have_posts() ) {
									$jobs->the_post();
									dynamic_sidebar( 'mapr' );									
								} ?>
							</div>
						</div>
					</div>					
				</div>
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
				<link rel='stylesheet' id='jqueryuicss-css'  href='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css?ver=4.2.2' type='text/css' media='all' />
				<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js?ver=4.2.2'></script>
				<script type="text/javascript">

					window.onload = function(){
						var oldv = jQuery('#wp-user-avatar-existing').val();
						jQuery('.media-modal-close,.media-button-select').click(function(){
							var newv = jQuery('#wp-user-avatar-existing').val();
							if(newv != oldv){
								jQuery('#wpua-add-existing').css({"background-color":"#3396d1 !important"});
								oldv = newv;
							}
						});
					}
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
									content: '<div><div style="box-shadow: none;" class="map-marker type-37"><i class="ion-ios-home"></i></div></div>',
								});
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

//do_action( 'woocommerce_before_my_account' ); ?>



<?php //wc_get_template( 'myaccount/my-downloads.php' ); ?>



<?php //wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>



<?php //wc_get_template( 'myaccount/my-address.php' ); ?>



<?php //do_action( 'woocommerce_after_my_account' ); ?>

