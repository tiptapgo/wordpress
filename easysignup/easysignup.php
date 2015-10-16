<?php
require_once("../wp-load.php");

if(isset($wp_query->query_vars['category'])) {
	$title = urldecode($wp_query->query_vars['category']);
}

$args = array(
	'meta_query' => array(
		array(
			'key' => 'slug',
			'value' => $title,
			'compare' => 'LIKE'
			)
		),
	'post_type' => 'landing-pages',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'caller_get_posts'=> 1
	);

$my_query = null;
$postid = '';
$my_query = new WP_Query($args);
if( $my_query->have_posts() ) {
	while ($my_query->have_posts()) {
		$my_query->the_post();
		$postid = get_the_ID();
		if($postid != ''){
			break;
		}
	}
}

$backgroundimage = get_post_meta($postid,'backgroundimage', true);
$title = get_post_meta($postid,'slug', true);                  
$formfeature1 = get_post_meta($postid,'formfeature1', true);                  
$formfeature2 = get_post_meta($postid,'formfeature2', true);                  
$formfeature3 = get_post_meta($postid,'formfeature3', true);                  
$formicon1 = get_post_meta($postid,'formicon1', true);                  
$formicon2 = get_post_meta($postid,'formicon2', true);                  
$formicon3 = get_post_meta($postid,'formicon3', true);                                 
$hiwicon1 = get_post_meta($postid,'hiwicon1', true);                  
$hiwicon2 = get_post_meta($postid,'hiwicon2', true);                  
$hiwicon3 = get_post_meta($postid,'hiwicon3', true);                  
$hiwtitle1 = get_post_meta($postid,'hiwtitle1', true);                  
$hiwtitle2 = get_post_meta($postid,'hiwtitle2', true);                  
$hiwtitle3 = get_post_meta($postid,'hiwtitle3', true);                  
$hiwdesc1 = get_post_meta($postid,'hiwdesc1', true);                  
$hiwdesc2 = get_post_meta($postid,'hiwdesc2', true);                  
$hiwdesc3 = get_post_meta($postid,'hiwdesc3', true);                  
$featureimg1 = get_post_meta($postid,'featureimg1', true);                  
$featureimg2 = get_post_meta($postid,'featureimg2', true);                  
$featureimg3 = get_post_meta($postid,'featureimg3', true);                  
$featurepara11 = get_post_meta($postid,'featurepara11', true);                  
$featurepara12 = get_post_meta($postid,'featurepara12', true);                  
$featurepara21 = get_post_meta($postid,'featurepara21', true);                  
$featurepara22 = get_post_meta($postid,'featurepara22', true);                  
$featurepara31 = get_post_meta($postid,'featurepara31', true);                  
$featurepara32 = get_post_meta($postid,'featurepara32', true);                  
$featurehead1 = get_post_meta($postid,'featurehead1', true);                 
$featurehead2 = get_post_meta($postid,'featurehead2', true);                  
$featurehead3 = get_post_meta($postid,'featurehead3', true);  

function to_title_case( $string ) {
     $articles_conjunctions_prepositions = array(
          'a','an','the',
          'and','but','or','nor',
          'if','then','else','when',
          'at','by','from','for','in',
          'off','on','out','over','to','into','with'
     );
     $acronyms_and_such = array(
         'asap', 'unhcr', 'wpse', 'wtf'
     );
     $words = explode( ' ', mb_strtolower( $string ) );

     foreach ( $words as $position => $word ) {
         if( in_array( $word, $acronyms_and_such ) ) {
             $words[$position] = mb_strtoupper( $word );
         } elseif (
             0 === $position ||
             ! in_array( $word, $articles_conjunctions_prepositions ) 
         ) {
             $words[$position] = ucwords( $word );
         }
     }         
     $string = implode( ' ', $words );
     return $string;
}                
$title = to_title_case($title);
include_once('landheader.php');

?>

<!-- Main content -->
<div id="content" class="site-content">
	<!-- top section with sign up below -->
	<div style="background-image: url(<?php echo $backgroundimage;?>);height:98%;height: calc(100% - 75px)" class="homepage-cover page-cover entry-cover has-image">
		<div class="cover-wrapper container">
			<div class="row">
				<div class="col-md-6 col-xs-12 xs-hidden"></div>
				<div class="col-md-1 col-xs-12 xs-hidden"></div>
				<div class="col-md-5 col-xs-12">
					<div class="signupcta">
						<h2>Find Tutor for <?php echo $title;?></h2>
						<div class="init">									
							<div class="row featurerow">
								<div class="col-md-3 col-xs-3 signupicon"><i class="<?php echo $formicon1;?>"></i></div>
								<div class="col-md-9 col-xs-9 signupfeature"><span><?php echo $formfeature1;?></span></div>
							</div>
							<div class="row featurerow">
								<div class="col-md-3 col-xs-3 signupicon"><i class="<?php echo $formicon2;?>"></i></div>
								<div class="col-md-9 col-xs-9 signupfeature"><span><?php echo $formfeature2;?></span></div>
							</div>
							<div class="row featurerow">
								<div class="col-md-3 col-xs-3 signupicon"><i class="<?php echo $formicon3;?>"></i></div>
								<div class="col-md-9 col-xs-9 signupfeature"><span><?php echo $formfeature3;?></span></div>
							</div>
							<button>Sign Up</button>
						</div>
						<div class="convert hide">
							<form action="" method="post" id="convertform">
								<fieldset>
									<div class="field">
										<div class="input-group">
											<span class="input-group-addon" id="basic-addon1"><i style="padding: 0 3.5px;" class="ion-ios-person-outline"></i></span>
											<input type="text" name="convert_name" id="convert_name" placeholder="Your Name">
										</div>	
									</div>
								</fieldset>		
								<fieldset>
									<div class="field">
										<div class="input-group">
											<span class="input-group-addon" id="basic-addon1"><i style="padding: 0 2px;" class="ion-ios-email-outline"></i></span>
											<input type="email" name="convert_email" id="convert_email" placeholder="Your Email">
										</div>
										<div class="description">Your privacy is very important to us. Your email is never made public.</div>	
									</div>
								</fieldset>		
								<fieldset>
									<div class="field">
										<div class="input-group">
											<span class="input-group-addon" id="basic-addon1">+91</span>
											<input type="tel" name="convert_mobile" id="convert_mobile" placeholder="Your Mobile Number" maxlength="10">
										</div>
										<div class="description">Your privacy is very important to us. Your mobile number is never made public.</div>
									</div>												
								</fieldset>								
								<input type="submit" name="submit" id="submit" value="Submit">
							</form>
						</div>
						<div class="converted hide">
							<div class="thanks">
								<i class="ion-ios-checkmark-outline"></i>
								<strong>Thanks</strong>
							</div>	
						</div>																
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- top section with sign up ends -->
	<!-- how it works below -->
	<div class="container homepage-hero-style-image">
		<aside id="text-5" class="home-widget widget_text">
			<hgroup class="home-widget-section-title">
				<h2 class="home-widget-title">How it Works</h2></hgroup>
				<div class="textwidget">
					<div class="row">
						<div class="col-md-12 col-xs-12 col-lg-12 col-sm-12 text-center">
							<div class="col-md-4 col-xs-12 col-lg-4 col-sm-4 hiw-item">
								<div class="hiw-icon">
									<span class="<?php echo $hiwicon1;?>"></span>
								</div>
								<h2 class="hiw-head"><?php echo $hiwtitle1;?></h2>
								<div class="hiw-text">
									<?php echo $hiwdesc1;?>
								</div>
							</div>
							<div class="col-md-4 col-xs-12 col-lg-4 col-sm-4 hiw-item">
								<div class="hiw-icon">
									<span class="<?php echo $hiwicon2;?>"></span>
								</div>
								<h2 class="hiw-head"><?php echo $hiwtitle2;?></h2>
								<div class="hiw-text">
									<?php echo $hiwdesc2;?>
								</div>
							</div>
							<div class="col-md-4 col-xs-12 col-lg-4 col-sm-4 hiw-item">
								<div class="hiw-icon">
									<span class="<?php echo $hiwicon3;?>"></span>
								</div>
								<h2 class="hiw-head"><?php echo $hiwtitle3;?></h2>
								<div class="hiw-text">
									<?php echo $hiwdesc3;?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</aside>
		</div>
	</div>
</div>					
<!-- how it works ends -->
<!-- Feature set begins -->
<div class="container homepage-hero-style-image">
	<aside id="text-6" class="home-widget widget_text">
		<div class="textwidget">
			<div class="row">
				<div class="col-md-6 col-xs-12"><img src="<?php echo $featureimg1; ?>" alt="Feature 1 image"></div>
				<div class="col-md-6 col-xs-12">
					<h3><?php echo $featurehead1; ?></h3>
					<p><?php echo $featurepara11; ?></p>
					<p><?php echo $featurepara12; ?></p>
				</div>
			</div>
			<br><br>
			<div class="row">
				<div class="col-md-6 col-xs-12">
					<h3><?php echo $featurehead2; ?></h3>
					<p><?php echo $featurepara21; ?></p>
					<p><?php echo $featurepara22; ?></p>					
				</div>
				<div class="col-md-6 col-xs-12"><img src="<?php echo $featureimg2; ?>" alt="Feature 2 image"></div>
			</div>
			<br><br>
			<div class="row">
				<div class="col-md-6 col-xs-12"><img src="<?php echo $featureimg3; ?>" alt="Feature 3 image"></div>
				<div class="col-md-6 col-xs-12">
					<h3><?php echo $featurehead3; ?></h3>
					<p><?php echo $featurepara31; ?></p>
					<p><?php echo $featurepara32; ?></p>					
				</div>
			</div>														
		</div>
	</aside>							
</div>
<!-- Feature Set ends -->
<!-- Main content ends -->
<?php
include_once('landfooter.php');
?>