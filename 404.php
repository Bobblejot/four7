<?php

global $fs_framework;

fs_get_template_part( 'templates/page', 'header' );

$alert_message = __( 'Sorry, but the page you were trying to view does not exist.', 'four7' );
echo $fs_framework->alert( $type = 'warning', $alert_message );
?>

	<p><?php _e( 'It looks like this was the result of either:', 'four7' ); ?></p>
	<ul>
		<li><?php _e( 'a mistyped address', 'four7' ); ?></li>
		<li><?php _e( 'an out-of-date link', 'four7' ); ?></li>
	</ul>
<?php get_search_form();