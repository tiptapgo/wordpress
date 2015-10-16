<?php
/**
* Template Name: editprofile
*
* @package Listify
*/

if(! is_user_logged_in()){
	header("location: http://tiptapgo.co/my-account");
}
?>
<?php
get_header();
global $current_user;
get_currentuserinfo();
$userid = $current_user->ID;
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
while ( $jobs->have_posts() ) {
	$jobs->the_post();
	if($listid == 0){
		$listid = get_the_ID();
		if(get_post_meta((int)$listid,'_mobile_num',true)!=''){
			break;
		}
		else{
			$listid = 0;
		}
	}
}

$name = get_post_meta((int)$listid,'_tutor_name', true);
if($name ==''){
	$name = $current_user->display_name;
	if($name!=$current_user->first_name.' '.$current_user->last_name && $current_user->last_name!=''){
		$name = $current_user->first_name.' '.$current_user->last_name;
	}
}

$gender = get_post_meta((int)$listid,'_tutor_gender', true);
if($gender == ''){
	$temp = get_cimyFieldValue($userid,'MALE');
	if ($temp == true){
		$gender = "Male";
	}
	$temp = get_cimyFieldValue($userid,'FEMALE');
	if ($temp == true){
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

$address = get_post_meta((int)$listid,'_job_location', true);
if($address == ''){
	$address = get_cimyFieldValue($userid,'LOCATION');
}

$email = get_post_meta((int)$listid,'_application',true);
if($email == ''){
	$email = $current_user->user_email;
}

$phone = get_post_meta((int)$listid,'_mobile_num',true);
if($phone == ''){
	$phone = get_cimyFieldValue($userid,'MOBILE');
}

$bio = get_post_meta((int)$listid,'_tutor_bio',true);
if($bio == ''){
	$bio = $current_user->description;
}

$exp = get_post_meta((int)$listid,'_tutor_exp',true);
if($exp == ''){
	$exp = get_cimyFieldValue($userid,'YOE');
}

$qual = get_post_meta((int)$listid,'_tutor_high_edu',true);
if($qual == ''){
	$qual = get_cimyFieldValue($userid,'QUAL');
}

?>

<?php wc_print_notices();

function print_my_inline_script() {
	$user_ID = get_current_user_id();
	if ( wp_script_is( 'jquery', 'done' ) ) {
		?>
		<script type="text/javascript">
			jQuery('.change-img .button-primary').click(function(){
				mixpanel.track("ppic_save");
			})
			jQuery('#wpua-add-existing').click(function(){
				mixpanel.track("ppic_upload");
			})			
			jQuery("#tutor_location").geocomplete();
			if(jQuery('#tutor_age').val()!=''){
				var currage = parseInt(jQuery('#tutor_age').val().match(/[0-9]+/)[0], 10);
			} else{
				var currage = 0;
			}
			setInterval(function(){
				if(jQuery('#tutor_age').val() != ''){
					if(parseInt(jQuery('#tutor_age').val().match(/[0-9]+/)[0], 10) != currage){
						currage = parseInt(jQuery('#tutor_age').val().match(/[0-9]+/)[0], 10);
						var d = new Date();
						var utc = d.setFullYear(d.getUTCFullYear() - currage);
						var utcdob = new Date(d.getTime() + (d.getTimezoneOffset()*60000));
						var istdob = new Date(utcdob.getTime() - ((-5.5*60)*60000));
						var dd = istdob.getDate(); 
						var mm = istdob.getMonth()+1;
						var yyyy = istdob.getFullYear(); 
						jQuery('#tutor_dob').val(dd + '/' + mm + '/' + yyyy);
					}
				}
			}, 100);					
			jQuery('.fieldset-tutor_name').on("keypress", '#tutor_name' ,function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if (code == 13) {
					e.preventDefault();
					e.stopPropagation();
					jQuery(this).closest('form').submit();
				}
			});
			var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
			var re1 = /^[789]\d{9}$/i;
			//var datereg = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
			var datereg = /^\d+$/;
			jQuery("form").submit(function(e) {
				window.scrollTo(0, 0);				
				var email = jQuery('#application').val();
				var mobile = jQuery('#mobile_num').val();
				var dob = jQuery('#tutor_dob').val();
				var age = jQuery('#tutor_age').val();
				var name = jQuery('#tutor_name').val();
				var bio = jQuery('#tutor_bio').val();
				var gender = jQuery('#tutor_gender').val();
				var exp = jQuery('#tutor_exp').val();
				var qual = jQuery('#tutor_high_edu').val();
				var location = jQuery('#tutor_location').val();


				var emailtest = re.test(email);
				var mobiletest = re1.test(mobile);
				var agetest = datereg.test(age);

				jQuery("#application,#mobile_num,#tutor_age").css({
					"border-color": "#ebeef1"
				});
				if (emailtest == false && mobiletest == false && agetest == false) {
					jQuery("#application,#mobile_num,#tutor_age").css({
						"border-color": "#f00"
					});
					e.preventDefault();
				} else if (emailtest == true && mobiletest == false && agetest == false) {
					jQuery("#mobile_num,#tutor_age").css({
						"border-color": "#f00"
					});
					e.preventDefault();
				} else if (emailtest == false && mobiletest == true && agetest == false) {
					jQuery("#application,#tutor_age").css({
						"border-color": "#f00"
					});
					e.preventDefault();
				} else if (emailtest == false && mobiletest == false && agetest == true) {
					jQuery("#application,#mobile_num").css({
						"border-color": "#f00"
					});
					e.preventDefault();
				} else if (emailtest == true && mobiletest == true && agetest == false) {
					jQuery("#tutor_age").css({
						"border-color": "#f00"
					});
					e.preventDefault();
				} else if (emailtest == false && mobiletest == true && agetest == true) {
					jQuery("#application").css({
						"border-color": "#f00"
					});
					e.preventDefault();
				} else if (emailtest == true && mobiletest == false && agetest == true) {
					jQuery("#mobile_num").css({
						"border-color": "#f00"
					});
					e.preventDefault();
				}
				else{
					var offsetIST = 5.5;
					var d=new Date();
					var utcdate =  new Date(d.getTime() + (d.getTimezoneOffset()*60000));
					var istdate =  new Date(utcdate.getTime() - ((-offsetIST*60)*60000));
					mixpanel.identify(email);
					mixpanel.track("profile_update");
					mixpanel.people.set({
						"$name": name,
						"$email": email,
						"$phone": mobile,
						"$profile_type":"Edit Account",
						"$user_type": "Tutor",
						"$gender": gender,
						"$age": age,
						"$bio": bio,
						"$userid": <?php echo $user_ID; ?>,
						"$years_of_experience": exp,
						"$qualification": qual,						
						"$address": location,
						"$signup_time": istdate,
						"$ip": "<?php echo $_SERVER['REMOTE_ADDR']; ?>"
					});					
				}
			});
setInterval(function() {
	email = jQuery('#application').val();
	mobile = jQuery('#mobile_num').val();
	age = jQuery('#tutor_age').val();
	if (re.test(email) == true) {
		jQuery("#application").css({
			"border-color": "#ebeef1"
		});
	}
	if (re1.test(mobile) == true) {
		jQuery("#mobile_num").css({
			"border-color": "#ebeef1"
		});
	}
	if (datereg.test(age) == true) {
		jQuery("#tutor_age").css({
			"border-color": "#ebeef1"
		});
	}
}, 100);
</script>
<?php
}
}
add_action( 'wp_footer', 'print_my_inline_script' );

if(is_user_logged_in()) { ?>
<style type="text/css">
	input#mobile_num {
		width: 100%;
	}
	#wpua-images-existing {
		display: inline;
		position: relative;
	}
	#wpua-add-existing{
		display: inline;
		background: rgba(0,0,0,0.5);
		padding: 12px 34px;
	}
	#wpua-preview-existing{
		position: absolute;
		left: 100px;
		border: 0;
		top: -30px;
		width:64px;
		height: 64px;
		overflow: hidden;
	}
	#wpua-preview-existing img{
		border:0 !important;
	}
	#wpua-thumbnail-existing, #wpua-preview-existing span, #wpua-remove-button-existing, #wpua-remove-existing, #wpua-undo-button-existing{
		display:none !important;
	}

	.wpua-edit-container, #wpua-add-button-existing, .submit{
		display:inline;
	}
	.submit{
		display: none !important;
	}
	.change-img .submit input{
		padding: 13.5px 15px;
	}
</style>
<div id="primary" class="container">
	<div class="row content-area">
		<main id="main" class="site-main" role="main">
			<article class="page type-page status-publish hentry content-box content-box-wrapper">
				<div class="content-box-inner">
					<div class="entry-content">
						<h2>Edit Profile</h2>
						<form method="POST" action="<?php echo get_template_directory_uri(); ?>/edit-account.php" method="post" id="submit-job-form" class="job-manager-form" enctype="multipart/form-data">							
							<div class="row">
								<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
									<fieldset class="fieldset-tutor_name">
										<label for="tutor_name">Your Name *</label>
										<div class="field required-field">
											<input type="text" disabled class="input-text" name="tutor_name" value="<?php echo $name; ?>" id="tutor_name" placeholder="<?php echo $name; ?>" value="" maxlength="" required />
										</div>
									</fieldset>
								</div>
								<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
									<fieldset class="fieldset-application">
										<label for="application">Your email *</label>
										<div class="field required-field">
											<input type="email" disabled class="input-text" name="application" id="application" placeholder="<?php echo $email; ?>" value="<?php echo $email; ?>" maxlength="" required />
											<small class="description">Your email address is your TipTapGo! username.</small>
										</div>
									</fieldset>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">							
									<fieldset class="fieldset-mobile_num">
										<label for="mobile_num">Your Mobile number *</label>
										<div class="field required-field">
											<input type="tel" class="input-text" name="mobile_num" id="mobile_num" placeholder="<?php echo $phone; ?>" value="<?php echo $phone; ?>" maxlength="10" required />
											<small class="description">We respect your privacy. We need your number to send you booking requests.</small>
										</div>
									</fieldset>
								</div>
								<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
									<fieldset>
										<label for="tutor_gender">Age *</label>
										<div class="field">
											<input type="text" name="tutor_age" id="tutor_age" placeholder="Your Age" value="<?php echo $age; ?>">
											<input type="hidden" name="tutor_dob" id="tutor_dob" value="<?php echo $dob; ?>">
										</div>
									</fieldset>
								</div>	
							</div>								
							<fieldset class="fieldset-tutor_bio">
								<label for="tutor_bio">Your Bio </label>
								<div class="field">
									<textarea cols="20" rows="3" class="input-text" name="tutor_bio" id="tutor_bio" value="<?php echo $bio; ?>" placeholder="<?php echo $bio; ?>" maxlength="" required><?php echo $bio; ?></textarea>
									<small class="description">What would you like your future students to know about you?</small>
								</div>
							</fieldset>
							<div class="row">
								<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">									
									<fieldset class="fieldset-tutor_gender">
										<label for="tutor_gender">Gender</label>
										<div class="field">
											<select name="tutor_gender" id="tutor_gender" required>
												<option value="Male" <?php if($gender=='male') echo 'selected="selected"' ?> >Male</option>
												<option value="Female"<?php if($gender=='female') echo 'selected="selected"' ?> >Female</option>
											</select>
										</div>
									</fieldset>
								</div>
								<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">	
									<fieldset class="fieldset-tutor_exp">
										<label for="tutor_exp">Years of experience </label>
										<div class="field ">
											<select name="tutor_exp" id="tutor_exp">
												<option <?php if($exp == '0-2') echo 'selected'; ?> value="0-2">0-2 Years</option>
												<option <?php if($exp == '2-4') echo 'selected'; ?>  value="2-4">2-4 Years</option>
												<option <?php if($exp == '4-6') echo 'selected'; ?>  value="4-6">4-6 Years</option>
												<option <?php if($exp == '6-8') echo 'selected'; ?>  value="6-8">6-8 Years</option>
												<option <?php if($exp == '8-10') echo 'selected'; ?>  value="8-10">8-10 Years</option>
												<option <?php if($exp == '10-15') echo 'selected'; ?>  value="10-15">10-15 Years</option>
												<option <?php if($exp == '15-20') echo 'selected'; ?>  value="15-20">15-20 Years</option>
												<option <?php if($exp == '20+') echo 'selected'; ?>  value="20+">More than 20 Years</option>
											</select>
										</div>
									</fieldset>
								</div>
							</div>							
							<fieldset class="fieldset-tutor_high_edu" id="address">
								<label for="tutor_high_edu">Highest educational qualification</label>
								<div class="field ">
									<input type="text" class="input-text" name="tutor_high_edu" id="tutor_high_edu" value="<?php echo $qual; ?>" placeholder="Enter your highest educational qualification" value="" maxlength="">
								</div>
							</fieldset>							
							<fieldset>
								<label for="tutor_location">Address * </label>
								<div class="field">
									<input type="text" name="tutor_location" id="tutor_location" placeholder="Address" required value="<?php echo $address; ?>">
								</div>
							</fieldset>
							<fieldset class="fieldset-tutor_submit">
								<div class="field">
									<input type="submit" class="button" name="submit" id="submit" value="Update Profile" />
								</div>
							</fieldset>
						</form>
						<br>
						<h2>Change Profile Picture</h2>
						<div class="change-img"><?php echo do_shortcode('[avatar_upload]'); ?></div>
					</div>
				</div>
			</article>
		</main>
	</div>
</div>
<?php  } else { 
	$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
	$url .= $_SERVER['SERVER_NAME'];
	$url .= $_SERVER['REQUEST_URI'];
	header("Location:".dirname($url)."/my-account/");
	die();
}

?>


<?php get_footer(); ?>    