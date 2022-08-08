<?php
/**
 * Activate class.
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

namespace UnbBooking\Base;

class Activate
{
	public function activate() {
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

		
	}
}