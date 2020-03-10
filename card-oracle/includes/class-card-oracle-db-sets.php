<?php

class CO_Sets_DB extends CO_DB {
/**
 * Get things started
 *
 * @access  public
 * @since   1.0
*/
    public function __construct() {

	    global $wpdb;

	    $this->table_name  = $wpdb->prefix . 'card_oracle_sets';
	    $this->primary_key = 'set_id';
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
            'set_id'    => '%d',
            'set_name'  => '%s',
            'positions' => '%d',
        );
    }

    /**
	 * Get default columns values
	 *
	 * @access  public
	 * @since   1.0
	*/
	public function get_columns_defaults() {
		return array(
            'set_id'    => 0,
            'set_name'  => '',
            'positions' => 1,
        );
    }

    /**
    * Retrieve sets from the database
    *
    * @access  public
    * @since   1.0
    * @param   array $args
    * @param   bool  $count  Return only the total number of results found (optional)
    */
    public function get_sets( $args = array(), $count = false ) {

        global $wpdb;

        $defaults = array (
            'number'    => 20,
            'offset'    => 0,
            'set_id'    => 0,
            'set_name'  => '',
            'positions' => 0,
            'orderby'   => 'set_id',
            'order'     => 'ASC',
        );

        $args = wp_parse_args( $args, $defaults );

        if( $args['number'] < 1 ) {
            $args['number'] = 999999999999;
        }
    
        $where = '';
    
        // specific referrals
        if( ! empty( $args['set_id'] ) ) {
    
            if( is_array( $args['set_id'] ) ) {
                $set_id = implode( ',', $args['set_id'] );
            } else {
                $set_id = intval( $args['set_id'] );
            }
    
            $where .= "WHERE `set_id` IN( {$set_ids} ) ";
    
        }

        if( ! empty( $args['set_name'] ) ) {

            if( empty( $where ) ) {
                $where .= " WHERE";
            } else {
                $where .= " AND";
            }
    
            if( is_array( $args['set_name'] ) ) {
                $where .= " `set_name` IN(" . implode( ',', $args['set_name'] ) . ") ";
            } else {
                if( ! empty( $args['search'] ) ) {
                    $where .= " `set_name` LIKE '%%" . $args['set_name'] . "%%' ";
                } else {
                    $where .= " `set_name` = '" . $args['set_name'] . "' ";
                }
            }
    
        }

        $args['orderby'] = ! array_key_exists( $args['orderby'], $this->get_columns() ) ? $this->primary_key : $args['orderby'];

	    if ( 'total' === $args['orderby'] ) {
		    $args['orderby'] = 'total+0';
	    } else if ( 'subtotal' === $args['orderby'] ) {
		    $args['orderby'] = 'subtotal+0';
	    }

        $cache_key = ( true === $count ) ? md5( 'co_sets_count' . serialize( $args ) ) : md5( 'co_sets_' . serialize( $args ) );

        $results = wp_cache_get( $cache_key, 'sets' );
        
        if ( false === $results ) {

            if ( true === $count ) {
    
                $results = absint( $wpdb->get_var( "SELECT COUNT({$this->primary_key}) FROM {$this->table_name} {$where};" ) );
    
            } else {
    
                $results = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM {$this->table_name} {$where} ORDER BY {$args['orderby']} {$args['order']} LIMIT %d, %d;",
                        absint( $args['offset'] ),
                        absint( $args['number'] )
                    )
                );
    
            }
    
            wp_cache_set( $cache_key, $results, 'orders', 3600 );
    
        }
    
        return $results;
    
    }

    /**
	 * Return the number of results found for a given query
	 *
	 * @param  array  $args
	 * @return int
	 */
	public function count( $args = array() ) {
		return $this->get_sets( $args, true );
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
            set_id bigint(20) NOT NULL AUTO_INCREMENT,
            set_name varchar(100) NOT NULL,
            positions tinyint NOT NULL,
            PRIMARY KEY (set_id)
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;";

        dbDelta( $sql );

        update_option( $this->table_name . '_db_version', $this->version );
    }
}