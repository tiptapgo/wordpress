<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Listify
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<!-- start Mixpanel --><script type="text/javascript">(function(f,b){if(!b.__SV){var a,e,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=f.createElement("script");a.type="text/javascript";a.async=!0;a.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"http://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";e=f.getElementsByTagName("script")[0];e.parentNode.insertBefore(a,e)}})(document,window.mixpanel||[]);
mixpanel.init("f39c8dc269a848da80cc50761b6ddfe4");</script><!-- end Mixpanel -->

<!-- moved snippit tracking to footer -->

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-60139822-1', 'auto');
  ga('send', 'pageview');

</script>

<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">
		<div class="primary-header">
			<div class="container">
				<div class="primary-header-inner">
					<div class="site-branding">
						<?php $header_image = get_header_image(); ?>
						<?php if ( ! empty( $header_image ) ) : ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="custom-header"><img src="<?php echo esc_url( $header_image ); ?>" alt=""></a>
						<?php endif; ?>

						<h1 class="site-title"><a id="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
					</div>

					<div class="primary nav-menu">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'primary',
								'container_class' => 'nav-menu-container',
								'container_id'    => 'nav-menu-container'
							) );
						?>
					</div>
				</div>
				<?php if(false): ?>
				<div id="search-header" class="search-overlay">
					<div class="container">
						<?php locate_template( array( 'searchform-header.php', 'searchform.php' ), true, false ); ?>
						<a href="#search-header" data-toggle="#search-header" class="ion-close search-overlay-toggle"></a>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<div class="container">
				<a href="#" class="navigation-bar-toggle">
					<i class="ion-navicon-round"></i>
					<?php echo listify_get_theme_menu_name( 'primary' ); ?>
				</a>

				<div class="navigation-bar-wrapper">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'primary',
							'container_class' => 'primary nav-menu',
							'menu_class' => 'primary nav-menu'
						) );

						wp_nav_menu( array(
							'theme_location' => 'secondary',
							'container_class' => 'secondary nav-menu',
							'menu_class' => 'secondary nav-menu'
						) );
					?>
				</div>	
			</div>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<?php do_action( 'listify_content_before' ); ?>

	<div id="content" class="site-content">