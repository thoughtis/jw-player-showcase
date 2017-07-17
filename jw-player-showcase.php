<?php
/*
Plugin Name: JW Player Video Page
Plugin URI: https://github.com/thoughtis/jw-player-showcase
Description: Use JW Player's APIs to create a server side rendered version of the JW Showcase
Author: Thought.is
Version: 1.0.0
*/

/**
 * Requires the existing JW Player Plugin
 * Developed with version 1.5.1
 */

if ( defined( 'JWPLAYER_PLUGIN_VERSION' ) && defined( 'JW_SHOWCASE_CONFIG_URL' ) ) {

	define( 'JWSHOWCASE_PLUGIN_DIR', dirname( __FILE__ ) );

	require_once( __DIR__ . '/includes/functions.php' );
	require_once( __DIR__ . '/includes/plugin.php' );

}
