<?php
/**
 * This file adds compatibility with older functions, actions & filters that were used prior to version 3.1.1
 * Developers should NOT use these functions and actions and should instead migrate to the new ones.
 */


/**
 * Color functions
 */
function four7_sanitize_hex( $color ) {
	return Four7_Color::sanitize_hex( $color );
}

function four7_get_rgb( $hex, $implode = false ) {
	return Four7_Color::get_rgb( $hex, $implode );
}

function four7_get_rgba( $hex, $opacity, $echo ) {
	return Four7_Color::get_rgba( $hex, $opacity, $echo );
}

function four7_get_brightness( $hex ) {
	return Four7_Color::get_brightness( $hex );
}

function four7_adjust_brightness( $hex, $steps ) {
	return Four7_Color::adjust_brightness( $hex, $steps );
}

function four7_mix_colors( $hex1, $hex2, $percentage ) {
	return Four7_Color::mix_colors( $hex1, $hex2, $percentage );
}

function four7_hex_to_hsv( $hex ) {
	return Four7_Color::hex_to_hsv( $hex );
}

function four7_rgb_to_hsv( $color = array() ) {
	return Four7_Color::rgb_to_hsv( $rgb );
}

function four7_brightest_color( $colors = array(), $context = 'key' ) {
	return Four7_Color::brightest_color( $colors, $context );
}

function four7_most_saturated_color( $colors = array(), $context = 'key' ) {
	return Four7_Color::most_saturated_color( $colors, $context );
}

function four7_most_intense_color( $colors = array(), $context = 'key' ) {
	return Four7_Color::most_intense_color( $colors, $context );
}

function four7_brightest_dull_color( $colors = array(), $context = 'key' ) {
	return Four7_Color::brightest_dull_color( $colors, $context );
}

function four7_brightnefs_difference( $hex1, $hex2 ) {
	return Four7_Color::brightnefs_difference( $hex1, $hex2 );
}

function four7_color_difference( $hex1, $hex2 ) {
	return Four7_Color::color_difference( $hex1, $hex2 );
}

function four7_lumosity_difference( $hex1, $hex2 ) {
	return Four7_Color::lumosity_difference( $hex1, $hex2 );
}

/**
 * Layout functions
 */
function four7_content_width_px( $echo = false ) {
	return Four7_Layout::content_width_px( $echo );
}

/**
 * Image functions
 */
function four7_image_resize( $data ) {
	return Four7_Image::image_resize( $data );
}

/**
 * Actions & filters
 */
add_action( 'four7_single_top', 'four7_in_article_top_deprecated' );
function four7_in_article_top_deprecated() {
	if ( has_action( 'four7_in_article_top' ) ) {
		do_action( 'four7_in_article_top' );
	}
}

add_action( 'four7_entry_meta', 'four7_entry_meta_override_deprecated' );
function four7_entry_meta_override_deprecated() {
	if ( has_action( 'four7_entry_meta_override' ) ) {
		do_action( 'four7_entry_meta_override' );
	}
}

add_action( 'four7_entry_meta', 'four7_after_entry_meta_deprecated', 99 );
function four7_after_entry_meta_deprecated() {
	if ( has_action( 'four7_after_entry_meta' ) ) {
		do_action( 'four7_after_entry_meta' );
	}
}

add_action( 'four7_do_navbar', 'four7_pre_navbar_deprecated', 9 );
function four7_pre_navbar_deprecated() {
	if ( has_action( 'four7_pre_navbar' ) ) {
		do_action( 'four7_pre_navbar' );
	}
}

add_action( 'four7_do_navbar', 'four7_post_navbar_deprecated', 15 );
function four7_post_navbar_deprecated() {
	if ( has_action( 'four7_post_navbar' ) ) {
		do_action( 'four7_post_navbar' );
	}
}

add_action( 'four7_pre_wrap', 'four7_below_top_navbar_deprecated' );
function four7_below_top_navbar_deprecated() {
	if ( has_action( 'four7_below_top_navbar' ) ) {
		do_action( 'four7_below_top_navbar' );
	}
}

add_action( 'four7_pre_wrap', 'four7_breadcrumbs_deprecated' );
function four7_breadcrumbs_deprecated() {
	if ( has_action( 'four7_breadcrumbs' ) ) {
		do_action( 'four7_breadcrumbs' );
	}
}

add_action( 'four7_pre_wrap', 'four7_header_media_deprecated' );
function four7_header_media_deprecated() {
	if ( has_action( 'four7_header_media' ) )
		do_action( 'four7_header_media' );
}

add_action( 'four7_pre_footer', 'four7_after_wrap_deprecated' );
function four7_after_wrap_deprecated() {
	if ( has_action( 'four7_after_wrap' ) ) {
		do_action( 'four7_after_wrap' );
	}
}

add_action( 'four7_in_loop_start', 'four7_after_wrap_deprecated' );
function four7_before_the_content_deprecated() {
	if ( has_action( 'four7_before_the_content' ) ) {
		do_action( 'four7_before_the_content' );
	}
}

add_action( 'four7_in_loop_start', 'four7_in_loop_start_action_deprecated' );
function four7_in_loop_start_action_deprecated() {
	if ( has_action( 'four7_in_loop_start_action' ) ) {
		do_action( 'four7_in_loop_start_action' );
	}
}

add_action( 'four7_in_loop_end', 'four7_after_the_content_deprecated' );
function four7_after_the_content_deprecated() {
	if ( has_action( 'four7_after_the_content' ) ) {
		do_action( 'four7_after_the_content' );
	}
}