<?php


if ( ! class_exists( 'Four7_Jumbotron' ) ) {

	/**
	 * The Jumbotron module
	 */
	class Four7_Jumbotron {

		function __construct() {
			add_filter( 'redux/options/' . FOUR7_OPT_NAME . '/sections', array( $this, 'options' ), 90 );
			add_action( 'widgets_init', array( $this, 'jumbotron_widgets_init' ), 20 );
			add_action( 'four7_pre_wrap', array( $this, 'jumbotron_content' ), 5 );
			add_action( 'wp_enqueue_scripts', array( $this, 'jumbotron_css' ), 101 );
			add_action( 'wp_footer', array( $this, 'jumbotron_fittext' ), 10 );
			add_action( 'wp_enqueue_scripts', array( $this, 'jumbotron_fittext_enqueue_script' ), 101 );
		}

		/*
		 * The Jumbotron module options.
		 */
		function options( $sections ) {
			global $fs_settings;

			// Jumbotron Options
			$section = array(
				'title' => __( 'Jumbotron', 'four7' ),
				'icon'  => 'fa fa-bullhorn'
			);

			$fields[] = array(
				'id'    => 'help8',
				'title' => __( 'Jumbotron', 'four7' ),
				'desc'  => __( "A 'Jumbotron', also known as 'Hero' area, is an area in your site where you can display in a prominent position things that matter to you. This can be a slideshow, some text or whatever else you wish. This area is implemented as a widget area, so in order for something to be displayed you will have to add a widget to it.", 'four7' ),
				'type'  => 'info'
			);

			$fields[] = array(
				'title'    => __( 'Jumbotron Background', 'four7' ),
				'desc'     => __( 'Select the background for your Jumbotron area.', 'four7' ),
				'id'       => 'jumbo_bg',
				'default'  => array(
					'background-color'    => isset( $fs_settings['jumbotron_bg'] ) ? $fs_settings['jumbotron_bg'] : '#eeeeee',
					'background-repeat'   => isset( $fs_settings['jumbotron_background_repeat'] ) ? $fs_settings['jumbotron_background_repeat'] : NULL,
					'background-position' => isset( $fs_settings['jumbotron_background_image_position_toggle'] ) ? $fs_settings['jumbotron_background_image_position_toggle'] . ' center' : NULL,
					'background-image'    => isset( $fs_settings['jumbotron_background_image']['url'] ) ? $fs_settings['jumbotron_background_image']['url'] : NULL,
				),
				'compiler' => true,
				'output'   => '.jumbotron',
				'type'     => 'background',
			);

			$fields[] = array(
				'title'   => __( 'Display Jumbotron only on the Frontpage.', 'four7' ),
				'desc'    => __( 'When Turned OFF, the Jumbotron area is displayed in all your pages. If you wish to completely disable the Jumbotron, then please remove the widgets assigned to its area and it will no longer be displayed. Default: ON', 'four7' ),
				'id'      => 'jumbotron_visibility',
				'default' => 1,
				'type'    => 'switch'
			);

			$fields[] = array(
				'title'   => __( 'Full-Width', 'four7' ),
				'desc'    => __( 'When Turned ON, the Jumbotron is no longer restricted by the width of your page, taking over the full width of your screen. This option is useful when you have assigned a slider widget on the Jumbotron area and you want its width to be the maximum width of the screen. Default: OFF.', 'four7' ),
				'id'      => 'jumbotron_nocontainer',
				'default' => 1,
				'type'    => 'switch'
			);

			$fields[] = array(
				'title'   => __( 'Use fittext script for the title.', 'four7' ),
				'desc'    => __( 'Use the fittext script to enlarge or scale-down the font-size of the widget title to fit the Jumbotron area. Default: OFF', 'four7' ),
				'id'      => 'jumbotron_title_fit',
				'default' => 0,
				'type'    => 'switch',
			);

			$fields[] = array(
				'title'   => __( 'Center-align the content.', 'four7' ),
				'desc'    => __( 'Turn this on to center-align the contents of the Jumbotron area. Default: OFF', 'four7' ),
				'id'      => 'jumbotron_center',
				'default' => 0,
				'type'    => 'switch',
			);

			$fields[] = array(
				'title'    => __( 'Jumbotron Font', 'four7' ),
				'desc'     => __( 'The font used in jumbotron.', 'four7' ),
				'id'       => 'font_jumbotron',
				'compiler' => true,
				'default'  => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => 20,
					'color'       => '#333333',
					'google'      => 'false',
					'units'       => 'px'
				),
				'preview'  => array(
					'text' => __( 'This is my preview text!', 'four7' ), //this is the text from preview box
					'size' => '30px' //this is the text size from preview box
				),
				'type'     => 'typography',
			);

			$fields[] = array(
				'title'    => __( 'Jumbotron Header Overrides', 'four7' ),
				'desc'     => __( 'By enabling this you can specify custom values for each <h*> tag. Default: Off', 'four7' ),
				'id'       => 'font_jumbotron_heading_custom',
				'default'  => 0,
				'compiler' => true,
				'type'     => 'switch',
			);

			$fields[] = array(
				'title'    => __( 'Jumbotron Headers Font', 'four7' ),
				'desc'     => __( 'The main font for your site.', 'four7' ),
				'id'       => 'font_jumbotron_headers',
				'compiler' => true,
				'default'  => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'color'       => '#333333',
					'google'      => 'false'
				),
				'preview'  => array(
					'text' => __( 'This is my preview text!', 'four7' ), //this is the text from preview box
					'size' => '30px' //this is the text size from preview box
				),
				'type'     => 'typography',
				'required' => array( 'font_jumbotron_heading_custom', '=', array( '1' ) ),
			);

			$fields[] = array(
				'title'   => 'Jumbotron Border',
				'desc'    => __( 'Select the border options for your Jumbotron', 'four7' ),
				'id'      => 'jumbotron_border',
				'type'    => 'border',
				'all'     => false,
				'left'    => false,
				'top'     => false,
				'right'   => false,
				'default' => array(
					'border-top'    => '0',
					'border-bottom' => '0',
					'border-style'  => 'solid',
					'border-color'  => '#428bca',
				),
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'four7_module_jumbotron_options_modifier', $section );

			$sections[] = $section;

			return $sections;

		}

		/**
		 * Register sidebars and widgets
		 */
		function jumbotron_widgets_init() {
			register_sidebar( array(
				'name'          => __( 'Jumbotron', 'four7' ),
				'id'            => 'jumbotron',
				'before_widget' => '<section id="%1$s"><div class="section-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h1>',
				'after_title'   => '</h1>',
			) );
		}

		/*
		 * The content of the Jumbotron region
		 * according to what we've entered in the customizer
		 */
		function jumbotron_content() {
			global $fs_settings, $fs_framework;

			$hero        = false;
			$site_style  = $fs_settings['site_style'];
			$visibility  = $fs_settings['jumbotron_visibility'];
			$nocontainer = $fs_settings['jumbotron_nocontainer'];

			if ( ( ( $visibility == 1 && is_front_page() ) || $visibility != 1 ) && is_active_sidebar( 'jumbotron' ) ) {
				$hero = true;
			}

			if ( $hero ) {
				echo $fs_framework->clearfix();
				echo '<div class="before-main-wrapper">';

				if ( $site_style == 'boxed' && $nocontainer != 1 ) {
					echo '<div class="' . Four7_Layout::container_class() . '">';
				}

				echo '<div class="jumbotron">';

				if ( $nocontainer != 1 && $site_style == 'wide' || $site_style == 'boxed' ) {
					echo '<div class="' . Four7_Layout::container_class() . '">';
				}

				dynamic_sidebar( 'Jumbotron' );

				if ( $nocontainer != 1 && $site_style == 'wide' || $site_style == 'boxed' ) {
					echo '</div>';
				}

				echo '</div>';

				if ( $site_style == 'boxed' && $nocontainer != 1 ) {
					echo '</div>';
				}

				echo '</div>';
			}
		}

		/**
		 * Any Jumbotron-specific CSS that can't be added in the .less stylesheet is calculated here.
		 */
		function jumbotron_css() {
			global $fs_settings;

			$center = $fs_settings['jumbotron_center'];
			$border = $fs_settings['jumbotron_border'];

			$style = '';

			if ( $center == 1 ) {
				$style .= 'text-align: center;';
			}

			if ( ! empty( $border ) && $border['border-bottom'] > 0 && ! empty( $border['border-color'] ) ) {
				$style .= 'border-bottom:' . $border['border-bottom'] . ' ' . $border['border-style'] . ' ' . $border['border-color'] . ';';
			}

			$style .= 'margin-bottom: 0px;';

			$theCSS = '.jumbotron {' . trim( $style ) . '}';

			wp_add_inline_style( 'four7_css', $theCSS );
		}

		/*
		 * Enables the fittext.js for h1 headings
		 */
		function jumbotron_fittext() {
			global $fs_settings;

			$fittext_toggle   = $fs_settings['jumbotron_title_fit'];
			$jumbo_visibility = $fs_settings['jumbotron_visibility'];

			// Should only show on the front page if it's enabled, or site-wide when appropriate
			if ( $fittext_toggle == 1 && ( $jumbo_visibility == 0 && ( $jumbo_visibility == 1 && is_front_page() ) ) ) {
				echo '<script>jQuery(".jumbotron h1").fitText(1.3);</script>';
			}
		}

		/*
		 * Enqueues fittext.js when needed
		 */
		function jumbotron_fittext_enqueue_script() {
			global $fs_settings;

			$fittext_toggle   = $fs_settings['jumbotron_title_fit'];
			$jumbo_visibility = $fs_settings['jumbotron_visibility'];

			if ( $fittext_toggle == 1 && ( $jumbo_visibility == 0 && ( $jumbo_visibility == 1 && is_front_page() ) ) ) {
				wp_register_script( 'fittext', get_template_directory_uri() . '/assets/js/vendor/jquery.fittext.js', false, null, false );
				wp_enqueue_script( 'fittext' );
			}
		}
	}
}