<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://sudhanshu.wisdmlabs.net/
 * @since      1.0.0
 *
 * @package    Email_Subscriber
 * @subpackage Email_Subscriber/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Email_Subscriber
 * @subpackage Email_Subscriber/includes
 * @author     Sudhanshu Rai <sudhanshu.rai@wisdmlabs.com>
 */
class Email_Subscriber_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'email-subscriber',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
