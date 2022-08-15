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
        $rooms = get_post_meta( $post_args->ID, 'booking_rooms', true);
        $checkInValue = get_post_meta( $post_args->ID, 'booking_check_in', true);
        $checkOutValue = get_post_meta( $post_args->ID, 'booking_check_out', true);
        $totalPrice = get_post_meta( $post_args->ID, 'booking_price', true);
        $userFirstname = get_post_meta( $post_args->ID, 'booking_user', true);
        $userEmail = get_post_meta( $post_args->ID, 'booking_email', true);
        $userPhone = get_post_meta( $post_args->ID, 'booking_phone', true);
        ?>
        <div class="row">
            <div class="col"> 
                <?php
                $currencyOptions = get_option( 'currency_options' );
                $pos = isset( $currencyOptions['pos'] ) ? $currencyOptions['pos'] : 'Right'; 
                $symbol = isset( $currencyOptions['symbol'] ) ? $currencyOptions['symbol'] : '$'; 
                foreach ( $rooms as $id => $room ) {
                    $link = get_permalink( $id );
                    $price = strcmp( $pos, 'Left' ) == 0 ? $symbol . ' ' . $room['total'] :  $room['total'] . ' ' . $symbol;
                    $img = get_the_post_thumbnail_url( $id, 'post-thumbnail' );
                    ?>
                    <div class="row mb-5">
                        <div class="col"> 
                            <div class="fs-5 mb-3">Order dates</div>
                            <label for="booking_check_in[<?= $id ?>]">Check in</label>
                            <input type="text" class="regular-text mb-2" id="booking_check_in[<?= $id ?>]" name="booking_check_in[<?= $id ?>]" value="<?= $checkInValue[$id] ?>"/></br>
                            <label for="booking_check_out[<?= $id ?>]">Check Out</label>
                            <input type="text" class="regular-text" id="booking_check_out[<?= $id ?>]" name="booking_check_out[<?= $id ?>]" value="<?= $checkOutValue[$id] ?>"/>
                        </div>
                        <div class="col">
                            <div class="fs-5 mb-3">Room details</div>
                            <div class="row">
                                <div class="col-auto">
                                    <div>Room: <a href="<?= $link ?>"><?= $room['name'] ?></a></div>
                                    <div>Quantity: <b class="fw-bold"> x</b><?= $room['quantity'] ?></div>
                                    <div>Total Cost: <b class="fw-bold"><?= $price ?></b></div>
                                </div>
                                <div class="col text-center">
                                    <a href="<?= $link ?>"><img height=150px" src="<?= $img ?>" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="col-auto mx-5 px-5"> 
                <div class="fs-5 mb-3">Orderer details</div>
                <div>Order by</div>
                <div class="mb-2 fw-bold"><?= $userFirstname ?></div>
                <div>Email</div>
                <div class="mb-2 fw-bold"><?= $userEmail ?></div>
                <div>Phone number</div>
                <div class="mb-2 fw-bold"><?= $userPhone ?></div>
            </div>
            
        </div>
        <?php
    }
}

