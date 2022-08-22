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

	public static function currencySanitize( $input )
	{
		$currency = json_decode( $input['type'] );
		$input['type'] = $currency[0];
		$input['name'] = $currency[1];
		$input['symbol'] = $currency[2];
		return $input;
	}

	public static function currencySection() 
	{
		echo '<h2>Currency options</h2>';
	}

	public static function roomSection() 
	{
		echo '<h2>Default room values</h2>';
	}
    
	public static function unbText( $args )
	{
		$classes = $args['class'];
		$name = $args['label_for'];
		$place_holder = isset( $args['place_holder'] ) ? $args['place_holder'] : '';
		$option_name = $args['option_name'];

		$options = get_option( $option_name );
		$value = isset( $options[$name] ) ? $options[$name] : '';
		echo '<input type="text" class="' . $classes . '" id="' . $name . '"name="' . $option_name . '[' . $name . ']" placeholder="' . $place_holder . '" value="' . $value . '" required>';
	}

	public static function currencyType ( $args ) 
	{
		$name = $args['label_for'];
		$values = $args['values'];
		$option_name = $args['option_name'];

		$options = get_option( $option_name );
		$selected = isset( $options['type'] ) ? $options['type'] : 'USD'; 
		$pos = isset( $options['pos'] ) ? $options['pos'] : 'Right';
		
		?>
		<select name="<?= $option_name . '[type]' ?>" id="<?= $name ?>">
		<?php 
			foreach( $values as $value => $title ) {
				$rValue = esc_html( json_encode( array( $value, $title[0], $title[1] ) ) );
				$title = strcmp( $pos, 'Left' ) == 0 ?  $title[1] . ' ' . $title[0] :  $title[0] . ' ' . $title[1];
				?>
					<option value="<?= $rValue ?>" <?= strcmp( $value, $selected) == 0 ? 'selected' : '' ?>><?= $title ?></option>
				<?php 
			}
		?>
		</select>
		<?php
	}

	public static function currencyPos ( $args ) 
	{
		$name = $args['label_for'];
		$values = $args['values'];
		$option_name = $args['option_name'];

		$options = get_option( $option_name );
		$selected = isset( $options['pos'] ) ? $options['pos'] : 'Right'; 
		
		?>
		<select name="<?= $option_name . '[pos]' ?>" id="<?= $name ?>">
		<?php 
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