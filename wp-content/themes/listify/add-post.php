<?php

/**
* Template Name: newclass
*
* @package Listify
*/

if(!is_user_logged_in()){
	header("location: ".get_site_url()."/my-account/");
	die();
}

$user = wp_get_current_user();
$user_id = $user->ID;

$args = apply_filters( 'job_manager_get_dashboard_jobs_args', array(
	'post_type'           => 'job_listing',
	'post_status'         => array( 'publish', 'expired', 'pending' ),
	'ignore_sticky_posts' => 1,
	'posts_per_page'      => 10,
	'orderby'             => 'date',
	'order'               => 'desc',
	'author'              => $user_id
	) );

$jobs = new WP_Query;
$jobs->query( $args );
$listid = 0;
while ( $jobs->have_posts() ) {
	$jobs->the_post();
	if(get_post_meta((int)get_the_ID(),'_mobile_num',true)!='' && $listid == 0){
		$listid = get_the_ID();
		break;
	}
}

$address = get_post_meta($dummyid,'_job_location', true);
if($address == ''){
	$address = get_cimyFieldValue($user_id,'LOCATION');
}
$name = get_post_meta($dummyid,'tutor_name', true);
if($name == ''){
	$name = $user->display_name;
}
$pincode = get_post_meta($dummyid,'_company_website', true);

$mobile = get_cimyFieldValue($user_id,'MOBILE');
if($mobile == ''){
	$mobile = get_post_meta($dummyid,'_mobile_num',true);
}

$yoe = get_post_meta($dummyid,'_tutor_exp',true);
if($yoe == ''){
	$yoe = get_cimyFieldValue($user_id,'YOE');
}

$email = $user->user_email;

get_header();

$parentargs = array(
	'orderby'           => 'name', 
	'order'             => 'ASC',
	'hide_empty'        => false,
	'parent'            => 0,    
	); 

$parentterms = get_terms('job_listing_category', $parentargs);


?>
<link rel="stylesheet" href="<?php echo get_site_url(); ?>/wp-content/themes/listify/css/timepicker.css">
<style type="text/css">
	.ui-datepicker .ui-datepicker-prev.ui-state-hover {
		background: #fff !important;
	    background-image: url("<?php echo get_site_url(); ?>/wp-content/themes/listify/images/icon_arrow_left_black.png") !important;
		background-position: 3px 3px !important;
		border: 0 !important;
	}
	.ui-datepicker .ui-datepicker-next.ui-state-hover {
		background: #fff !important;
	    background-image: url("<?php echo get_site_url(); ?>/wp-content/themes/listify/images/icon_arrow_right_black.png") !important;
	    background-position: 1px 3px !important;
	    border: 0 !important;
	}	
	small {
		font-size: 14px;
	}
	body .chosen-container-single .chosen-single div:before {
		color: #3396d1;
		font-weight: bold;
		margin-left: -15px;
	}
	.job-manager-category-dropdown-wrapper:after{
		display: none;
	}
	.blue-h2{
		margin: 20px 0 20px 0 !important;
		color: #3396d1;
		font-size: 22px !important;
		border:0 !important;
	}
	@media(min-width: 992px){
		.text-right{
			text-align: right !important;
		}
		.radio-row{
			margin-bottom: 20px !important;
		}
		.row.jobhrsrow {
			margin-bottom: 20px !important;
		}
		.linefix{
			margin-left: -20px
		}		
	}
	@media(max-width: 991px){	
		.job-btn{
			margin: 0 0 10px !important;
			max-width: 200px;
		}
		.timehead{
			display: none;
		}
		.h3-right{
			display: none;
		}
	}
	label{
		line-height: 45px;
		font-size: 14.4px;
		cursor: default;
	}
	.radio-row{
		margin-bottom: 0;
	}
	.row.jobhrsrow {
		margin-bottom: 0;
	}
	.uicont{
		font-size: 18px !important;
	}	
	.job-btn-session,.job-btn {
		background: #fff;
		padding: 5px 10px;
		color: #999;
		border: 2px solid;
		border-color: #999; 
		border-radius: 5px !Important;
		margin: 0;
		cursor: pointer;
		font-size: 14px;
	}
	.jobhrsborder{
		padding: 5px;
	}	
	.greenicon{
		font-size: 30px !important;
		line-height: 45px !important;
		color: #72bb46;
	}
	.hide{
		display: none !important;
	}
	.compslider{
		height: 20px;
		border: 1px solid #ccc;
		margin: 10px 0;
	}
	.greenbar{
		height: 18px;
		background: #72bb46;
	}
	.formnotdone{
		border-bottom: 1px solid #9e875c !important;
		background: #faf6e3 !important;
		color: #9e875c !important;	
	}	
	#accordion{
		background: rgb(240, 243, 246);
	}
	.ui-accordion .ui-accordion-header {
		padding: 15px;
		min-height: 20px;
		font-size: 20px;
		font-weight: 500;
		margin:0;
		border-radius: 0;
	}
	.ui-state-disabled,.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
		border-bottom: 1px solid #9e875c !important;
		background: #faf6e3;
		border: 0;
		color: #9e875c;
		opacity: 1;
		cursor: pointer;
	}	
	.formdone{
		border-bottom: 1px solid #236992 !important;
		background: #3396d1 !important;
		color: #fff !important;
	}    
	.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active{
		background: #05abf2 !important;
		border-bottom: 1px solid #3396d1 !important;
		border: 0;
		color: #fff !important;
		cursor: pointer;
	}
	.head1 {
	    border-top-left-radius: 4px !important;
	    border-top-right-radius: 4px !important;
	}
	.head4 {
	    border-bottom-left-radius: 4px !important;
	    border-bottom-right-radius: 4px !important;
	}	
	#accordion input, #accordion textarea {
		background: #fff;
	} 
	#accordion span.h3-right, #accordion span.h3-check {
		font-size: 16px;
		float: right;
		line-height: 20px;
	}
	.ui-state-disabled .ui-icon,.ui-state-active .ui-icon, .ui-state-default .ui-icon, .ui-state-focus .ui-icon, .ui-state-hover .ui-icon {
		background-image: none !important;
	}
	.ui-accordion .ui-accordion-content {
		overflow: auto;
		overflow: visible;
	}	
	.select{
		width: 100%;
	}
	.select:after{
		display: none;
	}
	select,a.chosen-single {
		background: #fff !important;
		position: relative;
		z-index: 10;
	}
	.classtypebox{
		border: 1px solid #ebeef1;
		color: #717a8f;
		background: #fff;
		cursor: pointer;
		padding: 10px;
		font-size: 14px;
		border-radius: 4px;		
	}
	.classtypebox.active{
		border: 1px solid #9e875c !important;
		color: #9e875c !important;
		background: #faf6e3 !important;	
	}
	.classtypebox .head{
		font-size: 16px;
		margin: 10px 0;
		display: block;
		font-weight: bold;
	}
	.col-xs-offset-right-1 {
		padding: 0 20px 0 10px;
	}
	.skmremovelink{
		font-size: 20px;
		color: #717a8f;
		cursor: pointer;
	}	
	.duration{
		font-style:italic;
	}
	.review-title{
		font-size: 16px;
		margin: 10px 0;
	}
	.review-location, .review-details{
		margin: 10px 0;
	}
    .jobhrsrow, label, body .chosen-container-single .chosen-single span, .ui-widget button, .ui-widget input, .ui-widget select, .ui-widget textarea {
    	font-family: Montserrat, sans-serif;
    }
    .head4.ui-state-disabled, .head4.ui-state-default {
    	border-bottom: 1px solid #faf6e3 !important;
	}
	table{
		border-width: 0;
	}
	#ui-timepicker-div,#ui-datepicker-div {
	    color: #3396d1 !important;
	    z-index: 100 !important;
	    background:#fff !important;
	}
	.ui-datepicker .ui-datepicker-title {
		color: #3396d1 !important;
	}	
	.ui-datepicker td a.ui-state-default {
	    border-bottom: 0 !important;
	    color: #262626;
	    text-align: center;
	}
	.ui-datepicker .ui-state-disabled {
	    visibility: hidden;
	    border: 0 !important;
	}	
	.ui-datepicker-prev span {
	    background-image: url("<?php echo get_site_url(); ?>/wp-content/themes/listify/images/icon_arrow_left_black.png") !important;
	        background-position: -3px -3px !important;
	}

	.ui-datepicker-next span {
	    background-image: url("<?php echo get_site_url(); ?>/wp-content/themes/listify/images/icon_arrow_right_black.png") !important;
	        background-position: -3px -3px !important;
	}
	.classicon {
	    position: absolute;
	    right: 30px;
	    top: 9.5px;
	    font-size: 24px !important;
	    color: #3396d1;
	    pointer-events: none;
    }	
</style>
<div id="content" class="site-content">
	<div id="primary" class="container">
		<div class="content-area">
			<main id="main" class="site-main" role="main">
				<article>
					<div class="content-box-inner">
						<div class="entry-content">
							<aside class="widget">
								<div class="row">
									<div class="col-md-8 col-xs-12 col-sm-8 col-lg-8">
										<h2 class="blue-h2"> Create a New Class</h2>
									</div>
									<div class="col-md-4 col-xs-12 col-sm-4 col-lg-4">
										<div class="compslider">
											<div class="greenbar" style="width: 30%"></div>
										</div>
									</div>						
								</div>	
								<form novalidate action="<?php echo get_template_directory_uri(); ?>/addclass.php" method="post" id="submit-job-form" class="job-manager-form" enctype="multipart/form-data">									<div id="tabs">
									<div id="message" class="danger"></div>
									<div id="accordion">
										<h3 class="head1">1. General <span class="h3-right">What would you like to teach?</span><span class="fa fa-check h3-check hide"></span></h3>
										<div class="form1">											
											<input type="hidden" name="tutor_name" id="tutor_name" value="<?php echo $name; ?>" >							
											<input type="hidden" name="application" id="application" value="<?php echo $email; ?>" >							
											<input type="hidden" name="mobile_num" id="mobile_num" value="<?php echo $mobile; ?>">							
											<fieldset class="fieldset-job_title">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_title">Title</label></div>
												<div class="field col-md-6 col-xs-12 col-md-6 col-xs-12 required-field">
													<input type="text" class="input-text" name="job_title" id="job_title" tabindex="1" placeholder="Your profile headline" value="" maxlength="" required="">
												</div>
												<div class="col-md-3 col-xs-12">
													<small class="description">E.g. 'Weekend Meditation Classes for Women', 'Karate Klasses for Kids'</small>
												</div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>
											<fieldset class="fieldset-job_description">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_description">Description</label></div>
												<div class="field col-md-6 col-xs-12 required-field">
													<textarea cols="20" rows="3" class="input-text" name="job_description" tabindex="2" id="job_description" placeholder="Start typing..." maxlength="" required=""></textarea>
												</div>
												<div class="col-md-3 col-xs-12">
													<small class="description">Use this space to talk about your experience, class content, the type of students you like to tutor, whether you're a hobbyist or a professional.</small>
												</div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>													
											</fieldset>											
											<fieldset class="fieldset-job_category">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_category">Category</label></div>
												<div class="field col-md-3 col-xs-12 required-field">
													<select name='job_category_main[]' id='job_category_main' class='job-manager-category-dropdown' tabindex="3" data-placeholder='Choose a category&hellip;'>
														<option class="level-0" value="0">Choose a category&hellip;</option>
														<?php foreach ($parentterms as $term) { ?>
														<option class="level-0" value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
														<?php } ?>														
													</select>
												</div>
												<div class="field col-md-3 col-xs-12 required-field">
													<?php foreach ($parentterms as $term) { ?>
													<div class="hide catwrap <?php echo "catwrap".$term->term_id; ?>">
														<select name='job_category[]' id='job_category' class='subcat<?php echo $term->term_id; ?> job-manager-category-dropdown' data-placeholder='Choose a sub-category&hellip;'>
															<option class="level-0" value="0">Choose a sub-category&hellip;</option>
															<?php $childargs = array(
																'orderby'           => 'name', 
																'order'             => 'ASC',
																'hide_empty'        => false,
																'parent'            => $term->term_id,    
																);
															$childterms = get_terms('job_listing_category', $childargs);
															foreach ($childterms as $childterm) {?>
															<option class="level-0" value="<?php echo $childterm->term_id; ?>"><?php echo $childterm->name; ?></option>
															<?php }
															?>
														</select>	
													</div>
													<?php } ?>													
												</div>
												<div class="col-md-3 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>
											<fieldset class="fieldset-job_classtype">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_classtype">Type</label></div>
												<div class="field col-md-9 col-xs-12 required-field">
													<input type="hidden" name="job_classtype" id="job_classtype" value="regular">													
													<div class="col-sm-4 col-xs-12 col-md-4 col-lg-4 col-xs-offset-right-1" style="padding-left: 0;">
														<div class="classtypebox active" data-type='regular'>
															<div class="text-center head">Regular</div>
															Classes that take place every week like French or Maths for middle school.
														</div>
													</div>
													<div class="col-sm-4 col-xs-12 col-md-4 col-lg-4 col-xs-offset-right-1" style="padding-right: 10px;">														<div class="classtypebox" data-type='course'>
														<div class="text-center head">Course</div>
														One-time hobby class like Baking and Painting for a few hours on a given day.
													</div>
												</div>
												<div class="col-sm-4 col-xs-12 col-md-4 col-lg-4 col-lg-4" style="padding-left: 20px;">
													<div class="classtypebox" data-type='batch'>
														<div class="text-center head">Batch</div>
														More that one, but limited classes in a batch such as Meditation or Tennis(basic).
													</div>
												</div>
											</div>
											<div class="col-md-1 col-xs-12">
												<span class="fa fa-check-circle greenicon"></span>
											</div>													
										</fieldset>											
										<div class="row text-center">
											<button class='next uicont'>Continue</button>
										</div>
									</div>
									<h3 class="head2 ui-state-disabled">2. Schedule and Charges <span class="h3-right">When would you take classes?</span><span class="fa fa-check h3-check hide"></span></h3>	
									<div class="form2">
										<div class="regular-type">
											<fieldset class="fieldset-new-job_hours" style="position: relative">
												<div class="col-md-2 col-xs-12 text-right">
													<label>Timings</label>
													<div class="jobhrsborder" style="font-size: 12px !important" tabindex='5'><div>(You need to select</div><div> at least one time slot)</div></div>
												</div>
												<div class="field col-md-10 col-xs-12">
													<input type="hidden" name="newjobhrs" id="newjobhrs">
													<div class="row jobhrsrow timehead">
														<div class="field col-md-2 col-xs-12"></div>
														<div class="field col-md-2 col-xs-12 text-center">Morning</div>
														<div class="field col-md-2 col-xs-12 text-center">Afternoon</div>
														<div class="field col-md-2 col-xs-12 text-center">Evening</div>
														<div class="field col-md-2 col-xs-12 text-center">Night</div>
													</div>												
													<div class="row jobhrsrow">
														<div class="field col-md-2 col-xs-12">
															Monday
														</div>
														<div class="field col-md-2 col-xs-12"><div id="11btn" data-slot='8-12'  class="job-btn text-center">8am - 12pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="12btn" data-slot='12-16' class="job-btn text-center">12pm - 4pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="13btn" data-slot='16-20' class="job-btn text-center">4pm - 8pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="14btn" data-slot='20-22' class="job-btn text-center">8pm - 10pm</div></div>														
													</div>
													<div class="row jobhrsrow">
														<div class="field col-md-2 col-xs-12">
															Tuesday
														</div>
														<div class="field col-md-2 col-xs-12"><div id="21btn" data-slot='8-12'  class="job-btn text-center">8am - 12pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="22btn" data-slot='12-16' class="job-btn text-center">12pm - 4pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="23btn" data-slot='16-20' class="job-btn text-center">4pm - 8pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="24btn" data-slot='20-22' class="job-btn text-center">8pm - 10pm</div></div>															
													</div>	
													<div class="row jobhrsrow">
														<div class="field col-md-2 col-xs-12">
															Wednesday
														</div>
														<div class="field col-md-2 col-xs-12"><div id="31btn" data-slot='8-12'  class="job-btn text-center">8am - 12pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="32btn" data-slot='12-16' class="job-btn text-center">12pm - 4pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="33btn" data-slot='16-20' class="job-btn text-center">4pm - 8pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="34btn" data-slot='20-22' class="job-btn text-center">8pm - 10pm</div></div>															
													</div>
													<div class="row jobhrsrow">
														<div class="field col-md-2 col-xs-12">
															Thursday
														</div>
														<div class="field col-md-2 col-xs-12"><div id="41btn" data-slot='8-12'  class="job-btn text-center">8am - 12pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="42btn" data-slot='12-16' class="job-btn text-center">12pm - 4pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="43btn" data-slot='16-20' class="job-btn text-center">4pm - 8pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="44btn" data-slot='20-22' class="job-btn text-center">8pm - 10pm</div></div>															
													</div>
													<div class="row jobhrsrow">
														<div class="field col-md-2 col-xs-12">
															Friday
														</div>
														<div class="field col-md-2 col-xs-12"><div id="51btn" data-slot='8-12'  class="job-btn text-center">8am - 12pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="52btn" data-slot='12-16' class="job-btn text-center">12pm - 4pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="53btn" data-slot='16-20' class="job-btn text-center">4pm - 8pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="54btn" data-slot='20-22' class="job-btn text-center">8pm - 10pm</div></div>															
													</div>
													<div class="row jobhrsrow">
														<div class="field col-md-2 col-xs-12">
															Saturday
														</div>
														<div class="field col-md-2 col-xs-12"><div id="61btn" data-slot='8-12'  class="job-btn text-center">8am - 12pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="62btn" data-slot='12-16' class="job-btn text-center">12pm - 4pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="63btn" data-slot='16-20' class="job-btn text-center">4pm - 8pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="64btn" data-slot='20-22' class="job-btn text-center">8pm - 10pm</div></div>															
													</div>	
													<div class="row jobhrsrow">
														<div class="field col-md-2 col-xs-12">
															Sunday
														</div>
														<div class="field col-md-2 col-xs-12"><div id="71btn" data-slot='8-12' class="job-btn text-center">8am - 12pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="72btn" data-slot='12-16' class="job-btn text-center">12pm - 4pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="73btn" data-slot='16-20' class="job-btn text-center">4pm - 8pm</div></div>
														<div class="field col-md-2 col-xs-12"><div id="74btn" data-slot='20-22' class="job-btn text-center">8pm - 10pm</div></div>															
													</div>																																																														
												</div>
												<div class="col-md-3 col-xs-12"></div>
												<div class="col-md-1 col-xs-12" style="position: absolute; right: 0">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>																								
											</fieldset>
											<fieldset class="fieldset-job_monthly_classes">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_monthly_classes">Monthly classes</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<select name="job_monthly_classes" class="job-manager-category-dropdown" id="job_monthly_classes" required=""  placeholder="Monthly classes">
														<option value="">Monthly classes</option>
														<option value="1">1</option>
														<option value="2">2</option>
														<option value="3">3</option>
														<option value="4">4</option>
														<option value="5">5</option>
														<option value="6">6</option>
														<option value="7">7</option>
														<option value="8">8</option>
														<option value="9">9</option>
														<option value="10">10</option>
														<option value="11">11</option>
														<option value="12">12</option>
														<option value="13">13</option>
														<option value="14">14</option>
														<option value="15">15</option>											
													</select>													
												</div>
												<div class="col-md-3 col-xs-12"></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>												
											<fieldset class="fieldset-hourly_rate">
												<div class="col-md-2 col-xs-12 text-right"><label for="hourly_rate">Fees (in ₹)</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<input type="text" class="input-text" name="hourly_rate" id="hourly_rate" placeholder="" value="" maxlength="5" required="">
													<div class="fa fa-money classicon"></div>
												</div>
												<div class="col-md-3 col-xs-12"><small>How much do you wish to charge per class?</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>	
											<fieldset class="fieldset-job_monthly_fees">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_monthly_fees">Monthly fees (in ₹)</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<input type="text" class="input-text" name="job_monthly_fees" id="job_monthly_fees" placeholder="" value="" maxlength="5" required="">
													<div class="fa fa-money classicon"></div>
												</div>
												<div class="col-md-3 col-xs-12"><small>Your monthly fees for this course</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>	
											<fieldset class="fieldset-job_no_of_seats">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_no_of_seats">Number of seats</label></div>
												<div class="field col-md-4 col-xs-12 required-field">													
													<select name="job_no_of_seats" class="job-manager-category-dropdown" id="job_no_of_seats" required=""  placeholder="Number of seats in this course.">
														<option value="">Number of seats in this course.</option>
														<option value="1">1</option>
														<option value="2">2</option>
														<option value="3">3</option>
														<option value="4">4</option>
														<option value="5">5</option>
														<option value="6">6</option>
														<option value="7">7</option>
														<option value="8">8</option>
														<option value="9">9</option>
														<option value="10">10</option>
														<option value="11">11</option>
														<option value="12">12</option>
														<option value="13">13</option>
														<option value="14">14</option>
														<option value="15">15</option>											
													</select>													
												</div>
												<div class="col-md-3 col-xs-12"><small>How many students can attend this class? Select 1 for individual classes.</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>
										</div>
										<div class="course-type hide">
											<fieldset class="fieldset-job_date">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_date">Class date</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<input type="text" class="input-text" name="job_date" placeholder="" id="job_date" value="" required="">
													<div class="fa fa-calendar classicon"></div>
												</div>
												<div class="col-md-3 col-xs-12"><small>When will you take this class? (DD/MM/YYYY)</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>
											<fieldset class="fieldset-job_start_time">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_start_time">Start time</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<input type="text" class="input-text" name="job_start_time" placeholder="11:30 AM" id="job_start_time" value="" required="">
													<div class="fa fa-clock-o classicon"></div>
												</div>
												<div class="col-md-3 col-xs-12"><small>What time does your class start? (For example: 05:30 PM)</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>	
											<fieldset class="fieldset-job_end_time">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_end_time">End time</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<input type="text" class="input-text" name="job_end_time" placeholder="2:45 PM" id="job_end_time" value="" required="">
													<div class="fa fa-clock-o classicon"></div>
												</div>
												<div class="col-md-3 col-xs-12"><small>What time does your class end? (For example: 08:00 PM)</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>	
											<fieldset class="fieldset-job_duration">
												<div class="col-md-2 col-xs-12 text-right"></div>
												<div class="col-md-4 col-xs-12 duration text-right"></div>
												<div class="col-md-6 col-xs-12"></div>									
											</fieldset>	
											<fieldset class="fieldset-hourly_rate">
												<div class="col-md-2 col-xs-12 text-right"><label for="hourly_rate">Fees (in ₹)</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<input type="text" class="input-text" name="hourly_rate" id="hourly_rate" placeholder="" value="" maxlength="5" required="">
													<div class="fa fa-money classicon"></div>
												</div>
												<div class="col-md-3 col-xs-12"><small>How much do you wish to charge?</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>	
											<fieldset class="fieldset-job_no_of_seats">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_no_of_seats">Number of seats</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<select name="job_no_of_seats" class="job-manager-category-dropdown" id="job_no_of_seats" required=""  placeholder="Number of Seats in this course.">
														<option value="">Number of Seats in this course.</option>
														<option value="1">1</option>
														<option value="1">1</option>
														<option value="2">2</option>
														<option value="3">3</option>
														<option value="4">4</option>
														<option value="5">5</option>
														<option value="6">6</option>
														<option value="7">7</option>
														<option value="8">8</option>
														<option value="9">9</option>
														<option value="10">10</option>
														<option value="11">11</option>
														<option value="12">12</option>
														<option value="13">13</option>
														<option value="14">14</option>
														<option value="15">15</option>											
													</select>
												</div>
												<div class="col-md-3 col-xs-12"><small>How many students can attend this class? Select 1 for individual classes.</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>																																
										</div>
										<div class="batch-type hide">
											<fieldset class="fieldset-job_date">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_date">Starts on</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<input type="text" class="input-text" name="job_date" placeholder="" id="job_date1" value="" required="">
													<div class="fa fa-calendar classicon"></div>
												</div>
												<div class="col-md-3 col-xs-12"><small>When will this batch start? (DD/MM/YYYY)</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>
											<fieldset class="fieldset-job_date">
												<input type="hidden" value="" id="job_day_dump" name="job_day_dump">
												<input type="hidden" value="" id="job_time_dump" name="job_time_dump">
												<div class="row">
													<div class="col-md-12 col-xs-12">
														<div class="col-md-2 col-xs-12 text-right"><label for="job_day">Schedule</label></div>
														<span class="skmaddsource">
															<div class="field col-md-4 col-xs-12 required-field">
																<select name="job_day" class="job-manager-category-dropdown" id="job_day" required=""  placeholder="Number of Seats in this course.">
																	<option value="">Select a day.</option>
																	<option value="monday">Monday</option>
																	<option value="tuesday">Tuesday</option>
																	<option value="wednesday">Wednesday</option>
																	<option value="thursday">Thursday</option>
																	<option value="friday">Friday</option>
																	<option value="saturday">Saturday</option>
																	<option value="sunday">Sunday</option>											
																</select>
															</div>
															<div class="field col-md-3 col-xs-12 required-field">
																<input name="job_time" class="input-text" id="job_time" required="" placeholder="11:30 AM">
																<div style="right: 45px" class="fa fa-clock-o classicon"></div>
															</div>
														</span>
														<div class="col-md-2 col-xs-12"><small><a href="#" class="skmaddlink" style="line-height: 45px;color: #3396d1;font-weight:bold;position:relative;z-index:10;">Add more</a>&nbsp;</small></div>
														<div class="col-md-1 col-xs-12">
															<span class="fa fa-check-circle greenicon hide"></span>
														</div>
													</div>
												</div>
												<span class="skmaddtarget">
													<div class="row">
														<div class="col-xs-12 col-md-12">
															<div class="col-md-2"></div>
															<div class="skmaddtarget1"></div>
															<div class="col-md-2 col-xs-12"><small></small></div>
															<div class="col-md-1 col-xs-12"></div>
														</div>
													</div>
													<div class="row">
														<div class="col-xs-12 col-md-12">
															<div class="col-md-2"></div>
															<div class="skmaddtarget2"></div>
															<div class="col-md-2 col-xs-12"><small></small></div>
															<div class="col-md-1 col-xs-12"></div>
														</div>
													</div>
													<div class="row">		
														<div class="col-xs-12 col-md-12">											
															<div class="col-md-2"></div>
															<div class="skmaddtarget3"></div>
															<div class="col-md-2 col-xs-12"><small></small></div>
															<div class="col-md-1 col-xs-12"></div>
														</div>
													</div>
													<div class="row">
														<div class="col-xs-12 col-md-12">													
															<div class="col-md-2"></div>
															<div class="skmaddtarget4"></div>
															<div class="col-md-2 col-xs-12"><small></small></div>
															<div class="col-md-1 col-xs-12"></div>
														</div>
													</div>
													<div class="row">	
														<div class="col-xs-12 col-md-12">												
															<div class="col-md-2"></div>
															<div class="skmaddtarget5"></div>
															<div class="col-md-2 col-xs-12"><small></small></div>
															<div class="col-md-1 col-xs-12"></div>
														</div>
													</div>
													<div class="row">	
														<div class="col-xs-12 col-md-12">												
															<div class="col-md-2"></div>
															<div class="skmaddtarget6"></div>
															<div class="col-md-2 col-xs-12"><small></small></div>
															<div class="col-md-1 col-xs-12"></div>
														</div>
													</div>
												</span>
											</fieldset>				
											<fieldset class="fieldset-job_monthly_classes">
												<div class="col-md-2 col-xs-12 text-right"><label style="margin-left: -40px;line-height: 0;" for="job_monthly_classes">Number of classes in a month</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<select name="job_monthly_classes" class="job-manager-category-dropdown" id="job_monthly_classes" required=""  placeholder="Number of classes in a month.">
														<option value="">Number of classes in a month.</option>
														<option value="1">1</option>
														<option value="2">2</option>
														<option value="3">3</option>
														<option value="4">4</option>
														<option value="5">5</option>
														<option value="6">6</option>
														<option value="7">7</option>
														<option value="8">8</option>
														<option value="9">9</option>
														<option value="10">10</option>
														<option value="11">11</option>
														<option value="12">12</option>
														<option value="13">13</option>
														<option value="14">14</option>
														<option value="15">15</option>											
													</select>													
												</div>
												<div class="col-md-3 col-xs-12"><small>You can select upto 15 sessions.</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>	
											<fieldset class="fieldset-session_duration">
												<div class="col-md-2 col-xs-12 text-right"><label style="padding:5px" for="session_duration">Session duration</label></div>
												<div class="field col-md-9 col-xs-12 required-field">
													<div class="row jobhrsrow">
														<div class="field col-md-3 col-xs-12"><div id="sbtn1" data-duration='30'  class="job-btn-session text-center">30 mins.</div></div>
														<div class="field col-md-3 col-xs-12"><div id="sbtn2" data-duration='45'  class="job-btn-session text-center">45 mins.</div></div>
														<div class="field col-md-3 col-xs-12"><div id="sbtn3" data-duration='60'  class="job-btn-session text-center">1 hr.</div></div>
													</div>
													<div class="row jobhrsrow">
														<div class="field col-md-3 col-xs-12"><div id="sbtn4" data-duration='75'  class="job-btn-session text-center">1 hr. 15 mins.</div></div>
														<div class="field col-md-3 col-xs-12"><div id="sbtn5" data-duration='90'  class="job-btn-session text-center">1 hr. 30 mins.</div></div>
														<div class="field col-md-3 col-xs-12"><div id="sbtn6" data-duration='120'  class="job-btn-session text-center">2 hrs.</div></div>
													</div>
													<input type="hidden" name="session_duration" id="session_duration">											
												</div>												
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>											
											<fieldset class="fieldset-hourly_rate">
												<div class="col-md-2 col-xs-12 text-right"><label for="hourly_rate" class="linefix">Fees per class (in ₹)</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<input type="text" class="input-text" name="hourly_rate" id="hourly_rate" placeholder="" value="" maxlength="5" required="">
													<div class="fa fa-money classicon"></div>
												</div>
												<div class="col-md-3 col-xs-12"><small>How much do you wish to charge?</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>	
											<fieldset class="fieldset-job_monthly_fees">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_monthly_fees">Total fees (in ₹)</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<input type="text" class="input-text" name="job_monthly_fees" id="job_monthly_fees" placeholder="" value="" maxlength="5" required="">
													<div class="fa fa-money classicon"></div>
												</div>
												<div class="col-md-3 col-xs-12"><small>Your monthly fees for this course</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>																														
											<fieldset class="fieldset-job_no_of_seats">
												<div class="col-md-2 col-xs-12 text-right"><label for="job_no_of_seats">Number of seats</label></div>
												<div class="field col-md-4 col-xs-12 required-field">
													<select name="job_no_of_seats" class="job-manager-category-dropdown" id="job_no_of_seats" required=""  placeholder="Number of Seats in this course.">
														<option value="">Number of Seats in this course.</option>
														<option value="1">1</option>
														<option value="2">2</option>
														<option value="3">3</option>
														<option value="4">4</option>
														<option value="5">5</option>
														<option value="6">6</option>
														<option value="7">7</option>
														<option value="8">8</option>
														<option value="9">9</option>
														<option value="10">10</option>
														<option value="11">11</option>
														<option value="12">12</option>
														<option value="13">13</option>
														<option value="14">14</option>
														<option value="15">15</option>											
													</select>
												</div>
												<div class="col-md-3 col-xs-12"><small>How many students can attend this class? Select 1 for individual classes.</small></div>
												<div class="col-md-2 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon hide"></span>
												</div>												
											</fieldset>											
										</div>																																			
										<div class="row text-center">
											<button class='next uicont'>Continue</button>
										</div>
									</div>
									<h3 class="head3 ui-state-disabled">3. Location <span class="h3-right">Tell us where are the classes?</span><span class="fa fa-check h3-check hide"></span></h3>											
									<div class="form3">
										<fieldset class="fieldset-job_type">
											<div style="margin-top:-14px" class="col-md-2 col-xs-12 text-right"><label for="job_type">Travel policy</label></div>
											<div class="field col-md-5 col-xs-12 required-field">
												<div class="row radio-row">
													<div class="col-xs-12">
														<input type="radio" checked style="margin-left: 5px; margin-right: 5px; width: auto;" data-meta_key="job_type" class="jmfe-radio input-radio" name="job_type" id="job_type-student-location" value="37">Conducted At Student’s Location
													</div>
												</div>
												<div class="row radio-row">	
													<div class="col-xs-12">
														<input type="radio" style="margin-left: 5px; margin-right: 5px; width: auto;" data-meta_key="job_type" class="jmfe-radio input-radio" name="job_type" id="job_type-tutor-location" value="38">Conducted At Tutor’s Location
													</div>
												</div>														
											</div>
											<div class="col-md-4 col-xs-12"></div>
											<div class="col-md-1 col-xs-12">
												<span class="fa fa-check-circle greenicon"></span>
											</div>												
										</fieldset>

										<fieldset class="fieldset-job_location">
											<div class="col-md-2 col-xs-12 text-right"><label for="job_location">Location</label></div>
											<div class="field col-md-5 col-xs-12 required-field">
												<input type="text" class="input-text" name="job_location" id="job_location" placeholder="e.g. ABC Apartments, Indiranagar, Bangalore" value="<?php echo $address; ?>" maxlength="" required="">
											</div>
											<div class="col-md-4 col-xs-12"></div>
											<div class="col-md-1 col-xs-12">
												<span class="fa fa-check-circle greenicon hide"></span>
											</div>												
										</fieldset>
										<fieldset class="fieldset-company_website">
											<div class="col-md-2 col-xs-12 text-right"><label for="company_website">PIN code</label></div>
											<div class="field col-md-5 col-xs-12 required-field">
												<input type="text" class="input-text" name="company_website" id="company_website" placeholder="Your 6 digit area code" value="<?php echo $pincode; ?>" maxlength="6" required="">
											</div>
											<div class="col-md-4 col-xs-12">
												<small style="line-height:45px" class="description">Not sure about your PIN code? <a style="color: #3396d1 !important" href="<?php echo get_site_url()."/bangalore-pincodes/"; ?>" target="_blank">Find it here</a></small>
											</div>
											<div class="col-md-1 col-xs-12">
												<span class="fa fa-check-circle greenicon hide"></span>
											</div>												

										</fieldset>
										<div class="row text-center">
											<button class='next uicont'>Continue</button>
										</div>
									</div>	
									<h3 class="head4 ui-state-disabled">4. Review <span class="h3-right">What would you like to teach?</span><span class="fa fa-check h3-check hide"></span></h3>
									<div class="form4">
										<fieldset class="fieldset-tutor_gender_pref">
											<div class="col-md-6 col-xs-12 review">
												<div class="review-title"></div>
												<div class="review-description"></div>
												<div class="review-details">
													<span class="review-main-cat"></span> - 
													<span class="review-sub-cat"></span> - 
													<span class="review-job-type"></span>
												</div>
												<div class="review-datetime"></div>
												<div class="review-fees"></div>
												<div class="review-location"></div>
											</fieldset>
											<fieldset class="fieldset-tutor_gender_pref">
												<div class="col-md-2 col-xs-12 text-right"><label for="tutor_gender_pref" style="line-height: 45px;">Any preferences?</label></div>
												<div class="field col-md-4 col-xs-12">	
													<select name="tutor_gender_pref" class="job-manager-category-dropdown" id="tutor_gender_pref" required=""  placeholder="Any preferences?">
														<option selected="selected" value="I don't have any Preferences">I don't have any Preferences</option>
														<option value="Only Females">Only Females</option>
														<option value="Only Males">Only Males</option>
														<option value="Only School Students">Only School Students</option>
														<option value="Only Adults">Only Adults</option>																														
													</select>									
												</div>
												<div class="col-md-4 col-xs-12"></div>
												<div class="col-md-1 col-xs-12"></div>
												<div class="col-md-1 col-xs-12">
													<span class="fa fa-check-circle greenicon"></span>
												</div>
											</fieldset>	
											<div class="row text-center">
												<input class="button" type="submit" value="Create Class" id="submit" style="background: #77c04b !important; font-size:18px">
											</div>																												
										</div>
									</div>
								</form>
							</aside>
						</div>
					</div>
				</article>
			</main>
		</div>
	</div>
</div>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/geocomplete/1.6.5/jquery.geocomplete.min.js"></script>
<script type="text/javascript" src="<?php echo get_site_url(); ?>/wp-content/themes/listify/js/timepicker.js"></script>
<script type="text/javascript">
	var review = false;
	jQuery(window).load(function(){
		jQuery('input[name=job_date]').each(function(){
			jQuery(this).datepicker({ dateFormat: 'dd/mm/yy' });
		})
		jQuery('input[name=job_start_time],input[name=job_end_time],input[name=job_time]').each(function(){
			jQuery(this).timepicker({minTime:'8:00am',maxTime:'10:00pm'});
		})				
	})
	jQuery('.job-btn-session').click(function() {
	    var sthis = jQuery(this);
	    jQuery('.job-btn-session').each(function() {
	        jQuery(this).attr('style', '');
	    })
	    sthis.css({
	        "color": "#fff",
	        "background": "#3396d1",
	        "border": "1px solid #3396d1"
	    });
	    jQuery('#session_duration').val(sthis.data('duration'));
	})

	skmctr = 1;
	jQuery('.skmaddlink').click(function(e) {
	    e.preventDefault();
	    if (skmctr < 7) {
	        var skm = jQuery('.skmaddsource:first').clone();
	        var skmselect = skm.find('select');
	        skm.find(".job-manager-category-dropdown-wrapper").replaceWith(skmselect);
	        skm.find('#job_time').val('').css({
	            "border-color": "#ebeef1"
	        });
	        skm.find('#job_day').val('');
	        skmctr = jQuery('.skmaddsource').length;
	        jQuery('.skmaddtarget' + skmctr).append(skm);
	        jQuery('.skmaddtarget' + skmctr).find(".job-manager-category-dropdown").chosen({
	            search_contains: !0,
	            width: "195%"
	        });
	        jQuery("#accordion").accordion({
	            heightStyle: 'content'
	        });
	        jQuery('.skmaddtarget').find('small').each(function() {
	            jQuery(this).replaceWith('<div class="skmremovelink"><span class="fa fa-minus-circle"></span></div>');
	        });
	        jQuery('.skmremovelink').each(function() {
	            jQuery(this).addClass('hide')
	        })
	        for (i = 1; i < 7; i++) {
	            if (jQuery(".skmaddtarget" + i).children().length) {
	                skmctr = i;
	            }
	        }
	        jQuery('.skmaddtarget' + skmctr).parent('.col-md-12').find('.skmremovelink').removeClass('hide')
	        skmctr++;
	        tempct = skmctr;
			jQuery('input[name=job_time]').each(function(){
				jQuery(this).timepicker();
			})	        
	        jQuery('.skmremovelink').click(function() {
	            jQuery(this).parents('.col-md-12').find('.skmaddsource').remove();
	            jQuery('.skmremovelink').each(function() {
	                jQuery(this).addClass('hide')
	            })
	            for (i = 1; i < 7; i++) {
	                if (jQuery(".skmaddtarget" + i).children().length) {
	                    skmctr = i;
	                }
	            }
	            jQuery('.skmaddtarget' + skmctr).parent('.col-md-12').find('.skmremovelink').removeClass('hide')
	        })
	    }
	    if (skmctr == 7) {
	        jQuery('.skmaddlink').text("Only 7 Allowed").css({
	            'color': '#f00'
	        });
	        setTimeout(function() {
	            jQuery('.skmaddlink').text("Add more");
	            jQuery('.skmaddlink').css({
	                'color': '#3396d1'
	            });
	        }, 3000);
	    }
	})
	jQuery('.form2').find('.greenicon').each(function() {
	    jQuery(this).addClass('greenicondis').removeClass('greenicon');
	})
	jQuery('.regular-type').find('.greenicondis').each(function() {
	    jQuery(this).addClass('greenicon').removeClass('greenicondis')
	})
	jQuery('.classtypebox').click(function() {
	    var classthis = jQuery(this)
	    jQuery('.classtypebox').each(function() {
	        jQuery(this).removeClass('active');
	    })
	    classthis.addClass('active');
	    jQuery('.form2').find('.greenicon').each(function() {
	        jQuery(this).addClass('greenicondis').removeClass('greenicon');
	    })
	    jQuery('#job_classtype').val(classthis.data('type'));
	    if (jQuery('#job_classtype').val() == 'regular') {
	        jQuery('.regular-type').removeClass('hide');
	        jQuery('.regular-type').find('.greenicondis').each(function() {
	            jQuery(this).addClass('greenicon').removeClass('greenicondis')
	        })
	        if (!jQuery('.course-type').hasClass('hide')) {
	            jQuery('.course-type').addClass('hide');
	        }
	        if (!jQuery('.batch-type').hasClass('hide')) {
	            jQuery('.batch-type').addClass('hide');
	        }
	    } else if (jQuery('#job_classtype').val() == 'batch') {
	        jQuery('.batch-type').removeClass('hide');
	        jQuery('.batch-type').find('.greenicondis').each(function() {
	            jQuery(this).addClass('greenicon').removeClass('greenicondis')
	        })
	        if (!jQuery('.course-type').hasClass('hide')) {
	            jQuery('.course-type').addClass('hide');
	        }
	        if (!jQuery('.regular-type').hasClass('hide')) {
	            jQuery('.regular-type').addClass('hide');
	        }
	    } else if (jQuery('#job_classtype').val() == 'course') {
	        jQuery('.course-type').removeClass('hide');
	        jQuery('.course-type').find('.greenicondis').each(function() {
	            jQuery(this).addClass('greenicon').removeClass('greenicondis')
	        })
	        if (!jQuery('.regular-type').hasClass('hide')) {
	            jQuery('.regular-type').addClass('hide');
	        }
	        if (!jQuery('.batch-type').hasClass('hide')) {
	            jQuery('.batch-type').addClass('hide');
	        }
	    }
		jQuery('input[name=job_date]').each(function(){
			jQuery(this).datepicker({ dateFormat: 'dd/mm/yy' });
		})	    
	})
	document.getElementById('accordion').style.display = 'none';
	jQuery(window).load(function() {
	    jQuery("#accordion").accordion({
	        heightStyle: 'content'
	    });
	    document.getElementById('accordion').style.display = 'block';
	});
	jQuery(window).load(function() {
	    var initbio = jQuery('#job_description').val();
	    jQuery('#job_description').blur(function() {
	        var finalbio = jQuery('#job_description').val();
	        if (initbio != finalbio && finalbio == '') {
	            mixpanel.track("class_summ_del");
	        } else if (initbio != finalbio && initbio == '') {
	            mixpanel.track("class_summ_add");
	        } else if (initbio != finalbio && finalbio != '' && initbio != '') {
	            mixpanel.track("class_summ_edit");
	        }
	    })
	    jobhrsArray = [
	        [
	            [''],
	            [''],
	            [''],
	            ['']
	        ],
	        [
	            [''],
	            [''],
	            [''],
	            ['']
	        ],
	        [
	            [''],
	            [''],
	            [''],
	            ['']
	        ],
	        [
	            [''],
	            [''],
	            [''],
	            ['']
	        ],
	        [
	            [''],
	            [''],
	            [''],
	            ['']
	        ],
	        [
	            [''],
	            [''],
	            [''],
	            ['']
	        ],
	        [
	            [''],
	            [''],
	            [''],
	            ['']
	        ]
	    ];
	    jQuery('.job-btn').click(function(e) {
	        e.preventDefault();
	        if (!jQuery(this).hasClass('active')) {
	            jQuery(this).css({
	                "color": "#3396d1 !important"
	            }).css({
	                "border-color": "#3396d1"
	            }).addClass("active");
	            var btnid = jQuery(this).attr('id');
	            jobhrsArray[parseInt(btnid.charAt(0)) - 1][parseInt(btnid.charAt(1)) - 1] = jQuery(this).data('slot');
	            var flag = 0;
	            weekstr = '';
	            for (var i = 0; i < 7; i++) {
	                flag = 0;
	                for (var j = 0; j < 4; j++) {
	                    if (jobhrsArray[i][j] != '') {
	                        weekstr += "|" + jobhrsArray[i][j] + "|";
	                        flag = 1;
	                    }
	                }
	                if (!flag) {
	                    weekstr += "%empty%";
	                } else {
	                    weekstr = '%' + weekstr + '%';
	                }
	                weekstr = weekstr.trim();
	                weekstr = weekstr.replace(/\|+/g, '|');
	                weekstr = weekstr.replace(/\%+/g, '%');
	                weekstr = weekstr.replace("|%|", "%");
	                weekstr = weekstr.replace("|%", "%");
	                weekstr = weekstr.replace("%|", "%");
	                if (weekstr.slice(-1) == '|' && weekstr.charAt(0) == '|') {
	                    weekstr = weekstr.substring(1, weekstr.length - 1);
	                }
	            }
	            weekstr = weekstr.trim();
	            weekstr = weekstr.substring(1, weekstr.length - 1);
	            weekstr = weekstr.replace(/\|+/g, '|');
	            weekstr = weekstr.replace(/\%+/g, '%');
	            weekstr = weekstr.replace("|%|", "%");
	            weekstr = weekstr.replace("|%", "%");
	            weekstr = weekstr.replace("%|", "%");
	            if (weekstr.slice(-1) == '|' && weekstr.charAt(0) == '|') {
	                weekstr = weekstr.substring(1, weekstr.length - 1);
	            }
	            jQuery('#newjobhrs').val(weekstr);
	        } else {
	            jQuery(this).css({
	                "color": "#999 !important"
	            }).css({
	                "border-color": "#999"
	            }).removeClass("active");
	            var btnid = jQuery(this).attr('id');
	            jobhrsArray[parseInt(btnid.charAt(0)) - 1][parseInt(btnid.charAt(1)) - 1] = '';
	            var flag = 0;
	            weekstr = '';
	            for (var i = 0; i < 7; i++) {
	                flag = 0;
	                for (var j = 0; j < 4; j++) {
	                    if (jobhrsArray[i][j] != '') {
	                        weekstr += "|" + jobhrsArray[i][j] + "|";
	                        flag = 1;
	                    }
	                }
	                if (!flag) {
	                    weekstr += "%empty%";
	                } else {
	                    weekstr = '%' + weekstr + '%';
	                }
	                weekstr = weekstr.trim();
	                weekstr = weekstr.replace(/\|+/g, '|');
	                weekstr = weekstr.replace(/\%+/g, '%');
	                weekstr = weekstr.replace("|%|", "%");
	                weekstr = weekstr.replace("|%", "%");
	                weekstr = weekstr.replace("%|", "%");
	                if (weekstr.slice(-1) == '|' && weekstr.charAt(0) == '|') {
	                    weekstr = weekstr.substring(1, weekstr.length - 1);
	                }
	            }
	            weekstr = weekstr.trim();
	            weekstr = weekstr.substring(1, weekstr.length - 1);
	            weekstr = weekstr.replace(/\|+/g, '|');
	            weekstr = weekstr.replace(/\%+/g, '%');
	            weekstr = weekstr.replace("|%|", "%");
	            weekstr = weekstr.replace("|%", "%");
	            weekstr = weekstr.replace("%|", "%");
	            if (weekstr.slice(-1) == '|' && weekstr.charAt(0) == '|') {
	                weekstr = weekstr.substring(1, weekstr.length - 1);
	            }
	            jQuery('#newjobhrs').val(weekstr);
	        }
	        return false;
	    })
	    var rupee = /^(?:0|[1-9]\d*)(?:\.(?!.*000)\d+)?$/;
	    var pincode = /^([1-9])([0-9]){5}$/;
	    var validTime = /^(0?[1-9]|1[012])(:[0-5]\d) [APap][mM]$/;
	    var validDate = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
	    var sliderwidth = 30;
	    jQuery(function() {
	        jQuery(".job-manager-category-dropdown").chosen({
	            search_contains: !0,
	            width: "195%"
	        })
	    });
	    jQuery(function(a) {
	        a(".jmfe-radio").click(function() {
	            var b = a(this).data("meta_key");
	            a(".jmfe-clear-radio-" + b).fadeIn("slow")
	        }), a(".jmfe-clear-radio").click(function() {
	            var b = a(this).data("meta_key");
	            a(".jmfe-radio-" + b).prop("checked", !1), a(".jmfe-clear-radio-" + b).fadeOut("slow")
	        })
	    });
	    jQuery(document).ready(function() {
	        jQuery("body").on("click", ".job-manager-remove-uploaded-file", function() {
	            return jQuery(this).closest(".job-manager-uploaded-file").remove(), !1
	        })
	    });
	    jQuery('#job_location').geocomplete();
	    jQuery('.head2,.head3,.head4, .form1 .uicont').click(function(e) {
	        e.preventDefault();
	        e.stopImmediatePropagation();
	        if (jQuery("#accordion").accordion("option", "active") == 0) {
	            jQuery('.form1').find('input').each(function() {
	                if (jQuery(this).val() == '' && jQuery(this).prop('required')) {
	                    jQuery(this).css({
	                        "border-color": "#f00"
	                    });
	                    jQuery(this).focus();
	                }
	            })
	            if (jQuery('#job_category_main').val() == null || jQuery('#job_category_main').val() == 0) {
	                jQuery('#job_category_main_chosen').css({
	                    "border": "2px solid #f00"
	                });
	                jQuery('#job_category_main_chosen').focus();
	            } else if (jQuery('.subcat' + jQuery('#job_category_main').val()).val() == null || jQuery('.subcat' + jQuery('#job_category_main').val()).val() == 0) {
	                jQuery('.catwrap').css({
	                    "border": "2px solid #f00"
	                });
	                jQuery('.catwrap').focus();
	            }
	            if (jQuery("#job_description").val().length < 20){
	            	var init = jQuery("#job_description").val();
	            	jQuery("#job_description").css({"color":"#f00", "border-color": "#f00"}).val("Minimum 20 characters.")
	            	setTimeout(function(){
	            		jQuery("#job_description").attr('style','').val(init);
	            	}, 2000);
	            	jQuery("#job_description").focus();
	            }
	            if (jQuery("#job_title").val().length < 20){
	            	var init1 = jQuery("#job_title").val();
	            	jQuery("#job_title").css({"color":"#f00", "border-color": "#f00"}).val("Minimum 20 characters.")
	            	setTimeout(function(){
	            		jQuery("#job_title").attr('style','').val(init1);
	            	}, 2000);
	            	jQuery("#job_title").focus();
	            }	            
	            var flag = true;
	            jQuery('.form1').find('.greenicon').each(function() {
	                if (jQuery(this).hasClass('hide')) {
	                    flag = false;
	                }
	            });
	            if (flag) {
	                if (jQuery('.head2').hasClass('ui-state-disabled')) {
	                    jQuery('.head2').removeClass("ui-state-disabled");
	                }
	                jQuery('#accordion').accordion('option', 'active', 1);
					jQuery('html,body').animate({
			        	scrollTop: jQuery(".blue-h2").offset().top - 140
			        }, 100);	                
	            } else {
	                if (!jQuery('.head2').hasClass('ui-state-disabled')) {
	                    jQuery('.head2').addClass("ui-state-disabled");
	                }
	                if (!jQuery('.head3').hasClass('ui-state-disabled')) {
	                    jQuery('.head3').addClass("ui-state-disabled");
	                }
	                if (!jQuery('.head4').hasClass('ui-state-disabled')) {
	                    jQuery('.head4').addClass("ui-state-disabled");
	                }
	                jQuery('.head4 .h3-right').removeClass('hide');
	                jQuery('.head4 .h3-check').addClass('hide');
	                if (jQuery('.head4').hasClass('formdone')) {
	                    jQuery('.head4').removeClass('formdone');
	                }
	                jQuery('#accordion').accordion('option', 'active', 0);
	            }
	        }
	    });


	    jQuery('.head3, .head4, .form2 .uicont').click(function(e) {
	        e.preventDefault();
	        e.stopImmediatePropagation();
	        if (jQuery("#accordion").accordion("option", "active") == 1) {
	            var flag1 = true;
	            if (jQuery('#job_classtype').val() == 'regular') {
	                if (jQuery("#newjobhrs").val() == '' || jQuery("#newjobhrs").val() == "empty%empty%empty%empty%empty%empty%empty") {
	                    e.preventDefault();
	                    jQuery('.jobhrsborder').css({
	                        "border": "2px solid #f00"
	                    });
	                    jQuery('.jobhrsborder').focus();
	                }
	                if (jQuery('.regular-type #job_monthly_classes').val() == null || jQuery('.regular-type #job_monthly_classes').val() == 0) {
	                    jQuery('.regular-type #job_monthly_classes_chosen').css({
	                        "border": "2px solid #f00"
	                    });
	                    jQuery('.regular-type #job_monthly_classes_chosen').focus();
	                }
	                if (jQuery('.regular-type #job_no_of_seats').val() == null || jQuery('.regular-type #job_no_of_seats').val() == 0) {
	                    jQuery('.regular-type #job_no_of_seats_chosen').css({
	                        "border": "2px solid #f00"
	                    });
	                    jQuery('.regular-type #job_no_of_seats_chosen').focus();
	                }
	                if (!rupee.test(jQuery('#hourly_rate').val())) {
	                    e.preventDefault();
	                    jQuery('.regular-type #hourly_rate').css({
	                        "border-color": "#f00"
	                    });
	                    jQuery('.regular-type #hourly_rate').focus();
	                }
	                if (!rupee.test(jQuery('.regular-type #job_monthly_fees').val())) {
	                    e.preventDefault();
	                    jQuery('.regular-type #job_monthly_fees').css({
	                        "border-color": "#f00"
	                    });
	                    jQuery('.regular-type #job_monthly_fees').focus();
	                }
	                jQuery('.regular-type').find('.greenicon').each(function() {
	                    if (jQuery(this).hasClass('hide')) {
	                        flag1 = false;
	                    }
	                });
	                if (flag1) {
	                    if (jQuery('.head3').hasClass('ui-state-disabled')) {
	                        jQuery('.head3').removeClass("ui-state-disabled");
	                    }
	                    jQuery('#accordion').accordion('option', 'active', 2);
						jQuery('html,body').animate({
			        		scrollTop: jQuery(".blue-h2").offset().top - 140
			       		}, 100);	                    
	                } else {
	                    if (!jQuery('.head3').hasClass('ui-state-disabled')) {
	                        jQuery('.head3').addClass("ui-state-disabled");
	                    }
	                    if (!jQuery('.head4').hasClass('ui-state-disabled')) {
	                        jQuery('.head4').addClass("ui-state-disabled");
	                    }
	                    jQuery('.head4 .h3-right').removeClass('hide');
	                    jQuery('.head4 .h3-check').addClass('hide');
	                    if (jQuery('.head4').hasClass('formdone')) {
	                        jQuery('.head4').removeClass('formdone');
	                    }
	                    jQuery('#accordion').accordion('option', 'active', 1);
	                }
	            }
	            if (jQuery('#job_classtype').val() == 'course') {

	                if (!validDate.test(jQuery('#job_date').val())) {
	                    e.preventDefault();
	                    jQuery('.course-type #job_date').css({
	                        "border-color": "#f00"
	                    });
	                    jQuery('.course-type #job_date').focus();
	                }
	                if (!validTime.test(jQuery('#job_start_time').val())) {
	                    e.preventDefault();
	                    jQuery('.course-type #job_start_time').css({
	                        "border-color": "#f00"
	                    });
	                    jQuery('.course-type #job_start_time').focus();
	                }
	                if (!validTime.test(jQuery('#job_end_time').val())) {
	                    e.preventDefault();
	                    jQuery('.course-type #job_end_time').css({
	                        "border-color": "#f00"
	                    });
	                    jQuery('.course-type #job_end_time').focus();
	                }
	                jQuery('.course-type').find('.greenicon').each(function() {
	                    if (jQuery(this).hasClass('hide')) {
	                        flag1 = false;
	                    }
	                });
	                if (flag1) {
	                    if (jQuery('.head3').hasClass('ui-state-disabled')) {
	                        jQuery('.head3').removeClass("ui-state-disabled");
	                    }
	                    jQuery('#accordion').accordion('option', 'active', 2);
						jQuery('html,body').animate({
			        		scrollTop: jQuery(".blue-h2").offset().top - 140
			       		}, 100);	                    
	                } else {
	                    if (!jQuery('.head3').hasClass('ui-state-disabled')) {
	                        jQuery('.head3').addClass("ui-state-disabled");
	                    }
	                    if (!jQuery('.head4').hasClass('ui-state-disabled')) {
	                        jQuery('.head4').addClass("ui-state-disabled");
	                    }
	                    jQuery('.head4 .h3-right').removeClass('hide');
	                    jQuery('.head4 .h3-check').addClass('hide');
	                    if (jQuery('.head4').hasClass('formdone')) {
	                        jQuery('.head4').removeClass('formdone');
	                    }
	                    jQuery('#accordion').accordion('option', 'active', 1);
	                }

	            }
	            if (jQuery('#job_classtype').val() == 'batch') {
	                if (!validDate.test(jQuery('.batch-type #job_date1').val())) {
	                    jQuery('.batch-type #job_date1').css({
	                        "border-color": "#f00"
	                    });
	                    jQuery('.batch-type #job_date1').focus();
	                }
	                if (jQuery('.batch-type #job_monthly_classes').val() == null || jQuery('.batch-type #job_monthly_classes').val() == 0) {
	                    jQuery('.batch-type #job_monthly_classes_chosen').css({
	                        "border": "2px solid #f00"
	                    });
	                    jQuery('.batch-type #job_monthly_classes_chosen').focus();
	                }
	                if (!rupee.test(jQuery('.batch-type #hourly_rate').val())) {
	                    jQuery('.batch-type #hourly_rate').css({
	                        "border-color": "#f00"
	                    });
	                    jQuery('.batch-type #hourly_rate').focus();
	                }
	                if (!rupee.test(jQuery('.batch-type #job_monthly_fees').val())) {
	                    jQuery('.batch-type #job_monthly_fees').css({
	                        "border-color": "#f00"
	                    });
	                    jQuery('.batch-type #job_monthly_fees').focus();
	                }
	                if (jQuery('#session_duration').val() == '') {
	                    jQuery('#session_duration').parents('fieldset').find('label').css({
	                        "border": "2px solid #f00"
	                    });
	                    jQuery('#session_duration').parents('fieldset').find('label').focus();
	                }
	                if (jQuery('.batch-type #job_no_of_seats').val() == null || jQuery('.batch-type #job_no_of_seats').val() == 0) {
	                    jQuery('.batch-type #job_no_of_seats_chosen').css({
	                        "border": "2px solid #f00"
	                    });
	                    jQuery('.batch-type #job_no_of_seats_chosen').focus();
	                }
	                if (!validTime.test(jQuery('#job_time_dump').val().split(',')[0])) {
	                    jQuery('.batch-type #job_time').css({
	                        "border-color": "#f00"
	                    });
	                    jQuery('.batch-type #job_time').focus();
	                }
	                if (jQuery('#job_day_dump').val().split(',') [0] == '') {
	                    jQuery('.batch-type #job_day_chosen').css({
	                        "border": "2px solid #f00"
	                    });
	                    jQuery('.batch-type #job_day_chosen').focus();
	                }
	                jQuery('.batch-type').find('.greenicon').each(function() {
	                    if (jQuery(this).hasClass('hide')) {
	                        flag1 = false;
	                    }
	                });
	                if (flag1) {
	                    if (jQuery('.head3').hasClass('ui-state-disabled')) {
	                        jQuery('.head3').removeClass("ui-state-disabled");
	                    }
	                    jQuery('#accordion').accordion('option', 'active', 2);
						jQuery('html,body').animate({
			        		scrollTop: jQuery(".blue-h2").offset().top - 140
			       		}, 100);	                    
	                } else {
	                    if (!jQuery('.head3').hasClass('ui-state-disabled')) {
	                        jQuery('.head3').addClass("ui-state-disabled");
	                    }
	                    if (!jQuery('.head4').hasClass('ui-state-disabled')) {
	                        jQuery('.head4').addClass("ui-state-disabled");
	                    }
	                    jQuery('.head4 .h3-right').removeClass('hide');
	                    jQuery('.head4 .h3-check').addClass('hide');
	                    if (jQuery('.head4').hasClass('formdone')) {
	                        jQuery('.head4').removeClass('formdone');
	                    }
	                    jQuery('#accordion').accordion('option', 'active', 1);
	                }
	            }
	        }
	    });
	    jQuery('.head4, .form3 .uicont').click(function(e) {
	        e.preventDefault();
	        e.stopImmediatePropagation();
	        if (jQuery("#accordion").accordion("option", "active") == 2) {
	            jQuery('.form3').find('input').each(function() {
	                if (jQuery(this).val() == '' && jQuery(this).prop('required')) {
	                    jQuery(this).css({
	                        "border-color": "#f00"
	                    });
	                    jQuery(this).focus();
	                }
	            })
	            var flag2 = true;
	            jQuery('.form3').find('.greenicon').each(function() {
	                if (jQuery(this).hasClass('hide')) {
	                    flag2 = false;
	                }
	            });
	            if (flag2) {
	                review = true;
	                jQuery(".review-title").text(jQuery("#job_title").val()).css({
	                    "font-weight": "bold"
	                });
	                jQuery(".review-description").text(jQuery("#job_description").val());
	                jQuery(".review-details").css({
	                    "font-weight": "bold"
	                });
	                jQuery(".review-main-cat").text(jQuery("#job_category_main_chosen a span").text());
	                jQuery(".review-sub-cat").text(jQuery(".catwrap" + jQuery("#job_category_main").val() + " .chosen-single span").text());
	                var reviewtype = jQuery("#job_classtype").val();
	                jQuery(".review-job-type").text(reviewtype).css({
	                    "text-transform": "capitalize"
	                });
	                var reviewDate = '';
	                var reviewMonth = '';
	                var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	                var fees = jQuery("." + reviewtype + "-type #job_monthly_fees").val();
					if (reviewtype == 'course' || reviewtype == 'batch') {
						var d = ''
						if (reviewtype == 'course'){
							d = jQuery("." + reviewtype + "-type #job_date").val();
						} else if(reviewtype == 'batch') {
							d = jQuery("." + reviewtype + "-type #job_date1").val();
						}
						if (d.indexOf('/') != -1) {
							d = d.split('/')
						} else if (d.indexOf('.') != -1) {
							d = d.split('.')
						} else if (d.indexOf('-') != -1) {
							d = d.split('-')
						}
						d = new Date(d[1] + '/' + d[0] + '/' + d[2]);
						reviewDate = d.getDate();
						reviewMonth = monthNames[d.getMonth()];
					}
	                if (reviewtype == 'course') {
	                    fees = jQuery("." + reviewtype + "-type #hourly_rate").val();
	                    jQuery(".review-datetime").text("Starts on " + reviewDate + '-' + reviewMonth + ', ' + jQuery('.course-type #job_start_time').val());
	                } else if (reviewtype == 'batch') {
	                    jQuery(".review-datetime").text("Starts on " + reviewDate + '-' + reviewMonth + ', ' + jQuery("#job_time").val());
	                }
	                jQuery(".review-fees").text("Fees: ₹ " + fees);
	                jQuery(".review-location").css({
	                    "font-weight": "bold"
	                }).text("Classes at " + jQuery("#job_location").val() + " - " + jQuery("#company_website").val());

	                if (jQuery('.head4').hasClass('ui-state-disabled')) {
	                    jQuery('.head4').removeClass("ui-state-disabled").addClass('formdone');
	                }
	                jQuery('.head4 .h3-right').addClass('hide');
	                jQuery('.head4 .h3-check').removeClass('hide');
	                jQuery('#accordion').accordion('option', 'active', 3);
					jQuery('html,body').animate({
		        		scrollTop: jQuery(".blue-h2").offset().top - 140
		       		}, 100);	                

	            } else {
	                review = false;
	                if (!jQuery('.head4').hasClass('ui-state-disabled')) {
	                    jQuery('.head4').addClass("ui-state-disabled");
	                }
	                jQuery('.head4 .h3-right').removeClass('hide');
	                jQuery('.head4 .h3-check').addClass('hide');
	                jQuery('#accordion').accordion('option', 'active', 2);
	            }
	        }
	    });
		jQuery(function() {
		    jQuery('#submit-job-form').submit(function() {
				jQuery('select[name="job_category[]"]').each(function(){
					if(jQuery(this).val() == null || jQuery(this).val()=="0"){
						jQuery(this).remove();
					}
				})	    	
		        if (jQuery('#job_classtype').val() == 'course') {
		            jQuery('.regular-type').remove();
		            jQuery('.batch-type').remove();
		        } else if (jQuery('#job_classtype').val() == 'regular') {
		            jQuery('.course-type').remove();
		            jQuery('.batch-type').remove();
		        } else if (jQuery('#job_classtype').val() == 'batch') {
		            jQuery('.regular-type').remove();
		            jQuery('.course-type').remove();
		        }		        
		        return true; 
		    });
		});

	    var greenicon = '';
	    var widtharray = [];

	    function calcwidth() {
	        if (jQuery('#job_location').val() != '') {
	            widtharray[0] = true;
	        } else {
	            widtharray[0] = false;
	        }
	        if (jQuery('#job_title').val().length >= 20) {
	            widtharray[1] = true;
	        } else {
	            widtharray[1] = false;
	        }
	        if (jQuery('.subcat' + jQuery('#job_category_main').val()).val() != null && jQuery('.subcat' + jQuery('#job_category_main').val()).val() != 0 && jQuery('#job_category_main').val() != null && jQuery('#job_category_main').val() != 0) {
	            widtharray[2] = true;
	        } else {
	            widtharray[2] = false;
	        }
	        if (rupee.test(jQuery('#hourly_rate').val())) {
	            widtharray[3] = true;
	        } else {
	            widtharray[3] = false;
	        }
	        if (pincode.test(jQuery('#company_website').val())) {
	            widtharray[4] = true;
	        } else {
	            widtharray[4] = false;
	        }
	        if (jQuery('#job_description').val().length >= 20) {
	            widtharray[5] = true;
	        } else {
	            widtharray[5] = false;
	        }
	        if (jQuery('#newjobhrs').val() != '' && jQuery("#newjobhrs").val() != "empty%empty%empty%empty%empty%empty%empty") {
	            widtharray[6] = true;
	        } else {
	            widtharray[6] = false;
	        }
	        var numOfTrue = 0;
	        for (var i = 0; i < widtharray.length; i++) {
	            if (widtharray[i] === true)
	                numOfTrue++;
	        }
	        sliderwidth = 30 + 10 * numOfTrue;
	        if (sliderwidth == 100) {
	            jQuery(".cent").removeClass('hide');
	        } else {
	            jQuery(".cent").removeClass('hide').addClass('hide');
	        }
	    }
	    oldcatvalue = '';
	    var daydump = [];
	    var timedump = [];

	    function parseTime(s) {
	        var part = s.match(/(\d+):(\d+)(?: )?(am|pm)?/i);
	        var hh = parseInt(part[1], 10);
	        var mm = parseInt(part[2], 10);
	        var ap = part[3] ? part[3].toUpperCase() : null;
	        if (ap === "AM") {
	            if (hh == 12) {
	                hh = 0;
	            }
	        }
	        if (ap === "PM") {
	            if (hh != 12) {
	                hh += 12;
	            }
	        }
	        return {
	            hh: hh,
	            mm: mm
	        };
	    }
		var oldregularfees = 0;
		var oldbatchfees = 0;
		var newregularfees = 0;
		var newbatchfees = 0;		    

	    setInterval(function() {
	        daydump.length = 0;
	        timedump.length = 0;
	        
	        jQuery('select[name=job_day]').each(function() {
	            daydump.push(jQuery(this).val());
	        })
	        jQuery('#job_day_dump').val(daydump.join());

	        jQuery('input[name=job_time]').each(function() {
	             timedump.push(jQuery(this).val());
	        })
	        jQuery('#job_time_dump').val(timedump.join());

	        if (skmctr == 1 && !jQuery('.skmaddtarget' + skmctr).children().length) {
	            jQuery('.skmaddtarget' + skmctr).parent('.col-md-12').find('.skmremovelink').addClass('hide')
	        }
	        if (review) {
	            jQuery(".review-title").text(jQuery("#job_title").val()).css({
	                "font-weight": "bold"
	            });
	            jQuery(".review-description").text(jQuery("#job_description").val());
	            jQuery(".review-details").css({
	                "font-weight": "bold"
	            });
	            jQuery(".review-main-cat").text(jQuery("#job_category_main_chosen a span").text());
	            jQuery(".review-sub-cat").text(jQuery(".catwrap" + jQuery("#job_category_main").val() + " .chosen-single span").text());
	            var reviewtype = jQuery("#job_classtype").val();
	            jQuery(".review-job-type").text(reviewtype).css({
	                "text-transform": "capitalize"
	            });
	            var reviewDate = '';
	            var reviewMonth = '';
	            var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	            var fees = jQuery("." + reviewtype + "-type #job_monthly_fees").val();
				if (reviewtype == 'course' || reviewtype == 'batch') {
					var d = ''
					if (reviewtype == 'course'){
						d = jQuery("." + reviewtype + "-type #job_date").val();
					} else if(reviewtype == 'batch') {
						d = jQuery("." + reviewtype + "-type #job_date1").val();
					}
					if (d.indexOf('/') != -1) {
						d = d.split('/')
					} else if (d.indexOf('.') != -1) {
						d = d.split('.')
					} else if (d.indexOf('-') != -1) {
						d = d.split('-')
					}
					d = new Date(d[1] + '/' + d[0] + '/' + d[2]);
					reviewDate = d.getDate();
					reviewMonth = monthNames[d.getMonth()];
				}
	            if (reviewtype == 'course') {
	                fees = jQuery("." + reviewtype + "-type #hourly_rate").val();
	                jQuery(".review-datetime").text("Starts on " + reviewDate + '-' + reviewMonth + ', ' + jQuery('.course-type #job_start_time').val());
	            } else if (reviewtype == 'batch') {
	                jQuery(".review-datetime").text("Starts on " + reviewDate + '-' + reviewMonth + ', ' + jQuery("#job_time").val());
	            }
	            jQuery(".review-fees").text("Fees: ₹ " + fees);
	            jQuery(".review-location").css({
	                "font-weight": "bold"
	            }).text("Classes at " + jQuery("#job_location").val() + " - " + jQuery("#company_website").val());

	        }

	        if (jQuery('#job_category_main').val() != null && jQuery('#job_category_main').val() != 0) {
	            if (oldcatvalue != jQuery('#job_category_main').val()) {
	                jQuery('.catwrap').each(function() {
	                    jQuery(this).addClass('hide');
	                })
	                jQuery('.catwrap' + jQuery('#job_category_main').val()).removeClass('hide');
	                oldcatvalue = jQuery('#job_category_main').val();
	            }
	        } else {
	            jQuery('.subcat' + oldcatvalue).val('0');
	            jQuery('.catwrap').each(function() {
	                jQuery(this).addClass('hide');
	            })
	        }
	        for (var i = 3; i >= 1; i--) {
	            jQuery('.form' + i).each(function() {
	                var flag5 = true;
	                jQuery(this).find('.greenicon').each(function() {
	                    if (jQuery(this).hasClass('hide')) {
	                        flag5 = false;
	                    }
	                })
	                if (flag5) {
	                    jQuery('.head' + i).removeClass('formnotdone').addClass('formdone');
	                    jQuery('.head' + i + ' .h3-right').addClass('hide');
	                    jQuery('.head' + i + ' .h3-check').removeClass('hide');
	                } else if (!flag5 && !jQuery('.head' + i).hasClass('ui-state-active')) {
	                    jQuery('.head' + i).removeClass('formdone').addClass('formnotdone');
	                    jQuery('.head' + i + ' .h3-right').removeClass('hide');
	                    jQuery('.head' + i + ' .h3-check').addClass('hide');
	                } else if (!flag5 && jQuery('.head' + i).hasClass('ui-state-active')) {
	                    jQuery('.head' + i + ' .h3-right').removeClass('hide');
	                    jQuery('.head' + i + ' .h3-check').addClass('hide');
	                }

	            })
	        };
	        calcwidth();
	        jQuery('.greenbar').css({
	            "width": sliderwidth + "%"
	        });
	        if (jQuery('#job_location').val() != '') {
	            jQuery('#job_location').css({
	                "border-color": "#ebeef1"
	            });
	            greenicon = jQuery('#job_location').parents('fieldset').find('.greenicon');
	            if (greenicon.hasClass('hide')) {
	                greenicon.removeClass('hide');
	            }

	        } else {
	            greenicon = jQuery('#job_location').parents('fieldset').find('.greenicon');
	            if (!greenicon.hasClass('hide')) {
	                greenicon.addClass('hide');
	            }
	        }
	        if (jQuery('#job_title').val() != '' && jQuery("#job_title").val().length >= 20) {
	            jQuery('#job_title').css({
	                "border-color": "#ebeef1"
	            });
	            greenicon = jQuery('#job_title').parents('fieldset').find('.greenicon');
	            if (greenicon.hasClass('hide')) {
	                greenicon.removeClass('hide');
	            }

	        } else {
	            greenicon = jQuery('#job_title').parents('fieldset').find('.greenicon');
	            if (!greenicon.hasClass('hide')) {
	                greenicon.addClass('hide');
	            }
	        }
			if (rupee.test(jQuery(".regular-type #hourly_rate").val()) && jQuery(".regular-type select[name=job_monthly_classes]").val()!=null && jQuery(".regular-type select[name=job_monthly_classes]").val()!="0"){
				newregularfees = jQuery(".regular-type #hourly_rate").val()*jQuery(".regular-type select[name=job_monthly_classes]").val();
				if(newregularfees != oldregularfees){
					jQuery(".regular-type #job_monthly_fees").val(newregularfees);
					oldregularfees = newregularfees;
				}
			} else{
				jQuery(".regular-type #job_monthly_fees").val('')
			}
			if (rupee.test(jQuery(".batch-type #hourly_rate").val()) && jQuery(".batch-type select[name=job_monthly_classes]").val()!=null && jQuery(".batch-type select[name=job_monthly_classes]").val()!="0"){
				newbatchfees = jQuery(".batch-type #hourly_rate").val()*jQuery(".batch-type select[name=job_monthly_classes]").val();
				if(newbatchfees != oldbatchfees){
					jQuery(".batch-type #job_monthly_fees").val(newbatchfees);
					oldbatchfees = newbatchfees;
				}
			} else{
				jQuery(".batch-type #job_monthly_fees").val('')
			}	        
	        if (jQuery('#job_category_main').val() != null && jQuery('#job_category_main').val() != 0) {
	            jQuery('#job_category_main_chosen').css({
	                "border": "2px solid #ebeef1"
	            });
	        }
	        if (jQuery('.subcat' + jQuery('#job_category_main').val()).val() != null && jQuery('.subcat' + jQuery('#job_category_main').val()).val() != 0) {
	            jQuery('.catwrap').css({
	                "border": "2px solid #ebeef1"
	            });
	        }
	        if (jQuery('.subcat' + jQuery('#job_category_main').val()).val() != null && jQuery('.subcat' + jQuery('#job_category_main').val()).val() != 0 && jQuery('#job_category_main').val() != null && jQuery('#job_category_main').val() != 0) {
	            greenicon = jQuery('.subcat' + jQuery('#job_category_main').val()).parents('fieldset').find('.greenicon');
	            if (greenicon.hasClass('hide')) {
	                greenicon.removeClass('hide');
	            }

	        } else {
	            greenicon = jQuery('#job_category_main').parents('fieldset').find('.greenicon');
	            if (!greenicon.hasClass('hide')) {
	                greenicon.addClass('hide');
	            }
	        }
	        if (validTime.test(jQuery('#job_time_dump').val().split(',') [0])) {
	            jQuery('.batch-type #job_time').css({
	                "border-color": "#ebeef1"
	            });

	        }
	        if (jQuery('#job_day_dump').val().split(',') [0] != '') {
	            jQuery('.batch-type #job_day_chosen').css({
	                "border": "2px solid #ebeef1"
	            });
	        }
	        if (validTime.test(jQuery('#job_time_dump').val().split(',') [0]) && jQuery('#job_day_dump').val().split(',') [0] != '') {
	            greenicon = jQuery('.batch-type #job_time').parents('fieldset').find('.greenicon');
	            if (greenicon.hasClass('hide')) {
	                greenicon.removeClass('hide');
	            }
	        } else {
	            greenicon = jQuery('.batch-type #job_time').parents('fieldset').find('.greenicon');
	            if (!greenicon.hasClass('hide')) {
	                greenicon.addClass('hide');
	            }
	        }
	        jQuery('select[name=job_monthly_classes]').each(function() {
	            if (jQuery(this).val() != null && jQuery(this).val() != 0) {
	                jQuery(this).parents('fieldset').find('#job_monthly_classes_chosen').css({
	                    "border": "2px solid #ebeef1"
	                });
	                greenicon = jQuery(this).parents('fieldset').find('.greenicon');
	                if (greenicon.hasClass('hide')) {
	                    greenicon.removeClass('hide');
	                }

	            } else {
	                greenicon = jQuery(this).parents('fieldset').find('.greenicon');
	                if (!greenicon.hasClass('hide')) {
	                    greenicon.addClass('hide');
	                }
	            }
	        })
	        jQuery('select[name=job_no_of_seats]').each(function() {
	            if (jQuery(this).val() != null && jQuery(this).val() != 0) {
	                jQuery(this).parents('fieldset').find('#job_no_of_seats_chosen').css({
	                    "border": "2px solid #ebeef1"
	                });
	                greenicon = jQuery(this).parents('fieldset').find('.greenicon');
	                if (greenicon.hasClass('hide')) {
	                    greenicon.removeClass('hide');
	                }

	            } else {
	                greenicon = jQuery(this).parents('fieldset').find('.greenicon');
	                if (!greenicon.hasClass('hide')) {
	                    greenicon.addClass('hide');
	                }
	            }
	        })
	        jQuery('input[name=job_date]').each(function() {
	            if (validDate.test(jQuery(this).val())) {
	                jQuery(this).css({
	                    "border-color": "#ebeef1"
	                });
	                greenicon = jQuery(this).parents('fieldset').find('.greenicon');
	                if (greenicon.hasClass('hide')) {
	                    greenicon.removeClass('hide');
	                }

	            } else {
	                greenicon = jQuery(this).parents('fieldset').find('.greenicon');
	                if (!greenicon.hasClass('hide')) {
	                    greenicon.addClass('hide');
	                }
	            }
	        });
	        if (jQuery('#session_duration').val() != '') {
	            jQuery('#session_duration').parents('fieldset').find('label').css({
	                "border-color": "#ebeef1"
	            });
	            greenicon = jQuery('#session_duration').parents('fieldset').find('.greenicon');
	            if (greenicon.hasClass('hide')) {
	                greenicon.removeClass('hide');
	            }

	        } else {
	            greenicon = jQuery('#session_duration').parents('fieldset').find('.greenicon');
	            if (!greenicon.hasClass('hide')) {
	                greenicon.addClass('hide');
	            }
	        }
	        if (jQuery('#job_classtype').val() == 'course') {	        
		        timeflag = true;
		        if (validTime.test(jQuery('#job_start_time').val()) && validTime.test(jQuery('#job_end_time').val())){
		    	    var start = parseTime(jQuery('#job_start_time').val());
		            var end = parseTime(jQuery('#job_end_time').val());
		            var date1 = new Date(2000, 0, 1, start.hh, start.mm);
		            var date2 = new Date(2000, 0, 1, end.hh, end.mm);
		            if(date1 > date2){
		            	jQuery('.duration').text("\"End time\" should be more than \"Start time\"").css({"color":"#f00"});
		            	timeflag = false;
		            } else{
		            	timeflag = true;
		            	var diff = date2 - date1;
		                var msec = diff;
		                var hh = Math.floor(msec / 1000 / 60 / 60);
		                msec -= hh * 1000 * 60 * 60;
		                var mm = Math.floor(msec / 1000 / 60);
		                msec -= mm * 1000 * 60;
		                var ss = Math.floor(msec / 1000);
		                msec -= ss * 1000;
	                	jQuery('.duration').text("Your class duration: " + hh + " hrs. and " + mm + " mins.").css({"color":"#333"});
		            }
		        }       

		        jQuery('#job_start_time,#job_end_time').each(function() {
		            if (validTime.test(jQuery(this).val())) {
		                jQuery(this).css({
		                    "border-color": "#ebeef1"
		                });
		                if(!timeflag) {
		                	if(jQuery(this)[0] != jQuery('#job_end_time')[0]){	
		                		greenicon = jQuery(this).parents('fieldset').find('.greenicon');
		                		if (greenicon.hasClass('hide')) {
		                    		greenicon.removeClass('hide');
		                		}
		                	} else {
				                greenicon = jQuery(this).parents('fieldset').find('.greenicon');
				                if (!greenicon.hasClass('hide')) {
				                    greenicon.addClass('hide');
				                }		                		
		                	}
		                } else{
		                		greenicon = jQuery(this).parents('fieldset').find('.greenicon');
		                		if (greenicon.hasClass('hide')) {
		                    		greenicon.removeClass('hide');
		                		}	                	
		                }

		            } else {
		                greenicon = jQuery(this).parents('fieldset').find('.greenicon');
		                if (!greenicon.hasClass('hide')) {
		                    greenicon.addClass('hide');
		                }
		                timeflag = false;
		            }
		        });
			}


	        jQuery('#hourly_rate,#job_monthly_fees').each(function() {
	            if (rupee.test(jQuery(this).val())) {
	                jQuery(this).css({
	                    "border-color": "#ebeef1"
	                });
	                greenicon = jQuery(this).parents('fieldset').find('.greenicon');
	                if (greenicon.hasClass('hide')) {
	                    greenicon.removeClass('hide');
	                }

	            } else {
	                greenicon = jQuery(this).parents('fieldset').find('.greenicon');
	                if (!greenicon.hasClass('hide')) {
	                    greenicon.addClass('hide');
	                }
	            }
	        });

	        if (pincode.test(jQuery('#company_website').val())) {
	            jQuery('#company_website').css({
	                "border-color": "#ebeef1"
	            });
	            greenicon = jQuery('#company_website').parents('fieldset').find('.greenicon');
	            if (greenicon.hasClass('hide')) {
	                greenicon.removeClass('hide');
	            }

	        } else {
	            greenicon = jQuery('#company_website').parents('fieldset').find('.greenicon');
	            if (!greenicon.hasClass('hide')) {
	                greenicon.addClass('hide');
	            }
	        }
	        if (jQuery('#job_description').val() != "" && jQuery("#job_description").val().length >= 20) {
	            jQuery('#job_description').css({
	                "border-color": "#ebeef1"
	            });
	            greenicon = jQuery('#job_description').parents('fieldset').find('.greenicon');
	            if (greenicon.hasClass('hide')) {
	                greenicon.removeClass('hide');
	            }

	        } else {
	            greenicon = jQuery('#job_description').parents('fieldset').find('.greenicon');
	            if (!greenicon.hasClass('hide')) {
	                greenicon.addClass('hide');
	            }
	        }
	        if (jQuery("#newjobhrs").val() != '' && jQuery("#newjobhrs").val() != "empty%empty%empty%empty%empty%empty%empty") {
	            jQuery('.jobhrsborder').css({
	                "border": "0"
	            });
	            greenicon = jQuery("#newjobhrs").parents('fieldset').find('.greenicon');
	            if (greenicon.hasClass('hide')) {
	                greenicon.removeClass('hide');
	            }

	        } else {
	            greenicon = jQuery("#newjobhrs").parents('fieldset').find('.greenicon');
	            if (!greenicon.hasClass('hide')) {
	                greenicon.addClass('hide');
	            }
	        }
			jQuery('.ui-timepicker-list').each(function(){
				jQuery(this).find('li').each(function(){
					timepick = parseTime(jQuery(this).text());
					if(timepick.hh < 8 || timepick.hh > 22 || (timepick.hh == 22 && timepick.mm == 30)){
						jQuery(this).remove();
					}
				})
			})	        
	    }, 100);
	});
</script>
<?php get_footer(); ?>