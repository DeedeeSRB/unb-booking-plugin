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

        //$data = get_post_meta( $post_id , $column , true );
        $rooms = get_post_meta( $post_id , 'booking_rooms' , true );
        
        if ( strcmp( $column, 'booking_rooms' ) == 0 ) {
            foreach ( $rooms as $id => $room ) {
                $link = get_permalink( $id );
                $name = $room['name'];
                $quantity = $room['quantity'];
                echo '<a href=' . $link . '>' . $name . '</a>' . ' <b class="fw-bold">x</b>' . $quantity . '</br></br></br>';
            }
        }
        else if ( strcmp( $column, 'booking_check_in_out' ) == 0 ) {
            foreach ( $rooms as $id => $room ) {
                $check_in_date = new \DateTime( $room['check_in'] );
                $check_out_date = new \DateTime( $room['check_out'] );

                $check_in_formated = date_format( $check_in_date, "d M Y" );
                $check_out_formated = date_format( $check_out_date, "d M Y" );

                echo $check_in_formated . '</br>' . $check_out_formated . '</br></br>';
            }
        }
        else if ( strcmp( $column, 'booking_nights' ) == 0 ) {
            foreach ( $rooms as $id => $room ) {
                $check_in_date = new \DateTime( $room['check_in'] );
                $check_out_date = new \DateTime( $room['check_out'] );

                $booking_days = $check_in_date->diff($check_out_date)->format('%a');

                echo $booking_days . ' Night(s)' . '</br></br></br>';
            }
        }
        else if ( strcmp( $column, 'booking_price' ) == 0 ) {
            $price = get_post_meta( $post_id , $column , true );
            $currencyOptions = get_option( 'currency_options' );
            $pos = isset( $currencyOptions['pos'] ) ? $currencyOptions['pos'] : 'Right'; 
            $symbol = isset( $currencyOptions['symbol'] ) ? $currencyOptions['symbol'] : '$'; 
            $price = strcmp( $pos, 'Left' ) == 0 ? $symbol . ' ' . $price :  $price . ' ' . $symbol;
            echo $price;
        }
        else if ( strcmp( $column, 'booking_payment_paid' ) == 0 ) {
            $paid = get_post_meta( $post_id , $column , true );
            if ( $paid ) {
                echo '<div style="background-color: LightGreen; width: fit-content; padding: 0px 7px 2px 7px; border-radius: 5px; color: White;">Paid</div>';
            }
            else {
                echo '<div style="background-color: Tomato; width: fit-content; padding: 0px 7px 2px 7px; border-radius: 5px; color: White;">Not Paid</div>';
            }
        }
        else if ( strcmp( $column, 'booking_status' ) == 0 ) {
            $wc_order_id = get_post_meta( $post_id , 'wc_order_id' , true );
            $order = new \WC_Order( $wc_order_id );
            $status = ucwords( str_replace( '-', ' ', $order->get_status() ) );
            if ( strcmp( $status, 'Cancelled' ) == 0 || strcmp( $status, 'Failed' ) == 0 || strcmp( $status, 'Refunded' ) == 0 ) {
                echo '<div style="background-color: Tomato; width: fit-content; padding: 0px 7px 2px 7px; border-radius: 5px; color: White;">' . $status  . '</div>';
            }
            else if ( strcmp( $status, 'Completed' ) == 0 ) {
                echo '<div style="background-color: LightGreen; width: fit-content; padding: 0px 7px 2px 7px; border-radius: 5px; color: Black;">' . $status  . '</div>';
            }
            else {
                echo '<div style="background-color: #FF9933; width: fit-content; padding: 0px 7px 2px 7px; border-radius: 5px; color: White;">' . $status  . '</div>';
            }
        }
        else {
            $data = get_post_meta( $post_id , $column , true );
            echo $data;
        }
            
        

    }


}
