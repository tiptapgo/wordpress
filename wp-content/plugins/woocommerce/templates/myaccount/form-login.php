<?php
/**
 * Login Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if((isset($_GET['id']) && isset($_GET['key'])) || (isset($_POST['id']) && isset($_POST['key']))){

	$active = false;
	$acdone = false; 
	$ud = trim(stripcslashes($_GET['id']));
	if($ud =='')
		$ud = trim(stripcslashes($_POST['id']));

	$status = trim(stripcslashes($_GET['key']));
	if($status =='')
		$status = trim(stripcslashes($_POST['key']));

	$curr = get_user_meta($ud, 'active', true);

	$curruser = get_userdata( $ud );
	if((string)$status == "true"){
		if($curr == "active"){
			$active = true;
			$acText = "Your TipTapGo! Account is Already Activated";
		} else if ((string)$status == "true") {
			update_user_meta($ud, 'active', "active");
			$acText = "Your TipTapGo! Account is Activated";
			$active = true;
			$acdone = true;
		}
	}
	else if((string)$status == "false" && $curr != "active"){
		$active = true;
		$keyfake = true;
		$acText = "Your TipTapGo! Account Cannot Be Activated";
	}
	else if((string)$status == "false" && $curr == "active"){
		$active = true;
		$keyfake = false;
		$acText = "Your TipTapGo! Account is Already Activated";
	}	
}

?>

<style type="text/css">
	.loginhead{
		text-align: center;
		font-size:18px;
	}
	.loginhead h2 {
		padding: 10px 0 40px;
		margin: 0;
		font-size: 20px;
	}
	.signup{
		top: 95% !important;
		font-size: 16px;
		padding: 30px;
	}
	.logwrapper{
		position:relative;
		min-height:700px;
		width:100%;
		margin-top:160px;
	}
	.activation {
		position: absolute;
		color: #fff;
		padding: 10px;
		background-color: #05abf2;
		width: 100%;
		top: 70px;
		left: 0;
		text-align: center;
		font-size: 20px;
	}	
	
	.logwrapper aside {
		position: absolute;
		top: 50%;
		left: 50%;
		min-width:380px;
		-ms-transform: translate(-50%, -50%);
		-moz-transform: translate(-50%, -50%);
		-webkit-transform: translate(-50%, -50%);
		transform: translate(-50%, -50%);
		z-index: 10;
		margin-top: -100px;
	}
	.logwrapper img{
		z-index: 5;
		position: absolute;
		left: 50%;
		-ms-transform: translate(-50%, 50%);
		-moz-transform: translate(-50%, 50%);
		-webkit-transform: translate(-50%, 50%);
		transform: translate(-50%, 50%);
		top: -50%;
		width: 350px;
		height: 380px;
	}
	.signin{
		text-align:center;
		padding:20px 0 35px;
	}
	<?php if($keyfake == true){ ?>
		.activation{
			background-color:#ff8585 !important;
		}

		<?php } ?>	
		<?php if($active == true){ ?>	
			@media (max-width: 1008px) and (min-width: 992px){
				.logwrapper{
					margin-top:200px;
				}
			}
			@media (max-width: 991px){
				.logwrapper{
					margin-top:160px;
				}
				.activation{
					top:128px;
				}
			}
			@media (max-width: 768px){
				.logwrapper{
					margin-top:200px;
				}		
			}
			<?php } ?>	
			@media (max-width: 480px){
				.logwrapper aside {
					min-width: 300px;
					width: 300px;
				}
				.logwrapper img {
					-ms-transform: translate(-50%,80%);
					-moz-transform: translate(-50%,80%);
					-webkit-transform: translate(-50%,80%);
					transform: translate(-50%,80%);
					height: 300px;
				}		
			}	
		</style>

		<script type="text/javascript">
			jQuery('.login').submit(function(){
				mixpanel.track("signin", {
					"email": "<?php echo $curruser->user_email; ?>"
				});
			})
			document.onkeydown = checkKey;
			function checkKey(e) {
				e = e || window.event;
				if (e.keyCode == '13') 
					jQuery("#submit").click();
			}
			jQuery("#signuplink").click(function(){
				mixpanel.track("signup_from_signin");
			})	
			<?php 
			if($acdone == true){ ?>
				mixpanel.identify(<?php echo $curruser->user_email; ?>);
				mixpanel.people.set({
					"$email_verified": true
				});
				<?php } ?>
			</script>

			<?php wc_print_notices(); ?>

			<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

			<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

				<div class="col2-set" id="customer_login">

					<div class="col-1">

					<?php endif; ?>
					<?php if($active == true){ ?>
					<div class="activation"><?php echo $acText;?></div>
					<?php } ?>
					<div class="col-md-8 col-xs-12 col-sm-8 col-lg-8 logwrapper">
						<img src="http://tiptapgo.co/wp-content/uploads/2015/06/Elgo-Happy-e1434234434177.png" alt="elgo">
						<aside class="widget">

							<div class="loginhead">Sign in to<br><h2>TipTapGo!</h2></div>

							<form method="post" class="login">

								<?php do_action( 'woocommerce_login_form_start' ); ?>

								<p class="form-row form-row-wide">
									<!--<label for="username"><?php _e( 'Username or email address', 'woocommerce' ); ?> <span class="required">*</span></label>-->
									<input type="text" class="input-text" name="username" id="username" placeholder="Username or email" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
								</p>
								<p class="form-row form-row-wide">
									<!--<label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label> -->
									<input class="input-text" type="password" name="password" placeholder="Password" id="password" />
								</p>

								<?php do_action( 'woocommerce_login_form' ); ?>
								<div class="signin">
									<p class="form-row">
										<?php wp_nonce_field( 'woocommerce-login' ); ?>
										<input type="submit" id="submit" class="button" name="login" value="<?php _e( 'Sign in', 'woocommerce' ); ?>" />

									</p>
								</div>
								<p class="lost_password">
									<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php _e( 'Forgot your password?', 'woocommerce' ); ?></a>
								</p>

								<?php do_action( 'woocommerce_login_form_end' ); ?>

							</form>

						</aside>
						<aside class="widget signup">
							<div class="loginhead">
								<div>Don't have a<br> TipTapGo! Account?</div>
								<a id="signuplink" style="color: #77c04b;text-decoration: underline;" href="http://tiptapgo.co/sign-up/">Sign up today.</a>
							</div>
						</aside>

					</div>

					<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

					</div>

					<div class="col-2">

						<h2><?php _e( 'Register', 'woocommerce' ); ?></h2>

						<form method="post" class="register">

							<?php do_action( 'woocommerce_register_form_start' ); ?>

							<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

								<p class="form-row form-row-wide">
									<label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
									<input type="text" class="input-text" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
								</p>

							<?php endif; ?>

							<p class="form-row form-row-wide">
								<label for="reg_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
								<input type="email" class="input-text" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
							</p>

							<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

								<p class="form-row form-row-wide">
									<label for="reg_password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
									<input type="password" class="input-text" name="password" id="reg_password" />
								</p>

							<?php endif; ?>

							<!-- Spam Trap -->
							<div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

							<?php do_action( 'woocommerce_register_form' ); ?>
							<?php do_action( 'register_form' ); ?>

							<p class="form-row">
								<?php wp_nonce_field( 'woocommerce-register' ); ?>
								<input type="submit" class="button" name="register" value="<?php _e( 'Register', 'woocommerce' ); ?>" />
							</p>

							<?php do_action( 'woocommerce_register_form_end' ); ?>

						</form>

					</div>

				</div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
