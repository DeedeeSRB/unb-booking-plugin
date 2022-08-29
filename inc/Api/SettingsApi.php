<?php 
/**
 * SettingsApi class
 * 
 * Responsible to dynamicly register the plugin's admin pages and subpages and settings, sections, and fields
 *
 * @package    UNBBookingPlugin\Classes
 * @since      1.0.0
 */

namespace UnbBooking\Api;

/**
 * Admin Settings Api class
 */
class SettingsApi
{
	/**
	 * Array to keep track of all admin pages
	 *
	 * @since 1.0.0
	 * @var array 
	 */
	public $admin_pages = array();

	/**
	 * Array to keep track of all admin subpages
	 *
	 * @since 1.0.0
	 * @var array 
	 */
    public $admin_subpages = array();


	/**
	 * Array to keep track of all admin settings
	 *
	 * @since 1.0.0
	 * @var array 
	 */
    public $settings = array();

	/**
	 * Array to keep track of all admin settings' sections
	 *
	 * @since 1.0.0
	 * @var array 
	 */
	public $sections = array();

	/**
	 * Array to keep track of all admin settings' fields
	 *
	 * @since 1.0.0
	 * @var array 
	 */
	public $fields = array();

	/**
	 * Add actions to register the admin page menus and the settings
	 *
	 * @since 1.0.0
	 */
	public function register()
	{
		if ( ! empty($this->admin_pages) ) {
			add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
		}

        if ( !empty($this->settings) ) {
			add_action( 'admin_init', array( $this, 'registerCustomFields' ) );
		}
	}

	/**
	 * Set the admin pages
	 * 
	 * @since 1.0.0
	 * @param array An array with all the admin pages
	 */
	public function setPages( $pages )
	{
		$this->admin_pages = $pages;
		return $this;
	}

	/**
	 * Set the main admin page's title
	 * 
	 * @since 1.0.0
	 * @param string The title for the main admin page
	 */
    public function withSubPage( $title = null ) 
	{
		if ( empty($this->admin_pages) ) {
			return $this;
		}

		$admin_page = $this->admin_pages[0];

		$subpage = array(
			array(
				'parent_slug' => $admin_page['menu_slug'], 
				'page_title' => $admin_page['page_title'], 
				'menu_title' => ($title) ? $title : $admin_page['menu_title'], 
				'capability' => $admin_page['capability'], 
				'menu_slug' => $admin_page['menu_slug'], 
				'callback' => $admin_page['callback']
			)
		);

		$this->admin_subpages = $subpage;
		return $this;
	}

	/**
	 * Set the admin subpages
	 * 
	 * @since 1.0.0
	 * @param array An array with all the subpages
	 */
	public function setSubPages( $pages )
    {
		$this->admin_subpages = array_merge( $this->admin_subpages, $pages );
		return $this;
	}

	/**
	 * Register the admin menu with wordpress
	 * 
	 * @since 1.0.0
	 */
	public function addAdminMenu()
	{
		foreach ( $this->admin_pages as $page ) {
			add_menu_page( 
				$page['page_title'], 
				$page['menu_title'], 
				$page['capability'], 
				$page['menu_slug'], 
				$page['callback'], 
				$page['icon_url'], 
				$page['position'] 
			);
		}

        foreach ( $this->admin_subpages as $page ) {
			add_submenu_page( 
				$page['parent_slug'], 
				$page['page_title'], 
				$page['menu_title'], 
				$page['capability'], 
				$page['menu_slug'], 
				$page['callback'] 
			);
		}
	}

	/**
	 * Set the admin settings
	 * 
	 * @since 1.0.0
	 * @param array An array with all the settings
	 */
    public function setSettings( array $settings )
	{
		$this->settings = $settings;
		return $this;
	}

	/**
	 * Set the admin settings' sections
	 * 
	 * @since 1.0.0
	 * @param array An array with all the settings' sections
	 */
	public function setSections( array $sections )
	{
		$this->sections = $sections;
		return $this;
	}

	/**
	 * Set the admin settings' fields
	 * 
	 * @since 1.0.0
	 * @param array An array with all the settings' fields
	 */
	public function setFields( array $fields )
	{
		$this->fields = $fields;
		return $this;
	}

	/**
	 * Register the all the admin settings, sections, and fields
	 * 
	 * @since 1.0.0
	 */
	public function registerCustomFields()
	{
		foreach ( $this->settings as $setting ) {
			register_setting( 
				$setting["option_group"], 
				$setting["option_name"], 
				( isset( $setting["callback"] ) ? $setting["callback"] : '' ) 
			);
		}

		foreach ( $this->sections as $section ) {
			add_settings_section( 
				$section["id"], 
				( isset( $section["title"] ) ? $section["title"] : '' ), 
				( isset( $section["callback"] ) ? $section["callback"] : '' ), 
				$section["page"] 
			);
		}

		foreach ( $this->fields as $field ) {
			add_settings_field( 
				$field["id"], 
				$field["title"], 
				( isset( $field["callback"] ) ? $field["callback"] : '' ), 
				$field["page"], 
				$field["section"], 
				( isset( $field["args"] ) ? $field["args"] : '' ) 
			);
		}
	}
}