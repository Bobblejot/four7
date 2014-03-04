<?php


if ( ! class_exists( 'Four7_Layout' ) ) {

	/**
	* The "Layout Module"
	*/
	class Four7_Layout {

		function __construct() {
			global $fs_settings;

			add_filter( 'redux/options/' . FOUR7_OPT_NAME . '/sections', array( $this, 'options' ), 50 );
			add_filter( 'four7_section_class_wrapper',   array( $this, 'apply_layout_classes_wrapper'   )     );
			add_filter( 'four7_section_class_main',      array( $this, 'apply_layout_classes_main'      )     );
			add_filter( 'four7_section_class_primary',   array( $this, 'apply_layout_classes_primary'   )     );
			add_filter( 'four7_section_class_secondary', array( $this, 'apply_layout_classes_secondary' )     );
			add_filter( 'four7_container_class',         array( $this, 'container_class'                )     );
			add_filter( 'body_class',                        array( $this, 'layout_body_class'              )     );
			add_filter( 'four7_navbar_container_class',  array( $this, 'navbar_container_class'         )     );
			add_action( 'template_redirect',                 array( $this, 'content_width'                  )     );

			if ( $fs_settings['body_margin_top'] > 0 || $fs_settings['body_margin_bottom'] > 0 ) {
				add_action( 'wp_enqueue_scripts',            array( $this, 'body_margin'                   ), 101 );
			}

			add_action( 'get_header',             array( $this, 'boxed_container_div_open'          ), 1   );
			add_action( 'four7_pre_footer',   array( $this, 'boxed_container_div_open'          ), 1   );
			add_action( 'four7_do_navbar',    array( $this, 'boxed_container_div_close'         ), 99  );
			add_action( 'four7_after_footer', array( $this, 'boxed_container_div_close'         ), 899 );
			add_action( 'wp',                     array( $this, 'control_primary_sidebar_display'   )      );
			add_action( 'wp',                     array( $this, 'control_secondary_sidebar_display' )      );

			 // Modify the appearance of widgets based on user selection.
			$widgets_mode = $fs_settings['widgets_mode'];
			if ( $widgets_mode == 0 || $widgets_mode == 1 ) {
				add_filter( 'four7_widgets_class',        array( $this, 'alter_widgets_class'        ) );
				add_filter( 'four7_widgets_before_title', array( $this, 'alter_widgets_before_title' ) );
				add_filter( 'four7_widgets_after_title',  array( $this, 'alter_widgets_after_title'  ) );
			}

			add_action( 'wp_head',            array( $this, 'static_meta'      ) );
		}

		/*
		 * The layout core options for the Shoestrap theme
		 */
		function options( $sections ) {
			global $fs_settings;

			// Layout Settings
			$section = array( 
				'title'       => __( 'Layout', 'four7' ),
				'icon'        => 'el-icon-screen',
				'description' => '<p>In this area you can select your site\'s layout, the width of your sidebars, as well as other, more advanced options.</p>',
			);

			$fields[] = array( 
				'title'     => __( 'Site Style', 'four7' ),
				'desc'      => __( 'Select the default site layout. Defaults to "Wide". Please note that if you select a non-responsive layout, you will have to trigger the compiler so that your changes take effect.', 'four7' ),
				'id'        => 'site_style',
				'default'   => 'wide',
				'type'      => 'select',
				'options'   => array( 
					'static'  => __( 'Static (Non-Responsive)', 'four7' ),
					'wide'    => __( 'Wide', 'four7' ),
					'boxed'   => __( 'Boxed', 'four7' ),
					'fluid'   => __( 'Fluid', 'four7' ),
				),
				'compiler'  => true,
			);

			$fields[] = array( 
				'title'     => __( 'Layout', 'four7' ),
				'desc'      => __( 'Select main content and sidebar arrangement. Choose between 1, 2 or 3 column layout.', 'four7' ),
				'id'        => 'layout',
				'default'   => 1,
				'type'      => 'image_select',
				'options'   => array( 
					0 => ReduxFramework::$_url . '/assets/img/1c.png',
					1 => ReduxFramework::$_url . '/assets/img/2cr.png',
					2 => ReduxFramework::$_url . '/assets/img/2cl.png',
					3 => ReduxFramework::$_url . '/assets/img/3cl.png',
					4 => ReduxFramework::$_url . '/assets/img/3cr.png',
					5 => ReduxFramework::$_url . '/assets/img/3cm.png',
				)
			);

			$fields[] = array(
				'title'     => __( 'Custom Layouts per Post Type', 'four7' ),
				'desc'      => __( 'Set a default layout for each post type on your site.', 'four7' ),
				'id'        => 'cpt_layout_toggle',
				'default'   => 0,
				'type'      => 'switch',
			);

			$post_types = get_post_types( array( 'public' => true ), 'names' );
			foreach ( $post_types as $post_type ) {
				$fields[] = array(
					'title'     => __( $post_type . ' Layout', 'four7' ),
					'desc'      => __( 'Override your default stylings. Choose between 1, 2 or 3 column layout.', 'four7' ),
					'id'        => $post_type . '_layout',
					'default'   => $fs_settings['layout'],
					'type'      => 'image_select',
					'required'  => array( 'cpt_layout_toggle','=',array( '1' ) ),
					'options'   => array(
						0         => ReduxFramework::$_url . '/assets/img/1c.png',
						1         => ReduxFramework::$_url . '/assets/img/2cr.png',
						2         => ReduxFramework::$_url . '/assets/img/2cl.png',
						3         => ReduxFramework::$_url . '/assets/img/3cl.png',
						4         => ReduxFramework::$_url . '/assets/img/3cr.png',
						5         => ReduxFramework::$_url . '/assets/img/3cm.png',
					)
				);
			}

			$fields[] = array( 
				'title'     => __( 'Primary Sidebar Width', 'four7' ),
				'desc'      => __( 'Select the width of the Primary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the primary sidebar to have a width of 1/3 ( 4/12 ) of the total page width.', 'four7' ),
				'id'        => 'layout_primary_width',
				'type'      => 'button_set',
				'options'   => array(
					'1' => '1 Column',
					'2' => '2 Columns',
					'3' => '3 Columns',
					'4' => '4 Columns',
					'5' => '5 Columns'
				),
				'default' => '4'
			);

			$fields[] = array( 
				'title'     => __( 'Secondary Sidebar Width', 'four7' ),
				'desc'      => __( 'Select the width of the Secondary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the secondary sidebar to have a width of 1/3 ( 4/12 ) of the total page width.', 'four7' ),
				'id'        => 'layout_secondary_width',
				'type'      => 'button_set',
				'options'   => array(
					'1' => '1 Column',
					'2' => '2 Columns',
					'3' => '3 Columns',
					'4' => '4 Columns',
					'5' => '5 Columns'
				),
				'default' => '3'
			);

			$fields[] = array( 
				'title'     => __( 'Show sidebars on the frontpage', 'four7' ),
				'desc'      => __( 'OFF by default. If you want to display the sidebars in your frontpage, turn this ON.', 'four7' ),
				'id'        => 'layout_sidebar_on_front',
				'default'   => 0,
				'type'      => 'switch'
			);

			$fields[] = array( 
				'title'     => __( 'Margin from top ( Works only in \'Boxed\' mode )', 'four7' ),
				'desc'      => __( 'This will add a margin above the navbar. Useful if you\'ve enabled the \'Boxed\' mode above. Default: 0px', 'four7' ),
				'id'        => 'navbar_margin_top',
				'required'  => array('navbar_boxed','=',array('1')),
				'default'   => 0,
				'min'       => 0,
				'step'      => 1,
				'max'       => 120,
				'compiler'  => true,
				'type'      => 'slider'
			);

			$fields[] = array( 
				'title'     => __( 'Widgets mode', 'four7' ),
				'desc'      => __( 'How do you want your widgets to be displayed?', 'four7' ),
				'id'        => 'widgets_mode',
				'default'   => 1,
				'options'   => array(
					0           => __( 'Panel', 'four7' ),
					1           => __( 'Well', 'four7' ),
					2           => __( 'None', 'four7' ),
				),
				'type'      => 'button_set',
			);

			$fields[] = array( 
				'title'     => __( 'Body Top Margin', 'four7' ),
				'desc'      => __( 'Select the top margin of body element in pixels. Default: 0px.', 'four7' ),
				'id'        => 'body_margin_top',
				'default'   => 0,
				'min'       => 0,
				'step'      => 1,
				'max'       => 200,
				'edit'      => 1,
				'type'      => 'slider'
			);

			$fields[] = array( 
				'title'     => __( 'Body Bottom Margin', 'four7' ),
				'desc'      => __( 'Select the bottom margin of body element in pixels. Default: 0px.', 'four7' ),
				'id'        => 'body_margin_bottom',
				'default'   => 0,
				'min'       => 0,
				'step'      => 1,
				'max'       => 200,
				'edit'      => 1,
				'type'      => 'slider',
			);

			$fields[] = array( 
				'title'     => __( 'Custom Grid', 'four7' ),
				'desc'      => '<strong>' . __( 'CAUTION:', 'four7' ) . '</strong> ' . __( 'Only use this if you know what you are doing, as changing these values might break the way your site looks on some devices. The default settings should be fine for the vast majority of sites.', 'four7' ),
				'id'        => 'custom_grid',
				'default'   => 0,
				'type'      => 'switch',
			);

			$fields[] = array( 
				'title'     => __( 'Small Screen / Tablet view', 'four7' ),
				'desc'      => __( 'The width of Tablet screens. Default: 768px', 'four7' ),
				'id'        => 'screen_tablet',
				'required'  => array('custom_grid','=',array('1')),
				'default'   => 768,
				'min'       => 620,
				'step'      => 2,
				'max'       => 2100,
				'advanced'  => true,
				'compiler'  => true,
				'type'      => 'slider'
			);

			$fields[] = array( 
				'title'     => __( 'Desktop Container Width', 'four7' ),
				'desc'      => __( 'The width of normal screens. Default: 992px', 'four7' ),
				'id'        => 'screen_desktop',
				'required'  => array('custom_grid','=',array('1')),
				'default'   => 992,
				'min'       => 620,
				'step'      => 2,
				'max'       => 2100,
				'advanced'  => true,
				'compiler'  => true,
				'type'      => 'slider',

			);

			$fields[] = array( 
				'title'     => __( 'Large Desktop Container Width', 'four7' ),
				'desc'      => __( 'The width of Large Desktop screens. Default: 1200px', 'four7' ),
				'id'        => 'screen_large_desktop',
				'required'  => array('custom_grid','=',array('1')),
				'default'   => 1200,
				'min'       => 620,
				'step'      => 2,
				'max'       => 2100,
				'advanced'  => true,
				'compiler'  => true,
				'type'      => 'slider'
			);

			$fields[] = array( 
				'title'     => __( 'Columns Gutter', 'four7' ),
				'desc'      => __( 'The space between the columns in your grid. Default: 30px', 'four7' ),
				'id'        => 'layout_gutter',
				'required'  => array('custom_grid','=',array('1')),
				'default'   => 30,
				'min'       => 2,
				'step'      => 2,
				'max'       => 100,
				'advanced'  => true,
				'compiler'  => true,
				'type'      => 'slider',
			);

			$section['fields'] = $fields;

			do_action( 'four7_module_layout_options_modifier' );

			$sections[] = $section;
			return $sections;

		}

		/*
		 * Get the layout value, but only set it once!
		 */
		static public function get_layout() {
			global $four7_layout;
			global $fs_settings;

			if ( ! isset( $four7_layout ) ) {
				do_action( 'four7_layout_modifier' );

				$four7_layout = intval( $fs_settings['layout'] );

				// Looking for a per-page template ?
				if ( is_page() && is_page_template() ) {
					if ( is_page_template( 'template-0.php' ) ) {
						$four7_layout = 0;
					} elseif ( is_page_template( 'template-1.php' ) ) {
						$four7_layout = 1;
					} elseif ( is_page_template( 'template-2.php' ) ) {
						$four7_layout = 2;
					} elseif ( is_page_template( 'template-3.php' ) ) {
						$four7_layout = 3;
					} elseif ( is_page_template( 'template-4.php' ) ) {
						$four7_layout = 4;
					} elseif ( is_page_template( 'template-5.php' ) ) {
						$four7_layout = 5;
					}
				}

				if ( $fs_settings['cpt_layout_toggle'] == 1 ) {
					if ( ! is_page_template() ) {
						$post_types = get_post_types( array( 'public' => true ), 'names' );
						foreach ( $post_types as $post_type ) {
							$four7_layout = ( is_singular( $post_type ) ) ? intval( $fs_settings[$post_type . '_layout'] ) : $four7_layout;
						}
					}
				}

				if ( ! is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $four7_layout == 5 ) {
					$four7_layout = 3;
				}
			}
			return $four7_layout;
		}

		/*
		 *Override the layout value globally
		 */
		function set_layout( $val ) {
			global $four7_layout, $redux;
			$four7_layout = intval( $val );
		}

		/*
		 * Calculates the classes of the main area, main sidebar and secondary sidebar
		 */
		public static function section_class_ext( $target, $echo = false ) {
			global $redux, $fs_framework;
			global $fs_settings;

			$layout = self::get_layout();
			$first  = intval( $fs_settings['layout_primary_width'] );
			$second = intval( $fs_settings['layout_secondary_width'] );

			// disable responsiveness if layout is set to non-responsive
			$width = ( $fs_settings['site_style'] == 'static' ) ? 'mobile' : 'tablet';

			// Set some defaults so that we can change them depending on the selected template
			$main       = 12;
			$primary    = NULL;
			$secondary  = NULL;
			$wrapper    = 12;

			if ( four7_display_primary_sidebar() && four7_display_secondary_sidebar() ) {

				if ( $layout == 5 ) {
					$main       = 12 - floor( ( 12 * $first ) / ( 12 - $second ) );
					$primary    = floor( ( 12 * $first ) / ( 12 - $second ) );
					$secondary  = $second;
					$wrapper    = 12 - $second;
				} elseif ( $layout >= 3 ) {
					$main       = 12 - $first - $second;
					$primary    = $first;
					$secondary  = $second;
				} elseif ( $layout >= 1 ) {
					$main       = 12 - $first;
					$primary    = $first;
					$secondary  = $second;
				}

			} elseif ( four7_display_primary_sidebar() && ! four7_display_secondary_sidebar() ) {

				if ( $layout >= 1 ) {
					$main       = 12 - $first;
					$primary    = $first;
				}

			} elseif ( ! four7_display_primary_sidebar() && four7_display_secondary_sidebar() ) {

				if ( $layout >= 3 ) {
					$main       = 12 - $second;
					$secondary  = $second;
				}
			}

			if ( $target == 'primary' ) {
				$class = $fs_framework->column_classes( array( $width => $primary ), 'strimg' );
			} elseif ( $target == 'secondary' ) {
				$class = $fs_framework->column_classes( array( $width => $secondary ), 'strimg' );
			} elseif ( $target == 'wrapper' ) {
				$class = $fs_framework->column_classes( array( $width => $wrapper ), 'strimg' );
			} else {
				$class = $fs_framework->column_classes( array( $width => $main ), 'strimg' );
			}

			if ( $echo ) {
				echo $class;
			} else {
				return $class;
			}
		}

		/**
		 * Helper function for layout classes
		 */
		function apply_layout_classes_wrapper() {
			return self::section_class_ext( 'wrapper' );
		}

		/**
		 * Helper function for layout classes
		 */
		function apply_layout_classes_main() {
			return self::section_class_ext( 'main' );
		}

		/**
		 * Helper function for layout classes
		 */
		function apply_layout_classes_primary() {
			return self::section_class_ext( 'primary' );
		}

		/**
		 * Helper function for layout classes
		 */
		function apply_layout_classes_secondary() {
			return self::section_class_ext( 'secondary' );
		}

		/**
		 * Add and remove body_class() classes to accomodate layouts
		 */
		function layout_body_class( $classes ) {
			global $fs_settings;

			$layout     = self::get_layout();
			$site_style = $fs_settings['site_style'];
			$margin     = $fs_settings['navbar_margin_top'];

			if ( $layout == 2 || $layout == 3 || $layout == 5 ) {
				$classes[] = 'main-float-right';
			}

			if ( $site_style == 'boxed' ) {
				$classes[] = 'boxed-style';
			} elseif ( $site_style == 'fluid' ) {
				$classes[] = 'fluid';
			}

			return $classes;
		}

		/*
		 * Return the container class
		 */
		public static function container_class() {
			global $fs_settings;
			$class    = $fs_settings['site_style'] != 'fluid' ? 'container' : 'fluid';

			// override if navbar module exists and 'navbar-toggle' is set to left.
			if ( class_exists( 'Four7_Menus' ) ) {
				if ( $fs_settings['navbar_toggle'] == 'left' ) {
					$class = 'fluid';
				}
			}

			return $class;
		}

		/*
		 * Return the container class
		 */
		function navbar_container_class() {
			global $fs_settings;

			$site_style = $fs_settings['site_style'];
			$toggle     = $fs_settings['navbar_toggle'];

			if ( $toggle == 'full' ) {
				$class = 'fluid';
			} else {
				$class = ( $site_style != 'fluid' ) ? 'container' : 'fluid';
			}

			// override if navbar module exists and 'navbar-toggle' is set to left.
			if ( class_exists( 'ShoestrapMenus' ) ) {
				if ( $fs_settings['navbar_toggle'] == 'left' ) {
					$class = 'fluid';
				}
			}

			return $class;
		}

		/*
		 * Calculate the width of the content area in pixels.
		 */
		public static function content_width_px( $echo = false ) {
			global $redux;
			global $fs_settings;

			$layout = self::get_layout();

			$container  = filter_var( $fs_settings['screen_large_desktop'], FILTER_SANITIZE_NUMBER_INT );
			$gutter     = filter_var( $fs_settings['layout_gutter'], FILTER_SANITIZE_NUMBER_INT );

			$main_span  = filter_var( self::section_class_ext( 'main', false ), FILTER_SANITIZE_NUMBER_INT );
			$main_span  = str_replace( '-' , '', $main_span );

			// If the layout is #5, override the default function and calculate the span width of the main area again.
			if ( is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $layout == 5 ) {
				$main_span = 12 - intval( $fs_settings['layout_primary_width'] ) - intval( $fs_settings['layout_secondary_width'] );
			}

			if ( is_front_page() && $fs_settings['layout_sidebar_on_front'] != 1 ) {
				$main_span = 12;
			}

			$width = $container * ( $main_span / 12 ) - $gutter;

			// Width should be an integer since we're talking pixels, round up!.
			$width = round( $width );

			if ( $echo ) {
				echo $width;
			} else {
				return $width;
			}
		}

		/*
		 * Set the content width
		 */
		public static function content_width() {
			global $content_width;
			$content_width = self::content_width_px();
		}

		/*
		 * Body Margins
		 */
		function body_margin() {
			global $fs_settings;

			$body_margin_top    = $fs_settings['body_margin_top'];
			$body_margin_bottom = $fs_settings['body_margin_bottom'];

			$style = 'body { margin-top:'. $body_margin_top .'px; margin-bottom:'. $body_margin_bottom .'px; }';

			wp_add_inline_style( 'four7_css', $style );
		}

		/**
		 * Add a wrapper div when in "boxed" mode to disallow full-width elements
		 */
		function boxed_container_div_open() {
			global $fs_settings;

			if ( $fs_settings['site_style'] == 'boxed' ) echo '<div class="container boxed-container">';
		}

		/**
		 * Close the wrapper div that the 'boxed_container_div_open' opens when in "boxed" mode.
		 */
		function boxed_container_div_close() {
			global $fs_settings;

			if ( $fs_settings['site_style'] == 'boxed' ) echo '</div>';
		}

		/**
		 * Modify the rules for showing up or hiding the primary sidebar
		 */
		function control_primary_sidebar_display() {
			global $fs_settings;

			$layout_sidebar_on_front = $fs_settings['layout_sidebar_on_front'];

			if ( self::get_layout() == 0 ) {
				add_filter( 'four7_display_primary_sidebar', 'four7_return_false' );
			}

			if ( is_front_page() && $layout_sidebar_on_front == 1 && self::get_layout() != 0 ) {
				add_filter( 'four7_display_primary_sidebar', 'four7_return_true' );
			}

			if ( ( ! is_front_page() || ( is_front_page() && $layout_sidebar_on_front == 1 ) ) && self::get_layout() != 0 ) {
				add_filter( 'four7_display_primary_sidebar', 'four7_return_true' );
			}
		}

		/**
		 * Modify the rules for showing up or hiding the secondary sidebar
		 */
		function control_secondary_sidebar_display() {
			global $fs_settings;

			$layout_sidebar_on_front = $fs_settings['layout_sidebar_on_front'];

			if ( self::get_layout() < 3 ) {
				add_filter( 'four7_display_secondary_sidebar', 'four7_return_false' );
			}

			if ( ( ! is_front_page() && four7_display_secondary_sidebar() ) || ( is_front_page() && $layout_sidebar_on_front == 1 && self::get_layout() >= 3 ) ) {
				add_filter( 'four7_display_secondary_sidebar', 'four7_return_true' );
			}
		}

		/**
		 * Get the widget class
		 */
		function alter_widgets_class() {
			global $fs_settings;
			return $fs_settings['widgets_mode'] == 0 ? 'panel panel-default' : 'well';
		}

		/**
		 * Widgets 'before_title' modifying based on widgets mode.
		 */
		function alter_widgets_before_title() {
			global $fs_settings;
			return $fs_settings['widgets_mode'] == 0 ? '<div class="panel-heading">' : '<h3 class="widget-title">';
		}

		/**
		 * Widgets 'after_title' modifying based on widgets mode.
		 */
		function alter_widgets_after_title() {
			global $fs_settings;
			return $fs_settings['widgets_mode'] == 0 ? '</div><div class="panel-body">' : '</h3>';
		}

		/**
		 * Add some metadata when users have selected a static mode for their layout (not responsive).
		 */
		function static_meta() {
			global $fs_settings;

			if ( $fs_settings['site_style'] != 'static' ) : ?>
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<meta name="mobile-web-app-capable" content="yes">
				<meta name="apple-mobile-web-app-capable" content="yes">
				<meta name="apple-mobile-web-app-status-bar-style" content="black">
				<?php
			endif;
		}

		function include_wrapper() {
			global $four7_layout;

			if ( $four7_layout == 5 ) {
				return true;
			} else {
				return false;
			}
		}
	}
}