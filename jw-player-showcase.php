<?php
/*
Plugin Name: JW Player Showcase
Plugin URI: https://github.com/thoughtis/jw-player-showcase
Description: Use JW Player's APIs to create a server side rendered version of the JW Showcase
Author: Thought.is
Author URI: https://developer.wordpress.org/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'WPINC' ) ) {
	exit;
}

if ( defined( 'JW_SHOWCASE_CONFIG_URL' ) ) {

	define( 'JWSHOWCASE_PLUGIN_DIR', dirname( __FILE__ ) );
	define( 'JWSHOWCASE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

	require_once( __DIR__ . '/includes/class-request-cache.php' );
	require_once( __DIR__ . '/includes/class-media-request.php' );
	require_once( __DIR__ . '/includes/class-playlist-request.php' );

	require_once( __DIR__ . '/includes/functions.php' );
	require_once( __DIR__ . '/includes/routing.php' );
	require_once( __DIR__ . '/includes/assets.php' );

}
