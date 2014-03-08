<?php


if ( ! class_exists( 'Four7_Social' ) ) {
	/**
	 * The "Social" modue
	 */
	class Four7_Social {

		function __construct() {
			global $fs_settings;

			add_filter( 'redux/options/' . FOUR7_OPT_NAME . '/sections', array( $this, 'options' ), 140 );

			add_action( 'wp_enqueue_scripts', array( $this, 'social_css' ), 101 );

			$social_sharing_location = $fs_settings['social_sharing_location'];

			// Social Share select
			$social_sharing_single_page = $fs_settings['social_sharing_single_page'];

			// Conditions for showing content in posts archives
			if ( $fs_settings['social_sharing_archives'] == 1 ) {
				add_action( 'four7_entry_footer', array( $this, 'social_sharing' ), 5 );
			}

			// Conditions for showing content in single posts
			if ( $fs_settings['social_sharing_single_post'] == 1 ) {
				if ( $fs_settings['social_sharing_location'] == 'top' ) {
					add_action( 'four7_single_pre_content', array( $this, 'social_sharing' ), 5 );
				} elseif ( $fs_settings['social_sharing_location'] == 'bottom' ) {
					add_action( 'four7_single_after_content', array( $this, 'social_sharing' ), 5 );
				} elseif ( $fs_settings['social_sharing_location'] == 'both' ) {
					add_action( 'four7_single_pre_content', array( $this, 'social_sharing' ), 5 );
					add_action( 'four7_single_after_content', array( $this, 'social_sharing' ), 5 );
				}
			}

			// Conditions for showing content in single pages
			if ( $fs_settings['social_sharing_single_page'] == 1 ) {
				if ( $fs_settings['social_sharing_location'] == 'top' ) {
					add_action( 'four7_page_pre_content', array( $this, 'social_sharing' ), 5 );
				} elseif ( $fs_settings['social_sharing_location'] == 'bottom' ) {
					add_action( 'four7_page_after_content', array( $this, 'social_sharing' ), 5 );
				} elseif ( $fs_settings['social_sharing_location'] == 'both' ) {
					add_action( 'four7_page_pre_content', array( $this, 'social_sharing' ), 5 );
					add_action( 'four7_page_after_content', array( $this, 'social_sharing' ), 5 );
				}
			}
		}

		/*
		 * The social core options for the Four7 theme
		 */
		function options( $sections ) {

			$section = array(
				'title' => __( 'Social', 'four7' ),
				'icon'  => 'fa fa-users',
			);

			$fields[] = array(
				'id'    => 'social_sharing_help_1',
				'title' => __( 'Social Sharing', 'four7' ),
				'type'  => 'info'
			);

			$fields[] = array(
				'title'   => __( 'Button Text', 'four7' ),
				'desc'    => __( 'Select the text for the social sharing button.', 'four7' ),
				'id'      => 'social_sharing_text',
				'default' => 'Share',
				'type'    => 'text'
			);

			$fields[] = array(
				'title'   => __( 'Button Location', 'four7' ),
				'desc'    => __( 'Select between NONE, TOP, BOTTOM & BOTH. For archives, "BOTH" fallbacks to "BOTTOM" only.', 'four7' ),
				'id'      => 'social_sharing_location',
				'default' => 'top',
				'type'    => 'select',
				'options' => array(
					'none'   => 'None',
					'top'    => 'Top',
					'bottom' => 'Bottom',
					'both'   => 'Both',
				)
			);

			$fields[] = array(
				'title'   => __( 'Button Alignment', 'four7' ),
				'desc'    => __( 'Select between Left, Center, Right. fallbacks to "Left" only.', 'four7' ),
				'id'      => 'social_sharing_alignment',
				'default' => 'left',
				'type'    => 'select',
				'options' => array(
					'left'   => 'Left',
					'center' => 'Center',
					'right'  => 'Right',
				)
			);

			$fields[] = array(
				'title'   => 'Button Container Border',
				'desc'    => __( 'Select the border options for your Button', 'four7' ),
				'id'      => 'social_sharing_border',
				'type'    => 'border',
				'all'     => false,
				'left'    => false,
				'top'     => true,
				'right'   => false,
				'bottom'  => true,
				'default' => array(
					'border-top'    => '0',
					'border-bottom' => '0',
					'border-style'  => 'solid',
					'border-color'  => '#DDDDDD',
				),
			);

			$fields[] = array(
				'title'   => __( 'Button Styling', 'four7' ),
				'desc'    => __( 'Select between standard Bootstrap\'s button classes', 'four7' ),
				'id'      => 'social_sharing_button_class',
				'default' => 'default',
				'type'    => 'select',
				'options' => array(
					'default' => 'Default',
					'primary' => 'Primary',
					'success' => 'Success',
					'warning' => 'Warning',
					'danger'  => 'Danger',
				)
			);

			$fields[] = array(
				'title'   => __( 'Button Size', 'four7' ),
				'desc'    => __( 'Select between standard Bootstrap\'s button sizes', 'four7' ),
				'id'      => 'social_sharing_button_size',
				'default' => 'default',
				'type'    => 'select',
				'options' => array(
					'extra-small' => 'Extra-Small',
					'small'       => 'Small',
					'medium'      => 'Medium',
					'large'       => 'Large',
					'danger'      => 'Extra-Large',
				)
			);

			$fields[] = array(
				'title'   => __( 'Show in Posts Archives', 'four7' ),
				'desc'    => __( 'Show the sharing button in posts archives.', 'four7' ),
				'id'      => 'social_sharing_archives',
				'default' => '',
				'type'    => 'switch'
			);

			$fields[] = array(
				'title'   => __( 'Show in Single Post', 'four7' ),
				'desc'    => __( 'Show the sharing button in single post.', 'four7' ),
				'id'      => 'social_sharing_single_post',
				'default' => '1',
				'type'    => 'switch'
			);

			$fields[] = array(
				'title'   => __( 'Show in Single Page', 'four7' ),
				'desc'    => __( 'Show the sharing button in single page.', 'four7' ),
				'id'      => 'social_sharing_single_page',
				'default' => '1',
				'type'    => 'switch'
			);

			$fields[] = array(
				'id'      => 'share_networks',
				'type'    => 'checkbox',
				'title'   => __( 'Social Share Networks', 'four7' ),
				'desc'    => __( 'Select the Social Networks you want to enable for social shares', 'four7' ),

				'options' => array(
					'fb' => __( 'Facebook', 'four7' ),
					'gp' => __( 'Google+', 'four7' ),
					'li' => __( 'LinkedIn', 'four7' ),
					'pi' => __( 'Pinterest', 'four7' ),
					//	'rd'    => __( 'Reddit', 'four7' ),
					'tu' => __( 'Tumblr', 'four7' ),
					'tw' => __( 'Twitter', 'four7' ),
					'em' => __( 'Email', 'four7' ),
				)
			);

			$fields[] = array(
				'id'    => 'social_sharing_help_3',
				'title' => __( 'Social Links used in Menus && Footer', 'four7' ),
				'type'  => 'info'
			);

			$fields[] = array(
				'title'    => __( 'Blogger', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the Blogger icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'blogger_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'DeviantART', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the DeviantART icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'deviantart_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'Digg', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the Digg icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'digg_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'Dribbble', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the Dribbble icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'dribbble_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'Facebook', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the Facebook icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'facebook_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'Flickr', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the Flickr icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'flickr_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'GitHub', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the GitHub icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'github_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'Google+', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the Google+ icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'google_plus_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'LinkedIn', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the LinkedIn icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'linkedin_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'Pinterest', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the Pinterest icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'pinterest_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			//	$fields[] = array(
			//		'title'     => __( 'Reddit', 'four7' ),
			//		'desc'      => __( 'Provide the link you desire and the Reddit icon will appear. To remove it, just leave it blank.', 'four7' ),
			//		'id'        => 'reddit_link',
			//		'validate'  => 'url',
			//		'default'   => '',
			//		'type'      => 'text'
			//	);

			$fields[] = array(
				'title'    => __( 'RenRen', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the RenRen icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'renren_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'RSS', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the RSS icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'rss_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'Skype', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the Skype icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'skype_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			//		$fields[] = array(
			//			'title'     => __( 'SoundCloud', 'four7' ),
			//			'desc'      => __( 'Provide the link you desire and the SoundCloud icon will appear. To remove it, just leave it blank.', 'four7' ),
			//			'id'        => 'soundcloud_link',
			//			'validate'  => 'url',
			//			'default'   => '',
			//			'type'      => 'text'
			//		);

			$fields[] = array(
				'title'    => __( 'Tumblr', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the Tumblr icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'tumblr_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'Twitter', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the Twitter icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'twitter_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => __( 'Vimeo', 'four7' ),
				'desc'     => __( 'Provide the link you desire and the Vimeo icon will appear. To remove it, just leave it blank.', 'four7' ),
				'id'       => 'vimeo_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);


			$fields[] = array(
				'title'    => 'Vkontakte',
				'desc'     => 'Provide the link you desire and the Vkontakte icon will appear. To remove it, just leave it blank.',
				'id'       => 'vkontakte_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => 'Weibo',
				'desc'     => 'Provide the link you desire and the Weibo icon will appear. To remove it, just leave it blank.',
				'id'       => 'weibo_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$fields[] = array(
				'title'    => 'YouTube Link',
				'desc'     => 'Provide the link you desire and the YouTube icon will appear. To remove it, just leave it blank.',
				'id'       => 'youtube_link',
				'validate' => 'url',
				'default'  => '',
				'type'     => 'text'
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'four7_module_socials_options_modifier', $section );

			$sections[] = $section;

			return $sections;

		}

		/**
		 * Return an array of the social links the user has entered.
		 * This is simply a helper function for other functions.
		 */
		function get_social_links() {
			global $fs_settings;
			// An array of the available networks
			$networks = array();

			// Started on the new stuff, not done yet.
			$networks[] = array( 'url' => $fs_settings['blogger_link'], 'icon' => 'blogger', 'fullname' => 'Blogger' );
			$networks[] = array( 'url' => $fs_settings['deviantart_link'], 'icon' => 'deviantart', 'fullname' => 'DeviantART' );
			$networks[] = array( 'url' => $fs_settings['digg_link'], 'icon' => 'digg', 'fullname' => 'Digg' );
			$networks[] = array( 'url' => $fs_settings['dribbble_link'], 'icon' => 'dribbble', 'fullname' => 'Dribbble' );
			$networks[] = array( 'url' => $fs_settings['facebook_link'], 'icon' => 'facebook', 'fullname' => 'Facebook' );
			$networks[] = array( 'url' => $fs_settings['flickr_link'], 'icon' => 'flickr', 'fullname' => 'Flickr' );
			$networks[] = array( 'url' => $fs_settings['github_link'], 'icon' => 'github', 'fullname' => 'GitHub' );
			$networks[] = array( 'url' => $fs_settings['google_plus_link'], 'icon' => 'google-plus', 'fullname' => 'Google+' );
			$networks[] = array( 'url' => $fs_settings['linkedin_link'], 'icon' => 'linkedin', 'fullname' => 'LinkedIn' );
			$networks[] = array( 'url' => $fs_settings['pinterest_link'], 'icon' => 'pinterest', 'fullname' => 'Pinterest' );
			//		$networks[] = array( 'url' => $fs_settings['reddit_link'],       'icon' => 'reddit',     'fullname' => 'Reddit' );
			$networks[] = array( 'url' => $fs_settings['renren_link'], 'icon' => 'renren', 'fullname' => 'RenRen' );
			$networks[] = array( 'url' => $fs_settings['rss_link'], 'icon' => 'rss', 'fullname' => 'RSS' );
			$networks[] = array( 'url' => $fs_settings['skype_link'], 'icon' => 'skype', 'fullname' => 'Skype' );
			//		$networks[] = array( 'url' => $fs_settings['soundcloud_link'],   'icon' => 'soundcloud', 'fullname' => 'SoundCloud' );
			$networks[] = array( 'url' => $fs_settings['tumblr_link'], 'icon' => 'tumblr', 'fullname' => 'Tumblr' );
			$networks[] = array( 'url' => $fs_settings['twitter_link'], 'icon' => 'twitter', 'fullname' => 'Twitter' );
			$networks[] = array( 'url' => $fs_settings['vimeo_link'], 'icon' => 'vimeo-square', 'fullname' => 'Vimeo' );
			$networks[] = array( 'url' => $fs_settings['vkontakte_link'], 'icon' => 'vk', 'fullname' => 'Vkontakte' );
			$networks[] = array( 'url' => $fs_settings['weibo_link'], 'icon' => 'weibo', 'fullname' => 'Weibo' );
			$networks[] = array( 'url' => $fs_settings['youtube_link'], 'icon' => 'youtube', 'fullname' => 'YouTube' );

			return $networks;
		}

		/**
		 * Build an array of the available/enabled networks for social sharing.
		 */
		function get_social_shares() {
			global $fs_framework, $fs_settings;

			$nets = $fs_settings['share_networks'];

			$networks = null;

			if ( isset( $nets['fb'] ) ) {
				$networks['facebook'] = array(
					'icon'     => 'facebook',
					'fullname' => 'Facebook',
					'url'      => 'http://www.facebook.com/sharer.php?u=' . get_permalink() . '&amp;title=' . get_the_title()
				);
			}

			if ( isset( $nets['tw'] ) ) {
				$networks['twitter'] = array(
					'icon'     => 'twitter',
					'fullname' => 'Twitter',
					'url'      => 'http://twitter.com/home/?status=' . get_the_title() . ' - ' . get_permalink()
				);

				$twittername = $this->get_tw_username();

				if ( $twittername != '' ) {
					$network['twitter']['username'] = $twittername;
					$networks['twitter']['url'] .= ' via @' . $twittername;
				}
			}

//			if ( isset( $nets['rd'] ) ) {
//				$networks['reddit'] = array(
//					'icon'      => 'reddit',
//					'fullname'  => 'Reddit',
//					'url'       => 'http://reddit.com/submit?url=' .get_permalink() . '&amp;title=' . get_the_title()
//				);
//			}

			if ( isset( $nets['li'] ) ) {
				$networks['linkedin'] = array(
					'icon'     => 'linkedin',
					'fullname' => 'LinkedIn',
					'url'      => 'http://linkedin.com/shareArticle?mini=true&amp;url=' . get_permalink() . '&amp;title=' . get_the_title()
				);
			}

			if ( isset( $nets['gp'] ) ) {
				$networks['googleplus'] = array(
					'icon'     => 'google-plus',
					'fullname' => 'Google+',
					'url'      => 'https://plus.google.com/share?url=' . get_permalink()
				);
			}

			if ( isset( $nets['tu'] ) ) {
				$networks['tumblr'] = array(
					'icon'     => 'tumblr',
					'fullname' => 'Tumblr',
					'url'      => 'http://www.tumblr.com/share/link?url=' . urlencode( get_permalink() ) . '&amp;name=' . urlencode( get_the_title() ) . "&amp;description=" . urlencode( get_the_excerpt() )
				);
			}

			if ( isset( $nets['pi'] ) ) {
				$networks['pinterest'] = array(
					'icon'     => 'pinterest',
					'fullname' => 'Pinterest',
					'url'      => 'http://pinterest.com/pin/create/button/?url=' . get_permalink()
				);
			}

			if ( isset( $nets['em'] ) ) {
				$networks['email'] = array(
					'icon'     => 'envelope',
					'fullname' => 'Email',
					'url'      => 'mailto:?subject=' . get_the_title() . '&amp;body=' . get_permalink()
				);
			}

			return $networks;
		}

		/**
		 * Properly parses the twitter URL if set
		 */
		function get_tw_username() {
			global $fs_settings;
			$twittername  = '';
			$twitter_link = $fs_settings['twitter_link'];

			if ( $twitter_link != "" ) {
				$twitter_link = explode( '/', rtrim( $twitter_link, '/' ) );
				$twittername  = end( $twitter_link );
			}

			return $twittername;
		}


		/**
		 * Create the social sharing buttons
		 */
		function social_sharing() {
			global $fs_framework, $fs_settings;

			// The base class for icons that will be used
			$baseclass = 'icon fa fa-';

			// Don't show by default
			$show = false;

			// Button class
			if ( isset( $fs_settings['social_sharing_button_class'] ) && ! empty( $fs_settings['social_sharing_button_class'] ) ) {
				$button_color = $fs_settings['social_sharing_button_class'];
			} else {
				$button_color = 'default';
			}

			// Button size
			if ( isset( $fs_settings['social_sharing_button_size'] ) && ! empty( $fs_settings['social_sharing_button_size'] ) ) {
				$button_size = $fs_settings['social_sharing_button_size'];
			} else {
				$button_size = 'medium';
			}

			// Button Text
			$text = $fs_settings['social_sharing_text'];

			// Build the content
			$content = '<div class="row-social">';
			$content .= '<div class="row-social-inner">';
			$content .= '<div class="' . $fs_framework->button_group_classes( $button_size, null, 'social-share' ) . '">';
			$content .= '<button class="' . $fs_framework->button_classes( $button_color, null, null, 'social-share-main' ) . '">' . $text . '</button>';

			// An array of the available networks
			$networks = $this->get_social_shares();
			$networks = is_null( $networks ) ? array() : $networks;

			foreach ( $networks as $network ) {
				$content .= '<a class="' . $fs_framework->button_classes( $button_color, null, null, 'social-link' ) . '" href="' . $network['url'] . '" target="_blank">';
				$content .= '<i class="' . $baseclass . $network['icon'] . '"></i>';
				$content .= '</a>';
			}
			$content .= '</div>';
			$content .= '</div>';
			$content .= '</div>';
			$content .= '<div class="clearfix"></div>';

			// If at least ONE social share option is enabled then echo the content
			if ( ! empty( $networks ) ) {
				echo $content;
			}
		}

		/**
		 * AnySocial-specific CSS that can't be added in the .less stylesheet is calculated here.
		 */
		function social_css() {
			global $fs_settings;

			$alignment = $fs_settings['social_sharing_alignment'];
			$border    = $fs_settings['social_sharing_border'];

			$style = '';

			if ( $alignment == 'left' ) {
				$style .= 'text-align: left;';
			} else if ( $alignment == 'center' ) {
				$style .= 'text-align: center;';
			} else {
				if ( $alignment == 'right' ) {
					$style .= 'text-align: right;';
				}
			}


			if ( ! empty( $border ) && $border['border-bottom'] > 0 && ! empty( $border['border-color'] ) ) {
				$style .= 'border-bottom:' . $border['border-bottom'] . ' ' . $border['border-style'] . ' ' . $border['border-color'] . ';';
			}

			if ( ! empty( $border ) && $border['border-top'] > 0 && ! empty( $border['border-color'] ) ) {
				$style .= 'border-top:' . $border['border-top'] . ' ' . $border['border-style'] . ' ' . $border['border-color'] . ';';
			}

			$style .= 'margin-bottom: 0px;';

			$theCSS = '.row-social-inner {' . trim( $style ) . '}';

			wp_add_inline_style( 'four7_css', $theCSS );
		}
	}
}