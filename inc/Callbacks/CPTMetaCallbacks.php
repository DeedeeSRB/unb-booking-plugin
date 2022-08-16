<?php
/**
 * CPTMetaCallbacks class.
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

 /**
 * UNB Booking Plugin Custom Post Type's Meta Fields and Boxes Callbacks Class
 *
 * Responsible to keep track and display the plugin's custom post types fields and boxes.
 * 
 */
class CPTMetaCallbacks 
{
    public static function postBox( $post_args, $callback_args )
	{
		wp_nonce_field( UNB_PLUGIN_NAME, $callback_args['args']['nonce'] );
        foreach ( $callback_args['args']['fields'] as $field ) {
            $value = get_post_meta( $post_args->ID, $field['id'], true);
            $type = isset($field['type']) ? $field['type'] : '';
            $place_holder = isset($field['place_holder']) ? $field['place_holder'] : '';
            echo '<div>';
                echo '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';

                if ( $type == 'textarea' ) {
                    echo '<div><textarea class="regular-text" id="' . $field['id'] . '" name="' . $field['id'] . '" placeholder="' . $place_holder . '" >' .  $value . '</textarea></div>';
                }

                else if ( $type == 'select' ) {
                    $options = $field['options'];
                    echo '<div><select class="regular-text" id="' . $field['id'] . '" name="' . $field['id'] . '">';
                    foreach ( $options as $option ) { 
                        $selected = strcmp( $option, $value) == 0 ? 'selected' : '';
                        echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                    }
                    echo '</select></div>';
                }

                else {
                    echo '<div><input type="text" class="regular-text" id="' . $field['id'] . '" name="' . $field['id'] . '" placeholder="' . $place_holder . '" value="' .  $value . '"/></div>';    
                }
            echo '</div>';
        }
	}

    public static function bookingBox( $post_args, $callback_args )
	{
		wp_nonce_field( UNB_PLUGIN_NAME, $callback_args['args']['nonce'] );
        
        $rooms = get_post_meta( $post_args->ID, 'booking_rooms', true) !== null ? get_post_meta( $post_args->ID, 'booking_rooms', true) : '';
        $billingDetails = get_post_meta( $post_args->ID, 'booking_billing_details', true) !== null ? get_post_meta( $post_args->ID, 'booking_billing_details', true) : '';
        $totalPrice = get_post_meta( $post_args->ID, 'booking_price', true) !== null ? get_post_meta( $post_args->ID, 'booking_price', true) : '';
        $paymentMethod = get_post_meta( $post_args->ID, 'booking_payment_method', true) !== null ? get_post_meta( $post_args->ID, 'booking_payment_method', true) : '';
        //$paymentPaid = get_post_meta( $post_args->ID, 'booking_payment_paid', true) !== null ? get_post_meta( $post_args->ID, 'booking_payment_paid', true) : '';
        $bookingDate = get_post_meta( $post_args->ID, 'booking_date', true) !== null ? get_post_meta( $post_args->ID, 'booking_date', true) : '';
        $wcOrderId = get_post_meta( $post_args->ID, 'wc_order_id', true) !== null ? get_post_meta( $post_args->ID, 'wc_order_id', true) : '';

        $currencyOptions = get_option( 'currency_options' );
        $pos = isset( $currencyOptions['pos'] ) ? $currencyOptions['pos'] : 'Right'; 
        $symbol = isset( $currencyOptions['symbol'] ) ? $currencyOptions['symbol'] : '$'; 
        $totalPrice = strcmp( $pos, 'Left' ) == 0 ? $symbol . ' ' . $totalPrice :  $totalPrice . ' ' . $symbol;

        ?>
        <div class="row">
        <?php 
            if ( $billingDetails != '' ) {
                ?>
                <div class="col-8"> 
                    <?php
                    foreach ( $rooms as $room ) {
                        $link = get_permalink( $room['id'] );
                        $price = strcmp( $pos, 'Left' ) == 0 ? $symbol . ' ' . $room['total'] :  $room['total'] . ' ' . $symbol;
                        $img = get_the_post_thumbnail_url( $room['id'], 'post-thumbnail' );
                        $check_in_date = new \DateTime( $room['check_in'] );
                        $check_out_date = new \DateTime( $room['check_out'] );
                        ?>
                        <div class="fs-5 mb-3">Room details</div>
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <div>Room: </div>
                                        <div>Check in: </div>
                                        <div>Check out: </div>
                                        <div>Night(s): </div>
                                        <div>Number of visitor(s): </div>
                                        <div>Quantity: </div>
                                        <div>Total cost: </div>
                                    </div>
                                    <div class="col">
                                        <div><a href="<?= $link ?>"><?= $room['name'] ?></a></div>
                                        <div><?= date_format( $check_in_date, "d M Y" ) ?></div>
                                        <div><?= date_format( $check_out_date, "d M Y" ) ?></div>
                                        <div><?= $check_in_date->diff($check_out_date)->format('%a') ?> Night(s)</div>
                                        <div><?= $room['num_visitors'] ?> Visitor(s)</div>
                                        <div><b class="mb-2 fw-bold"> x</b><?= $room['quantity'] ?></div>
                                        <div><b class="mb-2 fw-bold"><?= $price ?></b></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="<?= $link ?>"><img height=125px" src="<?= $img ?>" alt=""></a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } 
            else {
                ?>
                <div>There are no Room Details provided</div>
                <?php 
            } ?>
            <?php 
            if ( $billingDetails != '' ) {
                ?>
                <div class="col-4 ps-5"> 
                    <div class="fs-5 mb-3">Order details</div>
                    <div>Booking date</div>
                    <div class="mb-2 fw-bold"><?= $bookingDate ?></div>
                    <div>Payment method</div>
                    <div class="mb-2 fw-bold"><?= $paymentMethod ?></div>
                    <div>Total cost</div>
                    <div class="mb-2 fw-bold"><?= $totalPrice ?></div>
                    <div>Order by</div>
                    <div class="mb-2 fw-bold"><?= isset( $billingDetails['full_name'] ) ? $billingDetails['full_name'] : '' ?></div>
                    <div>Email</div>
                    <div class="mb-2 fw-bold"><?= isset( $billingDetails['email'] ) ? $billingDetails['email'] : ''  ?></div>
                    <div>Phone number</div>
                    <div class="mb-2 fw-bold"><?= isset( $billingDetails['phone'] ) ? $billingDetails['phone'] : ''  ?></div>
                    <div>Address</div>
                    <div class="mb-2 fw-bold"><?= isset( $billingDetails['address'] ) ? $billingDetails['address'] : ''  ?></div>
                    <div>City</div>
                    <div class="mb-2 fw-bold"><?= isset( $billingDetails['city'] ) ? $billingDetails['city'] : ''  ?></div>
                    <div>Zip / Postal Code</div>
                    <div class="mb-2 fw-bold"><?= isset( $billingDetails['zip'] ) ? $billingDetails['zip'] : ''  ?></div>
                </div>
                <?php
            } 
            else {
                ?>
                <div>There are no Order Details provided</div>
                <?php 
            } ?>
        </div>
        <?php
    }

    public static function bookingPaymentBox( $post_args, $callback_args ) {
        echo '<div>Test</div>'; 
    }
}

