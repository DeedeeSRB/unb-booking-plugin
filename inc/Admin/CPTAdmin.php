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
                    //'hierarchical' => true, // IF ITS TRUE THE TABLE WON'T BE FILLED 
                    //'taxonomies' => array( 'categories' ),
                )
            ),
            array(
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'page-attributes',
                ),
                'name' => 'Bookings',
                'singular_name' => 'Booking',
                'args' => array(
                    'menu_icon' => 'dashicons-book',
                    'public' => true,
                    'has_archive' => true,
                )
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
         *  The fields array of any meta box will hold all the fields that should be displayed in that meta box.
         *  Not all fields have to be displayed in the meta box or columns.
         *  These fields can take the attributes 'id', 'label', 'type', 'place_holder', and 'columnName'.
         *      Required fields: 'id' and 'label'
         *      Constraints: 'id' should be unique for each field
         *      Default values: -
         *          'type' => 'text'
         *          'columnName' => label
         *          'place_holder' => ''
         */
        $roomMetaFields = array(
            array(
                'id' => 'room_desc',
                'label' => 'Description',
                'type' => 'textarea',
            ),
            array(
                'id' => 'room_price',
                'label' => 'Price',
                'place_holder' => '(Default: ' . get_option( 'room_options' )['room_price'] . ')',
            ),
            array(
                'id' => 'room_max_num_vis',
                'label' => 'Maximum number of visitors',
                'columnName' => 'Max. No. of visitors',
                'place_holder' => '(Default: ' . get_option( 'room_options' )['room_max_num_vis'] . ')',
            ),
            array(
                'id' => 'room_min_booking_days',
                'label' => 'Minumum booking days',
                'columnName' => 'Min. booking days',
                'place_holder' => '(Default: ' . get_option( 'room_options' )['room_min_booking_days'] . ')',
            ),
            array(
                'id' => 'room_amenities',
                'label' => 'Amenties',
                'type' => 'textarea',
                'place_holder' => '(Default: ' . get_option( 'room_options' )['room_amenities'] . ')',
            ),
        ); 

        $bookingMetaFields = array(
            array(
                'id' => 'booking_rooms',
                'label' => 'Booked rooms',
            ),
            array(
                'id' => 'booking_check_in',
                'label' => 'Check in',
            ),
            array(
                'id' => 'booking_check_out',
                'label' => 'Check out',
            ),
            array(
                'id' => 'booking_price',
                'label' => 'Total Price',
            ),
            array(
                'id' => 'booking_user',
                'label' => 'Booked user full name',
            ),
            array(
                'id' => 'booking_email',
                'label' => 'Booked user email',
            ),
            array(
                'id' => 'booking_phone',
                'label' => 'Booked user phone number',
            ),
            array(
                'id' => 'wc_order_id',
                'label' => 'WC Order ID',
            ),
        ); 

        /**
         *  The metaBoxes array will hold all the meta boxes that should be displayed for a specific CPT.
         *  These metaBoxes can take the attributes 'id', 'title', 'callback', 'screen', 'context', 'priority', and 'callback_args'.
         *  The 'callback_args' attribute can take 'nonce', 'fields', and 'unsetColumns'.
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
                'title' => __( 'Booking details' ),
                'callback' => array( 'CPTMetaCallbacks', 'bookingBox' ),
                'screen' => 'booking',
                'context' => 'normal',
                'priority' => 'high',
                'callback_args' => array(
                    'nonce' =>  'booking_box_nonce',
                    'fields' => $bookingMetaFields,
                    'unsetColumns' => array( 'booking_email', 'booking_phone', 'wc_order_id' ),
                    'option_name' => 'booking_room_options'
                )
            ),
        );

        $this->registerCPT->setCPTMetas($metaBoxes);
    }
}