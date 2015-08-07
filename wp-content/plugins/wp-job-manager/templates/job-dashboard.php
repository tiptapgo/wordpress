<style>
	.actionbtn, .actionbtn:hover,.actionbtn:focus{
		border: 1px solid #3396d1;
		background-color: #fff;
		color: #3396d1 !important;
		border-radius: 5px;
		box-shadow: none;
		width:180px;
	}
	@media(min-width:768px){
		article{
			min-height: 600px;
		}
		#job-manager-job-dashboard .actionmenu{
			left:0;
			right:0;
		}
		#job-manager-job-dashboard .sub-menu{
			width: 180px !important;
			border: 1px solid #3396d1!important;
			box-shadow: none!important;
		}
		#job-manager-job-dashboard .sub-menu:before, .sub-menu:after{
			display: none;
		}

		#job-manager-job-dashboard .sub-menu li{
			border-bottom: 1px solid #3396d1;
			width: 140px;
			margin-left: 20px;
			margin-right: 0px;
		}
		#job-manager-job-dashboard .sub-menu li:last-child{
			border-bottom: 0;
		}
	}
	@media(max-width:767px){
		.classitem{
			text-align:center;
		}
		h2,h4{
			margin:10px 0 !important;
		}
		.actionbtn{
			position: absolute;
			left: 50%;
			-ms-transform: translate(-50%, 0);
			-webkit-transform: translate(-50%, 0);
			-moz-transform: translate(-50%, 0);
			transform: translate(-50%, 0);
			top: -7px;
			z-index: 10;
		}
		#job-manager-job-dashboard .actions .sub-menu{
			display:none;
		}
		#job-manager-job-dashboard .menu:hover .sub-menu,  .menu:active .sub-menu{
			display:inline-block;
			background-color: #fff;
		}
	}
	h2{
		color:#3396d1;
	}
	h2,h4{
		border-bottom:0 !important;
	}
	hr{
		border-bottom:1px solid #3396d1;
	}
	.ghost.button {
		background: #fff !important;
		color: #3396d1 !important;
		box-shadow: none !Important;
		border: 1px solid #3396d1 !important;
		border-radius: 5px !important;
		padding: 5px 30px;
		font-weight: bold !important;
		font-size: 16px !important;
	}	
</style>
<div id="job-manager-job-dashboard">
	<h1><?php _e( 'Your Classes', 'wp-job-manager' ); ?></h1>
	<?php if ( ! $jobs ) : ?>
		<h2><?php _e( 'No classes found', 'wp-job-manager' ); ?></h2>
	<?php else : ?>
		<?php foreach ( $jobs as $job ) : ?>
			<div class="row">
				<div class="col-xs-12 col-md-3 col-lg-3 col-sm-3 classitem">
					<a href="<?php echo get_post_permalink($job->ID); ?>"><h2><?php echo $job->post_title; ?></h2></a>
				</div>
				<div class="col-xs-12 col-md-3 col-lg-3 col-sm-3 classitem">
					<?php $terms = wp_get_post_terms( $job->ID, array( 'job_listing_category' ) ); ?>
					<?php foreach ( $terms as $term ) : ?>
						<h4><?php echo $term->name; ?></h4>
					<?php endforeach; ?>
				</div>	
				<div class="col-xs-12 col-md-3 col-lg-3 col-sm-3 classitem">
					<h4><?php the_job_status( $job ); ?></h4>
				</div>
				<div class="col-xs-12 col-md-3 col-lg-3 col-sm-3 nav-menu actions">
					<div class="nav-menu-container">
						<ul class="menu">
							<li class="button actionbtn"> Actions <span class="ion-chevron-down pull-right"></span>
								<ul class="sub-menu actionmenu">
									<?php
									$actions = array();

									switch ( $job->post_status ) {
										case 'publish' :
										$actions['edit'] = array( 'label' => __( 'Edit', 'wp-job-manager' ), 'nonce' => false );
										break;
										case 'pending' :
										$actions['edit'] = array( 'label' => __( 'Edit', 'wp-job-manager' ), 'nonce' => false );
										break;
									}

									$actions['delete'] = array( 'label' => __( 'Delete', 'wp-job-manager' ), 'nonce' => true );
									$actions = apply_filters( 'job_manager_my_job_actions', $actions, $job );

									foreach ( $actions as $action => $value ) {
										$action_url = add_query_arg( array( 'action' => $action, 'job_id' => $job->ID ) );
										if ( $value['nonce'] ) {
											$action_url = wp_nonce_url( $action_url, 'job_manager_my_job_actions' );
										}
										echo '<li><a href="' . esc_url( $action_url ) . '" class="job-dashboard-action-' . esc_attr( $action ) . '">' . esc_html( $value['label'] ) . '</a></li>';
									}
									?>
								</ul>
							</li>
						</ul>
					</div>
				</div>																			
			</div>
			<hr>	
		<?php endforeach; ?>
	<?php endif; ?>
	<?php get_job_manager_template( 'pagination.php', array( 'max_num_pages' => $max_num_pages ) ); ?>
	<div class="pull-left" id="intro1">
		<a class="ghost button asidepad" href="http://tiptapgo.co/add-class/">Add Class</a>
	</div>
</div>
<?php 
global $current_user;
get_currentuserinfo();

function print_my_inline_script() {
	$user_ID = get_current_user_id();
	if ( wp_script_is( 'jquery', 'done' ) ) {
		?>
		<script type="text/javascript">
			jQuery(window).load(function(){

				var tour = {
					id: "hello-hopscotch",
					steps: [
					{
						title: "",
						content: "Publish your class information in less than a minute.",
						target: intro1,
						placement: "bottom"
					}																																																		
					]
				};
				hopscotch.startTour(tour);									

			})		
			mixpanel.identify(<?php echo $current_user->user_email; ?>);
			if(jQuery('#job-manager-job-dashboard').find('.row').length > 0){
				mixpanel.people.set({
					"$first_class_created": true
				});				
			} else{
				mixpanel.people.set({
					"$first_class_created": false
				});			
			}
		</script>
		<?php
	}
}
add_action( 'wp_footer', 'print_my_inline_script' );