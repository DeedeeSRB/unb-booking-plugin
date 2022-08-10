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
        
	}

    //Step 2. Use that class to extend WooCommerce Data Store class
    //add_filter( 'woocommerce_data_stores', 'room_woocommerce_data_stores' );
    function room_woocommerce_data_stores( $stores ) {
        $stores['product'] = 'Room_Product_Data_Store';

        return $stores;
    }

    //Step 3. Return price dynamically from your CPT meta value
    //add_filter('woocommerce_product_get_price', 'room_woocommerce_product_get_price', 10, 2 );
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

        if ( get_post_type( $id ) == 'room' ) {
            $class_name = 'Room_WC_Order_Item_Product';
        }

        return $class_name;
    }

    //Step 3. Make sure your CPT is queryable as WooCommerce Product
    //add_filter( 'woocommerce_product_type_query', 'room_woo_product_type', 12, 2 );
    function room_woo_product_type($false, $product_id) {
        if ($false === false) { // don't know why, but this is how woo does it
            global $post;

            // maybe redo it someday?!
            if ( !empty($post) && is_object($post) ) { // post is set
                if ($post->post_type == 'room' && $post->ID == $product_id) {
                    return 'room';
                } else {
                    $product = get_post( $product_id );
                    if ( is_object($product) && !is_wp_error($product) ) {
                        if ($product->post_type == 'room') return 'room';
                    }
                }
            } else if( wp_doing_ajax() ) { // has post set (useful when adding using ajax)
                $product_post = get_post( $product_id );
                if ($product_post->post_type == 'room') return 'room';
            } else {
                $product = get_post( $product_id );
                if ( is_object($product) && !is_wp_error($product) ) {
                    if ($product->post_type == 'room') return 'room';
                }
            }
        }

        return false;
    }

    //Step 4. Finally, your CPT should be queryable as as order line item
    //add_filter( 'woocommerce_checkout_create_order_line_item_object', 'room_woocommerce_checkout_create_order_line_item_object', 20, 4 );
    function room_woocommerce_checkout_create_order_line_item_object($item, $cart_item_key, $values, $order) {
        $product = $values['data'];

        if ($product->get_type() == 'room') {
            return return_room_wc_class();
            //return new Room_WC_Order_Item_Product();
        }
        
        return $item;
    }
}



