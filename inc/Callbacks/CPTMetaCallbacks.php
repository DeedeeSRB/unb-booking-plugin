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
}