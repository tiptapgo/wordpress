<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class WP_Job_Manager_Field_Editor_Package_WC
 *
 * @since 1.2.2
 *
 */
class WP_Job_Manager_Field_Editor_Package_WC {

	function __construct() {

	}

	/**
	 * Get WC Product ID
	 *
	 * WPJM core POSTs product id for new packages, or "user-{index}" for
	 * user packages.  Need to convert user packages to product id's.
	 *
	 * User packages are the index from the DB table
	 *
	 *
	 * @since 1.2.2
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	static function get_product_id( $id ) {

		if ( strpos( $id, 'user-' ) !== FALSE ) {
			$id           = str_replace( 'user-', '', $id );
			$user_package = get_user_job_package( $id );
			$id           = $user_package->get_product_id();
		}

		return $id;
	}

	/**
	 * Get packages in Field Editor format
	 *
	 *
	 * @since 1.2.2
	 *
	 * @param bool $as_array
	 *
	 * @return array|string
	 */
	static function get_packages( $as_array = FALSE ) {

		$fpackages = array();

		if( ! class_exists( 'WP_Job_Manager_WCPL_Submit_Job_Form' )  && defined( 'JOB_MANAGER_WCPL_PLUGIN_DIR' ) ) {
			include( JOB_MANAGER_WCPL_PLUGIN_DIR . '/includes/class-wp-job-manager-wcpl-submit-job-form.php' );
		}

		$packages = WP_Job_Manager_WCPL_Submit_Job_Form::get_packages();

		if( ! $packages ) return false;

		foreach ( $packages as $key => $package ) {
			$product = get_product( $package );
			// Skip if not job package
			if ( ! $product->is_type( array( 'job_package', 'job_package_subscription', 'subscription' ) ) ) continue;

			$fpackages[ $product->id ] = $product->get_title();
		}

		if ( ! $as_array ) {
			$options = new WP_Job_Manager_Field_Editor_Fields_Options();
			return $options->convert( $fpackages );
		}

		return $fpackages;
	}

	/**
	 * Filter Fields based on Packages
	 *
	 * Filters out fields that have packages_require enabled but do
	 * not have current package enabled for field.
	 *
	 * @since 1.2.2
	 *
	 * @param $fields
	 * @param $id
	 *
	 * @return mixed
	 */
	static function filter_fields( $fields, $id ) {

		$product_id = self::get_product_id( $id );

		// Loop through Job/Company
		foreach ( $fields as $group => $group_fields ) {

			// Loop through job or company fields
			foreach ( $group_fields as $field => $config ) {

				// Packages are required for this field
				if ( isset( $config[ 'packages_require' ] ) && $config[ 'packages_require' ] === "1" ) {

					// Skip if no packages were selected
					if( ! isset( $config['packages_show'] ) ) continue;

					// Could be an array of packages
					if ( is_array( $config[ 'packages_show' ] ) ) {
						$rm_field = in_array( $product_id, $config[ 'packages_show' ] ) ? FALSE : TRUE;
					} else {
						// Just a single package selected
						if ( $config[ 'packages_show' ] !== $product_id ) $rm_field = TRUE;
					}

					// Packages required on this field, and it doesn't
					if ( $rm_field ) unset( $fields[ $group ][ $field ] );

				}
			}

		}

		return $fields;
	}

}