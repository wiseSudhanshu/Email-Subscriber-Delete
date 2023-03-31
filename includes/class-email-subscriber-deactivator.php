<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://https://sudhanshu.wisdmlabs.net/
 * @since      1.0.0
 *
 * @package    Email_Subscriber
 * @subpackage Email_Subscriber/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Email_Subscriber
 * @subpackage Email_Subscriber/includes
 * @author     Sudhanshu Rai <sudhanshu.rai@wisdmlabs.com>
 */
class Email_Subscriber_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public function deactivate() {
		global $wpdb;

		// dropping tables on plugin uninstall
		$table_name = $wpdb->prefix . 'email_subscriptions';
		
		$wpdb->query("DROP TABLE IF EXISTS $table_name");
	}

}
