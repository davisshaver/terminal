<?php
/**
 * Apps data helper.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for data loading.
 */
class Data_Layer {

	use Singleton;

	/**
	 * Get post categories.
	 */
	private function get_post_categories() {
		$post_categories = wp_get_post_categories( get_the_ID() );
		$cats            = array();
		foreach ( $post_categories as $c ) {
			$cat    = get_category( $c );
			$cats[] = array(
				'name' => $cat->name,
				'slug' => $cat->slug,
			);
		}
		return $cats;
	}

	/**
	 * Get single data layer.
	 */
	public function get_single_data_layer() {
		if ( ! is_singular( terminal_get_post_types() ) ) {
			return array();
		}
		return array(
			'author_name'        => get_the_author(),
			'author_id'          => get_the_author_meta( 'ID' ),
			'post_categories'    => $this->get_post_categories(),
			'post_has_thumbnail' => has_post_thumbnail(),
			'post_title'         => the_title_attribute( array( 'echo' => false ) ),
			'post_id'            => get_the_ID(),
			'post_type'          => terminal_get_post_type( get_post() ),
		);
	}
}

add_action( 'after_setup_theme', [ '\Terminal\Data_Layer', 'instance' ] );
