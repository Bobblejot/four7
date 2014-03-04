<?php

global $fs_settings;

require_once dirname( __FILE__ ) . '/class-FOUR7_Framework.php';

do_action( 'four7_include_frameworks' );

if ( ! defined( 'FOUR7_FRAMEWORK' ) ) {
	if ( ! is_null( $fs_settings['framework'] ) && ! empty( $fs_settings['framework'] ) ) {
		$active_framework = $fs_settings['framework'];
	} else {
		$active_framework = 'bootstrap';
	}
} else {
	if ( FOUR7_FRAMEWORK != $fs_settings['framework'] ) {
		$fs_settings['framework'] = FOUR7_FRAMEWORK;
		update_option( FOUR7_OPT_NAME, $fs_settings );
	}
	$active_framework = FOUR7_FRAMEWORK;
}

$frameworks = apply_filters( 'four7_frameworks_array', array() );

// Return the classname of the active framework.
foreach ( $frameworks as $framework ) {
	if ( $active_framework == $framework['shortname'] ) {
		$active   = $framework['classname'];
	}
}

global $fs_framework;
$fs_framework = new $active;