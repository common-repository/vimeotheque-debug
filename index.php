<?php 
/*
 Plugin Name: Vimeotheque Debug
 Plugin URI: https://github.com/constantin-b/vimeotheque-debug/
 Description: Register all debug messages from plugin Vimeotheque (by CodeFlavors) and create debug logs that can be used for troubleshooting video import issues.
 Author: CodeFlavors
 Version: 1.0.1
 Author URI: https://codeflavors.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'VMTQ_DEBUGGER_VERSION', '1.0.1' );
define( 'VMTQ_DEBUGGER_FILE', __FILE__ );
define( 'VMTQ_DEBUGGER_PATH', plugin_dir_path( __FILE__ ) );
define( 'VMTQ_DEBUGGER_URL', plugin_dir_url( __FILE__ ) );

/**
 * Minimum Vimeotheque version required by the add-on
 */
define( 'VMTQ_DEBUGGER_PLUGIN_COMPAT', '2.0' );

if( !defined( 'VIMEOTHEQUE_VERSION' ) ){
	add_action( 'admin_notices', 'vmtq_debugger_no_plugin' );
} elseif( !version_compare( VIMEOTHEQUE_VERSION, VMTQ_DEBUGGER_PLUGIN_COMPAT, '>=' ) ){
	add_action( 'admin_notices', 'vmtq_debugger_fail_version' );
} elseif (
	! version_compare( PHP_VERSION, VIMEOTHEQUE_PHP_COMPAT, '>=' ) ||
	! version_compare( get_bloginfo( 'version' ), VIMEOTHEQUE_WP_COMPAT, '>=' )
)
{
	// if minimum PHP & WP versions aren't met, the plugin stops here; all notices will be issued by Vimeotheque Lite
	return;
} else{
	require_once VMTQ_DEBUGGER_PATH . 'includes/libs/plugin.class.php';
}

/**
 * Vimeotheque PRO admin notice for minimum Vimeotheque version.
 * @return void
 */
function vmtq_debugger_fail_version(){
	/* translators: %s: WordPress version */
	$message = sprintf(
		esc_html__( 'Vimeotheque Debugger requires Vimeotheque version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'vimeotheque-debug' ),
		VMTQ_DEBUGGER_PLUGIN_COMPAT
	);
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Admin notice for missing Vimeotheque PRO plugin.
 * @return void
 */
function vmtq_debugger_no_plugin(){
	/* translators: %s: WordPress version */
	$message = sprintf(
		esc_html__( 'Vimeotheque Debugger requires plugin Vimeotheque to be installed. Please install %s plugin to enable the add-on functionality.', 'vimeotheque-debug' ),
		sprintf(
			'<a href="%s" target="_blank">%s</a>',
			'https://wordpress.org/plugins/codeflavors-vimeo-video-post-lite/',
			'Vimeotheque'
		)
	);
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}