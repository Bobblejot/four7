<?php

/**
 * Metadata functions used in the core framework.  This file registers meta keys for use in WordPress 
 * in a safe manner by setting up a custom sanitize callback.
 *
 * @package four7 Framework
 * @subpackage Functions
 * @author inevisys
 * @copyright Copyright (c) 2013 - 2014, inevisys
 * @link http://inevisys.com/fourseven
 *
 */

/* Register meta on the 'init' hook. */
add_action( 'init', 'four7_register_meta' );

/**
 * Registers the framework's custom metadata keys and sets up the sanitize callback function.
 *
 * @since 1.0.0
 * @return void
 *
 */
 
function four7_register_meta() {

	/* Register meta if the theme supports the 'fourseven-core-seo' feature. */
	if ( current_theme_supports( 'four7-core-seo' ) ) {

		/* Register 'Title', 'Description', and 'Keywords' meta for posts. */
		register_meta( 'post', 'Title', 'four7_sanitize_meta' );
		register_meta( 'post', 'Description', 'four7_sanitize_meta' );
		register_meta( 'post', 'Keywords', 'four7_sanitize_meta' );

		/* Register 'Title', 'Description', and 'Keywords' meta for users. */
		register_meta( 'user', 'Title', 'four7_sanitize_meta' );
		register_meta( 'user', 'Description', 'four7_sanitize_meta' );
		register_meta( 'user', 'Keywords', 'four7_sanitize_meta' );
	}

	/* Register meta if the theme supports the 'fourseven-core-template-hierarchy' feature. */
	if ( current_theme_supports( 'four7-core-template-hierarchy' ) ) {

		$post_types = get_post_types( array( 'public' => true ) );

		foreach ( $post_types as $post_type ) {
			if ( 'page' !== $post_type )
				register_meta( 'post', "_wp_{$post_type}_template", 'four7_sanitize_meta' );
		}
	}
}

/**
 * Callback function for sanitizing meta when add_metadata() or update_metadata() is called by WordPress. 
 * If a developer wants to set up a custom method for sanitizing the data, they should use the 
 * "sanitize_{$meta_type}_meta_{$meta_key}" filter hook to do so.
 *
 * @since 1.0.0
 * @param mixed $meta_value The value of the data to sanitize.
 * @param string $meta_key The meta key name.
 * @param string $meta_type The type of metadata (post, comment, user, etc.)
 * @return mixed $meta_value
 *
 */
function four7_sanitize_meta( $meta_value, $meta_key, $meta_type ) {
	return strip_tags( $meta_value );
}

function four7_meta( $context = 'tags' ) {
	global $fs_framework;

	$panel_open = $fs_framework->make_panel( 'post-meta-' . $context );
	$panel_head = $fs_framework->make_panel_heading();
	$tags_label = '<i class="el-icon-tags"></i> ' . __( 'Tags:', 'four7' );
	$cats_label = '<i class="el-icon-tag"></i> ' . __( 'Categories:', 'four7' );
	$panel_body = $fs_framework->make_panel_body();
	$label_def  = '<span class="label label-tag">';

	if ( $context == 'tags' && get_the_tag_list() ) {
		echo apply_filters( 'four7_the_tags', get_the_tag_list( $panel_open . $panel_head . $tags_label . '</div>' . $panel_body . $label_def,
			'</span> ' . $label_def,
			'</span></div></div>'
		) );
	}

	if ( $context == 'cats' && get_the_category_list() ) {
		echo apply_filters( 'four7_the_cats', $panel_open . $panel_head . $cats_label . '</div>' . $panel_body . $label_def . get_the_category_list( '</span> ' . $label_def ) . '</span></div></div>' );
	}
}