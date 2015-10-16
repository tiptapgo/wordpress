<?php
/**
 * Template Name: Ratings
 *
 * @package Listify
 */

get_header();

function stars($field){
	for ($i=1; $i <= 5; $i++){
		echo '<span class="fa fa-star-o ratingstar" data-rate="'.$i.'"></span>';
	}
	echo '<input type="hidden" value="0" class="starval" name="hidden-'.$field.'" id="hidden-'.$field.'">';
}

$listid = (int)trim(stripcslashes($_GET['id']));
if($listid =='')
	$listid = (int)trim(stripcslashes($_POST['id']));

if($listid ==''){
	echo '<main><article><div class="content-area container"><div class="content-box"><aside><h2>No listings found</h2></aside></div></div></article></main>';
}

$revtype = trim(stripcslashes($_GET['type']));
if($revtype=='')
	$revtype = trim(stripcslashes($_POST['type']));

$tutor = get_post_meta($listid,'_tutor_name',true);	

if($listid!='' && $revtype == "listing"){	
	$title = get_the_title($listid);
}
else if($listid!='' && $revtype == "tutor")	{
	$title = $tutor;
}

global $current_user;
get_currentuserinfo();
$userid = $current_user->ID;
$mobile2 = get_cimyFieldValue((int)$current_user->ID,'MOBILE');
if(is_array($mobile2)) //hack to fix wrong output
$mobile2 ='';
$name = $current_user->display_name;
$email = $current_user->user_email;
$tutor_userid = get_post_field( 'post_author', $listid );
$nick = get_user_meta($tutor_userid,'nickname',true);
$profilepic = get_avatar( $tutor_userid , 120 );
?>

<style type="text/css">
	.ratingstar{
		color: #3396d1;
		font-size: 16px;
		margin:0 5px;
	}
	.ratinglabel, .star-wrap{
		padding: 10px 0;
	}
	.text-center{
		text-align:center;
	}
	fieldset{
		border: 0 !important;
	}
	.no-pad{
		padding:0;
	}
	input[type=text]{
		width:100%;
	}
	h4{
		margin: 20px 0;
	}
	h5{
		font-size:12px;
		margin:5px 0;
		display:inline-block;
	}
	h6{
		margin:10px 0;
	}
	.fa-user{
		margin:0 10px;
	}
	.radio-div{
		padding: 5px 15px;
	}
	fieldset{
		margin:0;
	}
	.photofix{
		width:150px;
	}
	#message{
		font-size:20px;
		color:#f00;
		border:1px solid #f00;
		padding:0 20px;
		margin:15px;
	}
	@media(max-width: 768px){
		.ratingstar{
			margin:0 3px;
		}
		.top-info{
			text-align:center;
		}
		.photofix{
			width:100%;
		}		
	}

</style>
<?php if($listid !=''): ?>
	<div id="primary" class="container">
		<div class="content-area">
			<main id="main" class="site-main" role="main">
				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1"></div>
				<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">

					<article>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 hide success-box">
							<aside class="widget text-center">
								<h4>Thanks! Your review has been sent for moderation.
									<br>
									<div style="padding: 30px 0 0;"><a style="width:250px;" class="button" id="btn0" href="http://tiptapgo.co">Continue to TipTapGo!</a></div>
									<div style="padding: 15px 0 0;"><a style="width:250px;background-color:#3b5998 !important;" id="btn1" class="button btn1" href="https://www.facebook.com/tiptapgoco">Follow us on Facebook</a></div>
									<div style="padding: 15px 0 0;"><a style="width:250px;background-color:#d14636 !important;" id="btn2" class="button" href="http://blog.tiptapgo.co">Read our Blogs</a></div>
								</aside>
							</div>
							<div class="content-box">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 top-info">
									<div class="row">
										<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 photofix">
											<?php echo $profilepic; ?>
											<div class="text-center">
												<?php if($tutor!=''): ?>
													<h5><a target="_blank" href="http://tiptapgo.co/profile/?nick=<?php echo $nick; ?>"><?php echo $tutor; ?></a></h5>
												<?php endif; ?>	
											</div>
										</div>	
										<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
											<h4>Please review <strong><?php echo $title; ?></strong></h4>
											<div><h6>Leaving a rating and review helps future students <br>make an educated decision.</h6></div>
										</div>
									</div>
								</div>
								<br><br>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">	
									<form action="<?php echo get_template_directory_uri(); ?>/ratingact.php" method="POST">
										<input type="hidden" name='rev_tutor' id='rev_tutor' value="<?php echo $tutor; ?>">
										<input type="hidden" name='rev_tutorid' id='rev_tutorid' value="<?php echo $listid; ?>">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
												<div class="row">
													<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
														<div class="ratinglabel">Communication</div>
													</div>
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<div id="comm" class="star-wrap row"><?php stars('comm'); ?></div>
													</div>									
												</div>	
												<div class="row">
													<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
														<div class="ratinglabel">Subject Knowledge</div>
													</div>
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<div id="sk" class="star-wrap row"><?php stars('sk'); ?></div>
													</div>									
												</div>	
												<div class="row">
													<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
														<div class="ratinglabel">Teaching Style</div>									
													</div>
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<div id="ts" class="star-wrap row"><?php stars('ts'); ?></div>
													</div>									
												</div>	
												<div class="row">
													<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
														<div class="ratinglabel">Discipline</div>
													</div>
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<div id="dis" class="star-wrap row"><?php stars('dis'); ?></div>
													</div>									
												</div>																					
											</div>
										</div>
										<div class="row">
											<fieldset class="fieldset-review_cat">
												<div class="field row">
													<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 radio-div">
														<input type="radio" name="review_cat" id="review_cat_student" value="Student" checked="checked">I'm a Student
													</div>
													<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 radio-div">
														<input type="radio" name="review_cat" id="review_cat_parent" value="Parent">I'm a Parent
													</div>
												</div>	
											</fieldset>		
											<div class="row">
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">			
													<fieldset class="fieldset-review_comment">
														<div class="field">
															<label for="review_comment">Comment (optional)</label>
															<textarea cols="20" rows="3" class="input-text" name="review_comment" id="review_comment" placeholder="Please describe your personal experience about this class." maxlength=""></textarea>								
														</div>
													</fieldset>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
													<fieldset class="fieldset-review_name">
														<div class="field">
															<input type="text" class="input-text" name="review_name" id="review_name" placeholder="Full name" value="<?php if($name!='') echo $name; ?>" maxlength="">								
														</div>
													</fieldset>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
													<fieldset class="fieldset-review_email">
														<div class="field">
															<input type="text" class="input-text" name="review_email" id="review_email" placeholder="Email" value="<?php if($email!='') echo $email; ?>" maxlength="">								
														</div>
													</fieldset>
												</div>
												<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
													<fieldset class="fieldset-review_mobile">
														<div class="field">
															<input type="text" class="input-text" name="review_mobile" id="review_mobile" placeholder="Mobile Number" value="<?php if($mobile2!='') echo $mobile2; ?>" maxlength="">								
														</div>
													</fieldset>	
												</div>
											</div>
											<fieldset class="fieldset-review_anonymous">
												<div class="field">									
													<input type="checkbox" name="review_anonymous" value="true"> Post anonymously
												</div>
											</fieldset>
											<div id="message" class="hide"></div>								
											<fieldset class="fieldset-submit">
												<div class="field">
													<input type="submit" class="button" name="submit" id="submit" placeholder="Submit" value="Submit">								
												</div>
											</fieldset>
										</div>																		
									</form>
								</div>
							</div>
						</article>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1"></div>
				</main>
			</div>
		</div>
	<?php endif; ?>
	<?php
	function print_my_inline_script() {
		if ( wp_script_is( 'jquery', 'done' ) ) {
			global $current_user;
			get_currentuserinfo();
			?>

			<script type="text/javascript">
				function correctemail(){
					mixpanel.identify("<?php echo $current_user->user_email; ?>");	
				}
				jQuery("#btn0").click(function(){
					mixpanel.track("rr_success_ttg");
				});
				jQuery("#btn1").click(function(){
					mixpanel.track("rr_success_fb");
				});
				jQuery("#btn2").click(function(){
					mixpanel.track("rr_success_blog");
				});		
				jQuery('textarea').focus(function() {
					jQuery(this).val('');
				});
				previous = [0, 0, 0, 0];
				jQuery('.ratingstar').click(function() {
					var rating = jQuery(this).data('rate');
					if (jQuery(this).parent().attr('id') == 'comm')
						previous[0] = rating;
					if (jQuery(this).parent().attr('id') == 'sk')
						previous[1] = rating;
					if (jQuery(this).parent().attr('id') == 'ts')
						previous[2] = rating;
					if (jQuery(this).parent().attr('id') == 'dis')
						previous[3] = rating;
					jQuery(this).parent().children('input').val(rating);
					jQuery(this).parent().children('span').each(function() {
						if (jQuery(this).data('rate') <= rating)
							jQuery(this).addClass('fa-star').removeClass('fa-star-o');
						else
							jQuery(this).addClass('fa-star-o').removeClass('fa-star');
					});
				});


				jQuery('.ratingstar').hover(
					function() {
						var rating1 = jQuery(this).data('rate');
						jQuery(this).parent().children('span').each(function() {
							if (jQuery(this).data('rate') <= rating1)
								jQuery(this).addClass('fa-star').removeClass('fa-star-o');
							else
								jQuery(this).addClass('fa-star-o').removeClass('fa-star');
						});
					},
					function() {
						var temp;
						if (jQuery(this).parent().attr('id') == 'comm')
							temp = previous[0];
						if (jQuery(this).parent().attr('id') == 'sk')
							temp = previous[1];
						if (jQuery(this).parent().attr('id') == 'ts')
							temp = previous[2];
						if (jQuery(this).parent().attr('id') == 'dis')
							temp = previous[3];
						jQuery(this).parent().children('input').val(temp);
						jQuery(this).parent().children('span').each(function() {
							if (jQuery(this).data('rate') <= temp)
								jQuery(this).addClass('fa-star').removeClass('fa-star-o');
							else
								jQuery(this).addClass('fa-star-o').removeClass('fa-star');
						});
					}
				);

			jQuery(document).ready(function() {
				jQuery("form").submit(function(e) {
		        e.preventDefault(); //prevent default form submit

		        var name = jQuery('#review_name').val();
		        var email = jQuery('#review_email').val();
		        var mobile = jQuery('#review_mobile').val();
		        var cat = jQuery('#review_cat').val();
		        var comm = jQuery('#hidden-comm').val();
		        var ts = jQuery('#hidden-ts').val();
		        var sk = jQuery('#hidden-sk').val();
		        var dis = jQuery('#hidden-dis').val();
		        var tutor = jQuery('#rev_tutor').val();
		        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		        var re1 = /^[789]\d{9}$/i;

		        if (re.test(email) == false && re1.test(mobile) == false)
		        	jQuery("#message").show().empty().append("Please enter correct email and mobile number.");
		        else if (re.test(email) == false && re1.test(mobile) == true)
		        	jQuery("#message").show().empty().append("Please enter correct email.");
		        else if (re.test(email) == true && re1.test(mobile) == false)
		        	jQuery("#message").show().empty().append("Please enter correct mobile number.");
		        else {
		        	jQuery("#message").hide();
		        	jQuery.ajax({
		        		url: jQuery(this).attr('action'),
		        		type: 'POST',
		        		data: jQuery(this).serialize(),
		        		success: function(data) {
		        			if (data == 1) {
		        				var offsetIST = 5.5;
		        				var d=new Date();
		        				var utcdate =  new Date(d.getTime() + (d.getTimezoneOffset()*60000));
		        				var istdate =  new Date(utcdate.getTime() - ((-offsetIST*60)*60000));		        				
		        				jQuery('.content-box').hide();
		        				jQuery('.success-box').show();
		        				window.scrollTo(0, 0);
		        				mixpanel.identify(email);
		        				mixpanel.people.set({
		        					"$name": name,
		        					"$email": email,
		        					"$phone": mobile,
		        					"$user_type": cat,								    
		        					"$profile_type":"Review Submit",
		        					"$tutor_reviewed":tutor,
		        					"$signup_time":istdate,
		        					"$ip": "<?php echo $_SERVER['REMOTE_ADDR']; ?>"
		        				},correctemail);        				
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
?>
<?php get_footer(); ?>