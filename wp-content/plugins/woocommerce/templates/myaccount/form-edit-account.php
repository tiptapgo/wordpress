<?php
/**
 * Edit account form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


?>

<?php wc_print_notices(); ?>

<aside class="widget">

	<form action="" method="post">

		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

		

		<?php 
		global $current_user;
		get_currentuserinfo();
		?>
		
		<?php if ($current_user->first_name == '' || $current_user->last_name == '' || $current_user->first_name == 'NA' || $current_user->last_name == 'NA') {  ?>
		<div class="row">
			<div class="col-md-6 col-sm-12 col-lg-6 col-xs-12">
				<fieldset>
					<p class="form-row form-row-wide">
					<label for="account_first_name">First Name (required)</label>
						<input type="text" class="input-text" name="account_first_name" id="account_first_name" required placeholder="First Name" value="" />
					</p>
				</fieldset>	
			</div>
			<div class="col-md-6 col-sm-12 col-lg-6 col-xs-12">
				<fieldset>
					<p class="form-row form-row-wide">
						<label for="account_last_name">Last Name</label>
						<input type="text" class="input-text" name="account_last_name" id="account_last_name" placeholder="Last Name" value="" />
					</p>
				</fieldset>
			</div>
		</div>
		<?php } else { ?>
		<input type="hidden" class="input-text" name="account_first_name" id="account_first_name" value="<?php echo $current_user->first_name; ?>" />

		<input type="hidden" class="input-text" name="account_last_name" id="account_last_name" value="<?php echo $current_user->last_name; ?>" />

		<?php } ?>
		<input type="hidden" class="input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />


		<fieldset>
			<legend><?php _e( 'Password Change', 'woocommerce' ); ?></legend>

			<p class="form-row form-row-wide">
				<label for="password_current"><?php _e( 'Current Password (required)', 'woocommerce' ); ?></label>
				<input type="password" class="input-text" required name="password_current" id="password_current" />
			</p>
			<p class="form-row form-row-wide">
				<label for="password_1"><?php _e( 'New Password (required)', 'woocommerce' ); ?></label>
				<input type="password" class="input-text" required name="password_1" id="password_1" />
			</p>
			<p class="form-row form-row-wide">
				<label for="password_2"><?php _e( 'Confirm New Password (required)', 'woocommerce' ); ?></label>
				<input type="password" class="input-text" required name="password_2" id="password_2" />
			</p>
		</fieldset>
		<?php wp_nonce_field( 'save_account_details' ); ?>
		<input type="submit" class="button" name="save_account_details" value="<?php _e( 'Change Password', 'woocommerce' ); ?>" />
		<input type="hidden" name="action" value="save_account_details" />

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>

	</form>
</aside>	
