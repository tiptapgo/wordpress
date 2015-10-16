<?php
/**
 * The template for displaying a single job listings' content.
 *
 * @package Listify
 */

global $job_manager;
$classtype = get_post_meta(get_the_ID(),'_job_classtype',true);
if($classtype == ''){
	update_post_meta(get_the_ID(),'_job_classtype','regular');	
}
elseif ($classtype == 'course') {
	$monthlyfees = get_post_meta(get_the_ID(), '_hourly_rate', true);
} 
else{
	$monthlyfees = get_post_meta(get_the_ID(), '_job_monthly_fees', true);
	if($monthlyfees == ''){
		$monthlyfees = get_post_meta(get_the_ID(), '_hourly_rate', true);
	} 
}
$booktutor = get_the_author_meta( 'ID' );
$bookclass = get_the_ID();
?>


<style type="text/css">
	@media (min-width: 992px) and (max-width: 1200px){
		.col-md-2 {
			width: 19.666667%;
		}
	}
	@media (max-width: 991px){
		.timeval {	
			margin: 7px 10px;
			padding: 3px;
			display: inline;
		}
		.inline-div {
			float: none !important;
		}
		.hide{
			display: none !important;
		}
		.classtypemean{
			display: block;
		}
		.bookrow.row {
		    padding: 0 80px !important;
		}
	}
	@media (max-width: 487px){
		.pull-left, .pull-right {	
			width: 100%;
		}
		.pull-left input[name=submit], .pull-right input[name=submit]{
			width: 100%;
			margin: 10px 0;
		}
	}
	@media (min-width: 992px){
		.skmmenu{
			position: fixed;
			z-index: 999;
			margin-top: 30px !important; 			
		}
		.skmclassinfo{
			margin-top: 120px !important;
		}
		.bookleft{
			margin: 15px 0 15px 20px;
		}	  
		.bookright{
			margin: 15px 0;
		}		

	} 	
	@media (min-width: 992px) and (max-width: 1012px){
		.skmmenu{
			margin-top: 105px !important; 			
		}
		.skmclassinfo{
			margin-top: 195px !important;
		}
	}
	@media (max-width: 767px) {
		.skmmenu ul > li {
			margin: 0 30% 0 23% !important;
			margin: 0 30% 0 calc(30% - 20px) !important;
			line-height: 45px !important;
			display: block !important;
			padding: 0 !important;
			text-align: center;
			min-height: 50px;
		}
	}
	small{
		color: #3396d1;
		cursor: pointer;
		text-transform: none;
	}
	h6{
		margin-top: 10px;
	}	
	.bookleft{
		border: 1px solid #77c04b;
		border-top-left-radius: 4px;
		border-bottom-left-radius: 4px;  
		color: #77c04b;
		cursor: pointer;
		font-size: 16px;
		padding: 5px 0 !important;
	}	  
	.bookright{
		border: 1px solid #77c04b;
		border-top-right-radius: 4px;
		border-bottom-right-radius: 4px; 	    
		background: #77c04b;
		color: #fff;
		cursor: pointer;
		font-size: 16px;
		padding: 5px 0 !important;
	}	 	
	.entry-cover.has-image:after, .listing-cover.has-image:after {
		background: transparent !important; 
	}
	.gallery-preview-image{
		border-radius: 0 !important;
		width: 75.5px !important;
	}
	.pull-left, .pull-right {
		margin-top: 10px;
	} 	
	#listify_widget_panel_listing_map-2 > div{
		padding: 0 !important;
	}
	#primary #listify_widget_panel_listing_map-2 h1.widget-title {
		margin: 0 !important;
	}
	#primary aside {
		margin-bottom: 30px !important;
		margin-top: 0 !important;
		border: 1px solid #ccc;
		border-radius: 0 !important;
		padding: 0 !important;
		font-size: 16px;
		padding-bottom: 20px !important; 	    
	}
	#primary #listify_widget_panel_listing_map-2{
		padding-bottom: 0 !important;
	}	
	#primary aside > ul ,#primary aside > h6, #primary aside > p, #primary aside > div{
		padding: 0 20px;
	}
	#primary aside > ul > li{
		padding: 0 10px;
	}
	#primary h1.widget-title {
		background: #d4e5f2;
		padding: 15px 0 15px 20px;
		border-bottom: 1px solid #ccc;
	}	
	#primary{
		margin-top: 30px;
	}
	.ion-camera:before {
		opacity: 0.5;
	}	
	.datarow{
		margin: 0 20px 0 0;
	}
	.skmclassinfo, .skmmenu{
		margin: 70px 0 0;
		border: 1px solid #ccc;
		background: #fff;
	}
	.skmmenu{
		margin-bottom:  -5px !important; 
	}
	.skmclassinfo{
		margin-top: 50px;
		padding: 15px;
	}
	.skmclassinfo .content-single-job_listing-actions-start, .skmclassinfo .job_listing-location , .skmclassinfo .job_listing-rating-wrapper{
		display: none;
	}	
	.content-area {
		margin-top: 15px !important;
	}	
	.content-single-job_listing-hero-company{
		text-transform: capitalize;
		color: #333 !important;
	}
	.content-single-job_listing-hero-class-type,.content-single-job_listing-title-category a {
		color: #555 !important;
		font-size: 15px;
	}
	.content-single-job_listing-hero-class-type{
		text-transform: capitalize;
	}
	.timeval {
		color: #fff;
		background: #3396d1;
		margin: 5px 10px;
		text-align: center;
		font-size: 13px;
	}	
	.dayname{
		margin: 5px 0;
		font-size: 14px;
		padding: 0;
		text-transform: capitalize;
	}
	.skmclassdet{
		padding: 0 20px 15px !important;
		line-height: 22px;
		display: block;
	}
	.skminfoicon{
		font-size: 22px !important;
		color: #3396d1;
		position: relative;
		top: 3px;
		padding-right: 15px;
	}
	a.go-to-gallery{
		margin-top: -35px;
	}
	.skmmenu ul{
		list-style-type: none;
		margin-bottom: 0 !important
	}
	.skmmenu ul > li{
		display: inline;
		margin: 0 10px;
		padding: 18px 20px;
		line-height: 60px;
		pointer-events: none;		
	}
	.skmmenu ul > li > a {
		pointer-events: auto;	
	}
	.skmmenu ul > li.hover, .skmmenu ul > li.active{
		border-bottom: 4px solid #3396d1;
	}
	.skmmenulink{
		text-decoration: none;
		outline: 0 !important;
		font-size: 16px;
		color: #333;
	}
	.inline-div{
		display: inline;
		float: left;
		font-size: 16px;
		position: relative;
		top: 3px;
	}
	.no-pad {
		padding: 0 35px!important;
	}
	.classtypemean {
		font-size: 14px;
		padding: 2px 10px;
		color: #fff;
		text-transform: none;
		background: #444;
		border-radius: 4px;
		margin-left: 10px;
	}
	
	#skm-book {
		position: absolute;
		width: 100%;
		height: 100%;
		z-index: 1000;
		background: rgba(255,255,255,0.95);
	}
	.book-form{
		width: 400px;
		height: 540px;
		padding: 30px;
		background: #eee;
		position: absolute;
		left: 50%;
		top: 40%;
		-moz-transform: translate(-50%, -50%);
		-ms-transform: translate(-50%, -50%);
		-webkit-transform: translate(-50%, -50%);		
		transform: translate(-50%, -50%);
	}
	.book-form form{
		margin-top: 20px;
	}

	.book-form strong{
		font-size: 20px;
		color: #333;
	}
	.book-form input{
		width: 100%;
		background: #fff;
		border: none;
		font-size: 16px;
		line-height: 43px;
		padding: 0;
		padding-left: 20px;		
	}
	.skm-form-control{
		height: 45px;
		width: 100%;
		padding-left: 20px;
		border: 1px solid #ccc;
		background: #fff;
		margin: 10px 0;
	}
	.skm-form-control i {
		position: absolute;
		top:0px;
		left: -5px;
		font-size: 30px !important;
		pointer-events:none;
	}
	.skmbtn{
		border: 1px solid #ccc;
		background: #fff;
		height: 45px;
		line-height: 45px;
		font-size: 14px;
		width: 155px;
		color: #888;
		text-align: center;
		cursor: pointer;
	}
	.skmbtn.active{
		border: 1px solid #3396d1;
		background: #3396d1;
		color: #fff;
	}
	.book-form #submit{
		box-shadow: none;
		height: 45px !important;
		width: 100%;
		background: #72bb46 !important;
		margin-top: 20px;
	}
	.no-width{
		width: 0 !important;
	}
	.skmrow:before,
	.skmrow:after {
		display: table;
		content: " ";
	}

	.skmrow:after {
		clear: both;
	}
	.skmclosebtn{
		position: absolute;
		right: 20px;
		top: 10px;
		font-size: 45px !important;
		line-height: 45px;
		padding: 0 10px;
		cursor: pointer;
	}
	.success {
		font-size: 16px;
		text-align: center;
		color: #333;
	}
	#lookup {
		background: #72bb46 !important;
		margin: 20px 0;
		width: 100%;
	}		
	#verify, #verifycode, #verifybtn, #finalbtn{
		box-shadow: none;
		height: 45px !important;
		width: 100%;
		margin-top: 20px;
	}
	.finaldetail{
		color: #333;
		font-size: 14px;
	}
	.right{
		text-align: right;
	}
	.finaldetail strong {
	    font-size: 14px !important;
	}
	.lockicon {
	    font-size: 30px !important;
	    position: absolute;
	    color: #fff !important;
	    margin: 20px 18px;
	}		
</style>
<div class="skm-modal-wrap">
	<div id="skm-book" class="hide">
		<div class="book-form">
			<strong>You are a step away from starting classes.</strong>
			<i class="ion-ios-close-empty skmclosebtn"></i>
			<form action="<?php echo get_template_directory_uri();?>/bookclass.php" method="POST" id="bookform">
				<input type="hidden" name="booktutor" id="booktutor" value="<?php echo $booktutor; ?>">
				<input type="hidden" name="bookclass" id="bookclass" value="<?php echo $bookclass; ?>"> 	  		 	  		
				<input type="hidden" name="bookfees" id="bookfees" value="<?php echo $monthlyfees; ?>"> 
				<div class="formdivs">
					<div class="skm-form-control row">
						<div class="col-xs-12 col-md-12 col-lg-12 col-sm-12">
							<i class="ion-ios-person-outline"></i>
							<input type="text" name="bookname" id="bookname" placeholder="Full name"> 
						</div>
					</div>
					<div class="skm-form-control row">
						<div class="col-xs-12 col-md-12 col-lg-12 col-sm-12">
							<i class="ion-ios-at-outline"></i>
							<input type="text" name="bookemail" id="bookemail" placeholder="Email"> 
						</div>
					</div>
					<div class="skm-form-control row">
						<div class="col-xs-12 col-md-12 col-lg-12 col-sm-12">
							<i class="ion-ios-telephone-outline"></i>
							<input type="text" name="bookmobile" maxlength="10" id="bookmobile" placeholder="Mobile"> 
						</div>
					</div>
					<div class="skm-form-control row">
						<div class="col-xs-12 col-md-12 col-lg-12 col-sm-12">
							<i class="ion-ios-navigate-outline"></i>
							<input type="text" name="booklocation" id="booklocation" placeholder="Location"> 
						</div>
					</div>
					<div class="skmrow">
						<div class="col-xs-5 col-md-5 col-lg-5 col-sm-5 skmbtn skmgender" data-value="Male">I'm a Male</div>
						<div class="no-width col-xs-2 col-md-2 col-lg-2 col-sm-2"></div>
						<div class="col-xs-5 col-md-5 col-lg-5 col-sm-5 skmbtn skmgender active" data-value="Female">I'm a Female</div>
						<input type="hidden" name="bookgender" id="bookgender" value="Female">
					</div>	
					<div class="skm-form-control row">
						<div class="col-xs-12 col-md-12 col-lg-12 col-sm-12">
							<i class="ion-ios-calendar-outline"></i>
							<input maxlength="3" type="text" name="bookage" id="bookage" placeholder="Age"> 
						</div>
					</div>
				</div>
				<div class="finaldetail hide">
					<div class="row">
						<div class="col-md-4 col-xs-4"><strong>Name</strong></div>
						<div class="col-md-8 col-xs-8 right finalname"></div>						
					</div>
					<div class="row">
						<div class="col-md-4 col-xs-4"><strong>Email</strong></div>
						<div class="col-md-8 col-xs-8 right finalemail"></div>		
					</div>
					<div class="row">
						<div class="col-md-4 col-xs-4"><strong>Mobile</strong></div>						
						<div class="col-md-8 col-xs-8 right finalmobile"></div>		
					</div>
					<div class="row">
						<div class="col-md-4 col-xs-4"><strong>Location</strong></div>						
						<div class="col-md-8 col-xs-8 right finallocation"></div>		
					</div>
					<div class="row">
						<div class="col-md-4 col-xs-4"><strong>Gender</strong></div>						
						<div class="col-md-8 col-xs-8 right finalgender"></div>		
					</div>
					<div class="row">
						<div class="col-md-4 col-xs-4"><strong>Age</strong></div>						
						<div class="col-md-8 col-xs-8 right finalage"></div>		
					</div>					
					<br><hr>
					<div class="row">
						<div class="col-md-8 col-xs-8"><strong>Fees</strong></div>
						<div class="col-md-4 col-xs-4 right"><i class="fa fa-inr"></i> <?php echo $monthlyfees; ?></div>		
					</div>
					<div class="row">
						<div class="col-md-8 col-xs-8"><strong>Internet Handling Fees</strong></div>
						<div class="col-md-4 col-xs-4 right"><i class="fa fa-inr"></i> 100</div>			
					</div>
					<div class="row">
						<div class="col-md-8 col-xs-8"><strong>Total Fees</strong></div>
						<div class="col-md-4 col-xs-4 right"><i class="fa fa-inr"></i> <?php echo $monthlyfees+100; ?></div>			
					</div>
					<br><hr><br><br>															
				</div>	
				<div class="row verifyrow">
					<div class="col-xs-12 col-md-12 col-lg-12 col-sm-12">
						<button id="verify">Verify Me</button> 
					</div>
				</div>
				<div class="row verifyformrow hide">
					<div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
						<input type="text" maxlength="4" placeholder="Enter Code" name="verifycode" id="verifycode"> 
					</div>				
					<div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
						<button id="verifybtn">Verify</button> 
					</div>
				</div>
				<div class="row hide finalrow">
					<div class="col-xs-12 col-md-12 col-lg-12 col-sm-12">
						<button id="finalbtn">Continue</button> 
					</div>
				</div>															
				<div class="row hide submitrow">
					<div class="col-xs-12 col-md-12 col-lg-12 col-sm-12">
						<i class="ion-ios-locked-outline lockicon"></i>
						<input type="submit" name="submit" id="submit" value="PAY SECURELY WITH PayU"> 
					</div>
				</div>	
			</form>		  			  		  			  			  		
		</div>
	</div>
</div>	
<div class="skmfix container">
	<div class="skmmenu">
		<ul>
			<li class="skmlink active"><a href="#skmlink-1" class="skmmenulink">Overview</a></li>
			<li class="skmlink"><a href="#skmlink-2" class="skmmenulink">Schedule</a></li>			
			<li class="skmlink"><a href="#skmlink-3" class="skmmenulink">Location</a></li>
			<li class="skmlink"><a href="#skmlink-4" class="skmmenulink">Gallery</a></li>
			<li class="skmlink"><a href="#skmlink-5" class="skmmenulink">Reviews</a></li>
		</ul>
	</div>

	<div class="skmclassinfo" id="skmlink-1">
		<div class="content-single-job_listing-hero-wrapper cover-wrapper container">

			<div class="content-single-job_listing-hero-inner row">

				<div class="content-single-job_listing-hero-company col-md-12 col-sm-12 col-xs-12 col-lg-12">
					<?php do_action( 'listify_single_job_listing_meta' ); ?>
				</div>

				<div class="content-single-job_listing-hero-actions col-md-12 col-sm-12 col-xs-12 col-lg-12">
					<?php do_action( 'listify_single_job_listing_actions' ); ?>
				</div>

				<div class="content-single-job_listing-hero-class-type col-md-12 col-sm-12 col-xs-12 col-lg-12">
					<?php echo get_post_meta(get_the_ID(),'_job_classtype',true); ?> <small data-classtype="<?php echo get_post_meta(get_the_ID(),'_job_classtype',true); ?>">(What does this mean?)</small>
					<span id="regulartext" class="classtypemean hide">Classes that take place every week like French or Maths for middle school.</span>
					<span id="coursetext" class="classtypemean hide">One-time hobby class like Baking and Painting for a few hours on a given day.</span>
					<span id="batchtext" class="classtypemean hide">More that one, but limited classes in a batch such as Meditation or Tennis(basic).</span>
				</div>				

			</div>

		</div>
	</div>	

	<div class="single_job_listing" itemscope itemtype="http://schema.org/LocalBusiness" <?php echo apply_filters(
	'listify_job_listing_data', '', false ); ?>>

	<div <?php echo apply_filters( 'listify_cover', 'listing-cover content-single-job_listing-hero', array( 'size' => 'full' ) ); ?>>
		<!--<div class="job-background-filter"></div> -->
	</div>

	<div id="primary">
		<div class="row content-area">

			<?php if ( listify_has_integration( 'woocommerce' ) ) : ?>
				<?php wc_print_notices(); ?>
			<?php endif; ?>

			<main id="main" class="site-main col-md-8 col-sm-7 col-xs-12" role="main">

				<?php do_action( 'single_job_listing_start' ); ?>

				<?php
				if ( ! dynamic_sidebar( 'single-job_listing-widget-area' ) ) {
					$defaults = array(
						'before_widget' => '<aside class="widget widget-job_listing">',
						'after_widget'  => '</aside>',
						'before_title'  => '<h1 class="widget-title widget-title-job_listing %s">',
						'after_title'   => '</h1>',
						'widget_id'     => ''
						);

					the_widget(
						'Listify_Widget_Listing_Map',
						array(
							'title' => __( 'Listing Location', 'listify' ),
							'icon'  => 'compass',
							'map'   => 1,
							'address' => 1,
							'phone' => 1,
							'web' => 1
							),
						wp_parse_args( array( 'before_widget' => '<aside class="widget widget-job_listing listify_widget_panel_listing_map">' ), $defaults )
						);

					the_widget(
						'Listify_Widget_Listing_Video',
						array(
							'title' => __( 'Video', 'listify' ),
							'icon'  => 'ios-film-outline',
							),
						wp_parse_args( array( 'before_widget' => '<aside class="widget widget-job_listing
							listify_widget_panel_listing_video">' ), $defaults )
						);

					the_widget(
						'Listify_Widget_Listing_Content',
						array(
							'title' => __( 'Listing Description', 'listify' ),
							'icon'  => 'clipboard'
							),
						wp_parse_args( array( 'before_widget' => '<aside class="widget widget-job_listing listify_widget_panel_listing_content">' ), $defaults )
						);

					the_widget(
						'Listify_Widget_Listing_Comments',
						array(
							'title' => ''
							),
						$defaults
						);
				}
				?>
				<?php get_template_part('skmreviews'); ?>

				<?php do_action( 'single_job_listing_end' ); ?>

			</main>

			<?php get_sidebar( 'single-job_listing' ); ?>

		</div>
	</div>
</div>
</div>
<?php
function print_my_inline_script() {
	$tutorname = get_user_by('id', get_the_author_meta( 'ID' ))->display_name;
	if ( wp_script_is( 'jquery', 'done' ) ) {	?>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.pin/1.0.1/jquery.pin.min.js"></script>
	<script>
		function getUrlParameterinit(sParam) {
			var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

			for (i = 0; i < sURLVariables.length; i++) {
				sParameterName = sURLVariables[i].split('=');

				if (sParameterName[0] === sParam) {
					return sParameterName[1] === undefined ? true : sParameterName[1];
				}
			}
		};
		if(getUrlParameterinit('bookthis') != undefined || getUrlParameterinit('already_payu_complete') != undefined ||	getUrlParameterinit('payu_complete') != undefined || getUrlParameterinit('wait_for_tutor') != undefined){
			jQuery('body').css({"overflow":"hidden"});
			jQuery(".book-form").addClass('hide');
			jQuery("#skm-book").removeClass('hide');

		}		

		jQuery(window).load(function() {
			verhash = '';
			jQuery("#verify").click(function(e){
				e.preventDefault();
				var remob = /^[789]\d{9}$/i;
				var bookmobile1 = jQuery("#bookmobile").val();					
				if(!remob.test(jQuery("#bookmobile").val())){
					jQuery("#bookmobile").css({"color":"#f00"}).parents('.skm-form-control').css({"border":" 1px solid #f00"}).find('i').css({"color":"#f00"});
					jQuery("#bookmobile").val('Please enter correct mobile number.');					
					setTimeout(function(){
						jQuery("#bookmobile").parents('.skm-form-control').css({"border":"1px solid #ccc"}).find('i').css({"color":"#717a8f"});
						jQuery("#bookmobile").val(bookmobile1).css({"color":"#717a8f"});
					}, 2000);
				} else {
					jQuery.ajax({																
						url: '<?php echo get_template_directory_uri();?>/generalverify.php',
						type: 'POST',
						contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						dataType: "text",
						data: {'mobile': bookmobile1},
						error: function(){
							jQuery('#verify').attr('disabled', true).text('Sending Failed');
							setTimeout(function(){
								jQuery('#verify').attr('disabled', false).text('Send Verification Code');
							}, 2000);
						},
						success: function(resdata) {
							if(resdata.indexOf("ERR:") == -1){
								jQuery('#verify').attr('disabled', true).text('New Code Sent');
								jQuery('#bookmobile').attr('disabled', true);
								verhash = resdata;
								jQuery(".verifyformrow").removeClass('hide');
								jQuery(".verifyrow").addClass('hide');
							} else {
								jQuery('#verify').attr('disabled', true).text('Sending Failed');
								setTimeout(function(){
									jQuery('#verify').attr('disabled', false).text('Send Verification Code');
								}, 2000);
							}
						},
						cache: false
					});
				}
			return false;
		});
		jQuery('#verifybtn').click(function(e){
			e.preventDefault();
			if(jQuery('#verifycode').val() == verhash){
				jQuery('#verifybtn').text('Verified!');
				setTimeout(function(){
					jQuery('.verifyformrow').addClass('hide');
					jQuery('.finalrow').removeClass('hide');
				}, 2000);					
			} else{
				jQuery('#verifybtn').text('Failed!');
				setTimeout(function(){
					jQuery('#verifybtn').text('Verify');
				}, 2000);									
			}
		})

		var scroll = 0;
		function closeSkmModal(){
			jQuery('body').css({"overflow":"auto"});
			jQuery("html, body").animate({
				scrollTop: scroll
			}, 0);			     	
			if(!jQuery("#skm-book").hasClass('hide')){
				jQuery("#skm-book").addClass('hide');
			}				
		}
		function openSkmModal(){
			jQuery('body').css({"overflow":"hidden"});
			scroll = jQuery(window).scrollTop();
			jQuery("html, body").animate({
				scrollTop: 0
			}, 0);
			if(jQuery("#skm-book").hasClass('hide')){
				jQuery("#skm-book").removeClass('hide');
			}
			jQuery('#booklocation').geocomplete();				
		}
		function getUrlParameter(sParam) {
			var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

			for (i = 0; i < sURLVariables.length; i++) {
				sParameterName = sURLVariables[i].split('=');

				if (sParameterName[0] === sParam) {
					return sParameterName[1] === undefined ? true : sParameterName[1];
				}
			}
		};
		if(getUrlParameter('bookthis') != undefined && getUrlParameter('bookthis') == 'true'){
			openSkmModal();
			jQuery(".book-form").removeClass('hide');		
		}			
		if(getUrlParameter('already_payu_complete') != undefined && getUrlParameter('already_payu_complete') == 'true'){
			openSkmModal();
			jQuery('.book-form strong').text('KABOOM!').css({"display":"block","text-align":"center"});
			jQuery('.book-form .row').text('');
			jQuery('.bookleft').text("CLASS BOOKED");
			jQuery('.bookreq').text("Class Booked");
			jQuery('.bookreq,.bookleft,.bookright').css({"pointer-events":"none"});
			jQuery('.skm-form-control,.skmbtn').each(function(){
				jQuery(this).addClass('hide');
			});
			jQuery('.book-form').find('.row').append("<div class=\"success\"><div>You have already booked this class.</div><div>Check your email for your class pass.</div><button id=\"lookup\">KEEP LOOKING</button></div>");
			jQuery('.book-form').css({"height":"220px"});
			jQuery(".book-form").removeClass('hide');
			jQuery('.book-form').find('.row').on('click', '#lookup', function(e) {
				e.preventDefault();
				closeSkmModal();
			});				
		}
		if(getUrlParameter('wait_for_tutor') != undefined && getUrlParameter('wait_for_tutor') == 'true'){
			openSkmModal();
			jQuery('.book-form strong').text('WAIT!').css({"display":"block","text-align":"center"});
			jQuery('.book-form .row').text('');
			jQuery('.bookleft').text("CLASS BOOKED");
			jQuery('.bookreq').text("Class Booked");
			jQuery('.bookreq,.bookleft,.bookright').css({"pointer-events":"none"});
			jQuery('.skm-form-control,.skmbtn').each(function(){
				jQuery(this).addClass('hide');
			});
			jQuery('.book-form').find('.row').append("<div class=\"success\"><div>You have already booked this class.</div><div> Tutors usually take 2-3 days to respond.</div><button id=\"lookup\">KEEP LOOKING</button></div>");
			jQuery('.book-form').css({"height":"220px"});
			jQuery(".book-form").removeClass('hide');
			jQuery('.book-form').find('.row').on('click', '#lookup', function(e) {
				e.preventDefault();
				closeSkmModal();
			});				
		}						
		if(getUrlParameter('payu_complete') != undefined && getUrlParameter('payu_complete') == 'true'){
			openSkmModal();
			jQuery('.book-form strong').text('KABOOM!').css({"display":"block","text-align":"center"});
			jQuery('.book-form .row').text('');
			jQuery('.bookleft').text("CLASS BOOKED");
			jQuery('.bookreq').text("Class Booked");
			jQuery('.bookreq,.bookleft,.bookright').css({"pointer-events":"none"});
			jQuery('.skm-form-control,.skmbtn').each(function(){
				jQuery(this).addClass('hide');
			});
			jQuery('.book-form').find('.row').append("<div class=\"success\"><div>Your request has been sent to <?php echo $tutorname; ?>.</div><div> Tutors usually take 2-3 days to respond.</div><button id=\"lookup\">KEEP LOOKING</button></div>");
			jQuery('.book-form').css({"height":"220px"});
			jQuery(".book-form").removeClass('hide');
			jQuery('.book-form').find('.row').on('click', '#lookup', function(e) {
				e.preventDefault();
				closeSkmModal();
			});				
		} else if(getUrlParameter('payu_complete') != undefined && getUrlParameter('payu_complete') == 'false'){
			openSkmModal();
			jQuery('.book-form strong').text('Sorry!').css({"display":"block","text-align":"center"});
			jQuery('.book-form .row').text('');
			jQuery('.skm-form-control,.skmbtn').each(function(){
				jQuery(this).addClass('hide');
			});
			jQuery('.book-form').find('.row').append("<div class=\"success\"><div>Your transaction was not successful.</div><div> Please try to book again.</div><button id=\"lookup\">KEEP LOOKING</button></div>");
			jQuery('.book-form').css({"height":"220px"});
			jQuery(".book-form").removeClass('hide');
			jQuery('.book-form .skmclosebtn').click(function(e) {
				e.preventDefault();
				var url = location.protocol + '//' + location.host + location.pathname;
				window.location = url;
			});						
			jQuery('.book-form').find('.row').on('click', '#lookup', function(e) {
				e.preventDefault();
				var url = location.protocol + '//' + location.host + location.pathname;
				window.location = url;
			});						
		}

		jQuery('.bookleft, .bookright,.bookreq').click(function(){
			openSkmModal();
		});
		jQuery('#skm-book').dblclick(function(){
			closeSkmModal();
		})
		jQuery('.skmclosebtn').click(function(){
			closeSkmModal();
		})
		jQuery(document).keyup(function(e) {
			if (e.keyCode == 27) {
				closeSkmModal();
			}
		});
		jQuery('.skmbtn').click(function(){
			var this1 = jQuery(this);
			jQuery(this).parent('.skmrow').find('.skmbtn').each(function(){
				if(jQuery(this).hasClass('active')){
					jQuery(this).removeClass('active');
				}
			})
			this1.addClass('active');
		})
		jQuery('.skmgender').click(function(){
			jQuery('#bookgender').val(jQuery(this).data('value'));
		});
		jQuery('.skmtype').click(function(){
			jQuery('#booktype').val(jQuery(this).data('value'));
		});	
		jQuery('.skm-form-control').click(function(){
			jQuery(this).find('input').focus();
		});
		jQuery(function() {
			jQuery('')
		})
		jQuery(function() {
			jQuery("#finalbtn").click(function(e){
				e.preventDefault();
				var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				var re1 = /^[789]\d{9}$/i;
				var bookname = jQuery("#bookname").val();
				var bookemail = jQuery("#bookemail").val();
				var bookmobile = jQuery("#bookmobile").val();	
				var booklocation = jQuery("#booklocation").val();
				var bookage = jQuery("#bookage").val();
				var flag = true;
				if(bookname==''){
					jQuery("#bookname").css({"color":"#f00"}).parents('.skm-form-control').css({"border":" 1px solid #f00"}).find('i').css({"color":"#f00"});
					jQuery("#bookname").val('Please enter your name.');					
					setTimeout(function(){
						jQuery("#bookname").parents('.skm-form-control').css({"border":"1px solid #ccc"}).find('i').css({"color":"#717a8f"});
						jQuery("#bookname").val('').css({"color":"#717a8f"});
					}, 2000);
					flag = false;
				}					
				if(!re1.test(bookmobile)){
					jQuery("#bookmobile").css({"color":"#f00"}).parents('.skm-form-control').css({"border":" 1px solid #f00"}).find('i').css({"color":"#f00"});
					jQuery("#bookmobile").val('Please enter correct mobile number.');					
					setTimeout(function(){
						jQuery("#bookmobile").parents('.skm-form-control').css({"border":"1px solid #ccc"}).find('i').css({"color":"#717a8f"});
						jQuery("#bookmobile").val(bookmobile).css({"color":"#717a8f"});
					}, 2000);
					flag = false;
				}								
				if(!re.test(bookemail)){
					jQuery("#bookemail").css({"color":"#f00"}).parents('.skm-form-control').css({"border":" 1px solid #f00"}).find('i').css({"color":"#f00"});
					jQuery("#bookemail").val('Please enter correct email.');					
					setTimeout(function(){
						jQuery("#bookemail").parents('.skm-form-control').css({"border":"1px solid #ccc"}).find('i').css({"color":"#717a8f"});
						jQuery("#bookemail").val(bookemail).css({"color":"#717a8f"});
					}, 2000);
					flag = false;
				}
				if(booklocation==''){
					jQuery("#booklocation").css({"color":"#f00"}).parents('.skm-form-control').css({"border":" 1px solid #f00"}).find('i').css({"color":"#f00"});
					jQuery("#booklocation").val('Please enter your location.');					
					setTimeout(function(){
						jQuery("#booklocation").parents('.skm-form-control').css({"border":"1px solid #ccc"}).find('i').css({"color":"#717a8f"});
						jQuery("#booklocation").val('').css({"color":"#717a8f"});
					}, 2000);
					flag = false;
				}
				if(isNaN(bookage) || bookage < 1 || bookage > 200){
					jQuery("#bookage").css({"color":"#f00"}).parents('.skm-form-control').css({"border":" 1px solid #f00"}).find('i').css({"color":"#f00"});
					jQuery("#bookage").val('Please enter correct age.');					
					setTimeout(function(){
						jQuery("#bookage").parents('.skm-form-control').css({"border":"1px solid #ccc"}).find('i').css({"color":"#717a8f"});
						jQuery("#bookage").val('').css({"color":"#717a8f"});
					}, 2000);
					flag = false;
				}
				if(flag){
					var plural = (jQuery('#bookage').val() == 1 ? ' year old':' years old');
					jQuery('#bookmobile').attr('disabled', false);
					jQuery('.formdivs').addClass('hide');
					jQuery('.finalname').text(jQuery('#bookname').val());
					jQuery('.finalemail').text(jQuery('#bookemail').val());
					jQuery('.finalmobile').text(jQuery('#bookmobile').val());
					jQuery('.finallocation').text(jQuery('#booklocation').val());
					jQuery('.finalgender').text(jQuery('#bookgender').val());
					jQuery('.finalage').text(jQuery('#bookage').val() + plural);
					jQuery('.finaldetail').removeClass('hide');
					jQuery('.finalrow').addClass('hide');
					jQuery('.submitrow').removeClass('hide');
				}
			});
		});			
	});
	jQuery('small').hover(function(){
		if(jQuery("#" + jQuery(this).data('classtype') + "text").hasClass('hide')){
			jQuery("#" + jQuery(this).data('classtype') + "text").removeClass('hide');
		}
	}, function(){
		if(!jQuery("#" + jQuery(this).data('classtype') + "text").hasClass('hide')){
			jQuery("#" + jQuery(this).data('classtype') + "text").addClass('hide');
		}		
	})
	jQuery('.bookleft,.bookright').click(function(){
		mixpanel.track('class_btc');
	})
	var asidectr = 2;
	jQuery('aside').not('aside:first').each(function(){
		jQuery(this).wrap( "<div id='skmlink-" + asidectr + "' ></div>" );
		asidectr++;
	})
	jQuery('.skmmenulink').click(function(){
		jQuery('.skmmenulink').each(function(){
			if(jQuery(this).parent('li').hasClass('active')){
				jQuery(this).parent('li').removeClass('active');
			}
		});
		if(!jQuery(this).parent('li').hasClass('active')){
			jQuery(this).parent('li').addClass('active');
		}
	});
	jQuery('.skmmenulink').hover(function(){
		if(!jQuery(this).parent('li').hasClass('hover')){
			jQuery(this).parent('li').addClass('hover');
		}
	}, function(){
		if(jQuery(this).parent('li').hasClass('hover')){
			jQuery(this).parent('li').removeClass('hover');
		}		
	})
	if(jQuery(window).width() > 970) {
		jQuery(".skmmenu").css({"width": jQuery('.container').innerWidth() - 30 + "px"});
	} else {
		jQuery(".skmmenu").attr('style','');
	}
	setInterval(function(){
		if(jQuery(window).width() > 970){
			jQuery(".skmmenu").css({"width": jQuery('.container').innerWidth() - 30 + "px"});
		} else {
			jQuery(".skmmenu").attr('style','');
		}
	}, 100);
	jQuery(function() {
		jQuery('a[href*=#]:not([href=#])').not('.popup-trigger').click(function() {
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
				var target = jQuery(this.hash);
				target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
				if (target.length) {
					jQuery('html,body').animate({
						scrollTop: target.offset().top - 200
					}, 500);
					return false;
				}
			}
		});
	});		
</script>
<?php
}
}

add_action( 'wp_footer', 'print_my_inline_script' );