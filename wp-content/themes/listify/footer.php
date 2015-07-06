<?php

/**

 * The template for displaying the footer.

 *

 * Contains the closing of the #content div and all content after

 *

 * @package Listify

 */

?>



	</div><!-- #content -->



	<div class="footer-wrapper">



		<?php if ( ! listify_is_job_manager_archive() ) : ?>



			<?php get_template_part( 'content', 'cta' ); ?>



			<?php get_template_part( 'content', 'aso' ); ?>



			<?php if ( is_active_sidebar( 'widget-area-footer-1' ) || is_active_sidebar( 'widget-area-footer-2' ) || is_active_sidebar( 'widget-area-footer-3' ) ) : ?>



				<footer class="site-footer-widgets">

					<div class="container">

						<div class="row">



							<div class="footer-widget-column col-xs-12 col-sm-12 col-lg-5">

								<?php dynamic_sidebar( 'widget-area-footer-1' ); ?>

							</div>



							<div class="footer-widget-column col-xs-12 col-sm-6 col-lg-3 col-lg-offset-1">

								<?php dynamic_sidebar( 'widget-area-footer-2' ); ?>

							</div>



							<div class="footer-widget-column col-xs-12 col-sm-6 col-lg-3">

								<?php dynamic_sidebar( 'widget-area-footer-3' ); ?>

							</div>



						</div>

					</div>

				</footer>



			<?php endif; ?>



		<?php endif; ?>



		<footer id="colophon" class="site-footer" role="contentinfo">

			<div class="container">



				<div class="site-info">

					<?php echo listify_theme_mod( 'copyright-text' ); ?>

				</div><!-- .site-info -->



				<div class="site-social">

					<?php wp_nav_menu( array(

						'theme_location' => 'social',

						'menu_class' => 'nav-menu-social',

						'fallback_cb' => '',

						'depth' => 1

					) ); ?>

				</div>



			</div>

		</footer><!-- #colophon -->



	</div>



</div><!-- #page -->



<div id="ajax-response"></div>



<?php wp_footer(); ?>

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
	var pattern =/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
	return pattern.test($('#tutor_dob').val());
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

</body>

</html>