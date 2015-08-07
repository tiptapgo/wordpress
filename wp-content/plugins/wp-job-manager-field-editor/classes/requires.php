<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class WP_Job_Manager_Field_Editor_Requires
 *
 * @since 1.1.9
 *
 */
class WP_Job_Manager_Field_Editor_Requires {

	private $config = array();
	private $required_plugins = array();

	function __construct() {

		$this->set_config();
		$this->wpjm();
		// if ( file_exists( WPJM_FIELD_EDITOR_PLUGIN_DIR . '/classes/resume/menu.php' ) ) $this->wprm();

		require_once( WPJM_FIELD_EDITOR_PLUGIN_DIR . '/includes/tgmpa.php' );
		add_action( 'tgmpa_register', array( $this, 'check_requires' ) );

	}

	/**
	 * Check required plugins are activated/installed
	 *
	 * @since 1.1.9
	 *
	 */
	function check_requires() {

		tgmpa( $this->required_plugins, $this->config );

	}

	/**
	 * Set WPJM as required plugin
	 *
	 * @since 1.1.9
	 *
	 */
	function wpjm(){

		$this->required_plugins[] = array(
				'name'     => 'WP Job Manager',
				'slug'     => 'wp-job-manager',
				'required' => true,
			);

	}

	/**
	 * Set WPRM as required plugin
	 *
	 * @since 1.1.9
	 *
	 */
	function wprm() {

		$this->required_plugins[ ] = array(
			'name'     => 'WP Resume Manager',
			'slug'     => 'wp-job-manager-resumes',
			'required' => false,
		);

	}

	/**
	 * Set Configuration Values
	 *
	 * @since 1.1.9
	 *
	 */
	function set_config(){

		$this->config = array(
			'id'           => 'jmfe-deps', // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '', // Default absolute path to pre-packaged plugins.
			'menu'         => 'jmfe-required-plugins', // Menu slug.
			'has_notices'  => true, // Show admin notices or not.
			'dismissable'  => true, // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '', // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => true, // Automatically activate plugins after installation or not.
			'message'      => '', // Message to output right before the plugins table.
			'strings'      => array(
				'page_title'                      => __( 'Install Required Plugins', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				'menu_title'                      => __( 'Install Plugins', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				'installing'                      => __( 'Installing Plugin: %s', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				// %s = plugin name.
				'oops'                            => __( 'Something went wrong with the plugin API.', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				'notice_can_install_required'     => _n_noop( 'This plugin requires the following plugin: %1$s.', 'This plugin requires the following plugins: %1$s.', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				// %1$s = plugin name(s).
				'notice_can_install_recommended'  => _n_noop( 'This plugin recommends the following plugin: %1$s.', 'This plugin recommends the following plugins: %1$s.', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				// %1$s = plugin name(s).
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				// %1$s = plugin name(s).
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				// %1$s = plugin name(s).
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				// %1$s = plugin name(s).
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				// %1$s = plugin name(s).
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this plugin: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this plugin: %1$s.', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				// %1$s = plugin name(s).
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				// %1$s = plugin name(s).
				'install_link'                    => _n_noop( 'Install Plugin', 'Install Plugins', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				'activate_link'                   => _n_noop( 'Activate Plugin', 'Activate Plugins', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				'return'                          => __( 'Return to Required Plugins Installer', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				'plugin_activated'                => __( 'Plugin activated successfully.', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				'complete'                        => __( 'All plugins installed and activated successfully. %s', 'wp-job-manager-field-editor', 'wp-job-manager-field-editor' ),
				// %s = dashboard link.
				'nag_type'                        => 'error'
				// Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			)
		);

	}

}

new WP_Job_Manager_Field_Editor_Requires();