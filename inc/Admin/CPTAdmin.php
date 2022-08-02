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
	 * Call function to set custom post types and their meta fields and boxes in the RegisterCPT.php file and then register them.
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
                    'public' => true,
                    'has_archive' => true,
                    'hierarchical' => true,
                    //'taxonomies' => array( 'post_tags' ),
                )
            )
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
        require UNB_PLUGIN_PATH . 'inc/Callbacks/CPTMetaCallbacks.php';

        $roomMetaFields = array(
            array(
                'id' => 'room_price',
                'label' => 'Price',
                'type' => 'text',
                'place_holder' => 'Enter a price',
            ),
            array(
                'id' => 'room_max_num_vis',
                'label' => 'Maximum number of visitors',
                'type' => 'text',
                'place_holder' => '',
            ),
            array(
                'id' => 'room_min_booking_days',
                'label' => 'Minumum booking days',
                'type' => 'text',
                'place_holder' => '',
            ),
            array(
                'id' => 'room_amenities',
                'label' => 'Amenties',
                'type' => 'textarea',
                'place_holder' => '',
            ),
        ); 

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
                    'fields' => $roomMetaFields
                )
            ),
        );

        $this->registerCPT->setCPTMetas($metaBoxes);
    }
}