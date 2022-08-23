<?php
/**
 * Activate class.
 *
 * @category   Class
 * @package    UNBBookingPlugin\Classes
 * @since      1.0.0
 */

namespace UnbBooking\Base;

class Activate
{
	public static function activate() {
		flush_rewrite_rules();

		if ( !get_option( 'room_options' ) ) {
			$room_default = array(
                'room_price' => '150',
                'room_max_num_vis' => '3',
                'room_min_booking_days' => '7',
                'room_amenities' => 'Tv, Internet, Swimming Pool',
            );
		    update_option( 'room_options', $room_default );
		}

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