<?php
/**
 * FM Customizer/sponsors integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for sponsors.
 */
class FM_Sponsors {

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
			'Sponsor Options',
			array(
				'name'     => 'terminal_sponsor_options',
				'serialize_data' => false,
				'children' => array(
					'enable_sponsors' => new \Fieldmanager_Checkbox( 'Enable inline ads' ),
					'tier_one_sponsors' => new \Fieldmanager_Group(
						__( 'Tier one sponsors', 'terminal' ),
						array(
							'limit'  => 0,
							'starting_count' => 0,
							'add_more_label' => 'Add more',
							'collapsible' => true,
							'children' => array(
								'sponsor_media' => new \Fieldmanager_Media( 'Sponsor Media' ),
								'sponsor_link' => new \Fieldmanager_Link( 'Sponsor Link' ),
								'sponsor_name' => new \Fieldmanager_Textfield( __( 'Sponsor Name', 'terminal' ) ),
							)
						)
					),
					'tier_two_sponsors' => new \Fieldmanager_Group(
						__( 'Tier two sponsors', 'terminal' ),
						array(
							'limit'  => 0,
							'starting_count' => 0,
							'add_more_label' => 'Add more',
							'collapsible' => true,
							'children' => array(
								'sponsor_media' => new \Fieldmanager_Media( 'Sponsor Media' ),
								'sponsor_link' => new \Fieldmanager_Link( 'Sponsor Link' ),
								'sponsor_name' => new \Fieldmanager_Textfield( __( 'Sponsor Name', 'terminal' ) ),
							)
						)
					),
					'tier_three_sponsors' => new \Fieldmanager_Group(
						__( 'Tier three sponsors', 'terminal' ),
						array(
							'limit'  => 0,
							'starting_count' => 0,
							'add_more_label' => 'Add more',
							'collapsible' => true,
							'children' => array(
								'sponsor_media' => new \Fieldmanager_Media( 'Sponsor Media' ),
								'sponsor_link' => new \Fieldmanager_Link( 'Sponsor Link' ),
								'sponsor_name' => new \Fieldmanager_Textfield( __( 'Sponsor Name', 'terminal' ) ),
							)
						)
					),
					'tier_four_sponsors' => new \Fieldmanager_Group(
						__( 'Tier four sponsors', 'terminal' ),
						array(
							'limit'  => 0,
							'starting_count' => 0,
							'add_more_label' => 'Add more',
							'collapsible' => true,
							'children' => array(
								'sponsor_media' => new \Fieldmanager_Media( 'Sponsor Media' ),
								'sponsor_link' => new \Fieldmanager_Link( 'Sponsor Link' ),
								'sponsor_name' => new \Fieldmanager_Textfield( __( 'Sponsor Name', 'terminal' ) ),
							)
						)
					),
					'tier_five_sponsors' => new \Fieldmanager_Group(
						__( 'Tier five sponsors', 'terminal' ),
						array(
							'limit'  => 0,
							'starting_count' => 0,
							'add_more_label' => 'Add more',
							'collapsible' => true,
							'children' => array(
								'sponsor_media' => new \Fieldmanager_Media( 'Sponsor Media' ),
								'sponsor_link' => new \Fieldmanager_Link( 'Sponsor Link' ),
								'sponsor_name' => new \Fieldmanager_Textfield( __( 'Sponsor Name', 'terminal' ) ),
							)
						)
					)
				),
			)
		);
		fm_beta_customize_add_to_customizer( array(
			'section_args' => array(
				'priority'   => 53,
				'capability' => 'edit_posts',
				'title'      => __( 'Sponsor Options', 'terminal' ),
			),
			'setting_args' => array( 'transport' => 'refresh' ),
		), $fm );
	}
}

FM_Sponsors::instance();
