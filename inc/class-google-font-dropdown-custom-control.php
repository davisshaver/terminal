<?php
/**
 * Custom class for Google Font integration.
 *
 * @package Terminal
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * A class to create a dropdown for all google fonts
 */
class Google_Font_Dropdown_Custom_Control extends WP_Customize_Control {
	/**
	 * Available fonts.
	 *
	 * @var $fonts array
	 */
	private $fonts = false;

	/**
	 * Constructor.
	 *
	 * Supplied `$args` override class property defaults.
	 *
	 * If `$args['settings']` is not defined, use the $id as the setting ID.
	 *
	 * @since 3.4.0
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    {
	 *     Optional. Arguments to override class property defaults.
	 *
	 *     @type int                  $instance_number Order in which this instance was created in relation
	 *                                                 to other instances.
	 *     @type WP_Customize_Manager $manager         Customizer bootstrap instance.
	 *     @type string               $id              Control ID.
	 *     @type array                $settings        All settings tied to the control. If undefined, `$id` will
	 *                                                 be used.
	 *     @type string               $setting         The primary setting for the control (if there is one).
	 *                                                 Default 'default'.
	 *     @type int                  $priority        Order priority to load the control. Default 10.
	 *     @type string               $section         Section the control belongs to. Default empty.
	 *     @type string               $label           Label for the control. Default empty.
	 *     @type string               $description     Description for the control. Default empty.
	 *     @type array                $choices         List of choices for 'radio' or 'select' type controls, where
	 *                                                 values are the keys, and labels are the values.
	 *                                                 Default empty array.
	 *     @type array                $input_attrs     List of custom input attributes for control output, where
	 *                                                 attribute names are the keys and values are the values. Not
	 *                                                 used for 'checkbox', 'radio', 'select', 'textarea', or
	 *                                                 'dropdown-pages' control types. Default empty array.
	 *     @type array                $json            Deprecated. Use WP_Customize_Control::json() instead.
	 *     @type string               $type            Control type. Core controls include 'text', 'checkbox',
	 *                                                 'textarea', 'radio', 'select', and 'dropdown-pages'. Additional
	 *                                                 input types such as 'email', 'url', 'number', 'hidden', and
	 *                                                 'date' are supported implicitly. Default 'text'.
	 * }
	 */
	public function __construct( $manager, $id, $args = array() ) {
			$this->fonts = $this->get_fonts();
			parent::__construct( $manager, $id, $args );
	}

	/**
	 * Render the content of the category dropdown.
	 */
	public function render_content() {
		if ( ! empty( $this->fonts ) ) { ?>
			<label>
					<span class="customize-category-select-control"><?php echo esc_html( $this->label ); ?></span>
					<select <?php $this->link(); ?>>
							<?php
							foreach ( $this->fonts as $k => $v ) {
									printf( '<option value="%s" %s>%s</option>', esc_attr( $k ), selected( $this->value(), $k, false ), esc_html( $v->family ) );
							}
							?>
					</select>
			</label>
		<?php }
	}

	/**
	 * Get the google fonts from the API or in the cache
	String
	 */
	public function get_fonts() {
			$cachetime = 86400 * 7;
			if(file_exists($fontFile) && $cachetime < filemtime($fontFile))
			{
					$content = json_decode(file_get_contents($fontFile));
			} else {
					$googleApi = 'https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&key={API_KEY}';
					$fontContent = wp_remote_get( $googleApi, array('sslverify'   => false) );
					$fp = fopen($fontFile, 'w');
					fwrite($fp, $fontContent['body']);
					fclose($fp);
					$content = json_decode($fontContent['body']);
			}
			if( $amount == 'all' )
			{
					return $content->items;
			} else {
					return array_slice($content->items, 0, $amount);
			}
	}
 }
?>
