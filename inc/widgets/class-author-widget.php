<?php
/**
 * FM Widget integration.
 *
 * @package Terminal
 */

if ( class_exists( '\FM_Widget' ) ) {

	/**
	 * Author Widget.
	 */
	class Author_Widget extends \FM_Widget {

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'terminal-author-widget',
				__( 'Author Posts (Hidden on Index Pages)', 'terminal' )
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
			if ( ! is_singular() ) {
				return;
			}
			$author_query = new \WP_Query( array(
				'author'              => get_the_author_meta( 'ID' ),
				'posts_per_page'      => 3,
				'ignore_sticky_posts' => true,
				'post__not_in'        => array( get_the_ID() ),
			) );
			if ( $author_query->have_posts() ) :
				// phpcs:ignore
				echo $args['before_widget'];
				printf(
					'%s %s %s %s',
					$args['before_title'],
					esc_html( __( 'More by ', 'terminal' ) ),
					get_the_author_meta( 'first_name' ),
					$args['after_title']
				);
				echo '<div class="category-widget">';
				while ( $author_query->have_posts() ) :
					$author_query->the_post();
					if ( 0 === $author_query->current_post ) {
						get_template_part( 'partials/post-widget-featured' );
					} else {
						get_template_part( 'partials/post-widget' );
					}
				endwhile;
				echo '</div>';
				// phpcs:ignore
				echo $args['after_widget'];
			endif;
			wp_reset_postdata();
			// phpcs:ignore
		}

		/**
		 * Define the fields that should appear in the widget.
		 *
		 * @return array Fieldmanager fields.
		 */
		protected function fieldmanager_children() {
			return [];
		}
	}

	register_widget( '\Author_Widget' );
}
