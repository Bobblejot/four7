<?php global $fs_framework; ?>
<article <?php post_class(); ?>>
	<?php do_action( 'four7_in_article_top' ); ?>
	<?php four7_title_section( true, 'h2', true ); ?>
	<?php do_action( 'four7_entry_meta' ); ?>
	<div class="entry-summary">
		<?php echo apply_filters( 'four7_do_the_excerpt', get_the_excerpt() ); ?>
		<?php echo $fs_framework->clearfix(); ?>
	</div>
	<?php
	if ( has_action( 'four7_entry_footer' ) ) :
		echo '<footer class="entry-footer">';
		do_action( 'four7_entry_footer' );
		echo '</footer>';
	endif;
	?>
	<?php do_action( 'four7_in_article_bottom' ); ?>
</article>