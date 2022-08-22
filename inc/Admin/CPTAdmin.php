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

namespace UnbBooking\Admin;

use UnbBooking\CPTs\RegisterCPT;

 /**
 * UNB Booking Plugin Custom Post Type Admin Class
 *
 * Responsible to creating and setting custom post types data in the RegisterCPT.php file and calling to register them for the admin panel.
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
	 * Call function to set custom post types and their meta fields and boxes in the RegisterCPT.php file and then register them.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register() 
	{
        require_once UNB_PLUGIN_PATH . 'inc/CPT/RegisterCPT.php';
	
        // Create new instance of our RegisterCPT class
		$this->registerCPT = new RegisterCPT();

        // Call the set CPTS and their matas functions
        $this->setCPTs();
        $this->setCPTMetas();

        // All the register function to register all the custom post types and their meta boxes
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
                    'title',
                    'editor', // context
                    //'author',
                    'thumbnail', // (featured image, current theme must also support post-thumbnails)
                    //'excerpt',
                    //'trackbacks',
                    //'custom-fields',
                    //'comments', // also will see comment count balloon on edit screen
                    //'revisions', // will store revisions 
                    'page-attributes', // menu order, hierarchical must be true to show Parent option
                    //'post-formats',
                ),
                'name' => 'Rooms',
                'singular_name' => 'Room',
                'args' => array(
                    'menu_icon' => 'dashicons-admin-multisite',
                    'public' => true,
                    'has_archive' => true,
                    'taxonomies' => array( 'tags', 'categories' ),
                )
            ),
            array(
                'supports' => array(
                    'title',
                ),
                'name' => 'Bookings',
                'singular_name' => 'Booking',
                'args' => array(
                    'menu_icon' => 'dashicons-book',
                    'public' => true,
                    'has_archive' => true,
                    'capabilities' => array(
                        'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
                    ),
                    'map_meta_cap' => true,
                ),
            ),
        );

        $this->registerCPT->setCPTs($cpts);
    }

    /**
	 * Set custom post types meta boxes and fields in the RegisterCPT.php file.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function setCPTMetas() {
        require_once UNB_PLUGIN_PATH . 'inc/Callbacks/CPTMetaCallbacks.php';

        /**
         *  This array will hold all the meta fields that should be included in the custom post type meta box.
         *  Important note: Not all fields have to be displayed in the meta box or columns. 
         * 
         *  These fields can take the attributes 'id', 'label', 'type', 'place_holder', 'columnName', and 'description.
         *  
         *  The 'label' will be shown in both the table and the field label.
         *  You can override the table name with 'columnName'.
         *  There are three types for now (text, textara, and select)
         * 
         *      Required fields: 'id' and 'label'
         *      Constraints: 'id' should be unique for each field
         *      Default values: -
         *          'type' => 'text'
         *          'columnName' => label
         *          'place_holder' => ''
         *          'description' => null
         */
        $roomMetaFields = array(
            array(
                'id' => 'room_desc',
                'label' => 'Description',
                'type' => 'textarea',
                'description' => 'A small description to the room',
            ),
            array(
                'id' => 'room_price',
                'label' => 'Price',
                'place_holder' => isset( get_option( 'room_options' )['room_price'] ) ? '(Default: ' . get_option( 'room_options' )['room_price'] . ')' : '', 
                'description' => 'What does this room cost for 1 day?',
            ),
            array(
                'id' => 'room_max_num_vis',
                'label' => 'Maximum number of visitors',
                'columnName' => 'Max. No. of visitors',
                'place_holder' => isset( get_option( 'room_options' )['room_max_num_vis'] ) ? '(Default: ' . get_option( 'room_options' )['room_max_num_vis'] . ')' : '',
                'description' => 'This room can hold a maximum number of X visitors',
            ),
            array(
                'id' => 'room_min_booking_days',
                'label' => 'Minumum booking days',
                'columnName' => 'Min. booking days',
                'place_holder' => isset( get_option( 'room_options' )['room_min_booking_days'] ) ? '(Default: ' . get_option( 'room_options' )['room_min_booking_days'] . ')' : '',
                'description' => 'What is the minumum booking days for this room?',
            ),
            array(
                'id' => 'room_amenities',
                'label' => 'Amenties',
                'type' => 'textarea',
                'place_holder' => isset( get_option( 'room_options' )['room_amenities'] ) ? '(Default: ' . get_option( 'room_options' )['room_amenities'] . ')' : '',
                'description' => 'What special about this room? Please seperate the amenities with a comma \',\'',
            ),
        ); 

        $bookingMetaFields = array(
            array(
                'id' => 'booking_status',
                'label' => 'Status',
            ),
            array(
                'id' => 'booking_rooms',
                'label' => 'Room(s)',
            ),
            array(
                'id' => 'booking_num_visitors',
                'label' => 'Visitor(s)',
            ),
            array(
                'id' => 'booking_check_in_out',
                'label' => 'Check in / out',
            ),
            array(
                'id' => 'booking_nights',
                'label' => 'Night(s)',
            ),
            array(
                'id' => 'booking_billing_details',
                'label' => 'Billing Details',
            ),
            array(
                'id' => 'booking_price',
                'label' => 'Total Price',
            ),
            array(
                'id' => 'booking_payment_method',
                'label' => 'Payment Method',
            ),
            array(
                'id' => 'booking_date',
                'label' => 'Booking Date',
            ),
            array(
                'id' => 'wc_order_id',
                'label' => 'WC Order ID',
            ),
        );
        
        $bookingPaymentMetaFields = array(
            array(
                'id' => 'booking_payment_paid',
                'label' => 'Payment',
            ),
        );

        /**
         *  This array will hold all the meta boxes that should be displayed for a specific CPT.
         * 
         *  These meta boxes can take the attributes 'id', 'title', 'callback', 'screen', 'context', 'priority', and 'callback_args'.
         *  The 'screen' attribute refers to the custom post type, in this first case it is 'room'.
         *  The 'context' attribute refers to where the meta box should be displayed in the cpt page (advanved, noraml, or side)
         * 
         *  The 'callback_args' attribute can take 'nonce', 'fields', 'unsetColumns', 'option_name', and 'custom_display'.
         *  The 'unsetColumns' attribute refers to the fields/column that you don't want to be displayed in the cpt table page. 
         *      Also you can unset builtin columns such as 'Title' and 'Date'
         *  The 'option_name' is only used when there is a default value for a field and you want to use it if the inputed value when saving the post is empty.
         *  The 'custom_display' means that this meta box has its own custom functions in the CustomRegisterCPT.php file.
         * 
         *      Required fields: 'id', 'title', 'callback', 'screen', and 'nonce'
         *      Constraints: 
         *          'id' should be unique for each metaBox.
         *          'title'
         *          'callback' callback methods should be createed in the CPTMetaCallbacks class or use the defualt method 'postBox'.
         *          'screen' this should be the same as the post type you want it to be for
         *          'nonce' this should be unique 
         *      Default values:
         *          'context' => 'advanced'
         *          'priority' => 'default'
         *          'fields' => null
         *          'unsetColumns' => null
         *          'option_name' => null
         */
        $metaBoxes = array(
            array(
                'id' => 'room_content_box',
                'title' => __( 'Room details' ),
                'callback' => array( 'CPTMetaCallbacks', 'postBox' ),
                'screen' => 'room',
                'context' => 'normal',
                'priority' => 'high',
                'callback_args' => array(
                    'nonce' =>  'room_box_nonce',
                    'fields' => $roomMetaFields,
                    'unsetColumns' => array('date'),
                    'option_name' => 'room_options'
                )
            ),
            array(
                'id' => 'booking_room_content_box',
                'title' => __( 'Booking status' ),
                'callback' => array( 'CPTMetaCallbacks', 'bookingBox' ),
                'screen' => 'booking',
                'context' => 'normal',
                'priority' => 'high',
                'callback_args' => array(
                    'nonce' =>  'booking_box_nonce',
                    'fields' => $bookingMetaFields,
                    'unsetColumns' => array( 'date', 'booking_email', 'booking_phone', 'booking_address', 'booking_num_people', 'booking_billing_details', 'wc_order_id' ),
                    'custom_display' => true,
                )
            ),
            array(
                'id' => 'booking_room_payment_content_box',
                'title' => __( 'Payment details' ),
                'callback' => array( 'CPTMetaCallbacks', 'bookingPaymentBox' ),
                'screen' => 'booking',
                'context' => 'side',
                'priority' => 'high',
                'callback_args' => array(
                    'nonce' =>  'booking_payment_box_nonce',
                    'fields' => $bookingPaymentMetaFields,
                    'custom_display' => true,
                )
            ),
        );

        $this->registerCPT->setCPTMetas($metaBoxes);
    }
}