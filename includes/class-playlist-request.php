<?php

namespace JW_Showcase;

use WP_Error;

class Playlist_Request extends Media_Request {

	/**
	 * Set in Object Cache
	 * Extended to store video objects individually when storing playlists
	 */

	public function set_in_object_cache() {

		// Use random expiration so they don't all uncache at once.
		$exp = ( HOUR_IN_SECONDS * 6 ) + rand( MINUTE_IN_SECONDS, HOUR_IN_SECONDS );

		// Add to object cache
		wp_cache_set( $this->id, $this->data, $this->cache_group, $exp );

		// Add videos within playlist to cache
		foreach ( $this->data->playlist as $video ) {

			$exp = ( HOUR_IN_SECONDS * 6 ) + rand( MINUTE_IN_SECONDS, HOUR_IN_SECONDS );

			wp_cache_set( $video->mediaid, $video, 'jw_player_showcase_videos', $exp );

		}

	}

	/**
	 * Get from API
	 * Extended to match playlist JSON structure
	 */

	public function get_from_api() {

		// Request from JW Player
		$url = "https://cdn.jwplayer.com/{$this->endpoint}/{$this->id}";

		$request = wpcom_vip_file_get_contents( $url, 3, HOUR_IN_SECONDS );

		if ( true !== is_string( $request ) ) {

			return false;

		}

		$playlist = json_decode( $request );

		if ( true !== is_object( $playlist ) || true !== property_exists( $playlist, 'playlist' ) ) {

			return false;

		}

		$this->data = $playlist;

		return true;

	}

}
