<?php
/**
 * Activator
 * 
 * Runs functions as soon as the plugin has been activated in the plugins admin page.
 *
 * @package    UNBBookingPlugin\Classes
 * @since      1.0.0
 */

namespace UnbBooking\Base;

/**
 * Activate class
 */
class Activate
{
	/**
	 * The function that gets called as soon as you activate the plugin 
	 * 
	 * @since 1.0.0
	 */
	public static function activate() {
		flush_rewrite_rules();

		// Set up the default room values if they were left empty in the room form
		if ( !get_option( 'default_room_vals' ) ) {
			$room_default = array(
                'room_price' => '150',
                'room_max_num_vis' => '3',
                'room_min_booking_days' => '7',
                'room_amenities' => 'Tv, Internet, Swimming Pool',
            );
		    update_option( 'default_room_vals', $room_default );
		}

		// Set up the currency optinos for the plugin
		if ( !get_option( 'currency_options' ) ) {
			$currency_default = array(
                'type' => 'USD',
				'name' => 'Dollar',
				'symbol' => '$',
                'pos' => 'Right',
            );
		    update_option( 'currency_options', $currency_default );
		}
	}
}