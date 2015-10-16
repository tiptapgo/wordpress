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
		<style type="text/css">
			.viewprofile, .bookreq{				
				width: 100%;
				margin: 20px 0 10px;
			}
			.viewprofile{
				padding: 7px 5px !important;
				border: 1px solid #3396d1 !important;
				background: #fff !important;
				color: #3396d1 !important;
				box-shadow: none !important;
				text-align: center;	
			}
			.bookreq{
				padding: 10px 5px !important;
				border: 1px solid #77c04b !important;	
			}
			.bookreq.active{
				background: #fff !important;
				color: #77c04b !important;
				box-shadow: none;
				-webkit-transition: all 0.3s ease-out;
          			transition: all 0.3s ease-out;
			}
			.bookinfo{
				background: #eee;
				color: #3396d1;
				padding: 20px 10px;
				margin: 20px 0;
				text-align: center;
				-webkit-transition: all 1s linear;
          			transition: all 1s linear;
			}
		</style>
		<h1 class="widget-title widget-title-job_listing ion-ios-person-outline">Tutor Details</h1>
		<div class="job_listing-author">
			<div class="job_listing-author-avatar">
				<a href="<?php echo get_site_url()."/profile/?nick=".get_userdata(get_the_author_meta( 'ID' ))->user_login; ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 210 ); ?></a>
			</div>

			<div class="job_listing-author-info">
				<a href="<?php echo get_site_url()."/profile/?nick=".get_userdata(get_the_author_meta( 'ID' ))->user_login; ?>"><?php the_author(); ?></a>
			</div>
			<br>
			<?php if ( $biography && $bio = get_the_author_meta( 'description', get_the_author_meta( 'ID' ) ) ) : ?>
				<div class="job_listing-author-biography">
					<?php echo nl2br($bio); ?>
				</div>
			<?php endif; ?>
			<div class="row">
				<div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
					<a href="<?php echo get_site_url()."/profile/?nick=".get_userdata(get_the_author_meta( 'ID' ))->user_login; ?>" class="button viewprofile">View Profile</a>
				</div>
				<div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">	
					<button class="bookreq">Request to Book</button>		
				</div>	
			</div>
			<!--<div class="row">
				<div class="col-xs-12 hide bookinfo"><div>To book this Class, SMS or WhatsApp</div><div><?php echo get_the_ID(); ?> to 0 9901 079 974</div></div>
			</div>-->	
			<?php do_action( 'listify_widget_job_listing_author_after' ); ?>
		</div>
		<script type="text/javascript">
			jQuery(".viewprofile").click(function(){
				mixpanel.track("class_view_profile");
			})
			var mixpanelflag = 0;
			jQuery(".bookreq").click(function(){
				if(mixpanelflag == 0){
					mixpanel.track("class_rtb");
					mixpanelflag = 1;
				}
			})
		</script>

		<?php
		echo $after_widget;

		$content = ob_get_clean();

		echo apply_filters( $this->widget_id, $content );

		$this->cache_widget( $args, $content );
	}
}
