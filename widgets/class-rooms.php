<?php
/**
 * ShowBookingRooms class.
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
 * Show Booking Rooms widget class.
 *
 * @since 1.0.0
 */
class ShowBookingRooms extends Widget_Base {

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
		return __( 'UNB Rooms List', 'unb_booking' );
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
		return 'fas fa-home';
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
				'label' => __( 'UNB Booking Rooms', 'unb_booking' ),
			)
		);

		$this->add_control(
			'mum_of_rooms',
			array(
				'label'   => __( 'Number of Rooms', 'unb_booking' ),
				'type' => Controls_Manager::NUMBER,
				'placeholder' => 'eg. 6',
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 6,
			)
		);
		
		
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
		$args = array(
			'post_type' => 'room',
			'post_status' => array('publish'),
			'posts_per_page' => $settings['mum_of_rooms'],
		);
		$query = new \WP_Query( $args );
		$posts = $query->posts;
		
		return include_once UNB_PLUGIN_PATH . '/templates/widgets/rooms-booking-widget.php';
	}
}