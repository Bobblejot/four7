<?php

/*
 * Add the necessary actions and filters for post formats.
 */
function four7_post_formats() {
	if ( is_singular() ) {

		// If the post format is set to "aside", don't display a title.
		if ( get_post_format() == 'aside' ) {
			add_filter( 'four7_title_section', 'four7_blank', 20 );
		}

		if ( get_post_format() == 'gallery' ) {
			add_filter( 'four7_title', 'four7_post_format_gallery_title', 20 );
		}

		// If the post format is set to "link", make the link into a button.
		if ( get_post_format() == 'link' ) {
			add_filter( 'the_content', 'four7_post_formats_link' );
			add_filter( 'four7_title', 'four7_post_format_link_title', 20 );
		}

		if ( get_post_format() == 'image' ) {
			add_filter( 'four7_title', 'four7_post_format_image_title', 20 );
		}

		if ( get_post_format() == 'quote' ) {
			add_filter( 'four7_title', 'four7_post_format_quote_title', 20 );
		}

		if ( get_post_format() == 'status' ) {
			add_filter( 'four7_title', 'four7_post_format_status_title', 20 );
		}

		if ( get_post_format() == 'video' ) {
			add_filter( 'four7_title', 'four7_post_format_video_title', 20 );
		}

		if ( get_post_format() == 'audio' ) {
			add_filter( 'four7_title', 'four7_post_format_audio_title', 20 );
		}

		if ( get_post_format() == 'chat' ) {
			add_filter( 'four7_title', 'four7_post_format_chat_title', 20 );
		}
	}
}

add_action( 'wp', 'four7_post_formats' );


/**
 * Add icons to post format titles (gallery)
 */
function four7_post_format_gallery_title( $title ) {
	return '<i class="fa fa-picture-o text-info"></i> ' . $title;
}

/**
 * Add icons to post format titles (link)
 */
function four7_post_format_link_title( $title ) {
	return '<i class="fa fa-link text-info"></i> ' . $title;
}

/**
 * Add icons to post format titles (image)
 */
function four7_post_format_image_title( $title ) {
	return '<i class="fa fa-picture-o text-info"></i> ' . $title;
}

/**
 * Add icons to post format titles (quote)
 */
function four7_post_format_quote_title( $title ) {
	return '<i class="fa fa-quote-left text-info"></i> ' . $title;
}

/**
 * Add icons to post format titles (status)
 */
function four7_post_format_status_title( $title ) {
	return '<i class="fa fa--comment text-info"></i> ' . $title;
}

/**
 * Add icons to post format titles (image)
 */
function four7_post_format_video_title( $title ) {
	return '<i class="fa fa-video-camera text-info"></i> ' . $title;
}

/**
 * Add icons to post format titles (audio)
 */
function four7_post_format_audio_title( $title ) {
	return '<i class="fa fa-volume-up text-info"></i> ' . $title;
}

/**
 * Add icons to post format titles (chat)
 */
function four7_post_format_chat_title( $title ) {
	return '<i class="fa fa-comment-o text-info"></i> ' . $title;
}


/*
 * If the post format is set to "link", make the link into a button.
 */
function four7_post_formats_link( $content ) {
	global $fs_framework;

	return str_replace( '<a ', '<a class="' . $fs_framework->button_classes( 'primary', 'large' ) . '" ', $content );
}
