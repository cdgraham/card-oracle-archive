<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://cdgraham.com
 * @since             0.5.0
 * @package           Card_Oracle
 *
 * @wordpress-plugin
 * Plugin Name:       Card Oracle
 * Plugin URI:        https://chillichalli.com/card-oracle
 * Description:       This plugin lets you create tarot and oracle readings using your own cards, spreads and interpretations.
 * Version:           0.5.0
 * Author:            Christopher Graham
 * Author URI:        https://cdgraham.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       card-oracle
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 0.5.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
if ( ! defined( 'CARD_ORACLE_VERSION' ) ) {
	define( 'CARD_ORACLE_VERSION', '0.5.0' );
}

define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-card-oracle-activator.php
 */
function activate_card_oracle() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-card-oracle-activator.php';
	Card_Oracle_Activator::activate();

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-card-oracle-deactivator.php
 */
function deactivate_card_oracle() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-card-oracle-deactivator.php';
	Card_Oracle_Deactivator::deactivate();

}

register_activation_hook( __FILE__, 'activate_card_oracle' );
register_deactivation_hook( __FILE__, 'deactivate_card_oracle' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-card-oracle.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.5.0
 */
function run_card_oracle() {

	$plugin = new Card_Oracle();
	$plugin->run();

}

run_card_oracle();