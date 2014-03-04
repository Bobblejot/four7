<?php
/**
 * Enqueue scripts and stylesheets
 */
function four7_scripts() {

	$stylesheet_url = apply_filters( 'four7_main_stylesheet_url', four7_CSS . '/style-default.css' );
	$stylesheet_ver = apply_filters( 'four7_main_stylesheet_ver', null );

	wp_enqueue_style( 'four7_css', $stylesheet_url, false, $stylesheet_ver );

	wp_register_script( 'modernizr',         four7_JS . '/vendor/modernizr-2.7.0.min.js', false, null, false );
	wp_register_script( 'four7_main',    four7_JS . '/main.js',                       false, null, true  );
	wp_register_script( 'fitvids',           four7_JS . '/vendor/jquery.fitvids.js',      false, null, true  );

	wp_enqueue_script( 'jquery'            );

	wp_enqueue_script( 'modernizr'         );
	wp_enqueue_script( 'four7_main'    );
	wp_enqueue_script( 'fitvids' );

	if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'four7_scripts', 100 );