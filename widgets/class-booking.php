<?php
/**
 * UnbBooking class.
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
namespace UnbBooking\Widgets;

use UnbBooking\CPTs\RegisterCPT;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();
/**
 * Awesomesauce widget class.
 *
 * @since 1.0.0
 */
class showBookingRooms extends Widget_Base {
	/**
	 * Class constructor.
	 *
	 * @param array $data Widget data.
	 * @param array $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		// // Bootstrap 5.0.2
		// wp_register_style( 'unb_booking_bootstrap_style', plugins_url( '/assets/bootstrap-5.0.2-dist/css/bootstrap.min.css', UNB_BOOKING ), array(), '1.0.0' );
		// wp_register_script( 'unb_booking_bootstrap_script', plugins_url( '/assets/bootstrap-5.0.2-dist/js/bootstrap.min.js', UNB_BOOKING ), array('jquery'), '5.0.2' );
		// wp_register_script( 'unb_booking_bootstrap_bundle_script', plugins_url( '/assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js', UNB_BOOKING ), array('jquery'), '5.0.2' );
		
		// // Daterangepicker
		// wp_register_style( 'unb_booking_daterangepicker_style', plugins_url( '/assets/daterangepicker/daterangepicker.css', UNB_BOOKING ), array(), '1.0.0' );
		// wp_register_script( 'unb_booking_daterangepicker_script', plugins_url( '/assets/daterangepicker/daterangepicker.js', UNB_BOOKING ), array('jquery'), '1.0.0' );

		// wp_register_style( 'unb_booking_style', plugins_url( '/assets/css/main.css', UNB_BOOKING ), array(), '1.0.0' );
		// wp_register_script( 'unb_booking_script', plugins_url( '/assets/js/script.js', UNB_BOOKING ), array('jquery'), '1.0.0' );	
	}
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'unb_booking_rooms';
	}
	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'UNB Booking Room', 'unb_booking' );
	}
	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-pencil';
	}
	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'general' );
	}
	
	/**
	 * Enqueue styles.
	 */
	public function get_style_depends() {
		return array( 'unb_booking_style' );
	}

	/**
	 * Enqueue script.
	 */
	public function get_script_depends() {
		return array( 'unb_booking_script' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'UNB Booking Products', 'unb_booking' ),
			)
		);

		require_once UNB_PLUGIN_PATH . 'inc/CPT/RegisterCPT.php';

		foreach ( RegisterCPT::$customPostTypes as $cpt ) {
			$this->add_control(
				$cpt['name'],
				array(
					'label'   => __( $cpt['name'], 'unb_booking' ),
					'type'    => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'your-plugin' ),
					'label_off' => esc_html__( 'Hide', 'your-plugin' ),
					'return_value' => 'yes',
					'default' => 'yes',
				)
			);
		}
		
		$this->end_controls_section();
	}
	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		foreach ( RegisterCPT::$customPostTypes as $cpt ) {
			$args = array(
				'post_type' => strtolower( $cpt['singular_name'] ),
				'post_status' => array('publish'),
				//'posts_per_page' => 6,
			);
			$query = new \WP_Query( $args );
			$cpt_posts[$cpt['name']] = $query->posts;
		}
		
		return include_once UNB_PLUGIN_PATH . '/templates/booking-widget.php';
	}
	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {
		
	}
}