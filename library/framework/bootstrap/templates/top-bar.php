<header id="banner-header" class="banner <?php echo apply_filters( 'four7_navbar_class', 'navbar navbar-default' ); ?>" role="banner">
	<div class="<?php echo apply_filters( 'four7_navbar_container_class', 'container' ); ?>">
		<div class="navbar-header">
			<?php echo apply_filters( 'four7_nav_toggler', '
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-main, .nav-extras">
				<span class="sr-only">' . __( 'Toggle navigation', 'four7' ) . '</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>' ); ?>
			<?php echo apply_filters( 'four7_navbar_brand', '<a class="navbar-brand text" href="' . home_url('/') . '">' . get_bloginfo( 'name' ) . '</a>' ); ?>
		</div>
		<?php if ( has_action( 'four7_pre_main_nav' ) ) : ?>
			<div class="nav-extras">
				<?php do_action( 'four7_pre_main_nav' ); ?>
			</div>
		<?php endif; ?>
		<nav class="nav-main navbar-collapse collapse" role="navigation">
			<?php
			do_action( 'four7_inside_nav_begin' );
			if ( has_nav_menu( 'primary_navigation' ) )
				wp_nav_menu( array( 'theme_location' => 'primary_navigation', 'menu_class' => apply_filters( 'four7_nav_class', 'navbar-nav nav' ) ) );

			do_action( 'four7_inside_nav_end' );
			?>
		</nav>
		<?php do_action( 'four7_post_main_nav' ); ?>
	</div>
</header>