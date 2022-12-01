<?php
/**
 * Enqueue
 * 
 * Runs functions to enqueue scripts/styles for the admin or site pages
 *
 * @package    UNBBookingPlugin\Classes
 * @since      1.0.0
 */

namespace UnbBooking\Base;

/**
 * Script/Style Enqueue class
 */
class Enqueue
{
	/**
	 * Add action refrecencing the admin and site load scripts/styles functions
	 * 
	 * @since 1.0.0
	 */
    public function register() 
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

		//add_action('wp_head', array( $this, 'add_js_css' ) );
		//add_action('admin_head', array( $this, 'add_js_css_admin' ) );
	}
	
	/**
	 * Enqueue scripts and styels for the site pages
	 * 
	 * @since 1.0.0
	 */
	public function enqueue() 
	{
		// JQueryCore
		wp_enqueue_script('jquerycore', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', array('jquery') );

		// The custom plugin scripts
		wp_enqueue_script( 'pluginscript', UNB_PLUGIN_URL . 'assets/js/script.js', array('jquery') );

		// JQueryUICore and Css
		wp_enqueue_script('jqueryuicore', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js', array('jquery') );
		wp_enqueue_style( 'jqueryuicss', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css' );

		wp_enqueue_style( 'unb_main_style', UNB_PLUGIN_URL.'/assets/css/main.css' );
		
		// Bootstrap
		wp_enqueue_style( 'bootstrapstyle', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/css/bootstrap.min.css' );
		wp_enqueue_script( 'bootstrapscript', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/js/bootstrap.min.js' );
		wp_enqueue_script( 'bootstrapscriptbudle', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js' );

		// Fontawesome icons
		wp_enqueue_script( 'fontawsome', 'https://use.fontawesome.com/releases/v5.15.4/js/all.js' );

		// A localized value to reference the admin ajax value in our javasript code
		wp_localize_script( 'pluginscript', 'admin_url_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	/**
	 * Enqueue scripts and styels for the admin pages
	 * 
	 * @since 1.0.0
	 */
	public function admin_enqueue() {
		// Bootstrap just for grids and utility styles
		wp_enqueue_style( 'bootstrapgrifstyle', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/css/bootstrap-grid.min.css' );
		wp_enqueue_style( 'bootstraputilitiesstyle', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/css/bootstrap-utilities.min.css' );
		
		// The custom plugin scripts
		wp_enqueue_style( 'pluginstyle', UNB_PLUGIN_URL . 'assets/css/main.css' );
	}
}