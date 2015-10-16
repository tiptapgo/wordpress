<?php
/**
* Template Name: bookingrequests
*
* @package Listify
*/

if(! is_user_logged_in()){
	header("location: http://tiptapgo.co/my-account");
	die();
}

get_header();

function print_my_inline_script() {
	$user_ID = get_current_user_id();
	if ( wp_script_is( 'jquery', 'done' ) ) {
		?>
		<script type="text/javascript">
		jQuery(window).load(function(){
			var scroll = 0;
			function closeSkmModal(){
				jQuery('body').css({"overflow":"auto"});
				jQuery("html, body").animate({
					scrollTop: scroll
				}, 0);			     	
				if(!jQuery("#skm-book").hasClass('hide')){
					jQuery("#skm-book").addClass('hide');
				}				
			}
			function openSkmModal(id1,id2){
				jQuery('body').css({"overflow":"hidden"});
				scroll = $(window).scrollTop();
				jQuery("html, body").animate({
					scrollTop: 0
				}, 0);
				if(jQuery("#skm-book").hasClass('hide')){
					jQuery("#skm-book").removeClass('hide');
				}
				jQuery('input#classid').val(id1);
				jQuery('input#postid').val(id2);				
			}			
			jQuery('.decline').click(function(){
				openSkmModal(jQuery(this).data('classid'), jQuery(this).data('postid'));
			})
			jQuery('#submit, .accept').click(function(e){
				var postid = jQuery(this).data('postid');
				var accept = false;
				if(jQuery(this).hasClass('accept')){
					accept = true;
				}
				e.preventDefault();
				jQuery.ajax({																
					url: '<?php echo get_template_directory_uri();?>/booking.php',
					type: 'POST',
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					dataType: "text",
					data: (!accept ? { 'id': jQuery('#postid').val(), 'accept': 'false', 'rejectreason': jQuery('#rejectreason').val() } : { 'id': postid, 'accept': 'true' }),
					error: function(){
						alert('ERROR');
					},
					success: function(resdata) {
						if(resdata.indexOf("ERR:") == -1){
							location.reload();
						} else{
							alert('ERROR');
						}
					},
					cache: false
				});
			})
			jQuery('#skm-book').dblclick(function(){
				closeSkmModal();
			})
			jQuery('.skmclosebtn').click(function(){
				closeSkmModal();
			})
			jQuery(document).keyup(function(e) {
				if (e.keyCode == 27) {
					closeSkmModal();
				}
			});			
		})
		</script>
	<?php
	}
}
add_action( 'wp_footer', 'print_my_inline_script' );
$tutorid = get_current_user_id();
$args=array(
	'meta_query' => array(
		array(
			'key' => 'booktutorid',
			'value' => $tutorid,
			'compare' => 'LIKE'
			)
		),
	'post_type' => 'bookings',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'caller_get_posts'=> 1
	);

$my_query = null;
$my_query = new WP_Query($args);
?>

<style type="text/css">
	.red {
		color: #a94442;
	}
	.green {
		color: #77c04b;
	}
	.greenb {
		color: #fff;
		background-color: #77c04b;
		border-radius: 4px;
	}
	.bookbtn{
	    padding: 5px 0;
	    height: 30px;
	    margin-top: -5px;
	    line-height: 20px;
	    font-size: 16px;
	    border-radius: 4px;
	    cursor: pointer;
	}
	.bookbtn.green{
		border:1px solid #77c04b;
	}
	.bookbtn.red{
		border:1px solid #a94442;
	}	
	.bookingrow{
		line-height: 25px;
    	margin-bottom: 20px;
	}
	.col-md-1f{
		width: 11%;
		float: left;
	}
	#skm-book {
		position: absolute;
		width: 100%;
		height: 100%;
		z-index: 1000;
		background: rgba(255,255,255,1);
	}
	.book-form{
		width: 400px;
		height: 400px;
		padding: 30px;
		background: #eee;
		position: absolute;
		left: 50%;
		top: 40%;
		-moz-transform: translate(-50%, -50%);
		-ms-transform: translate(-50%, -50%);
		-webkit-transform: translate(-50%, -50%);		
		transform: translate(-50%, -50%);
	}
	.book-form form{
		margin-top: 20px;
	}

	.book-form strong{
		font-size: 20px;
		color: #333;
		width: 90%;
		display: block;
	}
	.skmclosebtn{
		position: absolute;
		right: 20px;
		top: 10px;
		font-size: 45px !important;
		line-height: 45px;
		padding: 0 10px;
		cursor: pointer;
	}	
	.book-form textarea{
		width: 100%;
		background: #fff;
		font-size: 16px;
		border: 1px solid #ccc;
		padding: 10px;	
	}
	.book-form #submit{
		box-shadow: none;
		height: 45px !important;
		width: 100%;
		background: #a94442 !important;
		margin-top: 20px;
	}
	.bookicon {
    	font-size: 35px;
    	position: absolute;
    	margin: -7px 0 0 -30px;
	}

</style>
<div class="skm-modal-wrap">
	<div id="skm-book" class="hide">
		<div class="book-form">
			<strong>Let us know your reason for rejecting this request.</strong>
			<i class="ion-ios-close-empty skmclosebtn"></i>
			<p style="font-size: 12px; margin: 10px 0;">Your account will be terminated on second cancellation. If you're unable to take a class and have not received any requests, we suggest you to remove the class.</p>
			<form action="" method="POST" id="bookform">
				<input type="hidden" name="classid" id="classid" value="">
				<input type="hidden" name="postid" id="postid" value="">				
				<textarea rows="4" name='rejectreason' id='rejectreason' placeholder="Start typing..."></textarea>
				<div class="row">
					<div class="col-xs-12 col-md-12 col-lg-12 col-sm-12">
						<input type="submit" name="submit" id="submit" value="Decline Request"> 
					</div>
				</div>					
			</form>		  			  		  			  			  		
		</div>
	</div>
</div>					
<div id="primary" class="container">
	<div class="row content-area">
		<main id="main" class="site-main" role="main">
			<article class="page type-page status-publish hentry content-box content-box-wrapper">
				<div class="content-box-inner">
					<div class="entry-content">
					<h2>Booking Requests</h2>
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<?php if( $my_query->have_posts() ) { ?>
								<p>Here are the booking reuqests for your classes. Confirm the request to accept the student in your class.<br>Note: If you decline more than 2 requests than your account will be disabled.</p>
							<?php } else { ?>
								<p>No requests were made for your classes yet.</p>
							<?php } ?>
						</div>
					</div>
					<br><br>			
					<?php if( $my_query->have_posts() ) { ?>
					<div class="row">
						<div class="col-md-3 col-xs-12"><strong>Class</strong></div>
						<div class="col-md-2 col-xs-12"><strong>Date</strong></div>
						<div class="col-md-2 col-xs-12"><strong>Name</strong></div>
						<div class="col-md-1 col-xs-12"><strong>Payment</strong></div>
						<div class="col-md-4 col-xs-12 text-center"><strong>Status</strong></div>
						<hr>
					</div>
					<hr>	
					<?php } ?>

					<?php
						if( $my_query->have_posts() ) {
							while ($my_query->have_posts()) {
								$my_query->the_post();
								$matchid = get_the_ID();
								$matchbookid = get_post_meta($matchid, 'bookclassid', true);
								$bookname = get_post_meta($matchid, 'bookname', true);
								$bookdate = get_the_date( 'j-F-Y', $matchid );
								$bookfees = get_post_meta($matchid, 'bookfees', true);
								$booktitle = get_the_title( $matchbookid );
								$bookstatus = get_post_meta($matchid, 'bookstatus', true);
								$bookrejected = get_post_meta($matchid, 'bookrejected', true); ?>
								<div class="row bookingrow">
									<div class="col-md-3 col-xs-12"><?php echo $booktitle; ?></div>
									<div class="col-md-2 col-xs-12"><?php echo $bookdate; ?></div>
									<div class="col-md-2 col-xs-12"><?php echo $bookname; ?></div>
									<div class="col-md-1 col-xs-12">₹ <?php echo $bookfees; ?></div>
									<div class="col-md-4 col-xs-12 text-center">
										<?php
										if($bookstatus == 'true'){ ?>
											<div class="green"><i class="ion-ios-checkmark-empty bookicon"></i> Confirmed</div>
										<?php } else if($bookrejected == 'true'){ ?>
											<div class="red"><i class="ion-ios-flag bookicon"></i> Rejected</div>
										<?php }
										 else if($bookstatus == 'false'){ ?>
	 										<div class="row">
	 											<div class="col-md-1f col-xs-12"></div>
												<div data-classid="<?php echo $matchbookid; ?>" data-postid="<?php echo $matchid; ?>" class="decline col-md-4 col-xs-12 red bookbtn text-center">Decline</div>
												<div class="col-md-1f col-xs-12"></div>
												<div data-classid="<?php echo $matchbookid; ?>" data-postid="<?php echo $matchid; ?>" class="accept col-md-4 col-xs-12 green bookbtn text-center">Confirm</div>
												<div class="col-md-1f col-xs-12"></div>
											</div>											
										<?php }
										?>
									</div>
								</div>
								<hr>									

								<?php
							}

						}
					 ?>
					</div>
				</div>
			</article>
		</main>
	</div>
</div>


<?php get_footer(); ?>    