<?php
/**
 * FM Customizer/sidebar integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for ads.
 */
class FM_Sidebar {

	use Singleton;

	/**
	 * Setup.
	 */
	public function setup() {
		add_action( 'customize_register', array( $this, 'add_partial' ) );
		add_action( 'fm_beta_customize', array( $this, 'customizer_init' ), 1000 );
	}

	/**
	 * Register sidebar partial.
	 *
	 * @param object $wp_customize Customize object.
	 */
	public function add_partial( $wp_customize ) {
		if ( empty( $wp_customize->selective_refresh ) ) {
			return;
		}
		$wp_customize->selective_refresh->add_partial( 'sidebar_partial', array(
			'selector'            => '#sidebar',
			'settings'            => array( 'terminal_sidebar_options' ),
			'render_callback'     => array( $this, 'buffer_sidebar_template' ),
			'container_inclusive' => true,
		) );
	}

	/**
	 * Buffer template.
	 *
	 * @return string Buffered markup.
	 */
	public function buffer_sidebar_template() {
		ob_start();
		get_template_part( 'partials/sidebar' );
		$var = ob_get_contents();
		ob_end_clean();
		return $var;
	}

	/**
	 * Add a customizer GROUP in a single SECTION for sidebar.
	 */
	public function customizer_init() {
		$fm = new \Fieldmanager_Group(
			'Sidebar Layout Options',
			array(
				'name'     => 'terminal_sidebar_options',
				'children' => array(
					'alignment' => new \Fieldmanager_Select( 'Alignment', array(
						'options' => array(
							'left',
							'right',
							'bottom',
						),
					) ),
				),
			)
		);
		fm_beta_customize_add_to_customizer( array(
			'section_args' => array(
				'priority' => 52,
				'title'    => __( 'Sidebar Options', 'terminal' ),
			),
			'setting_args' => array( 'transport' => 'postMessage' ),
		), $fm );
	}
}

FM_Sidebar::instance();
