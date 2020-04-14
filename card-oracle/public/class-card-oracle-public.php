<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      0.4.5
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/public
 * @author     Christopher Graham <chris@chillichalli.com>
 */
class Card_Oracle_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.4.5
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.4.5
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.4.5
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.4.5
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/card-oracle-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.4.5
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/card-oracle-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Get all the published cards for a specific reading
	 * 
	 * @since	0.4.5
	 * @return
	 */
	public function get_cards_for_reading( $reading_id ) {
		global $wpdb;

		$sql = "SELECT p1.ID FROM " . $wpdb->prefix . "posts p1 " . 
			"INNER JOIN " . $wpdb->prefix . "postmeta m1 " . 
			"ON ( p1.ID = m1.post_id ) " . 
			"WHERE ( ( m1.meta_key = '_co_reading_id' AND m1.meta_value = '" .  $reading_id . 
			"' ) ) AND p1.post_type = 'co_cards' AND p1.post_status = 'publish' ORDER BY p1.ID";

		return $wpdb->get_results( $sql, OBJECT );
	}

	/**
	 * Card Oracle shortcode to display card reading
	 * 
	 * @since	0.4.7
	 * @return
	 */
	public function display_card_oracle_card_of_day( $atts ) {

		if ( empty( $atts['id'] ) ) {
			return;
		} else {
			$reading_id = $atts['id'];
		}

		$card_ids = $this->get_cards_for_reading( $reading_id );
		$index = date( 'z' ) % max( count( $card_ids ), 1 );
		$card_of_day = get_post( $card_ids[$index]->ID );
		$image = get_the_post_thumbnail_url( $card_of_day, 'medium' );
		$footer = get_post_meta( $reading_id, 'footer_text', true );

		$display_html = '<div class="cotd-wrapper">
			<cotd-header>' . $card_of_day->post_title . '</cotd-header>
			<cotd-main>' . $card_of_day->post_content . '</cotd-main>';

			
		if ( ! empty( $image ) ) {
			$display_html .= '<cotd-aside><img src="' . $image . '"></cotd-aside>';
		}

		if ( ! empty( $footer ) ) {
			$display_html .= '<cotd-footer>' . $footer . '</cotd-footer>';
		}

		$display_html .= '</div>';

		return $display_html;
	}

	/**
	 * Card Oracle shortcode to display card reading
	 * 
	 * @since	0.4.5
	 * @return
	 */
	public function display_card_oracle_random_card( $atts ) {

		if ( empty( $atts['id'] ) ) {
			return;
		} else {
			$reading_id = $atts['id'];
		}

		$card_ids = $this->get_cards_for_reading( $reading_id );
		$card_count = count( $card_ids ) - 1;
		$card_of_day = get_post( $card_ids[rand( 0, $card_count )]->ID );
		$image = get_the_post_thumbnail_url( $card_of_day, 'medium' );
		$footer = get_post_meta( $reading_id, 'footer_text', true );

		if ( ! empty( $footer ) ) {
			$footer_html = '<cotd-footer>' . $footer . '</cotd-footer>';
		}

		$display_html = '<div class="cotd-wrapper">
				<cotd-header>' . $card_of_day->post_title . '</cotd-header>
				<cotd-main>' . $card_of_day->post_content . '</cotd-main>';

		if ( ! empty( $image ) ) {
			$display_html .= '<cotd-aside><img src="' . $image . '"></cotd-aside>';
		}

		if ( ! empty( $footer ) ) {
			$display_html .= '<cotd-footer>' . $footer . '</cotd-footer>';
		}

		$display_html .= '</div>';

		return $display_html;
	}

	/**
	 * Card Oracle shortcode to display card reading
	 * 
	 * @since	0.4.6
	 * @return
	 */
	public function display_card_oracle_set( $atts ) {
		global $wpdb;
		$page_display = '';

		if ( empty( $atts['id'] ) ) {
			return;
		} else {
			$reading_id = $atts['id'];
		}

		$sql = "SELECT p1.post_title, p1.ID FROM " . $wpdb->posts . " p1 " .
					"INNER JOIN " . $wpdb->prefix . "postmeta mt1" . " " . 
					"ON p1.id = mt1.post_id " .
					"INNER JOIN " . $wpdb->prefix . "postmeta mt2" . " " . 
					"ON p1.id = mt2.post_id " .
					"WHERE mt1.meta_key = '_co_reading_id' " . 
					"AND mt1.meta_value = '" . $reading_id . "' " .
					"AND mt2.meta_key = '_co_card_order' " .
					"AND p1.post_type = 'co_positions' " .
					"AND post_status = 'publish' " . 
					"ORDER BY mt2.meta_value";
			
		// The $positions is an array of all the positions in a reading, it consists of
		// the position title and position ID
		$positions = $wpdb->get_results( $sql, OBJECT );
		// Get the number of positions for this reading type.
		$positions_count = $wpdb->num_rows;

		// Get the question text
		$question_text = get_post_meta( $reading_id, 'question_text', true );

		if ( !isset( $_POST['Submit'] ) ):
			// Get the image for the back of the card
			$card_back_url = get_the_post_thumbnail_url( $reading_id, 'medium' );
			if ( empty( $card_back_url ) ) {
				$card_back_url = PLUGIN_URL . 'assets/images/cardback.png';
			}

			// Get all the published cards for this reading
			$card_ids = $this->get_cards_for_reading( $reading_id );

			// The number of cards returned
			$card_count = count( $card_ids );

			// Get just the card ids and shuffle them
			shuffle( $card_ids );

			// Display the form
			$page_display = '<div class="data" data-positions="' . $positions_count .'">
				<form name="form2" action="" method="post">';

			if ( get_post_meta( $reading_id, 'display_question', true ) === "yes" ) {
				$page_display .= '<input name="question" id="question" type="text" size="40" placeholder="' . 
					$question_text . '" required/>';
			}

			/* translators: %d is a number */
			$select_cards = esc_html( sprintf( _n( 'Next select %d card.', 'Next select %d cards.', $positions_count, 'card-oracle' ),
				number_format_i18n( $positions_count ) ) );

			$page_display .= '<input name="picks" id="picks" type="hidden">
				<div class="btn-block">
					<button name="Submit" type="submit" id="Submit">Submit</button>
				</div>
			</form>
			<h2>' . $select_cards . '</h2>';

			// Display the back of the cards
			for ( $i = 0; $i < $card_count; $i++) {
				$page_display .= '<button type="button" value="'. $card_ids[$i]->ID .
					'" id="id' . $card_ids[$i]->ID . '" onclick="this.disabled = true;" 
					class="btn btn-default clicked"><img class="img-btn" src="' . $card_back_url . '">
					</button>';
			}

			$page_display .= '</div>';
		endif;

		if ( isset( $_POST['Submit'] ) )
		{
			$cards = explode( ',', $_POST['picks'] );
			$page_display .= '<div class="">';

			if ( ! empty( $_POST["question"] ) ) {
				$page_display .= '<h2>' . $question_text . '</h2><h3>' . sanitize_text_field( $_POST["question"] ) . '</h3>';
			}

			for ( $i = 0; $i < count( $cards ); $i++ ) {
		
				$sql = "SELECT m1.post_id FROM " . $wpdb->prefix . "postmeta m1 " .
				"INNER JOIN " . $wpdb->prefix . "posts p1 " .
				"ON p1.ID = m1.post_id AND p1.post_status = 'publish' " .
				"INNER JOIN " . $wpdb->prefix . "postmeta m2 " . 
				"ON m1.post_id = m2.post_id " .
				"AND m2.meta_key = '_co_card_id' " .
				"AND m2.meta_value = " . $cards[$i] . " " .
				"WHERE m1.meta_key = '_co_position_id' " . 
				"AND m1.meta_value LIKE '%" . serialize( $positions[$i]->ID ) . "%';";

				$description_id = $wpdb->get_results( $sql, OBJECT );
				if ( $description_id ) {
					$description = get_post( $description_id[0]->post_id );
					$main_text = '<cotd-main><h3>' . get_the_title( $cards[$i] ) . '</h3>' .
						apply_filters('the_content', $description->post_content ) . '</cotd-main>';
				} else {
					$main_text = '<cotd-main><h3>' . get_the_title( $cards[$i] ) . '</h3></cotd-main>';
				}

				$page_display .= '<div class="cotd-wrapper">
						<cotd-header>' . $positions[$i]->post_title . '</cotd-header>
						<cotd-aside><img src="' . get_the_post_thumbnail_url( $cards[$i], 'medium' ) . '"></cotd-aside>' . 
						$main_text . 
					 '</div>';
			}

			$page_display .= '</div>';
		} // End POST submit

		return $page_display;
	} // End display_card_oracle_set

} // End Class Card_Oracle_Public