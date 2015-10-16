<?php
/**
 * Job Listing: Business Hours
 *
 * @since Listify 1.0.0
 */
class Listify_Widget_Listing_Business_Hours extends Listify_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_description = __( 'Display the business hours of the listing.', 'listify' );
		$this->widget_id          = 'listify_widget_panel_listing_business_hours';
		$this->widget_name        = __( 'Listify - Listing: Business Hours', 'listify' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Title:', 'listify' )
				),
			'icon' => array(
				'type'    => 'select',
				'std'     => 'clock',
				'label'   => __( 'Icon:', 'listify' ),
				'options' => $this->get_icon_list()
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

		global $job_manager;

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$icon = isset( $instance[ 'icon' ] ) ? $instance[ 'icon' ] : null;

		if ( $icon ) {
			$before_title = sprintf( $before_title, 'ion-' . $icon );
		}

		$hours = get_post()->_job_hours;

		$timings = get_post_meta(get_the_ID(),'multi_job_hrs',true);
		$timings = explode('%', $timings); 
		$i = 0; $j = 0;
		$hours = array();
		$trainhours = array();
		foreach ($timings as $slots) {
			if(strpos($slots, '|')){
				$slots = explode('|', $slots);
				foreach ($slots as $slot) {
					if($slot == "8-12"){
						$hours[$i][$j] = "8:00 am to 12:00 pm";
						$trainhours[$i][$j] = "8am to 12pm";
					} else if($slot == "12-16"){
						$hours[$i][$j] = "12:00 pm to 4:00 pm";
						$trainhours[$i][$j] = "12pm to 16pm";
					} else if($slot == "16-20"){
						$hours[$i][$j] = "4:00 pm to 8:00 pm";
						$trainhours[$i][$j] = "16pm to 20pm";
					} else if($slot == "20-22"){
						$hours[$i][$j] = "8:00 pm to 10:00 pm";
						$trainhours[$i][$j] = "20pm to 22pm";
					} else if($slot == "closed"){
						$hours[$i][$j] = "Closed";
						$trainhours[$i][$j] = "Closed";
					} else if($slot == "empty"){
						$hours[$i][$j] = "";
						$trainhours[$i][$j] = "";
					}
					$j++;
				}
			} else{
				if($slots == "8-12"){
					$hours[$i][0] = "8:00 am to 12:00 pm";
					$trainhours[$i][0] = "8am to 12pm";
				} else if($slots == "12-16"){
					$hours[$i][] = "12:00 pm to 4:00 pm";
					$trainhours[$i][0] = "12pm to 16pm";
				} else if($slots == "16-20"){
					$hours[$i][0] = "4:00 pm to 8:00 pm";
					$trainhours[$i][0] = "16pm to 20pm";
				} else if($slots == "20-22"){
					$hours[$i][0] = "8:00 pm to 10:00 pm";
					$trainhours[$i][0] = "20pm to 22pm";
				} else if($slots == "closed"){
					$hours[$i][0] = "Closed";
					$trainhours[$i][0] = "Closed";
				} else if($slots == "empty"){
					$hours[$i][0] = "";
					$trainhours[$i][0] = "";
				}
			}
			$i++;
		}

		$numericdays = listify_get_days_of_week();	

		global $wp_locale;	

		$days = array();
		foreach ( $numericdays as $key => $i ) {	
			array_push($days, $wp_locale->get_weekday( $i ));
		}		

		ob_start();

		echo $before_widget;

		if ( $title ) echo $before_title . $title . $after_title;

		do_action( 'listify_widget_job_listing_hours_before' );

		for ($i=0; $i < count($hours); $i++) { 
			for ($j=0; $j < count($hours[$i]); $j++) {	
				if($hours[$i][$j] !='') {
					?>
					<p class="business-hour" itemprop="openingHours" content="<?php echo $days[$i].' '.$trainhours[$i][$j]; ?>">
						<span class="day"><?php echo $days[$i]; ?></span>
						<span class="business-hour-time"> <?php echo $hours[$i][$j]; ?> </span>
						</p>
						<?php
					} 
				} 
			} 

			do_action( 'listify_widget_job_listing_hours_after' );

			echo $after_widget;

			$content = ob_get_clean();

			echo apply_filters( $this->widget_id, $content );

			$this->cache_widget( $args, $content );
		}
	}
