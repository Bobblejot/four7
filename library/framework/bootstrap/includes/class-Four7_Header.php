<?php


if ( ! class_exists( 'Four7_Header' ) ) {

	/**
	* The Header module
	*/
	class Four7_Header {

		function __construct() {
			add_filter( 'redux/options/' . FOUR7_OPT_NAME . '/sections', array( $this, 'options' ), 80 );
			add_action( 'widgets_init',       array( $this, 'header_widgets_init' ), 30 );
			add_action( 'four7_pre_wrap', array( $this, 'branding' ), 3 );
		}
		/*
		 * The Header module options.
		 */
		function options( $sections ) {
			$settings = get_option( FOUR7_OPT_NAME );

			// Jumbotron Options
			$section = array(
				'title' => __( 'Header', 'four7'),
				'icon'  => 'el-icon-eye-open'
			);

			$fields[] = array( 
				'id'          => 'help9',
				'title'       => __( 'Extra Branding Area', 'four7' ),
				'desc'        => __( 'You can enable an extra branding/header area. In this header you can add your logo, and any other widgets you wish.', 'four7' ),
				'type'        => 'info',
			);

			$fields[] = array( 
				'title'       => __( 'Display the Header.', 'four7' ),
				'desc'        => __( 'Turn this ON to display the header. Default: OFF', 'four7' ),
				'id'          => 'header_toggle',
				'default'     => 0,
				'type'        => 'switch',
			);

			$fields[] = array( 
				'title'       => __( 'Display branding on your Header.', 'four7' ),
				'desc'        => __( 'Turn this ON to display branding ( Sitename or Logo )on your Header. Default: ON', 'four7' ),
				'id'          => 'header_branding',
				'default'     => 1,
				'type'        => 'switch',
				'required'    => array('header_toggle','=',array('1')),
			);

			$fields[] = array( 
				'title'       => __( 'Header Background', 'four7' ),
				'desc'        => __( 'Specify the background for your header.', 'four7' ),
				'id'          => 'header_bg',
				'default'     => array(
					'background-color' => '#ffffff'
				),
				'output'      => '.header-wrapper',
				'type'        => 'background',
				'required'    => array( 'header_toggle','=',array( '1' ) ),
			);

			$fields[] = array( 
				'title'       => __( 'Header Background Opacity', 'four7' ),
				'desc'        => __( 'Select the background opacity for your header. Default: 100%.', 'four7' ),
				'id'          => 'header_bg_opacity',
				'default'     => 100,
				'min'         => 0,
				'step'        => 1,
				'max'         => 100,
				'compiler'    => true,
				'type'        => 'slider',
				'required'    => array('header_toggle','=',array('1')),
			);

			$fields[] = array( 
				'title'       => __( 'Header Text Color', 'four7' ),
				'desc'        => __( 'Select the text color for your header. Default: #333333.', 'four7' ),
				'id'          => 'header_color',
				'default'     => '#333333',
				'transparent' => false,    
				'type'        => 'color',
				'required'    => array('header_toggle','=',array('1')),
			);

			$fields[] = array( 
				'title'       => __( 'Header Top Margin', 'four7' ),
				'desc'        => __( 'Select the top margin of header in pixels. Default: 0px.', 'four7' ),
				'id'          => 'header_margin_top',
				'default'     => 0,
				'min'         => 0,
				'max'         => 200,
				'type'        => 'slider',
				'required'    => array( 'header_toggle', '=', array('1') ),
			);

			$fields[] = array( 
				'title'       => __( 'Header Bottom Margin', 'four7' ),
				'desc'        => __( 'Select the bottom margin of header in pixels. Default: 0px.', 'four7' ),
				'id'          => 'header_margin_bottom',
				'default'     => 0,
				'min'         => 0,
				'max'         => 200,
				'type'        => 'slider',
				'required'    => array( 'header_toggle', '=', array('1') ),
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'four7_module_header_options_modifier', $section );
			
			$sections[] = $section;
			return $sections;

		}

		/**
		 * Register sidebars and widgets
		 */
		function header_widgets_init() {
			register_sidebar( array(
				'name'          => __( 'Header Area', 'four7' ),
				'id'            => 'header-area',
				'before_widget' => '<div class="container">',
				'after_widget'  => '</div>',
				'before_title'  => '<h1>',
				'after_title'   => '</h1>',
			) );
		}

		/*
		 * The Header template
		 */
		function branding() {
			if ( four7_getVariable( 'header_toggle' ) == 1 ) {
				echo '<div class="before-main-wrapper">';

				if ( four7_getVariable( 'site_style' ) == 'boxed' ) {
					echo '<div class="container">';
				}

				echo '<div class="header-wrapper">';

				if ( four7_getVariable( 'site_style' ) == 'wide' ) {
					echo '<div class="container">';
				}

				if ( four7_getVariable( 'header_branding' ) == 1 ) {
					echo '<a class="brand-logo" href="' . home_url() . '/"><h1>' . Four7_Branding::logo() . '</h1></a>';
				}

				if ( four7_getVariable( 'header_branding' ) == 1 ) {
					$pullclass = ' class="pull-right"';
				} else {
					$pullclass = null;
				}

				echo '<div' . $pullclass . '>';
				dynamic_sidebar( 'header-area' );
				echo '</div >';

				if ( four7_getVariable( 'site_style' ) == 'wide' ) {
					echo '</div >';
				}

				echo '</div >';

				if ( four7_getVariable( 'site_style' ) == 'boxed' ) {
					echo '</div >';
				}

				echo '</div >';
			}
		}
	}
}