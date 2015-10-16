<?php
/**
 * The template for displaying Job Listings.
 *
 * Also used for all job listing taxonomy archives.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Listify
 */

add_filter( 'wp_title', 'yoast_add_title');
add_filter( 'wpseo_title', 'yoast_add_title');
function yoast_add_title( $str ) {
	if($_GET['search_location']=='' && $_GET['search_categories'][0]==''){
		return "All the Classes happening in Bangalore | TipTapGo!";
	}
	else if($_GET['search_location']!='' && $_GET['search_categories'][0]==''){
		return "All the Classes happening in ".$_GET['search_location']." | TipTapGo!";
	}
	else if($_GET['search_location']=='' && $_GET['search_categories'][0]!=''){
		$cat = get_term_by( 'term_id', $_GET['search_categories'][0], 'job_listing_category' );
		if ( $cat ){
			return $cat->name." Classes happening in Bangalore | TipTapGo!";
		}
	}
	if($_GET['search_location']!='' && $_GET['search_categories'][0]!=''){
		$cat = get_term_by( 'term_id', $_GET['search_categories'][0], 'job_listing_category' );
		if ( $cat ){
			return $cat->name." Classes happening in ".$_GET['search_location']." | TipTapGo!";
		}
	}	
}

add_filter( 'wpseo_metadesc', 'yoast_add_meta_desc');
function yoast_add_meta_desc( $str ) {
	return "Take classes and find thousands of tutors in your area using TipTapGo!";
}

get_header(); ?>

	<?php do_action( 'listify_output_map' ); ?>

	<div id="primary" class="container">
		<div class="row content-area">

			<?php get_sidebar( 'archive-job_listing' ); ?>

			<main id="main" class="site-main <?php if ( listify_job_listing_archive_has_sidebar() ) : ?>col-md-8 col-sm-12 <?php endif; ?>col-xs-12" role="main">
				<?php do_action( 'listify_output_results' ); ?>
				<div class="row">
				    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 archivecta">
					<?php get_template_part( 'content', 'cta' ); ?>
				    </div>
				</div>
			</main>

		</div>
	</div>

<?php get_footer(); ?>