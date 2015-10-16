
<?php
/**
 * The template for displaying the call to action
 */

if ( ! listify_theme_mod( 'call-to-action-display' ) ) {
	return;
}

$title = listify_theme_mod( 'call-to-action-title' );
$description = listify_theme_mod( 'call-to-action-description' );
$button_text = listify_theme_mod( 'call-to-action-button-text' );
$button_href = listify_theme_mod( 'call-to-action-button-href' );
$button_subtext = listify_theme_mod( 'call-to-action-button-subtext' );
if ( is_archive()){
	$title = "Can't find a Tutor?";
}
?>


<?php if(is_front_page()) { 
	$parentargs = array(
		'orderby'           => 'name', 
		'order'             => 'ASC',
		'hide_empty'        => true,
		'parent'            => 0,    
		); 

	$parentterms = get_terms('job_listing_category', $parentargs);
	?>
	<div class="popular-content">
		<div class="container">
			<div class="row">
				<hgroup class="text-center">
					<h2>Collections</h2>
				</hgroup>			
				<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
					<h6 class="cat-link-head">Trending Categories</h6>
					<div class="cat">
						<?php
						$popcat = array();		
						$linkcat = array();	
						$allcat = array();			
						foreach ($parentterms as $term) {
							$childargscat = array(
								'orderby'           => 'name', 
								'order'             => 'ASC',
								'hide_empty'        => false,
								'parent'            => $term->term_id,    
								);
							$childtermscat = get_terms('job_listing_category', $childargscat); 
							foreach ($childtermscat as $child) {
								$query = new WP_Query( array( 'job_listing_category' => $child->name ) );
								$popcat[$child->term_id] = $query->found_posts;
								$linkcat[$child->term_id] = get_term_link( $child );
								$allcat[$child->term_id] = $child->name;
							}
						}
						arsort($popcat);
						$popctr = 0;
						foreach ($popcat as $key => $value) {
							$popctr++;
							if($popctr > 5){
								break;
							}	
							?>
							<div class="cat-link">
								<a href="<?php echo $linkcat[$key];?>" title="<?php echo $allcat[$key]; ?>"><?php echo $allcat[$key]; ?></a>
							</div>
							<?php } ?>
						</div>	
						<a href="http://tiptapgo.co/class-categories/" class="cat-link-head" id="showallcat">See All Categories</a>													
					</div>
					<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
						<?php
						$args = apply_filters( 'job_manager_get_dashboard_jobs_args', array(
							'post_type'           => 'job_listing',
							'post_status'         => array( 'publish', 'expired', 'pending' ),
							'ignore_sticky_posts' => 1,
							'posts_per_page'      => 999999999999999999999999999999999,
							'orderby'             => 'date',
							'order'               => 'desc',
							) );
						$jobs = new WP_Query;
						$jobs->query( $args );					

						$listid = 0;
						$locationArray = array();
						$fullAddressArr = array();
						$addressDump = array();
						while ( $jobs->have_posts() ) {
							$jobs->the_post();
							
							$listid = get_the_ID();
							if($listid!= ''){
								$fullAddress = get_post_meta((int)$listid,'geolocation_formatted_address',true);
								$city = get_post_meta((int)$listid,'geolocation_city',true);
								$state = get_post_meta((int)$listid,'geolocation_state_long',true);								
								array_push($addressDump, $fullAddress);
								if(strpos($city, ',')){
									$city = explode(',', $city);
									$city = $city[count($city)-1];
								}
								if(strtolower(trim($city)) == strtolower(trim($state))){
									continue;
								}
								$fullAddressArr = explode(',', $fullAddress);								
								for($i = 0 ; $i < count($fullAddressArr); $i++){
									if(strtolower($fullAddressArr[$i]) == strtolower($city)){
										if(array_key_exists(($i-1),$fullAddressArr)){
											if(strtolower($fullAddressArr[$i-1]) == strtolower($city)){
												continue;
											}
											$sublocality = $fullAddressArr[$i-1];
										} else{
											continue;
										}
										if(!array_key_exists($sublocality, $locationArray)){											
											$locationArray[$sublocality] = 1;
										}
									}
								}	
							}						
						}
						foreach ($locationArray as $key => $value) {
							foreach ($addressDump as $location) {
								if(strpos($location, $key)){
									$locationArray[$key]++;
								}
							}
						}
						unset($addressDump);							
						arsort($locationArray);
						?>
						<h6 class="cat-link-head">Popular Locations</h6>
						<div class="cat">
							<?php
							$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
							$url .= $_SERVER['SERVER_NAME'];
							$url .= $_SERVER['REQUEST_URI'];
							foreach ($locationArray as $key => $value) {
								$locationArrayCtr++;
								if($locationArrayCtr > 5){
									break;
								}	
								?>
								<div class="cat-link">
									<a href="<?php echo $url."/classes/?search_keywords=&search_location=".$key;?>" title="<?php echo $key; ?>"><?php echo $key; ?></a>
								</div>
								<?php }
								?>
							</div>	
						</div>
						<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
							<h6 class="cat-link-head">Just Added</h6>
							<div class="cat">
								<?php
								$queryObject = new WP_Query( 'post_type=job_listing&posts_per_page=5' );
								if ($queryObject->have_posts()) {
									while ($queryObject->have_posts()) {
										$queryObject->the_post();
										?>
										<div class="cat-link">
											<a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a>
										</div>			
										<?php
									}    
								}?>
							</div>
						</div>
					</div>		
				</div>
			</div>
			<?php } ?>	
			<div class="call-to-action <?php if ( is_archive()){ echo 'white-back';} ?>">
				<div class="container">
					<div class="row">

						<div class="col-md-6 col-lg-8 col-sm-12 col-xs-12">
							<h1 class="cta-title"><?php echo esc_attr( $title ); ?></h1>
							<?php if( ! is_archive() ) { ?>
							<div class="cta-description"><?php echo wpautop( esc_attr( $description ) ); ?></div>
							<?php } ?>
						</div>

						<div class="cta-button-wrapper col-md-6 col-lg-4 col-sm-12 col-xs-12">
							<a class="cta-button button" href="<?php echo esc_url( $button_href ); ?>"><?php echo esc_attr( $button_text ); ?></a>
							<small class="cta-subtext"><?php echo esc_attr( $button_subtext ); ?></small>

						</div>

					</div>
				</div>

			</div>	
			<?php if(is_front_page()) { ?>		
			<div class="newsletter">
				<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
					<div class="container">
						<div class="row"> 		
							<div class="col-sm-6 col-md-6 col-lg-6 col-xs-12">
								<h3>Subscribe Newsletter</h3>
								<h4>Stay up to date with all things TipTapGo!</h4>
							</div>
							<div class="col-sm-6 col-md-6 col-lg-6 col-xs-12">
								<form action="">
									<div class="input-group row">
										<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
											<input type="email" placeholder="Your email address" class="newsinput form-control" name="newsemail" id="newsemail">
											<span class="input-group-btn">
												<button class="newsbutton">Never miss a post!</button>
											</span>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
