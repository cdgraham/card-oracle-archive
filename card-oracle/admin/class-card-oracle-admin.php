<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      1.0.0
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

	public function add_card_sets_box() {
		
		$screens = array( 'co_sets' );

		add_meta_box( 'reading_position', __( 'Reading Positions', 'card-oracle' ), 'reading_position_meta', $screens, 'normal', 'default' );
	} // add_card_sets_box

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
	 * @since    1.0.0
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

	/**
	 * 
	 * Create our custom post type for card sets
	 */
	public function new_cpt_sets() {

		// Set the labels for the custom post type
		$labels = array(
			'name'				 => __( 'Card Sets' ),
			'singular_name'		 => __( 'Card Set' ),
			'add_new'			 => __( 'Add New Card Set'),
			'add_new_item'		 => __( 'Add New Card Set'),
			'edit_item'			 => __( 'Edit Card Set'),
			'new_item'			 => __( 'New Card Set'),
			'all_items'			 => __( 'All Card Sets'),
			'view_item'			 => __( 'View Card Set'),
			'search_items'		 => __( 'Search Card Sets'),
			'featured_image'	 => 'Card Back',
			'set_featured_image' => 'Add Card Back'
		);

		// Settings for our post type
		$args = array(
			
			'description'		=> 'Holds our card set information',
			'has_archive'		=> false,
			'labels'			=> $labels,
			'menu_icon'			=> 'dashicons-admin-page',
			'menu_position'		=> 25,
			'public'			=> true,
			'show_in_admin_bar'	=> true,
			'show_in_nav_menus'	=> false,
			'supports'			=> array( 'title', 'thumbnail' ),
			'query_var'			=> true,
		);

		register_post_type( 'co_sets', $args );
	} // new_cpt_sets
	
	/**
	 * Create the card set custom post type for Card Oracle
	 * 
	 * @since	1.0.0
	 * @return	
	 */
	public function reading_position_meta() {
		global $post;

		echo '<input type="hidden" name="reading_noncename" id="reading_noncename" value="' .
		wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

		echo '<p><label for=reading_position">Reading Position</label></br />';
		echo '<input type="text" name="" value="' . mysql_escape_string( get_post_meta( $post->ID, 'reading_position', true ) ) . '" /></p>';
	}
}