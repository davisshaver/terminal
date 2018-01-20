<?php
/**
 * Nav partial.
 *
 * @package Terminal
 */

?>

<div id="nav-bar" class="terminal-utility-font">
	<div id="nav-bar-inside">
		<?php
			wp_nav_menu( array(
				'theme_location' => 'header',
				'depth'          => 2,
			) );
		?>
	</div>
</div>
