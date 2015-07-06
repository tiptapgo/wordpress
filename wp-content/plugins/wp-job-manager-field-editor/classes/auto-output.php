<?php

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'WP_Job_Manager_Field_Editor_Sort' ) ) require_once( WPJM_FIELD_EDITOR_PLUGIN_DIR . '/classes/sort.php' );
/**
 * Class WP_Job_Manager_Field_Editor_Auto_Output
 *
 * @since 1.1.9
 *
 */
class WP_Job_Manager_Field_Editor_Auto_Output {

	private static $instance;
	private        $available_options = array();
	private        $fields;
	private        $output_as;

	function __construct() {

		// JOB LISTING ACTIONS
		add_action( 'single_job_listing_start', array( $this, 'single_job_listing_start' ), 1 );
		add_action( 'single_job_listing_meta_before', array( $this, 'single_job_listing_meta_before' ), 100 );
		// Start <ul>
		add_action( 'single_job_listing_meta_start', array( $this, 'single_job_listing_meta_start' ), 100 );
		add_action( 'single_job_listing_meta_end', array( $this, 'single_job_listing_meta_end' ), 100 );
		// End </ul>
		add_action( 'single_job_listing_meta_after', array( $this, 'single_job_listing_meta_after' ), 100 );
		// Before Company Meta
		add_action( 'single_job_listing_start', array( $this, 'single_job_listing_company_before' ), 25 );
		// After Company Meta
		add_action( 'single_job_listing_start', array( $this, 'single_job_listing_company_after' ), 35 );
		add_filter( 'the_job_description', array( $this, 'the_job_description' ), 100 );
		add_action( 'single_job_listing_end', array( $this, 'single_job_listing_end' ), 1 );

		// Start <ul>
		add_action( 'job_listing_meta_start', array( $this, 'job_listing_meta_start' ), 100 );
		add_action( 'job_listing_meta_end', array( $this, 'job_listing_meta_end' ), 100 );
		// End </ul>

		// JOBIFY
		add_action( 'single_job_listing_info_before', array( $this, 'single_job_listing_info_before' ), 1 );
		add_action( 'single_job_listing_info_start', array( $this, 'single_job_listing_info_start' ), 1 );
		add_action( 'single_job_listing_info_end', array( $this, 'single_job_listing_info_end' ), 1 );
		add_action( 'single_job_listing_info_after', array( $this, 'single_job_listing_info_after' ), 1 );
		add_action( 'single_resume_info_before', array( $this, 'single_resume_info_before' ), 1 );
		add_action( 'single_resume_info_start', array( $this, 'single_resume_info_start' ), 1 );
		add_action( 'single_resume_info_end', array( $this, 'single_resume_info_end' ), 1 );
		add_action( 'single_resume_info_after', array( $this, 'single_resume_info_after' ), 1 );

		// JOBERA
		add_action( 'single_job_listing_above_logo', array($this, 'single_job_listing_above_logo'), 1 );
		add_action( 'single_job_listing_below_social_links', array($this, 'single_job_listing_below_social_links'), 1 );
		add_action( 'single_job_listing_below_location_map', array($this, 'single_job_listing_below_location_map'), 1 );

		// RESUME LISTING ACTIONS
		add_action( 'single_resume_start', array( $this, 'single_resume_start' ), 1 );
		add_action( 'single_resume_end', array( $this, 'single_resume_end' ), 1 );
		add_action( 'single_resume_meta_start', array( $this, 'single_resume_meta_start' ), 100 );
		add_action( 'single_resume_meta_end', array( $this, 'single_resume_meta_end' ), 100 );

	}

	/**
	 * Magic Method to handle Action Method calls not defined
	 *
	 * @since 1.1.9
	 *
	 * @param $name Name of function/method being called
	 * @param $args Arguments called with function/method
	 */
	public function __call( $name, $args ) {

		if ( strpos( $name, 'single_job_listing' ) !== FALSE ) {
			$this->single_action( $name, array( 'job', 'company' ) );
		}

		if ( strpos( $name, 'single_resume' ) !== FALSE ) {
			$this->single_action( $name, 'resume_fields' );
		}

	}

	/**
	 * Handle Single Action call from Magic Method
	 *
	 * Gets custom fields and filter out (remove) fields that do not match
	 * the current action, or fields that are disabled.
	 *
	 *
	 * @since 1.1.9
	 *
	 * @param $action string Filter action, also the value saved in configuration
	 * @param $groups string Custom field groups that should be used
	 */
	function single_action( $action, $groups ) {

		$fields = array();

		if ( is_array( $groups ) ) {
			foreach ( $groups as $group ) {
				$custom_fields = $this->fields()->get_custom_fields( TRUE, $group );
				$fields        = array_merge_recursive( $fields, $custom_fields );
			}
		} else {
			$fields = $this->fields()->get_custom_fields( TRUE, $groups );
		}

		$enabled_fields  = $this->fields()->fields_list_filter( $fields, array( 'status' => 'enabled' ) );
		$filtered_fields = $this->fields()->fields_list_filter( $enabled_fields, array( 'output' => $action ) );

		if ( ! empty( $filtered_fields ) ) $this->do_auto_output( $filtered_fields );
	}

	/**
	 * Get WP_Job_Manager_Field_Editor_Fields Object
	 *
	 * @since 1.1.9
	 *
	 * @return WP_Job_Manager_Field_Editor_Fields
	 */
	public function fields() {

		if ( ! class_exists( 'WP_Job_Manager_Field_Editor_Fields' ) ) include( WPJM_FIELD_EDITOR_PLUGIN_DIR . '/classes/fields.php' );
		if ( ! $this->fields ) $this->fields = WP_Job_Manager_Field_Editor_Fields::get_instance();

		return $this->fields;
	}

	/**
	 * Output using the_custom_field with configuration
	 *
	 *
	 * @since 1.1.9
	 *
	 * @param array $fields Field configuration for auto output
	 */
	function do_auto_output( $fields = array() ) {

		$li_actions = array( 'single_job_listing_meta_start', 'single_job_listing_meta_end', 'single_resume_meta_start', 'single_resume_meta_end' );

		$fieldSort = new WP_Job_Manager_Field_Editor_Sort( $fields, 'output_priority' );
		$fields = $fieldSort->float();

		foreach ( $fields as $meta_key => $config ) {

			if ( in_array( $config[ 'output' ], $li_actions ) ) $config[ 'li' ] = true;

			if ( function_exists( 'the_custom_field' ) ) {
				the_custom_field( $config[ 'meta_key' ], get_the_id(), $config );
			}

		}

	}

	/**
	 * Returns available output as options
	 *
	 *
	 * @since 1.1.9
	 *
	 * @param bool $as_array
	 * @param null $field_group
	 *
	 * @return array|string
	 */
	function get_output_as( $as_array = FALSE, $field_group = NULL ) {

		$this->output_as = array(
			'text'   => __( 'Standard Value Output (Regular text)', 'wp-job-manager-field-editor' ),
			'link'   => __( 'Link', 'wp-job-manager-field-editor' ),
			'image'  => __( 'Image', 'wp-job-manager-field-editor' ),
			'oembed' => __( 'oEmbed (YouTube/Vimeo/SoundCloud etc)', 'wp-job-manager-field-editor' ),
			'video'  => __( 'HTML5 Video', 'wp-job-manager-field-editor' ),
			'checkbox_output_options' => '---' . __( 'Checkbox Output Options', 'wp-job-manager-field-editor' ),
		    'checklabel' => __( 'Checkbox (Only show label if checked)', 'wp-job-manager-field-editor' ),
		    'checkcustom' => __( 'Checkbox (Custom True/False Labels)', 'wp-job-manager-field-editor' ),
		);

		if ( ! $as_array ) return $this->fields()->options()->convert( $this->output_as );

		return $this->output_as;

	}

	/**
	 * Check if Output Option exists
	 *
	 * @since 1.1.9
	 *
	 * @param $output_option
	 *
	 * @return bool
	 */
	function is_valid_option( $output_option ) {

		if ( array_key_exists( $output_option, $this->get_options( TRUE ) ) ) return TRUE;

		return FALSE;
	}

	/**
	 * Get Available Output Options
	 *
	 * Based on available templates, and WPJM version, will
	 * return the possible field options that are available.
	 *
	 * @since 1.1.9
	 *
	 * @param bool $as_array Return field options as array
	 *
	 * @param null $list_field_group
	 *
	 * @return string
	 */
	function get_options( $as_array = FALSE, $list_field_group = NULL ) {

		$output_options = array();

		$output_options = $this->add_other_options( $output_options, $list_field_group );

		$output_options = apply_filters( 'field_editor_output_options', $output_options );

		if ( ! $as_array ) $output_options = $this->fields()->options()->convert( $output_options );

		return $output_options;
	}

	/**
	 * Add version specific field options
	 *
	 * @since 1.1.9
	 *
	 * @param      $output_options
	 *
	 * @param null $list_field_group
	 *
	 * @return array
	 */
	function add_other_options( $output_options, $list_field_group = NULL ) {

		if ( $list_field_group ) {

			switch ( $list_field_group ) {

				case 'job':
					$this->wpjm();
					$this->jobify( 'job' );
					$this->jobera( 'job' );
					break;

				case 'company':
					$this->wpjm();
					$this->jobify( 'company' );
					$this->jobera( 'company' );
					break;

				case 'resume_fields':
					$this->wprm();
					$this->jobify( 'resume' );
					break;

			}

		}

		return array_merge( $output_options, $this->available_options );

	}

	/**
	 * Jobera Theme custom action output areas
	 *
	 * Requires Jobera 2.0.1.2 or newer
	 *
	 * @since 1.2.7
	 *
	 * @param $type
	 *
	 * @return array|bool
	 */
	function jobera( $type ) {

		if ( $type === 'company' ) $type = "job";
		$theme         = wp_get_theme();
		$theme_name    = strtolower( $theme->get( 'Name' ) );
		$theme_version = $theme->get( 'Version' );
		//$theme_version = '2.3';

		if ( $theme_name !== 'jobera' || version_compare( $theme_version, '2.3', '<' ) ) return FALSE;

		$jobera_options_job = array(
			'2.3' => array(
				'single_job_listing_jobera' => '---' . __( "Jobera Theme", 'wp-job-manager-field-editor' ),
				'single_job_listing_above_logo' => __( 'Single Job Listing Above Logo', 'wp-job-manager-field-editor' ),
				'single_job_listing_below_social_links'  => __( 'Single Job Listing Below Social Links', 'wp-job-manager-field-editor' ),
				'single_job_listing_below_location_map'  => __( 'Single Job Listing Below Location Map', 'wp-job-manager-field-editor' ),
			)
		);

		foreach ( ${"jobera_options_$type"} as $version => $options ) {

			if ( version_compare( $theme_version, $version, 'ge' ) ) {
				$this->available_options = array_merge( $this->available_options, $options );
			}

		}

		return $this->available_options;

	}

	/**
	 * Jobify Theme custom action output areas
	 *
	 * Requires Jobify 2.0.1.2 or newer
	 *
	 * @since 1.1.12
	 *
	 * @param $type
	 *
	 * @return array|bool
	 */
	function jobify( $type ){

		if ( $type === 'company' ) $type = "job";
		$theme = wp_get_theme();
		$theme_name = strtolower( $theme->get( 'Name' ) );
		$theme_version = $theme->get( 'Version' );
		//$theme_version = '2.0.1.2';

		if ( $theme_name !== 'jobify' || version_compare( $theme_version, '2.0.1.2', '<' )) return false;

		$jobify_options_job = array(
			'2.0.1.2' => array(
				'single_job_listing_info_jobify' => '---' . __( "Jobify Theme", 'wp-job-manager-field-editor' ),
				'single_job_listing_info_before' => __( 'Single Job Listing Before', 'wp-job-manager-field-editor' ),
				'single_job_listing_info_after'  => __( 'Single Job Listing After', 'wp-job-manager-field-editor' ),
				'single_job_listing_info_start'  => __( 'Single Job Listing Start', 'wp-job-manager-field-editor' ),
				'single_job_listing_info_end'    => __( 'Single Job Listing End', 'wp-job-manager-field-editor' ),
			)
		);

		$jobify_options_resume = array(
			'2.0.1.2' => array(
				'single_resume_info_jobify' => '---' . __( "Jobify Theme", 'wp-job-manager-field-editor' ),
				'single_resume_info_before' => __( 'Single Resume Listing Before', 'wp-job-manager-field-editor' ),
				'single_resume_info_after'  => __( 'Single Resume Listing After', 'wp-job-manager-field-editor' ),
				'single_resume_info_start'  => __( 'Single Resume Listing Start', 'wp-job-manager-field-editor' ),
				'single_resume_info_end'    => __( 'Single Resume Listing End', 'wp-job-manager-field-editor' ),
			)
		);

		foreach ( ${"jobify_options_$type"} as $version => $options ) {

			if ( version_compare( $theme_version, $version, 'ge' ) ) {
				$this->available_options = array_merge( $this->available_options, $options );
			}

		}

		return $this->available_options;

	}

	/**
	 * WP Job Manager Field Types
	 *
	 * Will return the available field options based on the
	 * currently installed version of WP Job Manager.
	 *
	 * @since 1.1.9
	 *
	 * @return array
	 */
	function wpjm() {

		$wpjm_options = array(
			'1.10.0' => array(
				'single_job_listing_page' => '---' . __( "Single Job Page", 'wp-job-manager-field-editor' ),
				'single_job_listing_start'          => __( "Top of Job Listing", 'wp-job-manager-field-editor' ),
				'single_job_listing_meta_before'    => __( "Before Job Meta", 'wp-job-manager-field-editor' ),
				'single_job_listing_meta_start'     => __( 'Job Meta Start (before Job Type)', 'wp-job-manager-field-editor' ),
				'single_job_listing_meta_end'       => __( 'Job Meta End (after Date Posted)', 'wp-job-manager-field-editor' ),
				'single_job_listing_meta_after'     => __( 'After Job Meta', 'wp-job-manager-field-editor' ),
				'single_job_listing_company_before' => __( 'Before Company Meta', 'wp-job-manager-field-editor' ),
				'single_job_listing_company_after'  => __( 'After Company Meta', 'wp-job-manager-field-editor' ),
				'the_job_description'               => __( 'Bottom of Job Description', 'wp-job-manager-field-editor' ),
				'single_job_listing_end'            => __( 'Bottom of Job Listing', 'wp-job-manager-field-editor' ),
			),
			'1.17.1' => array(
				'job_listing_page' => '---' . __( "Jobs List Page", 'wp-job-manager-field-editor' ),
				'job_listing_meta_start' => __( "Jobs List Meta Start", 'wp-job-manager-field-editor' ),
				'job_listing_meta_end' => __( "Jobs List Meta End", 'wp-job-manager-field-editor' ),
			)
		);

		foreach ( $wpjm_options as $version => $options ) {

			if ( version_compare( JOB_MANAGER_VERSION, $version, 'ge' ) ) {
				$this->available_options = array_merge( $this->available_options, $options );
			}

		}

		return $this->available_options;

	}

	/**
	 * WP Job Manager Resumes Field Types
	 *
	 * Will return the available field options based on the
	 * currently installed version of WP Job Manager.
	 *
	 * @since 1.1.9
	 *
	 * @return array
	 */
	function wprm() {

		$wprm_options = array(
			'1.0.0' => array(
				'single_resume_page' => __( "---Single Resume Page", 'wp-job-manager-field-editor' ),
				'single_resume_meta_start' => __( 'Meta Start (before Category)', 'wp-job-manager-field-editor' ),
				'single_resume_meta_end'   => __( 'Meta End (after Date Posted)', 'wp-job-manager-field-editor' )
			),
		    '1.4.5' => array(
			    'single_resume_start' => __( 'Top of Resume Listing', 'wp-job-manager-field-editor' ),
			    'single_resume_end' => __( 'Bottom of Resume Listing', 'wp-job-manager-field-editor' )
		    )
		);

		foreach ( $wprm_options as $version => $options ) {

			if ( version_compare( RESUME_MANAGER_VERSION, $version, 'ge' ) ) {
				$this->available_options = array_merge( $this->available_options, $options );
			}

		}

		return $this->available_options;

	}

	/**
	 * Filter Job Description to add Auto Outputs
	 *
	 *
	 * @since 1.2.1
	 *
	 * @param $the_content
	 *
	 * @return string
	 */
	function the_job_description( $the_content ){

		ob_start();
		$this->single_action( 'the_job_description', array( 'job', 'company' ) );
		$AOhtml = ob_get_contents();
		ob_end_clean();

		if( $AOhtml ) $the_content .= $AOhtml;

		return $the_content;

	}

	/**
	 * Singleton Instance
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Job_Manager_Field_Editor_Auto_Output
	 */
	static function get_instance() {

		if ( NULL == self::$instance ) self::$instance = new self;

		return self::$instance;
	}

}

WP_Job_Manager_Field_Editor_Auto_Output::get_instance();