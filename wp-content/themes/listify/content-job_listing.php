<?php
/**
 * The template for displaying job listings (in a loop).
 *
 * @package Listify
 */

$classStr = "job_listing job-type-conducted-at-tutors-location type-job_listing status-publish has-post-thumbnail hentry job_listing_category-bollywood job_listing_type-conducted-at-tutors-location col-xs-12 col-sm-6 col-md-4 style-grid";
if(is_page('My Account') || is_page('Profile'))
	$classStr = str_replace("col-md-4", "col-md-6", $classStr);
?>

<li id="job_listing-<?php the_ID(); ?>" <?php echo 'class="'.$classStr.'"'; echo apply_filters('listify_job_listing_data', '', false ); ?>>

	<div class="content-box">

		<a href="<?php the_permalink(); ?>" class="job_listing-clickbox"></a>

		<header <?php echo apply_filters( 'listify_cover', 'job_listing-entry-header listing-cover' ); ?>>
			<div class="job-background-filter"></div>
            <?php do_action( 'listify_content_job_listing_header_before' ); ?>

			<div class="job_listing-entry-header-wrapper cover-wrapper">

				<div class="job_listing-entry-thumbnail">
					<div <?php echo apply_filters( 'listify_cover', 'list-cover' ); ?>></div>
				</div>
				<div class="job_listing-entry-meta">
					<?php do_action( 'listify_content_job_listing_meta' ); ?>
				</div>

			</div>

            <?php do_action( 'listify_content_job_listing_header_after' ); ?>
		</header><!-- .entry-header -->

		<footer class="job_listing-entry-footer">

			<?php do_action( 'listify_content_job_listing_footer' ); ?>

		</footer><!-- .entry-footer -->

	</div>
</li><!-- #post-## -->
