<?php

class Sets_List extends WP_List_Table {

    /**
     * Constuctor, we override the parent to pass our own arguments
     * The three main parameters: sigular and plural labesl, ajax
     */
    function __construct() {
        parent::__construct( [
            'singular'  => __( 'Set', 'sp' ),
            'plural'    => __( 'Sets', 'sp' ),
            'ajax'      => false
        ] );
    }

    /**
    * Delete a card set record.
    *
    * @param int $id set ID
    */
    public static function delete_set( $set_id ) {
        global $wpdb;

        $wpdb->delete(
            "{$wpdb->prefix}card_oracle_sets",
            [ 'set_id' => $set_id ],
            [ '%d' ]
        );
    }

    /**
    * Method for name column
    *
    * @param array $item an array of DB data
    *
    * @return string
    */
    function column_name( $item ) {
        print_r ( $item );

        // create a nonce
        $delete_nonce = wp_create_nonce( 'sp_delete_set' );
      
        $title = '<strong>' . $item['set_id'] . '</strong>';
      
        $actions = [
          'delete' => sprintf( '<a href="?page=%s&action=%s&set_id=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['set_id'] ), $delete_nonce )
        ];
        
        return $title . $this->row_actions( $actions );
    }

    /**
    * Define the columns that are going to be used in the table
    * @return array $columns, the array of columns to use with the table
    */
    function get_columns() {
        $columns = array (
            'cb'        => '<input type="checkbox" />',
            'set_id'    => __( 'ID', $this->plugin_text_domain ),
            'set_name'  => __( 'Set Name', $this->plugin_text_domain ),
            'positions' => __( 'Positions', $this->plugin_text_domain )
        );

        return $columns;
    }

    /**
     * Render a column when no column specific method exists.
     * 
     * @param array $item
     * @param string $column_name
     * 
     * @return mixed
     */
    public function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'set_id':
                $delete_nonce = wp_create_nonce( 'sp_delete_set' );
                $title = '<strong>' . $item[ $column_name ] . '</strong>';
                $actions = [
                    'delete' => sprintf( '<a href="?page=%s&action=%s&set_id=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['set_id'] ), $delete_nonce )
                  ];
                  
                return $title . $this->row_actions( $actions );
            case 'set_name':
            case 'positions':
                return $item[ $column_name ];
            default:
                return print_r ( $item, true );
        }
    }
    /**
     * Which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted
     * the user
     */
    public function get_sortable_columns() {
        $sortable = array(
            'set_id'        => array( 'set_id', true ),
            'set_name'      => array( 'set_name', true ),
            'positions' => array( 'positions', false )
        );

        return $sortable;
    }

    /**
     * Text displayed when no card sets found
     */
    public function no_items() {
        _e( 'No cards sets available.', 'sp');
    }

    /**
    * Render the bulk edit checkbox
    *
    * @param array $item
    *
    * @return string
    */
    function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['set_id'] );
    }

    /**
    * Returns an associative array containing the bulk action
    *
    * @return array
    */
    public function get_bulk_actions() {
        $actions = [ 'bulk-delete' => 'Delete' ];
  
        return $actions;
    }

    public function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {
      
          // In our file that handles the request, verify the nonce.
          $nonce = esc_attr( $_REQUEST['_wpnonce'] );
      
          if ( ! wp_verify_nonce( $nonce, 'sp_delete_set' ) ) {
            die( 'Go get a life script kiddies' );
          }
          else {
            self::delete_set( absint( $_GET['set_id'] ) );
      
            wp_redirect( esc_url( add_query_arg() ) );
            exit;
          }
      
        }
      
        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
             || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {
      
          $delete_ids = esc_sql( $_POST['bulk-delete'] );
      
          // loop over the array of record IDs and delete them
          foreach ( $delete_ids as $id ) {
            self::delete_set( $id );
      
          }
      
          wp_redirect( esc_url( add_query_arg() ) );
          exit;
        }
      }
    public function prepare_items() {
        // code to handle bulk actions
	    
        //used by WordPress to build and fetch the _column_headers property
        //$this->_column_headers = $this->get_column_info();
        $this->_column_headers = array(
            $this->get_columns(),
            array(),
            $this->get_sortable_columns(),
        );		      
        $table_data = $this->fetch_table_data();
        
        // code to handle data operations like sorting and filtering
	    
        // start by assigning your data to the items variable
        $this->items = $table_data;	
	    
        // code to handle pagination
        // CDG Add screen options to page to set this
        // 	$users_per_page = $this->get_items_per_page( 'sets_per_page' );
        $sets_per_page = 10;
        $table_page = $this->get_pagenum();

        // provide the ordered data to the List Table
        // we need to maually slice the data based on the current pagination
        $this->items = array_slice( $table_data, ( ( $table_page -1 ) * $sets_per_page ), $sets_per_page );

        // set the pagination arguments
        $total_sets = count( $table_data );
        $this->set_pagination_args( array (
            'total_items' => $total_sets,
            'per_page' => $sets_per_page,
            'total_pages' => ceil( $total_sets/$sets_per_page )
        ) );
    }

    public function fetch_table_data() {
        global $wpdb;
        $wpdb_table = $wpdb->prefix . 'card_oracle_sets';		
        $orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'set_id';
        $order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'ASC';
  
        $user_query = "SELECT 
                          set_id, set_name, positions
                        FROM 
                          $wpdb_table 
                        ORDER BY $orderby $order";
  
        // query output_type will be an associative array with ARRAY_A.
        $query_results = $wpdb->get_results( $user_query, ARRAY_A  );
        
        // return result array to prepare_items.
        return $query_results;	
      }
}