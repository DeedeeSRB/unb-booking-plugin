<?php
/**
 * Initiator
 * 
 * Responsible to initiate all the UNB classes by calling their register methods.
 *
 * @package    UNBBookingPlugin\Classes
 * @since      1.0.0
 */

namespace UnbBooking;

use UnbBooking\Base\Enqueue;
use UnbBooking\Admin\Admin;
use UnbBooking\Admin\CPTAdmin;
use UnbBooking\Manage\AjaxManager;
use UnbBooking\Manage\WCManager;
use UnbBooking\CPTs\CustomRegisterCPT;

 /**
 * Initiator class
 */
final class Init
{
	/**
	 * Store all the classes inside an array
	 * 
	 * @since 1.0.0
	 * @return array Full list of classes
	 */
	public static function get_services() 
	{
		require_once plugin_dir_path( UNB_BOOKING ) . '/inc/Base/Enqueue.php';
		require_once plugin_dir_path( UNB_BOOKING ) . '/inc/Admin/Admin.php';
		require_once plugin_dir_path( UNB_BOOKING ) . '/inc/Admin/CPTAdmin.php';
		require_once plugin_dir_path( UNB_BOOKING ) . '/inc/Manage/AjaxManager.php';
		require_once plugin_dir_path( UNB_BOOKING ) . '/inc/Manage/WCManager.php';
		require_once plugin_dir_path( UNB_BOOKING ) . '/inc/CPT/CustomRegisterCPT.php';

		return [
			Enqueue::class,
			Admin::class,
			CPTAdmin::class,
			AjaxManager::class,
			WCManager::class,
			CustomRegisterCPT::class,
		];
	}

	/**
	 * Loop through the classes, initialize them, 
	 * and call the register() method if it exists
	 * 
	 * @since 1.0.0
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