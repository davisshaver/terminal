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
      'limit'  => 0,
      'starting_count' => 0,
      'add_more_label' => 'Add more',
      'collapsible' => true,
      'children' => array(
        'membership_id' => new Fieldmanager_Datasource_Post( array(
          'query_args' => array(
            'post_type' => 'memberpressproduct'
          ),
        ) ),
        'domains' => new Fieldmanager_Group( array(
          'label' => __( 'Domains', 'terminal' ),
          'limit' => 0,
          'starting_count' => 0,
          'add_more_label' => 'Add domain',
          'sortable' => true,
          'collapsible' => true,
          'children' => array(
            'domain' => new Fieldmanager_Textfield(),
          ),
        ) )
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
