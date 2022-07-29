<?php 
/**
 * @package  UNBBookingPlugin
 */
//namespace Inc\Api;

class AdminCallbacks
{
	public static function adminDashboard()
	{
		return require_once UNB_PLUGIN_PATH . '/templates/admin.php';
	}

	public static function adminSettings()
	{
		return require_once UNB_PLUGIN_PATH . '/templates/admin-settings.php';
	}
    
	public static function unbBEntry1()
	{
		$entry_1 = esc_attr( get_option( 'entry_1' ) );
		echo '<input type="text" class="regular-text" name="entry_1" placeholder="Entry 1" maxlength="45" value="' . $entry_1 . '" required>';
	}

	
	
}