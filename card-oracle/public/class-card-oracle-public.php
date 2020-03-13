<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      1.0.0
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/card-oracle-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/card-oracle-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Card Oracle shortcode to display card reading
	 * 
	 * @since	1.0.0
	 * @return
	 */
	public function display_card_oracle_set( $atts ) {

		global $wpdb;

		extract( shortcode_atts( array( 'id' => 1 ), $atts));

		// create a zero position idea when card position has not been defined yet.
		$zero_position = array( 'blank' );
		
		// get the card position meta data, strip leading and trailing spaces and merge it.
		$positions = array_map('trim', explode( ',', get_post_meta( $id, 'co_positions', true ) ) );
		$positions_count = count( $positions );

		$position_text = array_merge( $zero_position, $positions );


		// print_r ( $position_text );

		$args = array(
			'fields' => 'ids',
			'post_type' => 'co_cards',
			'meta_query' => array(
				array(
					'key' => 'co_reading_id',
					'value' => $id,
				),
				array(
					'key' => 'co_card_position',
					'value' => 1,
				),
			),
		);

		$cards = new WP_Query( $args );

		print_r( $cards->posts );

		// The number of cards returned
		$card_count = count( $cards->posts );

		// Get just the card ids and shuffle them
		$card_ids = $cards->posts;
		shuffle( $card_ids );
		print_r( $card_ids );

		// Display the form
		echo '<form name="form2" action="" method="post">
			<input name="question" id="question" type="text" size="40" placeholder="Enter your question?" required/>
			  <input name="picks" id="picks" type="hidden">
			<div class="btn-block">
				<button name="Submit" type="submit" id="Submit">Submit</button>
			</div>
		</form>
		<h2>Next select ' . $positions_count . ' cards</h2>';

		// Display the back of the cards
		for ( $i = 0; $i < $card_count; $i++) {
			echo '<button type="button" value="'. $i .'" id="id' . $i .'" onclick="this.disabled = true;" class="btn btn-default clicked"></button>';
		}

	}
}
