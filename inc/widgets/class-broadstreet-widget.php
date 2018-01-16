<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */

namespace Terminal;

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * Demo Widget.
	 */
	class Broadstreet_Widget extends \FM_Widget {

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
			echo 'ad';
		}

		/**
		 * Define the fields that should appear in the widget.
		 *
		 * @return array Fieldmanager fields.
		 */
		protected function fieldmanager_children() {
			return [
				'media' => new \Fieldmanager_Media( __( 'Image', 'text-domain' ) ),
				'autocomplete' => new \Fieldmanager_Autocomplete( [
					'label' => __( 'Autocomplete', 'text-domain' ),
					'datasource' => new \Fieldmanager_Datasource_Post,
				] ),
				'repeatable' => new \Fieldmanager_TextField( [
					'label' => __( 'Repeatable', 'text-domain' ),
					'limit' => 0,
				] ),
			];
		}
	}

	add_action( 'widgets_init', function() {
		register_widget( '\Terminal\Broadstreet_Widget' );
	} );
}
