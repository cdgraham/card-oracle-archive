<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://cdgraham.com
 * @since      0.3.0
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.3.0
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 * @author     Christopher Graham <chris@chillichalli.com>
 */
class Card_Oracle_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.3.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'card-oracle',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
