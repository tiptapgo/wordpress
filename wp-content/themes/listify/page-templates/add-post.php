<?php 
/**
* Template Name: add class
*
* @package Listify
*/
$my_post = array(
	'post_title'    => '(dummy)',
	'post_content'  => '',
	'post_status'   => 'pending',
	'post_author'	=> '',	
	'post_type' => 'job_listing',
	);

$postid = wp_insert_post( $my_post, false);

$jobs = new WP_Query;
$jobs->query( $args );
$listid = 0;
while ( $jobs->have_posts() ) {
	$jobs->the_post();
	if($listid == 0){
		$listid = get_the_ID();
	}
}

$user = wp_get_current_user();

$address = get_post_meta((int)$listid,'_job_location', true);
if($address == '')
	$address = get_cimyFieldValue($user_id,'LOCATION',true);

$name = get_post_meta((int)$listid,'tutor_name', true);
if($name == '')
	$name = $user->display_name;

$pincode = get_post_meta((int)$listid,'_company_website', true);

$mobile = get_post_meta((int)$listid,'_mobile_num',true);
if($mobile == '')
	$mobile = get_cimyFieldValue($user_id,'MOBILE',true);

$dob = get_post_meta((int)$listid,'_tutor_dob', true);
if($dob == '')
	$dob = get_cimyFieldValue($user_id,'DOB',true);

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
								if($postid == 0){ ?>
								<h1>Sorry posts can't be added Right Now</h1>
								<?php }

								else{ ?>
								<form action="/edit-listings/?action=edit&amp;job_id=<?php echo $postid?>" method="post" id="submit-job-form" class="job-manager-form" enctype="multipart/form-data">
									<div id="tabs">
										<div id="message" class="danger"></div>
										<div id="tabs-1" class="hide">
											<div class="fixer">
												<fieldset class="fieldset-tutor_name">
													<label for="tutor_name">Name *</label>
													<div class="field required-field">
														<input type="text" class="input-text" name="tutor_name" id="tutor_name" placeholder="Your full name" value="<?php echo $name; ?>" maxlength="" required="">							
													</div>
												</fieldset>
												<fieldset class="fieldset-application">
													<label for="application">Email Id *</label>
													<div class="field required-field">
														<input type="text" class="input-text" name="application" id="application" placeholder="Enter your Email" value="<?php echo $email; ?>" maxlength="" required="">							
													</div>
												</fieldset>
												<fieldset class="fieldset-mobile_num">
													<label for="mobile_num">Mobile number *</label>
													<div class="field required-field">
														<small class="description">We respect your privacy. We need your number to send you booking requests.</small><input type="text" class="input-text" name="mobile_num" id="mobile_num" placeholder="Your 10 digit mobile number" value="<?php echo $mobile; ?>" maxlength="10" required="">							
													</div>
												</fieldset>
												<fieldset class="fieldset-tutor_dob">
													<label for="tutor_dob">Your Date of Birth</label>
													<div class="field ">
														<input type="text" class="input-text" name="tutor_dob" id="tutor_dob" placeholder="DD/MM/YYYY" value="<?php echo $dob; ?>" maxlength="">								
													</div>
												</fieldset>
												<fieldset class="fieldset-listing_gender">
													<label for="listing_gender">Gender</label>
													<div class="field ">
														<input type="radio" style="margin-left: 5px; margin-right: 5px; width: auto;" data-meta_key="listing_gender" class="jmfe-radio jmfe-radio-listing_gender input-radio" name="listing_gender" id="listing_gender-Female" value="Female">Female<br>
														<input type="radio" style="margin-left: 5px; margin-right: 5px; width: auto;" data-meta_key="listing_gender" class="jmfe-radio jmfe-radio-listing_gender input-radio" name="listing_gender" id="listing_gender-Male" value="Male" checked="checked">Male<br>
														<small data-meta_key="listing_gender" alt="Clear Selection" class="jmfe-clear-radio jmfe-clear-radio-listing_gender" style="margin-left: 5px; cursor: pointer;">
															<span class="dashicons dashicons-dismiss" style="vertical-align: middle;"></span>
														</small>
													</div>
												</fieldset>
												<fieldset class="fieldset-tutor_bio">
													<label for="tutor_bio">Bio</label>
													<div class="field ">
														<small class="description">What would you like your future students to know about you?</small><textarea cols="20" rows="3" class="input-text" name="tutor_bio" id="tutor_bio" placeholder="Enter a description of yourself" maxlength="">Testing the bio field.</textarea>						
													</div>
												</fieldset>
												<fieldset class="fieldset-tutor_high_edu">
													<label for="tutor_high_edu">Highest educational qualification</label>
													<div class="field ">
														<input type="text" class="input-text" name="tutor_high_edu" id="tutor_high_edu" placeholder="Enter your highest educational qualification" value="" maxlength="">								
													</div>
												</fieldset>
											</div>		
										</div>
										<div id="tabs-2">
											<!--<h2>About Your Classes</h2> -->
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
													<select name="job_category[]" id="job_category" class="job-manager-category-dropdown " multiple="multiple" data-placeholder="Choose a category…" style="display: none;">
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
													<div class="chosen-container chosen-container-multi" style="width: 261px;" title="" id="job_category_chosen">
														<ul class="chosen-choices">
															<li class="search-field">
																<input type="text" value="Choose a category…" class="default" autocomplete="off" style="width: 141px;">
															</li>
														</ul>
														<div class="chosen-drop">
															<ul class="chosen-results">
																<li class="active-result level-0" data-option-array-index="0">Arts</li>
																<li class="active-result level-1" data-option-array-index="1">&nbsp;&nbsp;&nbsp;Art &amp; Craft</li>
																<li class="active-result level-1" data-option-array-index="2">&nbsp;&nbsp;&nbsp;Creative Design</li>
																<li class="active-result level-1" data-option-array-index="3">&nbsp;&nbsp;&nbsp;Drawing</li>
																<li class="active-result level-1" data-option-array-index="4">&nbsp;&nbsp;&nbsp;Phonics</li>
																<li class="active-result level-1" data-option-array-index="5">&nbsp;&nbsp;&nbsp;Theatre</li>
																<li class="active-result level-0" data-option-array-index="6">Class 1 - Class 5</li>
																<li class="active-result level-1" data-option-array-index="7">&nbsp;&nbsp;&nbsp;All Subjects</li>
																<li class="active-result level-1" data-option-array-index="8">&nbsp;&nbsp;&nbsp;Mathematics</li>
																<li class="active-result level-1" data-option-array-index="9">&nbsp;&nbsp;&nbsp;Science</li>
																<li class="active-result level-1" data-option-array-index="10">&nbsp;&nbsp;&nbsp;Social Studies</li>
																<li class="active-result level-0" data-option-array-index="11">Class 11 - Class 12</li>
																<li class="active-result level-1" data-option-array-index="12">&nbsp;&nbsp;&nbsp;Accounts</li>
																<li class="active-result level-1" data-option-array-index="13">&nbsp;&nbsp;&nbsp;Biology</li>
																<li class="active-result level-1" data-option-array-index="14">&nbsp;&nbsp;&nbsp;Business Studies</li>
																<li class="active-result level-1" data-option-array-index="15">&nbsp;&nbsp;&nbsp;Chemistry</li>
																<li class="active-result level-1" data-option-array-index="16">&nbsp;&nbsp;&nbsp;Computer Science</li>
																<li class="active-result level-1" data-option-array-index="17">&nbsp;&nbsp;&nbsp;Economics</li>
																<li class="active-result level-1" data-option-array-index="18">&nbsp;&nbsp;&nbsp;Electronics</li>
																<li class="active-result level-1" data-option-array-index="19">&nbsp;&nbsp;&nbsp;Geography</li>
																<li class="active-result level-1" data-option-array-index="20">&nbsp;&nbsp;&nbsp;History</li>
																<li class="active-result level-1" data-option-array-index="21">&nbsp;&nbsp;&nbsp;Mathematics</li>
																<li class="active-result level-1" data-option-array-index="22">&nbsp;&nbsp;&nbsp;Physics</li>
																<li class="active-result level-1" data-option-array-index="23">&nbsp;&nbsp;&nbsp;Political Science</li>
																<li class="active-result level-1" data-option-array-index="24">&nbsp;&nbsp;&nbsp;Psychology</li>
																<li class="active-result level-0" data-option-array-index="25">Class 6 - Class 8</li>
																<li class="active-result level-1" data-option-array-index="26">&nbsp;&nbsp;&nbsp;Mathematics</li>
																<li class="active-result level-1" data-option-array-index="27">&nbsp;&nbsp;&nbsp;Science</li>
																<li class="active-result level-1" data-option-array-index="28">&nbsp;&nbsp;&nbsp;Social Science</li>
																<li class="active-result level-0" data-option-array-index="29">Class 9 - Class 10</li>
																<li class="active-result level-1" data-option-array-index="30">&nbsp;&nbsp;&nbsp;Mathematics</li>
																<li class="active-result level-1" data-option-array-index="31">&nbsp;&nbsp;&nbsp;Science</li>
																<li class="active-result level-1" data-option-array-index="32">&nbsp;&nbsp;&nbsp;Social Science</li>
																<li class="active-result level-0" data-option-array-index="33">Cooking</li>
																<li class="active-result level-1" data-option-array-index="34">&nbsp;&nbsp;&nbsp;Baking</li>
																<li class="active-result level-0" data-option-array-index="35">Dance</li>
																<li class="active-result level-1" data-option-array-index="36">&nbsp;&nbsp;&nbsp;Bharatnatyam</li>
																<li class="active-result level-1" data-option-array-index="37">&nbsp;&nbsp;&nbsp;Bollywood</li>
																<li class="active-result level-0" data-option-array-index="38">Engineering</li>
																<li class="active-result level-1" data-option-array-index="39">&nbsp;&nbsp;&nbsp;Mechanical Engineering</li>
																<li class="active-result level-0" data-option-array-index="40">Environmental Science</li>
																<li class="active-result level-0" data-option-array-index="41">Graduation Classes</li>
																<li class="active-result level-1" data-option-array-index="42">&nbsp;&nbsp;&nbsp;Computer Science</li>
																<li class="active-result level-1" data-option-array-index="43">&nbsp;&nbsp;&nbsp;Geography</li>
																<li class="active-result level-1" data-option-array-index="44">&nbsp;&nbsp;&nbsp;Zoology</li>
																<li class="active-result level-0" data-option-array-index="45">Hobby</li>
																<li class="active-result level-1" data-option-array-index="46">&nbsp;&nbsp;&nbsp;Acrylic painting</li>
																<li class="active-result level-1" data-option-array-index="47">&nbsp;&nbsp;&nbsp;Bottle painting</li>
																<li class="active-result level-1" data-option-array-index="48">&nbsp;&nbsp;&nbsp;Card-making</li>
																<li class="active-result level-1" data-option-array-index="49">&nbsp;&nbsp;&nbsp;Creative-writing</li>
																<li class="active-result level-1" data-option-array-index="50">&nbsp;&nbsp;&nbsp;Glass painting</li>
																<li class="active-result level-1" data-option-array-index="51">&nbsp;&nbsp;&nbsp;Oil painting</li>
																<li class="active-result level-1" data-option-array-index="52">&nbsp;&nbsp;&nbsp;Typing</li>
																<li class="active-result level-0" data-option-array-index="53">Kids</li>
																<li class="active-result level-1" data-option-array-index="54">&nbsp;&nbsp;&nbsp;Play Dates</li>
																<li class="active-result level-0" data-option-array-index="55">Languages</li>
																<li class="active-result level-1" data-option-array-index="56">&nbsp;&nbsp;&nbsp;Chinese / Mandarin</li>
																<li class="active-result level-1" data-option-array-index="57">&nbsp;&nbsp;&nbsp;English</li>
																<li class="active-result level-1" data-option-array-index="58">&nbsp;&nbsp;&nbsp;French</li>
																<li class="active-result level-1" data-option-array-index="59">&nbsp;&nbsp;&nbsp;German</li>
																<li class="active-result level-1" data-option-array-index="60">&nbsp;&nbsp;&nbsp;Hindi</li>
																<li class="active-result level-1" data-option-array-index="61">&nbsp;&nbsp;&nbsp;Japanese</li>
																<li class="active-result level-1" data-option-array-index="62">&nbsp;&nbsp;&nbsp;Kanada</li>
																<li class="active-result level-1" data-option-array-index="63">&nbsp;&nbsp;&nbsp;Sanskrit</li>
																<li class="active-result level-1" data-option-array-index="64">&nbsp;&nbsp;&nbsp;Telugu</li>
																<li class="active-result level-0" data-option-array-index="65">Management</li>
																<li class="active-result level-1" data-option-array-index="66">&nbsp;&nbsp;&nbsp;Accounting</li>
																<li class="active-result level-1" data-option-array-index="67">&nbsp;&nbsp;&nbsp;Business Studies</li>
																<li class="active-result level-1" data-option-array-index="68">&nbsp;&nbsp;&nbsp;Economics</li>
																<li class="active-result level-1" data-option-array-index="69">&nbsp;&nbsp;&nbsp;Entrepreneurship</li>
																<li class="active-result level-1" data-option-array-index="70">&nbsp;&nbsp;&nbsp;International Marketing</li>
																<li class="active-result level-1" data-option-array-index="71">&nbsp;&nbsp;&nbsp;Operations Management</li>
																<li class="active-result level-1" data-option-array-index="72">&nbsp;&nbsp;&nbsp;Organizational Behaviour</li>
																<li class="active-result level-1" data-option-array-index="73">&nbsp;&nbsp;&nbsp;Principles of Management</li>
																<li class="active-result level-1" data-option-array-index="74">&nbsp;&nbsp;&nbsp;Statistics</li>
																<li class="active-result level-0" data-option-array-index="75">Medical</li>
																<li class="active-result level-1" data-option-array-index="76">&nbsp;&nbsp;&nbsp;Bio Chemistry</li>
																<li class="active-result level-0" data-option-array-index="77">Music</li>
																<li class="active-result level-1" data-option-array-index="78">&nbsp;&nbsp;&nbsp;Carnatic</li>
																<li class="active-result level-0" data-option-array-index="79">Programming</li>
																<li class="active-result level-1" data-option-array-index="80">&nbsp;&nbsp;&nbsp;C</li>
																<li class="active-result level-1" data-option-array-index="81">&nbsp;&nbsp;&nbsp;C Plus Plus</li>
																<li class="active-result level-1" data-option-array-index="82">&nbsp;&nbsp;&nbsp;Unix</li>
																<li class="active-result level-0" data-option-array-index="83">Recreation</li>
																<li class="active-result level-1" data-option-array-index="84">&nbsp;&nbsp;&nbsp;Chess</li>
																<li class="active-result level-1" data-option-array-index="85">&nbsp;&nbsp;&nbsp;CrossFit</li>
																<li class="active-result level-1" data-option-array-index="86">&nbsp;&nbsp;&nbsp;Meditation</li>
																<li class="active-result level-1" data-option-array-index="87">&nbsp;&nbsp;&nbsp;Reiki</li>
																<li class="active-result level-1" data-option-array-index="88">&nbsp;&nbsp;&nbsp;Yoga</li>
																<li class="active-result level-0" data-option-array-index="89">Sports</li>
																<li class="active-result level-1" data-option-array-index="90">&nbsp;&nbsp;&nbsp;Tennis</li>
																<li class="active-result level-0" data-option-array-index="91">Technology</li>
																<li class="active-result level-1" data-option-array-index="92">&nbsp;&nbsp;&nbsp;Computer Science</li>
																<li class="active-result level-1" data-option-array-index="93">&nbsp;&nbsp;&nbsp;Java</li>
																<li class="active-result level-1" data-option-array-index="94">&nbsp;&nbsp;&nbsp;Website Design</li>
																<li class="active-result level-0" data-option-array-index="95">Test Prep</li>
																<li class="active-result level-1" data-option-array-index="96">&nbsp;&nbsp;&nbsp;AIPMT</li>
																<li class="active-result level-1" data-option-array-index="97">&nbsp;&nbsp;&nbsp;CFA</li>
																<li class="active-result level-1" data-option-array-index="98">&nbsp;&nbsp;&nbsp;GMAT</li>
																<li class="active-result level-1" data-option-array-index="99">&nbsp;&nbsp;&nbsp;GRE</li>
																<li class="active-result level-1" data-option-array-index="100">&nbsp;&nbsp;&nbsp;IITJEE</li>
																<li class="active-result level-1" data-option-array-index="101">&nbsp;&nbsp;&nbsp;SAT</li>
																<li class="active-result level-0" data-option-array-index="102">Workshop</li>
																<li class="active-result level-1" data-option-array-index="103">&nbsp;&nbsp;&nbsp;Public Speaking</li>
															</ul>
														</div>
													</div>
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
															<option value="0-2">0-2 Years</option>
															<option value="2-4">2-4 Years</option>
															<option value="4-6">4-6 Years</option>
															<option value="6-8">6-8 Years</option>
															<option value="8-10">8-10 Years</option>
															<option value="10-15">10-15 Years</option>
															<option value="15-20">15-20 Years</option>
															<option value="20+">More than 20 Years</option>
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
												<label for="job_hours">Hours of Operation *</label>
												<div class="field required-field">
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
													<small class="description">Not sure about your PIN code? Find it <a href="http://tiptapgo.co/bangalore-pincodes/" target="_blank">here</a></small><input type="text" class="input-text" name="company_website" id="company_website" placeholder="Your 6 digit area code" value="" maxlength="6" required="">
												</div>
											</fieldset>
											<p>
												<input type="hidden" name="job_manager_form" value="edit-job">
												<input type="hidden" name="job_id" value="1200">
												<input type="hidden" name="step" value="0">
												<input type="submit" name="submit_job" class="button" value="Submit Listing">		
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
<?php get_footer(); ?>