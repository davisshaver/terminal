<?php
/**
 * FM Customizer/layout integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for layout.
 */
class FM_Layout {

	use Singleton;

	/**
	 * Setup.
	 */
	public function setup() {
		add_action( 'fm_beta_customize', array( $this, 'customizer_init' ), 1000 );
	}

	/**
	 * Add a customizer GROUP in a single SECTION for ads.
	 */
	public function customizer_init() {
		$fm = new \Fieldmanager_Group(
			'Layout Options',
			array(
				'name'     => 'terminal_layout_options',
				'children' => array(
					'single_meta_position' => new \Fieldmanager_Select(
						'Single - Byline Position',
						array(
							'default_value' => 'top',
							'options'       => array( 'top', 'middle', 'bottom', 'none' ),
						)
					),
					'loop_meta_position'   => new \Fieldmanager_Select(
						'Loop - Byline Position',
						array(
							'default_value' => 'top',
							'options'       => array( 'top', 'middle', 'bottom', 'none' ),
						)
					),
				),
			)
		);
		fm_beta_customize_add_to_customizer( array(
			'section_args' => array(
				'priority'   => 54,
				'capability' => 'edit_posts',
				'title'      => __( 'Layout Options', 'terminal' ),
			),
			'setting_args' => array( 'transport' => 'refresh' ),
		), $fm );
	}
}

FM_Layout::instance();
