<?php global $fs_framework; ?>
<?php echo get_avatar($comment, $size = '64'); ?>
<div class="media-body">
	<h4 class="media-heading"><?php echo get_comment_author_link(); ?></h4>
	<time datetime="<?php echo comment_date('c'); ?>"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>"><?php printf(__('%1$s', 'four7'), get_comment_date(),  get_comment_time()); ?></a></time>
	<?php edit_comment_link(__('(Edit)', 'four7'), '', ''); ?>

	<?php if ($comment->comment_approved == '0') : ?>
		<?php echo $fs_framework->alert( 'info', __( 'Your comment is awaiting moderation.', 'four7' ) ); ?>
	<?php endif; ?>

	<?php comment_text(); ?>
	<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
