<?php 
/**
 * Admin class
 * 
 * Responsible to create admin pages, subpages, and custom forms data and setting them in SettingsApi
 * Callbacks for the pages and custom fields should be included in AdminCallbacks.php file
 *
 * @package    UNBBookingPlugin\Classes
 * @since      1.0.0
 */

namespace UnbBooking\Admin;

use UnbBooking\Api\SettingsApi;
use UnbBooking\Callbacks\AdminCallbacks;

/**
 * Admin Class
 */
class Admin
{
	/**
	 * SettingApi variable
	 *
	 * @since 1.0.0
	 * @var SettingsApi SettingApi variable to set pages, subpages, and forms.
	 */
	public $settings;

	/**
	 * Set admin pages, subpages, and forms for the admin panel in the SettingsApi.php file and then register them.
	 *
	 * @since 1.0.0
	 */
	public function register() 
	{
		require_once UNB_PLUGIN_PATH . 'inc/Api/SettingsApi.php';
		require_once UNB_PLUGIN_PATH . 'inc/Callbacks/AdminCallbacks.php';
		
		// Create new SettingsApi varaible
		$this->settings = new SettingsApi();

		// Set the admin pages
		$this->setPages();
		// The first admin page is called "General"
		$this->settings->withSubPage( 'General' );
		// Set the any admin subpages
		$this->setSubpages();

		// Set the admin settings 
		$this->setSettings();
		// Set the admin settings' sections
		$this->setSections();
		// Set the admin settings' fields
		$this->setFields();

		// Register all the variables we set
		$this->settings->register();
	}

	/**
	 * Setting the admin pages for the SettingsApi
	 *
	 * @since 1.0.0
	 */
	public function setPages() 
	{
		$pages = array(
			array(
				'page_title' => 'UNB Booking Plugin', 
				'menu_title' => 'UNB Booking', 
				'capability' => 'manage_options', 
				'menu_slug' => 'unb_booking_plugin', 
				'callback' => array( 'UnbBooking\Callbacks\AdminCallbacks', 'adminDashboard' ), 
				'icon_url' => 'dashicons-open-folder', 
				'position' => 58
			)
		);

		$this->settings->setPages( $pages );
	}

	/**
	 * Setting the admin subpages to the subpages array
	 *
	 * @since 1.0.0
	 */
	public function setSubpages() 
	{
		$subpages = array(
			array(
				'parent_slug' => 'unb_booking_plugin', 
				'page_title' => 'UNB Booking Settings', 
				'menu_title' => 'Settings', 
				'capability' => 'manage_options', 
				'menu_slug' => 'unb_booking_plugin_settings', 
				'callback' => array( 'UnbBooking\Callbacks\AdminCallbacks', 'adminSettings' )
			)
		);

		$this->settings->setSubPages( $subpages );
	}

	/**
	 * Setting the admin settings in the SettingsApi.file
	 *
	 * @since 1.0.0
	 */
	public function setSettings()
	{
		// This array will hold the settings that should be included in the admin pages
		// NOTE: The callbacks should included in the AdminCallbacks.php file
		$args = array(
			array(
				'option_group' => 'unb_booking_plugin_currency_options',
				'option_name' => 'currency_options',
				'callback' => array( 'UnbBooking\Callbacks\AdminCallbacks', 'currencySanitize' )
			),
			array(
				'option_group' => 'unb_booking_plugin_default_room_vals',
				'option_name' => 'default_room_vals',
			),
		);

		$this->settings->setSettings( $args );
	}

	/**
	 * Setting the admin sections in the SettingsApi.file
	 *
	 * @since 1.0.0
	 */
	public function setSections()
	{
		// This array will hold the settings' sections that should be included in the admin pages
		// NOTE: The callbacks should included in the AdminCallbacks.php file
		$args = array(
			array(
				'id' => 'unb_booking_plugin_currency_section',
				'callback' => array( 'UnbBooking\Callbacks\AdminCallbacks', 'currencySection' ),
				'page' => 'unb_booking_plugin'
			),
			array(
				'id' => 'unb_booking_plugin_room_section',
				'callback' => array( 'UnbBooking\Callbacks\AdminCallbacks', 'roomSection' ),
				'page' => 'unb_booking_plugin_settings'
			),
		);

		$this->settings->setSections( $args );
	}

	/**
	 * Setting the admin fields in the SettingsApi.file
	 *
	 * @since 1.0.0
	 */
	public function setFields()
	{
        // This array will hold the settings' fields that should be included in the admin pages
		// NOTE: The callbacks should included in the AdminCallbacks.php file
		$args = array(
			array(
				'id' => 'currency_type',
				'title' => 'Currency',
				'callback' => array( 'UnbBooking\Callbacks\AdminCallbacks', 'currencyType' ),
				'page' => 'unb_booking_plugin',
				'section' => 'unb_booking_plugin_currency_section',
				'args' => array(
					'label_for' => 'currency_type',
					'option_name' => 'currency_options',
					'values' => array(
						'TRY' => array( 'title' => 'Turkish Lira',  'sym' => '₺' ),
						'USD' => array( 'title' => 'Dollar', 		'sym' => '$' ),
						'EUR' => array( 'title' => 'Euro', 			'sym' => '€' ),
						'RUB' => array( 'title' => 'Russia Ruble', 	'sym' => '₽' ),
						'AED' => array( 'title' => 'UAE Dirham', 	'sym' => 'د.إ' ),
					),
				)
			),
			array(
				'id' => 'currency_pos',
				'title' => 'Currency Position',
				'callback' => array( 'UnbBooking\Callbacks\AdminCallbacks', 'currencyPos' ),
				'page' => 'unb_booking_plugin',
				'section' => 'unb_booking_plugin_currency_section',
				'args' => array(
					'label_for' => 'currency_pos',
					'option_name' => 'currency_options',
					'values' => array(
						'Right',
						'Left'
					),
				)
			),
			array(
				'id' => 'room_price',
				'title' => 'Price',
				'callback' => array( 'UnbBooking\Callbacks\AdminCallbacks', 'unbText' ),
				'page' => 'unb_booking_plugin_settings',
				'section' => 'unb_booking_plugin_room_section',
				'args' => array(
					'label_for' => 'room_price',
					'class' => 'regular-text',
					'place_holder' => 'eg. 150',
					'option_name' => 'default_room_vals'
				)
			),
			array(
				'id' => 'room_max_num_vis',
				'title' => 'Maximum number of visitors',
				'callback' => array( 'UnbBooking\Callbacks\AdminCallbacks', 'unbText' ),
				'page' => 'unb_booking_plugin_settings',
				'section' => 'unb_booking_plugin_room_section',
				'args' => array(
					'label_for' => 'room_max_num_vis',
					'class' => 'regular-text',
					'place_holder' => 'eg. 3',
					'option_name' => 'default_room_vals'
				)
			),
			array(
				'id' => 'room_min_booking_days',
				'title' => 'Minimum booking days',
				'callback' => array( 'UnbBooking\Callbacks\AdminCallbacks', 'unbText' ),
				'page' => 'unb_booking_plugin_settings',
				'section' => 'unb_booking_plugin_room_section',
				'args' => array(
					'label_for' => 'room_min_booking_days',
					'class' => 'regular-text',
					'place_holder' => 'eg. 7',
					'option_name' => 'default_room_vals'
				)
			),
			array(
				'id' => 'room_amenities',
				'title' => 'Amemities',
				'callback' => array( 'UnbBooking\Callbacks\AdminCallbacks', 'unbText' ),
				'page' => 'unb_booking_plugin_settings',
				'section' => 'unb_booking_plugin_room_section',
				'args' => array(
					'label_for' => 'room_amenities',
					'class' => 'regular-text',
					'place_holder' => 'eg. Tv, Internet, Swimming Pool',
					'option_name' => 'default_room_vals'
				)
			),
			
		);

		$this->settings->setFields( $args );
	}
}