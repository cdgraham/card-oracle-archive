<?php

/**
 * Fired during plugin activation
 *
 * @link       https://cdgraham.com
 * @since      0.4.1
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.4.1
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 * @author     Christopher Graham <chris@chillichalli.com>
 */
class Card_Oracle_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.4.1
	 */
	public static function activate() {

		// Installed version number
		update_option( 'card_oracle_version', CARD_ORACLE_VERSION );
		
		flush_rewrite_rules();

	}

}