<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      0.4.1
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/card-oracle-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/card-oracle-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Card Oracle shortcode to display card reading
	 * 
	 * @since	0.4.1
	 * @return
	 */
	public function display_card_oracle_set( $atts ) {
		global $wpdb;

		extract( shortcode_atts( array( 'id' => 1 ), $atts ) );

		$sql = "SELECT p1.post_title, p1.ID FROM " . $wpdb->posts . " p1 " .
					"INNER JOIN " . $wpdb->prefix . "postmeta mt1" . " " . 
					"ON p1.id = mt1.post_id " .
					"INNER JOIN " . $wpdb->prefix . "postmeta mt2" . " " . 
					"ON p1.id = mt2.post_id " .
					"WHERE mt1.meta_key = 'co_reading_id' " . 
					"AND mt1.meta_value = " . $id . " " .
					"AND mt2.meta_key = 'co_card_order' " .
					"AND p1.post_type = 'co_positions' " .
					"AND post_status = 'publish' " . 
					"ORDER BY mt2.meta_value";
					
		// The $positions is an array of all the positions in a reading, it consists of
		// the position title and position ID
		$positions = $wpdb->get_results( $sql, OBJECT );
		// Get the number of positions for this reading type.
		$positions_count = $wpdb->num_rows;

		if( !isset( $_POST['Submit'] ) ):
			$sql = "SELECT p1.ID FROM " . $wpdb->prefix . "posts p1 " . 
				"INNER JOIN " . $wpdb->prefix . "postmeta m1 " . 
				"ON ( p1.ID = m1.post_id ) " . 
				"WHERE ( ( m1.meta_key = 'co_reading_id' AND m1.meta_value = '" . $id . 
				"' ) ) AND p1.post_type = 'co_cards' AND ( ( p1.post_status = 'publish' ) )";

			$card_ids = $wpdb->get_results( $sql, OBJECT );

			// The number of cards returned
			$card_count = $wpdb->num_rows;

			// Get just the card ids and shuffle them
			shuffle( $card_ids );

			// Display the form
			echo '<div class="data" data-positions="' . $positions_count .'">
				<form name="form2" action="" method="post">
				<input name="question" id="question" type="text" size="40" placeholder="Enter your question?" required/>
				<input name="picks" id="picks" type="hidden">
				<div class="btn-block">
					<button name="Submit" type="submit" id="Submit">Submit</button>
				</div>
			</form>
			<h2>Next select ' . $positions_count . ' cards</h2>';

			// Display the back of the cards
			for ( $i = 0; $i < $card_count; $i++) {
				echo '<button type="button" value="'. $card_ids[$i]->ID .
					'" id="id' . $card_ids[$i]->ID .
					'" onclick="this.disabled = true;" 
					class="btn btn-default clicked" 
					><img class="img-btn" src="' . get_the_post_thumbnail_url( $id, 'medium' ) . '">
					</button>';
			}
			echo '</div>';
		endif;

		if( isset( $_POST['Submit'] ) )
		{
			$cards = explode( ',', $_POST['picks'] );

			echo '<div class="w3-container">
			<h2>Your Question: ' . $_POST["question"] . '</h2>';

			for ( $i = 0; $i < count( $cards ); $i++ ) {
		
				$sql = "SELECT m1.post_id FROM " . $wpdb->prefix . "postmeta m1 " .
					"INNER JOIN " . $wpdb->prefix . "postmeta m2 " . 
					"ON m1.post_id = m2.post_id " .
					"AND m2.meta_key = 'co_card_id' " .
					"AND m2.meta_value = " . $cards[$i] . " " .
					"WHERE m1.meta_key = 'co_position_id' " . 
					"AND m1.meta_value = " . $positions[$i]->ID;

				$description_id = $wpdb->get_results( $sql, OBJECT );

				echo '<div class="w3-row-padding">
					<h3 class="w3-container w3-center w3-indigo w3-round-large w3-padding-16">' . $positions[$i]->post_title . '</h3>
					<div class="w3-center">
						' . get_the_post_thumbnail( $cards[$i], 'medium' ) . '
					</div>
					<div class="w3-container">
						<h3>' . get_the_title( $cards[$i] ) . '</h3>';

				if ( $description_id ) {
					$description = get_post( $description_id[0]->post_id );
					echo '<p>' . apply_filters('the_content', $description->post_content ) .  '</p>';
				}

				echo '</div></div>';
			}
		} // End POST submit
	} // End display_card_oracle_set

} // End Class Card_Oracle_Public