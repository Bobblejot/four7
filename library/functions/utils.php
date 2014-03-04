<?php
/**
 * Utility functions
 */

function is_element_empty( $element ) {
	$element = trim( $element );
	return empty( $element ) ? false : true;
}

function four7_return_true()  {
	return true;
}

function four7_return_false() {
	return false;
}

function four7_blank() {
	return '';
}


function four7_array_delete( $idx, $array ) {  
	unset( $array[$idx] );
	return ( is_array( $array ) ) ? array_values( $array ) : null;
}


function four7_procefs_font( $font ) {

	if ( empty( $font['font-weight'] ) ) {
		$font['font-weight'] = "inherit";
	}

	if ( empty( $font['font-style'] ) ) {
		$font['font-style'] = "inherit";
	}

	if ( isset( $font['font-size'] ) ) {
		$font['font-size'] = filter_var( $font['font-size'], FILTER_SANITIZE_NUMBER_INT );
	}

	return $font;
}

if ( ! function_exists( 'four7_getVariable' ) ) :
/*
 * Gets the current values from REDUX, and if not there, grabs the defaults
 */
function four7_getVariable( $name, $key = false ) {
	global $redux;
	$options = $redux;

	// Set this to your preferred default value
	$var = '';

	if ( empty( $name ) && ! empty( $options ) ) {
		$var = $options;
	} else {
		if ( ! empty( $options[$name] ) ) {
			$var = ( ! empty( $key ) && ! empty( $options[$name][$key] ) && $key !== true ) ? $options[$name][$key] : $var = $options[$name];;
		}
	}
	return $var;
}
endif;


if ( ! function_exists( 'four7_password_form' ) ) :
/*
 * Replace the password forms with a bootstrap-formatted version.
 */
function four7_password_form() {
	global $post, $fs_framework;
	$label    = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
	$content  = '<form action="';
	$content .= esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) );
	$content .= '" method="post">';
	$content .= __( 'This post is password protected. To view it please enter your password below:', 'four7' );
	$content .= '<div class="input-group">';
	$content .= '<input name="post_password" id="' . $label . '" type="password" size="20" />';
	$content .= '<span class="input-group-btn">';
	$content .= '<input type="submit" name="Submit" value="' . esc_attr__( "Submit" ) . '" class="' . $fs_framework->button_classes() . '" />';
	$content .= '</span></div></form>';

	return $content;
}
endif;
add_filter( 'the_password_form', 'four7_password_form' );


if ( ! function_exists( 'four7_replace_reply_link_class' ) ) :
/*
 * Apply the proper classes to comment reply links
 */
function four7_replace_reply_link_class( $class ) {
	global $fs_framework;
	$class = str_replace( "class='comment-reply-link", "class='comment-reply-link " . $fs_framework->button_classes( 'success', 'small' ), $class );
	return $class;
}
endif;
add_filter('comment_reply_link', 'four7_replace_reply_link_class');


if ( ! function_exists( 'four7_init_filesystem' ) ) :
/*
 * Initialize the Wordpress filesystem, no more using file_put_contents function
 */
function four7_init_filesystem() {
	if ( empty( $wp_filesystem ) ) {
		require_once(ABSPATH .'/wp-admin/includes/file.php');
		WP_Filesystem();
	}
}
endif;
add_filter('init', 'four7_init_filesystem');

/**
* Returns the URL from the post.
*
* @uses get_url_in_content() to get the URL in the post meta (if it exists) or
* the first link found in the post content.
*
* Falls back to the post permalink if no URL is found in the post.
*
* @since 1.0.0
* @return string The Link format URL.
*/

function four7_get_link_url() {
$content = get_the_content();
$has_url = get_url_in_content( $content );

return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}
/**
* Generates the relevant template info.  Adds template meta with theme version.  Uses the theme
* name and version from style.css.  In 0.6, added the fourseven_meta_template
* filter hook.
*
* @since 1.0.0
*/

function four7_meta_template() {
$data = four7_get_theme_data();
$template = '<meta name="template" content="' . esc_attr( "{$data['Title']} {$data['Version']}" ) . '" />' . "\n";
echo apply_atomic( 'meta_template', $template );
}

/**
* Dynamic element to wrap the site title in.  If it is the front page, wrap it in an <h1> element.  One other
* pages, wrap it in a <div> element.
*
* @since 1.0.0
* @access public
* @return void
*/

function four7_site_title() {

/* If viewing the front page of the site, use an <h1> tag.  Otherwise, use a <div> tag. */
$tag = ( is_front_page() ) ? 'h1' : 'div';

/* Get the site title.  If it's not empty, wrap it with the appropriate HTML. */
if ( $title = get_bloginfo( 'name' ) )
$title = sprintf( '<%1$s class="site-title"><a href="%2$s" title="%3$s" rel="home"><span>%4$s</span></a></%1$s>', tag_escape( $tag ), home_url(), esc_attr( $title ), $title );

/* Display the site title and apply filters for developers to overwrite. */
echo apply_atomic( 'site_title', $title );
}

/**
* Dynamic element to wrap the site description in.  If it is the front page, wrap it in an <h2> element.
* On other pages, wrap it in a <div> element.
*
* @since 1.0.0
* @access public
* @return void
*/

function four7_site_description() {

/* If viewing the front page of the site, use an <h2> tag.  Otherwise, use a <div> tag. */
$tag = ( is_front_page() ) ? 'h2' : 'div';

/* Get the site description.  If it's not empty, wrap it with the appropriate HTML. */
if ( $desc = get_bloginfo( 'description' ) )
$desc = sprintf( '<%1$s class="site-description"><span>%2$s</span></%1$s>', tag_escape( $tag ), $desc );

/* Display the site description and apply filters for developers to overwrite. */
echo apply_atomic( 'site_description', $desc );
}
