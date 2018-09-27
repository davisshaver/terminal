<?php
/**
 * FM Customizer/membership integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for membership.
 */
class FM_Membership {

	use Singleton;

	/**
	 * Setup.
	 */
	public function setup() {
		add_action( 'fm_beta_customize', array( $this, 'customizer_init' ), 1000 );
	}

	/**
	 * Add a customizer GROUP in a single SECTION for membership.
	 */
	public function customizer_init() {
    $fm = new \Fieldmanager_Group( array(
      'name' => 'terminal_membership_options', 
      'label' => __( 'Membership Options', 'terminal' ),
      'children' => array(
        'membership_page' => new \Fieldmanager_Link( __( 'Membership Info URL', 'terminal' ) ),
        'ad_free_subscription' => new \Fieldmanager_Textfield( __( 'Ad Free Subscription ID', 'terminal' ) ),
        'restricted_memberships' => new \Fieldmanager_Group(
          __( 'Restricted memberships', 'terminal' ),
          array(
            'limit'  => 0,
            'starting_count' => 0,
            'add_more_label' => 'Add more',
            'collapsible' => true,
            'children' => array(
              'membership_id' => new \Fieldmanager_Textfield( __( 'Membership ID', 'terminal' ) ),
              'domains' => new \Fieldmanager_Textfield(
                __( 'Eligible domains (comma-separated)', 'terminal' )
              ),
            )
          )
        )
      )
    ) );
		fm_beta_customize_add_to_customizer( array(
			'section_args' => array(
				'priority'   => 53,
				'capability' => 'edit_posts',
				'title'      => __( 'Membership Options', 'terminal' ),
			),
			'setting_args' => array( 'transport' => 'refresh' ),
		), $fm );
	}
}

FM_Membership::instance();
