<?php
/**
 * Newspack Integration.
 *
 * @package Terminal
 */

namespace Terminal;

/**
 * Class wrapper for Newspack.
 */
class Newspack {

    use Singleton;

    /**
     * Setup actions.
     */
    public function setup() {
        register_sidebar(
            [
                'name'          => __( 'After header', 'terminal' ),
                'id'            => 'after-header',
                'description'   => __( 'Add widgets here to appear after header.', 'terminal' ),
                'before_widget' => '<section id="%1$s" class="after-header widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );
        add_action( 'after_header', [ $this, 'do_after_header' ] );
        add_shortcode( 'terminal-ad', [ $this, 'print_ad' ] );
        add_shortcode( 'terminal-mailchimp', [ $this, 'mailchimp_print' ] );
        add_action( 'before_footer', [ $this, 'print_sponsors_module' ] );
        add_filter( 'newspack-caption', [ $this, 'filter_newspack_caption' ] );
        add_filter( 'newspack-caption-exists', [ $this, 'filter_newspack_caption_exists' ] );
        add_filter( 'wp_enqueue_scripts', function() {
            wp_enqueue_style( TERMINAL_APP, plugin_dir_url( dirname( __FILE__ ) ) . '/client/build/index.css', array(), TERMINAL_VERSION );
            if ( function_exists( 'newspack_is_amp' ) && ! newspack_is_amp() ) {
                wp_enqueue_script( 'terminal-broadstreet', 'https://static.lebtown.com/init-2.min.js' );
                wp_add_inline_script( 'terminal-broadstreet', "broadstreet.watch({ domain: 'sponsors.lebtown.com' })" );
            }
            wp_enqueue_script( 'amp-mustache' );
            global $wp_query;
            $enqueue_amp_form = true;
            if (
                'memberpressproduct' === $wp_query->get( 'post_type', false ) ||
                in_array(
                    $wp_query->get( 'pagename', false ),
                    array(
                        'affiliate-login',
                        'affiliate-dashboard',
                        'affiliate-signup',
                        'login',
                        'account',
                        'cart',
                        'checkout',
                        'autumn-happy-hour',
                        'press-releases',
                        'job',
                        '2x-impact-fund-nomination-form',
                        'church-listings',
                        'job-listings',
                    ),
                    true
                ) ||
                is_single( 85009 )
            ) {
                $enqueue_amp_form = false;
            }
            if ( $enqueue_amp_form ) {
                wp_enqueue_script( 'amp-form' );
            }
        } );
        add_action( 'entry_meta', [ $this, 'print_reading_time' ] );
    }

    /**
     * Call reading time shortcode.
     */
    public function print_reading_time() {
        if ( get_post_type() === 'post' ) {
            echo do_shortcode( '[rt_reading_time label="" postfix="min read" postfix_singular="min read"]' );
        }
    }


    public function filter_newspack_caption_exists() {
        return (bool) $this->get_featured_image_caption();
    }

    public function filter_newspack_caption( $caption ) {
        $featured_image_caption = $this->get_featured_image_caption();
        if ( ! empty( $featured_image_caption ) ) {
            return $featured_image_caption;
        }
        return $caption;
    }

    /**
     * Template function to get featured image caption/credit (if available).
     *
     * @return mixed Caption/credit value.
     */
    public function get_featured_image_caption() {
        $return  = '';
        $caption = get_post_meta( get_the_ID(), 'terminal_featured_meta_caption', true );
        $credit  = get_post_meta( get_the_ID(), 'terminal_featured_meta_credit', true );
        if ( ! empty( $credit ) || ! empty( $caption ) ) {
            $caption = ! empty( $caption ) ? $caption : '';
            $return = sprintf(
                '<div class="terminal-featured-caption">%s</div>',
                wp_kses_post( $caption )
            );
            if ( ! empty( $credit ) ) {
                $return = str_replace(
                    '</p>',
                    sprintf(
                        ' <span class="terminal-featured-credit">%s</span></p>',
                        esc_html( $credit )
                    ),
                    $return
                );
            }
            return $return;
        }
        return $return;
    }

    /**
     * Shortcode wrapper for mailchimp signup.
     */
    public function mailchimp_print() {
        return terminal_get_template_part( 'signup-small' );
    }

    /**
     * Print ad.
     */
    public function print_ad( $atts ) {
        if ( ! empty( $atts['zone'] ) ) {
            $ad_unit = $atts['zone'];
        } else {
            $ad_unit = '70108';
        }
        if ( ! empty( $atts['amp_unit'] ) ) {
            $amp_unit = $atts['unit'];
        } else {
            $amp_unit = '70108';
        }
        if ( ! empty( $atts['amp_disable'] ) ) {
            $amp_disable = true;
        } else {
            $amp_disable = false;
        }
        if ( ! empty( $atts['width'] ) ) {
            $ad_width = $atts['width'];
        } else {
            $ad_width = '300';
        }
        if ( ! empty( $atts['height'] ) ) {
            $ad_height = $atts['height'];
        } else {
            $ad_height = '250';
        }
        if ( ! empty( $atts['disable_header'] ) ) {
            $header = false;
        } else {
            $header = __( 'Advertisement', 'terminal' );
        }
        if ( ! empty( $atts['keywords'] ) ) {
            $keywords = __( $atts['keywords'] );
        } else {
            $keywords = '';
        }
        return terminal_broadstreet_ad(
            $ad_height,
            $ad_width,
            $ad_unit,
            $amp_unit,
            $header,
            $amp_disable,
            $keywords,
            $ad_height,
            $ad_width
        );
    }

    /**
     * Do after header actions.
     */
    public function do_after_header() {
        dynamic_sidebar( 'after-header' );
    }

    /**
     * Print sponsors module.
     */
    public function print_sponsors_module() {
        global $wp_query;
        if (
            in_array(
                $wp_query->get( 'pagename', false ),
                array(
                    'credit-card-update',
                    'one-time-login',
                    'account',
                ),
                true
            )
        ) {
            return;
        }
        echo '<div class="terminal-wrap">';
        terminal_print_template_part( 'sponsors' );
        echo '</div>';
    }
}

add_action( 'after_setup_theme', [ '\Terminal\Newspack', 'instance' ] );
