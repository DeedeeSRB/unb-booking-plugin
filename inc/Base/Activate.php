<?php
/**
 * @package  AlecadddPlugin
 */
//namespace Inc\Base;

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

		
	}
}