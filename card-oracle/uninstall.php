<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://cdgraham.com
 * @since      0.4.4
 *
 * @package    Card_Oracle
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;

	delete_option( 'card_oracle_version' );
	
	$sql = "DELETE posts, terms, meta
	FROM wpl9_posts posts
	LEFT JOIN wpl9_term_relationships terms
		ON ( posts.ID = terms.object_id )
	LEFT JOIN wpl9_postmeta meta
		ON ( posts.ID = meta.post_id )
	WHERE posts.post_type in ( 'co_cards', 'co_descriptions', 'co_positions', 'co_readings' );";

	$wpdb->get_results( $sql, OBJECT );

}
