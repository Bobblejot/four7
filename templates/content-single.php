<?php

global $fs_framework;

while ( have_posts() ) : the_post();

	echo '<article class="' . implode( ' ', get_post_class() ) . '">';
		do_action( 'four7_single_top' );
		four7_title_section();
		do_action( 'four7_entry_meta' );
		echo '<hr>';

		echo '<div class="entry-content">';
			do_action( 'four7_single_pre_content' );
			the_content();
			echo $fs_framework->clearfix();
			do_action( 'four7_single_after_content' );
		echo '</div>';

		echo '<footer>';
			four7_meta( 'cats' );
			four7_meta( 'tags' );
			wp_link_pages( array(
				'before' => '<nav class="page-nav"><p>' . __('Pages:', 'four7'),
				'after'  => '</p></nav>'
			) );
		echo '</footer>';

		// The comments section loaded when appropriate
		if ( post_type_supports( 'post', 'comments' ) ) {
			do_action( 'four7_pre_comments' );

			if ( ! has_action( 'four7_comments_override' ) ) {
				comments_template( '/templates/comments.php' );
			} else {
				do_action( 'four7_comments_override' );
			}

			do_action( 'four7_after_comments' );
		}

		do_action( 'four7_in_article_bottom' );
	echo '</article>';
endwhile;