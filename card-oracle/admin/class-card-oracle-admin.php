<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      0.4.4
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
	 * @since    0.4.4
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.4.4
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since	0.4.4
	 * @param	string	$plugin_name	The name of this plugin.
	 * @param	string	$version		The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Add an options page under the Card Oracle menu
	 *
	 * @since	0.4.4
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
	 * @since	0.4.4
	 * @param	string	$card_oracle_cpt	The name of the custom post type.
	 * @return	int							The count of custom post types.
	 */
	public function get_card_oracle_cpt_count( $card_oracle_cpt ) {

		return ( wp_count_posts( $card_oracle_cpt )->publish );

	}
	/**
	 * Get the total counts of a cpt
	 * 
	 * @since	0.4.4
	 * @param	string	$reading_id		The post ID of the reading.
	 * @return	int		$count			The number of positions per reading.
	 */
	public function co_get_positions_per_reading( $reading_id ) {
		$args = array(
			'fields' => 'ids',
			'post_type' => 'co_positions',
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => 'co_reading_id',
					'value' => $reading_id,
					'compare' => 'LIKE',
				),
			),
		);

		$cards = new WP_Query( $args );
		echo $cards->request;

		// The number of cards returned
		$count = count( $cards->posts );
		
		return $count;
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since	0.4.5
	 */
	public function display_card_oracle_options_page() {
		global $wpdb;

		$readings_count = number_format_i18n( $this->get_card_oracle_cpt_count( 'co_readings' ) );
		/* translators: %d is a number */
		$readings_text = esc_html( sprintf( _n( '%d Total', '%d Total', $readings_count, 'card-oracle' ), $readings_count ) );

		$cards_count = number_format_i18n( $this->get_card_oracle_cpt_count( 'co_cards' ) );
		/* translators: %d is a number */
		$cards_text = esc_html( sprintf( _n( '%d Total', '%d Total', $cards_count, 'card-oracle' ), $cards_count ) );
		
		$positions_count = number_format_i18n( $this->get_card_oracle_cpt_count( 'co_positions' ) );
		/* translators: %d is a number */
		$positions_text = esc_html( sprintf( _n( '%d Total', '%d Total', $positions_count, 'card-oracle' ), $positions_count ) );

		$descriptions_count = number_format_i18n( $this->get_card_oracle_cpt_count( 'co_descriptions' ) );
		/* translators: %d is a number */
		$descriptions_text = esc_html( sprintf( _n( '%d Total', '%d Total', $descriptions_count, 'card-oracle' ), $descriptions_count ) );

		$readings = $this->get_co_reading_id_title();

		$reading_array = array();

		for ( $i = 0; $i < count( $readings ); $i++ ) {
			$reading_array[$i] = new stdClass();
			$reading_array[$i]->positions = count( $this->get_co_position_id_title( $readings[$i]->ID ) );
			$reading_array[$i]->cards = count( $this->get_co_card_id_title( $readings[$i]->ID ) );
			$reading_array[$i]->descriptions = count( $this->get_co_description_ids( $readings[$i]->ID ) );
		}

		include_once 'partials/card-oracle-admin-display.php';
	}

	/**
	 * Get all the reading post ids and titles
	 * 
	 * @since	0.4.4
	 * @return	$reading_ids	The array of IDs and Titles for all post_types co_readings
	 */
	public function get_co_reading_id_title() {
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
	 * @since	0.4.4
	 * @return	$card_ids	The array of card IDs and Titles
	 */
	public function get_co_card_id_title( $reading_id ) {
		global $wpdb;

		$sql = "SELECT ID, post_title FROM " . $wpdb->posts . " " .
					"INNER JOIN $wpdb->postmeta ON ID = post_id AND meta_key = 'co_reading_id' " .
					"WHERE post_type = 'co_cards' " .
					"AND post_status = 'publish' " .
					"AND meta_value LIKE '%" . serialize( $reading_id ) . "%' " . 
					"ORDER BY post_title";

		// The $positions is an array of all the positions in a reading, it consists of
		// the position title and position ID
		$card_ids = $wpdb->get_results( $sql, OBJECT );

		return $card_ids;
	}

	/**
	 * Get all the descriptions post ids and content for a reading id and post_type co_descriptions
	 * can include all cards when $card_id is not set or one or more cards when it is set. $card_id
	 * can be a single id or an array of ids.
	 * 
	 * @since	0.4.4
	 * @return	$description_ids	The array of description IDs and Content
	 */
	public function get_co_description_id_content( $reading_id, $card_id = NULL ) {
		global $wpdb;

		if ( isset( $card_id ) ) {
			$subquery = $card_id;
		} else {
			$subquery = "SELECT DISTINCT(id) FROM " . $wpdb->posts . " " .
			"INNER JOIN $wpdb->postmeta ON ID = post_id " .
			"WHERE post_type = 'co_cards' " . 
			"AND post_status = 'publish' " .
			"AND meta_key = 'co_reading_id' " . 
			"AND meta_value LIKE '%" . serialize( $reading_id ) . "%'";
		}

		$sql = "SELECT ID, post_content FROM " . $wpdb->posts . " " .
					"INNER JOIN $wpdb->postmeta ON ID = post_id " .
					"WHERE post_type = 'co_descriptions' " .
					"AND post_status = 'publish' " .
					"AND meta_key = 'co_card_id' " .
					"AND meta_value IN (" . $subquery . ")";

		// The $positions is an array of all the positions in a reading, it consists of
		// the position title and position ID
		return $wpdb->get_results( $sql, OBJECT );

	}

	/**
	 * Get all the descriptions post ids and reading id and post_type co_descriptions
	 * 
	 * @since	0.4.5
	 * @return	$description_ids	The array of description IDs and Content
	 */
	function get_co_description_ids( $reading_id ) {
		global $wpdb;

		$sql = "SELECT ID FROM $wpdb->posts 
			INNER JOIN $wpdb->postmeta m1 ON ID = m1.post_id
			INNER JOIN $wpdb->postmeta m2 ON ID = m2.post_id
			WHERE ( m1.meta_key = 'co_card_id' AND m1.meta_value IN (
				SELECT ID FROM $wpdb->posts
				INNER JOIN $wpdb->postmeta ON ID = post_id 
				WHERE ( meta_key = 'co_reading_id' AND meta_value LIKE '%" . serialize( $reading_id ) . "%' )
				AND post_type = 'co_cards' AND ((post_status = 'publish')))
			) AND ( m2.meta_key = 'co_position_id' AND m2.meta_value IN (
				SELECT ID FROM $wpdb->posts
				INNER JOIN $wpdb->postmeta ON ID = post_id 
				WHERE ( meta_key = 'co_reading_id' AND meta_value LIKE '%" . serialize( $reading_id ) . "%' )
				AND post_type = 'co_positions' AND ((post_status = 'publish')))
			)";

		$description_ids = $wpdb->get_results( $sql, OBJECT );

		return $description_ids;

	}

	/**
	 * Get all the position post ids and titles for a reading id and post_type co_positions
	 * 
	 * @since	0.4.4
	 * @return	array of card IDs and Titles
	 */
	public function get_co_position_id_title( $reading_id ) {
		global $wpdb;

		$sql = "SELECT ID, post_title FROM " . $wpdb->posts . " " .
					"INNER JOIN $wpdb->postmeta ON ID = post_id AND meta_key = 'co_reading_id' " .
					"WHERE post_type = 'co_positions' " .
					"AND post_status = 'publish' " .
					"AND meta_value LIKE '%" . serialize( $reading_id ) . "%'" . 
					"ORDER BY post_title";
					
		// The $positions is an array of all the positions in a reading, it consists of
		// the position title and position ID
		$results = $wpdb->get_results( $sql, OBJECT );

		return $results;
	}

	/**
	 * Create our custom metabox for cards
	 * 
	 * @since	0.4.4
	 */
	public function get_reading_dropdown_box( $selected_reading ) {
		
		if ( TRUE ) { // Unlimited Readings in Premium
			return wp_dropdown_pages( array( 'post_type' => 'co_readings', 'selected' => $selected_reading, 'name' => 'co_reading_dropdown', 
				'show_option_none' => __( '(no reading)' ), 'sort_column'=> 'post_title', 'echo' => 0 ) );
		} else { // Limited to 1 Reading in Free
			return wp_dropdown_pages( array( 'post_type' => 'co_readings', 'selected' => $selected_reading, 'name' => 'co_reading_dropdown', 
				'sort_column'=> 'post_title', 'number' => 1, 'echo' => 0 ) );
		}
	}

	/**
	 * Create our custom metabox for readings
	 * 
	 * @since	0.4.4
	 */
	public function add_meta_boxes_for_readings_cpt() {
	
		$screens = array( 'co_readings' );
		add_meta_box( 'card-reading', __( 'Settings', 'card-oracle' ), array( $this, 'render_reading_metabox' ), $screens, 'normal', 'high' );

	} // add_meta_boxes_for_readings_cpt() {

	/**
	 * Create our custom metabox for positions
	 * 
	 * @since	0.4.4
	 */
	public function add_meta_boxes_for_positions_cpt() {
	
		$screens = array( 'co_positions' );
		add_meta_box( 'card-reading', __( 'Settings', 'card-oracle' ), array( $this, 'render_position_metabox' ), $screens, 'normal', 'high' );
	} // add_meta_boxes_for_positions_cpt() {


	/**
	 * Create our custom metabox for cards
	 * 
	 * @since	0.4.4
	 */
	public function add_meta_boxes_for_cards_cpt() {
	
		$screens = array( 'co_cards' );
		add_meta_box( 'card-reading', __( 'Settings', 'card-oracle' ), array( $this, 'render_card_metabox' ), $screens, 'normal', 'high' );
	} // add_meta_boxes_for_cards_cpt

	/**
	 * Create our custom metabox for descriptions
	 * 
	 * @since	0.4.4
	 */
	public function add_meta_boxes_for_descriptions_cpt() {
	
		$screens = array( 'co_descriptions' );

		add_meta_box( 'card', __( 'Settings', 'card-oracle' ), array( $this, 'render_description_metabox' ), $screens, 'normal', 'high' );
	} // add_meta_boxes_for_descriptions_cpt

	public function display_card_oracle_quick_edit( $column ) {

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
	 * @since	0.4.4
	 */
	public function card_oracle_menu_items() {
		$co_admin_icon = 'data:image/svg+xml;base64,' . base64_encode( '<svg height="100px" width="100px"  fill="black" 
			xmlns:x="http://ns.adobe.com/Extensibility/1.0/" 
			xmlns:i="http://ns.adobe.com/AdobeIllustrator/10.0/" 
			xmlns:graph="http://ns.adobe.com/Graphs/1.0/" 
			xmlns="http://www.w3.org/2000/svg" 
			xmlns:xlink="http://www.w3.org/1999/xlink" 
			version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" 
			xml:space="preserve"><g><g i:extraneous="self">
			<circle fill="black" cx="49.926" cy="57.893" r="10.125"></circle>
			<path fill="black" d="M50,78.988c-19.872,0-35.541-16.789-36.198-17.503l-1.95-2.12l1.788-2.259c0.164-0.208,4.097-5.142,
				10.443-10.102 C32.664,40.296,41.626,36.751,50,36.751c8.374,0,17.336,3.546,25.918,10.253c6.346,4.96,10.278,9.894,
				10.443,10.102l1.788,2.259 l-1.95,2.12C85.541,62.2,69.872,78.988,50,78.988z M20.944,59.019C25.56,63.219,36.99,
				72.238,50,72.238 c13.059,0,24.457-9.013,29.061-13.214C74.565,54.226,63.054,43.501,50,43.501C36.951,43.501,25.444,
				54.218,20.944,59.019z"></path>
			<path fill="black" d="M44.305,30.939L50,21.075l5.695,9.864c3.002,0.427,6.045,1.185,9.102,2.265L50,7.575L35.203,33.204 
				C38.26,32.124,41.303,31.366,44.305,30.939z"></path>
			<path fill="black" d="M81.252,74.857L87.309,85H12.691l6.057-10.143c-2.029-1.279-3.894-2.629-5.578-3.887L1,92h98L86.83,
				70.97 C85.146,72.228,83.28,73.578,81.252,74.857z"></path>
			</g></g></svg>' );

		add_menu_page( __( 'Card Oracle', 'card-oracle' ), __( 'Card Oracle', 'card-oracle' ),
		'manage_options', 'card-oracle-admin-menu', array( $this, 'display_card_oracle_options_page'), $co_admin_icon, 40 );
		add_submenu_page( 'card-oracle-admin-menu', __( 'Card Oracle Options', 'card-oracle' ), __( 'Dashboard', 'card-oracle' ),
			'manage_options', 'card-oracle-admin-menu', array ($this, 'display_card_oracle_options_page') );
		add_submenu_page( 'card-oracle-admin-menu', __( 'Card Oracle Readings Admin', 'card-oracle' ), __( 'Readings', 'card-oracle' ),
			'manage_options', 'edit.php?post_type=co_readings' );
		add_submenu_page( 'card-oracle-admin-menu', __( 'Card Oracle positions Admin', 'card-oracle' ), __( 'Positions', 'card-oracle' ),
			'manage_options', 'edit.php?post_type=co_positions' );
		add_submenu_page( 'card-oracle-admin-menu', __( 'Card Oracle cards Admin', 'card-oracle' ), __( 'Cards', 'card-oracle' ),
			'manage_options', 'edit.php?post_type=co_cards' );
		add_submenu_page( 'card-oracle-admin-menu', __( 'Card Oracle Descriptions Admin', 'card-oracle' ), __( 'Descriptions', 'card-oracle' ),
			'manage_options', 'edit.php?post_type=co_descriptions' );

	}

	/**
	 * Move the featured image box for card readings
	 * 
	 * @since	0.4.4
	 */
	public function cpt_image_box() {

		// Move the image metabox from the sidebar to the normal position 
		$screens = array( 'co_cards' );
		remove_meta_box( 'postimagediv', $screens, 'side' );
		add_meta_box( 'postimagediv', __('Front of Card Image', 'card-oracle'), 'post_thumbnail_meta_box', $screens, 'side', 'default' );
	
		// Move the image metabox from the sidebar to the normal position 
		$screens = array( 'co_readings' );
		remove_meta_box( 'postimagediv', $screens, 'side' );
		add_meta_box( 'postimagediv', __('Back of Card Image', 'card-oracle'), 'post_thumbnail_meta_box', $screens, 'side', 'default' );

		//remove Astra metaboxes from our cpt
		$screens = array( 'co_cards', 'co_readings', 'co_positions', 'co_descriptions' );
		remove_meta_box( 'astra_settings_meta_box', $screens, 'side' );	// Remove Astra Settings in Posts

	}

	/**
	 * Display the custom admin columns for Cards
	 * 
	 * @since	0.4.4
	 */
	public function custom_card_column( $column ) {

		global $post;
		global $wpdb;
	
		switch ( $column ) {
			case 'card_reading':
				$readings = get_post_meta( $post->ID, 'co_reading_id', true );

				if ( is_array( $readings ) ) {
					foreach ( $readings as $reading ) {
						echo '<p>';
						echo get_the_title( $reading );
						echo '</p>';
					}
				}
				break;

			case 'cards_position':
				echo get_post_meta( $post->ID, 'co_card_position', true );
				break;

			case 'card_order':
				echo get_post_meta( $post->ID, 'co_card_order', true );
				break;

			case 'co_shortcode':
				echo '<input class="co-shortcode" id="copy'. $post->ID . '" value="[card-oracle id=&quot;' . $post->ID . 
					'&quot;]"><button class="copyAction copy-action-btn button" value="[card-oracle id=&quot;' . $post->ID . 
					'&quot;]"> <img src="' . PLUGIN_URL . 'assets/images/clippy.svg" alt="Copy to clipboard"></button>';
				break;

			case 'description_reading':
				$position_id = get_post_meta( $post->ID, 'co_position_id', true );		
				$reading_id = get_post_meta( $position_id, 'co_reading_id', true );
				echo get_the_title( $reading_id[0] );
				break;

			case 'number_card_descriptions':

				$reading_id = get_post_meta( $post->ID, 'co_reading_id', true );
				$count = count( $this->get_co_description_id_content( $reading_id, $post->ID ) );
				$positions = $this->co_get_positions_per_reading( $reading_id );

				if ( $count == $positions ) {
					echo $count;
				} else {
					echo '<font color="red">' . $count . '</font>';
				}
				break;

			case 'number_reading_positions':
				echo $this->co_get_positions_per_reading( $post->ID );
				break;

			case 'card_title':
				$card_id = get_post_meta( $post->ID, 'co_card_id', true );
				$card_title = get_the_title( $card_id );
				echo '<strong><a class="row-title" href="' . admin_url() . 'post.php?post=' . $post->ID . '&action=edit">' .
				$card_title .'</a></strong>';
				break;

			case 'position_title':
				$position_id = get_post_meta( $post->ID, 'co_position_id', true );
				echo get_the_title( $position_id );
				break;

			case 'position_number':
				$position_id = get_post_meta( $post->ID, 'co_position_id', true );
				echo get_post_meta( $position_id, 'co_card_order', true );
				break;
		}

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since	0.4.4
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
	 * @since	0.4.4
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
	 * Limit the number of Readings and Positions custom post type
	 * 
	 * @since	0.4.4
	 * @return	 
	 */
	public function limit_positions_cpt_count() {
		global $typenow;
		global $action;

		// Current License is ok return
		return;

		// If it's an edit don't block and return
		if ( $action === 'editpost' ) {
			return;
		}

		if ( $typenow === 'co_readings' ) {
			$message = __( 'Sorry, the maximum number of Readings has been reached.', 'card-oracle' );
			$limit = 1;
			// Grab all our Readings CPT
			$total = get_posts( array( 
				'post_type' => 'co_readings', 
				'numberposts' => -1, 
				'post_status' => 'publish, future, draft' 
			));
		} elseif ( $typenow === 'co_positions' ) {
			$message = __( 'Sorry, the maxiumum number of Positions has been reached.', 'card-oracle' );
			$limit = 5;
			// Grab all our Positions CPT
			$total = get_posts( array( 
				'post_type' => 'co_positions', 
				'numberposts' => -1, 
				'post_status' => 'publish, future, draft' 
			));
		} elseif ( $typenow === 'co_cards' ) {
			$message = __( 'Sorry, the maxiumum number of Cards has been reached.', 'card-oracle' );
			$limit = 25;
			// Grab all our Positions CPT
			$total = get_posts( array( 
				'post_type' => 'co_cards', 
				'numberposts' => -1, 
				'post_status' => 'publish, future, draft' 
			));
		} else {
			return;
			
		}
	
		# Condition match, block new post
		if ( !empty( $total ) && count( $total ) >= $limit ) {
			echo '<p>' . $message . '</p>';
			echo '<p>' . __( 'Please consider upgrading to our premium version.', 'card-oracle' ) . '</p>';

			wp_die(
				__( 'You can purchase it here:', 'card-oracle' ), 
				__( 'Maximum reached', 'card-oracle' ),  
				array( 
					'response' => 500, 
					'back_link' => true 
				)
			);
		}

	}

	/**
	 * Create our custom post type for card readings
	 * 
	 * @since	0.4.4
	 * @return
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
			'supports'			=> array( 'title', 'editor', 'thumbnail' ),
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
	 * Render the Reading Metabox for Cards CPT
	 * 
	 * @since	0.4.4
	 * @return	
	 */
	public function render_card_metabox() {

		global $post;
		
		// Generate nonce
		wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );

		$readings = $this->get_co_reading_id_title();
		$selected_readings = get_post_meta( $post->ID, 'co_reading_id', true );

		echo '<div class=""><p><label class="co-metabox" for="co_reading_id">';
		_e( 'Card Reading', 'card-oracle' );
		echo '</label></p>';

		foreach ( $readings as $id ) {
			if ( is_array( $selected_readings ) && in_array( $id->ID, $selected_readings ) ) {
				$checked = 'checked="checked"';
			} else {
				$checked = null;
			}

			echo '<p><input type="checkbox" name="co_reading_id[]" value="' . 
				$id->ID . '" ' . $checked . ' />' . $id->post_title . '</p>';
		}
		echo '</div>';
		
	} // render_card_metabox

	/**
	 * Render the Card Metabox for Descriptions CPT
	 * 
	 * @since	0.4.4
	 * @return	
	 */
	public function render_description_metabox() {

		global $post;

		error_log( print_r( $post, true ) );

		// Generate nonce
		wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );

		$selected_card = get_post_meta( $post->ID, 'co_card_id', true );

		echo '<p><label class="co-metabox">';
		_e( 'Card', 'card-oracle' );
		echo '</label><br />';

		$pages = wp_dropdown_pages( array( 'post_type' => 'co_cards', 'selected' => $selected_card, 'name' => 'co_card_id', 
			'show_option_none' => __( '(no card)' ), 'sort_column'=> 'post_title', 'echo' => 0 ) );

		echo $pages;
		echo '</p>';

		$selected_position = get_post_meta( $post->ID, 'co_position_id', true );

		echo '<label class="co-metabox">';
		_e( 'Description Position', 'card-oracle' );
		echo '</label><br />';

		$pages = wp_dropdown_pages( array( 'post_type' => 'co_positions', 'selected' => $selected_position, 'name' => 'co_position_id', 
		'show_option_none' => __( '(no card)' ), 'sort_column'=> 'co_card_order', 'echo' => 0 ) );

		echo $pages;
		echo '</p>';

	} // render_description_metabox

	/**
	 * Render the Reading and Order Metabox for Positions CPT
	 * 
	 * @since	0.4.4
	 * @return	
	 */
	public function render_position_metabox() {

		global $post;
		
		// Generate nonce
		wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );

		$selected_reading = get_post_meta( $post->ID, 'co_reading_id', true );
		
		echo '<p><label class="co-metabox">';
		_e( 'Card Reading', 'card-oracle' );
		echo '</label><br />';
		echo $this->get_reading_dropdown_box( $selected_reading[0] );
		echo '</p>';

		echo '<p><label class="co-metabox" for="co_card_order">';
		_e( 'Card Order', 'card-oracle' );
		echo '</label><br />';
		echo '<input class="co-metabox-textbox" name="co_card_order" type="number" min="1" ' . 
			 'ondrop="return false" onpaste="return false" value="' . 
			 esc_html( $post->co_card_order ) . '" /></p>';

//			 esc_html( get_post_meta( $post->ID, "co_card_order", true ) ) . '" /></p>';
		
	} // render_position_metabox

	/**
	 * Render the Reading Metabox for Cards CPT
	 * 
	 * @since	0.4.4
	 * @return	
	 */
	public function render_reading_metabox() {

		global $post;
		
		$settings =   array(
			'wpautop' => true,              // Whether to use wpautop for adding in paragraphs. Note that the paragraphs are added automatically when wpautop is false.
			'media_buttons' => false,        // Whether to display media insert/upload buttons
			'textarea_name' => 'footer_text',       // The name assigned to the generated textarea and passed parameter when the form is submitted.
			'textarea_rows' => 5,          // The number of rows to display for the textarea
			'tabindex' => '',               // The tabindex value used for the form field
			'editor_css' => '',             // Additional CSS styling applied for both visual and HTML editors buttons, needs to include <style> tags, can use "scoped"
			'editor_class' => '',           // Any extra CSS Classes to append to the Editor textarea
			'teeny' => false,               // Whether to output the minimal editor configuration used in PressThis
			'dfw' => false,                 // Whether to replace the default fullscreen editor with DFW (needs specific DOM elements and CSS)
			'tinymce' => true,              // Load TinyMCE, can be used to pass settings directly to TinyMCE using an array
			'quicktags' => true,            // Load Quicktags, can be used to pass settings directly to Quicktags using an array. Set to false to remove your editor's Visual and Text tabs.
			'drag_drop_upload' => false     // Enable Drag & Drop Upload Support (since WordPress 3.9)
		);

		// Generate nonce
		wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );

		// $display_input_checked = get_post_meta( $post->ID, 'display_question', true );
		$display_input_checked = $post->display_question;
		if ( $display_input_checked === "yes" ) {
			$display_input_checked = 'checked="checked"';
		}

		echo '<p><input class="co-metabox" type="checkbox" name="display_question" value="yes" ' . $display_input_checked . ' />';
		echo '<label for="display_question" class="co-metabox">';
		_e( 'Display Input Box', 'card-oracle' );
		echo '</label></p>';
		echo '<p class="co__help-text">';
		_e( 'Checking this box will display an input field to the users to enter a question.', 'card-oracle' );
		echo '</p>';
		echo '<p><label for="question_text" class="co-metabox">';
		_e( 'Text for question input box', 'card-oracle' );
		echo '</label><br />';
		echo '<input class="co-metabox" name="question_text" type="text" value="' . 
			wp_kses( get_post_meta( $post->ID, 'question_text', true ), array() ) . '" /></p>';
		echo '<p><label for="footer_text" class="co-metabox">';
		_e( 'Footer to be displayed on daily and random cards', 'card-oracle' );
		echo '</label><br />';
		wp_editor ( $post->footer_text, 'footer_text', $settings );
		
	} // render_reading_metabox

	/**
	 * Save the card post meta for Card Oracle
	 * 
	 * @since	0.4.4
	 * @return	
	 */
	public function save_card_oracle_meta_data() {
		global $post;

		if ( ! $this->co_check_rights() ) {
			return;
		}

		// If the Card Reading has been selected update it.
		if ( isset ( $_POST['co_reading_id'] ) ) {
			update_post_meta( $post->ID, 'co_reading_id', is_array( $_POST['co_reading_id'] ) ? $_POST['co_reading_id'] : serialize( $_POST['co_reading_id'] ) );
		}
		
		if ( isset ( $_POST['co_reading_dropdown'] ) ) {
			update_post_meta( $post->ID, 'co_reading_id', array( $_POST['co_reading_dropdown'] ) );
		}

		// If the Card Reading Display has been selected update it.
		if ( isset ( $_POST['display_question'] ) ) {
			update_post_meta( $post->ID, 'display_question', $_POST['display_question'] );
		} else {
			delete_post_meta( $post->ID, 'display_question' );
		}

		// If the Card Reading Footer text has been selected update it.
		if ( isset ( $_POST['footer_text'] ) ) {
			update_post_meta( $post->ID, 'footer_text', $_POST['footer_text'] );
		} else {
			delete_post_meta( $post->ID, 'footer_text' );
		}

		// If the Card Reading Display has been selected update it.
		if ( isset ( $_POST['question_text'] ) ) {
			update_post_meta( $post->ID, 'question_text', $_POST['question_text'] );
		} else {
			delete_post_meta( $post->ID, 'question_text' );
		}

		// If the Card Position has been selected update it.
		if ( isset( $_POST['co_card_position'] ) ) {
			update_post_meta( $post->ID, 'co_card_position', wp_kses_post( $_POST['co_card_position'] ) );
		} else {
			delete_post_meta( $post->ID, 'co_card_position' );
		}

		// If the Card has been selected update it.
		if ( isset( $_POST['co_card_id'] ) ) {
			update_post_meta( $post->ID, 'co_card_id', wp_kses_post( $_POST['co_card_id'] ) );
		} else {
			delete_post_meta( $post->ID, 'co_card_id' );
		}

		// If the Card has been selected update it.
		if ( isset( $_POST['co_position_id'] ) ) {
			update_post_meta( $post->ID, 'co_position_id', wp_kses_post( $_POST['co_position_id'] ) );
		} else {
			delete_post_meta( $post->ID, 'co_position_id' );
		}

		// If the Card Position has been selected update it.
		if ( isset( $_POST['co_card_order'] ) ) {
			update_post_meta( $post->ID, 'co_card_order', wp_kses_post( $_POST['co_card_order'] ) );
		} else {
			delete_post_meta( $post->ID, 'co_card_order' );
		}

	}

	/**
	 * Set the admin columns for Cards
	 * 
	 * @since	0.4.4
	 * @return	$columns 
	 */
	public function set_custom_cards_columns( $columns ) {
		// unset the date so we can move it to the end
		unset($columns['date']);

		$columns['card_reading'] = __('Associated Reading(s)', 'card-oracle' );
		$columns['number_card_descriptions'] = __('Number of Descriptions', 'card-oracle' );
		$columns['date'] = __('Date', 'card-oracle' );

		return $columns;
	}

	/**
	 * Set the admin columns for Descriptions
	 * 
	 * @since	0.4.4
	 * @return
	 */
	public function set_custom_descriptions_columns( $columns ) {
		// unset the date so we can move it to the end
		unset( $columns['title'] );
		unset( $columns['date'] );

		$columns['card_title'] = __( 'Card', 'card-oracle' );
		$columns['description_reading'] = __( 'Card Reading', 'card-oracle' );
		$columns['position_title'] = __( 'Position', 'card-oracle' );
		$columns['position_number'] = __( 'Position Number', 'card-oracle' );
		$columns['date'] = __( 'Date', 'card-oracle' );

		return $columns;
	}

	/**
	 * Set the admin columns for Card Readings
	 * 
	 * @since	0.4.4
	 * @return	$columns
	 */
	public function set_custom_readings_columns( $columns ) {
		// unset the date so we can move it to the end
		unset( $columns['date'] );

		$columns['co_shortcode'] = __( 'Shortcode', 'card-oracle' );
		$columns['number_reading_positions'] = __( 'Positions', 'card-oracle' );
		$columns['date'] = __( 'Date', 'card-oracle' );

		return $columns;
	}

	/**
	 * Set the admin columns for Card Positions
	 * 
	 * @since	0.4.4
	 * @return	$columns
	 */
	public function set_custom_positions_columns( $columns ) {
		// unset the date so we can move it to the end
		unset( $columns['date'] );

		$columns['card_reading'] = __( 'Card Reading', 'card-oracle' );
		$columns['card_order'] = __( 'Position', 'card-oracle' );
		$columns['date'] = __( 'Date', 'card-oracle' );

		return $columns;
	}

	/**
	 * Set the sortable columns for Cards
	 * 
	 * @since	0.4.4
	 * @return	$columns
	 */
	public function set_custom_sortable_card_columns( $columns ) {
		// unset the date so we can move it to the end
	
		$columns['card_reading'] = 'card_reading';
		$columns['number_card_descriptions'] = 'number_card_descriptions';

		return $columns;
	}

	/**
	 * Set the sortable columns for Descriptions
	 * 
	 * @since	0.4.4
	 * @return	$columns
	 */
	public function set_custom_sortable_description_columns( $columns ) {
		// unset the date so we can move it to the end
	
		$columns['card_title'] = 'card_title';
		$columns['description_reading'] = 'description_reading';
		$columns['position_title'] = 'position_title';
		$columns['position_number'] = 'position_number';

		return $columns;
	}

	/**
	 * Set the sortable columns for Positions
	 * 
	 * @since	0.4.4
	 * @return	$columns
	 */
	public function set_custom_sortable_position_columns( $columns ) {
		// unset the date so we can move it to the end
	
		$columns['card_reading'] = 'card_reading';
		$columns['card_order'] = 'cards_position';

		return $columns;
	}

	/**
	 * Check the user has permissions
	 * 
	 * @since	0.4.4
	 * @return	$columns
	 */
	public function co_check_rights() {

		global $post;

        // Check nonce
        if ( ! isset( $_POST['meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['meta_box_nonce'], 'meta_box_nonce' ) ) {
			return false;
		}

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

        // Prevent quick edit from clearing custom fields
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return false;
		}

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return false;
		}

        return true;

	}

}