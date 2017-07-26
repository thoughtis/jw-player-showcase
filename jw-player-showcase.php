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

	add_action( 'wp_enqueue_scripts', function() {

		wp_enqueue_script( 'jw-player-showcase', plugin_dir_url( __FILE__ ) . 'assets/js/build.js', array(), '1.0.0', true );

		wp_enqueue_style( 'jw-player-showcase', plugin_dir_url( __FILE__ ) . 'assets/css/app.css', array(), '1.0.0', 'screen' );

	});

}
