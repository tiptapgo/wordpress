<?php
/**
 * WP Job Manager
 */

class Listify_WP_Job_Manager extends listify_Integration {

	public function __construct() {
		$this->includes = array(
			'class-walker-category.php',
			'class-walker-category-dropdown.php',
			'class-wp-job-manager-customizer.php',
			'class-wp-job-manager-gallery.php',
			'class-wp-job-manager-map.php',
			'class-wp-job-manager-claim.php',
			'class-wp-job-manager-business-hours.php',
			'class-wp-job-manager-service.php',
			'class-wp-job-manager-services.php',
			'class-wp-job-manager-template.php',
			'class-wp-job-manager-submission.php',
			'class-wp-job-manager-categories.php',
			'class-taxonomy-breadcrumbs.php'
		);

		$this->integration = 'wp-job-manager';

		parent::__construct();
	}

	public function setup_actions() {
		/* add_action( 'wp', array( $this, 'remove_translations' ) ); */
		add_filter( 'job_manager_show_addons_page', '__return_false' );

		add_action( 'init', array( $this, 'init' ), 0 );

		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

		add_filter( 'submit_job_form_fields', array( $this, 'submit_job_form_fields' ) );
		add_filter( 'register_post_type_job_listing', array( $this, 'register_post_type_job_listing' ) );

		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

		add_filter( 'job_manager_output_jobs_defaults', array( $this, 'job_manager_output_jobs_defaults' ) );
		add_filter( 'get_job_listings_query_args', array( $this, 'get_job_listings_query_args' ), 10, 2 );

		add_filter( 'submit_job_form_login_url', array( $this, 'login_url' ) );
	}

	function remove_translations() {
		global $job_manager;

		remove_action( 'plugins_loaded', array( $job_manager, 'load_plugin_textdomain' ) );
	}

	public function init() {
		$this->customizer = new Listify_WP_Job_Manager_Customizer;
		$this->template = new Listify_WP_Job_Manager_Template;
		$this->map = new Listify_WP_Job_Manager_Map;
		$this->gallery = new Listify_WP_Job_Manager_Gallery;
		$this->claim = new Listify_WP_Job_Manager_Claim;
		$this->services = new Listify_WP_Job_Manager_Services;
		$this->submission = new Listify_WP_Job_Manager_Submission;
		$this->categories = new Listify_WP_Job_Manager_Categories;
	}

	public function after_setup_theme() {
		add_theme_support( 'job-manager-templates' );
	}

	public function wp_enqueue_scripts() {
		wp_dequeue_style( 'wp-job-manager-frontend' );
		wp_dequeue_style( 'chosen' );
	}

	public function submit_job_form_fields( $fields ) {
		return $fields;

		unset( $fields[ 'company' ][ 'company_name' ] );
		unset( $fields[ 'company' ][ 'company_tagline' ] );
		unset( $fields[ 'company' ][ 'company_logo' ] );

		return $fields;
	}

	public function register_post_type_job_listing( $args ) {
		$args[ 'supports' ][] = 'comments';
		$args[ 'supports' ][] = 'thumbnail';

		if ( apply_filters( 'listify_override_job_manager_caps', true ) ) {
			unset( $args[ 'capabilities' ] );
		}

		return $args;
	}

	public function job_manager_output_jobs_defaults( $default ) {
		$type = get_queried_object();

		if ( is_tax( 'job_listing_type' ) ) {
			$default[ 'job_types' ] = $type->slug;
			$default[ 'selected_job_types' ] = $type->slug;
			$default[ 'show_categories' ] = true;
		} elseif ( is_tax( 'job_listing_category' ) ) {
			$default[ 'show_categories' ] = true;
			$default[ 'categories' ] = $type->slug;
			$default[ 'selected_category' ] = $type->slug;
		} elseif ( is_search() ) {
			$default[ 'keywords' ] = get_search_query();
			$default[ 'show_filters' ] = false;
		}

		if ( is_home() || listify_is_widgetized_page() ) {
			$default[ 'show_category_multiselect' ] = false;
		}

		if ( isset( $_GET[ 'search_categories' ] ) ) {
			$category = get_term_by( 'ID', absint( $_GET[ 'search_categories' ] ), 'job_listing_category' );

			$default[ 'show_categories' ] = true;
			$default[ 'categories' ] = $_GET[ 'search_categories' ];
		}

		return $default;
	}

	public function pre_get_posts( $query ) {
		if ( is_admin() ) {
			return;
		}

		if ( $query->is_author() && $query->is_main_query() ) {
			$query->set( 'posts_per_page', 3 );
			$query->set( 'post_type', array( 'job_listing' ) );
			$query->set( 'post_status', 'publish' );
		}
	}

	public function get_job_listings_query_args( $query_args, $args ) {
		if ( is_author() ) {
			$author = get_user_by( 'slug', get_query_var( 'author_name' ) );

			$query_args[ 'author' ] = $author->ID;
			$query_args[ 'posts_per_page' ] = 3;
			$query_args[ 'paged' ] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		}

		if ( isset( $args[ 'no_found_rows' ] ) ) {
			$query_args[ 'no_found_rows' ] = true;
			$query_args[ 'cache_results' ] = false;
		}

		if ( isset( $args[ 'post__in' ] ) ) {
			$query_args[ 'post__in' ] = $args[ 'post__in' ];
		}

		return $query_args;
	}

	public function login_url( $url ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return $url;
		}

		return get_permalink( wc_get_page_id( 'myaccount' ) );
	}

}

$GLOBALS[ 'listify_job_manager' ] = new Listify_WP_Job_Manager();
