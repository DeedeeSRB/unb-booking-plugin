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

	// public static function roomSanitize ( $input ) {
	// 	error_log(json_encode($input));
	// 	return $input;
	// }

	public static function roomSection () {
		echo '<h2>Default room values</h2>';
	}
    
	public static function unbText( $args )
	{
		$classes = $args['class'];
		$name = $args['label_for'];
		$place_holder = $args['place_holder'];
		$option_name = $args['option_name'];

		$room_options = get_option( $option_name );
		$value = isset($room_options[$name]) ? $room_options[$name] : '';
		echo '<input type="text" class="' . $classes . '" id="' . $name . '"name="' . $option_name . '[' . $name . ']" placeholder="' . $place_holder . '" value="' . $value . '" required>';
	}

	
	
}