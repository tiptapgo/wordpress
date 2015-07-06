<?php

/**

 * Job Submission Form

 */

if ( ! defined( 'ABSPATH' ) ) exit;



global $job_manager;

?>

<form action="<?php echo esc_url( $action ); ?>" method="post" id="submit-job-form" class="job-manager-form" enctype="multipart/form-data">



<div id="tabs">
  <ul>
    <li><a href="#tabs-1">1. About Yourself</a></li>
    <li><a href="#tabs-2">2. About Your Classes</a></li>
    <li><a href="#tabs-3">3. Timings and Address</a></li>
  </ul>

  <div id="message" class="danger"></div>

  <!-- Tab panes -->
  <div id="tabs-1">
  	<?php if ( apply_filters( 'submit_job_form_show_signin', true ) ) : ?>
		<?php get_job_manager_template( 'account-signin.php' ); ?>
	<?php endif; ?>

	<?php if ( job_manager_user_can_post_job() ) : ?>
		<!--<h2><?php _e( 'About Yourself', 'wp-job-manager' ); ?></h2> -->
		<!-- Job Information Fields -->
		<div class="fixer" <?php if(!is_user_logged_in()) echo 'style="margin-top:-120px"' ?> >
		<?php do_action( 'submit_job_form_job_fields_start' ); ?>    	
 		<?php foreach ( $job_fields as $key => $field ) {
 			 if($field['priority'] > 0 &&  $field['priority'] < 6) { ?>

					<fieldset class="fieldset-<?php esc_attr_e( $key ); ?>">

						<label for="<?php esc_attr_e( $key ); ?>"><?php echo $field['label'] . apply_filters( 'submit_job_form_required_label', $field['required'] ? '' : ' <small>' . __( '(optional)', 'wp-job-manager' ) . '</small>', $field ); ?></label>

						<div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">

							<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', array( 'key' => $key, 'field' => $field ) ); ?>
								<?php// SKM test - echo $key; ?>
						</div>

					</fieldset>

		<?php } } ?>
		</div>		

    </div>
  <div id="tabs-2">
  		<!--<h2><?php _e( 'About Your Classes', 'wp-job-manager' ); ?></h2> -->
 		<?php foreach ( $job_fields as $key => $field ) {
 			 if($field['priority'] > 5.9 &&  $field['priority'] < 12) { ?>

					<fieldset class="fieldset-<?php esc_attr_e( $key ); ?>">

						<label for="<?php esc_attr_e( $key ); ?>"><?php echo $field['label'] . apply_filters( 'submit_job_form_required_label', $field['required'] ? '' : ' <small>' . __( '(optional)', 'wp-job-manager' ) . '</small>', $field ); ?></label>

						<div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">

							<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', array( 'key' => $key, 'field' => $field ) ); ?>

						</div>

					</fieldset>

		<?php } } ?>
    </div>
  <div id="tabs-3">
  	    <!--<h2><?php _e( 'Timings and Address', 'wp-job-manager' ); ?></h2>-->
 		<?php foreach ( $job_fields as $key => $field ) {
 			 if($field['priority'] > 11 &&  $field['priority'] < 14) { ?>

					<fieldset class="fieldset-<?php esc_attr_e( $key ); ?>">

						<label for="<?php esc_attr_e( $key ); ?>"><?php echo $field['label'] . apply_filters( 'submit_job_form_required_label', $field['required'] ? '' : ' <small>' . __( '(optional)', 'wp-job-manager' ) . '</small>', $field ); ?></label>

						<div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">

							<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', array( 'key' => $key, 'field' => $field ) ); ?>

						</div>

					</fieldset>

		<?php } } ?>
		<?php do_action( 'submit_job_form_job_fields_end' ); ?>

		<!-- Company Information Fields -->

		<?php if ( $company_fields ) : ?>

			<!-- <h2><?php _e( 'Company Details', 'wp-job-manager' ); ?></h2> -->



			<?php do_action( 'submit_job_form_company_fields_start' ); ?>



			<?php foreach ( $company_fields as $key => $field ) : ?>

				<fieldset class="fieldset-<?php esc_attr_e( $key ); ?>">

					<label for="<?php esc_attr_e( $key ); ?>"><?php echo $field['label'] . apply_filters( 'submit_job_form_required_label', $field['required'] ? '' : ' <small>' . __( '(optional)', 'wp-job-manager' ) . '</small>', $field ); ?></label>

					<div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">

						<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', array( 'key' => $key, 'field' => $field ) ); ?>

					</div>

				</fieldset>

			<?php endforeach; ?>



			<?php do_action( 'submit_job_form_company_fields_end' ); ?>

		<?php endif; ?>



		<p>

			<input type="hidden" name="job_manager_form" value="<?php echo $form; ?>" />

			<input type="hidden" name="job_id" value="<?php echo esc_attr( $job_id ); ?>" />

			<input type="hidden" name="step" value="<?php echo esc_attr( $step ); ?>" />
		<?php if(is_user_logged_in()): ?>
			<input type="submit" name="submit_job" class="button" value="Submit Listing" />		
		<?php else: ?>			
			<input type="submit" name="submit_job" class="button" value="Create Account" />
		<?php endif; ?>
		</p>


	<?php else : ?>



		<?php do_action( 'submit_job_form_disabled' ); ?>



	<?php endif; ?>		
    </div>

</div>

</form>
