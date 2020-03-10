<?php

class CO_Cards_DB extends CO_DB {
/**
 * Get things started
 *
 * @access  public
 * @since   1.0
*/
    public function __construct() {

	    global $wpdb;

	    $this->table_name  = $wpdb->prefix . 'card_oracle_cards';
	    $this->primary_key = 'card_id';
	    $this->version     = '1.0';

    }

    /**
	 * Get columns and formats
	 *
	 * @access  public
	 * @since   1.0
	*/
	public function get_columns() {
		return array(
            'card_id'   => '%d',
            'set_id'    => '%d',
            'card_name' => '%s',
            'position'  => '%d',
            'card_text' => '%s',
        );
    }

    /**
	 * Create the table
	 *
	 * @access  public
	 * @since   1.0
	*/
    public function create_table() {
        global $wpdb;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $sql = "CREATE TABLE " . $this->table_name . " (
            card_id bigint(20) NOT NULL AUTO_INCREMENT,
            set_id bigint(20) NOT NULL AUTO_INCREMENT,
            card_name varchar(100) NOT NULL,
            position tinyint NOT NULL,
            card_text longtext,
            PRIMARY KEY (set_id)
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;";

        dbDelta( $sql );

        update_option( $this->table_name . '_db_version', $this->version );
    }
}