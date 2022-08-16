<?php
/**
 * WCManager class.
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

//use UnbBooking\WCClasses;

/**
 * WCManager is responsible to handle the actions and hooks for the cpt woocommerce product
 */
class WCManager
{
    /**
	 * Register the actions and hooks for the cpt woocommerce product
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register() 
	{
        require UNB_PLUGIN_PATH . 'inc/WCClasses/WCClasses.php';
        add_action( 'init', 'register_wcclasses' );

        add_filter( 'woocommerce_data_stores', array( $this , 'room_woocommerce_data_stores' ) );
        add_filter( 'woocommerce_product_get_price', array( $this , 'room_woocommerce_product_get_price' ), 10, 2 );

        add_filter( 'woocommerce_product_class', array( $this , 'room_woo_product_class' ), 20, 3  );
        add_filter( 'woocommerce_get_order_item_classname', array( $this , 'room_woocommerce_get_order_item_classname' ) , 20, 3 );
        add_filter( 'woocommerce_product_type_query', array( $this , 'room_woo_product_type' ), 12, 2 );
        add_filter( 'woocommerce_checkout_create_order_line_item_object', array( $this , 'room_woocommerce_checkout_create_order_line_item_object' ), 20, 4 );

        add_filter( 'woocommerce_get_item_data', array( $this , 'display_cart_item_custom_meta_data' ), 10, 2 );
        add_action( 'woocommerce_checkout_create_order_line_item', array( $this , 'save_cart_item_custom_meta_as_order_item_meta' ), 10, 4 );
        
        add_action( 'woocommerce_checkout_order_processed', array( $this , 'save_booking_order' ),  10, 1  );
	}

    //Step 2. Use that class to extend WooCommerce Data Store class
    function room_woocommerce_data_stores( $stores ) {
        $stores['product'] = 'Room_Product_Data_Store';

        return $stores;
    }

    //Step 3. Return price dynamically from your CPT meta value
    function room_woocommerce_product_get_price( $price, $product ) {

        $product_id = $product->get_id();

        if ( get_post_type( $product_id ) == 'room' ) {
            $price = get_post_meta( $product_id, 'room_price', true );
            $price = isset( $price ) ? ( floatval( $price ) ) : 0;
        }

        return $price;
    }

    // Note: If you've done till this step, then the post will be added to cart and WooCommerce will recognize it as cart item up to checkout process. 
    // But WooCommerce won't recognize it as an order item after checkout is done. To make it available as an order item, we need to follow some more steps.

    // Step 1. Use your custom class to extend WooCommerce product class and add your CPT
    function room_woo_product_class( $class_name, $product_type, $product_id ) {
        
        if ( $product_type == 'room' ) {
            $class_name = 'Room_Woo_Product';
        }

        return $class_name;
    }

    //Step 2. Make sure your CPT is recognized as Product when WooCommerce tries to get product class
    function room_woocommerce_get_order_item_classname($class_name, $item_type, $id) {
        global $wpdb;
        $isRoom = $wpdb->get_var("SELECT meta_value FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = {$id} AND meta_key = 'Room'");

        if ('yes' === $isRoom) { // load the new class if the item is our custom post
            $class_name = 'Room_WC_Order_Item_Product';
        }

        return $class_name;
    }

    //Step 3. Make sure your CPT is queryable as WooCommerce Product
    function room_woo_product_type($false, $product_id) {
        if ($false === false) { // don't know why, but this is how woo does it
            global $post;

            if (is_object($post) && !empty($post)) { // post is set
                if ($post->post_type == 'room' && $post->ID == $product_id) 
                    return 'room';
                else {
                    $product = get_post( $product_id );
                    if (is_object($product) && !is_wp_error($product)) { // post not set but it's a room
                        if ($product->post_type == 'room') 
                            return 'room';
                    }
                }    
    
            } else if(wp_doing_ajax()) { // has post set (usefull when adding using ajax)
                $product_post = get_post( $product_id );
                if ($product_post->post_type == 'room') 
                    return 'room';
            } else { 
                $product = get_post( $product_id );
                if (is_object($product) && !is_wp_error($product)) { // post not set but it's a room
                    if ($product->post_type == 'room') 
                        return 'room';
                }
            }
        }
        return false;
    }

    //Step 4. Finally, your CPT should be queryable as as order line item
    function room_woocommerce_checkout_create_order_line_item_object($item, $cart_item_key, $values, $order) {
        $product = get_post_type( $values['product_id'] );
        if ($product == 'room') {
            return new \Room_WC_Order_Item_Product();
        }
        
        return $item;
    }

    // Display custom cart item meta data (in cart and checkout)
    function display_cart_item_custom_meta_data( $item_data, $cart_item ) {
        $meta_keys = array( 'Check in', 'Check out', 'Number of visitors' );
        foreach ( $meta_keys as $meta_key ){
            if ( isset( $cart_item[$meta_key] ) ) {
                $item_data[] = array(
                    'key'       => $meta_key,
                    'value'     => $cart_item[$meta_key],
                );
            }
        }
        
        return $item_data;
    }

    // Save cart item custom meta as order item meta data and display it everywhere on orders and email notifications.
    function save_cart_item_custom_meta_as_order_item_meta( $item, $cart_item_key, $values, $order ) {
        $meta_keys = array( 'Check in', 'Check out', 'Number of visitors' );
        if ($values['data']->get_type() == 'room') {
            foreach ( $meta_keys as $meta_key ){
                if ( isset( $values[$meta_key] ) ) {
                    $item->update_meta_data( $meta_key, $values[$meta_key] );
                }
            }
            $item->update_meta_data( 'Room', 'yes' ); // add a way to recognize custom post type in ordered items
        }
    }

    // Save booking order on woocommerce successful order placed
    function save_booking_order( $order_id ) {
        $order = new \WC_Order( $order_id );
        
        $total = 0;

        $billing_info = array(
            'full_name' => $order->get_formatted_billing_full_name(),
            'email' => $order->get_billing_email(),
            'phone' => $order->get_billing_phone(),
            'address' => $order->get_billing_address_1(),
            'country' => $order->get_billing_country(),
            'city' => $order->get_billing_city(),
            'zip' => $order->get_billing_postcode(),
        );

        foreach ( $order->get_items() as $item_id => $item ) {

            $product_id = $item->get_product_id();

            $post_type = get_post_type( $product_id ); // If product isn't a room then it shouln't be added to the bookings
            if ( strcmp( $post_type, 'room') != 0 ) continue;

            $product_name = $item->get_name();
            $quantity = $item->get_quantity();
            $prod_total = $item->get_total();
            $total += $prod_total;

            // Getting meta values such as check in / out dates and number of visitors
            $check_in = $item->get_meta( 'Check in', true ); // Check in date
            $check_out = $item->get_meta( 'Check out', true ); // Check out date
            $num_visitors = $item->get_meta( 'Number of visitors', true ); // Number of visitors

            $products[] = array(
                'id' => $product_id,
                'name' => $product_name,
                'quantity' => $quantity,
                'check_in' => $check_in,
                'check_out' => $check_out,
                'num_visitors' => $num_visitors,
                'total' => $prod_total,
            );
        }

        $status = $order->get_status();
        $payment_method = $order->get_payment_method_title();

        $new_post = array(
            'post_title' => 'Order ' . $order_id,
            'post_status' => 'publish',
            'post_type' => 'booking',
            'meta_input' => array(
                'booking_rooms' => $products,
                'booking_status' => $status,
                'booking_billing_details' => $billing_info,
                'booking_price' => $total,
                'booking_payment_method' => $payment_method,
                'booking_payment_paid' => false,
                'booking_date' => date('Y-m-d H:i:s'),
                'wc_order_id' => $order_id,
            )
        );
        $post_id = wp_insert_post( $new_post );

        $order->update_meta_data( 'Booking Id', $post_id );
    }
}



