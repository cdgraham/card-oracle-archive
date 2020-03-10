<?php

/**
 * Fired during plugin activation
 *
 * @link       https://cdgraham.com
 * @since      0.3.0
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.3.0
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
	 * @since    0.3.0
	 */
	public static function activate() {

		$dbsets = new CO_Sets_DB;
		$dbsets->create_table();

		//$dbcards = new CO_Cards_DB;
		//$dbcards->create_table();
	}

}
