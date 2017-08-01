<?php

namespace JW_Showcase;

use WP_Error;

/**
 * Do 404
 */

function do_404() {

	global $wp_query;

	$wp_query->set_404();

	status_header( 404 );

	get_template_part( '404' );

	exit();

}

/**
 * Is Video?
 * @return boolean
 */

function is_video() {

	return 'videos' === get_query_var( 'section' );

}

/**
 * Get Config
 * Request the JSON configuration file from JW Player
 *
 * @return mixed object|boolean
 */

function get_config() {

	$request = wpcom_vip_file_get_contents( JW_SHOWCASE_CONFIG_URL, 3, HOUR_IN_SECONDS );

	if ( true !== is_string( $request ) ) {

		return false;

	}

	$request = json_decode( $request );

	if ( true !== is_object( $request ) || true !== property_exists( $request, 'siteName' ) ) {

		return false;

	}

	return $request;

}

/**
 * Get Playlist ID
 *
 * @return string
 */

function get_playlist_id() {

	return get_query_var( 'playlist' );

}

/**
 * Get Playlist
 *
 * @return object|boolean
 */

function get_playlist( $id = null ) {

	// Use ID if provided
	if ( null === $id ) {

		// Otherwise get it from WP Request
		$id = get_playlist_id();

	}

	// ID Required
	if ( '' === $id ) {

		return false;

	}

	$playlist = new Playlist_Request( $id, 'v2/playlists', 'playlist' );

	if ( true === is_wp_error( $playlist->request() ) ) {

		return false;

	}

	return $playlist->data;

}

/**
 * Get Playlists
 *
 * @param object $config
 */

function get_playlists( $config = null ) {

	$playlists = array();

	// Use config if provided
	if ( null === $config ) {

		// Otherwise get from WP Request
		$config = get_config();

	}

	if ( ! property_exists( $config, 'playlists' ) || ! is_array( $config->playlists ) ) {

		$config->playlists = $playlists;

	}

	if ( property_exists( $config, 'featuredPlaylist' ) && is_string( $config->featuredPlaylist ) ) {

		array_unshift( $config->playlists, $config->featuredPlaylist );

	}

	$playlists = array_map( __NAMESPACE__ . '\\get_playlist', $config->playlists );

	return $playlists;

}

function get_video_id() {

	return get_query_var( 'video' );

}

/**
 * Get Video
 *
 * @param 	string|null 	$id
 * @return 	object|boolean
 */

function get_video( $id = null ) {

	// Use ID if provided
	if ( null === $id ) {

		// Otherwise get from WP Request
		$id = get_video_id();

	}

	// Require ID
	if ( '' === $id ) {

		return false;

	}

	$video = new Media_Request( $id, 'v2/media', 'media' );

	if ( true === is_wp_error( $video->request() ) ) {

		return false;

	}

	return $video->data;

}
