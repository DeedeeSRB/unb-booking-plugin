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
		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

		add_action('wp_head', array( $this, 'add_js_css' ) );
		//add_action('admin_head', array( $this, 'add_js_css_admin' ) );
	}
	
	public function enqueue() 
	{
		wp_enqueue_style( 'pluginstyle', UNB_PLUGIN_URL . 'assets/mystyles.min.css' );
		wp_enqueue_script( 'pluginscript', UNB_PLUGIN_URL . 'assets/myscript.min.js' );

		wp_enqueue_style( 'bootstrapstyle', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/css/bootstrap.min.css' );
		wp_enqueue_script( 'bootstrapscript', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/js/bootstrap.min.js' );
		wp_enqueue_script( 'bootstrapscriptbudle', UNB_PLUGIN_URL . 'assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js' );

		//wp_localize_script( 'pluginscript', 'admin_url_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function add_js_css()
	{	
		?>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
			
			<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
		<?php
	}
}