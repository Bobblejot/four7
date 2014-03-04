<?php

global $fs_framework;

while ( have_posts() ) : the_post();
	four7_title_section();
	do_action( 'four7_entry_meta' );
	do_action( 'four7_page_pre_content' );
	the_content();
	echo $fs_framework->clearfix();
	four7_meta( 'cats' );
	four7_meta( 'tags' );
	do_action( 'four7_page_after_content' );

	wp_link_pages( array( 'before' => '<nav class="pagination">', 'after' => '</nav>' ) );
endwhile;