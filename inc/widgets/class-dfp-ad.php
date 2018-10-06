<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * Demo Widget.
	 */
	class DFP_Widget extends \FM_Widget {

		/**
		 * Track uses as printing.
		 *
		 * @var int Uses.
		 */
		private $uses = 0;

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-dfp-widget',
				__( 'DFP Ad', 'terminal' )
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			if ( ! empty( $args['unit'] ) ) {
				$ad_unit = $args['unit'];
			} else {
				$ad_unit = '7383568527';
			}
			if ( ! empty( $args['width'] ) ) {
				$ad_width = $args['width'];
			} else {
				$ad_width = '320';
			}
			if ( ! empty( $args['height'] ) ) {
				$ad_height = $args['height'];
			} else {
				$ad_height = '50';
			}
			printf(
				'<amp-ad width="%s" height="%s" type="adsense" data-ad-client="ca-pub-0809625376938310" data-ad-slot="%s" layout="fixed"></amp-ad>',
				esc_attr( $ad_width ),
				esc_attr( $ad_height ),
				esc_attr( $ad_unit )
			);
		}

		/**
		 * Define the fields that should appear in the widget.
		 *
		 * @return array Fieldmanager fields.
		 */
		protected function fieldmanager_children() {
			return [
				'unit'   => new \Fieldmanager_TextField( __( 'Unit Override', 'terminal' ) ),
				'height' => new \Fieldmanager_TextField( __( 'Height Override', 'terminal' ) ),
				'width'  => new \Fieldmanager_TextField( __( 'Width Override', 'terminal' ) ),
			];
		}
	}

	register_widget( '\DFP_Widget' );
}
