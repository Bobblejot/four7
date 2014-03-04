<?php

if ( ! has_action( 'four7_content_single_override' ) ) {
	fs_get_template_part( 'templates/content', 'single' );
} else {
	do_action( 'four7_content_single_override' );
}
