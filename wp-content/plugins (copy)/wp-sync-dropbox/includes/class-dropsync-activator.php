<?php

/**
 * Fired during plugin activation
 *
 * @link       http://scopeship.com
 * @since      1.0.0
 *
 * @package    Dropsync
 * @subpackage Dropsync/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dropsync
 * @subpackage Dropsync/includes
 * @author     Krupal Lakhia <krupaly2k@gmail.com>
 */
class Dropsync_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dropsync-admin.php';

		flush_rewrite_rules();

		$opts 		= array();
		$options 	= Dropsync_Admin::get_options_list();

		foreach ( $options as $option ) {

			$opts[ $option[0] ] = $option[2];

		}

		update_option( 'dropsync-options', $opts );

		Dropsync_Admin::add_admin_notices();
		
	
	}

}
