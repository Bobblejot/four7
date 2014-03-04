<?php

/* Load the core theme framework.
================================================== */  
require_once( trailingslashit( TEMPLATEPATH ) . 'library/class-Four7_Init.php' );
new Four7_Init();

$is_ajax            = true;
$is_ie9             = ( strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 9') )? true : false;
$is_ie10            = ( strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 10') )? true : false;


if ( ! defined( 'FOUR7_FRAMEWORK' ) ) {
	define( 'FOUR7_FRAMEWORK', 'bootstrap' );
}

if ( ! defined( 'FOUR7_OPT_NAME' ) ) {
	define( 'FOUR7_OPT_NAME', 'four7' );
}

global $fs_settings;

$fs_settings = get_option( FOUR7_OPT_NAME );


/* Do theme setup on the 'after_setup_theme' hook.
================================================== */  
add_action( 'after_setup_theme', 'four7_theme_setup' );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 3.1.0
 */
 
function four7_theme_setup() {

	/* Get action/filter hook prefix.
	================================================== */  
	$prefix = four7_get_prefix();

	/* Add theme support for core framework features.
	================================================== */ 
	add_theme_support( 'four7-core-menus', array( 'primary_navigation', 'mobile_menu', 'secondary_navigation', 'footer_menu' ) );
//	add_theme_support( 'four7-core-sidebars', array( 'primary', 'secondary','header','before-content','after-content','after-singular','footer','after-footer' ) );
	
	/* Activation
	================================================== */ 
	add_theme_support( 'four7-activation' );
	
	/* Load scripts.
	================================================== */ 
	add_theme_support( 'four7-core-scripts' );
	add_theme_support( 'four7-core-widgets' );
//	add_theme_support( 'four7-core-shortcodes' );
	
	/* Load the media grabber script.
	================================================== */ 
	add_theme_support( 'four7-core-media-grabber' );
	
	/* Add theme support for core utilities.
	================================================== */ 
	add_theme_support( 'four7-core-seo' );
    add_theme_support( 'four7-gallery' );  // Enable Bootstrap's thumbnails component on [gallery]
    add_theme_support( 'nice-search' );    // Enable /?s= to /search/ redirect
    add_theme_support( 'jquery-cdn' );     // Enable to load jQuery from the Google CDN
    
	/* Add theme support for framework extensions.
	================================================== */ 
	add_theme_support( 'gallery' );
	add_theme_support( 'comments' );
//	add_theme_support( 'pages' );

    /* Add theme support for bbPress.
	================================================== */ 
	add_theme_support( 'four7-bbpress' );

	/* Add theme support for WordPress features.
	================================================== */ 
	add_theme_support( 'structured-post-formats', array('audio', 'gallery', 'image', 'link', 'video') );
	add_theme_support( 'post-formats', array('aside', 'chat', 'quote', 'status') );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list') );
	add_theme_support( 'post-thumbnails' );      // wp thumbnails (sizes handled in functions.php)
    
    /* THUMBNAIL SIZES
    ================================================== */  	
    set_post_thumbnail_size( 220, 150, true);
    add_image_size( 'widget-image', 94, 70, true);
    add_image_size( 'thumb-square', 250, 250, true);
    add_image_size( 'thumb-image', 600, 450, true);
    add_image_size( 'thumb-image-twocol', 900, 675, true);
    add_image_size( 'thumb-image-onecol', 1800, 1200, true);
    add_image_size( 'blog-image', 1280, 9999);
    add_image_size( 'full-width-image-gallery', 1280, 720, true);
	
	/* Use Framework Gallery Styles.
	================================================== */ 
	add_filter( 'use_default_gallery_style', '__return_false' );	

	/* Add classes to the comments pagination.
	================================================== */  
	add_filter( 'previous_comments_link_attributes', 'four7_previous_comments_link_attributes' );
	add_filter( 'next_comments_link_attributes', 'four7_next_comments_link_attributes' );

	/* Wraps <blockquote> around quote posts.
    ================================================== */  
	add_filter( 'the_content', 'four7_quote_content' );

	/* Adds the featured image to image posts if no content is found.
	================================================== */  
	add_filter( 'the_content', 'four7_image_content' );

	/* Add custom <body> classes.
	================================================== */  
	add_filter( 'body_class', 'four7_fe_body_class' );

	/* Simplifies the taxonomy template name for post formats.
	================================================== */
	add_filter( 'taxonomy_template', 'four7_fe_taxonomy_template' );
		
	/* Tell the TinyMCE editor to use a custom stylesheet
	================================================== */  
	add_editor_style('library/assets/css/editor-style.css');	
	
}


/**
 * Returns the featured image for the image post format if the user didn't add any content to the post.
 *
 * @since 0.1.0
 * @param string $content The post content.
 * @return string $content
 */
function four7_image_content( $content ) {

	if ( has_post_format( 'image' ) && '' == $content ) {
		if ( is_singular() )
			$content = get_the_image( array( 'size' => 'full', 'meta_key' => false, 'link_to_post' => false ) );
		else
			$content = get_the_image( array( 'size' => 'full', 'meta_key' => false ) );
	}

	return $content;
}

/**
 * Grabs the first URL from the post content of the current post.  This is meant to be used with the link post 
 * format to easily find the link for the post. 
 *
 * @since 0.1.0
 * @return string The link if found.  Otherwise, the permalink to the post.
 *
 * @note This is a modified version of the twentyeleven_url_grabber() function in the TwentyEleven theme.
 * @author wordpressdotorg
 * @copyright Copyright (c) 2011, wordpressdotorg
 * @link http://wordpress.org/extend/themes/twentyeleven
 * @license http://wordpress.org/about/license
 */
function four7_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return get_permalink( get_the_ID() );

	return esc_url_raw( $matches[1] );
}

/**
 * Adds 'class="prev" to the previous comments link.
 *
 * @since 0.1.0
 * @param string $attributes The previous comments link attributes.
 * @return string
 */
function four7_previous_comments_link_attributes( $attributes ) {
	return $attributes . ' class="prev"';
}

/**
 * Adds 'class="next" to the next comments link.
 *
 * @since 0.1.0
 * @param string $attributes The next comments link attributes.
 * @return string
 */
function four7_next_comments_link_attributes( $attributes ) {
	return $attributes . ' class="next"';
}

/**
 * Returns the number of images attached to the current post in the loop.
 *
 * @since 0.1.0
 * @return int
 */
function four7_get_image_attachment_count() {
	$images = get_children( array( 'post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image', 'numberposts' => -1 ) );
	return count( $images );
}

/**
 * Returns a set of image attachment links based on size.
 *
 * @since 0.1.0
 * @return string Links to various image sizes for the image attachment.
 */
function four7_get_image_size_links() {

	/* If not viewing an image attachment page, return. */
	if ( !wp_attachment_is_image( get_the_ID() ) )
		return;

	/* Set up an empty array for the links. */
	$links = array();

	/* Get the intermediate image sizes and add the full size to the array. */
	$sizes = get_intermediate_image_sizes();
	$sizes[] = 'full';

	/* Loop through each of the image sizes. */
	foreach ( $sizes as $size ) {

		/* Get the image source, width, height, and whether it's intermediate. */
		$image = wp_get_attachment_image_src( get_the_ID(), $size );

		/* Add the link to the array if there's an image and if $is_intermediate (4th array value) is true or full size. */
		if ( !empty( $image ) && ( true === $image[3] || 'full' == $size ) )
			$links[] = "<a class='image-size-link' href='" . esc_url( $image[0] ) . "'>{$image[1]} &times; {$image[2]}</a>";
	}

	/* Join the links in a string and return. */
	return join( ' <span class="sep">/</span> ', $links );
}


/** ====== four7 Core 3.1.0 functionality. ====== **/

/**
 * Fix for four7 Core until version 3.1.0 is released.  This adds the '.custom-background' class to the <body> 
 * element for the WordPress custom background feature.
 *
 * @since 3.1.0
 * @todo Remove once theme is upgraded to four7 Core 1.3.0.
 * @link http://core.trac.wordpress.org/ticket/18698
 */
function four7_fe_body_class( $classes ) {

	if ( get_background_image() || get_background_color() )
		$classes[] = 'custom-background';

	if ( is_tax( 'post_format' ) )
		$classes = array_map( 'four7_clean_post_format_slug', $classes );

	return $classes;
}

/**
 * Removes 'post-format-' from the taxonomy template name for post formats.
 *
 * @since 0.1.0
 * @todo Remove once theme is upgraded to four7 Core 1.3.0.
 */
function four7_fe_taxonomy_template( $template ) {

	$term = get_queried_object();

	if ( 'post_format' == $term->taxonomy ) {

		$slug = four7_clean_post_format_slug( $term->slug );

		$has_template = locate_template( array( "taxonomy-{$term->taxonomy}-{$slug}.php" ) );

		if ( $has_template )
			$template = $has_template;
	}

	return $template;
}

/**
 * Add functionality to four7 Core 1.3.0.
 *
 * @since 0.1.0
 * @todo Remove once theme is upgraded to four7 Core 1.3.0.
 */
function four7_clean_post_format_slug( $slug ) {
	return str_replace( 'post-format-', '', $slug );
}


function four7_feed_link($output, $feed) {
    global $data;

    if($data['rss_link']):
    $feed_url = $data['rss_link'];

    $feed_array = array('rss' => $feed_url, 'rss2' => $feed_url, 'atom' => $feed_url, 'rdf' => $feed_url, 'comments_rss2' => '');
    $feed_array[$feed] = $feed_url;
    $output = $feed_array[$feed];
    endif;

    return $output;
}
add_filter('feed_link','four7_feed_link', 1, 2);

function tf_addURLParameter($url, $paramName, $paramValue) {
     $url_data = parse_url($url);
     if(!isset($url_data["query"]))
         $url_data["query"]="";

     $params = array();
     parse_str($url_data['query'], $params);
     $params[$paramName] = $paramValue;   
     $url_data['query'] = http_build_query($params);
     return tf_build_url($url_data);
}


 function tf_build_url($url_data) {
     $url="";
     if(isset($url_data['host']))
     {
         $url .= $url_data['scheme'] . '://';
         if (isset($url_data['user'])) {
             $url .= $url_data['user'];
                 if (isset($url_data['pass'])) {
                     $url .= ':' . $url_data['pass'];
                 }
             $url .= '@';
         }
         $url .= $url_data['host'];
         if (isset($url_data['port'])) {
             $url .= ':' . $url_data['port'];
         }
     }
     $url .= $url_data['path'];
     if (isset($url_data['query'])) {
         $url .= '?' . $url_data['query'];
     }
     if (isset($url_data['fragment'])) {
         $url .= '#' . $url_data['fragment'];
     }
     return $url;
 }

function getClassAlign($post_count)
{
    if(($post_count % 2)>0)
        return " align-left ";
    else
        return " align-right ";
}

function four7_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

/**
 * Wraps the output of the quote post format content in a <blockquote> element if the user hasn't added a 
 * <blockquote> in the post editor.
 *
 * @since 0.1.0
 * @param string $content The post content.
 * @return string $content
 */
function four7_quote_content( $content ) {

	if ( has_post_format( 'quote' ) ) {
		preg_match( '/<blockquote.*?>/', $content, $matches );

		if ( empty( $matches ) )
			$content = "<blockquote>{$content}</blockquote>";
	}

	return $content;
}



function get_related_posts($post_id) {
	$query = new WP_Query();
    
    $args = '';

	$args = wp_parse_args($args, array(
		'showposts' => -1,
		'post__not_in' => array($post_id),
		'ignore_sticky_posts' => 0,
        'category__in' => wp_get_post_categories($post_id)
	));
	
	$query = new WP_Query($args);
	
  	return $query;
}

function get_related_projects($post_id) {
    $query = new WP_Query();
    
    $args = '';

    $item_cats = get_the_terms($post_id, 'portfolio_category');
    if($item_cats):
    foreach($item_cats as $item_cat) {
        $item_array[] = $item_cat->term_id;
    }
    endif;

    $args = wp_parse_args($args, array(
        'showposts' => -1,
        'post__not_in' => array($post_id),
        'ignore_sticky_posts' => 0,
        'post_type' => 'four7_portfolio',
        'tax_query' => array(
            array(
                'taxonomy' => 'portfolio_category',
                'field' => 'id',
                'terms' => $item_array
            )
        )
    ));
    
    $query = new WP_Query($args);
    
    return $query;
}

function four7_pagination($pages = '', $range = 2)
{
    global $data;
    
     $showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<ul class='pagination pagination-sm'>";
         
         if($paged > 1) echo "<li><a class='pagination-prev' href='".get_pagenum_link($paged - 1)."'>&laquo;</a></li>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<li class='active'><span>".$i."</span></li>":"<li><a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a></li>";
             }
         }

         if ($paged < $pages) echo "<li><a href='".get_pagenum_link($paged + 1)."'>&raquo;</a><li>";  
     
         echo "</ul>\n";
     }
}


function ajax_login_init(){

    wp_localize_script( 'ajax-login-script', 'ajax_login_object', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'redirecturl' => $_SERVER['REQUEST_URI'],
        'loadingmessage' =>__('Validating...')
    ));

    // Enable the user with no privileges to run ajax_login() in AJAX
    add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
}

// Execute the action only if the user isn't logged in
if (!is_user_logged_in()) {
    add_action('init', 'ajax_login_init');
}

function ajax_login(){

    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );

    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;

    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
    } else {
        echo json_encode(array('loggedin'=>true, 'message'=>__('Sign in successful')));
    }

    die();
}


function string_limit_words($string, $word_limit)
{
	$words = explode(' ', $string, ($word_limit + 1));
	
	if(count($words) > $word_limit) {
		array_pop($words);
	}
	
	return implode(' ', $words);
}


/**
 * Replaces the standard wordpress login logo.
 *
 * @since 0.1.0
 */
function four7_login_head() {
	echo "
	<style>
	body.login #login h1 a {
		background: url('".get_bloginfo('template_url')."/library/images/login-logo.png') no-repeat scroll center top transparent;
		height: 63px;
		width: 274px;
	}
	</style>
	";
}
add_action("login_head", "four7_login_head");



?>