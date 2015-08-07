<?php
/**
 * Template Name: Student-Search
 *
 * @package Listify
 */

get_header(); ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/student-ng-app/style.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/student-ng-app/select.css">

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