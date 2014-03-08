<?php


if ( ! class_exists( 'Four7_Author_Box' ) ) {

	/**
	 * This class handles the Breadcrumbs generation and display
	 */
	class Four7_Author_Box {

		/**
		 * Class constructor
		 */
		function __construct() {
		}


		/**
		 * Display or return the full breadcrumb path.
		 *
		 * @param string $before  The prefix for the breadcrumb, usually something like "You're here".
		 * @param string $after   The suffix for the breadcrumb.
		 * @param bool   $display When true, echo the breadcrumb, if not, return it as a string.
		 *
		 * @return string
		 */
		function single_author( $display = true ) {

			global $fs_framework, $fs_settings;

			$metas = $fs_settings['four7_entry_author_config'];
			$class = $fs_settings['authorbox_panel_class'];

			$content      = '';
			$social_links = '';

			$panel_open         = $fs_framework->open_panel( 'panel-' . $class . ' post-author-box' );
			$panel_close        = $fs_framework->close_panel();
			$panel_head_open    = $fs_framework->open_panel_heading();
			$panel_head_close   = $fs_framework->close_panel_heading();
			$panel_title        = sprintf( esc_attr__( 'Article written by %s', 'four7' ), '<a href=' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '>' . get_the_author_meta( 'display_name' ) . '</a>' );
			$avatar_image       = get_avatar( get_the_author_meta( 'user_email' ), 'auto' );
			$description        = wpautop( get_the_author_meta( 'description' ) );
			$panel_body_open    = $fs_framework->open_panel_body();
			$panel_body_close   = $fs_framework->close_panel_body();
			$panel_footer_open  = $fs_framework->open_panel_footer();
			$panel_footer_close = $fs_framework->close_panel_footer();


			if ( is_array( $metas ) ) {
				foreach ( $metas as $meta => $value ) {

					if ( $meta == 'twitter' && ! empty( $value ) ) {
						if ( $twitter = get_the_author_meta( 'twitter' ) ) {
							$social_links .= '<a class ="twitter" href="' . esc_url( "http://twitter.com/{$twitter}" ) . '" title="' . sprintf( esc_attr__( '%s on Twitter', 'four7' ), get_the_author_meta( 'display_name' ) ) . '"><i class="fa fa-twitter-square fa-fw fa-2x"></i></a>';
						}
					}

					if ( $meta == 'facebook' && ! empty( $value ) ) {
						if ( $facebook = get_the_author_meta( 'facebook' ) ) {
							$social_links .= '<a class ="facebook" href="' . esc_url( $facebook ) . '" title="' . sprintf( esc_attr__( '%s on Facebook', 'four7' ), get_the_author_meta( 'display_name' ) ) . '"><i class="fa fa-facebook-square fa-fw fa-2x"></i></a>';
						}
					}

					if ( $meta == 'google_plus' && ! empty( $value ) ) {
						if ( $google_plus = get_the_author_meta( 'google_plus' ) ) {
							$social_links .= '<a class ="google_plus" href="' . esc_url( $google_plus ) . '" title="' . sprintf( esc_attr__( '%s on Google+', 'four7' ), get_the_author_meta( 'display_name' ) ) . '"><i class="fa fa-google-plus-square fa-fw fa-2x"></i></a>';
						}
					}

					if ( $meta == 'feed' && ! empty( $value ) ) {
						$social_links .= '<a class ="feed" href="' . esc_url( get_author_feed_link( get_the_author_meta( 'ID' ) ) ) . '" title="' . sprintf( esc_attr__( 'Subscribe to the feed for %s', 'four7' ), get_the_author_meta( 'display_name' ) ) . '"><i class="fa fa-rss-square fa-fw fa-2x"></i></a>';
					}

				}
			}


			$content .= $panel_open . $panel_head_open . '<h4 class="author-name panel-title">' . $panel_title . '</h4>' . $panel_head_close . $panel_body_open . '<div class="author-avatar col-sm-3">' . $avatar_image . '</div>' . '<div class="author-description author bio col-sm-9">' . $description . '</div>' . $panel_body_close . $panel_footer_open . $social_links . $panel_footer_close . $panel_close;


			if ( $display ) {
				echo $content;

				return true;
			} else {
				return $content;
			}


		}

	}
}