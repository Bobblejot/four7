<?php
/**
 * Internationalization and translation functions.  Because four7 Core is a framework made up of various 
 * extensions with different textdomains, it must filter 'gettext' so that a single translation file can 
 * handle all translations.
 *
 * @package four7 Framework
 * @subpackage Core
 * @author inevisys
 * @copyright Copyright (c) 2013 - 2014, inevisys
 * @link http://inevisys.com/four7
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Checks if a textdomain's translation files have been loaded.  This function behaves differently from 
 * WordPress core's is_textdomain_loaded(), which will return true after any translation function is run over 
 * a text string with the given domain.  The purpose of this function is to simply check if the translation files 
 * are loaded.
 *
 * @since 1.0.0
 * @access private This is only used internally by the framework for checking translations.
 * @param string $domain The textdomain to check translations for.
 */
function four7_is_textdomain_loaded( $domain ) {
	global $four7;

	return ( isset( $four7->textdomain_loaded[$domain] ) && true === $four7->textdomain_loaded[$domain] ) ? true : false;
}

/**
 * Loads the framework's translation files.  The function first checks if the parent theme or child theme 
 * has the translation files housed in their '/localization' folder.  If not, it sets the translation file the the 
 * framework '/localization' folder.
 *
 * @since 1.0.0
 * @access private
 * @uses load_textdomain() Loads an MO file into the domain for the framework.
 * @param string $domain The name of the framework's textdomain.
 * @return true|false Whether the MO file was loaded.
 */
function four7_load_framework_textdomain( $domain ) {

	/* Get the WordPress installation's locale set by the user. */
	$locale = get_locale();

	/* Check if the mofile is located in parent/child theme /localization folder. */
	$mofile = locate_template( array( "library/localization/{$domain}-{$locale}.mo" ) );

	/* If no mofile was found in the parent/child theme, set it to the framework's mofile. */
	if ( empty( $mofile ) )
		$mofile = trailingslashit( four7_LOCALIZATION ) . "{$domain}-{$locale}.mo";

	return load_textdomain( $domain, $mofile );
}

/**
 * Gets the parent theme textdomain. This allows the framework to recognize the proper textdomain of the 
 * parent theme.
 *
 * Important! Do not use this for translation functions in your theme.  Hardcode your textdomain string.  Your 
 * theme's textdomain should match your theme's folder name.
 *
 * @since 1.0.0
 * @access private
 * @uses get_template() Defines the theme textdomain based on the template directory.
 * @global object $four7 The global four7 object.
 * @return string $four7->textdomain The textdomain of the theme.
 */
function four7_get_parent_textdomain() {
	global $four7;

	/* If the global textdomain isn't set, define it. Plugin/theme authors may also define a custom textdomain. */
	if ( empty( $four7->parent_textdomain ) )
		$four7->parent_textdomain = sanitize_key( apply_filters( four7_get_prefix() . '_parent_textdomain', get_template() ) );

	/* Return the expected textdomain of the parent theme. */
	return $four7->parent_textdomain;
}

/**
 * Gets the child theme textdomain. This allows the framework to recognize the proper textdomain of the 
 * child theme.
 *
 * Important! Do not use this for translation functions in your theme.  Hardcode your textdomain string.  Your 
 * theme's textdomain should match your theme's folder name.
 *
 * @since 1.0.0
 * @access private
 * @uses get_stylesheet() Defines the child theme textdomain based on the stylesheet directory.
 * @global object $four7 The global four7 object.
 * @return string $four7->child_theme_textdomain The textdomain of the child theme.
 */
function four7_get_child_textdomain() {
	global $four7;

	/* If a child theme isn't active, return an empty string. */
	if ( !is_child_theme() )
		return '';

	/* If the global textdomain isn't set, define it. Plugin/theme authors may also define a custom textdomain. */
	if ( empty( $four7->child_textdomain ) )
		$four7->child_textdomain = sanitize_key( apply_filters( four7_get_prefix() . '_child_textdomain', get_stylesheet() ) );

	/* Return the expected textdomain of the child theme. */
	return $four7->child_textdomain;
}

/**
 * Filters the 'load_textdomain_mofile' filter hook so that we can change the directory and file name 
 * of the mofile for translations.  This allows child themes to have a folder called /localization with translations
 * of their parent theme so that the translations aren't lost on a parent theme upgrade.
 *
 * @since 1.0.0
 * @access private
 * @param string $mofile File name of the .mo file.
 * @param string $domain The textdomain currently being filtered.
 * @return $mofile
 */
function four7_load_textdomain_mofile( $mofile, $domain ) {

	/* If the $domain is for the parent or child theme, search for a $domain-$locale.mo file. */
	if ( $domain == four7_get_parent_textdomain() || $domain == four7_get_child_textdomain() ) {

		/* Check for a $domain-$locale.mo file in the parent and child theme root and /localization folder. */
		$locale = get_locale();
		$locate_mofile = locate_template( array( "library/localization/{$domain}-{$locale}.mo", "{$domain}-{$locale}.mo" ) );

		/* If a mofile was found based on the given format, set $mofile to that file name. */
		if ( !empty( $locate_mofile ) )
			$mofile = $locate_mofile;
	}

	/* Return the $mofile string. */
	return $mofile;
}

/**
 * Filters 'gettext' to change the translations used for the 'four7-core' textdomain.  This filter makes it possible 
 * for the theme's MO file to translate the framework's text strings.
 *
 * @since 1.0.0
 * @access private
 * @param string $translated The translated text.
 * @param string $text The original, untranslated text.
 * @param string $domain The textdomain for the text.
 * @return string $translated
 */
function four7_gettext( $translated, $text, $domain ) {

	/* Check if 'four7-core' is the current textdomain, there's no mofile for it, and the theme has a mofile. */
	if ( 'four7-core' == $domain && !four7_is_textdomain_loaded( 'four7-core' ) && four7_is_textdomain_loaded( four7_get_parent_textdomain() ) ) {

		/* Get the translations for the theme. */
		$translations = &get_translations_for_domain( four7_get_parent_textdomain() );

		/* Translate the text using the theme's translation. */
		$translated = $translations->translate( $text );
	}

	return $translated;
}

/**
 * Filters 'gettext' to change the translations used for the each of the extensions' textdomains.  This filter 
 * makes it possible for the theme's MO file to translate the framework's extensions.
 *
 * @since 1.0.0
 * @access private
 * @param string $translated The translated text.
 * @param string $text The original, untranslated text.
 * @param string $domain The textdomain for the text.
 * @return string $translated
 */
function four7_extensions_gettext( $translated, $text, $domain ) {

	/* Check if the current textdomain matches one of the framework extensions. */
	if ( in_array( $domain, array( 'comments', 'pages-walker' ) ) ) {

		/* If the theme supports the extension, switch the translations. */
		if ( current_theme_supports( $domain ) ) {

			/* If the framework mofile is loaded, use its translations. */
			if ( four7_is_textdomain_loaded( 'four7-core' ) )
				$translations = &get_translations_for_domain( 'four7-core' );

			/* If the theme mofile is loaded, use its translations. */
			elseif ( four7_is_textdomain_loaded( four7_get_parent_textdomain() ) )
				$translations = &get_translations_for_domain( four7_get_parent_textdomain() );

			/* If translations were found, translate the text. */
			if ( !empty( $translations ) )
				$translated = $translations->translate( $text );
		}
	}

	return $translated;
}

?>