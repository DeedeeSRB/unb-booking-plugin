<?php
/**
 * @package  UNBBookingPlugin
 */
//namespace Inc;

final class Init
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() 
	{
		require plugin_dir_path( UNB_BOOKING ) . '/inc/Admin/Admin.php';
		require plugin_dir_path( UNB_BOOKING ) . '/inc/Admin/CPTAdmin.php';

		return [
			Admin::class,
			CPTAdmin::class,
		];
	}

	/**
	 * Loop through the classes, initialize them, 
	 * and call the register() method if it exists
	 * @return
	 */
	public static function register_services() 
	{
		foreach ( self::get_services() as $class ) {
			$service = new $class();
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}
}