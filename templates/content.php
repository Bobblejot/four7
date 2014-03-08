<?php

global $fs_framework;

echo '<article '; post_class(); echo '>';

	do_action( 'four7_in_article_top' );
	four7_title_section( true, 'h2', true );
	do_action( 'four7_entry_meta' );
	echo '<hr>';

	echo '<div class="entry-content">';
		the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'four7' ) );
    echo $fs_framework->clearfix();
	echo '</div>';

	if ( has_action( 'four7_entry_footer' ) ) {
		echo '<footer class="entry-footer">';
		do_action( 'four7_entry_footer' );
		echo '</footer>';
	}

	do_action( 'four7_in_article_bottom' );

echo '</article>';