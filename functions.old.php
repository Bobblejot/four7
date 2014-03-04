<?php

if ( ! defined( 'FOUR7_FRAMEWORK' ) ) {
	// Define bootstrap as the default framework.
	// Other frameworks can be added via plugins and override this.
	define( 'FOUR7_FRAMEWORK', 'bootstrap' );
}

// define the 'four7_ASSETS' constant.
if ( ! defined( 'four7_ASSETS' ) ) {
	define( 'four7_ASSETS', get_template_directory_uri() . '/assets' ); 
}

/*
 * The option that is used by four7 in the database for all settings.
 *
 * This can be overriden by adding this in your wp-config.php:
 * define( 'FOUR7_OPT_NAME', 'custom_option' )
 */
 
if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/framework/redux-framework/ReduxCore/framework.php' ) ) {
    require_once( dirname( __FILE__ ) . '/framework/redux-framework/ReduxCore/framework.php' );
}

if ( ! defined( 'FOUR7_OPT_NAME' ) ) {
	define( 'FOUR7_OPT_NAME', 'four7' );
}

global $fs_settings;
$fs_settings = get_option( FOUR7_OPT_NAME );

/* CLASSES */
require_once locate_template('/library/classes/class-Four7_Color.php');
require_once locate_template('/library/classes/class-Four7_Image.php');
require_once locate_template( '/library/classes/class-TGM_Plugin_Activation.php' );


require_once locate_template('/library/functions-core.php');
require_once locate_template('/library/redux-init.php');

// Get the framework
require_once locate_template( '/framework/framework.php' );

require_once locate_template( '/library/template.php' );     // Custom get_template_part function.
require_once locate_template( '/library/utils.php' );        // Utility functions
require_once locate_template( '/library/init.php' );         // Initial theme setup and constants
require_once locate_template( '/library/wrapper.php' );      // Theme wrapper class
require_once locate_template( '/library/sidebar.php' );      // Sidebar class
require_once locate_template( '/library/footer.php' );       // Footer configuration
require_once locate_template( '/library/config.php' );       // Configuration
require_once locate_template( '/library/titles.php' );       // Page titles
require_once locate_template( '/library/cleanup.php' );      // Cleanup
require_once locate_template( '/library/comments.php' );     // Custom comments modifications
require_once locate_template( '/library/meta.php' );         // Tags
require_once locate_template( '/library/widgets.php' );      // Sidebars and widgets
require_once locate_template( '/library/post-formats.php' ); // Sidebars and widgets
require_once locate_template( '/library/scripts.php' );      // Scripts and stylesheets


//require_once locate_template( '/library/dependencies.php' );                // load our dependencies


if ( class_exists( 'bbPress' ) ) {
	require_once locate_template( '/library/bbpress.php' );      // Scripts and stylesheets
}

do_action( 'four7_include_files' );