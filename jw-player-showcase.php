<?php
/*
Plugin Name: JW Player Video Page
Plugin URI: https://github.com/thoughtis/jw-player-showcase
Description: Use JW Player's APIs to create a server side rendered version of the JW Showcase
Author: Thought.is
Version: 1.0.0
*/

if ( defined( 'JW_SHOWCASE_CONFIG_URL' ) && function_exists( 'wpcom_vip_file_get_contents' ) ) {

	define( 'JWSHOWCASE_PLUGIN_DIR', dirname( __FILE__ ) );
	define( 'JWSHOWCASE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

	require_once( __DIR__ . '/includes/class-request-cache.php' );
	require_once( __DIR__ . '/includes/class-media-request.php' );
	require_once( __DIR__ . '/includes/class-playlist-request.php' );

	require_once( __DIR__ . '/includes/functions.php' );
	require_once( __DIR__ . '/includes/routing.php' );
	require_once( __DIR__ . '/includes/assets.php' );

}
