<?php
/**
 * uninstall.php
 * uninstall setting
 */

if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function ms_custom_login_delete_plugin() {
	$option_name = 'ms_custom_login_options';

	delete_option( $option_name );

	// For site options in multisite
	delete_site_option( $option_name );
}
ms_custom_login_delete_plugin();
?>