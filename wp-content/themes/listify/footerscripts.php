<?php 
/**
 * The template for scripts in footer
 * Author - Shivam Mathur
 */
?>
<?php if(is_page(10)): ?>
	<script>
		$(function() {
			$( "#tabs" ).tabs();
		});
		function valCheck($form) {
			isValid1 = true;
			$inputs = $form.find('input[type=text], input[type=email], textarea');
			$inputs.each(function(i, el) {
				var $input = $(el);
				if ($input.val() == "" && $input.prop('required')) {
					isValid1 = false;
				}
			});
			return isValid1;
		}
		function checkDate(){
			return true;
			//var pattern =/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
			//return pattern.test($('#tutor_dob').val());
		}
		function checkRupee(){
			var rupee = /^(?:0|[1-9]\d*)(?:\.(?!.*000)\d+)?$/;
			return rupee.test($('#hourly_rate').val());
		}
		var localityInput2 = jQuery('#job_location');
		var options = {
			types: ['sublocality'],
			componentRestrictions: {
				country: 'in'
			},
		}
		jQuery(function () {	
			localityInput2.geocomplete()
			.bind("geocode:result", function (event, result) {						
				localityInput2.click();
			});
		});
		$(function(){
			$tabs = $('#tabs').tabs();
			$("#tabs").click(function(){
				if ($("#tabs .ui-state-active a").attr("id") == 'ui-id-1') {
					$('a#ui-id-2.ui-tabs-anchor').click(function(e){
						$(function(){
							$('#account_email').val($('#application').val());
						});
						$res = valCheck($('#tabs-1'));
						$res2 = checkDate();
						if($res == false){
							$('#message').css({'display':'block'});
							$('#message').empty();
							$('#message').append("Please fill all the required fields marked by * in this tab");
			//$tabs.tabs({ beforeActivate: function( event, ui ) {return false;}});
			$tabs.tabs('option', 'active', 0);
		}
		else if($res2 == false && $res==true){
			$('#message').css({'display':'block'});
			$('#message').empty();
			$('#message').append("Please fill correct date of birth as (DD/MM/YYYY)");
			//$tabs.tabs({ beforeActivate: function( event, ui ) {return false;}});
			$tabs.tabs('option', 'active', 0);
		}
		else{
			$('#message').css({'display':'none'});
			$('#message').empty();
			$('#account_email').val($('#application').val());
		} 
	});
					$('a#ui-id-3.ui-tabs-anchor').click(function(e){
						$res = valCheck($('#tabs-1'));
						$res2 = checkDate();
						if($res == false){
							$('#message').css({'display':'block'});
							$('#message').empty();
							$('#message').append("Please fill all the required fields marked by * in this tab");
			//$tabs.tabs({ beforeActivate: function( event, ui ) {return false;}});
			$tabs.tabs('option', 'active', 0);
		}
		else if($res2 == false && $res==true){
			$('#message').css({'display':'block'});
			$('#message').empty();
			$('#message').append("Please fill correct date of birth as (DD/MM/YYYY)");
			//$tabs.tabs({ beforeActivate: function( event, ui ) {return false;}});
			$tabs.tabs('option', 'active', 0);
		}
		else {
			$('#message').css({'display':'none'});
			$('#message').empty();
			$res = valCheck($('#tabs-2'));
			if($res == false){
				$('#message').css({'display':'block'});
				$('#message').empty();
				$('#message').append("Please fill all the required fields marked by * in tab 2 first");
				//$tabs.tabs({ beforeActivate: function( event, ui ) {return false;}});
				$('#account_email').val($('#application').val());
				$tabs.tabs('option', 'active', 1);
			}
			else{
				$('#message').css({'display':'none'});
				$('#message').empty();
				$('#account_email').val($('#application').val());
			}  
		}
	});
}
if ($("#tabs .ui-state-active a").attr("id") == 'ui-id-2') {
	$('a#ui-id-3.ui-tabs-anchor').click(function(e){
		$res = valCheck($('#tabs-2'));
		$res3 = checkRupee();
		console.log('exec-init');
		if($res == false ){
			console.log('hand-of-god');
			$('#message').css({'display':'block'});
			$('#message').empty();
			$('#message').append("Please fill all the required fields marked by * in this tab");
			//$tabs.tabs({ beforeActivate: function( event, ui ) {return false;}});
			$tabs.tabs('option', 'active', 1);
		}
		else if($res3 == false){
			console.log('exec');
			$('#message').css({'display':'block'});
			$('#message').empty();
			$('#message').append("Please fill your hourly rate correctly in ₹");
			//$tabs.tabs({ beforeActivate: function( event, ui ) {return false;}});
			$tabs.tabs('option', 'active', 1);
		}
		else if($res3 == true && $res == true){
			$('#message').css({'display':'none'});
			$('#message').empty();
		}  
	});
}
if ($("#tabs .ui-state-active a").attr("id") == 'ui-id-3') {
	$form = $('#tabs-3');	
	$input = $form.find('input[type=submit]');
	$input.click(function(e){
		$res = valCheck($('#tabs-3'));
		if($res == false){
			e.preventDefault();
			$('#message').css({'display':'block'});
			$('#message').empty();
			$('#message').append("Please fill all the required fields marked by * in this tab");
			//$tabs.tabs({ beforeActivate: function( event, ui ) {return false;}});
			$tabs.tabs('option', 'active', 2);
			return false;
		}
		else{
			$('#message').css({'display':'none'});
			$('#message').empty();
		}  
	});
}
});
});
$(function() {
	var $tabs = $('#tabs').tabs();
	$(".ui-tabs-panel").each(function(i){	
		var totalSize = 2;
		if (i != totalSize) {
			next = i + 1;
			$(this).append("<a href='#' class='next-tab mover button' rel='" + next + "'>Next</a>");
		}
	});
	$('.next-tab').click(function() {
		$this =  $(this);
		$form = $('#tabs-' + $(this).attr("rel"));
		inputSelector = ['input[type=email]', 'input[type=text]','textarea'];
		$inputs = $form.find('input[type=text], input[type=email], textarea, input[type=radio] ')
		isValid = true;
		$inputs.each(function(i, el) {
			var $input = $(el);
			if ($input.val() == "" && $input.prop('required')) {
				isValid = false;
			}
		});	
		if(isValid == true && (($this.attr("rel")==1 && checkDate() == true) || ($this.attr("rel")==2 && checkRupee() == true))){
			$('#message').empty();
			$('#account_email').val($('#application').val());
			$('#message').css({'display':'none'});
			$tabs.tabs('option', 'active', $this.attr("rel"));
			return true;
		}
		else if(isValid == false) {
			$('#message').css({'display':'block'});
			$('#message').empty();
			$('#message').append("Please fill all the required fields marked by *");
		}
		else if($this.attr("rel")==1 && checkDate() == false){
			$('#message').css({'display':'block'});
			$('#message').empty();
			$('#message').append("Please fill correct date of birth as (DD/MM/YYYY)");
		}
		else if($this.attr("rel")==2 && checkRupee() == false){
			$('#message').css({'display':'block'});
			$('#message').empty();
			$('#message').append("Please fill your hourly rate correctly in ₹");
		}
		else {
			$('#message').css({'display':'block'});
			$('#message').empty();
			$('#message').append("Please fill all the required fields marked by *");
		}
	});
});
$(window).load(function(){
	var $_GET = {};
	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
		function decode(s) {
			return decodeURIComponent(s.split("+").join(" "));
		}
		$_GET[decode(arguments[1])] = decode(arguments[2]);
	});
	$form = $('#submit-job-form');
	$fields = $form.find('input[type=text], textarea');
	$fields.each(function (){
		$this = $(this);
		$(function() {
			$this.val($_GET[$this.attr('name')]);
		});
	});
});
</script>
<?php endif; ?>
<script type="text/javascript">
	jQuery(window).load(function(){
		jQuery("#menu-item-1119 a").click(function(){
			mixpanel.track("blog_ftr_top");
		})
		jQuery("#menu-item-1114 a").click(function(){
			mixpanel.track("blog_ftr_bottom");
		});
		jQuery("#menu-item-822 a").click(function(){
			mixpanel.track("bat_ftr");
		});
		jQuery("#menu-item-1103 a").click(function(){
			mixpanel.track("bat_hdr");
		});
		jQuery(".cta-button").click(function(){
			mixpanel.track("rat_ftr");
		});
	});    
</script>
<?php if(is_user_logged_in()): ?>
	<script>
		<?php
		$user = wp_get_current_user();
		?>
		jQuery(window).load(function(){
			mixpanel.identify('<?php echo $user->user_email; ?>');
			jQuery("#menu-item-1203 a").click(function(){
				mixpanel.track("view_profile");
			});
			jQuery("#menu-item-1194 a").click(function(){
				mixpanel.track("logout");
			});
		});
		jQuery(".cat-link a").each(function(){
			jQuery(this).css({"width": jQuery(".cat-link").outerWidth() - 50 + "px"});
		});
		jQuery(window).resize(function(){
			jQuery(".cat-link a").each(function(){
				jQuery(this).css({"width": jQuery(".cat-link").outerWidth() - 50 + "px"});
			});
		})		    
	</script>
<?php endif; ?>
<?php if(is_front_page()): ?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			var items = ["Mathematics", "Biology", "Chemistry", "Chess", "Music"],
			$text = jQuery( '#dynamic' ),
			delay = 2;

			function loop ( delay ) {
				jQuery.each( items, function ( i, elm ){
					$text.delay(delay*1E3).fadeOut();
					$text.queue(function(){
						$text.html( items[i] );
						$text.dequeue();
					});
					$text.fadeIn();
					$text.queue(function(){
						if(i == items.length-1){
							loop(delay);   
						}
						$text.dequeue();
					});
				});
			}

			loop(delay);
		})

		jQuery('.update_results').click(function(){
			mixpanel.track(
				"home_search",
				{
					"search_area": jQuery('#search_location').val(),
					"search_category": jQuery('.chosen-single').children('span').text() 
				}
				);
		})
		jQuery("#listify_widget_feature_callout-3").find('a').click(function(){
			mixpanel.track("bat_callout");
		})
		jQuery(".newsbutton").click(function(e){
			e.preventDefault();
			var newsemail = jQuery(".newsinput").val();
			if(newsemail !=''){
				jQuery.ajax({
					url: 'https://tiptapgo.us10.list-manage.com/subscribe/post-json?u=2fc2ebae226425938283a84b3&amp;id=aa9ec705d3&c=?',
					type: 'POST',
					dataType: 'jsonp',
					data: {'MERGE0':newsemail,'b_2fc2ebae226425938283a84b3_aa9ec705d3':'','subscribe':'Subscribe',},
					error: function(){
						jQuery(".newsletter button").prop('disabled',true).text("Error!");
						setTimeout(function(){
							jQuery(".newsletter button").prop('disabled',false).text("Never miss a post!");
						}, 3000);						
					},
					success: function(test) {
						if(test.result == 'success'){				
							if(test.msg.toLowerCase().indexOf("confirm your email") >= 0){
								jQuery(".newsletter button").prop('disabled',true).text("Confirmation email sent");
							} else{
								jQuery(".newsletter button").prop('disabled',true).text("Thanks!");	
							}
							setTimeout(function(){
								jQuery(".newsletter button").prop('disabled',false).text("Never miss a post!");
								jQuery(".newsinput").val('');
							}, 3000);
							mixpanel.people.set({
								"$email": newsemail,
								"$newsletter": true,
								"$user_type": "Newsletter Subscriber"				
							});							
						} else if(test.msg.toLowerCase().indexOf("already subscribed") >= 0){
							jQuery(".newsletter button").prop('disabled',true).text("Already Subscribed");
							setTimeout(function(){
								jQuery(".newsletter button").prop('disabled',false).text("Never miss a post!");
							}, 3000);					
						} else{
							jQuery(".newsletter button").prop('disabled',true).text("Error");
							setTimeout(function(){
								jQuery(".newsletter button").prop('disabled',false).text("Never miss a post!");
							}, 3000);

						}
					},
					cache: false
				});
}
});
</script>
<?php endif; ?>
<?php if(is_archive()){ ?>
<script type="text/javascript">
	jQuery('.cta-button').click(function(){
		mixpanel.track("rat_search");
	})
</script>
<?php } ?>
<?php if(is_page( 'Find a tutor who meets your custom requirements' )) { ?>
<!--noptimize-->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.10/angular-ui-router.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular-animate.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/student-ng-app/app.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/student-ng-app/select.min.js"></script> 
<script src="<?php echo get_template_directory_uri(); ?>/student-ng-app/ui-bootstrap.js"></script> 
<script>
	jQuery(function() {
		jQuery(".meter > span").each(function() {
			jQuery(this)
			.data("origWidth", jQuery(this).width())
			.width(0)
			.animate({
				width: jQuery(this).data("origWidth")
			}, 1200);
		});
	});
</script>
<!--/noptimize-->
<?php } ?>	