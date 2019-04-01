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
	class Broadstreet_Widget extends \FM_Widget {

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
				'terminal-broadstreet-widget',
				__( 'Broadstreet Ad', 'terminal' )
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
			if ( ! empty( $instance['unit'] ) ) {
				$ad_unit = $instance['unit'];
			} else {
				$ad_unit = '64590';
			}
			if ( ! empty( $instance['width'] ) ) {
				$ad_width = $instance['width'];
			} else {
				$ad_width = '300';
			}
			if ( ! empty( $instance['height'] ) ) {
				$ad_height = $instance['height'];
			} else {
				$ad_height = '250';
			}
			echo terminal_broadstreet_ad( $ad_height, $ad_width, $ad_unit );
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

	register_widget( '\Broadstreet_Widget' );
}
