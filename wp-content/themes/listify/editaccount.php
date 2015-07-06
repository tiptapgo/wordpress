<?php
/**
* Template Name: edit account
*
* @package Listify
*/
get_header();
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
while ( $jobs->have_posts() ) {
    $jobs->the_post();
	$listid = get_the_ID();
}	

$name = get_post_meta((int)$listid,'_tutor_name', true);

$gender = strtolower(get_post_meta((int)$listid,'_tutor_gender', true));

$dob = get_post_meta((int)$listid,'_tutor_dob', true);

$email = get_post_meta((int)$listid,'_application',true);

$phone = get_post_meta((int)$listid,'_mobile_num',true);

$bio = get_post_meta((int)$listid,'_tutor_bio',true);

?>

<?php wc_print_notices(); ?>

<?php if(is_user_logged_in()) { ?>
<style type="text/css">
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
						<h2>Edit Basic Account Details</h2>
						<form method="POST" action="<?php echo get_template_directory_uri(); ?>/edit-account.php" method="post" id="submit-job-form" class="job-manager-form" enctype="multipart/form-data">
							<input type="hidden" value="<?php echo (int)$listid; ?>" id="listid" />
							<fieldset class="fieldset-tutor_name">
								<label for="tutor_name">Your Name *</label>
								<div class="field required-field">
									<input type="text" class="input-text" name="tutor_name" value="<?php echo $name; ?>" id="tutor_name" placeholder="<?php echo $name; ?>" value="" maxlength="" required />
								</div>
							</fieldset>
							<fieldset class="fieldset-application">
								<label for="application">Your email *</label>
								<div class="field required-field">
									<input type="text" class="input-text" name="application" id="application" placeholder="<?php echo $email; ?>" value="<?php echo $email; ?>" maxlength="" required />
									<small class="description">Your email address is your TipTapGo! username.</small>
								</div>
							</fieldset>
							<fieldset class="fieldset-mobile_num">
								<label for="mobile_num">Your Mobile number *</label>
								<div class="field required-field">
									<input type="text" class="input-text" name="mobile_num" id="mobile_num" placeholder="<?php echo $phone; ?>" value="<?php echo $phone; ?>" maxlength="10" required />
									<small class="description">We respect your privacy. We need your number to send you booking requests.</small>
								</div>
							</fieldset>
							<fieldset class="fieldset-tutor_bio">
								<label for="tutor_bio">Your Bio </label>
								<div class="field">
									<textarea cols="20" rows="3" class="input-text" name="tutor_bio" id="tutor_bio" value="<?php echo $bio; ?>" placeholder="<?php echo $bio; ?>" maxlength="" required><?php echo $bio; ?></textarea>
									<small class="description">What would you like your future students to know about you?</small>
								</div>
							</fieldset>
							<fieldset class="fieldset-tutor_gender">
								<label for="tutor_gender">Gender *</label>
								<div class="field">
									<select name="tutor_gender" id="tutor_gender" required>
										<option value="Male" <?php if($gender=='male') echo 'selected="selected"' ?>>Male</option>
										<option value="Female"<?php if($gender=='female') echo 'selected="selected"' ?> >Female</option>
									</select>
								</div>
							</fieldset>
							<fieldset class="fieldset-tutor_submit">
								<div class="field">
									<input type="submit" class="button" name="submit" id="submit" />
								</div>
							</fieldset>
						</form>
						<br>
						<h2>Change User Avatar</h2>
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