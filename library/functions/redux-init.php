<?php

/*
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 * Also if running on windows you may have url problems, which can be fixed by defining the framework url first
 */

if ( class_exists( 'ReduxFrameworkPlugin' ) ) :
	function four7_redux_init() {

		$args = array();

		// ** PLEASE PLEASE for production use your own key! **

		/*
		 * Remove the link until Redux is updated on wp.org
		 */
		//Setup custom links in the footer for share icons
		$args['share_icons'][] = array(
			'url'   => 'https://github.com/four7/four7',
			'title' => 'Fork Me on GitHub',
			'icon'  => 'fa fa-github'
		);

		// Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
		$args['opt_name']           = FOUR7_OPT_NAME;
		$args['customizer']         = true;
		$args['forced_edd_license'] = true;
		$args['google_api_key']     = 'AIzaSyDjhDP_doqoTWKbbHxXCtiIGPPGyg_6ru8';
		$args['global_variable']    = 'redux';
		$args['default_show']       = true;
		$args['default_mark']       = '*';
		$args['page_slug']          = FOUR7_OPT_NAME;
		$theme                      = wp_get_theme();
		$args['display_name']       = $theme->get( 'Name' );
		$args['menu_title']         = __( 'Four7 Options', 'four7' );
		$args['display_version']    = $theme->get( 'Version' );
		$args['page_position']      = 99;
		$args['dev_mode']           = false;
		$args['menu_type']          = 'menu';
		$args['allow_sub_menu']     = true;
		$args['page_parent']        = 'themes.php';

		$args['help_tabs'][] = array(
			'id'      => 'redux-options-1',
			'title'   => __( 'Theme Information 1', 'four7' ),
			'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'four7' )
		);
		$args['help_tabs'][] = array(
			'id'      => 'redux-options-2',
			'title'   => __( 'Theme Information 2', 'four7' ),
			'content' => __( '<p>This is the tab content, HTML is allowed. Tab2</p>', 'four7' )
		);

		//Set the Help Sidebar for the options page - no sidebar by default
		$args['help_sidebar'] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'four7' );

		$sections = array();
		$sections = apply_filters( 'four7_add_sections', $sections );

		$ReduxFramework = new ReduxFramework( $sections, $args );

		if ( ! empty( $redux['dev_mode'] ) && $redux['dev_mode'] == 1 ) {
			$ReduxFramework->args['dev_mode']    = true;
			$ReduxFramework->args['system_info'] = true;
		}
	}

	add_action( 'init', 'four7_redux_init' );
endif;

function newIconFont() {
	// Uncomment this to remove elusive icon from the panel completely
	//wp_deregister_style( 'redux-elusive-icon' );
	//wp_deregister_style( 'redux-elusive-icon-ie7' );

	wp_register_style(
		'redux-font-awesome',
		'//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css',
		array(),
		time(),
		'all'
	);
	wp_enqueue_style( 'redux-font-awesome' );
}

// This example assumes the opt_name is set to redux_demo.  Please replace it with your opt_name value.
add_action( 'redux/page/' . FOUR7_OPT_NAME . '/enqueue', 'newIconFont' );

/**
 * Remove the demo link and the notice of integrated demo from the redux-framework plugin
 */
function four7_remove_demo() {

	// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
	if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
		remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::instance(), 'plugin_metalinks' ), null, 2 );

		// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
		remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
	}
}

add_action( 'redux/loaded', 'four7_remove_demo' );