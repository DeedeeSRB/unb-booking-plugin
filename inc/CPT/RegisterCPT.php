<?php
/**
 * RegisterCPT class.
 *
 * @category   Class
 * @package    UNBBookingPlugin
 * @subpackage WordPress
 * @author     Unbelievable Digital
 * @copyright  2022 Unbelievable Digital
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       https://unbelievable.digital/
 * @since      1.0.0
 * php version 7.3.9
 */

 /**
 * UNB Booking Plugin Registering Custom Post Type Class
 *
 * Responsible to register the plugin's custom post types for the admin panel.
 * 
 */
class RegisterCPT 
{
    /**
	 * Admin custom post types
	 *
	 * @since 1.0.0
	 * @var array Array to keep track of all custom post types
	 */
    public $customPostTypes = array();

    /**
	 * Meta boxes for the custom post types
	 *
	 * @since 1.0.0
	 * @var array Array to keep track of all custom posts meta data
	 */
    public $metaBoxes = array();

    /**
	 * Meta fields for the custom post types meta boxes
	 *
	 * @since 1.0.0
	 * @var array Array to keep track of all custom posts meta data
	 */
    public $metaFields = array();
    
    /**
	 * Call a function to register custom post types and their meta fields and boxes if they are not empty.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function register() {
        if ( ! empty($this->customPostTypes) ) {
            add_action('init', array( $this, 'registerCPTs' ) );
        }
        if ( ! empty($this->metaBoxes) ) {
            add_action( 'add_meta_boxes', array( $this, 'registerCPTMetaBoxes' ) );
        }
        if ( ! empty($this->metaFields) ) {
            add_action( 'save_post', array( $this, 'saveCustomPosts' ), 10, 2 );
            add_action( 'manage_posts_custom_column' , array( $this, 'customDisplayColumns' ), 10, 2 );
            add_filter( 'manage_posts_columns', array( $this, 'customColumnsList' ), 10, 2 );
            
        }
    }

    /**
	 * Register all custom post types for the admin panel.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function registerCPTs() {
        foreach ( $this->customPostTypes as $cpt ) {
		
            $supports = $cpt['supports'];

            $name = $cpt['name'];
            $singular_name = $cpt['singular_name'];
            $labels = array(
                'name'                  => _x( $name, 'Post type general name' ),
                'singular_name'         => _x( $singular_name, 'Post type singular name' ),
                'menu_name'             => _x( $name, 'Admin Menu text' ),
                'name_admin_bar'        => _x( $singular_name, 'Add New on Toolbar' ),
                'add_new'               => __( 'Add New' ),
                'add_new_item'          => __( 'Add New ' . strtolower($singular_name) ),
                'new_item'              => __( 'New ' . strtolower($singular_name) ),
                'edit_item'             => __( 'Edit ' . strtolower($singular_name) ),
                'view_item'             => __( 'View ' . strtolower($singular_name) ),
                'all_items'             => __( 'All ' . strtolower($name) ),
                'search_items'          => __( 'Search ' . strtolower($name) ),
                'parent_item_colon'     => __( 'Parent ' . strtolower($name) . ':' ),
                'not_found'             => __( 'No ' . strtolower($name) . ' found.' ),
                'not_found_in_trash'    => __( 'No ' . strtolower($name) . ' found in Trash.' ),
                'featured_image'        => _x( 'Recipe Featured Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'recipe' ),
                'set_featured_image'    => _x( 'Set featured image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'recipe' ),
                'remove_featured_image' => _x( 'Remove featured image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'recipe' ),
                'use_featured_image'    => _x( 'Use as featured image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'recipe' ),
                'archives'              => _x( $singular_name . ' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4' ),
                'insert_into_item'      => _x( 'Insert into ' . strtolower($singular_name), 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4' ),
                'uploaded_to_this_item' => _x( 'Uploaded to this ' . strtolower($singular_name), 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4' ),
                'filter_items_list'     => _x( 'Filter ' . strtolower($name) . ' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4' ),
                'items_list_navigation' => _x( $name . ' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4' ),
                'items_list'            => _x( $name . ' list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4' ),
            );

            $args = array(
                'supports' => $supports,
                'labels' => $labels 
            );
            $args = array_merge( $args, $cpt['args'] );
            
            register_post_type( strtolower( $singular_name ), $args);
            register_taxonomy( 'categories', strtolower( $singular_name ) );
        }
    }

    /**
	 * Register all custom post types meta boxex.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function registerCPTMetaBoxes() {
        foreach ( $this->metaBoxes as $metaBox) {
            add_meta_box( 
                $metaBox['id'],
                $metaBox['title'],
                $metaBox['callback'],
                $metaBox['screen'],
                ( isset( $metaBox['context'] ) ? $metaBox['context'] : 'advanced' ),
                ( isset( $metaBox['priority'] ) ? $metaBox['priority'] : 'default' ),
                ( isset( $metaBox['callback_args'] ) ? $metaBox['callback_args'] : null )
            );
        }
    }

    /**
	 * Save all custom post types meta fields.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function saveCustomPosts( $post_id, $post ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        if ( !wp_verify_nonce( $_POST[$post->post_type . '_box_nonce'], UNB_PLUGIN_NAME ) ) return;

        if ( 'page' == $_POST['post_type'] ) if ( !current_user_can( 'edit_page', $post_id ) ) return;
        else if ( !current_user_can( 'edit_post', $post_id ) ) return;
        
        foreach( $this->metaFields[$post->post_type] as $metaField ) {
            $fieldToUpdate = $_POST[$metaField['id']];
            update_post_meta( $post_id, $metaField['id'], $fieldToUpdate );
        }
    }

    /**
	 * Filter the columns of all custom post types metas.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function customColumnsList( $columns, $post_type ) {
        
        $columnData = $this->metaColumns[$post_type];
        foreach( $columnData['unset'] as $unset ) {
            unset( $columns[$unset] );
        }

        foreach( $columnData['columnNames'] as $id => $name ) {
            $columns[$id] = __( $name );
        }

        return $columns;
    }

    /**
	 * Display the columns data of all custom post types metas.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function customDisplayColumns( $column, $post_id ) {
        echo get_post_meta( $post_id , $column , true );
    }

    /**
	 * Set custom post types array
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function setCPTs($cpt) {
        $this->customPostTypes = array_merge( $this->customPostTypes, $cpt );
		return $this;
    }

    /**
	 * Set custom post types meta fields and boxes array
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function setCPTMetas($metaBoxes) {

        // Setting the meta boxes
        $this->metaBoxes = array_merge( $this->metaBoxes, $metaBoxes );

        foreach ( $metaBoxes as $metaBox ) {
            // Setting the meta fields
            $this->metaFields[$metaBox['screen']] = $metaBox['callback_args']['fields'];

            // Setting columns for the CPT for the new meta data
            foreach ( $metaBox['callback_args']['fields'] as $field ) {
                $this->metaColumns[$metaBox['screen']]['columnNames'][$field['id']] = isset($field['columnName']) ? $field['columnName'] : $field['label'];
            } 

            // Unsetting any default columns
            $this->metaColumns[$metaBox['screen']]['unset'] = $metaBox['callback_args']['unsetColumns'];
        }

		return $this;
    }
}
