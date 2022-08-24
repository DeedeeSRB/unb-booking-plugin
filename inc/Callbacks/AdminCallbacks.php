<?php 
/**
 * Admin Callbakcs
 * 
 * Hold all the template and forms callback functions for the admin settings page
 * 
 * @package    UNBBookingPlugin\Classes
 * @since      1.0.0
 */

namespace UnbBooking\Callbacks;

/**
 * Admin Callbacks class
 */
class AdminCallbacks
{
	/**
	 * @since 	   1.0.0
	 * @return php The template page for the admin general page 
	 */
	public static function adminDashboard()
	{
		return require_once UNB_PLUGIN_PATH . '/templates/admin.php';
	}

	/**
	 * @since 	   1.0.0
	 * @return php The template page for the admin settings page 
	 */
	public static function adminSettings()
	{
		return require_once UNB_PLUGIN_PATH . '/templates/admin-settings.php';
	}

	/**
	 * Takes the input from the currency settings and seperates them into specific input fields
	 * 
	 * @since  1.0.0
	 * @param  Array $input The list of inputs recieved from the currency form
	 * @return Array
	 */
	public static function currencySanitize( $input )
	{
		$currency = json_decode( $input['type'] );
		$input['type'] = $currency[0];
		$input['name'] = $currency[1];
		$input['symbol'] = $currency[2];
		return $input;
	}

	/**
	 * Echos the title for the currency form section
	 * 
	 * @since 1.0.0
	 */
	public static function currencySection() 
	{
		echo '<h2>Currency options</h2>';
	}

	/**
	 * Echos the title for the default room values form section
	 * 
	 * @since 1.0.0
	 */
	public static function roomSection() 
	{
		echo '<h2>Default room values</h2>';
	}
    
	/**
	 * Echos the text input fields for the default room values form
	 * 
	 * @since 1.0.0
	 * @param Array $args The list of arguments for the field
	 */
	public static function unbText( $args )
	{
		// The css classes for the input field
		$classes = $args['class'];
		// The name/id for the field
		$name = $args['label_for'];
		// The place holder if it is set
		$place_holder = isset( $args['place_holder'] ) ? $args['place_holder'] : '';

		// The option name to get the previous values
		$option_name = $args['option_name'];
		// Get the option values
		$options = get_option( $option_name );
		// Get the specific value for this field if it is set previously by the user
		$value = isset( $options[$name] ) ? $options[$name] : '';

		// Echo the input field with the previous properties 
		// NOTE: The name of the field is set to $option_name['$name'] ( ie. default_room_vals['room_price'] )
		// This is built like this to keep the options clean by saving them in an array instead of indiviual option values
		echo '<input type="text" class="' . $classes . '" id="' . $name . '"name="' . $option_name . '[' . $name . ']" placeholder="' . $place_holder . '" value="' . $value . '" required>';
	}

	/**
	 * Echos the select input field for the currency type form
	 * 
	 * @since 1.0.0
	 * @param Array $args The list of arguments for the field
	 */
	public static function currencyType ( $args ) 
	{
		// The name/id for the field
		$name = $args['label_for'];
		// The values/options for the select field
		$values = $args['values'];

		// The option name to get the previous values
		$option_name = $args['option_name'];
		// Get the option values
		$options = get_option( $option_name );
		// Get the previously selected values for the currency type and pos fields
		$currencyType = isset( $options['type'] ) ? $options['type'] : 'USD'; # The currency type: Default 'USD'
		$pos = isset( $options['pos'] ) ? $options['pos'] : 'Right'; # The currency postion: Default 'Right'
		
		// Similarly, the name is set up as an array ($option_name[type])
		?>
		<select name="<?= $option_name . '[type]' ?>" id="<?= $name ?>">
		<?php 
			// List all the options for the select field
			foreach( $values as $value => $title ) {
				// The return value for this specefic option is a combination of the value, the tile, and its symbol. 
				$rValue = esc_html( json_encode( array( $value, $title['title'], $title['sym'] ) ) );
				// This $title variable is to display the correct the title and sym in the correct postion (Left or Right)
				$title = strcmp( $pos, 'Left' ) == 0 ?  $title['sym'] . ' ' . $title['title'] :  $title['title'] . ' ' . $title['sym'];
				?>
					<option value="<?= $rValue ?>" <?= strcmp( $value, $currencyType) == 0 ? 'selected' : '' ?>><?= $title ?></option>
				<?php 
			}
		?>
		</select>
		<?php
	}

	/**
	 * Echos the select input field for the currency symbol postion form
	 * 
	 * @since 1.0.0
	 * @param Array $args The list of arguments for the field
	 */
	public static function currencyPos ( $args ) 
	{
		// The name/id for the field
		$name = $args['label_for'];
		// The values/options for the select field
		$values = $args['values'];

		// The option name to get the previous values
		$option_name = $args['option_name'];
		// Get the option values
		$options = get_option( $option_name );
		// Get the previously selected value for the position of the symbol
		$selected = isset( $options['pos'] ) ? $options['pos'] : 'Right'; 
		
		// The name is set up as an array ($option_name[type])
		?>
		<select name="<?= $option_name . '[pos]' ?>" id="<?= $name ?>">
		<?php 
			// List all the options for the select field
			foreach( $values as $value ) {
				?>
					<option value="<?= $value ?>" <?= strcmp( $value, $selected) == 0 ? 'selected' : '' ?>><?= $value ?></option>
				<?php 
			}
		?>
		</select>
		<?php
	}

	
	
}