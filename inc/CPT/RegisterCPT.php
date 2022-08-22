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

namespace UnbBooking\CPTs;

 /**
 * UNB Booking Plugin Registering Custom Post Type Class
 *
 * Responsible to register the plugin's custom post types dynamicly for the admin panel.
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
    public static $customPostTypes = array();

    /**
	 * Meta boxes for the custom post types
	 *
	 * @since 1.0.0
	 * @var array Array to keep track of all custom posts meta data
	 */
    public static $metaBoxes = array();

    /**
	 * Meta fields for the custom post types meta boxes
	 *
	 * @since 1.0.0
	 * @var array Array to keep track of all custom posts meta data
	 */
    public static $metaFields = array();

    /**
	 * Table columns for the custom post types meta data
	 *
	 * @since 1.0.0
	 * @var array Array to keep track of all custom posts meta data columns
	 */
    public static $metaColumns = array();
    
    /**
	 * Call a function to register custom post types and their meta fields and boxes if they are not empty.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function register() {
        if ( !empty( RegisterCPT::$customPostTypes ) ) {
            add_action('init', array( $this, 'registerCPTs' ) );
        }
        if ( !empty( RegisterCPT::$metaBoxes ) ) {
            add_action( 'add_meta_boxes', array( $this, 'registerCPTMetaBoxes' ) );
        }
        if ( !empty( RegisterCPT::$metaFields ) ) {
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
        foreach ( RegisterCPT::$customPostTypes as $cpt ) {
		
            $supports = $cpt['supports'];

            $name = $cpt['name'];
            $singular_name = $cpt['singular_name'];
            $labels = array(
                'name'                  => _x( $name, 'Post type general name' ),
                'singular_name'         => _x( $singular_name, 'Post type singular name' ),
                'menu_name'             => _x( $name, 'Admin Menu text' ),
                'name_admin_bar'        => _x( $singular_name, 'Add New on Toolbar' ),
                'add_new'               => __( 'Add New' ),
                'add_new_item'          => __( 'Add New ' . strtolower( $singular_name ) ),
                'new_item'              => __( 'New ' . strtolower( $singular_name ) ),
                'edit_item'             => __( 'Edit ' . strtolower( $singular_name ) ),
                'view_item'             => __( 'View ' . strtolower( $singular_name ) ),
                'all_items'             => __( 'All ' . strtolower( $name ) ),
                'search_items'          => __( 'Search ' . strtolower( $name ) ),
                'parent_item_colon'     => __( 'Parent ' . strtolower( $name ) . ':' ),
                'not_found'             => __( 'No ' . strtolower( $name ) . ' found.' ),
                'not_found_in_trash'    => __( 'No ' . strtolower( $name ) . ' found in Trash.' ),
                'featured_image'        => _x( 'Recipe Featured Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'recipe' ),
                'set_featured_image'    => _x( 'Set featured image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'recipe' ),
                'remove_featured_image' => _x( 'Remove featured image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'recipe' ),
                'use_featured_image'    => _x( 'Use as featured image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'recipe' ),
                'archives'              => _x( $singular_name . ' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4' ),
                'insert_into_item'      => _x( 'Insert into ' . strtolower( $singular_name ), 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4' ),
                'uploaded_to_this_item' => _x( 'Uploaded to this ' . strtolower( $singular_name ), 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4' ),
                'filter_items_list'     => _x( 'Filter ' . strtolower( $name ) . ' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4' ),
                'items_list_navigation' => _x( $name . ' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4' ),
                'items_list'            => _x( $name . ' list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4' ),
            );

            $args = array(
                'supports' => $supports,
                'labels' => $labels 
            );
            $args = array_merge( $args, $cpt['args'] );
            
            register_post_type( strtolower( $singular_name ), $args);
        }
    }

    /**
	 * Register all custom post types meta boxex.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function registerCPTMetaBoxes() {
        foreach ( RegisterCPT::$metaBoxes as $metaBox) {
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
        
        $post_type = get_post_type( $post_id );

        // If the given post type isn't part of our plugin then this function won't affect it.
        if ( !array_key_exists( $post_type, RegisterCPT::$metaColumns ) ) return;

        // If the post type has a custom function to display the columns then this function won't proceed.
        if ( isset( RegisterCPT::$metaColumns[$post_type]['custom_display'] ) && RegisterCPT::$metaColumns[$post_type]['custom_display'] ) return;

        // If it tries to auto save, we won't actually save the data.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // If no post data is provided we won't continue.
        if ( empty( $_POST ) ) return;
        
        // We check if the post type box nonce exsist.
        if ( !isset( $_POST[$post->post_type . '_box_nonce'] ) ) return;

        // We verify the nonce.
        if ( !wp_verify_nonce( $_POST[$post->post_type . '_box_nonce'], UNB_PLUGIN_NAME ) ) return;

        // If the user isn't authorized to edit this post then we won't continue.
        if ( 'page' == $_POST['post_type'] ) if ( !current_user_can( 'edit_page', $post_id ) ) return;
        else if ( !current_user_can( 'edit_post', $post_id ) ) return;
        
        // For all the custom fields in this post type, we get the values from the post and saving them.
        // If their value is empty we check if they have a default value.
        foreach( RegisterCPT::$metaFields[$post->post_type] as $metaField ) {
            $fieldToUpdate = $_POST[$metaField['id']];
            if ( ( !isset( $fieldToUpdate ) || $fieldToUpdate == '' ) && RegisterCPT::$metaColumns[$post_type]['option_name'] != '' ) {
                $option_name = RegisterCPT::$metaColumns[$post_type]['option_name'];
                $fieldToUpdate = get_option( $option_name )[$metaField['id']] ;
            }
            // Save or update the meta.
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
        
        // If the given post type isn't part of our plugin then this function won't affect it.
        if ( !array_key_exists( $post_type, RegisterCPT::$metaColumns ) ) return $columns;

        // Get all the column data from the post type
        $columnData = RegisterCPT::$metaColumns[$post_type];
        
        // Each column has its id and name. Use these to add more columns to display in the post's table.
        foreach( $columnData['columnNames'] as $id => $name ) {
            $columns[$id] = __( $name );
        }

        // If there were any columns to unset, unset them here.
        foreach( $columnData['unset'] as $unset ) {
            unset( $columns[$unset] );
        }
        
        // Return the new array of columns
        return $columns;
    }

    /**
	 * Display the columns data of all custom post types metas.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function customDisplayColumns( $column, $post_id ) {

        // Get the post type to check if it is part of our plugin.
        $post_type = get_post_type( $post_id );

        // If the given post type isn't part of our plugin then this function won't affect it.
        if ( !array_key_exists( $post_type, RegisterCPT::$metaColumns ) ) return;

        // If the post type has a custom function to display the columns then this function won't proceed.
        if ( isset( RegisterCPT::$metaColumns[$post_type]['custom_display'] ) && RegisterCPT::$metaColumns[$post_type]['custom_display'] ) return;
        
        // Get the data for this column and display it if it is not empty.
        $data = get_post_meta( $post_id , $column , true );
        if ( isset( $data ) && $data != '' ) echo $data;
    }

    /**
	 * Set custom post types array
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function setCPTs($cpt) {
        RegisterCPT::$customPostTypes = array_merge( RegisterCPT::$customPostTypes, $cpt );
    }

    /**
	 * Set custom post types meta fields and boxes array
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function setCPTMetas($metaBoxes) {

        // Setting the meta boxes
        RegisterCPT::$metaBoxes = array_merge( RegisterCPT::$metaBoxes, $metaBoxes );

        // For each meta box there are many attriutes we want to save seperated with their custom post type ('screen')
        foreach ( $metaBoxes as $metaBox ) {

            /**
             * Setting the meta fields
             * 
             * 1) If the array is not initialized then we initialize it by giving an empty array.
             * Note: If we try to merge it with another array and it wasn't initialized we get an error.
             * 2) We get the fields from the callback_args.
             * 3) We merge the new fields to the old fields. 
             * 
             * */ 
            if ( !isset( RegisterCPT::$metaFields[$metaBox['screen']] ) ) RegisterCPT::$metaFields[$metaBox['screen']] = array();
            $fields =isset( $metaBox['callback_args']['fields'] )  ? $metaBox['callback_args']['fields'] : array();
            RegisterCPT::$metaFields[$metaBox['screen']] = array_merge( RegisterCPT::$metaFields[$metaBox['screen']], $fields );

            // Setting columns for the CPT for the new meta data if fields are set
            if ( isset( $metaBox['callback_args']['fields'] ) ) {
                foreach ( $metaBox['callback_args']['fields'] as $field ) {
                    RegisterCPT::$metaColumns[$metaBox['screen']]['columnNames'][$field['id']] = isset( $field['columnName']) ? $field['columnName'] : $field['label'];
                } 
            }

            /**
             * Unsetting any default columns and custom fields
             * 
             * 1) If the array is not initialized then we initialize it.
             * 2) We get the unset columns from the callback_args.
             * 3) We merge the new unset columns to the old unset columns. 
             * 
             * */ 
            if ( !isset( RegisterCPT::$metaColumns[$metaBox['screen']]['unset'] ) ) RegisterCPT::$metaColumns[$metaBox['screen']]['unset'] = array();
            $unset = isset( $metaBox['callback_args']['unsetColumns'] ) ? $metaBox['callback_args']['unsetColumns'] : array();
            RegisterCPT::$metaColumns[$metaBox['screen']]['unset'] = array_merge( RegisterCPT::$metaColumns[$metaBox['screen']]['unset'], $unset );

            // Setting option name to get default values for the cpt if it is set
            RegisterCPT::$metaColumns[$metaBox['screen']]['option_name'] = isset( $metaBox['callback_args']['option_name'] ) ? $metaBox['callback_args']['option_name'] : '';

            // Setting whether this cpt has custom display columns if it is set. So it doesn't run the default methods in this class.
            // (eg. booking has a custom dispaly function in CustomRegisterCPT.php)
            RegisterCPT::$metaColumns[$metaBox['screen']]['custom_display'] = isset( $metaBox['callback_args']['custom_display'] ) ? $metaBox['callback_args']['custom_display'] : '';
        }
    }
}
