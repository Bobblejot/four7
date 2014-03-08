<?php
/**
 * The menus functions deal with registering nav menus within WordPress for the core framework.  Theme
 * developers may use the default menu(s) provided by the framework within their own themes, decide not
 * to use them, or register additional menus.
 *
 * @package    four7 Framework
 * @subpackage Functions
 * @author     inevisys
 * @copyright  Copyright (c) 2013 - 2014, inevisys
 * @link       http://inevisys.com/four7
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 */

/* Register nav menus. */
add_action( 'init', 'four7_register_menus' );

/**
 * Registers the the framework's default menus based on the menus the theme has registered support for.
 *
 * @since 1.0.0
 * @uses  register_nav_menu() Registers a nav menu with WordPress.
 * @link  http://codex.wordpress.org/Function_Reference/register_nav_menu
 */
function four7_register_menus() {

	/* Get theme-supported menus. */
	$menus = get_theme_support( 'four7-core-menus' );

	/* If there is no array of menus IDs, return. */
	if ( ! is_array( $menus[0] ) ) {
		return;
	}

	/* Register the 'primary' menu. */
	if ( in_array( 'primary_navigation', $menus[0] ) ) {
		register_nav_menu( 'primary_navigation', _x( 'top bar navigation', 'top bar nav menu location', four7_get_parent_textdomain() ) );
	}

	/* Register the 'secondary' menu. */
	if ( in_array( 'mobile_menu', $menus[0] ) ) {
		register_nav_menu( 'mobile_menu', _x( 'mobile menu', 'mobile nav menu location', four7_get_parent_textdomain() ) );
	}

	/* Register the 'secondary' menu. */
	if ( in_array( 'secondary_navigation', $menus[0] ) ) {
		register_nav_menu( 'secondary_navigation', _x( 'main menu', 'main bar nav menu location', four7_get_parent_textdomain() ) );
	}

	/* Register the 'subsidiary' menu. */
	if ( in_array( 'footer_menu', $menus[0] ) ) {
		register_nav_menu( 'footer_menu', _x( 'footer menu', 'footer nav menu location', four7_get_parent_textdomain() ) );
	}
}

?>