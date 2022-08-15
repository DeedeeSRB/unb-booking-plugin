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
        add_action( 'save_post_booking', array( $this, 'saveCustomBooking' ), 10, 2 );
        add_action( 'manage_booking_posts_custom_column' , array( $this, 'bookingDisplayColumns' ), 10, 2 );
    }

    /**
	 * Save booking custom post types meta fields (check in and check out dates).
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public static function saveCustomBooking(  $post_id, $post ) {
        $post_type = get_post_type( $post_id );

        if ( !array_key_exists( $post_type, RegisterCPT::$metaColumns ) ) return;

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        if ( empty( $_POST ) ) return;
        
        if ( !isset( $_POST['booking_box_nonce'] ) ) return;

        if ( !wp_verify_nonce( $_POST['booking_box_nonce'], UNB_PLUGIN_NAME ) ) return;

        if ( 'page' == $_POST['post_type'] ) if ( !current_user_can( 'edit_page', $post_id ) ) return;
        else if ( !current_user_can( 'edit_post', $post_id ) ) return;
        
        if ( isset( $_POST['booking_check_in'] ) ) {
            foreach( $_POST['booking_check_in'] as $id => $date ) {
                $booking_check_in[$id] = $date;
            } 
            update_post_meta( $post_id, 'booking_check_in', $booking_check_in );
        }

        if ( isset( $_POST['booking_check_out'] ) ) {
            foreach( $_POST['booking_check_out'] as $id => $date ) {
                $booking_check_out[$id] = $date;
            } 
            update_post_meta( $post_id, 'booking_check_out', $booking_check_out );
        }
        
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
            if ( strcmp( $column, 'booking_rooms' ) == 0 ) {
                foreach ( $data as $id => $room ) {
                    $link = get_permalink( $id );
                    $name = $room['name'];
                    $quantity = $room['quantity'];
                    echo '<a href=' . $link . '>' . $name . '</a>' . ' <b class="fw-bold">x</b>' . $quantity . '</br>';
                }
            }
            else if ( strcmp( $column, 'booking_check_out' ) == 0 || strcmp( $column, 'booking_check_in' ) == 0 ) {
                foreach ( $data as $date ) {
                    echo $date . '</br>';
                }
            }
            else {
                echo $data;
            }
            
        } 

    }


}
