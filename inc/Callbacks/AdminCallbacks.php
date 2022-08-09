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
		$selected = isset( $options[$name] ) ? key( json_decode( $options[$name] ) ) : 'USD'; 
		$side = isset( $options['currency_pos'] ) ? $options['currency_pos'] : array( 'Right' );
		
		?>
		<select name="<?= $option_name . '[' . $name . ']' ?>" id="<?= $name ?>">
		<?php 
			foreach( $values as $value => $title ) {
				$rValue = esc_html( json_encode( array( $value => json_encode( array( $title[0], $title[1] ) ) ) ) );
				$title = strcmp( $side, 'Left' ) == 0 ?  $title[1] . ' ' . $title[0] :  $title[0] . ' ' . $title[1];
				echo $value;
				
				echo strcmp( $value, $selected);
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
		$selected = isset( $options[$name] ) ? $options[$name] : 'Right'; 
		
		?>
		<select name="<?= $option_name . '[' . $name . ']' ?>" id="<?= $name ?>">
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