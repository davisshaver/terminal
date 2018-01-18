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
				__( 'Broadstreet Sidebar Ad', 'terminal' )
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
			$this->uses += 1;
			if ( ! empty( $args['unit'] ) ) {
				$ad_unit = $args['unit'];
			} else {
				$ad_unit = '64590';
			}
			if ( ! empty( $args['title'] ) ) {
				$ad_title = $args['title'];
			} else {
				$ad_title = __( 'Sponsored By', 'terminal' );
			}
			printf(
				'<div id="%s" class="sidebar-section terminal-broadstreet terminal-broadstreet-sidebar terminal-sidebar-body-font"><div class="sidebar-header terminal-sidebar-header-font">%s</div><div class="terminal-broadstreet-sidebar-interior"><broadstreet-zone zone-id="%s"></broadstreet-zone></div></div>',
				esc_attr( 'terminal-broadstreet-ad-sidebar-' . $this->uses ),
				esc_html( $ad_title ),
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
				'title' => new \Fieldmanager_TextField( __( 'Title Override', 'terminal' ) ),
				'unit'  => new \Fieldmanager_TextField( __( 'Unit Override', 'terminal' ) ),
			];
		}
	}

	register_widget( '\Broadstreet_Widget' );
}
