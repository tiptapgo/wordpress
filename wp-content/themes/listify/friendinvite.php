<?php
/**
 * Template Name: Friendinvite
 *
 * @package Listify
 */

get_header();

$listid = (int)trim(stripcslashes($_GET['id']));
if($listid ==''){
	$listid = (int)trim(stripcslashes($_POST['id']));
}

if($listid =='' || !is_int($listid)){
	echo '<main><article><div class="content-area container"><div class="content-box"><aside><h2>You are not allowed to invite</h2></aside></div></div></article></main>';
}

$email = get_post_meta($listid,'bookemail',true);
$name = get_post_meta($listid,'bookname',true);

?>

<style type="text/css">
	.toprow{
		text-align:center;
	}
	fieldset{
		border:0;
	}
	input{
		width:100%;
	}
	.button{
		max-width: 100%;
	}
	h4{
		font-size: 18px;
		line-height: 2;
		margin: 0;
	}
	.removebtn{
		position: absolute;
		right: -5px;
		top: 25px;
		font-size: 20px;
		color: #777;
		cursor: pointer;
	}
	.rowtoadd{
		position:relative;
	}
	#success{
		padding:60px 0 !important;
		height:600px;
		margin:100px 0;
	}
	#submit{
		max-width:200px !important; 
	}
	.elgo1{
		border-radius: 50%;
		border: 2px solid #F0F8FF;
		background-color: #FFC107;
		max-height: 180px;
    	max-width: 180px !important;
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
	@media(max-width: 767px){
		.elgo1{
			max-width:180px;
			max-height:180px;
		}		
		#success{
			padding:60px 0 !important;
			height:800px;
			margin:50px 0;
		}	
	}
</style>
<?php if($listid !=''): ?>
	<div id="primary" class="container">
		<div class="content-area">
			<main id="main" class="site-main" role="main">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<article id="success" class="hide widget">
						<div class="row content-area">
							<div class="content-box-inner">		
								<div class="entry-content">
									<div class="col-xs-12 col-lg-2 col-sm-2 col-md-2"></div>	
									<div class="col-xs-12 col-lg-8 col-sm-8 col-md-8" style="text-align:center;font-size:20px">
										<div class="col-xs-12 col-lg-12 col-sm-12 col-md-12">
											<img class="elgo1" src="<?php echo get_site_url()."/wp-content/plugins/wp-job-manager/assets/images/elgo.png"; ?>">	
										</div>
										<br><br>
										<div class="col-xs-12 col-lg-12 col-sm-12 col-md-12">
											<strong>Your invitation has been sent successfully.</br></strong>
										</div>	
									</div>
									<div class="col-xs-12 col-lg-2 col-sm-2 col-md-2"></div>
								</div>	
							</div>
						</div>
					</article>				
					<article id="init">
						<aside class="widget">
							<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 toprow">
								<div class="row">
									<h1><strong>Invite Your Friends!</strong></h1>
									<h4>Help your friends to find classes and tutors near them.</h4>	
									<br>
								</div>
							</div>
							<br>
							<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
								<div class="row">
									<form method="POST" id="inviteform" action="">
										<div class="row">
											<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
												<fieldset class="fieldset-review_name">
													<div class="field">
														<input type="text" class="input-text" name="review_name" id="review_name" placeholder="Your name" value="<?php if($name!='') echo $name; ?>" required="" maxlength="">								
													</div>
												</fieldset>
											</div>
										</div>
										<div class="row rowtoadd">
											<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
												<fieldset class="fieldset-review_sendname">
													<div class="field">
														<input type="text" class="input-text name" name="review_sendname_1" id="review_sendname_1" placeholder="Friend's name" maxlength="">								
													</div>
												</fieldset>
											</div>
											<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
												<fieldset class="fieldset-review_sendemail">
													<div class="field">
														<input type="email" class="input-text email" name="review_sendemail_1" id="review_sendemail_1" placeholder="Friend's email" maxlength="">								
													</div>
												</fieldset>
											</div>
											<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
												<fieldset class="fieldset-review_sendemail">
													<div class="field">
														<div class="input-group">
															<span class="input-group-addon" id="basic-addon1">+91</span>
															<input type="tel" class="input-text mobile" name="review_sendmobile_1" id="review_sendmobile_1" placeholder="Friend's mobile no" maxlength="10">								
														</div>	
													</div>
												</fieldset>
											</div>
										</div>
										<div id="rowtarget"></div>	
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<fieldset class="fieldset-review_add">
													<div class="field">
														<a href="#" id="addlink"><u>Add more</u></a>
													</div>
												</fieldset>
											</div>	
										</div>
										<div class="message"></div>
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<fieldset class="fieldset-review_submit">
													<div class="field">
														<input type="submit" class="button" name="submit" id="submit" placeholder="Send Invite" value="Send Invite">								
													</div>
												</fieldset>
											</div>	
										</div>																																								
									</form>			
								</div>
							</div>
						</aside>	
					</article>
				</div>
			</main>
		</div>
	</div>
<?php endif;  ?>
<?php
function print_my_inline_script() {
	if ( wp_script_is( 'jquery', 'done' ) ) {
	global $current_user;
	get_currentuserinfo();
?>

<script>
	jQuery(window).load(function(){
		var counter = 2;
		jQuery('#addlink').click(function(e){
			e.preventDefault();
			temp = jQuery('.rowtoadd:first').clone();
			temp.find('#review_sendname_1').val('').attr('name','review_sendname_' + counter).attr('id','review_sendname_' + counter).css({"border-color":"#ebeef1"});
			temp.find('#review_sendemail_1').val('').attr('name','review_sendemail_' + counter).attr('id','review_sendemail_' + counter).css({"border-color":"#ebeef1"});
			temp.find('#review_sendmobile_1').val('').attr('name','review_sendmobile_' + counter).attr('id','review_sendmobile_' + counter).css({"border-color":"#ebeef1"});
			temp.append('<span class="fa fa-minus-circle removebtn"></span>');
			counter++;				
			jQuery('#rowtarget').append(temp);
			jQuery('.removebtn').click(function(){
				jQuery(this).parents('.rowtoadd').remove();
				counter--;
			});
		});
		setInterval(function(){
			var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
			var re1 = /^[789]\d{9}$/i;
			jQuery('.rowtoadd').each(function(){
				var name = jQuery(this).find('.name').val();
				var email = jQuery(this).find('.email').val();
				var mobile = jQuery(this).find('.mobile').val();
				if(name!=''){
					jQuery(this).find('.name').css({"border-color":"#ebeef1"});
				}
				if(email!='' && re.test(email)){
					jQuery(this).find('.email').css({"border-color":"#ebeef1"});
				}
				if(mobile!='' && re1.test(mobile)){
					jQuery(this).find('.mobile').css({"border-color":"#ebeef1"});
					jQuery(this).find('.input-group-addon').css({"border-color":"#3396d1"});
					jQuery(this).find('.input-group-addon').css({"background-color":"#3396d1"});
				}				
			})									
		}, 100);		
		jQuery("#inviteform").submit(function(e) {
			e.preventDefault();
			var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
			var re1 = /^[789]\d{9}$/i;
			var flag = true;
			jQuery('.rowtoadd').each(function(){
				var name = jQuery(this).find('.name').val();
				var email = jQuery(this).find('.email').val();
				var mobile = jQuery(this).find('.mobile').val();
				
				if(name==''){
					jQuery(this).find('.name').css({"border-color":"#f00"});
					flag = false;
				}
				if(email=='' || !re.test(email)){
					jQuery(this).find('.email').css({"border-color":"#f00"});
					flag = false;
				}
				if(mobile=='' || !re1.test(mobile)){
					jQuery(this).find('.mobile').css({"border-color":"#f00"});
					jQuery(this).find('.input-group-addon').css({"border-color":"#f00"});
					jQuery(this).find('.input-group-addon').css({"background-color":"#f00"});
					flag = false;
				}
			});	
			if(flag){
				sendrequests();
				correctemail();
			}
		});	
		function correctemail(){
			mixpanel.identify("<?php echo $email; ?>");
		}
		function sendrequests(){		
			jQuery('.rowtoadd').each(function(){	
				var name = jQuery(this).find('.name').val();
				var email = jQuery(this).find('.email').val();
				var mobile = jQuery(this).find('.mobile').val();
				correctemail();
				var offsetIST = 5.5;
				var d=new Date();
				var utcdate =  new Date(d.getTime() + (d.getTimezoneOffset()*60000));
				var istdate =  new Date(utcdate.getTime() - ((-offsetIST*60)*60000));
				var tutornm = "<?php echo $name; ?>"		
				mixpanel.identify(email);
				mixpanel.people.set({
					"$name": name,
					"$email": email,
					"$phone": mobile,
					"$profile_type":"Friend Invite",
					"$user_type": "Friend Invited User",
					"$invited_by": tutornm,
					"$signup_time": istdate,
					"$ip": "<?php echo $_SERVER['REMOTE_ADDR']; ?>"
				});
			});
			jQuery.ajax({
 				url: "<?php echo get_site_url().'/wp-content/themes/listify/invitestore.php'; ?>",
				type: 'POST',
				data: '',
				success: function(data) {
					if(data == true){
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
					}
			 	},
				error: function(e) {
				}
			});
		}		
	});
</script>
<?php
	}
}
add_action( 'wp_footer', 'print_my_inline_script' );
?>
<?php get_footer(); ?>