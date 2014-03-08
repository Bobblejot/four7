<?php
/**
 * four7 Core - A WordPress theme development framework.
 *
 * four7 Core is a framework for developing WordPress themes.  The framework allows theme developers
 * to quickly build themes without having to handle all of the "logic" behind the theme or having to code
 * complex functionality for features that are often needed in themes.  The framework does these things
 * for developers to allow them to get back to what matters the most:  developing and designing themes.
 * The framework was built to make it easy for developers to include (or not include) specific, pre-coded
 * features.  Themes handle all the markup, style, and scripts while the framework handles the logic.
 *
 * four7 Core is a modular system, which means that developers can pick and choose the features they
 * want to include within their themes.  Most files are only loaded if the theme registers support for the
 * feature using the add_theme_support( $feature ) function within their theme.
 *
 *
 * @package   four7 Core
 * @version   3.1.0
 * @author    four7 <webmaster@four7.com>
 * @copyright Copyright (c) 2008 - 2011, four7
 * @link      http://four7.com/four7-core
 */

/**
 * The four7 class launches the framework.  It's the organizational structure behind the entire framework.
 * This class should be loaded and initialized before anything else within the theme is called to properly use
 * the framework.
 *
 * After parent themes call the four7 class, they should perform a theme setup function on the
 * 'after_setup_theme' hook with a priority of 10.  Child themes should add their theme setup function on
 * the 'after_setup_theme' hook with a priority of 11.  This allows the class to load theme-supported features
 * at the appropriate time, which is on the 'after_setup_theme' hook with a priority of 12.
 *
 * @since 0.7.0
 */
class Four7_Init {

	/**
	 * Constructor method for the four7 class.  This method adds other methods of the class to
	 * specific hooks within WordPress.  It controls the load order of the required files for running
	 * the framework.
	 *
	 * @since 3.1.0
	 */
	function __construct() {

		global $four7;

		/* Set up an empty class for the global $four7 object. */
		$four7 = new stdClass;

		/* Define framework, parent theme, and child theme constants. */
		add_action( 'after_setup_theme', array( &$this, 'constants' ), 1 );

		/* Load the core functions required by the rest of the framework. */
		add_action( 'after_setup_theme', array( &$this, 'core' ), 2 );

		/* Initialize the framework's default actions and filters. */
		add_action( 'after_setup_theme', array( &$this, 'default_filters' ), 3 );

		/* Language functions and translations setup. */
		add_action( 'after_setup_theme', array( &$this, 'i18n' ), 4 );

		/* Handle theme supported features. */
		add_action( 'after_setup_theme', array( &$this, 'theme_support' ), 12 );

		/* Load the framework functions. */
		add_action( 'after_setup_theme', array( &$this, 'functions' ), 13 );

		/* Load the framework extensions. */
		add_action( 'after_setup_theme', array( &$this, 'extensions' ), 14 );

		/* Load admin files. */
		add_action( 'wp_loaded', array( &$this, 'admin' ) );

	}

	/**
	 * Defines the constant paths for use within the core framework, parent theme, and child theme.
	 * Constants prefixed with 'four7_' are for use only within the core framework and don't
	 * reference other areas of the parent or child theme.
	 *
	 * @since 3.1.0
	 */
	function constants() {

		/* Sets the framework version number. */
		define( 'four7_VERSION', '3.3.0' );

		/* Sets the path to the parent theme directory. */
		define( 'THEME_DIR', get_template_directory() );

		/* Sets the path to the parent theme directory URI. */
		define( 'THEME_URI', get_template_directory_uri() );

		/* Sets the path to the child theme directory. */
		define( 'CHILD_THEME_DIR', get_stylesheet_directory() );

		/* Sets the path to the child theme directory URI. */
		define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );

		/* Sets the path to the core framework directory. */
		define( 'four7_DIR', trailingslashit( THEME_DIR ) . basename( dirname( __FILE__ ) ) );

		/* Sets the path to the core framework directory URI. */
		define( 'four7_URI', trailingslashit( THEME_URI ) . basename( dirname( __FILE__ ) ) );

		/* Sets the path to the core framework classes directory. */
		define( 'four7_ADMIN', trailingslashit( four7_DIR ) . 'admin' );

		/* Sets the path to the core framework classes directory. */
		define( 'four7_ADMIN_ASSETS', trailingslashit( four7_URI ) . 'admin/assets' );

		/* Sets the path to the core framework classes directory. */
		define( 'four7_FRAMEWORK', trailingslashit( four7_DIR ) . 'framework' );

		/* Sets the path to the core framework classes directory. */
		define( 'four7_CORE', trailingslashit( four7_DIR ) . 'framework/core' );

		/* Sets the path to the core framework classes directory. */
		define( 'four7_WIDGETS', trailingslashit( four7_DIR ) . 'widgets' );

		/* Sets the path to the core framework classes directory. */
		define( 'four7_CLASSES', trailingslashit( four7_DIR ) . 'classes' );

		/* Sets the path to the core framework extensions directory. */
		define( 'four7_EXTENSIONS', trailingslashit( four7_DIR ) . 'extensions' );

		/* Sets the path to the core framework functions directory. */
		define( 'four7_FUNCTIONS', trailingslashit( four7_DIR ) . 'functions' );

		/* Sets the path to the core framework languages directory. */
		define( 'four7_LOCALIZATION', trailingslashit( four7_DIR ) . 'localization' );

		/* Sets the path to the core framework images directory URI. */
		define( 'four7_ASSETS', trailingslashit( THEME_URI ) . 'assets' );

		/* Sets the path to the core framework images directory URI. */
		define( 'four7_IMAGES', trailingslashit( THEME_URI ) . 'assets/img' );

		/* Sets the path to the core framework CSS directory URI. */
		define( 'four7_CSS', trailingslashit( THEME_URI ) . 'assets/css' );

		/* Sets the path to the core framework JavaScript directory URI. */
		define( 'four7_JS', trailingslashit( THEME_URI ) . 'assets/js' );

		/* Sets the path to the core framework fonts directory URI. */
		define( 'four7_FONTS', trailingslashit( THEME_URI ) . 'assets/fonts' );

	}

	/**
	 * Loads the core framework functions.  These files are needed before loading anything else in the
	 * framework because they have required functions for use.
	 *
	 * @since 3.1.0
	 */
	function core() {

		/* Load the core framework functions. */
		require_once( trailingslashit( four7_CLASSES ) . 'class-Four7_Color.php' );

		/* Load the core framework functions. */
		require_once( trailingslashit( four7_CLASSES ) . 'class-Four7_Image.php' );

		/* Load the core framework functions. */
		require_once( trailingslashit( four7_CLASSES ) . 'class-TGM_Plugin_Activation.php' );

		/* Load media-related functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'dependencies.php' );

		/* Load the core framework functions. */
		require_once( trailingslashit( four7_FRAMEWORK ) . 'framework.php' );

		/* Load the core framework functions. */
		require_once( trailingslashit( four7_CORE ) . 'core.php' );

		/* Load the context-based functions. */
		require_once( trailingslashit( four7_CORE ) . 'context.php' );

		/* Load the core framework internationalization functions. */
		require_once( trailingslashit( four7_CORE ) . 'i18n.php' );
	}

	/**
	 * Loads both the parent and child theme translation files.  If a locale-based functions file exists
	 * in either the parent or child theme (child overrides parent), it will also be loaded.  All translation
	 * and locale functions files are expected to be within the theme's '/languages' folder, but the
	 * framework will fall back on the theme root folder if necessary.  Translation files are expected
	 * to be prefixed with the template or stylesheet path (example: 'templatename-en_US.mo').
	 *
	 * @since 3.1.0
	 */
	function i18n() {
		global $four7;

		/* Get parent and child theme textdomains. */
		$parent_textdomain = four7_get_parent_textdomain();
		$child_textdomain  = four7_get_child_textdomain();

		/* Load the framework textdomain. */
		$four7->textdomain_loaded['four7-core'] = four7_load_framework_textdomain( 'four7-core' );

		/* Load theme textdomain. */
		$four7->textdomain_loaded[$parent_textdomain] = load_theme_textdomain( $parent_textdomain );

		/* Load child theme textdomain. */
		$four7->textdomain_loaded[$child_textdomain] = is_child_theme() ? load_child_theme_textdomain( $child_textdomain ) : false;

		/* Get the user's locale. */
		$locale = get_locale();

		/* Locate a locale-specific functions file. */
		$locale_functions = locate_template( array( "library/localization/{$locale}.php", "{$locale}.php" ) );

		/* If the locale file exists and is readable, load it. */
		if ( ! empty( $locale_functions ) && is_readable( $locale_functions ) ) {
			require_once( $locale_functions );
		}
	}

	/**
	 * Removes theme supported features from themes in the case that a user has a plugin installed
	 * that handles the functionality.
	 *
	 * @since 1.3.0
	 */
	function theme_support() {

		/* Remove support for the core SEO component if the WP SEO plugin is installed. */
		if ( defined( 'WPSEO_VERSION' ) ) {
			remove_theme_support( 'four7-core-seo' );
		}

		/* Remove support for the the Get the Image extension if the plugin is installed. */
		if ( function_exists( 'get_the_image' ) ) {
			remove_theme_support( 'get-the-image' );
		}
	}


	/**
	 * Loads the framework functions.  Many of these functions are needed to properly run the
	 * framework.  Some components are only loaded if the theme supports them.
	 *
	 * @since 0.7.0
	 */
	function functions() {

		/* Load media-related functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'cleanup.php' );

		/* Load media-related functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'config.php' );

		/* Load media-related functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'redux-init.php' );

		/* Load media-related functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'media.php' );

		/* Load the metadata functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'meta.php' );

		/* Load the utility functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'utils.php' );

		/* Load the template functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'sidebar.php' );

		/* Load the template functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'titles.php' );

		/* Load the template functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'template.php' );

		/* Load the template functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'wrapper.php' );

		/* Load the template functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'footer.php' );

		/* Load the template functions. */
		require_once( trailingslashit( four7_FUNCTIONS ) . 'post-formats.php' );


		/* Load the menus functions if supported. */
		require_if_theme_supports( 'four7-bbpress', trailingslashit( four7_FUNCTIONS ) . 'bbpress.php' );

		/* Load the menus functions if supported. */
		require_if_theme_supports( 'four7-core-menus', trailingslashit( four7_FUNCTIONS ) . 'menus.php' );

		/* Load the core SEO component. */
		require_if_theme_supports( 'four7-core-seo', trailingslashit( four7_CORE ) . 'core-seo.php' );

		/* Load the shortcodes if supported. */
		//	require_if_theme_supports( 'four7-core-shortcodes', trailingslashit( four7_FUNCTIONS ) . 'shortcodes.php' );

		/* Load the sidebars if supported. */
		//	require_if_theme_supports( 'four7-core-sidebars', trailingslashit( four7_FUNCTIONS ) . 'sidebars.php' );

		/* Load the scripts if supported. */
		require_if_theme_supports( 'four7-core-scripts', trailingslashit( four7_FUNCTIONS ) . 'scripts.php' );

		/* Load the media grabber script if supported. */
		require_if_theme_supports( 'four7-core-media-grabber', trailingslashit( four7_FUNCTIONS ) . 'media-grabber.php' );

		/* Load the widgets if supported. */
		require_if_theme_supports( 'four7-core-widgets', trailingslashit( four7_FUNCTIONS ) . 'widgets.php' );


	}

	/**
	 * Load extensions (external projects).  Extensions are projects that are included within the
	 * framework but are not a part of it.  They are external projects developed outside of the
	 * framework.  Themes must use add_theme_support( $extension ) to use a specific extension
	 * within the theme.  This should be declared on 'after_setup_theme' no later than a priority of 11.
	 *
	 * @since 3.1.0
	 */
	function extensions() {

		/* Load the Gallery extension if supported and the plugin isn't active. */
		if ( ! function_exists( 'four7_gallery' ) ) {
			require_if_theme_supports( 'gallery', trailingslashit( four7_EXTENSIONS ) . 'gallery.php' );
		}

		/* Load the Get the Image extension if supported and the plugin isn't active. */
		if ( ! function_exists( 'get_the_image' ) ) {
			require_if_theme_supports( 'get-the-image', trailingslashit( four7_EXTENSIONS ) . 'get-the-image.php' );
		}

		/* Load the Cleaner Caption extension if supported. */
		require_if_theme_supports( 'comments', trailingslashit( four7_EXTENSIONS ) . 'comments.php' );

		require_if_theme_supports( 'aqResize', trailingslashit( four7_EXTENSIONS ) . 'aq_resizer.php' );

		//	require_if_theme_supports( 'pages', trailingslashit( four7_EXTENSIONS ) . 'pages-walker.php' );
	}

	/**
	 * Load admin files for the framework.
	 *
	 * @since 0.7.0
	 */
	function admin() {

		/* Check if in the WordPress admin. */
		if ( is_admin() ) {

			/* Load the main admin file. */
			require_once( trailingslashit( four7_ADMIN ) . 'admin.php' );

			/* Load the theme settings feature if supported. */
			//	require_if_theme_supports( 'hybrid-core-theme-settings', trailingslashit( four7_ADMIN ) . 'theme-settings.php' );
		}
	}

	/**
	 * Adds the default framework actions and filters.
	 *
	 * @since 3.1.0
	 */
	function default_filters() {

		/* Remove bbPress theme compatibility if current theme supports bbPress. */
		if ( current_theme_supports( 'bbpress' ) ) {
			remove_action( 'bbp_init', 'bbp_setup_theme_compat', 8 );
		}

		/* Add the theme info to the header (lets theme developers give better support). */
		add_action( 'wp_head', 'four7_meta_template', 1 );

		/* Filter the textdomain mofile to allow child themes to load the parent theme translation. */
		add_filter( 'load_textdomain_mofile', 'four7_load_textdomain_mofile', 10, 2 );

		/* Filter text strings for four7 Core and extensions so themes can serve up translations. */
		add_filter( 'gettext', 'four7_gettext', 1, 3 );
		add_filter( 'gettext', 'four7_extensions_gettext', 1, 3 );

		/* Make text widgets and term descriptions shortcode aware. */
		add_filter( 'widget_text', 'do_shortcode' );
		add_filter( 'term_description', 'do_shortcode' );
	}
}

?>
