<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      0.3.0
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/admin
 * @author     Christopher Graham <chris@chillichalli.com>
 */
class Card_Oracle_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.3.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.3.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.3.0
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
	 * @since    0.3.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Card_Oracle_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Card_Oracle_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/card-oracle-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.3.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Card_Oracle_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Card_Oracle_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/card-oracle-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_plugin_admin_menu() {

		// Create the Card Oracle Admin Page
		add_menu_page( 'Card Oracle', 'Card Oracle', 'manage_options', 
			$this->plugin_name, array( $this, 'display_card_oracle_setup_page' ), 'dashicons-admin-page', 200 );

		add_submenu_page( $this->plugin_name, 'Card Oracle', 'Settings', 'manage_options', 
			$this->plugin_name, array( $this, 'display_card_oracle_setup_page' ) );

		add_submenu_page( $this->plugin_name, 'Card Sets', 'Card Sets', 'manage_options', 
			'card_oracle_card_sets', array ( $this, 'display_card_oracle_card_sets_page' ) );

	}
	/**
	 * Add settings action link to the plugin page.
	 * 
	 * @since	0.3.0
	 */

	 public function add_action_links( $links ) {
		 $settings_link = array (
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge(  $settings_link, $links );

	 }

	/**
	 * Render the settings page for this plugin
	 * 
	 * @since	0.3.0
	 */

	public function display_card_oracle_setup_page() {


		require_once ( plugin_dir_path( __FILE__ ) . '/partials/card-oracle-admin-display.php' );
	}

	public function display_card_oracle_card_sets_page() {

		$set_obj = new Sets_List;
		$set_obj->prepare_items();

		require_once ( plugin_dir_path( __FILE__ ) . '/partials/card-oracle-cardsets-display.php' );		
	}
}