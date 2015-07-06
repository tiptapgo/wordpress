<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/student-ng-app/style.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/student-ng-app/select.css">
<?php
/**
 * Template Name: Student-Search
 *
 * @package Listify
 */
//echo '<base href="http://shivammathur.com/ttg/index.php/become-a-student/" />';
get_header(); ?>

<?php do_action( 'listify_page_before' );?>

<div id="primary" class="container">
	<div class="row content-area">
		<main id="main" class="site-main" role="main">
			<article id="post-40" class="post-40 page type-page status-publish hentry content-box content-box-wrapper">	
				<div class="content-box-inner">		
					<div class="entry-content">	

						<div class="ng-appform" ng-app="formApp">
					    	<!-- views will be injected here -->
						    <div class="container">
						        	<div ui-view></div>
							</div>
						</div>
					</div>
				</div>
			</article>	
		</main>
	</div>
</div>		

<?php get_footer(); ?>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.10/angular-ui-router.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular-animate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/geocomplete/1.4/jquery.geocomplete.min.js"></script>
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