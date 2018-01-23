<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * Category Widget.
	 */
	class Category_Widget extends \FM_Widget {

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
				'terminal-category-widget',
				__( 'Category Posts', 'terminal' )
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
			printf(
				'<div></div>'
			);
		}

		/**
		 * Define the fields that should appear in the widget.
		 *
		 * @return array Fieldmanager fields.
		 */
		protected function fieldmanager_children() {
			return [
				'number'   => new \Fieldmanager_Select( 'Number to show', array(
					'default_value' => 3,
					'options'       => array(
						2,
						3,
						4,
						5,
					),
				) ),
				'category' => new \Fieldmanager_Select( array(
					'datasource' => new \Fieldmanager_Datasource_Term( array(
						'taxonomy' => 'category',
					) ),
				) ),
			];
		}
	}

	register_widget( '\Category_Widget' );
}
