<?php
/**
 * FM Customizer/bylines integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for bylines.
 */
class FM_Bylines {

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
			'Byline Options',
			array(
				'name'     => 'terminal_byline_options',
				'children' => array(
					'default_gravatar'     => new \Fieldmanager_Media( 'Default author image' ),
					'time_ago_format'      => new \Fieldmanager_Select( 'Relative or absolute post date', array( 'options' => array( 'relative', 'absolute' ) ) ),
					'hide_by'              => new \Fieldmanager_Checkbox( 'Hide \'By\' in byline' ),
					'loop_hide_avatar'     => new \Fieldmanager_Checkbox( 'Loop - Disable Avatar' ),
					'loop_avatar_size'     => new \Fieldmanager_Select( 'Loop - Avatar Size', array( 'options' => array( 25, 30, 40, 50, 60, 75, 100 ) ) ),
					'loop_hide_date'       => new \Fieldmanager_Checkbox( 'Loop - Disable Publish Date' ),
					'loop_hide_author'     => new \Fieldmanager_Checkbox( 'Loop - Disable Author' ),
					'loop_hide_category'   => new \Fieldmanager_Checkbox( 'Loop - Disable Categories' ),
					'loop_hide_comments'   => new \Fieldmanager_Checkbox( 'Loop - Disable Comments' ),
					'loop_hide_edit'       => new \Fieldmanager_Checkbox( 'Loop - Disable Edit Button for Editors' ),
					'single_hide_avatar'   => new \Fieldmanager_Checkbox( 'Single - Disable Avatar' ),
					'single_avatar_size'   => new \Fieldmanager_Select( 'Single - Avatar Size', array( 'options' => array( 25, 30, 40, 50, 60, 75, 100 ) ) ),
					'single_hide_date'     => new \Fieldmanager_Checkbox( 'Single - Disable Publish Date' ),
					'single_hide_author'   => new \Fieldmanager_Checkbox( 'Single - Disable Author' ),
					'single_hide_category' => new \Fieldmanager_Checkbox( 'Single - Disable Categories' ),
					'single_hide_comments' => new \Fieldmanager_Checkbox( 'Single - Disable Comments' ),
					'single_hide_edit'     => new \Fieldmanager_Checkbox( 'Single - Disable Edit Button for Editors' ),
				),
			)
		);
		fm_beta_customize_add_to_customizer( array(
			'section_args' => array(
				'priority'   => 54,
				'capability' => 'edit_posts',
				'title'      => __( 'Byline Options', 'terminal' ),
			),
			'setting_args' => array( 'transport' => 'refresh' ),
		), $fm );
	}
}

FM_Bylines::instance();
