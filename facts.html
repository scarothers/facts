<?php
/*
Plugin Name: Facts
Description: Enter facts for Truth Teller
Version: 1.0 
Author: Yuri Victor ( hi@yurivictor.com )
License: MIT (attached)
*/

 if ( ! class_exists( 'Facts' ) ):

 final class Facts {


    /** Constants *************************************************************/

    const name    = 'Facts';  // human-readable name of plugin
    const key     = 'facts';  // plugin slug, generally base filename and url endpoint

    const version = '1.0';


    /** Variables *************************************************************/

    var $facts; // Array of objects


    /** Load Methods **********************************************************/


    /**
     * Register with WordPress API on Construct
     *
     * @uses add_action() to hook methods into WordPress actions
     *
     */
    function __construct() {

        self::includes();
        self::add_actions();

        //self::add_admin_actions();

    }

    /**
     * Include the necessary files
     */
    private static function includes() {
        //require( dirname( __FILE__ ) . '/classes/class-facts-admin.php' );
    }

    /**
     * Hook actions in that run on every page-load
     *
     * @uses add_action()
     */
    private static function add_actions() {

        add_action( 'init', array( __CLASS__, 'init' ) );
        add_action( 'init', array( __CLASS__, 'register_cpt' ) );

        add_action( 'init', array( __CLASS__, '_endpoints_add_endpoint' ) );
        add_action( 'template_redirect', array( __CLASS__, '_endpoints_template_redirect' ) );

    }


    /**
     * Hook actions in that run on every admin page-load
     *
     * @uses is_admin()
     * @return if not in admin area
     */
    private function add_admin_actions() {

        // Bail if not in admin area
        if ( ! is_admin() )
            return;

        $this->admin = new Facts_Admin( self::key, $this );

    }


    /** Public Methods ********************************************************/

    /**
     * Truth Teller initialization functions.
     *
     * This is where Truth Teller sets up any additional things it needs to run
     * inside of WordPress.
     */
    public static function init() {
        register_activation_hook( __FILE__, '_endpoints_activate' );     
        register_deactivation_hook( __FILE__, '_endpoints_deactivate' );
    }


    /**
     * Register "facts" custom post type
     *
     * @uses register_post_type
     */
    public function register_cpt() {

        $labels = array(
            'name'          => __( 'Facts', self::key ),
            'singular_name' => __( 'Fact', self::key ),
            'add_new'       => __( 'Add fact', self::key ),
            'add_new_item'  => __( 'Add Plain English', self::key ),
            'edit_item'     => __( 'Edit Plain English', self::key )
        );

        $args = array(
            'labels'      => $labels,
            'public'      => false,
            'show_ui'     => true,
            'supports'    => array( 'editor', 'custom-fields' ),
            'rewrite'     => false,
            'taxonomies'  => array( 'post_tag' ),
        );

        register_post_type( self::key, $args );

    }

    /**
     * Activate "facts" url for posts
     *
     * @uses flush_rewrite_rules
     */
    public function _endpoints_activate() {
        self::_endpoints_add_endpoint();
        flush_rewrite_rules();
    }

    /**
     * Add "facts" url for posts
     *
     * @uses add_rewrite_endpoint
     */
    public function _endpoints_add_endpoint() {
        add_rewrite_endpoint( 'facts', EP_PERMALINK | EP_PAGES );
    }
    
    /**
     * Removes "facts" url for posts
     *
     * @uses flush_rewrite_rules
     */    
    public function _endpoints_deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Redirects users to "facts" json
     *
     * @uses flush_rewrite_rules
     * @param $wp_query array of query_vars
     * @return if there is no "fact" query variable
     */    
    public function _endpoints_template_redirect() {
        
        global $wp_query;
 
        // if this is not a request for json or it's not a singular object then bail
        if ( ! isset( $wp_query->query_vars['facts'] ) )
                return;

        self::_endpoints_do_json();

        exit;

    }

    /**
     * Create json feed of facts
     *
     * @uses flush_rewrite_rules
     * @param $post array of posts
     * @return if not in a post or missing the "fact" query variable
     *
     * plain_english, keywords, assertion( true, false ), source_url
     *
     */    
    public function _endpoints_do_json() {

        header( 'Content-Type: application/json' );

        $query = new WP_Query( array(
            'post_type'      => 'facts',
            'posts_per_page' => -1
        ) );
        $count = 1;
        $total = count( $query->posts );

        $json = '{"facts":[';

        foreach( $query->posts as $post ) {
            $fact = array();
            $fact['id']            = $post->ID;
            $fact['plain_english'] = $post->post_content;
            $fact['keywords']      = wp_get_post_tags( $post->ID, array( 'fields' => 'names' ) );
            $fact['assertion']     = get_post_meta( $post->ID, 'assertion', true );
            $fact['source_url']    = get_post_meta( $post->ID, 'URL', true );
            $fact['source_org']    = get_post_meta( $post->ID, 'source_org', true );
            $fact['speaker']       = get_post_meta( $post->ID, 'speaker', true );
            $fact['party']         = get_post_meta( $post->ID, 'party', true );
            $fact['complicated']         = get_post_meta( $post->ID, 'complicated', true );
            $fact['post_title']   = get_post_meta( $post->ID, 'post_title', true );

            $json .= json_encode( $fact );
            if ( $count != $total ) {
                $json .= ',';
            }
            $count++;
        }

        $json .= ']}';
        

        echo $json;

    }
 
    /** 
     * Load a template. MVC FTW!
     * @param string $template the template to load, without extension (assumes .php). File should be in templates/ folder
     * @param args array of args to be run through extract and passed to template
     */
    public function template( $template, $args = array() ) {

        extract( $args );

        if ( ! $template )
            return false;
            
        $path = dirname( __FILE__ ) . "/templates/{$template}.php";
        $path = apply_filters( 'truthteller', $path, $template );

        include $path;
        
    }
    

 }

 $facts = new Facts();

 endif;