<?php
/**
 * Enqueue class.
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

namespace UnbBooking\Base;

class Enqueue
{
    public function register() 
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

		//add_action('wp_head', array( $this, 'add_js_css' ) );
		//add_action('admin_head', array( $this, 'add_js_css_admin' ) );
	}
	
	public function enqueue() 
	{
		wp_enqueue_script('jquerycore', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', array('jquery') );

		wp_enqueue_script( 'pluginscript', UNB_PLUGIN_URL . 'assets/js/script.js', array('jquery') );

		wp_enqueue_script('jqueryuicore', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js', array('jquery') );
		wp_enqueue_style( 'jqueryuicss', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css' );
	 
		wp_enqueue_style( 'bootstrapstyle', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/css/bootstrap.min.css' );
		wp_enqueue_script( 'bootstrapscript', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/js/bootstrap.min.js' );
		wp_enqueue_script( 'bootstrapscriptbudle', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js' );

		wp_enqueue_script( 'fontawsome', 'https://use.fontawesome.com/releases/v5.15.4/js/all.js' );

		wp_localize_script( 'pluginscript', 'admin_url_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function admin_enqueue() {
		wp_enqueue_style( 'bootstrapgrifstyle', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/css/bootstrap-grid.min.css' );
		wp_enqueue_style( 'bootstraputilitiesstyle', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/css/bootstrap-utilities.min.css' );
		wp_enqueue_style( 'pluginstyle', UNB_PLUGIN_URL . 'assets/css/main.css' );
	}
}