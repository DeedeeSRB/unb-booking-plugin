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
	 * Call function to set custom post types in the SettingsApi.php file and then register them.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register() 
	{
        require UNB_PLUGIN_PATH . 'inc/CPT/RegisterCPT.php';
		
		$this->registerCPT = new RegisterCPT();

        $this->setCPTs();

		$this->registerCPT->register();
    }

    /**
	 * Set custom post types in the SettingsApi.php file.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function setCPTs() {
        $cpts = array(
            array(
                'supports' => array(
                    'title', // post title
                    'editor', // post content
                    'custom-fields', // custom fields
                ),
                'labels' => array(
                    'name'                  => _x( 'Booking Orders', 'Post type general name' ),
                    'singular_name'         => _x( 'Booking Order', 'Post type singular name' ),
                    'menu_name'             => _x( 'Booking Orders', 'Admin Menu text' ),
                    'name_admin_bar'        => _x( 'Booking Order', 'Add New on Toolbar' ),
                    'add_new'               => __( 'Add New' ),
                    'add_new_item'          => __( 'Add New Booking Order' ),
                    'new_item'              => __( 'New Booking Order' ),
                    'edit_item'             => __( 'Edit Booking Order' ),
                    'view_item'             => __( 'View Booking Order' ),
                    'all_items'             => __( 'All Booking Orders' ),
                    'search_items'          => __( 'Search Booking Orders' ),
                    'parent_item_colon'     => __( 'Parent Booking Orders:' ),
                    'not_found'             => __( 'No booking orders found.' ),
                    'not_found_in_trash'    => __( 'No booking orders found in Trash.' ),
                    'archives'              => _x( 'Booking Order archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4' ),
                    'insert_into_item'      => _x( 'Insert into booking order', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4' ),
                    'uploaded_to_this_item' => _x( 'Uploaded to this booking order', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4' ),
                    'filter_items_list'     => _x( 'Filter booking orders list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4' ),
                    'items_list_navigation' => _x( 'Booking Orders list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4' ),
                    'items_list'            => _x( 'Booking Orders list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4' ),
                ),
                'args' => array(
                    'public' => true,
                    'has_archive' => true,
                )
            )
        );

        $this->registerCPT->setCPTs($cpts);
    }
}