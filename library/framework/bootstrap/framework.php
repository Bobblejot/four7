<?php

global $fs_settings;

// Include the framework class
include_once( dirname( __FILE__ ) . '/class-FOUR7_Framework_Bootstrap.php' );

if ( ! is_null( $fs_settings['framework'] ) && $fs_settings['framework'] == 'bootstrap' ) {
	define( 'FOUR7_FRAMEWORK_PATH', dirname( __FILE__ ) );
}

/**
 * Define the framework.
 * These will be used in the redux admin option to choose a framework.
 */
function four7_define_framework_bootstrap() {
	$framework = array(
		'shortname' => 'bootstrap',
		'name'      => 'Bootstrap',
		'classname' => 'FOUR7_Framework_Bootstrap',
		'compiler'  => 'less_php'
	);

	return $framework;
}

/**
 * Add the framework to redux
 */
function four7_add_framework_bootstrap( $frameworks ) {
	$frameworks[] = four7_define_framework_bootstrap();

	return $frameworks;
}
add_filter( 'four7_frameworks_array', 'four7_add_framework_bootstrap' );