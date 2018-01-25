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
	 * Private fonts array.
	 *
	 * @var $fonts
	 */
	public $fonts = array(
		'default' => array(
			'default' => true,
			'font-family' => 'inherit',
			'google' => false,
		),
		'arial-black' => array(
			'default' => false,
			'font-family' => 'Arial Black, Gadget, sans-serif',
			'google' => false,
		),
		'comic-sans' => array(
			'default' => false,
			'font-family' => 'Comic Sans MS, cursive, sans-serif',
			'google' => false,
		),
		'courier-new' => array(
			'default' => false,
			'font-family' => 'Courier New, Courier, monospace',
			'google' => false,
		),
		'lucida-console' => array(
			'default' => false,
			'font-family' => 'Lucida Console, Monaco, monospace',
			'google' => false,
		),
		'lucida-sans' => array(
			'default' => false,
			'font-family' => 'Lucida Sans Unicode, Lucida Grande, sans-serif',
			'google' => false,
		),
		'palatino' => array(
			'default' => false,
			'font-family' => 'Palatino Linotype, Book Antiqua, Palatino, serif',
			'google' => false,
		),
		'times-new-roman' => array(
			'default' => false,
			'font-family' => 'Times New Roman, Times, serif',
			'google' => false,
		),
		'helvetica' => array(
			'default' => false,
			'font-family' => 'Trebuchet MS, Helvetica, sans-serif',
			'google' => false,
		),
		'georgia' => array(
			'default' => false,
			'font-family' => 'Georgia, Cambria, Times New Roman, Times, serif',
			'google' => false,
		),
		'impact' => array(
			'default' => false,
			'font-family' => 'Impact, Charcoal, sans-serif',
			'google' => false,
		),
		'tahoma' => array(
			'default' => false,
			'font-family' => 'Tahoma, Geneva, sans-serif',
			'google' => false,
		),
		'verdana' => array(
			'default' => false,
			'font-family' => 'Verdana, Geneva, sans-serif',
			'google' => false,
		),
	);

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
			'index_meta'     => __( 'Index Meta', 'terminal' ),
			'utility'        => __( 'Utility', 'terminal' ),
			'headline'       => __( 'Headline', 'terminal' ),
			'tagline'        => __( 'Tagline', 'terminal' ),
			'cta_button'     => __( 'Call to Action', 'terminal' ),
			'loop_header'    => __( 'Loop Header', 'terminal' ),
			'share'          => __( 'Social share', 'terminal' ),
		);
		$children   = array();
		$colors     = array();
		foreach ( $font_slots as $slot => $name ) {
			$children[ "${slot}_size" ]      = new \Fieldmanager_Select( $name . ' Font Size', array(
				'options' => array(
					'default',
					'12px',
					'14px',
					'16px',
					'18px',
					'20px',
					'22px',
					'24px',
					'26px',
					'28px',
					'30px',
					'32px',
					'34px',
					'36px',
					'38px',
					'40px',
				),
			) );
			$children[ "${slot}_weight" ]    = new \Fieldmanager_Select( $name . ' Font Weight', array(
				'options' => array(
					'default',
					'400',
					'700',
				),
			) );
			$children[ "${slot}_style" ]     = new \Fieldmanager_Select( $name . ' Font Style', array( 'options' => array( 'default', 'italic' ) ) );
			$children[ "${slot}_transform" ] = new \Fieldmanager_Select( $name . ' Text Transform', array( 'options' => array( 'default', 'lowercase', 'uppercase', 'capitalize' ) ) );
			$children[ "${slot}_color" ]     = new \Fieldmanager_Colorpicker( $name . ' Text Color' );
			$children[ "${slot}_font" ]      = new \Fieldmanager_Select( $name . ' Font Family', array(
				'options' => array_keys( $this->fonts ),
			) );
		}
		$fm = new \Fieldmanager_Group( array(
			'name'     => 'typography',
			'children' => $children,
		) );
		fm_beta_customize_add_to_customizer( array(
			'section_args' => array(
				'capability' => 'edit_posts',
				'title'      => __( 'Typography', 'terminal' ),
				'priority'   => 50,
			),
			'setting_args' => array(
				'type'      => 'theme_mod',
				'transport' => 'refresh',
			),
		), $fm );
	}
}

FM_Fonts::instance();

