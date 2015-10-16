<?php
/**
 * Template Name: Account verification
 *
 * @package Listify
 */

get_header();

if(!is_user_logged_in()){
	header("location: ".get_site_url()."/my-account/");
	die();
}

global $current_user;
get_currentuserinfo();
$userid = $current_user->ID;

$verstatus = get_user_meta($userid,'verification_status',true);
if($verstatus != '') {
	$verfile = get_user_meta($userid,'verification_image',true);
	$verfile = wp_get_attachment_link( $verfile , 'medium' );
	$vertype = get_user_meta($userid,'verification_type',true);
}
if(get_user_meta($userid,'bankflag',true) == "true"){
	$bankflag = true;
	$bankername = get_user_meta($userid,'bankername',true);
	$accountno = get_user_meta($userid,'accountno',true);
	$accounttype = get_user_meta($userid,'accounttype',true);
	$ifsc = get_user_meta($userid,'ifsc',true);
	$bankname = get_user_meta($userid,'bankname',true);
} else{
	$bankflag = false;
}


?>

<link type="text/css" media="all" href="http://tiptapgo.co/wp-content/themes/listify/css/account.css" rel="stylesheet" />

<div id="primary" class="container">
	<div class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<article id="init">
					<aside class="widget">
						<?php
						if(isset($_GET['saveerror'])){
							$error1 = trim(stripslashes($_GET['saveerror']));
							if($error1 == "true"){ ?>
							<ul class="woocommerce-error">
								<li>Try Again! Your verification proof could not be saved.</li>
							</ul>
							<?php }	
						}
						if(isset($_GET['deleteerror'])){
							$error2 = trim(stripslashes($_GET['deleteerror']));
							if($error2 == "true"){ ?>
							<ul class="woocommerce-error">
								<li>Try Again! Your verification proof could not be deleted.</li>
							</ul>
							<?php }	
						}
						if(isset($_GET['success'])){
							$success1 = trim(stripslashes($_GET['success']));
							if($success1 == "true"){ ?>
							<div class="woocommerce-message">Your verification proof has been saved successfully.</div>
							<?php }	
						}
						if(isset($_GET['deletesuccess'])){
							$success2 = trim(stripslashes($_GET['deletesuccess']));
							if($success2 == "true"){ ?>
							<div class="woocommerce-message">Your verification proof has been deleted.</div>
							<?php }	
						}
						?>					
						<div class="row">
							<div class="col-md-8 col-xs-12 col-sm-12 col-lg-8 entry-content">
								<h2>Photo ID</h2>
								<p>Security is an important concern. Please verify yourself with a photo ID such as Passport, Income Tax PAN Card, Voter's ID or Driving license.</p> 
								<p>You can upload a scanned file or take a photo through your mobile phone. </p>
							</div>
							<div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">	
								<?php if($verstatus=="") { ?>
								<form id="job_verification" method="POST" enctype="multipart/form-data" action="<?php echo get_template_directory_uri();?>/job_verification.php">
									<fieldset>
										<div class="field row">
											<div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">	
												<select id="job_verification_doc_type" name="job_verification_doc_type">
													<option value="">Select an ID</option>
													<option value="Passport">Passport</option>
													<option value="Income Tax PAN Card">Income Tax PAN Card</option>
													<option value="Voter's ID">Voter's ID</option>
													<option value="Driving license">Driving license</option>
												</select>
											</div>
										</div>
									</fieldset>
									<fieldset>
										<div class="field row">
											<div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">
												<input data-content="Select a file" type="file" accept="image/*" name="job_verification_doc" id="job_verification_doc">
											</div>
											<div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">
												<img src="#" class="hide" id="preview" height="100">
											</div>
										</div>	
									</fieldset>
									<fieldset>
										<div class="field">
											<input type="submit" value="Submit" name="submit" id="submit">
										</div>
									</fieldset>									
								</form>
								<?php } else if($verstatus=='pending') { ?>
								<div class="row">
									<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
										<br>
										<strong>Type</strong> - <?php echo $vertype; ?>		
									</div>
									<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 verdoc">
										<?php echo $verfile; ?>
									</div>
									<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
										<button class="pendingbtn"><i class="fa fa-pencil"></i> Under review</button>
										<button class="deletebtn">Delete</button>
									</div>										
								</div>
								<?php } else if($verstatus=='verified') { ?>
								<div class="row">
									<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
										<br>
										<button class="verifiedbtn"><i class="fa fa-check"></i> You're verified</button>
									</div>									
									<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
										<br>
										<strong>Type</strong> - <?php echo $vertype; ?>		
									</div>
									<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 verdoc">
										<?php echo $verfile; ?>
									</div>									
								</div>
								<?php } ?>
							</div>
						</div>							
					</aside>
					<aside class="widget">
						<div class="row">
							<div class="col-md-8 col-xs-12 col-sm-12 col-lg-8 entry-content">
							<h2>Bank Details</h2>
								<p>When learners book a class, we collect fees on your behalf and transfer it to your account once the class is complete.</p> 
								<p>We process payments within 3 working days from the completion of class.</p>
								<p>For further queries, feel free to write to <a href="mailto:help@tiptapgo.co?Subject=Bank%20details%20query" target="_top">help@tiptapgo.co</a></p>
								<br>
							</div>
							<div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">
								<?php if(!$bankflag) { ?>
								<button class="bankform">Add Bank Account</button>
								<form id="bank_details" method="POST" style="display:none" action="<?php echo get_template_directory_uri();?>/bank_verification.php">
									<fieldset>
										<div class="field row">
											<div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">
												<input class="bankinput" type="text" placeholder="Account Holder Name" name="bank_holder_name" id="bank_holder_name">
											</div>
										</div>
									</fieldset>		
									<fieldset>
										<div class="field row">
											<div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">
												<input class="bankinput" maxlength="20" type="text" placeholder="Bank Account Number" placeholder="" name="bank_account_no" id="bank_account_no">
											</div>
										</div>
									</fieldset>		
									<fieldset>
										<div class="field row">
											<div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">	
												<select id="bank_type" name="bank_type">
													<option value="">Account Type</option>
													<option value="Saving">Saving</option>
													<option value="Current">Current</option>
												</select>
											</div>
										</div>
									</fieldset>	
									<fieldset>
										<div class="field row">
											<div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">
												<input class="bankinput" maxlength="20" type="text" placeholder="IFS Code" name="bank_ifsc" id="bank_ifsc">
											</div>
										</div>
									</fieldset>
									<fieldset>
										<div class="field row">
											<div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">
												<input class="bankinput" type="text" placeholder="Bank Name" name="bank_name" id="bank_name">
											</div>
										</div>
									</fieldset>	
									<fieldset>
										<div class="field">
											<input type="submit" value="Submit" name="submit" id="submitbank">
										</div>
									</fieldset>										
								</form>
								<?php } else { ?>
								<div class="row">
									<div class="col-md-6 col-xs-12"><strong>Account Holder Name</strong></div>
									<div class="col-md-6 col-xs-12 right"><?php echo $bankername; ?></div>			
								</div>	
								<div class="row">
									<div class="col-md-6 col-xs-12"><strong>Bank Account Numbe</strong></div>
									<div class="col-md-6 col-xs-12 right"><?php echo $accountno; ?></div>			
								</div>	
								<div class="row">
									<div class="col-md-6 col-xs-12"><strong>Account Type</strong></div>
									<div class="col-md-6 col-xs-12 right"><?php echo $accounttype; ?></div>			
								</div>	
								<div class="row">
									<div class="col-md-6 col-xs-12"><strong>IFS Code</strong></div>
									<div class="col-md-6 col-xs-12 right"><?php echo $ifsc; ?></div>			
								</div>	
								<div class="row">
									<div class="col-md-6 col-xs-12"><strong>Bank Name</strong></div>
									<div class="col-md-6 col-xs-12 right"><?php echo $bankname; ?></div>			
								</div>																	
								<?php } ?>
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
				jQuery(function() {
					jQuery("#job_verification_doc_type").chosen({
						search_contains: !0,
						width: "100%"
					});
				});
				function readURL(input) {
					if (input.files && input.files[0]) {
						if(input.files[0].type != 'image/png' && input.files[0].type != 'image/jpg' && input.files[0].type != 'image/jpeg' ) {
							jQuery("#job_verification_doc").val('').addClass('error').attr('data-content','Only images allowed');
							jQuery('#preview').attr('src','').addClass('hide');
							setTimeout(function(){
								jQuery('#job_verification_doc').attr('data-content','Select a file').removeClass('error');
							}, 2000);
        				//alert("File doesnt match png or jpg");
        			} else {
        				var reader = new FileReader();
        				reader.onload = function (e) {
        					jQuery('#preview').attr('src', e.target.result).removeClass('hide');
        				}
        				reader.readAsDataURL(input.files[0]);
        			}
        		}
        	}

        	jQuery("#job_verification_doc").change(function(){
        		readURL(this);
        	});			
        	jQuery('.deletebtn').click(function(){
        		if (confirm('Are you sure you want to delete your verification proof?')) {
        			jQuery.ajax({
        				url: '<?php echo get_template_directory_uri();?>/job_verification_reset.php',
        				type: 'POST',
        				contentType: "application/x-www-form-urlencoded; charset=UTF-8",
        				dataType: "text",
        				data: {'id':<?php echo $GLOBALS['userid']; ?>},
        				error: function(){
        					window.location="<?php echo get_site_url().'/account/?deleteerror=true'; ?>";
        				},
        				success: function(test) {
        					if(test.indexOf('Success') != -1){
        						window.location="<?php echo get_site_url().'/account/?deletesuccess=true'; ?>";
        					} else{
        						window.location="<?php echo get_site_url().'/account/?deleteerror=true'; ?>";
        					}
        				},
        				cache: false
        			});
        		} 
        	})
        	jQuery('.bankform').click(function(){
        		jQuery('#bank_details').show();
        		jQuery(this).hide();
        	})
        	jQuery('#submitbank').click(function(e){
    			e.preventDefault();
    			jQuery.ajax({
    				url: '<?php echo get_template_directory_uri();?>/bankadd.php',
    				type: 'POST',
    				contentType: "application/x-www-form-urlencoded; charset=UTF-8",
    				dataType: "text",
    				data: jQuery('#bank_details').serialize(),
    				error: function(){
    					jQuery('#submitbank').prop('disabled',true).value('Error');
						setTimeout(function(){
							jQuery('#submitbank').prop('disabled',false).value('Submit');
						}, 2000);    					
    				},
    				success: function(test) {
    					if(test.indexOf('ERR') == -1){
    						window.location="<?php echo get_site_url().'/account/'; ?>";
    					} else{
	    					jQuery('#submitbank').prop('disabled',true);
	    					jQuery('#submitbank').val('Error');
							setTimeout(function(){
								jQuery('#submitbank').prop('disabled',false)
								jQuery('#submitbank').val('Submit');
							}, 2000);  
    					}
    				},
    				cache: false
    			});
        		return false;
        	})        	
        	jQuery(window).load(function(){
        		setTimeout(function(){
        			jQuery('.woocommerce-message,.woocommerce-error').addClass('hide');
        		}, 3000);
        	})
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'print_my_inline_script' );
?>
<?php get_footer(); ?>