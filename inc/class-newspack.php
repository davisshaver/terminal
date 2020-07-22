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
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 10 );
        add_action( 'after_header', [ $this, 'do_after_header' ] );
    }

    /**
     * Enqueue styles.
     */
    public function enqueue_styles() {
        wp_enqueue_style( TERMINAL_APP, plugin_dir_url( dirname( __FILE__ ) ) . '/client/build/index.css', array(), TERMINAL_VERSION );
        wp_enqueue_script( 'terminal-broadstreet', 'https://static.lebtown.com/init-2.min.js' );
        wp_add_inline_script( 'terminal-broadstreet', "broadstreet.watch({ domain: 'sponsors.lebtown.com' })" );

    }

    /**
     * Do after header actions.
     */
    public function do_after_header() {
        dynamic_sidebar( 'after-header' );
    }
}

add_action( 'after_setup_theme', [ '\Terminal\Newspack', 'instance' ] );
