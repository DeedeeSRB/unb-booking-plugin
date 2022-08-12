<?php
/**
 * CustomRegisterCPT class.
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
 * Responsible to register the plugin's custom post types functions for specific custom post types.
 * 
 */
class CustomRegisterCPT 
{
 /**
	 * Register custom post types custom post types.
     * For this case it's the bookings cpt.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function register() {
        add_action( 'manage_booking_posts_custom_column' , array( $this, 'bookingDisplayColumns' ), 10, 2 );
    }

    /**
	 * Display the meta data of the booking custom post type in its columns.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public static function bookingDisplayColumns( $column, $post_id ) {

        $post_type = get_post_type( $post_id );

        $data = get_post_meta( $post_id , $column , true );
        if ( isset( $data ) && $data != '' ) {
            if ( strcmp( $column, 'booking_rooms' ) ) {
                foreach ( $data as $room ) {
                    $name = get_the_title( $room['id'] );
                    $quantity = $room['quantity'];
                    echo $name . ' x ' . $quantity;
                }
            }
            else {
                echo $data;
            }
            
        } 

    }


}
