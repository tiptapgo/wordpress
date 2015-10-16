<?php
/**
 * Job Listing: Video
 *
 * @since Listify 1.0.0
 */
class Listify_Widget_Listing_Video extends Listify_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_description = __( 'Display the listing video.', 'listify' );
		$this->widget_id          = 'listify_widget_panel_listing_video';
		$this->widget_name        = __( 'Listify - Listing: Video', 'listify' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => 'Video',
				'label' => __( 'Title:', 'listify' )
			),
			'icon' => array(
				'type'    => 'select',
				'std'     => 'ios-videocam',
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

		global $job_manager, $post;

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$icon = isset( $instance[ 'icon' ] ) ? $instance[ 'icon' ] : null;

		if ( $icon ) {
			$before_title = sprintf( $before_title, 'ion-' . $icon );
		}

		ob_start();

		echo $before_widget;

		if ( $title ) echo $before_title . $title . $after_title;
        
        do_action( 'listify_widget_job_listing_video_before' );
        $job_seats = get_post_meta(get_the_ID(), '_job_no_of_seats', true);      
        $classtype = get_post_meta(get_the_ID(), '_job_classtype', true);
        if($classtype == 'regular') {
        	$days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
			$monthlyfees = get_post_meta(get_the_ID(), '_job_monthly_fees', true);
			if($monthlyfees == ''){
				$monthlyfees = get_post_meta(get_the_ID(), '_hourly_rate', true)." per class";
			}         	
			$job_time = get_post_meta(get_the_ID(), 'multi_job_hrs', true);
			$job_monthly_classes = get_post_meta(get_the_ID(), '_job_monthly_classes', true);
			$job_time_init = explode("%", $job_time);
			$i=0;
			?>
			<h6>Regular classes every:</h6>
			<?php 
			foreach ($job_time_init as $daysch) { ?>
				<div class="row datarow">
				<?php
				if($daysch != 'empty' && $daysch != '' && $daysch != 'closed') {
					?>
					<div class="dayname col-lg-2 col-md-2 col-xs-12 col-sm-12"><?php echo $days[$i]; ?></div> 
					<?php
					if(strpos($daysch, "|")){
						$daysch_init = explode("|", $daysch);
						foreach ($daysch_init as $timeunit) {
							if($timeunit == '8-12'){
								$timeunit = "8AM - 12PM";
							} else if($timeunit == '12-16') {
								$timeunit = "12PM - 4PM";
							} else if($timeunit == '16-20') {
								$timeunit = "4PM - 8PM";
							} else if($timeunit == '20-22') {
								$timeunit = "8PM - 10PM";
							}
							?>
						 	<div class="timeval col-lg-2 col-md-2 col-xs-12 col-sm-12"><?php echo $timeunit; ?></div>
						 <?php } 
					} else{
							if($daysch == '8-12'){
								$daysch = "8AM - 12PM";
							} else if($daysch == '12-16') {
								$daysch = "12PM - 4PM";
							} else if($daysch == '16-20') {
								$daysch = "4PM - 8PM";
							} else if($daysch == '20-22') {
								$daysch = "8PM - 10PM";
							}
						 ?>
						<div class="timeval col-lg-2 col-md-2 col-xs-12 col-sm-12"><?php echo $daysch; ?></div>
					<?php
					}
				}
				$i++; ?>
				</div>
				<?php 
			}
			if($job_monthly_classes!='' || $job_seats!=''){ 
				echo '<hr>';
			}
			?>
			<?php if($job_monthly_classes!='') { ?>
			<data class="skmclassdet"><i class="skminfoicon fa fa-list-ul"></i> <?php echo $job_monthly_classes; ?> classes every month</data>
			<?php } if($job_seats!=''){ ?>
			<data class="skmclassdet"><i class="skminfoicon fa fa-users"></i> <?php echo $job_seats; ?> seats available</data>
			<?php }
			if($job_monthly_classes!='' || $job_seats!=''){ 
				echo '<hr>';
			}
			?>
			<div class="bookrow row">
				<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 bookleft text-center">BOOK THIS CLASS</div>
				<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 bookright text-center"><i class="fa fa-inr"></i> <?php echo $monthlyfees; ?></div>
			</div>	
		<?php
		} else if($classtype == 'course') {
			$days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
			$mons = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
			$job_start_time = get_post_meta(get_the_ID(), '_job_start_time', true);
			$monthlyfees = get_post_meta(get_the_ID(), '_hourly_rate', true);
			$job_end_time = get_post_meta(get_the_ID(), '_job_end_time', true);
			if($job_start_time!= '' && $job_end_time!=''){
				$to_time = strtotime(date("H:i", strtotime($job_start_time)));
				$from_time = strtotime(date("H:i", strtotime($job_end_time)));
				$duration = round(abs($to_time - $from_time) / 60 / 60 ,2);
			} else{
				$job_start_time = 0;
				$job_end_time = 0;
				$duration = 0;
			}
			$job_date = get_post_meta(get_the_ID(), '_job_date', true);
			if($job_date == ''){
				$job_date = date("d/m/Y");
			}
			$olddate = DateTime::createFromFormat('d/m/Y', $job_date);
			$newdate = $olddate->format('Y-m-d');
			$weekday = date('w', strtotime($newdate));
			$job_date = explode('/', $job_date);
			$job_date[0] = ltrim($job_date[0], '0');
			$job_date[1] = ltrim($job_date[1], '0');
			$job_date[2] = ltrim($job_date[2], '0');
			
			?>
				<div class="row no-pad">
					<div class="inline-div">This class is on</div><div class="timeval col-lg-4 col-md-4 col-xs-12 col-sm-12"><?php echo $days[$weekday].', '.$job_date[0].'-'.$mons[$job_date[1]].'-'.$job_date[2]; ?></div>
				</div>
				<div class="row no-pad">	
					<div class="inline-div">between</div><div class="timeval col-lg-2 col-md-2 col-xs-12 col-sm-12"><?php echo $job_start_time ?></div><div class="inline-div">and</div><div class="timeval col-lg-2 col-md-2 col-xs-12 col-sm-12"><?php echo $job_end_time ?></div>
				</div>
				<?php 
			if($duration!='' || $job_seats!=''){ 
				echo '<hr>';
			}
			if($duration!='') { ?>
				<data class="skmclassdet"><i style="padding-right:18.5px;" class="skminfoicon fa fa-clock-o"></i> <?php echo $duration; ?> hours duration</data>
			<?php } if($job_seats!=''){ ?>
				<data class="skmclassdet"><i class="skminfoicon fa fa-users"></i> <?php echo $job_seats; ?> seats available</data>
			<?php }
			if($duration!='' || $job_seats!=''){ 
				echo '<hr>';
			}
			?>
			<div class="bookrow row">
				<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 bookleft text-center">BOOK THIS CLASS</div>
				<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 bookright text-center"><i class="fa fa-inr"></i> <?php echo $monthlyfees; ?></div>
			</div>	
		<?php 
		} else if($classtype == 'batch') {
			$days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
			$mons = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
			$monthlyfees = get_post_meta(get_the_ID(), '_job_monthly_fees', true);
			if($monthlyfees == ''){
				$monthlyfees = get_post_meta(get_the_ID(), '_hourly_rate', true)." per class";
			} 
			$job_date = get_post_meta(get_the_ID(), '_job_date', true);
			if($job_date == ''){
				$job_date = date("d/m/Y");
			}
			$olddate = DateTime::createFromFormat('d/m/Y', $job_date);
			$newdate = $olddate->format('Y-m-d');
			$weekday = date('w', strtotime($newdate));
			$job_date = explode('/', $job_date);
			$job_date[0] = ltrim($job_date[0], '0');
			$job_date[1] = ltrim($job_date[1], '0');
			$job_date[2] = ltrim($job_date[2], '0');
			$timedump = explode(',', get_post_meta(get_the_ID(), '_job_time_dump', true));
			$daydump = explode(',', get_post_meta(get_the_ID(), '_job_day_dump', true));
			$duration = get_post_meta(get_the_ID(), '_session_duration', true);
			$job_monthly_classes = get_post_meta(get_the_ID(), '_job_monthly_classes', true);			
			?>
				<div class="row no-pad">
					<div class="inline-div">This class starts on</div><div class="timeval col-lg-4 col-md-4 col-xs-12 col-sm-12"><?php echo $days[$weekday].', '.$job_date[0].'-'.$mons[$job_date[1]].'-'.$job_date[2]; ?></div>
				</div>
				<h6>Classes take place every:</h6>
				<?php 
				for ($i=0; $i < count($daydump) ; $i++) {  ?>
					<div class="row datarow">
						<div class="dayname col-lg-2 col-md-2 col-xs-12 col-sm-12"><?php echo $daydump[$i]; ?></div> 
						<div class="timeval col-lg-2 col-md-2 col-xs-12 col-sm-12"><?php echo $timedump[$i]; ?></div> 
					</div>	
				<?php }
			if($duration!='' || $job_seats!='' || $job_monthly_classes!=''){ 
				echo '<hr>';
			}
			if($duration!='') { ?>
				<data class="skmclassdet"><i style="padding-right:18.5px;" class="skminfoicon fa fa-clock-o"></i> <?php echo $duration; ?> minutes per session</data>
			<?php } if($job_monthly_classes!=''){ ?>
				<data class="skmclassdet"><i class="skminfoicon fa fa-list-ul"></i> <?php echo $job_monthly_classes; ?> sessions in this batch</data>
			<?php } if($job_seats!=''){ ?>
				<data class="skmclassdet"><i class="skminfoicon fa fa-users"></i> <?php echo $job_seats; ?> seats available</data>
			<?php }

			if($duration!='' || $job_seats!='' || $job_monthly_classes!=''){ 
				echo '<hr>';
			}
			?>
			<div class="bookrow row">
				<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 bookleft text-center">BOOK THIS CLASS</div>
				<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 bookright text-center"><i class="fa fa-inr"></i> <?php echo $monthlyfees; ?></div>
			</div>
		<?php }

        do_action( 'listify_widget_job_listing_video_after' );

		echo $after_widget;

		$content = ob_get_clean();

		echo apply_filters( $this->widget_id, $content );

		$this->cache_widget( $args, $content );
	}
}
