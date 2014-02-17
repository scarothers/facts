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
    * START sara's messing around ..................................................................................
    */

    /**
    * Create a meta box to create a more user-friendly way to enter in metadata.
    * ALl of the following is working off of this walkthrough: 
    * http://www.creativebloq.com/wordpress/user-friendly-custom-fields-meta-boxes-wordpress-5113004
    */

    //We create an array called $meta_box and set the array key to the relevant post type
    $meta_box['post'] = array(
    
    //This is the id applied to the meta box
    'id' => 'post-format-meta',   
    
    //This is the title that appears on the meta box container
    'title' => 'Additional Fact Check Metadata',    
    
    //This defines the part of the page where the edit screen section should be shown
    'context' => 'normal',    
    
    //This sets the priority within the context where the boxes should show
    'priority' => 'high',
    
    //Here we define all the fields we want in the meta box
    'fields' => array(
        array(
            'name' => 'Assertion - True / False',
            'desc' => 'true or false',
            'id' => 'assertion',
            'type' => 'text',
            'default' => ''
        ),
        array(
            'name' => 'Complicated - Override',
            'desc' => 'If you want to display Complicated, enter 1. Otherwise leave blank.',
            'id' => 'complicated',
            'type' => 'text',
            'default' => ''
        )
        array(
            'name' => 'URL - Source',
            'desc' => 'URL to the fact check source',
            'id' => 'source_url',
            'type' => 'text',
            'default' => ''
        )
        array(
            'name' => 'Organization - Source',
            'desc' => 'e.g. The Washington Post, Politifact',
            'id' => 'source_org',
            'type' => 'text',
            'default' => ''
        )
        array(
            'name' => 'Title - Source',
            'desc' => 'Title of the fact check linked to',
            'id' => 'post_title',
            'type' => 'text',
            'default' => ''
        )
        array(
            'name' => 'Party of speaker',
            'desc' => '(Optional) Democratic or Republican',
            'id' => 'party',
            'type' => 'text',
            'default' => ''
        )
        array(
            'name' => 'Name of speaker',
            'desc' => '(Optional) Who originally made the claim',
            'id' => 'speaker',
            'type' => 'text',
            'default' => ''
        )
    )
);
 
    /**
    *The following line of code will run the functions that will actually create the Meta Box(es).
    */

    add_action('admin_menu', 'plib_add_box'); 

    /**
    * Adding the Meta Box
    */

    //Add meta boxes to post types
    function plib_add_box() {
    global $meta_box;
    
    foreach($meta_box as $post_type => $value) {
        add_meta_box($value['id'], $value['title'], 'plib_format_box', $post_type, $value['context'], $value['priority']);
    }
}

    /**
    * Formatting the Meta Box
    *This function applies the HTML formatting within the Meta Box for each input field.
    */

    //Format meta boxes
function plib_format_box() {
  global $meta_box, $post;

  // Use nonce for verification
  echo '<input type="hidden" name="plib_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

  echo '<table class="form-table">';

  foreach ($meta_box[$post->post_type]['fields'] as $field) {
      // get current post meta data
      $meta = get_post_meta($post->ID, $field['id'], true);

      echo '<tr>'.
              '<th style="width:20%"><label for="'. $field['id'] .'">'. $field['name']. '</label></th>'.
              '<td>';
      switch ($field['type']) {
          case 'text':
              echo '<input type="text" name="'. $field['id']. '" id="'. $field['id'] .'" value="'. ($meta ? $meta : $field['default']) . '" size="30" style="width:97%" />'. '<br />'. $field['desc'];
              break;
          case 'textarea':
              echo '<textarea name="'. $field['id']. '" id="'. $field['id']. '" cols="60" rows="4" style="width:97%">'. ($meta ? $meta : $field['default']) . '</textarea>'. '<br />'. $field['desc'];
              break;
          case 'select':
              echo '<select name="'. $field['id'] . '" id="'. $field['id'] . '">';
              foreach ($field['options'] as $option) {
                  echo '<option '. ( $meta == $option ? ' selected="selected"' : '' ) . '>'. $option . '</option>';
              }
              echo '</select>';
              break;
          case 'radio':
              foreach ($field['options'] as $option) {
                  echo '<input type="radio" name="' . $field['id'] . '" value="' . $option['value'] . '"' . ( $meta == $option['value'] ? ' checked="checked"' : '' ) . ' />' . $option['name'];
              }
              break;
          case 'checkbox':
              echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '"' . ( $meta ? ' checked="checked"' : '' ) . ' />';
              break;
      }
      echo     '<td>'.'</tr>';
  }

  echo '</table>';

}

    /**
    * Saving data from the Meta Box
    * Finally we need to tell WordPress that the fields exist and how to save them with the Post.
    */

    // Save data from meta box
    function plib_save_data($post_id) {
    global $meta_box,  $post;
    
    //Verify nonce
    if (!wp_verify_nonce($_POST['plib_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    //Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    //Check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    
    foreach ($meta_box[$post->post_type]['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}

add_action('save_post', 'plib_save_data');


    /**
    * END sara's messing around ..............................................................................
    */

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