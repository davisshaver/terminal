<?php
/**
 * Terminal singleton trait.
 *
 * @package Terminal
 */

namespace Terminal;

trait Singleton {

	/**
	 * Class instance.
	 *
	 * @var $instance Object
	 */
	protected static $instance;

	/**
	 * Get instance.
	 *
	 * @return object instance object
	 */
	public static function instance() {
		if ( ! isset( static::$instance ) ) {
			static::$instance = new static();
			static::$instance->setup();
		}
		return static::$instance;
	}

	/**
	 * Setup function. Override in class.
	 */
	public function setup() {
		// Put custom init here.
	}
}
