<?php
/**
 * CustomRegisterCPT class
 * 
 * Responsible to register the plugin's custom post types' functions for specific custom post types
 *
 * @package    UNBBookingPlugin\Classes
 * @since      1.0.0
 */

namespace UnbBooking\CPTs;

/**
 * UNB Booking Plugin Registering Custom Post Type Class
 */
class CustomRegisterCPT 
{
 /**
	 * Register custom post types custom post types
     * For this case it's the bookings cpt
	 *
	 * @since 1.0.0
	 */
    public function register() {
        add_action( 'save_post_booking', array( $this, 'saveCustomBooking' ), 10, 2 );
        add_action( 'manage_booking_posts_custom_column' , array( $this, 'bookingDisplayColumns' ), 10, 2 );
    }

    /**
	 * Save booking payment paid field
	 *
	 * @since 1.0.0
     * @param int     $post_id The id of the post currently being saved/updated
     * @param WP_Post $post The post object currently being saved/updated
	 */
    public static function saveCustomBooking(  $post_id, $post ) {

        // If it tries to auto save, don't continue
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // If no post data is provided, don't continue
        if ( empty( $_POST ) ) return;
        
        // Check if the nonce exsist
        if ( !isset( $_POST['booking_box_nonce'] ) ) return;

        // Verify the nonce
        if ( !wp_verify_nonce( $_POST['booking_box_nonce'], UNB_PLUGIN_NAME ) ) return;

        // If the user isn't authorized to edit this post, don't continue
        if ( 'page' == $_POST['post_type'] ) if ( !current_user_can( 'edit_page', $post_id ) ) return;
        else if ( !current_user_can( 'edit_post', $post_id ) ) return;
        
        if ( isset( $_POST['booking_payment_paid_form'] ) ) {
            // If the value is string true then save it as boolean true
            $paid = strcmp( $_POST['booking_payment_paid_form'], 'true' ) == 0 ? true : false;
            // Save or update the field
            update_post_meta( $post_id, 'booking_payment_paid', $paid );
        }
        
    }


    /**
	 * Display the columns data of the booking type meta fields
	 *
	 * @since 1.0.0
     * @param string $column  The column name
     * @param int    $post_id The booking's id
	 */
    public static function bookingDisplayColumns( $column, $post_id ) {
        
        // Get the list of all booked rooms for this booking
        $rooms = get_post_meta( $post_id , 'booking_rooms' , true );
        
        // Rooms column, display the rooms' name, link, and quantity
        if ( strcmp( $column, 'booking_rooms' ) == 0 ) {
            foreach ( $rooms as $room ) {
                $link = get_permalink( $room['id'] );
                $name = $room['name'];
                $quantity = $room['quantity'];
                echo '<a href=' . $link . '>' . $name . '</a>' . ' <b class="fw-bold">x</b>' . $quantity . '</br></br></br>';
            }
        }
        // Rooms check in/out date column, display the rooms' dates
        else if ( strcmp( $column, 'booking_check_in_out' ) == 0 ) {
            foreach ( $rooms as $room ) {
                $check_in_date = new \DateTime( $room['check_in'] );
                $check_out_date = new \DateTime( $room['check_out'] );

                // Format the dates as "day(int) monuth(str) year(int)" "eg. 16 Aug 2022"
                $check_in_formated = date_format( $check_in_date, "d M Y" );
                $check_out_formated = date_format( $check_out_date, "d M Y" );

                echo $check_in_formated . '</br>' . $check_out_formated . '</br></br>';
            }
        }
        // Nights booked column, display how long each room is booked for
        else if ( strcmp( $column, 'booking_nights' ) == 0 ) {
            foreach ( $rooms as $room ) {
                $check_in_date = new \DateTime( $room['check_in'] );
                $check_out_date = new \DateTime( $room['check_out'] );

                $booking_days = $check_in_date->diff($check_out_date)->format('%a');

                echo $booking_days . ' Night(s)' . '</br></br></br>';
            }
        }
        // Price column, display the booking's price according to currency settings
        else if ( strcmp( $column, 'booking_price' ) == 0 ) {
            $price = get_post_meta( $post_id , $column , true );
            $currencyOptions = get_option( 'currency_options' );
            $pos = isset( $currencyOptions['pos'] ) ? $currencyOptions['pos'] : 'Right'; 
            $symbol = isset( $currencyOptions['symbol'] ) ? $currencyOptions['symbol'] : '$'; 
            $price = strcmp( $pos, 'Left' ) == 0 ? $symbol . ' ' . $price :  $price . ' ' . $symbol;
            echo $price;
        }
        // Payment paid column, display the booking's payment paid status
        else if ( strcmp( $column, 'booking_payment_paid' ) == 0 ) {
            $paid = get_post_meta( $post_id , $column , true );
            if ( $paid ) {
                echo '<div style="background-color: LightGreen; width: fit-content; padding: 0px 7px 2px 7px; border-radius: 5px; color: Black;">Paid</div>';
            }
            else {
                echo '<div style="background-color: Tomato; width: fit-content; padding: 0px 7px 2px 7px; border-radius: 5px; color: White;">Not Paid</div>';
            }
        }
        // Status column, display the booking's status which is the same the woocommerce status
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
        // Rooms mumber of visitors column, display the rooms' number of visitors
        else if ( strcmp( $column, 'booking_num_visitors' ) == 0 ) {
            foreach ( $rooms as $room ) {
                $name = $room['name'];
                $quantity = $room['quantity'];
                echo '<div>' . $room['num_visitors'] . ' Visitor(s)</div></br></br>';
            }
        }
        else {
            $data = get_post_meta( $post_id , $column , true );
            echo $data;
        }
    }


}
