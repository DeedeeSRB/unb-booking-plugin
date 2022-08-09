<?php
/**
 * @package UNBBookingPlugin
 */

 /*
 Plugin Name: Unbelievable Digital Booking Plugin
 Description: First implimentation of Unbelievable Digital booking system
 Version: 1.0.0
 Author: Unbelievable Digital
 Author URI: https://unbelievable.digital/
 License: GPLv2 or later 
 Text Domain: unb-booking-plugin
 */

namespace UnbBooking;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

define( 'UNB_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'UNB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'UNB_PLUGIN_NAME', plugin_basename( __FILE__ ));
define( 'UNB_BOOKING', __FILE__ );

/**
 * Include the UNB Booking Activate class and register the activation hook.
 */
require UNB_PLUGIN_PATH . 'inc/Base/Activate.php';
register_activation_hook( __FILE__, array( 'UnbBooking\Base\Activate', 'activate' ) );

/**
 * Include the UNB Booking System class.
 */
require UNB_PLUGIN_PATH . 'class-unb-booking-system.php';

/**
 * Include the UNB Booking Initializer class.
 */
require UNB_PLUGIN_PATH . 'inc/init.php';

/**
 * Initialize the plugin
 */
$init = new Init();
$init->register_services();


