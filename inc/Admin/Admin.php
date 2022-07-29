<?php 
/**
 * Admin class.
 *
 * @category   Class
 * @package    UnbBooking
 * @subpackage WordPress
 * @author     Unbelievable Digital
 * @copyright  2022 Unbelievable Digital
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       https://unbelievable.digital/
 * @since      1.0.0
 * php version 7.3.9
 */
//namespace Inc\Pages;

//use Inc\Api\SettingsApi;

/**
 * UNB Booking Plugin Admin Class
 *
 * Responsible to create admin pages, subpages, and custom forms using SettingsApi.
 * Callbacks should be included in AdminCallbacks.php file.
 * 
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
	 * Admin pages
	 *
	 * @since 1.0.0
	 * @var array Array to keep track of all admin pages
	 */
	public $pages = array();

	/**
	 * SubAdmin pages
	 *
	 * @since 1.0.0
	 * @var array Array to keep track of all the subpages of the admin pages
	 */
	public $subpages = array();

	/**
	 * Register admin pages and subpages in the SettingsApi.php file.
	 * Call functions to set forms settings in the SettingsApi.php file.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register() 
	{
		require UNB_PLUGIN_PATH . 'inc/Api/SettingsApi.php';
		
		$this->settings = new SettingsApi();

		$this->setPages();
		$this->setSubpages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->addSubPages( $this->subpages )->register();
		//$this->settings->addPages( $this->pages )->register();
	}

	/**
	 * Setting the admin pages to the pages array
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function setPages() 
	{
		require UNB_PLUGIN_PATH . 'inc/Api/AdminCallbacks.php';
		$this->pages = array(
			array(
				'page_title' => 'UNB Booking Plugin', 
				'menu_title' => 'UNB Booking', 
				'capability' => 'manage_options', 
				'menu_slug' => 'unb_booking_plugin', 
				'callback' => array( 'AdminCallbacks', 'adminDashboard' ), 
				'icon_url' => 'dashicons-open-folder', 
				'position' => 58
			)
		);
	}

	/**
	 * Setting the admin subpages to the subpages array
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function setSubpages() 
	{
		$this->subpages = array(
			array(
				'parent_slug' => 'unb_booking_plugin', 
				'page_title' => 'UNB Booking Settings', 
				'menu_title' => 'Settings', 
				'capability' => 'manage_options', 
				'menu_slug' => 'unb_booking_plugin_settings', 
				'callback' => array( 'AdminCallbacks', 'adminSettings' )
			)
		);
	}

	/**
	 * Setting the admin settings in the SettingsApi.file
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function setSettings()
	{
		$args = array(
			array(
				'option_group' => 'unb_booking_plugin',
				'option_name' => 'entry_1',
			),
		);

		$this->settings->setSettings( $args );
	}

	/**
	 * Setting the admin sections in the SettingsApi.file
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function setSections()
	{
		$args = array(
			array(
				'id' => 'unb_booking_plugin_section',
				'callback' => '',
				'page' => 'unb_booking_plugin'
			)
		);

		$this->settings->setSections( $args );
	}

	/**
	 * Setting the admin fields in the SettingsApi.file
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function setFields()
	{
		//require UNB_PLUGIN_PATH . 'inc/Api/AdminCallbacks.php';
		$args = array(
			array(
				'id' => 'entry_1',
				'title' => 'Entry 1',
				'callback' => array( 'AdminCallbacks', 'unbBEntry1' ),
				'page' => 'unb_booking_plugin',
				'section' => 'unb_booking_plugin_section',
				'args' => array(
					'label_for' => 'entry_1',
					'class' => '',
				)
			),
			
		);

		$this->settings->setFields( $args );
	}
}