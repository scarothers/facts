<?php
/**
 * Administrative functions
 * @package Facts
 */

class Facts_Admin {

    /**
     * Hook into WordPress API on init
     */
    public function __construct( $key, &$parent ) {

        $this->key    = $key;
        $this->parent = &$parent;

        $this->admin_actions();
        $this->admin_filters();

    }

    /**
     * Hook actions in that run on admin page-load
     *
     * @uses add_action()
     */
    private function admin_actions() {
        // add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        // add_action( 'wp_insert_post_data', array( $this, 'save_post_meta' ), 10, 2 );
        add_action( 'manage_posts_custom_column', array( $this, 'add_custom_column' ), 10, 2 );
    }

    /**
     * Hook filters in that run on admin page-load
     *
     * @uses add_filter()
     */
    private function admin_filters() {
        add_filter( 'manage_posts_columns', array( $this, 'set_custom_column' ) );
    }

    /**
     * Show 'Plain English' instead of title
     */
    public function add_custom_column( $columns, $post_id ) {
        // global $post;

        // $switch( $column ) {
        //     case 'title':
        //         echo $post->post_content;
        //     break;
        // }

    }

    /**
     * Add column 'description'
     */
    public function set_custom_column( $defaults ) {
        $defaults['description'] = __( 'Description' );
        return $defaults;
    }

    /**
     * Add meta boxes to admin pages
     * 
     * @uses add_meta_box()
     */
    function add_meta_boxes() {
        add_meta_box( $this->key, 'Truthiness', array( $this, 'truthiness_meta_box' ), $this->key, 'side' );
    }

   /**
     * Render truthiness meta box
     * 
     * @uses get_post_meta()
     * @param object $post_data as WP_Post
     * @return turthiness-meta-box html
     */
    function truthiness_meta_box( $post_data ) {

        var_dump( $post_data );
        
        $selected = 'true';

        if ( '1' == get_post_meta( $post_data->ID, 'truthiness', true ) )
            $selected = 'false';

        $this->parent->template( 'truthiness-meta-box', compact( 'selected' ) );

    }

    /**
     * Save information from truthiness meta box
     */
    function save_post_meta( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return;
        
        update_post_meta( $post_id, 'truthiness', $_POST['truthiness'] );

    }

}