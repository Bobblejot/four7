<?php
/**
 * Functions file for loading scripts and stylesheets.  This file also handles the output of attachment files
 * by displaying appropriate HTML elements for the attachments.
 *
 * @package    four7 Framework
 * @subpackage Functions
 * @author     inevisys
 * @copyright  Copyright (c) 2013 - 2014, inevisys
 * @link       http://inevisys.com/four7
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 */

/* Load the development stylsheet in script debug mode. */
add_filter( 'stylesheet_uri', 'four7_debug_stylesheet', 10, 2 );


/**
 * Function for using a debug stylesheet when developing.  To develop with the debug stylesheet,
 * SCRIPT_DEBUG must be set to 'true' in the 'wp-config.php' file.  This will check if a 'style.dev.css'
 * file is present within the theme folder and use it if it exists.  Else, it defaults to 'style.css'.
 *
 * @since 3.3.0
 */
function four7_debug_stylesheet( $stylesheet_uri, $stylesheet_dir_uri ) {

	/* If SCRIPT_DEBUG is set to true and the theme supports 'dev-stylesheet'. */
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && current_theme_supports( 'dev-stylesheet' ) ) {

		/* Remove the stylesheet directory URI from the file name. */
		$stylesheet = str_replace( trailingslashit( $stylesheet_dir_uri ), '', $stylesheet_uri );

		/* Change the stylesheet name to 'style.dev.css'. */
		$stylesheet = str_replace( '.css', '.dev.css', $stylesheet );

		/* If the stylesheet exists in the stylesheet directory, set the stylesheet URI to the dev stylesheet. */
		if ( file_exists( trailingslashit( STYLESHEETPATH ) . $stylesheet ) ) {
			$stylesheet_uri = trailingslashit( $stylesheet_dir_uri ) . $stylesheet;
		}
	}

	/* Return the theme stylesheet. */

	return $stylesheet_uri;
}

/**
 * Loads the correct function for handling attachments.  Checks the attachment mime type to call
 * correct function. Image attachments are not loaded with this function.  The functionality for them
 * should be handled by the theme's attachment or image attachment file.
 *
 * Ideally, all attachments would be appropriately handled within their templates. However, this could
 * lead to messy template files.
 *
 * @since 3.3.0
 * @uses  get_post_mime_type() Gets the mime type of the attachment.
 * @uses  wp_get_attachment_url() Gets the URL of the attachment file.
 */
function four7_attachment() {
	$file      = wp_get_attachment_url();
	$mime      = get_post_mime_type();
	$mime_type = explode( '/', $mime );

	/* Loop through each mime type. If a function exists for it, call it. Allow users to filter the display. */
	foreach ( $mime_type as $type ) {
		if ( function_exists( "four7_{$type}_attachment" ) ) {
			$attachment = call_user_func( "four7_{$type}_attachment", $mime, $file );
		}

		$attachment = apply_atomic( "{$type}_attachment", $attachment );
	}

	echo apply_atomic( 'attachment', $attachment );
}

/**
 * Handles application attachments on their attachment pages.  Uses the <object> tag to embed media
 * on those pages.
 *
 * @since 3.3.0
 *
 * @param string $mime attachment mime type
 * @param string $file attachment file URL
 *
 * @return string
 */
function four7_application_attachment( $mime = '', $file = '' ) {
	$embed_defaults = wp_embed_defaults();
	$application    = '<object class="text" type="' . esc_attr( $mime ) . '" data="' . esc_url( $file ) . '" width="' . esc_attr( $embed_defaults['width'] ) . '" height="' . esc_attr( $embed_defaults['height'] ) . '">';
	$application .= '<param name="src" value="' . esc_url( $file ) . '" />';
	$application .= '</object>';

	return $application;
}

/**
 * Handles text attachments on their attachment pages.  Uses the <object> element to embed media
 * in the pages.
 *
 * @since 3.3.0
 *
 * @param string $mime attachment mime type
 * @param string $file attachment file URL
 *
 * @return string
 */
function four7_text_attachment( $mime = '', $file = '' ) {
	$embed_defaults = wp_embed_defaults();
	$text           = '<object class="text" type="' . esc_attr( $mime ) . '" data="' . esc_url( $file ) . '" width="' . esc_attr( $embed_defaults['width'] ) . '" height="' . esc_attr( $embed_defaults['height'] ) . '">';
	$text .= '<param name="src" value="' . esc_url( $file ) . '" />';
	$text .= '</object>';

	return $text;
}

/**
 * Handles audio attachments on their attachment pages.  Puts audio/mpeg and audio/wma files into
 * an <object> element.
 *
 * @todo  Test out and support more audio types.
 *
 * @since 3.3.0
 *
 * @param string $mime attachment mime type
 * @param string $file attachment file URL
 *
 * @return string
 */
function four7_audio_attachment( $mime = '', $file = '' ) {
	$embed_defaults = wp_embed_defaults();
	$audio          = '<object type="' . esc_attr( $mime ) . '" class="player audio" data="' . esc_url( $file ) . '" width="' . esc_attr( $embed_defaults['width'] ) . '" height="' . esc_attr( $embed_defaults['height'] ) . '">';
	$audio .= '<param name="src" value="' . esc_url( $file ) . '" />';
	$audio .= '<param name="autostart" value="false" />';
	$audio .= '<param name="controller" value="true" />';
	$audio .= '</object>';

	return $audio;
}

/**
 * Handles video attachments on attachment pages.  Add other video types to the <object> element.
 *
 * @since 3.3.0
 *
 * @param string $mime attachment mime type
 * @param string $file attachment file URL
 *
 * @return string
 */
function four7_video_attachment( $mime = false, $file = false ) {
	$embed_defaults = wp_embed_defaults();

	if ( $mime == 'video/asf' ) {
		$mime = 'video/x-ms-wmv';
	}

	$video = '<object type="' . esc_attr( $mime ) . '" class="player video" data="' . esc_url( $file ) . '" width="' . esc_attr( $embed_defaults['width'] ) . '" height="' . esc_attr( $embed_defaults['height'] ) . '">';
	$video .= '<param name="src" value="' . esc_url( $file ) . '" />';
	$video .= '<param name="autoplay" value="false" />';
	$video .= '<param name="allowfullscreen" value="true" />';
	$video .= '<param name="controller" value="true" />';
	$video .= '</object>';

	return $video;
}

?>