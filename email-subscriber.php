<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://sudhanshu.wisdmlabs.net/
 * @since             1.0.0
 * @package           Email_Subscriber
 *
 * @wordpress-plugin
 * Plugin Name:       Email Subscriber
 * Plugin URI:        https://https://sudhanshu.wisdmlabs.net/
 * Description:       Send email of recent posts to the user
 * Version:           1.0.0
 * Author:            Sudhanshu Rai
 * Author URI:        https://https://sudhanshu.wisdmlabs.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       email-subscriber
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EMAIL_SUBSCRIBER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-email-subscriber-activator.php
 */
function activate_email_subscriber() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-email-subscriber-activator.php';
	$activator = new Email_Subscriber_Activator();
	$activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-email-subscriber-deactivator.php
 */
function deactivate_email_subscriber() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-email-subscriber-deactivator.php';
	$deactivator = new Email_Subscriber_Deactivator();
	$deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_email_subscriber' );
register_deactivation_hook( __FILE__, 'deactivate_email_subscriber' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-email-subscriber.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_email_subscriber() {

	$plugin = new Email_Subscriber();
	$plugin->run();

}
run_email_subscriber();
