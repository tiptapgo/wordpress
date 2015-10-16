			<?php

			function is_decimal( $val )
			{
				return is_numeric( $val ) && floor( $val ) != $val;
			}			
			$curpostid = get_the_ID();
			$type = 'reviews';
			$args=array(
				'meta_query' => array(
					array(
						'key' => 'review_id',
						'value' => $curpostid,
						'compare' => 'LIKE'
						)
					),
				'post_type' => $type,
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'caller_get_posts'=> 1
				);

			$my_query = null;
			$my_query = new WP_Query($args);
			$revArray = array();
			$totalReviews = $my_query->post_count;
			$comm = 0;
			$sk = 0;
			$ts = 0;
			$dis = 0;
			if( $my_query->have_posts() ) {
				while ($my_query->have_posts()) {
					$my_query->the_post();
					$revid = get_the_ID();
					$comm += get_post_meta($revid, 'review_comm', true);
					$sk += get_post_meta($revid, 'review_sk', true);
					$ts += get_post_meta($revid, 'review_ts', true);
					$dis += get_post_meta($revid, 'review_dis', true);									
				}
			}
			if($totalReviews != 0){
				$orcomm = ($comm/$totalReviews);
				$orsk = ($sk/$totalReviews);
				$orts = ($ts/$totalReviews);
				$ordis = ($dis/$totalReviews);			
				$comm = floor($comm/$totalReviews);
				$sk = floor($sk/$totalReviews);
				$ts = floor($ts/$totalReviews);
				$dis = floor($dis/$totalReviews);
			}
			else{
				$orcomm = 0;
				$orsk = 0;
				$orts = 0;
				$ordis = 0;
				$comm = 0;
				$sk = 0;
				$ts = 0;
				$dis = 0;
			}
			$commempty = ((is_decimal($orcomm))? (4 - $comm) : (5 - $comm));
			$skempty = ((is_decimal($orsk))? (4 - $sk) : (5 - $sk));
			$tsempty = ((is_decimal($orts))? (4 - $ts) : (5 - $ts));
			$disempty = ((is_decimal($ordis))? (4 - $dis) : (5 - $dis));
			$tutorAvgRating = (round(($orcomm + $orsk + $orts + $ordis) / 2) /2);
			update_post_meta($curpostid,'_rating',$tutorAvgRating);
			update_post_meta($curpostid,'rating',$tutorAvgRating);
			wp_reset_query();
			if($totalReviews > 0): ?>
			<aside class="widget">
				<h1 class="widget-title widget-title-job_listing ion-ios-chatbubble-outline">Reviews</h1>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="ratinglabel">Communication</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="star-wrap">
								<?php
								for ($i=0; $i < $comm ; $i++){
									echo '<span class="fa fa-star ratingstar"></span>';
								}
								if(is_decimal($orcomm)){
									echo '<span class="fa fa-star-half-o ratingstar"></span>';
								}
								for ($i=0; $i < $commempty ; $i++){
									echo '<span class="fa fa-star-o ratingstar"></span>';
								}
								?>								
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">									
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="ratinglabel">Subject Knowledge</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="star-wrap">
								<?php
								for ($i=0; $i < $sk ; $i++){
									echo '<span class="fa fa-star ratingstar"></span>';
								}
								if(is_decimal($orsk)){
									echo '<span class="fa fa-star-half-o ratingstar"></span>';
								}								
								for ($i=0; $i < $skempty ; $i++){
									echo '<span class="fa fa-star-o ratingstar"></span>';
								}
								?>								
							</div>
						</div>	
					</div>								
				</div>	
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="ratinglabel">Teaching Style</div>									
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="star-wrap">
								<?php
								for ($i=0; $i < $ts ; $i++){
									echo '<span class="fa fa-star ratingstar"></span>';
								}
								if(is_decimal($orts)){
									echo '<span class="fa fa-star-half-o ratingstar"></span>';
								}								
								for ($i=0; $i < $tsempty ; $i++){
									echo '<span class="fa fa-star-o ratingstar"></span>';
								}
								?>								
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">									
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="ratinglabel">Discipline</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="star-wrap">
								<?php
								for ($i=0; $i < $dis ; $i++){
									echo '<span class="fa fa-star ratingstar"></span>';
								}
								if(is_decimal($ordis)){
									echo '<span class="fa fa-star-half-o ratingstar"></span>';
								}								
								for ($i=0; $i < $disempty ; $i++){
									echo '<span class="fa fa-star-o ratingstar"></span>';
								}
								?>								
							</div>
						</div>
					</div>									
				</div>
				<div class="review-body">
					<?php 
					if( $my_query->have_posts() ) {
						while ($my_query->have_posts()) {
							$my_query->the_post(); ?>
							<?php if(get_the_content() == ''){
								continue;
							}
							?>							
							<div>

								<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
									<div><?php the_content(); ?></div>
									<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 rev-meta">
										<?php 
										$revid1 = get_the_ID();
										$anonymous = get_post_meta($revid1, 'review_anonymous', true);
										$revname = get_post_meta($revid1, 'review_name', true);
										if($anonymous == 'true')
											echo "- Anonymous";
										else if($anonymous == 'false')
											echo "-".$revname." on ".date("d-M, Y", strtotime(get_the_date('Y-m-d')));
										?>
									</div>
								</div>
							</div>	
							<hr>						
							<?php }
						}			
						wp_reset_query();
						?>
					</div>
					<?php if(is_user_logged_in() && get_current_user_id() == get_the_author_meta( 'ID' )) {?>
					<div class="pull-left">
						<form action="http://tiptapgo.co/invite-reviews/" method="POST">
							<input type="hidden" name="id" id="id" value="<?php echo $curpostid; ?>">
							<input type="submit" name="submit" id="submit" value="Ask for Reviews" class="button">
						</form>
					</div>
					<?php } ?>
					<div class="pull-right">
						<form action="http://tiptapgo.co/ratings/" method="POST">
							<input type="hidden" name="type" id="type" value="listing">
							<input type="hidden" name="id" id="id" value="<?php echo $curpostid; ?>">
							<input type="submit" name="submit" id="submit" value="Review this Class" class="button">
						</form>
					</div>																								
				</aside>
			<?php else: ?>
				<aside class="widget">
					<h1 class="widget-title widget-title-job_listing ion-ios-chatbubble-outline">Reviews</h1>
					<h6>No reviews yet, be the first one to review</h6>
					<?php if(is_user_logged_in() && get_current_user_id() == get_the_author_meta( 'ID' )) {?>
					<div class="pull-left">
						<form action="http://tiptapgo.co/invite-reviews/" method="POST">
							<input type="hidden" name="id" id="id" value="<?php echo $curpostid; ?>">
							<input type="submit" name="submit" id="submit" value="Ask for Reviews" class="button">
						</form>
					</div>
					<?php } ?>
					<div class="pull-right">
						<form action="http://tiptapgo.co/ratings/" method="POST">
							<input type="hidden" name="type" id="type" value="listing">
							<input type="hidden" name="id" id="id" value="<?php echo $curpostid; ?>">
							<input type="submit" name="submit" id="submit" value="Review this Class" class="button">
						</form>
					</div>											
				</aside>											
			<?php endif; ?>		