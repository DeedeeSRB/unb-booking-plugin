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

    /**
	 * Callback function to display a posts' meta fields box dynamiclly
     * The $post_args variable is provided by wordpress and contains information about the post we are displaying its meta box
     * The $callback_args variable is provided by us and contains information such as the nonce name and the fields with their properties
     * 
	 * @since 1.0.0
	 * @access public
	 */
    public static function postBox( $post_args, $callback_args )
	{
        // Generate a wordpress nonce field for validation later on
		wp_nonce_field( UNB_PLUGIN_NAME, $callback_args['args']['nonce'] );

        // Foreach loop to display all the fields
        foreach ( $callback_args['args']['fields'] as $field ) {

            // The field id is also the post meta key and we check if there is an older value for this meta field
            $value = get_post_meta( $post_args->ID, $field['id'], true);

            // One of the properties we provide to fields is 'type' which explains what input type it is
            $type = isset( $field['type'] ) ? $field['type'] : '';

            // If we have set a placeholder to the field we fetch it
            $place_holder = isset( $field['place_holder'] ) ? $field['place_holder'] : '';

            // Start displaying the html for the fields
            echo '<div>';
            
            // Echo the label for the field with the 'for' attr set to the field id and the label is taken from the field 'label' property
            echo '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
            echo '<div>';

            // Depending on the type we display the corresponding input field with their properties
            if ( $type == 'textarea' ) {
                echo '<textarea class="regular-text" id="' . $field['id'] . '" name="' . $field['id'] . '" placeholder="' . $place_holder . '" >' .  $value . '</textarea>';
            }
            // If the type is 'select', the field should have the property 'options'
            else if ( $type == 'select' ) {
                $options = $field['options'];
                echo '<select class="regular-text" id="' . $field['id'] . '" name="' . $field['id'] . '">';
                foreach ( $options as $option ) { 
                    // If the option is the same as the value we retrieved from the post meta then that means it should be selected by default
                    $selected = strcmp( $option, $value) == 0 ? 'selected' : '';
                    echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                }
                echo '</select>';
            }
            // If not type is provided then by defautl the type should be text
            else {
                echo '<input type="text" class="regular-text" id="' . $field['id'] . '" name="' . $field['id'] . '" placeholder="' . $place_holder . '" value="' .  $value . '"/>';    
            }

            // If a description is provided to a field, then we display it
            if ( isset( $field['description'] ) ) {
                echo '<p class="description">' . $field['description'] . '</p>';
            }

            echo '</div>';
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
        $paymentPaid = get_post_meta( $post_args->ID, 'booking_payment_paid', true) !== null ? get_post_meta( $post_args->ID, 'booking_payment_paid', true) : false;
        ?>
        <div class="row mt-2 pt-1">
            <?php 
            if ( $paymentPaid ) { ?>
                <div class="col">
                    <div style="background-color: LightGreen; width: fit-content; padding: 0px 7px 2px 7px; border-radius: 5px; color: Black;">Paid</div>
                </div>
                <div class="col text-end">
                    <input type='hidden' value='false' name='booking_payment_paid_form'>
                    <input type="checkbox" name="booking_payment_paid_form" id="booking_payment_paid_form" value='true' checked/>
                    <label for="booking_payment_paid_form">Set Paid</label>
                </div>
            <?php }
            else { ?>
                <div class="col">
                    <div style="background-color: Tomato; width: fit-content; padding: 0px 7px 2px 7px; border-radius: 5px; color: White;">Not Paid</div>
                </div>
                <div class="col text-end">
                    <input type='hidden' value='false' name='booking_payment_paid_form'>
                    <input class="m-0" type="checkbox" name="booking_payment_paid_form" id="booking_payment_paid_form" value='true'/>
                    <label for="booking_payment_paid_form">Set Paid</label>
                </div>
            <?php } ?>
        </div>
        <?php
    }
}

