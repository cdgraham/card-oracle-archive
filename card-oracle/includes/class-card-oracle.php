<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://cdgraham.com
 * @since      0.4.1
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.4.1
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 * @author     Christopher Graham <chris@chillichalli.com>
 */
class Card_Oracle {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.4.1
	 * @access   protected
	 * @var      Card_Oracle_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.4.1
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.4.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.4.1
	 */
	public function __construct() {
		if ( defined( 'CARD_ORACLE_VERSION' ) ) {
			$this->version = CARD_ORACLE_VERSION;
		} else {
			$this->version = '0.4.1';
		}
		$this->plugin_name = 'card-oracle';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Card_Oracle_Loader. Orchestrates the hooks of the plugin.
	 * - Card_Oracle_i18n. Defines internationalization functionality.
	 * - Card_Oracle_Admin. Defines all hooks for the admin area.
	 * - Card_Oracle_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.4.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-card-oracle-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-card-oracle-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-card-oracle-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-card-oracle-public.php';

		/**
		 * The class responsible for defining all actions that occur in the meta boxes
		 * side of the site.
		 */
		// CDG update later
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-card-oracle-meta-boxes.php';

		$this->loader = new Card_Oracle_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Card_Oracle_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.4.1
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Card_Oracle_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.4.1
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Card_Oracle_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// call custom post types
		$this->loader->add_action( 'init', $plugin_admin, 'register_card_oracle_cpt' );

		// Custom columns for admin screens
		$this->loader->add_filter( 'manage_edit-co_cards_columns', $plugin_admin, 'set_custom_cards_columns' );
		$this->loader->add_filter( 'manage_edit-co_cards_sortable_columns', $plugin_admin, 'set_custom_sortable_card_columns' );
		$this->loader->add_filter( 'manage_edit-co_descriptions_columns', $plugin_admin, 'set_custom_descriptions_columns' );
		$this->loader->add_filter( 'manage_edit-co_descriptions_sortable_columns', $plugin_admin, 'set_custom_sortable_description_columns' );
		$this->loader->add_filter( 'manage_edit-co_readings_columns', $plugin_admin, 'set_custom_readings_columns' );
		$this->loader->add_filter( 'manage_edit-co_positions_columns', $plugin_admin, 'set_custom_positions_columns' );
		$this->loader->add_filter( 'manage_edit-co_positions_sortable_columns', $plugin_admin, 'set_custom_sortable_position_columns' );
		$this->loader->add_action( 'manage_co_cards_posts_custom_column', $plugin_admin, 'custom_card_column' );
		$this->loader->add_action( 'manage_co_descriptions_posts_custom_column', $plugin_admin, 'custom_card_column' );
		$this->loader->add_action( 'manage_co_readings_posts_custom_column', $plugin_admin, 'custom_card_column' );
		$this->loader->add_action( 'manage_co_positions_posts_custom_column', $plugin_admin, 'custom_card_column' );

		// Add Menu items
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'card_oracle_menu_items' );

		// Add metaboxes
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_reading_and_order_box' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_reading_box' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_card_and_positions_box' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_card_oracle_meta_data' );
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin, 'cpt_image_box' );

		// Add Quickedit TODO CDG
		$this->loader->add_action( 'quick_edit_custom_box', $plugin_admin, 'display_card_oracle_quick_edit' );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.4.1
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Card_Oracle_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Add the shortchode
		$this->loader->add_shortcode( 'card-oracle', $plugin_public, 'display_card_oracle_set' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.4.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.4.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.4.1
	 * @return    Card_Oracle_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.4.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
}
