<?php


if ( ! class_exists( 'Four7_Menus' ) ) {

	/**
	* The "Menus" module
	*/
	class Four7_Menus {

		function __construct() {
			global $fs_settings;

			add_filter( 'redux/options/' . FOUR7_OPT_NAME . '/sections', array( $this, 'options' ), 60 );
			add_filter( 'four7_nav_class',        array( $this, 'nav_class' ) );
			add_action( 'four7_inside_nav_begin', array( $this, 'navbar_pre_searchbox' ), 11 );
			add_filter( 'four7_navbar_class',     array( $this, 'navbar_class' ) );
			add_action( 'wp_enqueue_scripts',         array( $this, 'navbar_css' ), 101 );
			add_action( 'four7_do_navbar',        array( $this, 'do_navbar' ) );
			add_filter( 'four7_navbar_brand',     array( $this, 'navbar_brand' ) );
			add_filter( 'body_class',                 array( $this, 'navbar_body_class' ) );
			add_action( 'widgets_init',               array( $this, 'sl_widgets_init' ), 40 );
			add_action( 'four7_post_main_nav',    array( $this, 'navbar_sidebar' ) );
			add_action( 'four7_pre_wrap',         array( $this, 'secondary_navbar' ) );
			add_action( 'widgets_init',               array( $this, 'slidedown_widgets_init' ), 40 );
			add_action( 'four7_do_navbar',        array( $this, 'navbar_slidedown_content' ), 99 );
			add_action( 'wp_enqueue_scripts',         array( $this, 'megadrop_script' ), 200 );
			add_action( 'four7_pre_wrap',         array( $this, 'content_wrapper_static_left_open' ) );
			add_action( 'four7_after_footer',     array( $this, 'content_wrapper_static_left_close' ), 1 );

			if ( $fs_settings['secondary_navbar_margin'] != 0 ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'secondary_navbar_margin' ), 101 );
			}

			$hook = ( $fs_settings['navbar_toggle'] == 'left' ) ? 'four7_do_navbar' : 'four7_inside_nav_begin';
			add_action( $hook, array( $this, 'navbar_slidedown_toggle' ) );
		}

		/*
		 * The header core options for the Shoestrap theme
		 */
		function options( $sections ) {

			// Branding Options
			$section = array(
				'title' => __( 'Menus', 'four7' ),
				'icon'  => 'el-icon-lines'
			);

			$fields[] = array(
				'id'          => 'help7',
				'title'       => __( 'Advanced NavBar Options', 'four7' ),
				'desc'        => __( "You can activate or deactivate your Primary NavBar here, and define its properties. Please note that you might have to manually create a menu if it doesn't already exist.", 'four7' ),
				'type'        => 'info'
			);

			$fields[] = array(
				'title'       => __( 'Type of NavBar', 'four7' ),
				'desc'        => __( 'Choose the type of Navbar you want. Off completely hides the navbar, Alternative uses an alternative walker for the navigation menus. See <a target="_blank"href="https://github.com/twittem/wp-bootstrap-navwalker">here</a> for more details.', 'four7' ) . '<br>' . __( '<strong>WARNING:</strong> The "Static-Left" option is ONLY compatible with fluid layouts. The width of the static-left navbar is controlled by the secondary sidebar width.', 'four7' ),
				'id'          => 'navbar_toggle',
				'default'     => 'normal',
				'options'     => array(
					'none'    => __( 'Off', 'four7' ),
					'normal'  => __( 'Normal', 'four7' ),
					// 'pills'   => __( 'Pills', 'four7' ),
					'full'    => __( 'Full-Width', 'four7' ),
					'left'    => __( 'Static-Left', 'four7' ),
				),
				'type'        => 'button_set'
			);

			$fields[] = array(
				'id'          => 'helpnavbarbg',
				'title'       => __( 'NavBar Styling Options', 'four7' ),
				'desc'   	  => __( 'Customize the look and feel of your navbar below.', 'four7' ),
				'type'        => 'info'
			);

			$fields[] = array(
				'title'       => __( 'NavBar Background Color', 'four7' ),
				'desc'        => __( 'Pick a background color for the NavBar. Default: #eeeeee.', 'four7' ),
				'id'          => 'navbar_bg',
				'default'     => '#f8f8f8',
				'compiler'    => true,
				'transparent' => false,
				'type'        => 'color'
			);

			$fields[] = array(
				'title'       => __( 'NavBar Background Opacity', 'four7' ),
				'desc'        => __( 'Pick a background opacity for the NavBar. Default: 100%.', 'four7' ),
				'id'          => 'navbar_bg_opacity',
				'default'     => 100,
				'min'         => 0,
				'step'        => 1,
				'max'         => 100,
				'type'        => 'slider',
			);

			$fields[] = array(
				'title'       => __( 'NavBar Menu Style', 'four7' ),
				'desc'        => __( 'You can use an alternative menu style for your NavBars.', 'four7' ),
				'id'          => 'navbar_style',
				'default'     => 'default',
				'type'        => 'select',
				'options'     => array(
					'default' => __( 'Default', 'four7' ),
					'style1'  => __( 'Style', 'four7' ) . ' 1',
					'style2'  => __( 'Style', 'four7' ) . ' 2',
					'style3'  => __( 'Style', 'four7' ) . ' 3',
					'style4'  => __( 'Style', 'four7' ) . ' 4',
					'style5'  => __( 'Style', 'four7' ) . ' 5',
					'style6'  => __( 'Style', 'four7' ) . ' 6',
					'metro'   => __( 'Metro', 'four7' ),
				)
			);

			$fields[] = array(
				'title'       => __( 'Display Branding ( Sitename or Logo ) on the NavBar', 'four7' ),
				'desc'        => __( 'Default: ON', 'four7' ),
				'id'          => 'navbar_brand',
				'default'     => 1,
				'type'        => 'switch'
			);

			$fields[] = array(
				'title'       => __( 'Use Logo ( if available ) for branding on the NavBar', 'four7' ),
				'desc'        => __( 'If this option is OFF, or there is no logo available, then the sitename will be displayed instead. Default: ON', 'four7' ),
				'id'          => 'navbar_logo',
				'default'     => 1,
				'type'        => 'switch'
			);

			$fields[] = array(
				'title'       => __( 'NavBar Positioning', 'four7' ),
				'desc'        => __( 'Using this option you can set the navbar to be fixed to top, fixed to bottom or normal. When you\'re using one of the \'fixed\' options, the navbar will stay fixed on the top or bottom of the page. Default: Normal', 'four7' ),
				'id'          => 'navbar_fixed',
				'default'     => 0,
				'on'          => __( 'Fixed', 'four7' ),
				'off'         => __( 'Scroll', 'four7' ),
				'type'        => 'switch'
			);

			$fields[] = array(
				'title'       => __( 'Fixed NavBar Position', 'four7' ),
				'desc'        => __( 'Using this option you can set the navbar to be fixed to top, fixed to bottom or normal. When you\'re using one of the \'fixed\' options, the navbar will stay fixed on the top or bottom of the page. Default: Normal', 'four7' ),
				'id'          => 'navbar_fixed_position',
				'required'    => array('navbar_fixed','=',array('1')),
				'default'     => 0,
				'on'          => __( 'Bottom', 'four7' ),
				'off'         => __( 'Top', 'four7' ),
				'type'        => 'switch'
			);

			$fields[] = array(
				'title'       => __( 'NavBar Height', 'four7' ),
				'desc'        => __( 'Select the height of the NavBar in pixels. Should be equal or greater than the height of your logo if you\'ve added one.', 'four7' ),
				'id'          => 'navbar_height',
				'default'     => 50,
				'min'         => 38,
				'step'        => 1,
				'max'         => 200,
				'compiler'    => true,
				'type'        => 'slider'
			);

			$fields[] = array(
				'title'       => __( 'Navbar Font', 'four7' ),
				'desc'        => __( 'The font used in navbars.', 'four7' ),
				'id'          => 'font_navbar',
				'compiler'    => true,
				'default'     => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => 14,
					'color'       => '#333333',
					'google'      => 'false',
				),
				'preview'     => array(
					'text'    => __( 'This is my preview text!', 'four7' ), //this is the text from preview box
					'size'    => 30 //this is the text size from preview box
				),
				'type'        => 'typography',
			);

			$fields[] = array(
				'title'       => __( 'Branding Font', 'four7' ),
				'desc'        => __( 'The branding font for your site.', 'four7' ),
				'id'          => 'font_brand',
				'compiler'    => true,
				'default'     => array(
					'font-family' => 'Arial, Helvetica, sans-serif',
					'font-size'   => 18,
					'google'      => 'false',
					'color'       => '#333333',
				),
				'preview'     => array(
					'text'    => __( 'This is my preview text!', 'four7' ), //this is the text from preview box
					'size'    => 30 //this is the text size from preview box
				),
				'type'        => 'typography',
			);

			$fields[] = array(
				'title'     => __( 'Responsive NavBar Threshold', 'four7' ),
				'desc'      => __( 'Point at which the navbar becomes uncollapsed', 'four7' ),
				'id'        => 'grid_float_breakpoint',
				'type'      => 'button_set',
				'options'   => array(
					'min'           => __( 'Never', 'four7' ),
					'screen_xs_min' => __( 'Extra Small', 'four7' ),
					'screen_sm_min' => __( 'Small', 'four7' ),
					'screen_md_min' => __( 'Desktop', 'four7' ),
					'screen_lg_min' => __( 'Large Desktop', 'four7' ),
					'max'           => __( 'Always', 'four7' ),
				),
				'default'   => 'screen_sm_min',
				'compiler'  => true,
			);

			$fields[] = array(
				'title'       => __( 'NavBar Margin', 'four7' ),
				'desc'        => __( 'Select the top and bottom margin of the NavBar in pixels. Applies only in static top navbar ( scroll condition ). Default: 0px.', 'four7' ),
				'id'          => 'navbar_margin',
				'default'     => 0,
				'min'         => 0,
				'step'        => 1,
				'max'         => 200,
				'type'        => 'slider',
			);

			$fields[] = array(
				'title'       => __( 'Display social links in the NavBar.', 'four7' ),
				'desc'        => __( 'Display social links in the NavBar. These can be setup in the \'Social\' section on the left. Default: OFF', 'four7' ),
				'id'          => 'navbar_social',
				'default'     => 0,
				'type'        => 'switch'
			);

			$fields[] = array(
				'title'       => __( 'Display social links as a Dropdown list or an Inline list.', 'four7' ),
				'desc'        => __( 'How to display social links. Default: Dropdown list', 'four7' ),
				'id'          => 'navbar_social_style',
				'default'     => 0,
				'on'          => __( 'Inline', 'four7' ),
				'off'         => __( 'Dropdown', 'four7' ),
				'type'        => 'switch',
				'required'    => array('navbar_social','=',array('1')),
			);

			$fields[] = array(
				'title'       => __( 'Search form on the NavBar', 'four7' ),
				'desc'        => __( 'Display a search form in the NavBar. Default: On', 'four7' ),
				'id'          => 'navbar_search',
				'default'     => 1,
				'type'        => 'switch'
			);

			$fields[] = array(
				'title'       => __( 'Float NavBar menu to the right', 'four7' ),
				'desc'        => __( 'Floats the primary navigation to the right. Default: On', 'four7' ),
				'id'          => 'navbar_nav_right',
				'default'     => 1,
				'type'        => 'switch'
			);

			$fields[] = array(
				'id'          => 'help9',
				'title'       => __( 'Secondary Navbar', 'four7' ),
				'desc'        => __( 'The secondary navbar is a 2nd navbar, located right above the main wrapper. You can show a menu there, by assigning it from Appearance -> Menus.', 'four7' ),
				'type'        => 'info',
			);

			$fields[] = array(
				'title'       => __( 'Display social networks in the secondary navigation bar.', 'four7' ),
				'desc'        => __( 'Enable this option to display your social networks as a dropdown menu on the seondary navbar.', 'four7' ),
				'id'          => 'navbar_secondary_social',
				'default'     => 0,
				'type'        => 'switch',
			);

			$fields[] = array(
				'title'       => __( 'Secondary NavBar Margin', 'four7' ),
				'desc'        => __( 'Select the top and bottom margin of header in pixels. Default: 0px.', 'four7' ),
				'id'          => 'secondary_navbar_margin',
				'default'     => 0,
				'min'         => 0,
				'max'         => 200,
				'type'        => 'slider',
			);

			$fields[] = array(
				'id'          => 'helpsidebarmenus',
				'title'       => __( 'Sidebar Menus', 'four7' ),
				'desc'        => __( 'If you\'re using the "Custom Menu" widgets in your sidebars, you can control their styling here', 'four7' ),
				'type'        => 'info',
			);

			$fields[] = array(
				'title'       => __( 'Color for sidebar menus', 'four7' ),
				'desc'        => __( 'Select a style for menus added to your sidebars using the custom menu widget', 'four7' ),
				'id'          => 'menus_class',
				'default'     => 1,
				'type'        => 'select',
				'options'     => array(
					'default' => __( 'Default', 'four7' ),
					'primary' => __( 'Branding-Primary', 'four7' ),
					'success' => __( 'Branding-Success', 'four7' ),
					'warning' => __( 'Branding-Warning', 'four7' ),
					'info'    => __( 'Branding-Info', 'four7' ),
					'danger'  => __( 'Branding-Danger', 'four7' ),
				),
			);

			$fields[] = array(
				'title'       => __( 'Inverse Sidebar_menus.', 'four7' ),
				'desc'        => __( 'Default: OFF. See https://github.com/twittem/wp-bootstrap-navlist-walker for more details', 'four7' ),
				'id'          => 'inverse_navlist',
				'default'     => 0,
				'type'        => 'switch',
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'four7_module_menus_options_modifier', $section );

			$sections[] = $section;
			return $sections;

		}

		/**
		 * Modify the nav class.
		 */
		function nav_class() {
			global $fs_settings;

			if ( $fs_settings['navbar_nav_right'] == '1' ) {
				return 'navbar-nav nav pull-right';
			} else {
				return 'navbar-nav nav';
			}
		}


		/*
		 * The template for the primary navbar searchbox
		 */
		function navbar_pre_searchbox() {
			global $fs_settings;

			$show_searchbox = $fs_settings['navbar_search'];
			if ( $show_searchbox == '1' ) : ?>
				<form role="search" method="get" id="searchform" class="form-search pull-right navbar-form" action="<?php echo home_url('/'); ?>">
					<label class="hide" for="s"><?php _e('Search for:', 'four7'); ?></label>
					<input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="form-control search-query" placeholder="<?php _e('Search', 'four7'); ?> <?php bloginfo('name'); ?>">
				</form>
			<?php endif;
		}

		/**
		 * Modify the navbar class.
		 */
		public static function navbar_class( $navbar = 'main') {
			global $fs_settings;

			$fixed    = $fs_settings['navbar_fixed'];
			$fixedpos = $fs_settings['navbar_fixed_position'];
			$style    = $fs_settings['navbar_style'];
			$toggle   = $fs_settings['navbar_toggle'];
			$left     = ( $toggle == 'left' ) ? true : false;

			$bp = self::sl_breakpoint();

			$defaults = 'navbar navbar-default topnavbar';

			if ( $fixed != 1 ) {
				$class = ' navbar-static-top';
			} else {
				$class = ( $fixedpos == 1 ) ? ' navbar-fixed-bottom' : ' navbar-fixed-top';
			}

			$class = $defaults . $class;

			if ( $left ) {
				$extra_classes = 'navbar navbar-default static-left ' . $bp .  ' col-' . $bp . '-' . $fs_settings['layout_secondary_width'];
				$class = $extra_classes;
			}

			if ( $navbar != 'secondary' ) {
				return $class . ' ' . $style;
			} else {
				return 'navbar ' . $style;
			}
		}

		/**
		 * Modify the grid-float-breakpoint using Bootstrap classes.
		 */
		public static function sl_breakpoint() {
			global $fs_settings;

			$break    = $fs_settings['grid_float_breakpoint'];

			$bp = ( $break == 'min' || $break == 'screen_xs_min' ) ? 'xs' : 'xs';
			$bp = ( $break == 'screen_sm_min' )                    ? 'sm' : $bp;
			$bp = ( $break == 'screen_md_min' )                    ? 'md' : $bp;
			$bp = ( $break == 'screen_lg_min' || $break == 'max' ) ? 'lg' : $bp;

			return $bp;
		}

		/**
		 * Add some CSS for the navbar when needed.
		 */
		function navbar_css() {
			global $fs_settings;

			$navbar_bg_opacity = $fs_settings['navbar_bg_opacity'];
			$style = '';

			$opacity = ( $navbar_bg_opacity == '' ) ? '0' : ( intval( $navbar_bg_opacity ) ) / 100;

			if ( $opacity != 1 && $opacity != '' ) {
				$bg  = str_replace( '#', '', $fs_settings['navbar_bg'] );
				$rgb = Four7_Color::get_rgb( $bg, true );
				$opacityie = str_replace( '0.', '', $opacity );

				$style .= '.navbar, .navbar-default {';

				if ( $opacity != 1 && $opacity != '') {
					$style .= 'background: transparent; background: rgba(' . $rgb . ', ' . $opacity . '); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#' . $opacityie . $bg . ',endColorstr=#' . $opacityie . $bg . '); ;';
				} else {
					$style .= 'background: #' . $bg . ';';
				}

				$style .= '}';
			}

			if ( $fs_settings['navbar_margin'] != 1 ) {
				$style .= '.navbar-static-top { margin-top:'. $fs_settings['navbar_margin'] . 'px !important; margin-bottom:' . $fs_settings['navbar_margin'] . 'px !important; }';
			}

			wp_add_inline_style( 'four7_css', $style );
		}

		/**
		 * Will the sidebar be shown?
		 * If yes, then which navbar?
		 */
		function do_navbar() {
			global $fs_settings;

			$navbar_toggle = $fs_settings['navbar_toggle'];

			if ( $navbar_toggle != 'none' ) {
				if ( $navbar_toggle != 'pills' ) {
					if ( ! has_action( 'four7_header_top_navbar_override' ) ) {
						require( 'header-top-navbar.php' );
					} else {
						do_action( 'four7_header_top_navbar_override' );
					}
				} else {
					if ( ! has_action( 'four7_header_override' ) ) {
						require( 'header.php' );
					} else {
						do_action( 'four7_header_override' );
					}
				}
			} else {
				return '';
			}
		}

		/**
		 * get the navbar branding options (if the branding module exists)
		 * and then add the appropriate logo or sitename.
		 */
		function navbar_brand() {
			// Make sure the branding module exists.
			
			    global $fs_settings, $fs_framework;
			    
				$logo           = $fs_settings['logo'];
				$branding_class = ! empty( $logo['url'] ) ? 'logo' : 'text';

				if ( $fs_settings['navbar_brand'] != 0 ) {
					$branding  = '<a class="navbar-brand ' . $branding_class . '" href="' . home_url('/') . '">';
					$branding .= $fs_settings['navbar_logo'] == 1 ? $fs_framework->logo() : get_bloginfo( 'name' );
					$branding .= '</a>';
				} else {
					$branding = '';
				}
			} else {
				// If the branding module does not exist, return the defaults.
				$branding = '';
			}

			return $branding;
		}

		/**
		 * Add and remove body_class() classes
		 */
		function navbar_body_class( $classes ) {
			global $fs_settings;

			// Add 'top-navbar' or 'bottom-navabr' class if using Bootstrap's Navbar
			// Used to add styling to account for the WordPress admin bar
			if ( $fs_settings['navbar_fixed'] == 1 && $fs_settings['navbar_fixed_position'] != 1 && $fs_settings['navbar_toggle'] != 'left' ) {
				$classes[] = 'top-navbar';
			} elseif ( $fs_settings['navbar_fixed'] == 1 && $fs_settings['navbar_fixed_position'] == 1 ) {
				$classes[] = 'bottom-navbar';
			}

			return $classes;
		}

		/**
		 * Register sidebars and widgets
		 */
		function sl_widgets_init() {
			register_sidebar( array(
				'name'          => __( 'In-Navbar Widget Area', 'four7' ),
				'id'            => 'navbar',
				'description'   => __( 'This widget area will show up in your NavBars. This is most useful when using a static-left navbar.', 'four7' ),
				'before_widget' => '<div id="in-navbar">',
				'after_widget'  => '</div>',
				'before_title'  => '<h1>',
				'after_title'   => '</h1>',
			) );
		}

		/**
		 * Add the sidebar to the navbar.
		 */
		function navbar_sidebar() {
			dynamic_sidebar( 'navbar' );
		}

		/**
		 * The contents of the secondary navbar
		 */
		function secondary_navbar() {
			global $fs_settings, $fs_framework;

			if ( has_nav_menu( 'secondary_navigation' ) ) : ?>

				<?php echo $fs_framework->make_container( 'div' ); ?>
					<header class="secondary navbar navbar-default <?php echo self::navbar_class( 'secondary' ); ?>" role="banner">
						<button data-target=".nav-secondary" data-toggle="collapse" type="button" class="navbar-toggle">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<?php
						if ( $fs_settings['navbar_secondary_social'] != 0 ) {
							FOUR7_Framework_Bootstrap::navbar_social_links();
						} ?>
						<nav class="nav-secondary navbar-collapse collapse" role="navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'secondary_navigation', 'menu_class' => apply_filters( 'four7_nav_class', 'navbar-nav nav' ) ) ); ?>
						</nav>
					</header>
				</div>

			<?php endif;
		}

		/**
		 * Add margin to the secondary nvbar if needed
		 */
		function secondary_navbar_margin() {
			global $fs_settings;

			$secondary_navbar_margin = $fs_settings['secondary_navbar_margin'];
			$style = '.secondary { margin-top:' . $secondary_navbar_margin . 'px !important; margin-bottom:'. $secondary_navbar_margin .'px !important; }';

			wp_add_inline_style( 'four7_css', $style );
		}

		/**
		 * Register widget areas for the navbar dropdowns.
		 */
		function slidedown_widgets_init() {
			// Register widgetized areas
			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down Top', 'four7' ),
				'id'            => 'navbar-slide-down-top',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down 1', 'four7' ),
				'id'            => 'navbar-slide-down-1',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down 2', 'four7' ),
				'id'            => 'navbar-slide-down-2',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down 3', 'four7' ),
				'id'            => 'navbar-slide-down-3',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down 4', 'four7' ),
				'id'            => 'navbar-slide-down-4',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );
		}

		/*
		 * Calculates the class of the widget areas based on a 12-column bootstrap grid.
		 */
		public static function navbar_widget_area_class() {
			$str = '';
			$str .= ( is_active_sidebar( 'navbar-slide-down-1' ) ) ? '1' : '';
			$str .= ( is_active_sidebar( 'navbar-slide-down-2' ) ) ? '2' : '';
			$str .= ( is_active_sidebar( 'navbar-slide-down-3' ) ) ? '3' : '';
			$str .= ( is_active_sidebar( 'navbar-slide-down-4' ) ) ? '4' : '';

			$strlen = strlen( $str );

			$colwidth = ( $strlen > 0 ) ? 12 / $strlen : 12;

			return $colwidth;
		}

		/*
		 * Prints the content of the slide-down widget areas.
		 */
		function navbar_slidedown_content() {
			global $fs_settings;

		//	if ( is_active_sidebar( 'navbar-slide-down-1' ) || is_active_sidebar( 'navbar-slide-down-2' ) || is_active_sidebar( 'navbar-slide-down-3' ) || is_active_sidebar( 'navbar-slide-down-4' ) || is_active_sidebar( 'navbar-slide-down-top' ) ) : ?>
				<div class="before-main-wrapper">
					<?php $megadrop_class = ( $fs_settings['site_style'] != 'fluid' ) ? 'top-megamenu container' : 'top-megamenu'; ?>
					<div id="megaDrop" class="<?php echo $megadrop_class; ?>">
						<?php $widgetareaclass = 'col-sm-' . self::navbar_widget_area_class(); ?>

						<?php dynamic_sidebar( 'navbar-slide-down-top' ); ?>

						<div class="row">
							<?php if ( is_active_sidebar( 'navbar-slide-down-1' ) ) : ?>
								<div class="<?php echo $widgetareaclass; ?>">
									<?php dynamic_sidebar( 'navbar-slide-down-1' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( is_active_sidebar( 'navbar-slide-down-2' ) ) : ?>
								<div class="<?php echo $widgetareaclass; ?>">
									<?php dynamic_sidebar( 'navbar-slide-down-2' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( is_active_sidebar( 'navbar-slide-down-3' ) ) : ?>
								<div class="<?php echo $widgetareaclass; ?>">
									<?php dynamic_sidebar( 'navbar-slide-down-3' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( is_active_sidebar( 'navbar-slide-down-4' ) ) : ?>
								<div class="<?php echo $widgetareaclass; ?>">
									<?php dynamic_sidebar( 'navbar-slide-down-4' ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php 
			//endif;
		}

		/**
		 * When static-left navbar is selected, we need to add a wrapper to the whole content
		 */
		function content_wrapper_static_left_open() {
			global $fs_settings, $fs_framework;

			$breakpoint = self::sl_breakpoint();

			if ( $breakpoint == 'xs' ) {
				$width = 'mobile';
			} elseif ( $breakpoint == 'sm' ) {
				$width = 'tablet';
			} elseif ( $breakpoint == 'md' ) {
				$width = 'medium';
			} elseif ( $breakpoint == 'lg' ) {
				$width = 'large';
			}

			if ( isset( $fs_settings['navbar_toggle'] ) && $fs_settings['navbar_toggle'] == 'left' ) {
				echo $fs_framework->make_col( 'div', array( $width => 12 - $fs_settings['layout_secondary_width'] ), 'content-wrapper-left', 'col-' . $breakpoint . '-offset-' . $fs_settings['layout_secondary_width'] );
			}
		}

		/**
		 * When static-left navbar is selected, we need to close the wrapper opened by the content_wrapper_static_left function.
		 */
		function content_wrapper_static_left_close() {
			global $fs_settings, $fs_framework;

			if ( isset( $fs_settings['navbar_toggle'] ) && $fs_settings['navbar_toggle'] == 'left' ) {
				echo '</div>';
			}
		}

		/**
		 * The icon that helps us open/close the dropdown widgets.
		 */
		function navbar_slidedown_toggle() {
			global $fs_settings;

			$navbar_color = $fs_settings['navbar_bg'];
			$navbar_mode  = $fs_settings['navbar_toggle'];
			$trigger = (
				is_active_sidebar( 'navbar-slide-down-top' ) ||
				is_active_sidebar( 'navbar-slide-down-1' ) ||
				is_active_sidebar( 'navbar-slide-down-2' ) ||
				is_active_sidebar( 'navbar-slide-down-3' ) ||
				is_active_sidebar( 'navbar-slide-down-4' )
			) ? true : false;

			if ( $trigger ) {

				$class = ( $navbar_mode == 'left' ) ? ' static-left' : ' nav-toggle';
				$pre   = ( $navbar_mode != 'left' ) ? '<ul class="nav navbar-nav"><li>' : '';
				$post  = ( $navbar_mode != 'left' ) ? '</li></ul>' : '';

				echo $pre . '<a class="toggle-nav' . $class . '" href="#"><i class="el-icon-chevron-down"></i></a>' . $post;

			}
		}

		/**
		 * The script responsible for showing/hiding the dropdown widget areas from the navbar.
		 */
		function megadrop_script() {
			if ( is_active_sidebar( 'navbar-slide-down-top' ) || is_active_sidebar( 'navbar-slide-down-1' ) || is_active_sidebar( 'navbar-slide-down-2' ) || is_active_sidebar( 'navbar-slide-down-3' ) || is_active_sidebar( 'navbar-slide-down-4' ) ) {
				wp_register_script( 'four7_megadrop', get_template_directory_uri() . '/assets/js/megadrop.js', false, null, false );
				wp_enqueue_script( 'four7_megadrop' );
			}
		}
	}
}