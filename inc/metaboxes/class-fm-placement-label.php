<?php
/**
 * FM placement label.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for placement label.
 */
class FM_Placement_Label {

	use Singleton;

	/**
	 * Setup.
	 */
	public function setup() {
		add_action( 'fm_term_terminal-placement', array( $this, 'register_label' ) );
	}

	/**
	 * Register featured image credit.
	 */
	public function register_label() {
		$fm = new \Fieldmanager_Group( array(
			'name'           => 'terminal_placement_options',
			'serialize_data' => false,
			'children'       => array(
				'label'  => new \Fieldmanager_TextField( array(
					'label' => __( 'Label', 'terminal' ),
				) ),
			),
		) );
		$fm->add_term_meta_box( 'Placement Options', 'terminal-placement' );
	}
}

FM_Placement_Label::instance();
