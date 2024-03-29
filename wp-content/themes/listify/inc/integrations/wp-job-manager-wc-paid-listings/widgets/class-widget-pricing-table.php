<?php
/**
 * Job Listing: Social Profiles
 *
 * @since Listify 1.0.0
 */
class Listify_Widget_WCPL_Pricing_Table extends Listify_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_description = __( 'Display the pricing packages available for listings', 'listify' );
		$this->widget_id          = 'listify_widget_panel_wcpl_pricing_table';
		$this->widget_name        = __( 'Listify - Page: Pricing Table', 'listify' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Title:', 'listify' )
			),
			'description' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Description:', 'listify' )
			)
		);
		parent::__construct();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		if ( $this->get_cached_widget( $args ) )
			return;

		extract( $args );

		$packages = $this->get_packages();

		if ( ! $packages->have_posts() ) {
			return;
		}

		$title = apply_filters( 'widget_title', $instance[ 'title' ], $instance, $this->id_base );
		$description = isset( $instance[ 'description' ] ) ? esc_attr( $instance[ 'description' ] ) : false;

		$after_title = '<h2 class="home-widget-description">' . $description . '</h2>' . $after_title;

		$layout = count( $packages ) > 3 ? 'stacked' : 'inline';

		ob_start();

		echo $before_widget;

		if ( $title ) echo $before_title . $title . $after_title;
		?>

		<ul class="job-packages <?php echo $layout; ?>">

		<?php while ( $packages->have_posts() ) : $packages->the_post(); $product = get_product( get_the_ID() ); ?>

			<?php $tags = $product->get_tags(); ?>

			<li class="job-package">
				<?php if ( $tags ) : ?>
					<span class="job-package-tag"><?php echo $tags; ?></span>
				<?php endif; ?>

				<div class="job-package-header">
					<div class="job-package-title">
						<?php echo $product->get_title(); ?>
					</div>
					<div class="job-package-price">
						<?php echo $product->get_price_html(); ?>
					</div>
				</div>

				<div class="job-package-includes">
					<?php
						$content = $product->post->post_content;
						$content = (array)explode( "\n", $content );
					?>
					<ul>
						<li><?php echo implode( '</li><li>', $content ); ?></li>
					</ul>
				</div>

				<div class="job-package-purchase">
					<a href="<?php echo $product->add_to_cart_url(); ?>" class="button"><?php _e( 'Get Started Now', 'listify' ); ?></a>
				</div>
			</li>

		<?php endwhile; ?>

		</ul>

		<?php
		echo $after_widget;

		$content = ob_get_clean();

		echo apply_filters( $this->widget_id, $content );

		$this->cache_widget( $args, $content );
	}

	private function get_packages() {
		$packages = new WP_Query( array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => array( 'job_package', 'job_package_subscription' )
				)
			),
			'orderby' => 'menu_order',
			'lang' => substr( get_locale(), 0, 2 )
		) );

		return $packages;
	}
}
