<?php
/**
 * Template Name: signup
 * @package Listify
 */

if(is_user_logged_in()){
	header("location: http://tiptapgo.co/my-account/");
	die();
}

get_header();

?>

<style type="text/css">
	.text-center{
		text-align: center;
	}
	.topbright{
		background: #05abf2;
		font-size: 20px;
		line-height:30px;
		padding:20px 0;
		color: #fff;
		text-align:center;
	}
	.tophead{
		font-size: 35px;
		padding: 10px 0 20px;
	}
	.signuphead{
		height:65px;
		padding:15px 0;
		color:#fff;
		font-size:18px;
		text-align:center;
		background:#3396d1;
		max-width: 380px;
	}
	.leftfeatures{
		margin-top: 170px;
		right: -120px;
	}
	.leftfeatures img{
		position: absolute;
		width: 50px;
		height: 50px;
		top: 50%;
		left: 50%;
		margin: -25px 0 0 -25px;
	}
	.logwrapper{
		position:relative;
		min-height:900px;
		margin-top:200px;
		right: -40px;
	}

	.circle{
		width:80px;
		height:80px;
		background:#3396d1;
		border-radius: 50%;
		margin-top: 25px;
	}
	
	.logwrapper aside {
		position: absolute;
		top: 50%;
		min-width:380px;
		max-width:380px;
		-ms-transform: translate(0, -50%);
		-moz-transform: translate(0, -50%);
		-webkit-transform: translate(0, -50%);
		transform: translate(0, -50%);
		z-index: 10;
		padding:0;
		margin: -50px 15px 0;
		min-height: 400px;
	}
	.logwrapper img{
		z-index: 5;
		position: absolute;
		-ms-transform: translate(10%, 50%);
		-moz-transform: translate(10%, 50%);
		-webkit-transform: translate(10%, 50%);
		transform: translate(10%, 50%);
		top: -45%;
		width: 350px;
		height: 380px;
	}

	input {
		width: 100%;
	}
	fieldset{
		border: 0;
		padding: 25px 20px 0px;
		margin: 0;
	}

	input[type=submit]{
		margin: 0 0 25px;
	}

	select, .select{
		width: 100%;
	}
	p{
		font-size:14px;
		width:60%;
	}
	h6{
		font-size: 18px;
		line-height: 0.7;
	}
	.age-addon{
	  padding: 6px 120px 6px 20px;
	  background-color: #fff;
	  color: #555;
	  border: 0;		
	}
	.datepicker{
		z-index: 10;
		position: absolute;
		background-color: #fff;
		display:none;
	}
	.description{
		padding: 5px;
		margin: 0 0 -20px;
		font-size: 12px;
		width: 300px;
		color: #555;
		font-style: italic;
	}

	#success{
		padding:60px 0 !important;
		height:600px;
		margin:100px 0;
	}
	#success .button{
		position: relative !important;
		width:initial !important;
		margin: 0 !important;
	}
	.elgo1{
		border-radius: 50%;
		border: 2px solid #F0F8FF;
		background-color: #FFC107;
		max-width: 180px;
		max-height: 180px;
	}
	.ortext{
		margin: 10px 0;
		font-size:14px;
	}

	#form-views{
		background-color: transparent;
		border: none;
		border-radius: 0;
		margin-top:0 !important;
	}

	.elgo{
		display: none;
	}

	.call-to-action{
		display: none;
	}

	@media (max-width: 1200px) and (min-width: 991px){
		.logwrapper{
			right: 0 !important;
		}
		.leftfeatures {
			left: 50px;
		}
	}

	@media (max-width: 1200px) and (min-width: 768px){
		.logwrapper{
			right: 30px;
		}
		.leftfeatures {
			left: -10px;
		}
		.circletext {
			margin: -10px 0 0 90px;
		}
		.circle{
			position: absolute;
		}		
	}

	@media (max-width: 767px){
		.circletext {
			margin-top: 140px;
			text-align: center;
		}
		.circle {
			left: 50%;
			margin-left: -40px;
			position: absolute;
		}		
		.tophead {
			font-size: 24px;
			padding: 10px 0;
		}
		.topbright {
			font-size: 14px;
			line-height: 20px;
			padding: 10px;
		}		
		.elgo1{
			max-width:180px;
			max-height:180px;
		}		
		#success{
			padding:60px 0 !important;
			height:800px;
			margin:50px 0;
		}		
		p{
			width:100%;
		}
		.logwrapper{
			right:0;
			margin-top: 180px;
		}
		.logwrapper aside {
			left:50%;
			min-width:300px;
			-ms-transform: translate(-55%, -50%);
			-moz-transform: translate(-55%, -50%);
			-webkit-transform: translate(-55%, -50%);
			transform: translate(-55%, -50%);
		}
		.leftfeatures{
			position: relative;
			left: 50%;
			max-width:300px;
			-ms-transform: translate(-50%,0);
			-webkit-transform: translate(-50%,0);
			-moz-transform: translate(-50%,0);
			transform: translate(-50%,0);
		}
		.logwrapper img {
			height: 300px;
			left: 50%;
			-ms-transform: translate(-50%,80%);
			-moz-transform: translate(-50%,80%);
			-webkit-transform: translate(-50%,80%);
			transform: translate(-50%,80%);
		}
	}
	@media (max-width: 480px){
		.leftfeatures{
			-ms-transform: translate(-50%,-5%);
			-webkit-transform: translate(-50%,-5%);
			-moz-transform: translate(-50%,-5%);
			transform: translate(-50%,-5%);
		}
		.description{
			width:290px;
		}
		.field{
			width: 90%;
			margin-left: 5%;
		}				
		h6{
			line-height:1.7;
		}
	}
</style>

<?php //echo do_shortcode(['woocommerce_simple_registration']); ?>

<div id="primary">
	<div>
		<main id="main" class="site-main" role="main">
			<article id="success" class="hide widget">
				<div class="row content-area">
					<div class="content-box-inner">		
						<div class="entry-content">
							<div class="col-xs-12 col-lg-2 col-sm-2 col-md-2"></div>	
							<div class="col-xs-12 col-lg-8 col-sm-8 col-md-8" style="text-align:center;font-size:20px">
								<div class="col-xs-12 col-lg-12 col-sm-12 col-md-12">
									<img class="elgo1" src="http://tiptapgo.co/wp-content/plugins/wp-job-manager/assets/images/elgo.png">	
								</div>
								<br><br>
								<div class="col-xs-12 col-lg-12 col-sm-12 col-md-12">
									<strong>Woohoo! You've signed up successfully.<br>Check your inbox to confirm your account.</br></strong>
								</div>	
							</div>
							<div class="col-xs-12 col-lg-2 col-sm-2 col-md-2"></div>
						</div>	
					</div>
				</div>
			</article>
			<article id="init">
				<div>
					<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 topbright">
						<div class="container">
							<div class="tophead"><strong>Become a Tutor<span style="color:#ffc108">preneur</span></strong></div>
							<div>TipTapGo! connects Tutors with Students for Home Classes. <br>List your Classes and grow your Network.</div>
						</div>
					</div>
				</div>
				<div>
					<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
						<div class="container">
							<div class="col-md-6 col-xs-12 col-sm-6 col-lg-6 logwrapper" style="float:right">
								<img src="http://tiptapgo.co/wp-content/uploads/2015/06/Elgo-Happy-e1434234434177.png" alt="elgo">
								<aside class="widget">									

									<form method="post" id="signupform" action="">

										<div>
											<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 signuphead">Get started - it's free!</div>
											<fieldset>
												<div class="field">
													<input type="text" name="signup_name" id="signup_name" placeholder="Full Name">
												</div>
											</fieldset>
											<fieldset>
												<div class="field">
													<input type="email" name="signup_email" id="signup_email" placeholder="Email">
													<div class="description">Your privacy is very important to us. Your email is never made public.</div>
												</div>												
											</fieldset>
											<fieldset>
												<div class="field">
													<input type="password" name="signup_password" id="signup_password" placeholder="Password">
												</div>
											</fieldset>													
											<fieldset>
												<div class="field">
													<div class="input-group">
														<span class="input-group-addon" id="basic-addon1">+91</span>
														<input type="tel" name="signup_mobile" id="signup_mobile" placeholder="Mobile Number" maxlength="10">
													</div>
													<div class="description">Your privacy is very important to us. Your mobile number is never made public.</div>
												</div>												
											</fieldset>											
											<fieldset>
												<div class="field">
													<div class="input-group">
														<input type="text" name="signup_age" id="signup_age" placeholder="Age" maxlength="3">
														<span class="input-group-addon age-addon" id="basic-addon2">years</span>
													</div>
													<input type="hidden" name="signup_dob" id="signup_dob">
												</div>
											</fieldset>
											<fieldset>
												<div class="field">
													<input type="text" name="signup_category" id="signup_category" placeholder="Category">
													<div class="description">What do you like teach? E.g. Mathematics, French, Bharatnatyam.</div>
												</div>
											</fieldset>																							
											<fieldset>
												<div class="field">
													<div class="row">
														<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
															<label for="signup_gender">Gender</label>
														</div>
														<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
															<input type="radio" id="signup_gender" name="signup_gender" value="female" checked="checked"> Female
														</div>
														<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
														<input type="radio" id="signup_gender" name="signup_gender" value="male"> Male
														</div>
													</div>
												</div>
											</fieldset>																
											<fieldset>
												<div class="field">
													<input type="text" name="signup_location" id="signup_location" placeholder="Address">
												</div>
											</fieldset>								
											<fieldset>
												<div class="field">
													<input type="submit" class="button" name="submit" id="submit" value="Sign Up for Free">
												</div>
											</fieldset>																																																						
										</div>	
									</form>
								</aside>
							</div>
							<div class="col-md-6 col-xs-12 col-sm-6 col-lg-6 leftfeatures">
								<div class="row">
									<div class="col-md-2 col-xs-12 col-sm-2 col-lg-2 circle">
										<img src="<?php echo get_template_directory_uri();?>/images/signup1.png" alt="coinsicon" width="80" height="80">
									</div>
									<div class="col-md-10 col-xs-12 col-sm-10 col-lg-10 circletext">
										<h6>You Decide. We Deliver.</h6>
										<p>Charge your best price. Pick a time and location that works for you.</p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2 col-xs-12 col-sm-2 col-lg-2 circle">
										<img src="<?php echo get_template_directory_uri();?>/images/signup2.png" alt="usericon" width="80" height="80"> 
									</div>
									<div class="col-md-10 col-xs-12 col-sm-10 col-lg-10 circletext">
										<h6>You're In Good Company.</h6>
										<p>100s of tutors sign up every month to find students around them.</p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2 col-xs-12 col-sm-2 col-lg-2 circle">
										<img src="<?php echo get_template_directory_uri();?>/images/signup3.png" alt="boardicon" width="80" height="80">
									</div>
									<div class="col-md-10 col-xs-12 col-sm-10 col-lg-10 circletext">
										<h6>Anyone Can Teach.</h6>
										<p>As long as you've skills to share and passion to teach.</p>
									</div>
								</div>																											
							</div>							
						</div>
					</div>
				</div>		
			</article>
		</main>
	</div>
</div>

<?php
function print_my_inline_script() {
	if ( wp_script_is( 'jquery', 'done' ) ) {	?>
	<!--	<link rel='stylesheet' href='<?php // echo get_template_directory_uri();?>/css/datepicker.css' type='text/css' media='all' />
	<script type='text/javascript' src='<?php // echo get_template_directory_uri();?>/js/bootstrap-datepicker.js'></script>-->
	<script type='text/javascript' src='http://cdnjs.cloudflare.com/ajax/libs/geocomplete/1.4/jquery.geocomplete.min.js'></script>
	<script type='text/javascript'>
		rtdata = '';
		jQuery(window).load(function(){
			jQuery('#btn1').click(function(){
				mixpanel.track("signup_success_fb");
			})
			jQuery('#btn2').click(function(){
				mixpanel.track("signup_success_blog");
			})	
			//jQuery('#signup_dob').datepicker();
			var currage = 0;
			setInterval(function(){
				if(jQuery('#signup_age').val() != ''){
					if(parseInt(jQuery('#signup_age').val().match(/[0-9]+/)[0], 10) != currage){
						currage = parseInt(jQuery('#signup_age').val().match(/[0-9]+/)[0], 10);
						var d = new Date();
						var utc = d.setFullYear(d.getUTCFullYear() - currage);
						var utcdob = new Date(d.getTime() + (d.getTimezoneOffset()*60000));
						var istdob = new Date(utcdob.getTime() - ((-5.5*60)*60000));
						var dd = istdob.getDate(); 
						var mm = istdob.getMonth()+1;
						var yyyy = istdob.getFullYear(); 
						jQuery('#signup_dob').val(dd + '/' + mm + '/' + yyyy);
					}
				}
			}, 100);
			jQuery('#signup_location').geocomplete();
			var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
			var re1 = /^[789]\d{9}$/i;
			var datereg = /^([1-9]?\d|100)$/;
			//var datereg =/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;


			var name = jQuery('#signup_name').val();
			var firstName = name.split(' ').slice(0, -1).join(' ');
			var lastName = name.split(' ').slice(-1).join(' ');
			var usrgender = jQuery('#signup_gender').val();
			var email = jQuery('#signup_email').val();
			var mobile = jQuery('#signup_mobile').val();
			//var dob = jQuery('#signup_dob').val();
			var age = jQuery('#signup_age').val();
			var usrlocation = jQuery('#signup_location').val();
			var category = jQuery('#signup_category').val();

			setInterval(function(){
				email = jQuery('#signup_email').val();
				mobile = jQuery('#signup_mobile').val();
				//dob = jQuery('#signup_dob').val();
				age = jQuery('#signup_age').val();			        	
				if(re.test(email) == true){
					jQuery("#signup_email").css({"border-color":"#ebeef1"});
				}
				if(re1.test(mobile) == true){
					jQuery("#signup_mobile").css({"border-color":"#ebeef1"});
				}
				if(datereg.test(age) == true){
					jQuery("#signup_age").css({"border-color":"#ebeef1"});
				}										
			}, 100);	
			ca ='';	
			jQuery("#signupform").submit(function(e) {
				e.preventDefault();
				var emailtest = re.test(email);
				var mobiletest = re1.test(mobile);
				var agetest = datereg.test(age);
				ca = agetest;

				jQuery("#signup_email,#signup_mobile,#signup_age").css({"border-color":"#ebeef1"});
				if (emailtest == false && mobiletest == false && agetest == false)
					jQuery("#signup_email,#signup_mobile,#signup_age").css({"border-color":"#f00"});
				else if (emailtest == true && mobiletest == false && agetest == false)
					jQuery("#signup_mobile,#signup_age").css({"border-color":"#f00"});
				else if (emailtest == false && mobiletest == true && agetest == false)
					jQuery("#signup_email,#signup_age").css({"border-color":"#f00"});
				else if (emailtest == false && mobiletest == false && agetest == true)
					jQuery("#signup_email,#signup_mobile").css({"border-color":"#f00"});
				else if (emailtest == true && mobiletest == true && agetest == false)
					jQuery("#signup_age").css({"border-color":"#f00"});
				else if (emailtest == false && mobiletest == true && agetest == true)
					jQuery("#signup_email").css({"border-color":"#f00"});
				else if (emailtest == true && mobiletest == false && agetest == true)
					jQuery("#signup_mobile").css({"border-color":"#f00"});		        			        
				else {
					jQuery("#signup_email,#signup_mobile,#signup_age").css({"border-color":"#ebeef1"});
					jQuery.ajax({
						url: '<?php echo get_template_directory_uri();?>/signuptest.php',
						type: 'POST',
						contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						dataType: "text",
						data: {'email':jQuery('#signup_email').val()},
						error: function(){
							jQuery('#submit').val('Sign Up for Free').prop('disabled', false);
						},
						success: function(test) {
							if(test == false){
								jQuery('#signup_email').attr('type','text').val("Already Registered").css({"border-color":"#f00"}).css({"color":"#f00"});
								setTimeout(function(){
									jQuery('#signup_email').attr('type','email').css({"border-color":"#ebeef1"}).css({"color":"#717a8f"}).val('');
								}, 2000);
							}
							else if(test == true){
								jQuery('#submit').val('Wait').prop('disabled', true);
								jQuery.ajax({
									url: '<?php echo get_template_directory_uri();?>/signupnew.php',
									type: 'POST',
									contentType: "application/x-www-form-urlencoded; charset=UTF-8",
									dataType: "text",
									data: jQuery("#signupform").serialize(),
									error: function(){
										jQuery('#submit').val('Sign Up for Free').prop('disabled', false);
										console.log('error');
									},
									success: function(mdata) {
										jQuery('#submit').val('Sign Up for Free').prop('disabled', false);
										if(mdata == false){
											jQuery('#submit').val('Sign Up for Free').prop('disabled', false);
										}
										else{
											mdata = mdata.split('@');
											rtdata = mdata;
											jQuery('#init').hide();
											jQuery('#success').show();
											jQuery('main').addClass('container');
											window.scrollTo(0, 0);
											jQuery(function() {
												var this1 = jQuery("#btn2");
												this1.css({'width': jQuery("#btn1").outerWidth() + 'px !important'});
												jQuery(window).resize(function() {
													this1.css({'width': jQuery("#btn1").outerWidth() + 'px !important'});
												});
											});
											var offsetIST = 5.5;
											var d=new Date();
											var utcdate =  new Date(d.getTime() + (d.getTimezoneOffset()*60000));
											var istdate =  new Date(utcdate.getTime() - ((-offsetIST*60)*60000));
											var name1 = jQuery('#signup_name').val();
											var firstName1 = name1.split(' ').slice(0, -1).join(' ');
											var lastName1 = name1.split(' ').slice(-1).join(' ');
											if(firstName1 == ''){
												firstName1 = lastName1;
												lastName1 = '';
											}
											var usrgender1 = jQuery('#signup_gender').val();
											var email1 = jQuery('#signup_email').val();
											var mobile1 = jQuery('#signup_mobile').val();
											var password1 = jQuery("#signup_password").val();
											var dob1 = jQuery('#signup_dob').val();
											var age1 = jQuery('#signup_age').val();
											dob1 = dob1.split('/');
											dob1 = new Date(dob1[2] + '-' + dob1[1] + '-' + dob1[0]);
											var utcdate2 =  new Date(dob1.getTime() + (dob1.getTimezoneOffset()*60000));
											var istdate2 =  new Date(utcdate2.getTime() - ((-offsetIST*60)*60000));
											var usrlocation1 = jQuery('#signup_location').val();
											var category1 = jQuery('#signup_category').val();
											mixpanel.alias(email1)		        				
											mixpanel.identify(email1);
											mixpanel.people.set({
												"$name": name1,
												"$first_name": firstName1,
												"$last_name": lastName1,
												"$email": email1,
												"$phone": mobile1,
												"$profile_type":"Signup",
												"$user_type": "Tutor",
												"$gender": usrgender1,
												"$category": category1,
												"$age": age1,
												"$address": usrlocation1,
												"$userid": mdata[0],
												"$username":mdata[1],
												"$email_verified": false,
												"$phone_verified": false,
												"$first_class_created": false,
												"$signup_time": istdate,
												"$ip": "<?php echo $_SERVER['REMOTE_ADDR']; ?>"
											});	
										}	        					        				
									},
									cache: false
								});
}			
},
cache: false
});
}
});
});			
</script>
<?php
}
}

add_action( 'wp_footer', 'print_my_inline_script' );

get_footer(); ?>
