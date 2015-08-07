<?php
/**
 * Lost password form
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(isset($_GET['resetform']) && isset($_GET['resetform'])!=''){
	$reset = trim(stripslashes($_GET['resetform']));

	if((string)$reset == 'true' && !is_user_logged_in()){
		$heading = "Reset your Password";
		$args['form'] = 'lost_password';
	}
	else if((string)$reset == 'true' && is_user_logged_in()){
		header("location: http://tiptapgo.co/my-account/change-password/");
		die();
	}
}
else if('lost_password' != $args['form']){
	$heading = "Reset your Password";
}
else{
	$heading = "Forgot your Password?";
}


?>

<style type="text/css">
	.loginhead{
		text-align: center;
		font-size: 18px;
	}
	.loginhead h2 {
		padding: 10px 0 40px;
		margin: 0;
		font-size: 20px;
	}
	<?php if( 'lost_password' != $args['form'] ) { ?>
	.signup{
		top: 100% !important;
		font-size: 16px;
		padding: 30px;
	}
	<?php } else { ?>
	.signup{
		top: 95% !important;
		font-size: 16px;
		padding: 30px;
	}
	<?php  } ?> 
	input[type=password]{
		width: 100%;
	}
	.logwrapper{
		position:relative;
		min-height:600px;
		width:100%;
		margin-top:40px;
	}
	
	.logwrapper aside {
		position: absolute;
		top: 50%;
		left: 50%;
		min-width:380px;
		width:380px;
		-ms-transform: translate(-50%, -50%);
		-moz-transform: translate(-50%, -50%);
		-webkit-transform: translate(-50%, -50%);
		transform: translate(-50%, -50%);
		z-index: 10;
		margin-top: -100px;
	}
	.forgot{
		text-align:center;
		padding-top: 35px;
	}
	@media (max-width: 480px){
		.logwrapper aside {
			min-width: 300px;
			width: 300px;
		}
	}
</style>
<script type="text/javascript">
	document.onkeydown = checkKey;
	function checkKey(e) {
		e = e || window.event;
		if (e.keyCode == '13') 
			jQuery("#submit").click();
	}
	jQuery(window).load(function(){
		mixpanel.track("signup_from_signin");
	})
</script>
<?php wc_print_notices(); ?>

<div class="col-md-5 col-xs-12 col-sm-8 col-lg-8 logwrapper">
	<aside class="widget">
		<div class="loginhead"><h2><?php echo $heading; ?></h2></div>

		<form method="post" class="lost_reset_password">

			<?php if( 'lost_password' == $args['form'] ) : ?>

				<p><?php echo apply_filters( 'woocommerce_lost_password_message', __( 'Enter your email address below and we will send you the password reset instructions.', 'woocommerce' ) ); ?></p>

				<div class="form-row form-row-wide"><!--<label for="user_login"><?php _e( 'Username or email', 'woocommerce' ); ?></label> --><input class="input-text" type="text" name="user_login" placeholder="email" id="user_login" /></div>

			<?php else : ?>

				<p><?php echo apply_filters( 'woocommerce_reset_password_message', __( 'Enter a new password below.', 'woocommerce') ); ?></p>

				<div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
					<p>
					<label for="password_1"><?php _e( 'New password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="password" class="input-text" name="password_1" id="password_1" />
					</p>
				</div>
				<div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
					<p>
					<label for="password_2"><?php _e( 'Re-enter new password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="password" class="input-text" name="password_2" id="password_2" />
					</p>
				</div>

				<input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
				<input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />

			<?php endif; ?>

			<div class="clear"></div>
			<div class="forgot">
				<p class="form-row">
					<input type="hidden" name="wc_reset_password" value="true" />
					<input id="submit" type="submit" class="button" value="<?php echo 'lost_password' == $args['form'] ? __( 'Send me reset instructions', 'woocommerce' ) : __( 'Save', 'woocommerce' ); ?>" />
				</p>
			</div>

			<?php wp_nonce_field( $args['form'] ); ?>

		</form>
	</aside>
	<aside class="widget signup">
		<div class="loginhead">
			<div>Don't have a<br> TipTapGo! Account?</div>
			<a style="color: #77c04b; text-decoration: underline" href="http://tiptapgo.co/sign-up/">Sign up today.</a>
		</div>
	</aside>
</div>