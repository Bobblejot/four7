<?php

/**
 * Define which pages shouldn't have the primary sidebar
 *
 * See lib/sidebar.php for more details
 */
function four7_display_primary_sidebar() {
	$sidebar_config = new Four7_Sidebar(
		array(
			'is_404',
			'is_front_page'
		),
		array(
			'template-0.php'
		)
	);

	return apply_filters( 'four7_display_primary_sidebar', $sidebar_config->display );
}

/**
 * Define which pages shouldn't have the secondary sidebar
 *
 * See lib/sidebar.php for more details
 */
function four7_display_secondary_sidebar() {
	$sidebar_config = new Four7_Sidebar(
		array(
			'is_404',
			'is_front_page'
		),
		array(
			'template-0.php',
			'template-1.php',
			'template-2.php'
		)
	);

	return apply_filters( 'four7_display_secondary_sidebar', $sidebar_config->display );
}


if ( ! function_exists( 'four7_section_class' ) ) :
	/*
	 * Calculates the classes of the main area, main sidebar and secondary sidebar
	 */
	function four7_section_class( $target, $echo = false ) {
		global $redux, $fs_framework;
		// Disable the wrapper by default
		$wrapper = NULL;

		if ( four7_display_primary_sidebar() ) {
			// Both sidebars are displayed
			if ( four7_display_secondary_sidebar() ) {
				if ( is_page_template( 'template-5.php' ) ) {
					$main    = $fs_framework->column_classes( array( 'medium' => 8 ), 'string' );
					$primary = $fs_framework->column_classes( array( 'medium' => 4 ), 'string' );
				} else {
					$main    = $fs_framework->column_classes( array( 'medium' => 7 ), 'string' );
					$primary = $fs_framework->column_classes( array( 'medium' => 3 ), 'string' );
				}

				$secondary = $fs_framework->column_classes( array( 'medium' => 2 ), 'string' );

				if ( is_page_template( 'template-5.php' ) ) {
					$wrapper = $fs_framework->column_classes( array( 'medium' => 10 ), 'string' ) . 'right';
				} else {
					$wrapper = NULL;
				}

				// Only the primary sidebar is displayed
			} else {
				$main    = $fs_framework->column_classes( array( 'medium' => 8 ), 'string' );
				$primary = $fs_framework->column_classes( array( 'medium' => 4 ), 'string' );
			}
		} else {
			// Only the secondary sidebar is displayed
			if ( four7_display_secondary_sidebar() ) {
				$main      = $fs_framework->column_classes( array( 'medium' => 8 ), 'string' );
				$secondary = $fs_framework->column_classes( array( 'medium' => 4 ), 'string' );
			} else {
				// No sidebars displayed
				$main = $fs_framework->column_classes( array( 'medium' => 12 ), 'string' );
			}
		}

		// Add floats where needed.
		if ( is_page_template( 'template-2.php' ) || is_page_template( 'template-3.php' ) ) {
			$main .= ' pull-right';
		}

		if ( $target == 'primary' ) {
			$class = apply_filters( 'four7_section_class_primary', $primary );
		} elseif ( $target == 'secondary' ) {
			$class = apply_filters( 'four7_section_class_secondary', $secondary );
		} elseif ( $target == 'wrapper' ) {
			$class = apply_filters( 'four7_section_class_wrapper', $wrapper );
		} else {
			$class = apply_filters( 'four7_section_class_main', $main );
		}

		if ( is_array( $class ) ) {
			$class = implode( ' ', $class );
		}

		// echo or return the result.
		if ( $echo ) {
			echo $class;
		} else {
			return $class;
		}
	}
endif;


/*
 * Some templates require an additional wrapper div around the Main Area and the Primary Sidebar.
 * This function creates the opening <div> tag with the appropriate classes to be used.
 */
function four7_mp_wrap_div_open() {
	global $fs_framework;

	if ( $fs_framework->include_wrapper() ) {
		echo '<div class="mp_wrap ' . four7_section_class( 'wrapper' ) . '">' . $fs_framework->make_row( 'div' );
	}
}

/*
 * Closes the <div> opened by the four7_mp_wrap_div_open() function.
 */
function four7_mp_wrap_div_close() {
	global $fs_framework;

	if ( $fs_framework->include_wrapper() ) {
		echo '</div></div>';
	}
}

/*
 * Adds the actions to open and close the wrapper divs when necessary.
 *
 * Uses the four7_mp_wrap_div_open() and four7_mp_wrap_div_close() functions.
 */
function four7_mp_wrap_div_toggler() {
	$wrapper = four7_section_class( 'wrapper' );
	if ( ! is_null( $wrapper ) && ! empty( $wrapper ) ) {
		add_action( 'four7_pre_main', 'four7_mp_wrap_div_open', 999 );
		add_action( 'four7_post_main', 'four7_mp_wrap_div_close', 999 );
	}
}

add_action( 'wp', 'four7_mp_wrap_div_toggler' );

/**
 * $content_width is a global variable used by WordPress for max image upload sizes
 * and media embeds (in pixels).
 *
 * Example: If the content area is 640px wide, set $content_width = 620; so images and videos will not overflow.
 * Default: 1140px is the default Bootstrap container width.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1140;
}