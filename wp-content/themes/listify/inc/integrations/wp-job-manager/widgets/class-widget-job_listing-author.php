<?php
/**
 * Job Listing: Author
 *
 * @since Listify 1.0.0
 */
class Listify_Widget_Listing_Author extends Listify_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_description = __( 'Display the listing\'s author', 'listify' );
		$this->widget_id          = 'listify_widget_panel_listing_auhtor';
		$this->widget_name        = __( 'Listify - Listing: Author', 'listify' );
		$this->settings           = array(
			'descriptor' => array(
				'type'  => 'text',
				'std'   => 'Listing Owner',
				'label' => __( 'Descriptor:', 'listify' )
			),
			'biography' => array(
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => __( 'Show biography', 'listify' )
			),
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

		$descriptor = isset( $instance[ 'descriptor' ] ) ? esc_attr( $instance[ 'descriptor' ] ) : false;
		$biography = isset( $instance[ 'biography' ] ) && 1 == $instance[ 'biography' ] ? true : false;

		global $post;

		extract( $args );

		ob_start();

		echo $before_widget;
		?>

		<div class="job_listing-author">
			<div class="job_listing-author-avatar">
				<a href="http://tiptapgo.co/profile/?nick=<?php echo get_userdata(get_the_author_meta( 'ID' ))->user_login; ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 210 ); ?></a>
			</div>

			<div class="job_listing-author-info">
				<a href="http://tiptapgo.co/profile/?nick=<?php echo get_userdata(get_the_author_meta( 'ID' ))->user_login; ?>"><?php the_author(); ?></a>
			</div>
			<br>
			<?php if ( $biography && $bio = get_the_author_meta( 'description', get_the_author_meta( 'ID' ) ) ) : ?>
				<div class="job_listing-author-biography">
					<?php echo nl2br($bio); ?>
				</div>
			<?php endif; ?>

            <?php do_action( 'listify_widget_job_listing_author_after' ); ?>
		</div>

		<?php
		echo $after_widget;

		$content = ob_get_clean();

		echo apply_filters( $this->widget_id, $content );

		$this->cache_widget( $args, $content );
	}
}
