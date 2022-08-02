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
            echo '<div>';
                echo '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
                if ( $field['type'] == 'text' ) {
                    echo '<div><input type="text" class="regular-text" id="' . $field['id'] . '" name="' . $field['id'] . '" placeholder="' . $field['place_holder'] . '" value="' .  $value . '"/></div>';    
                }
                else if ( $field['type'] == 'textarea' ) {
                    echo '<div><textarea class="regular-text" id="' . $field['id'] . '" name="' . $field['id'] . '" placeholder="' . $field['place_holder'] . '" >' .  $value . '</textarea></div>';
                }
            echo '</div>';
        }
	}

    // public static function textType( $post_args, $callback_args )
	// {
	// 	wp_nonce_field( UNB_PLUGIN_NAME, $callback_args['args']['nonce'] );
    //     echo '<label for="' . $callback_args['args']['id'] . '"></label>';
    //     echo '<input type="text" id="' . $callback_args['args']['id'] . '" name="' . $callback_args['args']['id'] . '" placeholder="' . $callback_args['args']['place_holder'] . '" />';
	// }

    // public static function textareaType( $post_args, $callback_args )
	// {
	// 	wp_nonce_field( UNB_PLUGIN_NAME, $callback_args['args']['nonce'] );
    //     echo '<label for="' . $callback_args['args']['id'] . '"></label>';
    //     echo '<textarea style="width:100%;" id="' . $callback_args['args']['id'] . '" name="' . $callback_args['args']['id'] . '" placeholder="' . $callback_args['args']['place_holder'] . '" ></textarea>';
	// }
}