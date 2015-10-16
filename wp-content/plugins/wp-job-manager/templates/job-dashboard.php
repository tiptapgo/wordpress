<?php if(isset($_GET['edit']) && $_GET['edit'] == true) { ?>
<div class="job-manager-message">Your class information has been updated successfully.</div>
<?php } ?>

<?php if(isset($_GET['added']) && $_GET['added'] == true) { ?>
<div class="job-manager-message"> Your class was created successfully. It will be public after we approve in next 2-3 days.</div>
<?php } ?>

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
		background: #3396d1 !important;
		color:  #fff!important;
		box-shadow: none !Important;
		border: 1px solid #3396d1 !important;
		border-radius: 5px !important;
		padding: 5px 30px;
		font-size: 16px !important;
	}
	.job-dashboard-action-edit,.job-dashboard-action-delete{
		background: #fff !important;
		box-shadow: none !Important;
		border-radius: 5px !important;
		padding: 5px 20px;
		font-size: 16px !important;	
		margin: 0 10px;	
	}
	.job-dashboard-action-edit{
		color:  #3396d1 !important;
		border: 1px solid #3396d1 !important;
	}
	.job-dashboard-action-delete{
		color:  #e67c6c !important;
		border: 1px solid #e67c6c !important;
	}
	.job-dashboard-action-delete:before{
		display: none;
	}
	.job-dashboard-action-edit:before{
		display: none;
	}	
	.row-head{
		background: #eee;
		color: #717a8f;
		font-size: 16px;
		padding: 5px 0;
		margin: 20px 0;
	}	
</style>
<div id="job-manager-job-dashboard">
	<div class="row">
		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">	
			<h1 style="margin:20px 0"><?php _e( 'Your Classes', 'wp-job-manager' ); ?></h1>
			<?php if ( ! $jobs ) { ?>
				<h2><?php _e( 'No classes found', 'wp-job-manager' ); ?></h2>
			<?php } ?>	
		</div>	
		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
			<div class="pull-right" id="intro1" style="margin:20px 0">
				<a class="ghost button asidepad" href="<?php echo get_site_url()."/add-class/"; ?>" >Add new class</a>
			</div>	
		</div>		
	</div>	
	<?php if ( $jobs ) : ?>
		<div class="row row-head">
			<div class="col-xs-12 col-md-3 col-lg-3 col-sm-3 text-center">
				Class
			</div>
			<div class="col-xs-12 col-md-3 col-lg-3 col-sm-3 text-center">
				Category
			</div>
			<div class="col-xs-12 col-md-3 col-lg-3 col-sm-3 text-center">
				Status
			</div>
			<div class="col-xs-12 col-md-3 col-lg-3 col-sm-3 text-center">
				Actions
			</div>															
		</div>
		<?php foreach ( $jobs as $job ) : ?>
			<div class="row">
				<div class="col-xs-12 col-md-3 col-lg-3 col-sm-3 classitem">
					<h2 style="margin: 0 20px;line-height: 25px;">
					<?php
						switch ( $job->post_status ) {
							case 'publish' : ?>
								<a href="<?php echo get_post_permalink($job->ID); ?>"><h2 style="margin: 0 20px;line-height: 25px;"><?php echo $job->post_title; ?></h2></a>
								<?php
							break;
							case 'pending' :
								echo $job->post_title;
							break;
						}

					 ?>
					</h2>
					
				</div>
				<div class="col-xs-12 col-md-3 col-lg-3 col-sm-3 classitem text-center">
					<?php $terms = wp_get_post_terms( $job->ID, array( 'job_listing_category' ) ); ?>
					<?php foreach ( $terms as $term ) : ?>
						<h4><?php echo $term->name; ?></h4>
					<?php endforeach; ?>
				</div>	
				<div class="col-xs-12 col-md-3 col-lg-3 col-sm-3 classitem text-center">
					<h4><?php the_job_status( $job ); ?></h4>
				</div>					
				<div class="col-xs-12 col-md-3 col-lg-3 col-sm-3 nav-menu actions">
					<div class="nav-menu-container">
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
										if(esc_attr( $action ) == 'edit'){
											$action_url = get_site_url()."/update-class?id=".$job->ID;
										}
										if ( $value['nonce'] ) {
											$action_url = wp_nonce_url( $action_url, 'job_manager_my_job_actions' );
										}
										echo '<a href="' . esc_url( $action_url ) . '" class="button job-dashboard-action-' . esc_attr( $action ) . '">' . esc_html( $value['label'] ) . '</a>';
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
						placement: "top"
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