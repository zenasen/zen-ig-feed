<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       mdhrseanli@gmail.com
 * @since      1.0.0
 *
 * @package    Zen_Ig_Feed
 * @subpackage Zen_Ig_Feed/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Zen_Ig_Feed
 * @subpackage Zen_Ig_Feed/includes
 * @author     Hari Seanli <mdhrseanli@gmail.com>
 */
class Zen_Ig_Feed_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'zen-ig-feed',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
