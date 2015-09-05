<?php

// Admin options menu
include_once( GLOBAL_SMTP_PLUGIN_DIR . '/pages/global-smtp-page.php' );

function global_smtp_add_menu() {
	if ( !is_multisite() )
		add_options_page( __( 'Global SMTP', 'global-smtp' ), __( 'Global SMTP', 'global-smtp' ), 'update_core', 'global-smtp', 'global_smtp_page' );
}
add_action( 'admin_menu', 'global_smtp_add_menu' );

function global_smtp_add_network_menu() {
	if ( is_multisite() )
		add_submenu_page( 'settings.php', __( 'Global SMTP', 'global-smtp' ), __( 'Global SMTP', 'global-smtp' ), 'update_core', 'global-smtp', 'global_smtp_page' );
}
add_action( 'network_admin_menu', 'global_smtp_add_network_menu' );
