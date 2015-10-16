<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class WP_Job_Manager_Field_Editor_Integration
 *
 * @since 1.1.9
 *
 */
class WP_Job_Manager_Field_Editor_Integration extends WP_Job_Manager_Field_Editor_Fields {

	private static $instance;
	private $job_fields;
	private $resume_fields;
	protected static $force_validate_resumes = false;

	function __construct() {

		$this->job_fields = new WP_Job_Manager_Field_Editor_Job_Fields();
		if( $this->wprm_active() ) $this->resume_fields = new WP_Job_Manager_Field_Editor_Resume_Fields();

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_filter( 'job_manager_locate_template', array( $this, 'locate_template' ), 10, 3 );
		add_action( 'single_job_listing_start', array( $this, 'company_disabled_check' ), 25 );
	}

	/**
	 * Remove core company display on listing if all fields disabled
	 *
	 *
	 * @since 1.1.2
	 *
	 */
	function company_disabled_check(){

		$fields = $this->get_fields( 'company', 'enabled' );

		if ( empty( $fields ) ) {
			remove_action( 'single_job_listing_start', 'job_listing_company_display', 30 );
		}

	}

	/**
	 * Filter WPJM template locate to use custom templates
	 *
	 *
	 * @since 1.1.10
	 *
	 * @param $template
	 * @param $template_name
	 * @param $template_path
	 *
	 * @return string
	 */
	function locate_template( $template, $template_name, $template_path ){

		switch( $template_name ){

			case 'form-fields/term-checklist-field.php':
				wp_enqueue_script( 'jmfe-term-checklist-field' );
				break;

			default:
				if( file_exists( WPJM_FIELD_EDITOR_PLUGIN_DIR . '/templates/' . $template_name ) ){
					$template = WPJM_FIELD_EDITOR_PLUGIN_DIR . '/templates/' . $template_name;
				}
				break;
		}

		return $template;
	}

	/**
	 * Set disabled field required to false
	 *
	 * To prevent errors when saving/updating from frontend, we
	 * need to set required to false for disabled fields.
	 *
	 * @since 1.1.9
	 *
	 * @param $field
	 */
	function set_required_false( $field ){

		if( isset( $field[ 'status' ] ) && isset( $field[ 'required' ] ) ){

			if( $field[ 'status' ] == 'disabled' && $field[ 'required' ] ) $field[ 'required' ] = false;

		}

		return $field;

	}

	/**
	 * Run Once Plugins are Loaded
	 *
	 * @since 1.1.9
	 *
	 */
	function plugins_loaded(){

//		if ( ! class_exists( 'WP_Job_Manager_Field_Editor_Job_Writepanels' ) )
//			parent::do_require( '/classes/job/writepanels.php' );
//
//		new WP_Job_Manager_Field_Editor_Job_Writepanels();

	}

	/**
	 * Change Admin Field Type
	 *
	 * Will change the field type in fields array based on a few options.
	 * First will check if custom replace is specified in function, then
	 * will skip if field is taxonomy, then will check if function or action
	 * exists for field type.  If none of these will set field type to text.
	 *
	 *
	 * @since 1.1.9
	 *
	 * @param $current_type
	 *
	 * @return string
	 */
	function change_admin_field_type( $current_type ){

		$change_types = array(
			'wp-editor'      => 'textarea',
			'business-hours' => 'business_hours'
		);

		// Check if taxonomy type (always starts with "term-")
		if( strpos( $current_type, 'term-' ) !== false) return $current_type;

		// Check if custom function or action exists for type (WPJM)
		if ( method_exists( 'WP_Job_Manager_Field_Editor_Job_Writepanels', 'input_' . $current_type ) ) return $current_type;
		if ( has_action( 'job_manager_input_' . $current_type ) ) return $current_type;

		if( $this->wprm_active() ){
			// Check if custom function or action exists for type (WPRM)
			if ( method_exists( 'WP_Job_Manager_Field_Editor_Resume_Writepanels', 'input_' . $current_type ) ) return $current_type;
			if ( has_action( 'resume_manager_input_' . $current_type ) ) return $current_type;
		}

		// Check if defined above in custom type change( ! class_exists( 'WP_Job_Manager_Field_Editor_Job_Writepanels' ) )
		if ( array_key_exists( $current_type, $change_types ) ) return $change_types[ $current_type ];

		return 'text';
	}

	/**
	 * Clean config from option values
	 *
	 * WP Job Manager <= 1.19.0 does not support templates or override for fields in admin
	 * and because of this any config options including tilde (~) and asterisk (*) have to
	 * be removed from the value to prevent invalid values if listing is saved from admin
	 *
	 *
	 * @since 1.1.14
	 *
	 * @param $config array
	 *
	 * @return array
	 */
	function clean_option_values( $config ){
		$core_inputs = array( 'select', 'multiselect' );
		if( ! isset( $config['options'] ) || ! is_array( $config['options'] ) || ! in_array( $config['type'], $core_inputs ) ) return $config;

		$tmp_options = array();

		foreach( $config['options'] as $value => $label ){
			$value = str_replace( '*', '', $value, $replace_default );
			$value = str_replace( '~', '', $value, $replace_disabled );

			$tmp_options[ $value ] = $label;
		}

		$config['options'] = $tmp_options;

		return $config;
	}

	/**
	 * Adds underscore, and remove disabled
	 *
	 * Flattens first level array, adds underscore to meta key,
	 * and removes any disabled fields
	 *
	 * @since 1.1.9
	 *
	 * @param mixed $type Type of custom fields to use (job, company, resume_fields, etc)
	 * @param array $default_fields Array of fields to merge with
	 *
	 * @return mixed
	 */
	function prep_admin_fields( $type, $default_fields ) {

		$custom_fields = array();

		// Default fields don't have priority so we set them to 0
		foreach( $default_fields as $default_field => $default_field_conf ){
			if( ! empty( $default_field_conf['priority'] ) ) continue;
			$default_fields[ $default_field ][ 'priority' ] = 0;
		}

		if( is_array( $type ) && ! empty( $type ) ){

			foreach( $type as $the_type ) $custom_fields = array_merge( $custom_fields, $this->get_custom_fields( true, $the_type ) );

		} else {

			$custom_fields = $this->get_custom_fields( true, $type );

		}

		foreach ( $custom_fields as $custom => $config ) {

			// Do not include post title, or post content customized fields
			$skip_fields = array( 'job_title', 'candidate_name', 'resume_content', 'job_description' );
			$diff_keys = array( 'job_deadline' => 'application_deadline' );

			if ( in_array( $config[ 'meta_key' ], $skip_fields ) ) continue;

			// Check if admin meta key is different from job/resume listing
			if ( isset( $diff_keys[ $config['meta_key'] ] ) ) {
				$custom = $diff_keys[ $config[ 'meta_key' ] ];
				$config[ 'meta_key' ] = $custom;
			}

			// Do not include child field group parents
			if ( isset( $config[ 'group_parent' ] ) && $config[ 'group_parent' ] ) continue;
			if ( ! empty( $config[ 'fields' ] ) ) continue;

			// Do not include taxonomy fields to prevent errors when saving
			if ( ! empty( $config[ 'taxonomy' ] ) ) continue;

			// Check for WPJM <= 1.19.0 & WPJMFE >= 1.15.0 to remove
			// tilde and asterisk from options on admin fields (admin fields do not support templates or overrides ... yet)
			$config = $this->clean_option_values( $config );

			$custom = '_' . $custom;

			// Check if type needs to be changed for admin section
			if( ! empty( $config[ 'type' ] ) ) $config[ 'type' ] = $this->change_admin_field_type( $config[ 'type' ] );

			if ( array_key_exists( $custom, $default_fields ) ) {

				$default_fields[ $custom ] = array_merge( $default_fields[ $custom ], $config );

			} else {

				$default_fields[ $custom ] = $config;

			}

		}

		uasort( $default_fields, 'WP_Job_Manager_Field_Editor_Fields::priority_cmp' );

		return wp_list_filter( $default_fields, array( 'status' => 'disabled' ), 'NOT' );

	}

	/**
	 * Save field type meta
	 *
	 * Updates meta values for job/resume when updated, or created.
	 *
	 * @since 1.1.9
	 *
	 * @param string $type Type of custom fields to save meta for
	 * @param integer $job_id Specific ID of job to update/save values for
	 * @param array $values Array of values to use, normally passed from $_POST values
	 */
	function save_custom_fields( $type, $job_id, $values ) {

		$custom_fields = $this->get_custom_fields( true, $type );
		// Save Package/Product ID if POSTed from submit page
		$wcpl_pid = isset( $_POST['wcpl_jmfe_product_id'] ) ? intval( $_POST['wcpl_jmfe_product_id'] ) : false;
		if( $wcpl_pid ) update_post_meta( $job_id, '_wcpl_jmfe_product_id', $wcpl_pid );

		if ( ! empty( $custom_fields ) ) {
			$custom_enabled_fields = wp_list_filter( $custom_fields, array( 'status' => 'disabled' ), 'NOT' );

			foreach ( $custom_enabled_fields as $custom_field => $custom_field_config ) {

				if ( isset( $values[ $type ][ $custom_field ] ) ) {

					$_meta_key = '_' . $custom_field;

					// Don't update post meta for default fields
					if( isset( $custom_field_config['origin'] ) && $custom_field_config['origin'] != "default" ){
						update_post_meta( $job_id, $_meta_key, $values[ $type ][ $custom_field ] );
					}

					// Auto save auto populate field to user meta
					if( isset( $custom_field_config[ 'populate_save' ] ) && ! empty( $custom_field_config[ 'populate_save' ] ) ){
						update_user_meta( get_current_user_id(), $_meta_key, $values[ $type ][ $custom_field ] );
					}

				}

			}

		}

	}

	/**
	 * Returns Form_Submit_Job Class Object
	 *
	 * Internal function to include and call class object as needed
	 *
	 * @since 1.1.9
	 *
	 * @return WP_Job_Manager_Field_Editor_Job_Submit_Form
	 */
	function wpjm(){

		if ( version_compare( JOB_MANAGER_VERSION, '1.22.0', 'lt' ) ) {
			return new WP_Job_Manager_Field_Editor_Job_Legacy_Submit_Form;
		}

		return new WP_Job_Manager_Field_Editor_Job_Submit_Form;

	}

	/**
	 * Returns Form_Submit_Resume Class Object
	 *
	 * Internal function to include and call class object as needed
	 *
	 * @since 1.1.9
	 *
	 * @return WP_Job_Manager_Field_Editor_Resume_Submit_Form
	 */
	function wprm() {

		if ( version_compare( JOB_MANAGER_VERSION, '1.22.0', 'lt' ) ) {
			return new WP_Job_Manager_Field_Editor_Resume_Legacy_Submit_Form;
		}

		return new WP_Job_Manager_Field_Editor_Resume_Submit_Form;

	}

	/**
	 * Checks if $forced_filter is set to true
	 *
	 * Prevents returning customized fields when getting default fields
	 *
	 * @since 1.1.9
	 *
	 * @return bool
	 */
	function was_filter_forced() {

		return parent::$forced_filter;

	}

	/**
	 * Auto Populate field values from User Meta
	 *
	 * Called by filter to auto populate fields with data from user meta as configured
	 * in field editor "populate" settings for each field.
	 *
	 * @since 1.1.12
	 *
	 * @param $fields
	 * @param $user_id
	 *
	 * @return mixed
	 */
	function get_user_data( $fields, $user_id ) {

		foreach ( $fields as $field_group => $group_fields ) {

			foreach ( $group_fields as $field => $conf ) {
				// Null out populate_value for loop
				$populate_value = null;

				// Remove core auto populate if disabled from field configuration
				if( $field_group === 'company' && isset( $conf[ 'origin' ] ) && $conf[ 'origin' ] === 'default' ){
					if( isset( $conf['populate_enable'] ) && empty( $conf[ 'populate_enable' ] ) ) unset( $fields[ 'company' ][ $field ][ 'value' ] );
				}

				// Remove core auto populate if disabled from field configuration
				if ( $field_group === 'job' && $field === "application" && isset( $conf[ 'origin' ] ) && $conf[ 'origin' ] === 'default' ) {
					if ( isset( $conf[ 'populate_enable' ] ) && empty( $conf[ 'populate_enable' ] ) ) unset( $fields[ 'job' ][ 'application' ][ 'value' ] );
				}

				// Populate if enabled in field config
				if ( isset( $conf[ 'populate_enable' ] ) && ! empty( $conf[ 'populate_enable' ] ) ) {

					// Set populate value initially to "default" key from config array if it's set
					if ( isset( $conf[ 'default' ] ) && ! empty( $conf[ 'default' ] ) ) $populate_value = $conf[ 'default' ];
					if ( isset( $conf[ 'populate_default' ] ) && ! empty( $conf[ 'populate_default' ] ) ) $populate_value = $conf[ 'populate_default' ];

					// If meta key is set try and get from user meta
					if ( isset( $conf[ 'populate_meta_key' ] ) && ! empty( $conf[ 'populate_meta_key' ] ) ) {

						$pop_meta_key = $conf[ 'populate_meta_key' ];
						// Check for value in user meta to override default value
						$user_meta_value = get_user_meta( $user_id, $pop_meta_key, TRUE );
						if( $user_meta_value ) $populate_value = $user_meta_value;

					}
					// Filter for populate from other than user meta, if meta key is "my_meta_key",
					// filter would be "field_editor_auto_populate_my_meta_key"
					$populate_value = maybe_unserialize( apply_filters( "field_editor_auto_populate_{$pop_meta_key}", $populate_value ) );

					// Set value in config to autopopulate value if set
					if ( ! empty( $populate_value ) ) $fields[ $field_group ][ $field ][ 'value' ] = $populate_value;

				}

			}

		}

		return $fields;

	}

	/**
	 * Singleton Instance
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Job_Manager_Field_Editor_Integration
	 */
	static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

WP_Job_Manager_Field_Editor_Integration::get_instance();