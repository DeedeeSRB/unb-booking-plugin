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
 * UNB Booking Plugin Custom Post Type's Meta Boxes Callbacks Class
 *
 * Responsible to register the plugin's custom post types for the admin panel.
 * 
 */
class CPTMetaCallbacks 
{
    public static function roomPrice()
	{
		wp_nonce_field( UNB_PLUGIN_NAME, 'room_price_box_content_nonce' );
        echo '<label for="room_price"></label>';
        echo '<input type="text" id="room_price" name="room_price" placeholder="Enter a price" />';
	}
}