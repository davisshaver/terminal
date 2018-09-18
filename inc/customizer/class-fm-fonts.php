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
			'font-family' => 'default',
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
		'garamond' => array(
			'default' => false,
			'font-family' => 'EB Garamond, serif',
			'google' => 'EB+Garamond',
		),
		'lato' => array(
			'default' => false,
			'font-family' => 'Lato, sans-serif',
			'google' => 'Lato',
		),
		'slabo' => array(
			'default' => false,
			'font-family' => '"Slabo 27px", serif',
			'google' => 'Slabo+27px',
		),
		'ubuntu' => array(
			'default' => false,
			'font-family' => 'Ubuntu, sans-serif',
			'google' => 'Ubuntu',
		),
		'alegreya' => array(
			'default' => false,
			'font-family' => 'Alegreya, serif',
			'google' => 'Alegreya',
		),
		'alegreya-sans' => array(
			'default' => false,
			'font-family' => 'Alegreya, sans-serif',
			'google' => 'Alegreya+Sans',
		),
		'merriweather' => array(
			'default' => false,
			'font-family' => 'Merriweather, serif',
			'google' => 'Merriweather',
		),
		'merriweather-sans' => array(
			'default' => false,
			'font-family' => 'Merriweather, sans-serif',
			'google' => 'Merriweather+Sans',
		),
		'nunito' => array(
			'default' => false,
			'font-family' => 'Nunito, sans-serif',
			'google' => 'Nunito',
		),
		'nunito-sans' => array(
			'default' => false,
			'font-family' => 'Nunito, sans-serif',
			'google' => 'Nunito+Sans',
		),
		'roboto' => array(
			'default' => false,
			'font-family' => 'Roboto, sans-serif',
			'google' => 'Roboto',
		),
		'roboto-slab' => array(
			'default' => false,
			'font-family' => 'Roboto Slab, serif',
			'google' => 'Roboto+Slab',
		),
		'roboto-mono' => array(
			'default' => false,
			'font-family' => 'Roboto Mono, monospace',
			'google' => 'Roboto+Mono',
		),
		'quattrocento' => array(
			'default' => false,
			'font-family' => 'Quattrocento, serif',
			'google' => 'Quattrocento',
		),
		'quattrocento-sans' => array(
			'default' => false,
			'font-family' => 'Quattrocento Sans, sans-serif',
			'google' => 'Quattrocento+Sans',
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
			'utility'        => __( 'Utility', 'terminal' ),
			'cta_button'     => __( 'Call to Action', 'terminal' ),
			'cta_tagline'    => __( 'Tagline', 'terminal' ),
			'nav'            => __( 'Nav', 'terminal' ),
			'headline'       => __( 'Headline', 'terminal' ),
			'head_featured'  => __( 'Headline Featured', 'terminal' ),
			'index_meta'     => __( 'Index Meta', 'terminal' ),
			'loop_header'    => __( 'Loop Header', 'terminal' ),
			'sidebar_body'   => __( 'Sidebar Body', 'terminal' ),
			'sidebar_header' => __( 'Sidebar Header', 'terminal' ),
			'single_meta'    => __( 'Single Meta', 'terminal' ),
			'share'          => __( 'Social share', 'terminal' ),
			'card_title'     => __( 'Card title', 'terminal' ),
			'footer'         => __( 'Footer', 'terminal' ),
			'excerpt'        => __( 'Excerpt', 'terminal' ),
		);
		$children   = array();
		$colors     = array();
		foreach ( $font_slots as $slot => $name ) {
			$children[ "${slot}_anti_aliasing" ] = new \Fieldmanager_Checkbox( $name . ' Anti-Aliasing' );
			$children[ "${slot}_line_height" ]   = new \Fieldmanager_Select( $name . ' Line Height', array(
				'options' => array(
					'default',
					'0.8',
					'0.9',
					'1.0',
					'1.1',
					'1.2',
					'1.3',
					'1.4',
					'1.5',
				),
			) );
			$children[ "${slot}_size" ]          = new \Fieldmanager_Select( $name . ' Font Size', array(
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
			$children[ "${slot}_weight" ]        = new \Fieldmanager_Select( $name . ' Font Weight', array(
				'options' => array(
					'default',
					'400',
					'700',
				),
			) );
			$children[ "${slot}_color" ]         = new \Fieldmanager_Colorpicker( $name . ' Text Color' );
			$children[ "${slot}_style" ]         = new \Fieldmanager_Select( $name . ' Font Style', array( 'options' => array( 'default', 'italic' ) ) );
			$children[ "${slot}_transform" ]     = new \Fieldmanager_Select( $name . ' Text Transform', array( 'options' => array( 'default', 'lowercase', 'uppercase', 'capitalize' ) ) );
			$children[ "${slot}_font" ]          = new \Fieldmanager_Select( $name . ' Font Family', array(
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

