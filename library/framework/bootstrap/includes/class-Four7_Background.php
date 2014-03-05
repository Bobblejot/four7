<?php

if ( ! class_exists( 'Four7_Background' ) ) {

	/**
	* The "Background" module
	*/
	class Four7_Background {
		
		function __construct() {
			add_filter( 'redux/options/' . FOUR7_OPT_NAME . '/sections', array( $this, 'options' ), 30 );
			add_action( 'wp_enqueue_scripts', array( $this, 'css' ), 101 );
			add_action( 'plugins_loaded',     array( $this, 'upgrade_options' ) );
		}

		/*
		 * The background core options for the Four7 theme
		 */
		function options( $sections ) {
			global $redux;
			$settings = get_option( FOUR7_OPT_NAME );

			// Blog Options
			$section = array(
				'title' => __( 'Background', 'four7' ),
				'icon'  => 'el-icon-photo',
			);   

			$fields[] = array(
				'title'       => __( 'General Background Color', 'four7' ),
				'desc'        => __( 'Select a background color for your site. Default: #ffffff.', 'four7' ),
				'id'          => 'html_bg',
				'default'     => array(
					'background-color' => isset( $settings['html_color_bg'] ) ? $settings['html_color_bg'] : '#ffffff',
				),
				'transparent' => false,
				'type'        => 'background',
				'output'      => 'body'
			);

			$fields[] = array(
				'title'       => __( 'Content Background', 'four7' ),
				'desc'        => __( 'Background for the content area. Colors also affect input areas and other colors.', 'four7' ),
				'id'          => 'body_bg',
				'default'     => array(
					'background-color'    => isset( $settings['color_body_bg'] ) ? $settings['color_body_bg'] : '#ffffff',
					'background-repeat'   => isset( $settings['background_repeat'] ) ? $settings['background_repeat'] : NULL,
					'background-position' => isset( $settings['background_position_x'] ) ? $settings['background_position_x'] . ' center' : NULL,
					'background-image'    => isset( $settings['background_image']['url'] ) ? $settings['background_image']['url'] : NULL,
				),
				'compiler'    => true,
				'transparent' => false,
				'type'        => 'background',
				'output'      => '.wrap.main-section .content .bg'
			);

			$fields[] = array(
				'title'     => __( 'Content Background Color Opacity', 'four7' ),
				'desc'      => __( 'Select the opacity of your background color for the main content area so that background images will show through. Please note that if you have added an image for your content background, changing the opacity to something other than 100 will result in your background image not being shown. If you need to add opacity to your content background image, you will need to do it by adding transparency to the PNG background image itself.', 'four7' ),
				'id'        => 'body_bg_opacity',
				'default'   => 100,
				'min'       => 0,
				'step'      => 1,
				'max'       => 100,
				'type'      => 'slider',
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'four7_module_background_options_modifier', $section );
			
			$sections[] = $section;
			return $sections;

		}

		function css() {
			$content_opacity  = four7_getVariable( 'body_bg_opacity' );
			$bg_color         = four7_getVariable( 'body_bg' );
			$bg_color         = isset( $bg_color['background-color'] ) ? $bg_color['background-color'] : '#ffffff';

			// The Content background color
			$content_bg = $content_opacity < 100 ? 'background:' . Four7_Color::get_rgba( $bg_color, $content_opacity ) . ';' : '';

			$style = $content_opacity < 100 ? '.wrap.main-section div.content .bg {' . $content_bg . '}' : '';

			wp_add_inline_style( 'four7_css', $style );
		}
	}
}