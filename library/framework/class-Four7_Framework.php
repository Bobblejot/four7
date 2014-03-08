<?php

if ( ! class_exists( 'FOUR7_Framework' ) ) {

	/**
	 * The "Advanced" module
	 */
	class FOUR7_Framework {

		/**
		 * Class constructor
		 */
		function __construct() {
			global $fs_settings;

			require_once dirname( __FILE__ ) . '/core/class-FOUR7_Framework_Core.php';

			if ( ! defined( 'FOUR7_FRAMEWORK' ) ) {
				$active_framework = $fs_settings['framework'];
			} else {
				if ( ! isset( $fs_settings['framework'] ) || FOUR7_FRAMEWORK != $fs_settings['framework'] ) {
					$fs_settings['framework'] = FOUR7_FRAMEWORK;
					update_option( FOUR7_OPT_NAME, $fs_settings );
				}
				$active_framework = FOUR7_FRAMEWORK;
			}

			// Add the frameworks select to redux.
			add_filter( 'redux/options/' . FOUR7_OPT_NAME . '/sections', array( $this, 'options' ), 1 );

			// Include all frameworks
			$modules_path = new RecursiveDirectoryIterator( dirname( __FILE__ ) );
			$recIterator  = new RecursiveIteratorIterator( $modules_path );
			$regex        = new RegexIterator( $recIterator, '/\/framework.php$/i' );

			foreach ( $regex as $item ) {
				require_once $item->getPathname();
			}

			$frameworks = $this->frameworks_list();

			$compiler = false;
			// Return the classname of the active framework.
			foreach ( $frameworks as $framework ) {
				if ( $active_framework == $framework['shortname'] ) {
					$active = $framework['classname'];

					if ( isset( $framework['compiler'] ) ) {
						$compiler = $framework['compiler'];
					}
				}
			}

			// Get the compiler that will be used and initialize it.
			if ( $compiler ) {
				if ( $compiler == 'less_php' ) {
					require_once 'compilers/less-php/class-Four7_Less_php.php';
					$compiler_init = new Four7_Less_PHP();
				} elseif ( $compiler == 'sass_php' ) {
					require_once 'compilers/sass-php/class-Four7_Sass_php.php';
					$compiler_init = new Four7_Sass_PHP();
				}
			}
		}

		/**
		 * Get a list of all the available frameworks.
		 */
		function frameworks_list() {
			$frameworks = apply_filters( 'four7_frameworks_array', array() );

			return $frameworks;
		}

		/*
		 * Create the framework selector
		 */
		function options( $sections ) {
			global $redux;
			$settings = get_option( FOUR7_OPT_NAME );

			$frameworks = $this->frameworks_list();

			$frameworks_select    = array();
			$frameworks_shortlist = array();

			foreach ( $frameworks as $framework ) {
				$frameworks_select[$framework['shortname']] = $framework['name'];
				$frameworks_shortlist[]                     = $framework['shortname'];
			}

			$frameworks_shortlist = implode( ', ', $frameworks_shortlist );

			// Blog Options
			$section = array(
				'title' => __( 'General', 'four7' ),
				'icon'  => 'fa fa-globe',
			);

			if ( ! defined( 'FOUR7_FRAMEWORK' ) ) {

				$fields[] = array(
					'title'    => __( 'Framework Locking', 'four7' ),
					'desc'     => __( 'You can select a framework here. Keep in mind that if you reset your options, this option will also be reset and you will lose all your settings. When changing frameworks, your settings are also reset.
						<br>If you want to lock your site to a specific framework, then please define it in your wp-config.php file like this:', 'four7' ) . ' <code>define( "FOUR7_Framework", "foundation" );</code><br>' . __( 'Accepted values: ', 'four7' ) . $frameworks_shortlist . '</p>',
					'id'       => 'framework_lock_help',
					'type'     => 'info',
					'options'  => $frameworks_select,
					'compiler' => false,
				);


				$fields[] = array(
					'title'    => __( 'Framework Select', 'four7' ),
					'desc'     => __( 'Select a framework.', 'four7' ),
					'id'       => 'framework',
					'default'  => 'bootstrap',
					'type'     => 'select',
					'options'  => $frameworks_select,
					'compiler' => false,
				);
			}

			$fields[] = array(
				'title'   => __( 'Logo', 'four7' ),
				'desc'    => __( 'Upload a logo image using the media uploader, or define the URL directly.', 'four7' ),
				'id'      => 'logo',
				'default' => '',
				'type'    => 'media',
			);

			$fields[] = array(
				'title'   => __( 'Custom Favicon', 'four7' ),
				'desc'    => __( 'Upload a favicon image using the media uploader, or define the URL directly.', 'four7' ),
				'id'      => 'favicon',
				'default' => '',
				'type'    => 'media',
			);

			$fields[] = array(
				'title'   => __( 'Apple Icon', 'four7' ),
				'desc'    => __( 'This will create icons for Apple iPhone ( 57px x 57px ), Apple iPhone Retina Version ( 114px x 114px ), Apple iPad ( 72px x 72px ) and Apple iPad Retina ( 144px x 144px ). Please note that for better results the image you upload should be at least 144px x 144px.', 'four7' ),
				'id'      => 'apple_icon',
				'default' => '',
				'type'    => 'media',
			);

			$section['fields'] = $fields;

			do_action( 'four7_module_layout_options_modifier' );

			$sections[] = $section;

			return $sections;
		}
	}
}

$framework = new FOUR7_Framework();