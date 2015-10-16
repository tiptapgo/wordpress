<?php
/**
* Template Name: Class Categories
*
* @package Listify
*/

get_header();
$parentargs = array(
	'orderby'           => 'name', 
	'order'             => 'ASC',
	'hide_empty'        => true,
	'parent'            => 0,    
	); 

$parentterms = get_terms('job_listing_category', $parentargs);
?>
<style type="text/css">
	.browseh2{
		font-size: 24px !important;
		margin: -30px 0 60px !important;
		display: block;
	}
</style>
<div id="primary" class="container">
	<div class="row content-area">
		<main id="main" class="site-main" role="main">
			<article class="page type-page status-publish hentry content-box content-box-wrapper">
				<div class="content-box-inner">
					<div class="entry-content">
						<aside class="widget">
							<h2 class="text-center browseh2">Browse tutors by categories</h2>					
							<div class="home-categories">
								<div class="row">
									<div class="grid js-masonry" data-masonry-options='{ "itemSelector": ".grid-item" }'>
										<?php 
										foreach ($parentterms as $term) {
											$childargs = array(
												'orderby'           => 'name', 
												'order'             => 'ASC',
												'hide_empty'        => false,
												'parent'            => $term->term_id,    
												);
											$childterms = get_terms('job_listing_category', $childargs); 
											if(count($childterms) == 0){
												continue;
											}
											?>
											<div class="grid-item col-md-4 col-sm-6 col-lg-4 col-xs-12">
												<h6 class="cat-link-head"><?php echo $term->name; ?> </h6>
												<div class="cat">
													<?php 
													foreach ($childterms as $child) { ?>
													<div class="cat-link">
														<a href="<?php echo get_term_link( $child );?>" title="<?php echo $child->name;?>"><?php echo $child->name;?></a>
													</div>	
													<?php } ?>
												</div>
											</div>
											<?php
										}					
										?>
									</div>				
								</div>			
							</div>
						</aside>
					</div>
				</div>
			</article>
		</main>
	</div>
</div>
<?php
get_footer(); 
?>    