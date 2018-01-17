<?php
/**
 * FM Customizer/fonts integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for fonts.
 */
class FM_Fonts {

	use Singleton;

	/**
	 * Setup.
	 */
	public function setup() {
		add_action( 'fm_beta_customize', array( $this, 'customizer_init' ), 1000 );
	}

	/**
	 * Add a customizer GROUP in a single SECTION for fonts.
	 */
	public function customizer_init() {

		$font_slots = array(
			'body'           => __( 'Body', 'terminal' ),
			'sidebar_body'   => __( 'Sidebar Body', 'terminal' ),
			'sidebar_header' => __( 'Sidebar Header', 'terminal' ),
			'single_meta'    => __( 'Single Meta', 'terminal' ),
			'utility'        => __( 'Utility', 'terminal' ),
			'headline'       => __( 'Headline', 'terminal' ),
		);
		$children = array();
		foreach ( $font_slots as $slot => $name ) {
			$children[] = new \Fieldmanager_Group( array(
				'name'     => $slot,
				'children' => array(

					'size'      => new \Fieldmanager_Textfield( $name . ' Font Size', array( 'default_value' => 'inherit' ) ),
					'transform' => new \Fieldmanager_Select( $name . ' Transform', array( 'options' => array( 'inherit', 'lowercase', 'uppercase', 'capitalize' ) ) ),
					'font'      => new \Fieldmanager_Select( $name . ' Font Family', array(
						'options' => array(
							'inherit',
							'"Arial Black", Gadget, sans-serif',
							'"Comic Sans MS", cursive, sans-serif',
							'"Courier New", Courier, monospace',
							'"Lucida Console", Monaco, monospace',
							'"Lucida Sans Unicode", "Lucida Grande", sans-serif',
							'"Palatino Linotype", "Book Antiqua", Palatino, serif',
							'"Times New Roman", Times, serif',
							'"Trebuchet MS", Helvetica, sans-serif',
							'Arial, Helvetica, sans-serif',
							'Georgia, Cambria, "Times New Roman", Times, serif',
							'Impact, Charcoal, sans-serif',
							'Tahoma, Geneva, sans-serif',
							'Verdana, Geneva, sans-serif',
						),
					) ),
				),
			) );
		}
		$fm = new \Fieldmanager_Group( array(
			'name'     => 'option_fields',
			'children' => $children,
		) );
		fm_beta_customize_add_to_customizer( array(
			'section_args' => array(
				'capability' => 'edit_posts',
				'title'      => __( 'Typography', 'terminal' ),
				'priority'   => 15,
			),
			'setting_args' => array(
				'type'      => 'theme_mod',
				'transport' => 'postMessage',
			),
		), $fm );
	}
}

FM_Fonts::instance();

