<?php

namespace JW_Showcase;

use Exception;

$request_cache = array(

	'video' 	=> array(),
	'playlist' 	=> array(),

);

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

function get_playlist_id() {

	return get_query_var( 'playlist' );

}

function get_playlist( $id = null ) {

	global $request_cache;

	if ( null === $id ) {

		$id = get_playlist_id();

	}

	if ( '' === $id ) {

		return false;

	}

	// request cache
	if ( true === isset( $request_cache['playlist'][ $id ] ) ) {

		return $request_cache['playlist'][ $id ];

	}

	// object cache
	$cached_version = wp_cache_get( $id, 'jw_player_showcase_playlists' );

	if ( false !== $cached_version ) {

		$request_cache['playlist'][ $id ] = $cached_version;

		return $cached_version;

	}

	$url = "https://cdn.jwplayer.com/v2/playlists/{$id}";

	$request = wpcom_vip_file_get_contents( $url, 3, 900 );

	if ( true !== is_string( $request ) ) {

		return false;

	}

	$request = json_decode( $request );

	if ( true !== is_object( $request ) || true !== property_exists( $request, 'playlist' ) ) {

		return false;

	}

	// Use random expiration so they don't all uncache at once.
	$exp = ( HOUR_IN_SECONDS * 6 ) + rand( MINUTE_IN_SECONDS, HOUR_IN_SECONDS );

	$request_cache['playlist'][ $id ] = $request;

	// cache playlist
	wp_cache_set( $id, $request, 'jw_player_showcase_playlists', $exp );

	// cache videos in playlist
	foreach ( $request->playlist as $video ) {

		$exp = ( HOUR_IN_SECONDS * 6 ) + rand( MINUTE_IN_SECONDS, HOUR_IN_SECONDS );

		wp_cache_set( $video->mediaid, $video, 'jw_player_showcase_videos', $exp );

	}

	return $request;

}

/**
 * Get Playlists
 * @param object $config
 */

function get_playlists( $config = null ) {

	$playlists = array();

	if ( null === $config ) {

		$config = get_config();

	}

	if ( ! property_exists( $config, 'playlists' ) || ! is_array( $config->playlists ) ) {

		$config->playlists = $playlists;

	}

	if ( property_exists( $config, 'featuredPlaylist' ) && is_string( $config->featuredPlaylist ) ) {

		$config->playlists[] = $config->featuredPlaylist;

	}

	$playlists = array_map( __NAMESPACE__ . '\\get_playlist', $config->playlists );

	return $playlists;

}

function get_video_id() {

	return get_query_var( 'video' );

}

function get_video( $id = null ) {

	global $request_cache;

	if ( null === $id ) {

		$id = get_video_id();

	}

	if ( '' === $id ) {

		return false;

	}

	// request cache

	if ( true === isset( $request_cache['video'][ $id ] ) ) {

		return $request_cache['video'][ $id ];

	}

	// object cache

	$cached_version = wp_cache_get( $id, 'jw_player_showcase_videos' );

	if ( false !== $cached_version ) {

		$request_cache['video'][ $id ] = $cached_version;

		return $cached_version;

	}

	$url = "https://cdn.jwplayer.com/v2/media/{$id}";

	$request = wpcom_vip_file_get_contents( $url, 3, 900 );

	if ( true !== is_string( $request ) ) {

		return false;

	}

	$request = json_decode( $request );

	if ( true !== is_object( $request ) || true !== property_exists( $request, 'playlist' ) ) {

		return false;

	}

	$video = $request->playlist[0];

	$request_cache['video'][ $id ] = $video;

	// Use random expiration so they don't all uncache at once.
	$exp = ( HOUR_IN_SECONDS * 6 ) + rand( MINUTE_IN_SECONDS, HOUR_IN_SECONDS );

	wp_cache_set( $id, $video, 'jw_player_showcase_videos', $exp );

	return $video;

}
