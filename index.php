<?php

if ( ! has_action( 'four7_page_header_override' ) ) {
	fs_get_template_part( 'templates/page', 'header' );
} else {
	do_action( 'four7_page_header_override' );
}

do_action( 'four7_index_begin' );

if ( ! have_posts() ) {
	echo '<div class="alert alert-warning">' . __( 'Sorry, no results were found.', 'four7' ) . '</div>';
	get_search_form();
}

if ( ! has_action( 'four7_override_index_loop' ) ) {
	while ( have_posts() ) : the_post();
		do_action( 'four7_in_loop_start' );

		if ( ! has_action( 'four7_content_override' ) ) {
			fs_get_template_part( 'templates/content', get_post_format() );
		} else {
			do_action( 'four7_content_override' );
		}

		do_action( 'four7_in_loop_end' );
	endwhile;
} else {
	do_action( 'four7_override_index_loop' );
}

do_action( 'four7_index_end' );

echo four7_pagination_toggler();