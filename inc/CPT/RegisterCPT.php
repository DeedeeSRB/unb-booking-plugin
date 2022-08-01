<?php
/**
 * RegisterCPT class.
 *
 * @category   Class
 * @package    UNBBookingPlugin
 * @subpackage WordPress
 * @author     Unbelievable Digital
 * @copyright  2022 Unbelievable Digital
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       https://unbelievable.digital/
 * @since      1.0.0
 * php version 7.3.9
 */

 /**
 * UNB Booking Plugin Registering Custom Post Type Class
 *
 * Responsible to register the plugin's custom post types for the admin panel.
 * 
 */
class RegisterCPT 
{
    /**
	 * Admin custom post types
	 *
	 * @since 1.0.0
	 * @var array Array to keep track of all custom post types
	 */
    public $custom_post_types = array();
    
    /**
	 * Call a function to register custom post types if they are not empty.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function register() {
        if ( ! empty($this->custom_post_types) ) {
            add_action('init', array( $this, 'registerCPTs' ) );
        }
    }

    /**
	 * Register all custom post types for the admin panel.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function registerCPTs() {
        foreach ( $this->custom_post_types as $cpt ) {
		
            $supports = $cpt['supports'];

            $labels = $cpt['labels'];

            $args = array(
                'supports' => $supports,
                'labels' => $labels 
            );
            $args = array_merge( $args, $cpt['args'] );
            
            register_post_type( strtolower( $labels['singular_name'] ), $args);
        }
    }

    /**
	 * Set custom post types array
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function setCPTs($cpt) {
        $this->custom_post_types = array_merge( $this->custom_post_types, $cpt );

		return $this;
    }

}
