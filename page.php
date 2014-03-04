<?php

if ( ! has_action( 'four7_content_page_override' ) ) {
	fs_get_template_part( 'templates/content', 'page' );
} else {
	do_action( 'four7_content_page_override' );
}
