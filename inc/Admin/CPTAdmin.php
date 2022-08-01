<?php
/**
 * CPTAdmin class.
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
 * UNB Booking Plugin Custom Post Type Admin Class
 *
 * Responsible to creating and setting custom post types data in the RegisterCPT.php file and calling to register them for the admin panel.
 * 
 */
class CPTAdmin 
{
    /**
	 * RegisterCPT variable
	 *
	 * @since 1.0.0
	 * @var RegisterCPT RegisterCPT variable to set the custom post types and registering them
	 */
	public $registerCPT;

    /**
	 * Call function to set custom post types and their meta boxes in the RegisterCPT.php file and then register them.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register() 
	{
        require UNB_PLUGIN_PATH . 'inc/CPT/RegisterCPT.php';
		
		$this->registerCPT = new RegisterCPT();

        $this->setCPTs();
        $this->setCPTMetas();

		$this->registerCPT->register();
    }

    /**
	 * Set custom post types in the RegisterCPT.php file.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function setCPTs() {
        $cpts = array(
            array(
                'supports' => array(
                ),
                'labels' => array(
                    'name'                  => _x( 'Rooms', 'Post type general name' ),
                    'singular_name'         => _x( 'Room', 'Post type singular name' ),
                    'menu_name'             => _x( 'Rooms', 'Admin Menu text' ),
                    'name_admin_bar'        => _x( 'Room', 'Add New on Toolbar' ),
                    'add_new'               => __( 'Add New' ),
                    'add_new_item'          => __( 'Add New room' ),
                    'new_item'              => __( 'New room' ),
                    'edit_item'             => __( 'Edit room' ),
                    'view_item'             => __( 'View room' ),
                    'all_items'             => __( 'All rooms' ),
                    'search_items'          => __( 'Search rooms' ),
                    'parent_item_colon'     => __( 'Parent rooms:' ),
                    'not_found'             => __( 'No rooms found.' ),
                    'not_found_in_trash'    => __( 'No rooms found in Trash.' ),
                    'archives'              => _x( 'Room archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4' ),
                    'insert_into_item'      => _x( 'Insert into room', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4' ),
                    'uploaded_to_this_item' => _x( 'Uploaded to this room', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4' ),
                    'filter_items_list'     => _x( 'Filter rooms list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4' ),
                    'items_list_navigation' => _x( 'Rooms list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4' ),
                    'items_list'            => _x( 'Rooms list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4' ),
                ),
                'args' => array(
                    'public' => true,
                    'has_archive' => true,
                )
            )
        );

        $this->registerCPT->setCPTs($cpts);
    }

    /**
	 * Set custom post types meta boxes in the RegisterCPT.php file.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function setCPTMetas() {
        require UNB_PLUGIN_PATH . 'inc/Callbacks/CPTMetaCallbacks.php';
        $metas = array(
            array(
                'id' => 'room_price_box',
                'title' => __( 'Room Price' ),
                'callback' => array( 'CPTMetaCallbacks', 'roomPrice' ),
                'screen' => 'room',
                'context' => 'side',
                'priority' => 'high',
            )
        );

        $this->registerCPT->setCPTMetas($metas);
    }
}