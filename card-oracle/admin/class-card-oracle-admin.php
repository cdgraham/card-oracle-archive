<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      0.4.1
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
	 * @since    0.4.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.4.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.4.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Add an options page under the Card Oracle menu
	 *
	 * @since  0.4.1
	 */
	public function add_card_oracle_options_page() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Card Oracle Settings', 'card-oracle' ),
			__( 'Card Oracle', 'card-oracle' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_card_oracle_options_page' )
		);
	}

	/**
	 * Get the total counts of a cpt
	 * 
	 * @since 0.4.1
	 * @Return int count 
	 */
	public function get_card_oracle_cpt_count( $card_oracle_cpt ) {

		return ( wp_count_posts( $card_oracle_cpt )->publish );

	}

	public function co_get_positions_per_reading( $reading_id ) {
		$args = array(
			'fields' => 'ids',
			'post_type' => 'co_positions',
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => 'co_reading_id',
					'value' => $reading_id,
				),
			),
		);

		$cards = new WP_Query( $args );

		// The number of cards returned
		$count = count( $cards->posts );
		
		return $count;
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  0.4.1
	 */
	public function display_card_oracle_options_page() {
		global $wpdb;

		$readings_count = $this->get_card_oracle_cpt_count( 'co_readings' );
		$cards_count =  $this->get_card_oracle_cpt_count( 'co_cards' );
		$positions_count = $this->get_card_oracle_cpt_count( 'co_positions' );
		$descriptions_count = $this->get_card_oracle_cpt_count( 'co_descriptions' );

		$readings = $this->get_co_reading_id_title();

		include_once 'partials/card-oracle-admin-display.php';
	}

	/**
	 * Get all the reading post ids and titles
	 * 
	 * @since 0.4.1
	 * @return array of IDs and Titles for all post_types co_readings
	 */
	function get_co_reading_id_title() {
		global $wpdb;

		$sql = "SELECT ID, post_title FROM " . $wpdb->posts . " " .
					"WHERE post_type = 'co_readings' " .
					"AND post_status = 'publish' " . 
					"ORDER BY post_title";
					
		// The $positions is an array of all the positions in a reading, it consists of
		// the position title and position ID
		$reading_ids = $wpdb->get_results( $sql, OBJECT );

		return $reading_ids;
	}

	/**
	 * Get all the card post ids and titles for a reading id and post_type co_cards
	 * 
	 * @since 0.4.1
	 * @return array of card IDs and Titles
	 */
	function get_co_card_id_title( $reading_id ) {
		global $wpdb;

		$sql = "SELECT ID, post_title FROM " . $wpdb->posts . " " .
					"INNER JOIN $wpdb->postmeta ON ID = post_id AND meta_key = 'co_reading_id' " .
					"WHERE post_type = 'co_cards' " .
					"AND post_status = 'publish' " .
					"AND meta_value = '" . $reading_id . "'" . 
					"ORDER BY post_title";
					
		// The $positions is an array of all the positions in a reading, it consists of
		// the position title and position ID
		$results = $wpdb->get_results( $sql, OBJECT );

		return $results;
	}

	/**
	 * Get all the descriptions post ids and content for a reading id and post_type co_descriptions
	 * 
	 * @since 0.4.1
	 * @return array of card IDs and Content
	 */
	function get_co_description_id_content( $reading_id ) {
		global $wpdb;

		$sql = "SELECT ID, post_content FROM " . $wpdb->posts . " " .
					"INNER JOIN $wpdb->postmeta ON ID = post_id " .
					"WHERE post_type = 'co_descriptions' " .
					"AND post_status = 'publish' " .
					"AND meta_key = 'co_card_id' " .
					"AND meta_value IN (" . 
					"SELECT DISTINCT(id) FROM " . $wpdb->posts . " " .
					"INNER JOIN $wpdb->postmeta ON ID = post_id " .
					"WHERE post_type = 'co_cards' " . 
					"AND post_status = 'publish' " .
					"AND meta_key = 'co_reading_id' " . 
					"AND meta_value = '" . $reading_id . "')";
					
		// The $positions is an array of all the positions in a reading, it consists of
		// the position title and position ID
		$results = $wpdb->get_results( $sql, OBJECT );

		return $results;
	}

	/**
	 * Get all the position post ids and titles for a reading id and post_type co_positions
	 * 
	 * @since 0.4.1
	 * @return array of card IDs and Titles
	 */
	function get_co_position_id_title( $reading_id ) {
		global $wpdb;

		$sql = "SELECT ID, post_title FROM " . $wpdb->posts . " " .
					"INNER JOIN $wpdb->postmeta ON ID = post_id AND meta_key = 'co_reading_id' " .
					"WHERE post_type = 'co_positions' " .
					"AND post_status = 'publish' " .
					"AND meta_value = '" . $reading_id . "'" . 
					"ORDER BY post_title";
					
		// The $positions is an array of all the positions in a reading, it consists of
		// the position title and position ID
		$results = $wpdb->get_results( $sql, OBJECT );

		return $results;
	}

	/**
	 * Create our custom metabox for cards
	 * 
	 * @since    0.4.1
	 */
	function add_reading_and_order_box() {
	
		$screens = array( 'co_positions' );
		add_meta_box( 'card-reading', __( 'Settings', 'card-oracle' ), array( $this, 'render_reading_and_order_metabox' ), $screens, 'normal', 'high' );
	} // add_card_box

	/**
	 * Create our custom metabox for cards
	 * 
	 * @since    0.4.1
	 */
	function add_reading_box() {
	
		$screens = array( 'co_cards' );
		add_meta_box( 'card-reading', __( 'Settings', 'card-oracle' ), array( $this, 'render_reading_metabox' ), $screens, 'normal', 'high' );
	} // add_card_box

	/**
	 * Create our custom metabox for card positions
	 * 
	 * @since    0.4.1
	 */
	function add_card_and_positions_box() {
	
		$screens = array( 'co_descriptions' );

		add_meta_box( 'card', __( 'Settings', 'card-oracle' ), array( $this, 'render_card_and_position_metabox' ), $screens, 'normal', 'high' );
	} // add_card_box

	function display_card_oracle_quick_edit( $column ) {

		$html = '';

		if ( $column == 'card_order' ) {
			$html .= '<fieldset class="inline-edit-col-right ">';
            $html .= '<div class="inline-edit-group wp-clearfix">';
            $html .= '<label class="alignleft" for="post_rating">Post Subtitle</label>';
            $html .= '<input type="text" name="post_subtitle" id="post_subtitle" value="" />';
            $html .= '</div>';
        	$html .= '</fieldset>';
		}

		echo $html;

	}

	/**
	 * Create our menu and submenus
	 * 
	 * @since    0.4.1
	 */
	function card_oracle_menu_items() {

		add_menu_page( 'Card Oracle', 'Card Oracle', 'manage_options', 'card-oracle-admin-menu', array( $this, 'display_card_oracle_options_page'), 'dashicons-admin-page', 40 );
		add_submenu_page( 'card-oracle-admin-menu', 'Card Oracle Optionss', 'Options', 'manage_options', 'card-oracle-admin-menu', array ($this, 'display_card_oracle_options_page') );
		add_submenu_page( 'card-oracle-admin-menu', 'Card Oracle Readings Admin', 'Readings', 'manage_options', 'edit.php?post_type=co_readings' );
		add_submenu_page( 'card-oracle-admin-menu', 'Card Oracle Positions Admin', 'Positions', 'manage_options', 'edit.php?post_type=co_positions' );
		add_submenu_page( 'card-oracle-admin-menu', 'Card Oracle Cards Admin', 'Cards', 'manage_options', 'edit.php?post_type=co_cards' );
		add_submenu_page( 'card-oracle-admin-menu', 'Card Oracle Descriptions Admin', 'Descriptions', 'manage_options', 'edit.php?post_type=co_descriptions' );

	}

	/**
	 * Move the featured image box for card readings
	 * 
	 * @since    0.4.1
	 */
	function cpt_image_box() {

		// Move the image metabox from the sidebar to the normal position 
		$screens = array( 'co_cards' );
		remove_meta_box( 'postimagediv', $screens, 'side' );
		add_meta_box( 'postimagediv', __('Front of Card Image', 'card-oracle'), 'post_thumbnail_meta_box', $screens, 'normal', 'default' );
	
		// Move the image metabox from the sidebar to the normal position 
		$screens = array( 'co_readings' );
		remove_meta_box( 'postimagediv', $screens, 'side' );
		add_meta_box( 'postimagediv', __('Back of Card Image', 'card-oracle'), 'post_thumbnail_meta_box', $screens, 'normal', 'default' );

		//remove Astra metaboxes from our cpt
		$screens = array( 'co_cards', 'co_readings', 'co_positions', 'co_descriptions' );
		remove_meta_box( 'astra_settings_meta_box', $screens, 'side' );	// Remove Astra Settings in Posts

	}

	/**
	 * Display the custom admin columns for Cards
	 * 
	 * @since    0.4.1
	 */
	function custom_card_column( $column ) {

		global $post;
		global $wpdb;
	
		switch ( $column ) {
			case 'card_reading' :
				$reading = get_post_meta( $post->ID, 'co_reading_id', true );
				echo get_the_title( $reading );
			break;

			case 'cards_position' :
				echo get_post_meta( $post->ID, 'co_card_position', true );
			break;

			case 'card_order' :
				echo get_post_meta( $post->ID, 'co_card_order', true );
			break;

			case 'co_shortcode' :
				echo '<input class="co-shortcode" id="copy'. $post->ID . '" value="[card-oracle id=&quot;' . $post->ID . 
					'&quot;]"><button class="copyAction copy-action-btn" value="[card-oracle id=&quot;' . $post->ID . 
					'&quot;]"> <img src="' . plugin_dir_url( __DIR__ ) . 'assets/images/clippy.svg" width="13" alt="Copy to clipboard"></button>';
			break;

			case 'number_card_positions' :

				$sql = "SELECT * FROM " . $wpdb->postmeta . " " .
					"INNER JOIN " . $wpdb->prefix . "posts" . " " . 
					"ON id = post_id " .
					"WHERE meta_key = 'co_card_id' " . 
					"AND meta_value = " . $post->ID . " " .
					"AND post_status = 'publish'";
				$wpdb->get_results( $sql, OBJECT );
				$rows = $wpdb->num_rows;

				$reading_id = get_post_meta( $post->ID, 'co_reading_id', true );
				$positions = $this->co_get_positions_per_reading( $reading_id );

				if ( $rows == $positions ) {
					echo $rows;
				} else {
					echo '<font color="red">' . $rows . '</font>';
				}
			break;

			case 'number_reading_positions' :
				echo $this->co_get_positions_per_reading( $post->ID );
			break;

			case 'card_title' :
				$card_id = get_post_meta( $post->ID, 'co_card_id', true );
				$card_title = get_the_title( $card_id );
				echo '<strong><a class="row-title" href="' . admin_url() . 'post.php?post=' . $post->ID . '&action=edit">' .
				$card_title .'</a></strong>';
			break;

			case 'position_title' :
				$position_id = get_post_meta( $post->ID, 'co_position_id', true );
				echo get_the_title( $position_id );
			break;

			case 'position_number' :
				$position_id = get_post_meta( $post->ID, 'co_position_id', true );
				echo get_post_meta( $position_id, 'co_card_order', true );
			break;

		}

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.4.1
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
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.4.1
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
	 * Create our custom post type for card readings
	 * 
	 * @since    0.4.1
	 */
	public function register_card_oracle_cpt() {

		// Register the Cards cpt
		// Set the labels for the custom post type
		$labels = array(
			'name'				 => __( 'Cards' ),
			'singular_name'		 => __( 'Card' ),
			'add_new'			 => __( 'Add New Card'),
			'add_new_item'		 => __( 'Add New Card'),
			'edit_item'			 => __( 'Edit Card'),
			'new_item'			 => __( 'New Card'),
			'all_items'			 => __( 'All Cards'),
			'view_item'			 => __( 'View Card'),
			'search_items'		 => __( 'Search Cards'),
			'featured_image'	 => 'Card Image',
			'set_featured_image' => 'Add Card Image'
		);

		// Settings for our post type
		$args = array(
			
			'description'		=> 'Holds our card information',
			'has_archive'		=> false,
			'hierarchical'      => true,
			'labels'			=> $labels,
			'menu_icon'			=> 'dashicons-media-default',
			'menu_position'		=> 42,
			'public'			=> true,
			'show_in_menu'      => false,
			'show_in_admin_bar'	=> true,
			'show_in_nav_menus'	=> false,
			'supports'			=> array( 'title', 'thumbnail' ),
			'query_var'			=> true,
		);

		register_post_type( 'co_cards', $args );

		// Register the Descriptions cpt
		// Set the labels for the custom post type
		$labels = array(
			'name'				 => __( 'Card Descriptions' ),
			'singular_name'		 => __( 'Card Description' ),
			'add_new'			 => __( 'Add New Card Description'),
			'add_new_item'		 => __( 'Add New Card Description'),
			'edit_item'			 => __( 'Edit Card Description'),
			'new_item'			 => __( 'New Card Description'),
			'all_items'			 => __( 'All Card Descriptions'),
			'view_item'			 => __( 'View Card Description'),
			'search_items'		 => __( 'Search Card Descriptions'),
			'featured_image'	 => 'Card Description Image',
			'set_featured_image' => 'Add Card Description Image'
		);

		// Settings for our post type
		$args = array(
			
			'description'		=> 'Holds our card description information',
			'has_archive'		=> false,
			'hierarchical'      => true,
			'labels'			=> $labels,
			'menu_icon'			=> 'dashicons-format-gallery',
			'menu_position'		=> 43,
			'public'			=> true,
			'show_in_menu'      => false,
			'show_in_admin_bar'	=> true,
			'show_in_nav_menus'	=> false,
			'supports'			=> array( 'editor' ),
			'query_var'			=> true,
		);

		register_post_type( 'co_descriptions', $args );
		
		// register the Card Readings cpt
		// Set the labels for the custom post type
		$labels = array(
			'name'				 => __( 'Card Readings' ),
			'singular_name'		 => __( 'Card Reading' ),
			'add_new'			 => __( 'Add New Card Reading'),
			'add_new_item'		 => __( 'Add New Card Reading'),
			'edit_item'			 => __( 'Edit Card Reading'),
			'new_item'			 => __( 'New Card Reading'),
			'all_items'			 => __( 'All Card Readings'),
			'view_item'			 => __( 'View Card Reading'),
			'search_items'		 => __( 'Search Card Readings'),
			'featured_image'	 => 'Card Back',
			'set_featured_image' => 'Add Card Back'
		);

		// Settings for our post type
		$args = array(
			
			'description'		=> 'Holds our card reading information',
			'has_archive'		=> false,
			'hierarchical'      => true,
			'labels'			=> $labels,
			'menu_icon'			=> 'dashicons-admin-page', //dashicons-admin-page
			'menu_position'		=> 40,
			'public'			=> true,
			'show_in_menu'      => false,
			'show_in_admin_bar'	=> false,
			'show_in_nav_menus'	=> false,
			'supports'			=> array( 'title', 'thumbnail' ),
			'query_var'			=> true,
		);

		register_post_type( 'co_readings', $args );

		// Register the Positions cpt
		// Set the labels for the custom post type
		$labels = array(
			'name'				 => __( 'Card Positions' ),
			'singular_name'		 => __( 'Card Position' ),
			'add_new'			 => __( 'Add New Card Position'),
			'add_new_item'		 => __( 'Add New Card Position'),
			'edit_item'			 => __( 'Edit Card Position'),
			'new_item'			 => __( 'New Card Position'),
			'all_items'			 => __( 'All Card Positions'),
			'view_item'			 => __( 'View Card Position'),
			'search_items'		 => __( 'Search Card Positions'),
			'featured_image'	 => 'Card Position Image',
			'set_featured_image' => 'Add Card Position Image'
		);

		// Settings for our post type
		$args = array(
			
			'description'		=> 'Holds our card position information',
			'has_archive'		=> false,
			'hierarchical'      => true,
			'labels'			=> $labels,
			'menu_icon'			=> 'dashicons-format-gallery',
			'menu_position'		=> 41,
			'public'			=> true,
			'show_in_menu'      => false,
			'show_in_admin_bar'	=> true,
			'show_in_nav_menus'	=> false,
			'supports'			=> array( 'title' ),
			'query_var'			=> true,
		);

		register_post_type( 'co_positions', $args );

		

	} // register_card_oracle_cpt
		
	/**
	 * Render the Card Metabox for Card Oracle
	 * 
	 * @since	0.4.1
	 * @return	
	 */
	function render_card_and_position_metabox() {

		global $post;

		error_log( print_r( $post, true ) );

		// Generate nonce
		wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );

		$selected_card = get_post_meta( $post->ID, 'co_card_id', true );
		echo '<p><label class="co-metabox">Card</label><br />';
		$pages = wp_dropdown_pages( array( 'post_type' => 'co_cards', 'selected' => $selected_card, 'name' => 'co_card_id', 
			'show_option_none' => __( '(no card)' ), 'sort_column'=> 'post_title', 'echo' => 0 ) );

		echo $pages;
		echo '</p>';

		$selected_position = get_post_meta( $post->ID, 'co_position_id', true );
		echo '<label class="co-metabox">Description Position</label><br />';
		$pages = wp_dropdown_pages( array( 'post_type' => 'co_positions', 'selected' => $selected_position, 'name' => 'co_position_id', 
		'show_option_none' => __( '(no card)' ), 'sort_column'=> 'co_card_order', 'echo' => 0 ) );

		echo $pages;
		echo '</p>';

	} // render_card_and_position_metabox

	/**
	 * Render the Reading Metabox for Card Oracle
	 * 
	 * @since	0.4.1
	 * @return	
	 */
	function render_reading_metabox() {

		global $post;
		
		// Generate nonce
		wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );

		$selected_reading = get_post_meta( $post->ID, 'co_reading_id', true );

		echo '<p><label class="co-metabox">Card Reading</label><br />';
		$pages = wp_dropdown_pages( array( 'post_type' => 'co_readings', 'selected' => $selected_reading, 'name' => 'co_reading_id', 
			'show_option_none' => __( '(no reading)' ), 'sort_column'=> 'post_title', 'echo' => 0 ) );
		echo $pages;
		echo '</p>';
		
	} // render_reading_metabox

	/**
	 * Render the Reading and Order Metabox for Card Oracle
	 * 
	 * @since	0.4.1
	 * @return	
	 */
	function render_reading_and_order_metabox() {

		global $post;
		
		// Generate nonce
		wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );

		$selected_reading = get_post_meta( $post->ID, 'co_reading_id', true );

		echo '<p><label class="co-metabox">Card Reading</label><br />';
		$pages = wp_dropdown_pages( array( 'post_type' => 'co_readings', 'selected' => $selected_reading, 'name' => 'co_reading_id', 
			'show_option_none' => __( '(no reading)' ), 'sort_column'=> 'post_title', 'echo' => 0 ) );
		echo $pages;
		echo '</p>';

		echo '<p><label class="co-metabox" for="co_card_order">Card Order</label><br />';
		echo '<input class="co-metabox-textbox" type="text" name="co_card_order" value="' . 
			esc_html( get_post_meta( $post->ID, "co_card_order", true ) ) . '" /></p>';
		
	} // render_reading_and_order_metabox


	/**
	 * Render the card readings Metaboxes for Card Oracle
	 * 
	 * @since	0.4.1
	 * @return	
	 */
/* 	function render_position_list_metabox( ) {

		global $post;

		// Generate nonce
		wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );

		echo '<p><label for=co_positions">Enter the title for each of the positions in the description spread separated by commas.</label></br />';
		echo '<input style="width: 100%" type="text" name="co_positions" placeholder="The Past,The Present,The Future,Reason,Potential" value="' .
			esc_attr( get_post_meta( $post->ID, 'co_positions', true ) ) . '" /></p>';

	} // render_position_list_metabox */

	/**
	 * Save the card post meta for Card Oracle
	 * 
	 * @since	0.4.1
	 * @return	
	 */
	function save_card_oracle_meta_data() {
		global $post;

		if ( ! $this->co_check_rights() ) return;

		// If the Card Reading has been selected update it.
		if (isset ( $_POST['co_reading_id'] ) ) {
			update_post_meta( $post->ID, 'co_reading_id', $_POST['co_reading_id'] );
		}
		
		// If the Card Position has been selected update it.
		if ( isset( $_POST[ 'co_card_position' ] ) ) {
			update_post_meta( $post->ID, 'co_card_position', wp_kses_post( $_POST[ 'co_card_position' ] ) );
		} else {
			delete_post_meta( $post->ID, 'co_card_position' );
		}

		// If the Card has been selected update it.
		if ( isset( $_POST[ 'co_card_id' ] ) ) {
			update_post_meta( $post->ID, 'co_card_id', wp_kses_post( $_POST[ 'co_card_id' ] ) );
		} else {
			delete_post_meta( $post->ID, 'co_card_id' );
		}

		// If the Card has been selected update it.
		if ( isset( $_POST[ 'co_position_id' ] ) ) {
			update_post_meta( $post->ID, 'co_position_id', wp_kses_post( $_POST[ 'co_position_id' ] ) );
		} else {
			delete_post_meta( $post->ID, 'co_position_id' );
		}

		// If the Card Position has been selected update it.
		if ( isset( $_POST[ 'co_card_order' ] ) ) {
			update_post_meta( $post->ID, 'co_card_order', wp_kses_post( $_POST[ 'co_card_order' ] ) );
		} else {
			delete_post_meta( $post->ID, 'co_card_order' );
		}

	}

	/**
	 * Set the admin columns for Cards
	 * 
	 * @since	 0.4.1
	 * @return
	 */
	function set_custom_cards_columns( $columns ) {
		// unset the date so we can move it to the end
		unset($columns['date']);

		$columns['card_reading'] = __('Card Reading', $this->plugin_name );
		$columns['number_card_positions'] = __('Number of Positions', $this->plugin_name );
		$columns['date'] = __('Date', $this->plugin_name );

		return $columns;
	}

	/**
	 * Set the admin columns for Descriptions
	 * 
	 * @since	 0.4.1
	 * @return
	 */
	function set_custom_descriptions_columns( $columns ) {
		// unset the date so we can move it to the end
		unset( $columns['title'] );
		unset( $columns['date'] );

		$columns['card_title'] = __( 'Card', $this->plugin_name );
		$columns['position_title'] = __( 'Position', $this->plugin_name );
		$columns['position_number'] = __( 'Position Number', $this->plugin_name );
		$columns['date'] = __( 'Date', $this->plugin_name );

		return $columns;
	}

	/**
	 * Set the admin columns for Card Readings
	 * 
	 * @since	 0.4.1
	 * @return
	 */
	function set_custom_readings_columns( $columns ) {
		// unset the date so we can move it to the end
		unset( $columns['date'] );

		$columns['co_shortcode'] = __( 'Shortcode', $this->plugin_name );
		$columns['number_reading_positions'] = __( 'Positions', $this->plugin_name );
		$columns['date'] = __( 'Date', $this->plugin_name );

		return $columns;
	}

	/**
	 * Set the admin columns for Card Positions
	 * 
	 * @since	 0.4.1
	 * @return
	 */
	function set_custom_positions_columns( $columns ) {
		// unset the date so we can move it to the end
		unset( $columns['date'] );

		$columns['card_reading'] = __( 'Card Reading', $this->plugin_name );
		$columns['card_order'] = __( 'Position', $this->plugin_name );
		$columns['date'] = __( 'Date', $this->plugin_name );

		return $columns;
	}

	/**
	 * Set the sortable columns for Cards
	 * 
	 * @since	 0.4.1
	 * @return
	 */
	function set_custom_sortable_card_columns( $columns ) {
		// unset the date so we can move it to the end
	
		$columns['card_reading'] = 'card_reading';
		$columns['number_card_positions'] = 'number_card_positions';

		return $columns;
	}

	/**
	 * Set the sortable columns for Descriptions
	 * 
	 * @since	 0.4.1
	 * @return
	 */
	function set_custom_sortable_description_columns( $columns ) {
		// unset the date so we can move it to the end
	
		$columns['card_title'] = 'card_title';
		$columns['position_title'] = 'position_title';
		$columns['position_number'] = 'position_number';

		return $columns;
	}

	/**
	 * Set the sortable columns for Positions
	 * 
	 * @since	 0.4.1
	 * @return
	 */
	function set_custom_sortable_position_columns( $columns ) {
		// unset the date so we can move it to the end
	
		$columns['card_reading'] = 'card_reading';
		$columns['card_order'] = 'cards_position';

		return $columns;
	}

	public function co_check_rights() {

		global $post;

        // Check nonce
        if( ! isset( $_POST['meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['meta_box_nonce'], 'meta_box_nonce' ) ) return false;

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return false;

        // Prevent quick edit from clearing custom fields
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) return false;

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post->ID ) ) return false;

        return true;

	}

}