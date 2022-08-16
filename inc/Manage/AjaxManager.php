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

    public function unb_book_room() {

        if ( !wp_verify_nonce( $_POST['nonce'], "unb_book_room_nonce" ) ) {
            $return['success'] = 2;
            $return['message'] = 'Nonce Error';
            exit( json_encode( $return ) );
        }  

        $room_id = $_POST['room_id'];

        if ( !get_post_status ( $room_id ) ) {
            $return['success'] = 2;
            $return['message'] = 'This room doesn\'t seem to exist';
            exit( json_encode( $return ) );
        }

        // TODO: Check if the check in/out dates are real dates and that they are available.
        
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];

        $check_in_date = new \DateTime( $check_in );
        $check_out_date = new \DateTime( $check_out );
        
        $min_booking_days = get_post_meta( $room_id, 'room_min_booking_days', true );
        $booking_days = $check_in_date->diff($check_out_date)->format('%a');

        if ( $booking_days < $min_booking_days) {
            $return['success'] = 2;
            $return['message'] = 'You can\'t book this room for less than ' . $min_booking_days . ' days';
            exit( json_encode( $return ) );
        }

        $num_visitors = $_POST['num_visitors'];

        $max_num_visitors = get_post_meta( $room_id, 'room_max_num_vis', true );
        if ( $max_num_visitors < $num_visitors) {
            $return['success'] = 2;
            $return['message'] = 'You can\'t book this room for more than ' . $max_num_visitors . ' visitors';
            exit( json_encode( $return ) );
        }

        $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $room_id ) );
        $quantity = 1;
        $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
        $product_status = get_post_status( $product_id );

        $order_info = array(
            'Check in' => $check_in,
            'Check out' => $check_out,
            'Number of visitors' => $num_visitors
        );

        if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, 0, array(), $order_info ) && 'publish' === $product_status ) {

            do_action( 'woocommerce_ajax_added_to_cart', $product_id );

            if ( 'yes' === get_option('woocommerce_cart_redirect_after_add' ) ) {
                wc_add_to_cart_message( array( $product_id => $quantity ), true );
            }
        } 
        else {
            $return['success'] = 2;
            $return['message'] = "An error occured";
            exit( json_encode( $return ) );
        }

        $return['success'] = 1;
        $return['url'] = wc_get_cart_url();
        $return['message'] = "Product successfuly added to the cart!";
        exit( json_encode( $return ) );
    }

}