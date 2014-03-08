<?php
/**
 * The core functions file for the four7 framework. Functions defined here are generally
 * used across the entire framework to make various tasks faster. This file should be loaded
 * prior to any other files because its functions are needed to run the framework.
 *
 * @package    four7 Framework
 * @subpackage Core
 * @author     inevisys
 * @copyright  Copyright (c) 2013 - 2014, inevisys
 * @link       http://inevisys.com/four7
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Defines the theme prefix. This allows developers to infinitely change the theme. In theory,
 * one could use the four7 core to create their own theme or filter 'four7_prefix' with a
 * plugin to make it easier to use hooks across multiple themes without having to figure out
 * each theme's hooks (assuming other themes used the same system).
 *
 * @since 1.0.0
 * @uses  get_template() Defines the theme prefix based on the theme directory.
 * @global object $four7 The global four7 object.
 * @return string $four7->prefix The prefix of the theme.
 */
function four7_get_prefix() {
	global $four7;

	/* If the global prefix isn't set, define it. Plugin/theme authors may also define a custom prefix. */
	if ( empty( $four7->prefix ) ) {
		$four7->prefix = sanitize_key( apply_filters( 'four7_prefix', get_template() ) );
	}

	return $four7->prefix;
}

/**
 * Adds contextual action hooks to the theme.  This allows users to easily add context-based content
 * without having to know how to use WordPress conditional tags.  The theme handles the logic.
 *
 * An example of a basic hook would be 'four7_header'.  The do_atomic() function extends that to
 * give extra hooks such as 'four7_singular_header', 'four7_singular-post_header', and
 * 'four7_singular-post-ID_header'.
 *
 * @since 1.0.0
 * @uses  four7_get_prefix() Gets the theme prefix.
 * @uses  four7_get_context() Gets the context of the current page.
 *
 * @param string $tag     Usually the location of the hook but defines what the base hook is.
 * @param mixed  $arg,... Optional additional arguments which are passed on to the functions hooked to the action.
 */
function do_atomic( $tag = '', $arg = '' ) {
	if ( empty( $tag ) ) {
		return false;
	}

	/* Get the theme prefix. */
	$pre = four7_get_prefix();

	/* Get the args passed into the function and remove $tag. */
	$args = func_get_args();
	array_splice( $args, 0, 1 );

	/* Do actions on the basic hook. */
	do_action_ref_array( "{$pre}_{$tag}", $args );

	/* Loop through context array and fire actions on a contextual scale. */
	foreach ( (array) four7_get_context() as $context ) {
		do_action_ref_array( "{$pre}_{$context}_{$tag}", $args );
	}
}

/**
 * Adds contextual filter hooks to the theme.  This allows users to easily filter context-based content
 * without having to know how to use WordPress conditional tags.  The theme handles the logic.
 *
 * An example of a basic hook would be 'four7_entry_meta'.  The apply_atomic() function extends
 * that to give extra hooks such as 'four7_singular_entry_meta', 'four7_singular-post_entry_meta',
 * and 'four7_singular-post-ID_entry_meta'.
 *
 * @since 1.0.0
 * @uses  four7_get_prefix() Gets the theme prefix.
 * @uses  four7_get_context() Gets the context of the current page.
 *
 * @param string $tag     Usually the location of the hook but defines what the base hook is.
 * @param mixed  $value   The value on which the filters hooked to $tag are applied on.
 * @param mixed  $var,... Additional variables passed to the functions hooked to $tag.
 *
 * @return mixed $value The value after it has been filtered.
 */
function apply_atomic( $tag = '', $value = '' ) {
	if ( empty( $tag ) ) {
		return false;
	}

	/* Get theme prefix. */
	$pre = four7_get_prefix();

	/* Get the args passed into the function and remove $tag. */
	$args = func_get_args();
	array_splice( $args, 0, 1 );

	/* Apply filters on the basic hook. */
	$value = $args[0] = apply_filters_ref_array( "{$pre}_{$tag}", $args );

	/* Loop through context array and apply filters on a contextual scale. */
	foreach ( (array) four7_get_context() as $context ) {
		$value = $args[0] = apply_filters_ref_array( "{$pre}_{$context}_{$tag}", $args );
	}

	/* Return the final value once all filters have been applied. */

	return $value;
}

/**
 * Wraps the output of apply_atomic() in a call to do_shortcode(). This allows developers to use
 * context-aware functionality alongside shortcodes. Rather than adding a lot of code to the
 * function itself, developers can create individual functions to handle shortcodes.
 *
 * @since 1.0.0
 *
 * @param string $tag   Usually the location of the hook but defines what the base hook is.
 * @param mixed  $value The value to be filtered.
 *
 * @return mixed $value The value after it has been filtered.
 */
function apply_atomic_shortcode( $tag = '', $value = '' ) {
	return do_shortcode( apply_atomic( $tag, $value ) );
}

/**
 * The theme can save multiple things in a transient to help speed up page load times. We're
 * setting a default of 12 hours or 43,200 seconds (60 * 60 * 12).
 *
 * @since 1.0.0
 * @return int Transient expiration time in seconds.
 */
function four7_get_transient_expiration() {
	return apply_filters( four7_get_prefix() . '_transient_expiration', 43200 );
}

/**
 * Function for formatting a hook name if needed. It automatically adds the theme's prefix to
 * the hook, and it will add a context (or any variable) if it's given.
 *
 * @since 1.0.0
 *
 * @param string $tag     The basic name of the hook (e.g., 'before_header').
 * @param string $context A specific context/value to be added to the hook.
 */
function four7_format_hook( $tag, $context = '' ) {
	return four7_get_prefix() . ( ( ! empty( $context ) ) ? "_{$context}" : "" ) . "_{$tag}";
}

/**
 * Function for setting the content width of a theme.  This does not check if a content width has been set; it
 * simply overwrites whatever the content width is.
 *
 * @since 1.0.0
 * @global int $content_width The width for the theme's content area.
 *
 * @param int  $width         Numeric value of the width to set.
 */
function four7_set_content_width( $width = '' ) {
	global $content_width;

	$content_width = absint( $width );
}

/**
 * Function for getting the theme's content width.
 *
 * @since 1.0.0
 * @global int $content_width The width for the theme's content area.
 * @return int $content_width
 */
function four7_get_content_width() {
	global $content_width;

	return $content_width;
}

/**
 * Gets theme data and stores it in the global $four7 variable.  By storing it, it can be accessed quickly without
 * having to run through the get_theme_data() function again.
 *
 * @since 1.0.0
 *
 * @param string $path Whether to use the template (parent theme) or stylesheet (child theme) path.
 */
function four7_get_theme_data( $path = 'template' ) {
	global $four7;

	/* If 'template' is requested, get the parent theme data. */
	if ( 'template' == $path ) {

		/* If the parent theme data isn't set, grab it with the get_theme_data() function. */
		if ( empty( $four7->theme_data ) ) {
			$four7->theme_data = wp_get_theme( trailingslashit( TEMPLATEPATH ) . 'style.css' );
		}

		/* Return the parent theme data. */

		return $four7->theme_data;
	} /* If 'stylesheet' is requested, get the child theme data. */
	elseif ( 'stylesheet' == $path ) {

		/* If the child theme data isn't set, grab it with the get_theme_data() function. */
		if ( empty( $four7->child_theme_data ) ) {
			$four7->child_theme_data = wp_get_theme( trailingslashit( STYLESHEETPATH ) . 'style.css' );
		}

		/* Return the child theme data. */

		return $four7->child_theme_data;
	}

	/* Return false for everything else. */

	return false;
}

?>