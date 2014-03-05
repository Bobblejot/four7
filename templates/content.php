<?php

global $fs_framework;

echo '<article '; post_class(); echo '>';

	do_action( 'four7_in_article_top' );
	four7_title_section( true, 'h2', true );
	do_action( 'four7_entry_meta' );

	echo '<div class="entry-summary">';
		echo apply_filters( 'four7_do_the_excerpt', get_the_excerpt() );
		echo $fs_framework->clearfix();
	echo '</div>';

	if ( has_action( 'four7_entry_footer' ) ) {
		echo '<footer class="entry-footer">';
		do_action( 'four7_entry_footer' );
		echo '</footer>';
	}

	do_action( 'four7_in_article_bottom' );

echo '</article>';