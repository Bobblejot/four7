<?php ss_get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>
<a href="#content" class="sr-only"><?php _e( 'Skip to main content', 'four7' ); ?></a>
<?php global $fs_framework; ?>

	<!--[if lt IE 8]>
		<?php echo $fs_framework->alert( 'warning', __('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'four7') ); ?>
	<![endif]-->

	<?php do_action( 'get_header' ); ?>

	<?php ss_get_template_part( 'templates/top-bar' ); ?>

	<?php do_action( 'four7_pre_wrap' ); ?>

	<?php echo $fs_framework->make_container( 'div', 'wrap-main-section', 'wrap main-section' ); ?>

		<?php do_action('four7_pre_content'); ?>

		<div id="content" class="content">
			<?php echo $fs_framework->make_row( 'div', null, 'bg' ); ?>

				<?php do_action( 'four7_pre_main' ); ?>

				<main class="main <?php four7_section_class( 'main', true ); ?>" <?php if ( is_home() ) { echo 'id="home-blog"'; } ?> role="main">
					<?php include four7_template_path(); ?>
				</main><!-- /.main -->

				<?php do_action('four7_after_main'); ?>

				<?php if ( four7_display_primary_sidebar() ) : ?>
					<aside id="sidebar-primary" class="sidebar <?php four7_section_class( 'primary', true ); ?>" role="complementary">
						<?php if ( ! has_action( 'four7_sidebar_override' ) ) {
							include four7_sidebar_path();
						} else {
							do_action( 'four7_sidebar_override' );
						} ?>
					</aside><!-- /.sidebar -->
				<?php endif; ?>

				<?php do_action( 'four7_post_main' ); ?>

				<?php if ( four7_display_secondary_sidebar() ) : ?>
					<aside id="sidebar-secondary" class="sidebar secondary <?php four7_section_class( 'secondary', true ); ?>" role="complementary">
						<?php dynamic_sidebar( 'sidebar-secondary' ); ?>
					</aside><!-- /.sidebar -->
				<?php endif; ?>
			</div>
		</div><!-- /.content -->
		<?php do_action('four7_after_content'); ?>
	</div><!-- /.wrap -->
	<?php

	do_action('four7_pre_footer');

	if ( ! has_action( 'four7_footer_override' ) ) {
		ss_get_template_part( 'templates/footer' );
	} else {
		do_action( 'four7_footer_override' );
	}

	do_action( 'four7_after_footer' );

	wp_footer();

	?>
</body>
</html>