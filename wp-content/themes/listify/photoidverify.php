<?php
/**
 * Template Name: photoidverify
 *
 * @package Listify
 */

get_header();

if(!is_user_logged_in()){
	header("location: ".get_site_url()."/my-account/");
	die();
} else if(!current_user_can( 'manage_options' )){ ?>
	<aside class="widget">
		<ul class="woocommerce-error">
			<li>You are not Authorised.</li>
		</ul>
	</aside>	
 <?php 
 	die();
}

global $current_user;
get_currentuserinfo();
$userid = $current_user->ID;

?>

<style type="text/css">
</style>

<div id="primary" class="container">
	<div class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<article id="init">
					<aside class="widget">
					<?php
						if(isset($_GET['verifyerror'])){
							$error2 = trim(stripslashes($_GET['verifyerror']));
							if($error2 == "true"){ ?>
								<ul class="woocommerce-error">
									<li>Try Again! Unable to verify.</li>
								</ul>
							<?php }	
						}
						if(isset($_GET['success'])){
							$success1 = trim(stripslashes($_GET['success']));
							if($success1 == "true"){ ?>
								<div class="woocommerce-message">Successfully verified!</div>
							<?php }	
						}
					?>					
						<div class="row datarow">								
							<div class="col-md-1 col-xs-12 col-sm-1 col-lg-1">
								<strong>S.No</strong>
							</div>
							<div class="col-md-4 col-xs-12 col-sm-4 col-lg-4">
								<strong>Name</strong>
							</div>
							<div class="col-md-4 col-xs-12 col-sm-4 col-lg-4 verdoc">
								<strong>Photo ID</strong>
							</div>
							<div class="col-md-3 col-xs-12 col-sm-3 col-lg-3">
								<strong>Action</strong>
							</div>
						</div>
						<hr>
							<?php 
							$users = get_users( 'blog_id='.get_current_blog_id().'&orderby=nicename' );
							$ctr = 1;
							foreach ($users as $user) {
								$result1 = get_user_meta($user->ID, 'verification_type', true);
								$result2 = get_user_meta($user->ID, 'verification_image', true);
								$result2 = wp_get_attachment_link( $result2 , 'thumb' );
								$result3 = get_user_meta($user->ID, 'verification_status', true);
								if($result3 == "pending"){ ?>
									<div class="row datarow">								
										<div class="col-md-1 col-xs-12 col-sm-1 col-lg-1">
											<?php echo $ctr; ?>
										</div>
										<div class="col-md-4 col-xs-12 col-sm-4 col-lg-4">
											<a target="_blank" href="<?php echo get_site_url().'/tutor/'.$user->user_login; ?>"><?php echo $user->display_name; ?></a>
										</div>
										<div class="col-md-4 col-xs-12 col-sm-4 col-lg-4 verdoc">
											<?php echo $result2; ?>
										</div>
										<div class="col-md-3 col-xs-12 col-sm-3 col-lg-3">
											<button data-verify="<?php echo $user->ID; ?>">Verify</button>
										</div>
									</div>
									<hr>																													
								<?php
								$ctr++;
								}
							}
							?>
						</div>							
					</aside>	
				</article>
			</div>
		</main>
	</div>
</div>

<?php
function print_my_inline_script() {
	if ( wp_script_is( 'jquery', 'done' ) ) {
	global $current_user;
	get_currentuserinfo();
?>

<script>
	jQuery('button').click(function(){
		if (confirm('Are you sure you want to the approve this verification proof?')) {
			jQuery.ajax({
				url: '<?php echo get_template_directory_uri();?>/job_verification_status.php',
				type: 'POST',
				contentType: "application/x-www-form-urlencoded; charset=UTF-8",
				dataType: "text",
				data: {'id': jQuery(this).data('verify')},
					
				error: function(){
					window.location="<?php echo get_site_url().'/photo-id-verify/?verifyerror=true'; ?>";
				},
				success: function(test) {
					if(test.indexOf('Success') != -1){
						window.location="<?php echo get_site_url().'/photo-id-verify/?success=true'; ?>";
					} else{
						window.location="<?php echo get_site_url().'/photo-id-verify/?verifyerror=true'; ?>";
					}
				},
				cache: false
			});
		} 
	})
</script>
<?php
	}
}
add_action( 'wp_footer', 'print_my_inline_script' );
?>
<?php get_footer(); ?>