<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://sudhanshu.wisdmlabs.net/
 * @since      1.0.0
 *
 * @package    Email_Subscriber
 * @subpackage Email_Subscriber/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Email_Subscriber
 * @subpackage Email_Subscriber/admin
 * @author     Sudhanshu Rai <sudhanshu.rai@wisdmlabs.com>
 */
class Email_Subscriber_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Email_Subscriber_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Email_Subscriber_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/email-subscriber-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Email_Subscriber_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Email_Subscriber_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/email-subscriber-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_email_subscription_callback() {
		?>

		<script>
			jQuery(document).ready(function($) {
				$('#subscribe-me').on('click', function(e) {
					e.preventDefault();

					var email = $('#email').val();
					var data = {
						action: 'add_email_subscription',
						email: email
					};

					$.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', data, function(response) {
						var result = response.data;
						$('#subscription-message').html(result.message);
					});
				});
			});
		</script>

		<?php

		$email = sanitize_email( $_POST['email'] );
	
		// Check if the email is valid
		if ( ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => 'Invalid email address' ) );
		}
	
		// Add the email to the database
		$result = add_email_subscription( $email );
	
		// Send a notification email
		send_subscription_notification( $email );
	
		if ( $result ) {
			wp_send_json_success( array( 'message' => 'You have been subscribed successfully.' ) );
		} else {
			wp_send_json_error( array( 'message' => 'An error occurred while subscribing. Please try again later.' ) );
		}
	}
	
	// Add a function for adding email subscriptions to the database
	public function add_email_subscription( $email ) {
		global $wpdb;
	
		$table_name = $wpdb->prefix . 'email_subscriptions';
	
		$result = $wpdb->insert(
			$table_name,
			array(
				'email' => $email,
				'date_added' => current_time( 'mysql' )
			)
		);
	
		if ( $result === false ) {
			error_log( 'Failed to insert email subscription: ' . $wpdb->last_error );
			return false;
		}
	
		return true;
	}
	
	
	// Add a function for sending subscription notification emails
	public function send_subscription_notification( $email ) {
		$num_links = get_option( 'email_subscription_num_links', 3 );
	
		$recent_posts = wp_get_recent_posts( array(
			'numberposts' => $num_links,
			'post_status' => 'publish'
		) );
	
		$post_links = array();
		foreach ( $recent_posts as $post ) {
			$post_links[] = '<a href="' . get_permalink( $post['ID'] ) . '">' . $post['post_title'] . '</a>';
		}
	
		$headers = array();
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
	
		$subject = 'New subscription';
		$message = 'Thank you for subscribing to our newsletter. Here are the links to our latest posts:<br><br>';
		$message .= implode( '<br>', $post_links );
	
		wp_mail( $email, $subject, $message, $headers );
	}

	public function email_subscription_plugin_settings_page() {
		?>
		<div class="wrap">
			<h1>Email Subscription Plugin Settings</h1>
	
			<form action="options.php" method="post">
				<?php settings_fields( 'email_subscription_plugin_settings_group' ); ?>
				<?php do_settings_sections( 'email_subscription_plugin_settings_page' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	public function email_subscription_plugin_settings_menu() {
		add_options_page(
			'Email Subscription Plugin Settings',
			'Email Subscription',
			'manage_options',
			'email-subscription-plugin-settings',
			'email_subscription_plugin_settings_page'
		);
	}
	
	public function email_subscription_plugin_settings_init() {
		register_setting( 'email_subscription_plugin_settings_group', 'email_subscription_num_links' );

		add_settings_section( 'email_subscription_plugin_settings_section', 'Notification Email Settings', '', 'email_subscription_plugin_settings_page' );

		add_settings_field( 'email_subscription_num_links', 'Number of links to include in the notification email:', 'email_subscription_num_links_field', 'email_subscription_plugin_settings_page', 'email_subscription_plugin_settings_section' );
	}
	
	public function email_subscription_num_links_field() {
		$num_links = get_option( 'email_subscription_num_links', 3 );
		echo '<input type="number" name="email_subscription_num_links" value="' . esc_attr( $num_links ) . '" min="1" max="10">';
	}

}
