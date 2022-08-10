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

        $product_id = sanitize_text_field( $_POST['room_id']) ;

        //global $woocommerce;
        //$woocommerce->cart->add_to_cart( $product_id );

        //wp_safe_redirect( wc_get_checkout_url() );

        // $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $id ) );
        global $woocommerce;
        WC()->cart->add_to_cart();
        if ( $woocommerce->cart->add_to_cart( $product_id ) ) {

            do_action( 'woocommerce_ajax_added_to_cart', $product_id );

            if ('yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
                wc_add_to_cart_message( array( $product_id => $quantity ), true );
            }

            //wp_safe_redirect( wc_get_checkout_url() );
            //WC_AJAX :: get_refreshed_fragments();
        } else {
            $return['success'] = 2;
            $return['message'] = "An error occured";
            $return['product_url'] = apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id );
            exit( json_encode( $return ) );
        }

        $return['success'] = 1;
        $return['message'] = "Done";
        exit( json_encode( $return ) );
    }

}