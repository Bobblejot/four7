<?php
/**
 * Sets up the core framework's widgets and unregisters some of the default WordPress widgets if the 
 * theme supports this feature.  The framework's widgets are meant to extend the default WordPress
 * widgets by giving users highly-customizable widget settings.  A theme must register support for the 
 * 'four7-core-widgets' feature to use the framework widgets.
 *
 * @package four7 Framework
 * @subpackage Functions
 * @author inevisys
 * @copyright Copyright (c) 2013 - 2014, inevisys
 * @link http://inevisys.com/four7
 *
 */

/* Unregister WP widgets. */
add_action( 'widgets_init', 'four7_unregister_widgets' );

/* Register four7 widgets. */
add_action( 'widgets_init', 'four7_register_widgets' );

/* Register four7 sidebars. */
add_action( 'widgets_init', 'four7_sidebars_init' );

/**
 * Registers the core frameworks widgets.  These widgets typically overwrite the equivalent default WordPress
 * widget by extending the available options of the widget.
 *
 * @since 1.0.0
 * @uses register_widget() Registers individual widgets with WordPress
 * @link http://codex.wordpress.org/Function_Reference/register_widget
 */
 
function four7_register_widgets() {

    /* Load the advert grid widget class. */
    require_once( trailingslashit( four7_WIDGETS ) . 'widget-advertgrid.php' );

	/* Load the archives widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-archives.php' );

	/* Load the authors widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-authors.php' );

	/* Load the bookmarks widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-bookmarks.php' );

	/* Load the calendar widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-calendar.php' );

	/* Load the categories widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-categories.php' );
	
	/* Load the comments widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-comments.php' );
	
	/* Load the contact info widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-contact_info.php' );
	
	/* Load the facebook like widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-facebook-like.php' );	
	
	/* Load the flickr widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-flickr.php' );	
	
	/* Load the infocus widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-infocus.php' );

	/* Load the nav menu widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-nav-menu.php' );

	/* Load the pages widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-pages.php' );
	
	/* Load the portfolio grid widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-portfolio-grid.php' );
	
	/* Load the portfolio widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-portfolio.php' );
	
	/* Load the posts widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-posts.php' );
	
	/* Load the recent works widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-recent-works.php' );
	
	/* Load the rss twitter widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-rsstwitter.php' );

	/* Load the search widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-search.php' );
	
	/* Load the social icons widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-socialicons.php' );
	
	/* Load the tabbed widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-tabbed.php' );

	/* Load the tags widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-tags.php' );
		
	/* Load the thumbnails widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-thumbnails.php' );
	
	/* Load the tweets widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-tweets.php' );
	
	/* Load the twitter widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-twitter.php' );
	
	/* Load the video widget class. */
	require_once( trailingslashit( four7_WIDGETS ) . 'widget-video.php' );

    /* Register the adevrt grid widget. */
    register_widget( 'four7_Widget_Advertgrid' );
    
	/* Register the archives widget. */
	register_widget( 'four7_Widget_Archives' );

	/* Register the authors widget. */
	register_widget( 'four7_Widget_Authors' );

	/* Register the bookmarks widget. */
	register_widget( 'four7_Widget_Bookmarks' );

	/* Register the calendar widget. */
	register_widget( 'four7_Widget_Calendar' );

	/* Register the categories widget. */
	register_widget( 'four7_Widget_Categories' );
	
	/* Register the comments widget. */
	register_widget( 'four7_Widget_Comments' );
	
	/* Register the contact info widget. */
	register_widget( 'four7_Widget_Contact_Info' );
	
	/* Register the facebook like widget. */
	register_widget( 'four7_Widget_facebook_Like' );
	
	/* Register the flickr widget. */
	register_widget( 'four7_Widget_flickr' );
	
	/* Register the infocus widget. */
	register_widget( 'four7_Widget_InFocus' );

	/* Register the nav menu widget. */
	register_widget( 'four7_Widget_Nav_Menu' );

	/* Register the pages widget. */
	register_widget( 'four7_Widget_Pages' );
	
	/* Register the portfolio grid widget. */
	register_widget( 'four7_Widget_Portfolio_Grid' );
	
	/* Register the portfolio widget. */
	register_widget( 'four7_Widget_Portfolio' );
	
	/* Register the recent works widget. */
	register_widget( 'four7_Widget_Recent_Works' );
	
	/* Register the rss twitter widget. */
	register_widget( 'four7_Widget_Rsstwitter' );

	/* Register the search widget. */
	register_widget( 'four7_Widget_Search' );
	
	/* Register the social icons widget. */
	register_widget( 'four7_Widget_Socialicons' );
	
	/* Register the tabbed widget. */
	register_widget( 'four7_Widget_Tabbed' );

	/* Register the tags widget. */
	register_widget( 'four7_Widget_Tags' );
		
	/* Register the thumbnails widget. */
	register_widget( 'four7_Widget_Thumbnails' );
	
	/* Register the tweets widget. */
	register_widget( 'four7_Widget_Tweets' );
	
	/* Register the twitter widget. */
	register_widget( 'four7_Widget_Twitter' );
	
	/* Register the video widget. */
	register_widget( 'four7_Widget_Video' );
	
}

/**
 * Registers the core frameworks sidebars.  These widgets typically overwrite the equivalent default WordPress
 * widget by extending the available options of the widget.
 *
 * @since 1.0.0
 * @uses register_widget() Registers individual widgets with WordPress
 * @link http://codex.wordpress.org/Function_Reference/register_widget
 */
function four7_sidebars_init() {
	$class        = apply_filters( 'four7_widgets_class', '' );
	$before_title = apply_filters( 'four7_widgets_before_title', '<h3 class="widget-title">' );
	$after_title  = apply_filters( 'four7_widgets_after_title', '</h3>' );

	// Sidebars
	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'four7' ),
		'id'            => 'sidebar-primary',
		'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => $before_title,
		'after_title'   => $after_title,
	));

	register_sidebar( array(
		'name'          => __( 'Secondary Sidebar', 'four7' ),
		'id'            => 'sidebar-secondary',
		'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => $before_title,
		'after_title'   => $after_title,
	));
}

/**
 * Unregister default WordPress widgets that are replaced by the framework's widgets.  Widgets that
 * aren't replaced by the framework widgets are not unregistered.
 *
 * @since 1.0.0
 * @uses unregister_widget() Unregisters a registered widget.
 * @link http://codex.wordpress.org/Function_Reference/unregister_widget
 */
function four7_unregister_widgets() {

	/* Unregister the default WordPress widgets. */
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Nav_Menu_Widget' );
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
}

?>