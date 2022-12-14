<?php
/**
 * AjaxManager class.
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

namespace UnbBooking\Manage;

/**
 * AjaxManager is responsible to handle all ajax actions given by the front it.
 */
class AjaxManager
{
    /**
	 * Register the ajax actions and their callback methods
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register() 
	{
        add_action("wp_ajax_unb_book_room", array( $this , 'unb_book_room' ) );
        add_action("wp_ajax_nopriv_unb_book_room", array( $this , 'unb_book_room' ) );
	}

    /**
	 * This function is called when the "Book" button is pressed in the "book-room-button-widget".
     * Parameters such as nonce, room_id, check_in, check_out, and num_visitors should be past in the _POST.
	 * Whether the process is successful or not, we return a json encoded array with the values success and message.
     * 
     * If success is 1, that means it has finished the process successfuly.
     * If success is 2, that means it couldn't complete the process.
     * 
     * In the message we store why it process couldn't finish or notify that the process was successful.
     * 
     * The returned result will be handled in the javascript file.
     * 
	 * @since 1.0.0
	 * @access public
	 */
    public function unb_book_room() {

        // First we verify if the Nonce is correct.
        if ( !wp_verify_nonce( $_POST['nonce'], "unb_book_room_nonce" ) ) {
            $return['success'] = 2;
            $return['message'] = 'Nonce Error';
            exit( json_encode( $return ) );
        }  

        // We fetch the room id from the post
        $room_id = $_POST['room_id'];

        // If the post doesn't exist then we don't continue
        if ( !get_post_status ( $room_id ) ) {
            $return['success'] = 2;
            $return['message'] = 'This room doesn\'t seem to exist';
            exit( json_encode( $return ) );
        }
        
        // We fetch the check in and check out dates
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];

        // We convert them into DateTime variables so we can compare them easily
        $check_in_date = new \DateTime( $check_in );
        $check_out_date = new \DateTime( $check_out );
        
        // We fetch the minimum booking days for the room from the post meta
        $min_booking_days = get_post_meta( $room_id, 'room_min_booking_days', true );
        // We calculate the days that the user wants to book this room for
        $booking_days = $check_in_date->diff( $check_out_date )->format('%a');

        // If the user's booking days is less than this room's minimum booking days, we won't continue the process and notify the user of why it can't continue.
        if ( $booking_days < $min_booking_days) {
            $return['success'] = 2;
            $return['message'] = 'You can\'t book this room for less than ' . $min_booking_days . ' days';
            exit( json_encode( $return ) );
        }

        // We call a function to check if the room is available for the dates the user have chosen
        $availability = AjaxManager::checkAvailability( $check_in_date, $check_out_date, $room_id );
        if ( !$availability ) {
            $return['success'] = 2;
            $return['message'] = 'This room isn\'t available for the dates you have chosen';
            exit( json_encode( $return ) );
        }

        // We fetch the number of visitors
        $num_visitors = $_POST['num_visitors'];

        // fetch the maximum number of visitors for the room from the post meta
        $max_num_visitors = get_post_meta( $room_id, 'room_max_num_vis', true );

        // If the number of visitors the user choose is larger than the maximum number of visitors for the room, 
        // we won't continue the process and notify the user of why it can't continue.
        if ( $max_num_visitors < $num_visitors) {
            $return['success'] = 2;
            $return['message'] = 'You can\'t book this room for more than ' . $max_num_visitors . ' visitors';
            exit( json_encode( $return ) );
        }

        // Here we apply some filters to turn our room into a woocoommerce product and to function properly with our rooms
        // When we mention "product" down from here, we are refering to the room we are booking
        $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $room_id ) );
        $quantity = 1;

        // Here we use a woocommerce filter to check if the product would be added to the cart successfuly 
        $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

        // We get the post(room) status to check if it's published
        $product_status = get_post_status( $product_id );

        // Here we store the product meta data for the order
        $order_info = array(
            'Check in' => $check_in,
            'Check out' => $check_out,
            'Night(s)' => $booking_days,
            'Number of visitors' => $num_visitors,
        );

        // We do a similar validation process that woocommerce does when adding a product to the cart
        if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, 0, array(), $order_info ) && 'publish' === $product_status ) {

            // If it everything before is true, we do more woocommerce actions to add the product to the cart
            do_action( 'woocommerce_ajax_added_to_cart', $product_id );

            if ( 'yes' === get_option('woocommerce_cart_redirect_after_add' ) ) {
                wc_add_to_cart_message( array( $product_id => $quantity ), true );
            }
        } 
        else {
            // If something fails we return an error message
            $return['success'] = 2;
            $return['message'] = "An error occured";
            exit( json_encode( $return ) );
        }

        // Finally, if we get here that means the product(room) has been added successfuly to the cart
        // We also pass the url to the cart so we can automaticlly take the user to the cart's page
        $return['success'] = 1;
        $return['url'] = wc_get_cart_url();
        $return['message'] = "Product successfuly added to the cart!";
        exit( json_encode( $return ) );
    }

    /**
	 * Utility function to check if the dates chosen by the user are available for the chosen room.
     * The parameters $check_in_date and $check_out_date are provided.
     * 
     * If available return true, otherwise return false.
     * 
	 * @since 1.0.0
	 * @access public
     * @return boolean
	 */
    public static function checkAvailability( $check_in_date, $check_out_date, $room_id ) {
        // Setup the arguments to get all the bookings we have
        $queryArgs = array(
            'post_type' => 'booking',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );

        // Store the bookings in this variable
        $bookings = get_posts( $queryArgs );

        // Go through all the bookings to check the dates
        foreach ( $bookings as $booking ) {

            // Rake a look at the rooms in that booking (1 booking can have more than 1 room)
            $bk_rooms = get_post_meta( $booking->ID, 'booking_rooms', true );
            foreach ( $bk_rooms as $bk_room ) {

                // If the chosen room isn't the same type as the previously booked room then we shouldn't check the availablity since its a different room.
                if ( $bk_room['id'] != $room_id ) continue;

                // Rake the check in and out dates from that booked room
                $rm_check_in = $bk_room['check_in'];
                $rm_check_out = $bk_room['check_out'];

                // Convert them in to DateTime so we can comapre them
                $rm_check_in_date = new \DateTime( $rm_check_in );
                $rm_check_out_date = new \DateTime( $rm_check_out );

                // Validation algorithm to check if the dates are available
                // 1) If the chosen check in date is between or one the previously booked room check in and out dates, then the room won't be available
                if ( $check_in_date >= $rm_check_in_date && $check_in_date <= $rm_check_out_date ) {
                    return false;
                }
                // 2) If the chosen check in date is before the previously booked room check in date
                else if ( $check_in_date < $rm_check_in_date ) {
                    // 3) If the chosen check out date is between or one the previously booked room check in and out dates, then the room won't be available
                    if ( $check_out_date >= $rm_check_in_date && $check_out_date <= $rm_check_out_date ) {
                        return false;
                    }
                    // 4) If the chosen check out date is after the previously booked room check out date, then the room won't be available
                    else if ( $check_out_date > $rm_check_out_date ) {
                        return false;
                    }
                }
            }
        }

        // Return true if available
        return true;
    }

}