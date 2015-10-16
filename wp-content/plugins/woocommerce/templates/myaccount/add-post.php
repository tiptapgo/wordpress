<?php 
/**
* Template Name: add class
*
* @package Listify
*/

$user = wp_get_current_user();
$user_id = $user->ID;

echo get_cimyFieldValue($user_id,'MOBILE',true);

$args     = apply_filters( 'job_manager_get_dashboard_jobs_args', array(
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

$my_post = array(
	'post_title'    => '(dummy)',
	'post_content'  => '',
	'post_status'   => 'publish',
	'post_author'	=> $user_id,	
	'post_type' => 'job_listing',
	);

$dummyid = wp_insert_post( $my_post, false);

$temp = get_cimyFieldValue($userid,'MALE');
if ($temp == true){
	update_post_meta($dummyid,'_listing_gender', "Male");
}
$temp1 = get_cimyFieldValue($userid,'FEMALE');
if ($temp1 == true){
	update_post_meta($dummyid,'_listing_gender', "Female");
}

$dob = get_cimyFieldValue($userid,'DOB');
update_post_meta($dummyid,'_tutor_dob', $dob);

$education = get_cimyFieldValue($userid,'QUAL',true);
update_post_meta($dummyid,'_tutor_high_edu', $education);

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
	$yoe = get_cimyFieldValue($userid,'YOE');
}

$email = $user->user_email;

get_header();
?>
<div id="content" class="site-content">
	<div id="primary" class="container">
		<div class="content-area">
			<main id="main" class="site-main" role="main">
				<article>
					<div class="content-box-inner">
						<div class="entry-content">
							<aside class="widget">
								<?php
								if($dummyid == 0){ ?>
								<h1>Sorry posts can't be added Right Now</h1>
								<?php }

								else{ ?>
								<form action="/edit-classes/?action=edit&amp;job_id=<?php echo $dummyid;?>" method="post" id="submit-job-form" class="job-manager-form" enctype="multipart/form-data">
									<div id="tabs">
										<div id="message" class="danger"></div>
										<?php if($name=='' || $email=='' || $mobile=='') { ?>
										<div id="tabs-1" <?php if($full) echo 'class="hide"'; ?>>
											<h3>About Yourself</h3>
											<?php if ($name=='') { ?>
											<fieldset class="fieldset-tutor_name">
												<label for="tutor_name">Name</label>
												<div class="field">
													<input type="text" required class="input-text" name="tutor_name" id="tutor_name" placeholder="Your full name" maxlength="">							
												</div>
											</fieldset>
											<?php } ?>
											<?php if ($email=='') { ?>
											<fieldset class="fieldset-application">
												<label for="application">Email Id</label>
												<div class="field">
													<input type="text" required class="input-text" name="application" id="application" placeholder="Enter your Email" maxlength="">							
												</div>
											</fieldset>
											<?php } ?>
											<?php if ($mobile=='') { ?>												
											<fieldset class="fieldset-mobile_num">
												<label for="mobile_num">Mobile number</label>
												<div class="field">
													<small class="description">We respect your privacy. We need your number to send you booking requests.</small>
													<input type="text" required class="input-text" name="mobile_num" id="mobile_num" placeholder="Your 10 digit mobile number" maxlength="10">							
												</div>
											</fieldset>	
											<?php } ?>
										</div>												
										<?php } else { ?>
										<input type="hidden" name="tutor_name" id="tutor_name" value="<?php echo $name; ?>" >							
										<input type="hidden" name="application" id="application" value="<?php echo $email; ?>" >							
										<input type="hidden" name="mobile_num" id="mobile_num" value="<?php echo $mobile; ?>">							
										<?php } ?>
										<div id="tabs-2">
											<h3>About Your Classes</h3>
											<fieldset class="fieldset-job_type">
												<label for="job_type">Location *</label>
												<div class="field required-field">
													<span class="select postform-wrapper">
														<select name="job_type" id="job_type" class="postform">
															<option class="level-0" value="37">Conducted At Student’s Location</option>
															<option class="level-0" value="38">Conducted At Tutor’s Location</option>
														</select>
													</span>
												</div>
											</fieldset>
											<fieldset class="fieldset-job_category">
												<label for="job_category">Category *</label>
												<div class="field required-field">
													<select name='job_category[]' id='job_category' class='job-manager-category-dropdown ' multiple='multiple' data-placeholder='Choose a category&hellip;'>
														<option class="level-0" value="39">Arts</option>
														<option class="level-1" value="62">&nbsp;&nbsp;&nbsp;Art &amp; Craft</option>
														<option class="level-1" value="76">&nbsp;&nbsp;&nbsp;Creative Design</option>
														<option class="level-1" value="75">&nbsp;&nbsp;&nbsp;Drawing</option>
														<option class="level-1" value="64">&nbsp;&nbsp;&nbsp;Phonics</option>
														<option class="level-1" value="61">&nbsp;&nbsp;&nbsp;Theatre</option>
														<option class="level-0" value="42">Class 1 - Class 5</option>
														<option class="level-1" value="85">&nbsp;&nbsp;&nbsp;All Subjects</option>
														<option class="level-1" value="69">&nbsp;&nbsp;&nbsp;Mathematics</option>
														<option class="level-1" value="70">&nbsp;&nbsp;&nbsp;Science</option>
														<option class="level-1" value="82">&nbsp;&nbsp;&nbsp;Social Studies</option>
														<option class="level-0" value="41">Class 11 - Class 12</option>
														<option class="level-1" value="136">&nbsp;&nbsp;&nbsp;Accounts</option>
														<option class="level-1" value="95">&nbsp;&nbsp;&nbsp;Biology</option>
														<option class="level-1" value="135">&nbsp;&nbsp;&nbsp;Business Studies</option>
														<option class="level-1" value="67">&nbsp;&nbsp;&nbsp;Chemistry</option>
														<option class="level-1" value="134">&nbsp;&nbsp;&nbsp;Computer Science</option>
														<option class="level-1" value="131">&nbsp;&nbsp;&nbsp;Economics</option>
														<option class="level-1" value="84">&nbsp;&nbsp;&nbsp;Electronics</option>
														<option class="level-1" value="132">&nbsp;&nbsp;&nbsp;Geography</option>
														<option class="level-1" value="130">&nbsp;&nbsp;&nbsp;History</option>
														<option class="level-1" value="68">&nbsp;&nbsp;&nbsp;Mathematics</option>
														<option class="level-1" value="66">&nbsp;&nbsp;&nbsp;Physics</option>
														<option class="level-1" value="129">&nbsp;&nbsp;&nbsp;Political Science</option>
														<option class="level-1" value="133">&nbsp;&nbsp;&nbsp;Psychology</option>
														<option class="level-0" value="110">Class 6 - Class 8</option>
														<option class="level-1" value="124">&nbsp;&nbsp;&nbsp;Mathematics</option>
														<option class="level-1" value="125">&nbsp;&nbsp;&nbsp;Science</option>
														<option class="level-1" value="126">&nbsp;&nbsp;&nbsp;Social Science</option>
														<option class="level-0" value="80">Class 9 - Class 10</option>
														<option class="level-1" value="81">&nbsp;&nbsp;&nbsp;Mathematics</option>
														<option class="level-1" value="127">&nbsp;&nbsp;&nbsp;Science</option>
														<option class="level-1" value="128">&nbsp;&nbsp;&nbsp;Social Science</option>
														<option class="level-0" value="60">Cooking</option>
														<option class="level-1" value="63">&nbsp;&nbsp;&nbsp;Baking</option>
														<option class="level-0" value="40">Dance</option>
														<option class="level-1" value="83">&nbsp;&nbsp;&nbsp;Bharatnatyam</option>
														<option class="level-1" value="116">&nbsp;&nbsp;&nbsp;Bollywood</option>
														<option class="level-0" value="104">Engineering</option>
														<option class="level-1" value="105">&nbsp;&nbsp;&nbsp;Mechanical Engineering</option>
														<option class="level-0" value="121">Environmental Science</option>
														<option class="level-0" value="88">Graduation Classes</option>
														<option class="level-1" value="89">&nbsp;&nbsp;&nbsp;Computer Science</option>
														<option class="level-1" value="119">&nbsp;&nbsp;&nbsp;Geography</option>
														<option class="level-1" value="147">&nbsp;&nbsp;&nbsp;Zoology</option>
														<option class="level-0" value="107">Hobby</option>
														<option class="level-1" value="113">&nbsp;&nbsp;&nbsp;Acrylic painting</option>
														<option class="level-1" value="114">&nbsp;&nbsp;&nbsp;Bottle painting</option>
														<option class="level-1" value="108">&nbsp;&nbsp;&nbsp;Card-making</option>
														<option class="level-1" value="109">&nbsp;&nbsp;&nbsp;Creative-writing</option>
														<option class="level-1" value="112">&nbsp;&nbsp;&nbsp;Glass painting</option>
														<option class="level-1" value="111">&nbsp;&nbsp;&nbsp;Oil painting</option>
														<option class="level-1" value="122">&nbsp;&nbsp;&nbsp;Typing</option>
														<option class="level-0" value="78">Kids</option>
														<option class="level-1" value="79">&nbsp;&nbsp;&nbsp;Play Dates</option>
														<option class="level-0" value="43">Languages</option>
														<option class="level-1" value="137">&nbsp;&nbsp;&nbsp;Chinese / Mandarin</option>
														<option class="level-1" value="47">&nbsp;&nbsp;&nbsp;English</option>
														<option class="level-1" value="48">&nbsp;&nbsp;&nbsp;French</option>
														<option class="level-1" value="49">&nbsp;&nbsp;&nbsp;German</option>
														<option class="level-1" value="50">&nbsp;&nbsp;&nbsp;Hindi</option>
														<option class="level-1" value="103">&nbsp;&nbsp;&nbsp;Japanese</option>
														<option class="level-1" value="96">&nbsp;&nbsp;&nbsp;Kanada</option>
														<option class="level-1" value="51">&nbsp;&nbsp;&nbsp;Sanskrit</option>
														<option class="level-1" value="123">&nbsp;&nbsp;&nbsp;Telugu</option>
														<option class="level-0" value="93">Management</option>
														<option class="level-1" value="92">&nbsp;&nbsp;&nbsp;Accounting</option>
														<option class="level-1" value="90">&nbsp;&nbsp;&nbsp;Business Studies</option>
														<option class="level-1" value="91">&nbsp;&nbsp;&nbsp;Economics</option>
														<option class="level-1" value="145">&nbsp;&nbsp;&nbsp;Entrepreneurship</option>
														<option class="level-1" value="144">&nbsp;&nbsp;&nbsp;International Marketing</option>
														<option class="level-1" value="142">&nbsp;&nbsp;&nbsp;Operations Management</option>
														<option class="level-1" value="143">&nbsp;&nbsp;&nbsp;Organizational Behaviour</option>
														<option class="level-1" value="146">&nbsp;&nbsp;&nbsp;Principles of Management</option>
														<option class="level-1" value="94">&nbsp;&nbsp;&nbsp;Statistics</option>
														<option class="level-0" value="86">Medical</option>
														<option class="level-1" value="87">&nbsp;&nbsp;&nbsp;Bio Chemistry</option>
														<option class="level-0" value="44">Music</option>
														<option class="level-1" value="117">&nbsp;&nbsp;&nbsp;Carnatic</option>
														<option class="level-0" value="99">Programming</option>
														<option class="level-1" value="100">&nbsp;&nbsp;&nbsp;C</option>
														<option class="level-1" value="102">&nbsp;&nbsp;&nbsp;C Plus Plus</option>
														<option class="level-1" value="101">&nbsp;&nbsp;&nbsp;Unix</option>
														<option class="level-0" value="71">Recreation</option>
														<option class="level-1" value="77">&nbsp;&nbsp;&nbsp;Chess</option>
														<option class="level-1" value="115">&nbsp;&nbsp;&nbsp;CrossFit</option>
														<option class="level-1" value="72">&nbsp;&nbsp;&nbsp;Meditation</option>
														<option class="level-1" value="73">&nbsp;&nbsp;&nbsp;Reiki</option>
														<option class="level-1" value="74">&nbsp;&nbsp;&nbsp;Yoga</option>
														<option class="level-0" value="58">Sports</option>
														<option class="level-1" value="120">&nbsp;&nbsp;&nbsp;Tennis</option>
														<option class="level-0" value="45">Technology</option>
														<option class="level-1" value="97">&nbsp;&nbsp;&nbsp;Computer Science</option>
														<option class="level-1" value="98">&nbsp;&nbsp;&nbsp;Java</option>
														<option class="level-1" value="106">&nbsp;&nbsp;&nbsp;Website Design</option>
														<option class="level-0" value="46">Test Prep</option>
														<option class="level-1" value="56">&nbsp;&nbsp;&nbsp;AIPMT</option>
														<option class="level-1" value="148">&nbsp;&nbsp;&nbsp;CFA</option>
														<option class="level-1" value="52">&nbsp;&nbsp;&nbsp;GMAT</option>
														<option class="level-1" value="53">&nbsp;&nbsp;&nbsp;GRE</option>
														<option class="level-1" value="55">&nbsp;&nbsp;&nbsp;IITJEE</option>
														<option class="level-1" value="54">&nbsp;&nbsp;&nbsp;SAT</option>
														<option class="level-0" value="65">Workshop</option>
														<option class="level-1" value="118">&nbsp;&nbsp;&nbsp;Public Speaking</option>
													</select>
												</div>
											</fieldset>
											<fieldset class="fieldset-hourly_rate">
												<label for="hourly_rate">Hourly rate (in ₹) *</label>
												<div class="field required-field">
													<input type="text" class="input-text" name="hourly_rate" id="hourly_rate" placeholder="Enter the hourly rate you wish to charge (in ₹)" value="" maxlength="4" required="">
												</div>
											</fieldset>
											<fieldset class="fieldset-job_title">
												<label for="job_title">Title *</label>
												<div class="field required-field">
													<small class="description">E.g. 'Weekend Meditation Classes for Women', 'Karate Klasses for Kids'</small>
													<input type="text" class="input-text" name="job_title" id="job_title" placeholder="Your profile headline" value="" maxlength="" required="">
												</div>
											</fieldset>
											<fieldset class="fieldset-job_description">
												<label for="job_description">Description *</label>
												<div class="field required-field">
													<textarea cols="20" rows="3" class="input-text" name="job_description" id="job_description" placeholder="Use this space to talk about your experience, class content, the type of students you like to tutor, whether you're a hobbyist or a professional." maxlength="" required=""></textarea>
												</div>
											</fieldset>
											<fieldset class="fieldset-tutor_exp">
												<label for="tutor_exp">Years of experience </label>
												<div class="field ">
													<span class="select null-wrapper">
														<select name="tutor_exp" id="tutor_exp">
															<option <?php if($yoe == '0-2') echo 'selected'; ?> value="0-2">0-2 Years</option>
															<option <?php if($yoe == '2-4') echo 'selected'; ?>  value="2-4">2-4 Years</option>
															<option <?php if($yoe == '4-6') echo 'selected'; ?>  value="4-6">4-6 Years</option>
															<option <?php if($yoe == '6-8') echo 'selected'; ?>  value="6-8">6-8 Years</option>
															<option <?php if($yoe == '8-10') echo 'selected'; ?>  value="8-10">8-10 Years</option>
															<option <?php if($yoe == '10-15') echo 'selected'; ?>  value="10-15">10-15 Years</option>
															<option <?php if($yoe == '15-20') echo 'selected'; ?>  value="15-20">15-20 Years</option>
															<option <?php if($yoe == '20+') echo 'selected'; ?>  value="20+">More than 20 Years</option>
														</select>
													</span>
												</div>
											</fieldset>
											<fieldset class="fieldset-tutor_gender_pref">
												<label for="tutor_gender_pref">Any gender or age preferences?</label>
												<div class="field ">
													<input type="radio" style="margin-left: 5px; margin-right: 5px; width: auto;" data-meta_key="tutor_gender_pref" class="jmfe-radio jmfe-radio-tutor_gender_pref input-radio" name="tutor_gender_pref" id="tutor_gender_pref-Only Females" value="Only Females">Only Females<br>
													<input type="radio" style="margin-left: 5px; margin-right: 5px; width: auto;" data-meta_key="tutor_gender_pref" class="jmfe-radio jmfe-radio-tutor_gender_pref input-radio" name="tutor_gender_pref" id="tutor_gender_pref-Only Males" value="Only Males">Only Males<br>
													<input type="radio" style="margin-left: 5px; margin-right: 5px; width: auto;" data-meta_key="tutor_gender_pref" class="jmfe-radio jmfe-radio-tutor_gender_pref input-radio" name="tutor_gender_pref" id="tutor_gender_pref-Only School Students" value="Only School Students">Only School Students<br>
													<input type="radio" style="margin-left: 5px; margin-right: 5px; width: auto;" data-meta_key="tutor_gender_pref" class="jmfe-radio jmfe-radio-tutor_gender_pref input-radio" name="tutor_gender_pref" id="tutor_gender_pref-Only Adults" value="Only Adults">Only Adults<br>
													<input type="radio" style="margin-left: 5px; margin-right: 5px; width: auto;" data-meta_key="tutor_gender_pref" class="jmfe-radio jmfe-radio-tutor_gender_pref input-radio" name="tutor_gender_pref" id="tutor_gender_pref-I don't have any preferences" value="I don't have any preferences">I don't have any preferences<br>
													<small data-meta_key="tutor_gender_pref" alt="Clear Selection" class="jmfe-clear-radio jmfe-clear-radio-tutor_gender_pref" style="margin-left: 5px; cursor: pointer;">
														<span class="dashicons dashicons-dismiss" style="vertical-align: middle;"></span>
													</small>
												</div>
											</fieldset>
										</div>
										<div id="tabs-3">						
											<fieldset class="fieldset-job_hours">
												<label for="job_hours">Hours of Operation</label>
												<div class="field">
													<table>
														<tbody><tr>
															<th width="50%">&nbsp;</th>
															<th align="left">Open</th>
															<th align="left">Close</th>
														</tr>

														<tr>
															<td align="left">Monday</td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[1][start]" value="" autocomplete="off"></td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[1][end]" value="" autocomplete="off"></td>
														</tr>
														<tr>
															<td align="left">Tuesday</td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[2][start]" value="" autocomplete="off"></td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[2][end]" value="" autocomplete="off"></td>
														</tr>
														<tr>
															<td align="left">Wednesday</td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[3][start]" value="" autocomplete="off"></td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[3][end]" value="" autocomplete="off"></td>
														</tr>
														<tr>
															<td align="left">Thursday</td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[4][start]" value="" autocomplete="off"></td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[4][end]" value="" autocomplete="off"></td>
														</tr>
														<tr>
															<td align="left">Friday</td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[5][start]" value="" autocomplete="off"></td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[5][end]" value="" autocomplete="off"></td>
														</tr>
														<tr>
															<td align="left">Saturday</td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[6][start]" value="" autocomplete="off"></td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[6][end]" value="" autocomplete="off"></td>
														</tr>
														<tr>
															<td align="left">Sunday</td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[0][start]" value="" autocomplete="off"></td>
															<td align="left" class="business-hour"><input type="text" class="timepicker ui-timepicker-input" name="job_hours[0][end]" value="" autocomplete="off"></td>
														</tr>
													</tbody></table>
												</div>
											</fieldset>
											<fieldset class="fieldset-job_location">
												<label for="job_location">Address *</label>
												<div class="field required-field">
													<input type="text" class="input-text" name="job_location" id="job_location" placeholder="e.g. ABC Apartments, Indiranagar, Bangalore" value="<?php echo $address; ?>" maxlength="" required="">
												</div>
											</fieldset>
											<fieldset class="fieldset-company_website">
												<label for="company_website">PIN code *</label>
												<div class="field required-field">
													<small class="description">Not sure about your PIN code? Find it <a href="http://tiptapgo.co/bangalore-pincodes/" target="_blank">here</a></small><input type="text" class="input-text" name="company_website" id="company_website" placeholder="Your 6 digit area code" value="<?php echo $pincode; ?>" maxlength="6" required="">
												</div>
											</fieldset>
											<p>
												<input type="hidden" name="job_manager_form" value="edit-job">
												<input type="hidden" name="job_id" value="<?php echo $dummyid;?>">
												<input type="hidden" name="step" value="0">
												<input type="submit" name="submit_job" id="submit" class="button" value="Add Class">		
											</p>
										</div>
									</div>
								</form>
								<?php } ?>
							</aside>
						</div>
					</div>
				</article>
			</main>
		</div>
	</div>
</div>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/geocomplete/1.6.5/jquery.geocomplete.min.js"></script>
<script type="text/javascript">
	jQuery(window).load(function(){
		var rupee = /^(?:0|[1-9]\d*)(?:\.(?!.*000)\d+)?$/;
		jQuery(function(){jQuery(".job-manager-category-dropdown").chosen({search_contains:!0})});
		jQuery(function(a){a(".jmfe-radio").click(function(){var b=a(this).data("meta_key");a(".jmfe-clear-radio-"+b).fadeIn("slow")}),a(".jmfe-clear-radio").click(function(){var b=a(this).data("meta_key");a(".jmfe-radio-"+b).prop("checked",!1),a(".jmfe-clear-radio-"+b).fadeOut("slow")})});
		jQuery(document).ready(function(){jQuery("body").on("click",".job-manager-remove-uploaded-file",function(){return jQuery(this).closest(".job-manager-uploaded-file").remove(),!1})});
		jQuery('#job_location').geocomplete();
		jQuery('#submit').click(function(e) {
			var flag = 0;
			jQuery('#tabs-1,#tabs-2,#tabs-3').find('input').each(function(){
				if(jQuery(this).val()=='' && jQuery(this).prop('required')) {
					e.preventDefault();
					jQuery(this).css({"border-color":"#f00"});
					jQuery(this).focus();
				}
			})
			if(jQuery('#job_category').val()==null){
				e.preventDefault();
				jQuery('.default').css({"border-color":"#f00"});
				jQuery('.default').focus();

			}
			if(jQuery('#job_description').val()==""){
				e.preventDefault();
				jQuery('#job_description').css({"border-color":"#f00"});
				jQuery('#job_description').focus();
			}			
			if(!rupee.test(jQuery('#hourly_rate').val())) {
				e.preventDefault();
				jQuery('#hourly_rate').css({"border-color":"#f00"});
				jQuery('#hourly_rate').focus();	
			}	

		});
		setInterval(function(){
			jQuery('input').not('#hourly_rate').each(function(){
				if(jQuery(this).val()!='' && jQuery(this).prop('required')) {
					jQuery(this).css({"border-color":"#ebeef1"});
				}
			});	
			if(jQuery('#job_category').val()!=null){
				jQuery('.default').css({"border-color":"#ebeef1"});
			}			
			if(rupee.test(jQuery('#hourly_rate').val())) {
				jQuery('#hourly_rate').css({"border-color":"#ebeef1"});
			}
			if(jQuery('#job_description').val()!=""){
				jQuery('#job_description').css({"border-color":"#ebeef1"});
			}								
		}, 100);					
	});
</script>
<?php get_footer(); ?>