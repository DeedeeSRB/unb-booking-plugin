<?php
/**
 * register_wcclasses class.
 *
 * @category   Function
 * @package    UNBBookingPlugin
 * @subpackage WordPress
 * @author     Unbelievable Digital
 * @copyright  2022 Unbelievable Digital
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       https://unbelievable.digital/
 * @since      1.0.0
 * php version 7.3.9
 */

//namespace UnbBooking\WCClasses;

function register_wcclasses() {
    

    //Use that class to extend WooCommerce Data Store class
    class Room_Product_Data_Store extends WC_Product_Data_Store_CPT implements WC_Object_Data_Store_Interface, WC_Product_Data_Store_Interface {

        public function read( &$product ) {
            $product->set_defaults();
            $post_object = get_post( $product->get_id() );

            if ( ! $product->get_id() || ! $post_object || !in_array( $post_object->post_type, ['room', 'product'] ) ) {
                throw new Exception( __( 'Invalid product.', 'woocommerce' ) );
            }

            $product->set_id( $post_object->ID );

            $product->set_props(
                array(
                    'name'              => $post_object->post_title,
                    'slug'              => $post_object->post_name,
                    'date_created'      => $this->string_to_timestamp( $post_object->post_date_gmt ),
                    'date_modified'     => $this->string_to_timestamp( $post_object->post_modified_gmt ),
                    'status'            => $post_object->post_status,
                    'description'       => $post_object->post_content,
                    'short_description' => $post_object->post_excerpt,
                    'parent_id'         => $post_object->post_parent,
                    'menu_order'        => $post_object->menu_order,
                    'post_password'     => $post_object->post_password,
                    'reviews_allowed'   => 'open' === $post_object->comment_status,
                )
            );

            $this->read_attributes( $product );
            $this->read_downloads( $product );
            $this->read_visibility( $product );
            $this->read_product_data( $product );
            $this->read_extra_data( $product );
            $product->set_object_read( true );

            do_action( 'woocommerce_product_read', $product->get_id() );
        }

        /**
         * Get the product type based on product ID.
         */
        public function get_product_type( $product_id ) {

            $post_type = get_post_type( $product_id );

            if ( 'product_variation' === $post_type ) {
                return 'variation';
            } elseif ( 'room' === $post_type ) {
                $terms = get_the_terms( $product_id, 'product_type' );
                return !empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'room';
            } else {
                return false;
            }

        }

    }

    // Note: If you've done till this step, then the post will be added to cart and WooCommerce will recognize it as cart item up to checkout process. 
    // But WooCommerce won't recognize it as an order item after checkout is done. To make it available as an order item, we need to follow some more steps.

    //  Use your custom class to extend WooCommerce product class and add your CPT
    class Room_Woo_Product extends WC_Product {
        protected $post_type = 'room';

        public function get_type() {
            return 'room';
        }

        public function __construct( $product = 0 ) {
            $this->supports[] = 'ajax_add_to_cart';
            parent::__construct( $product );
        }
        // maybe overwrite other functions from WC_Product
    }
    
    // Make sure your CPT is recognized as Product when WooCommerce tries to get product class
    class Room_WC_Order_Item_Product extends WC_Order_Item_Product {
        public function set_product_id( $value ) {
            if ( $value > 0 && 'room' !== get_post_type( absint( $value ) ) ) {
                $this->error( 'order_item_product_invalid_product_id', __( 'Invalid product ID', 'woocommerce' ) );
            }
            $this->set_prop( 'product_id', absint( $value ) );
        }
    }
}