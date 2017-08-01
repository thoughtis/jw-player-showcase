<?php

namespace JW_Showcase;

use WP_Error;

/**
 * Class Media_Request
 * @description request something from the JW Player media API endpoint.
 * Store it for reuse during this request and in the object cache as well.
 */

class Media_Request {

	public $id;
	public $data;
	public $endpoint;
	public $type;
	public $cache_group;

	/**
	 * Construct
	 * @param string $id
	 * @param string $endpoint
	 * @param string $type
	 */

	public function __construct( $id = '', $endpoint = '', $type = '' ) {

		$this->id 			= $id;
		$this->endpoint 	= $endpoint;
		$this->type 		= $type;
		$this->cache_group 	= "jw_player_showcase_{$type}";

	}

	/**
	 * Request
	 *
	 * @return mixed object|WP_Error
	 */

	public function request() {

		if ( $this->get_from_request_cache() ) {

			return $this->data;

		} elseif ( $this->get_from_object_cache() ) {

			$this->set_in_request_cache();

			return $this->data;

		} elseif ( $this->get_from_api() ) {

			$this->set_in_request_cache();
			$this->set_in_object_cache();

			return $this->data;

		}

		return new WP_Error( 'JW_Showcase', "{$this->type} not found" );

	}

	/**
	 * Get From Request Cache
	 *
	 * @return boolean
	 */

	public function get_from_request_cache() {

		global $jw_request_cache;

		$this->data = $jw_request_cache->get( $this->type, $this->id );

		if ( $this->data ) {

			return true;

		}

		return false;

	}

	/**
	 * Set in Request Cache
	 *
	 * @return boolean
	 */

	public function set_in_request_cache() {

		global $jw_request_cache;

		$jw_request_cache->set( $this->type, $this->id, $this->data );

	}

	/**
	 * Get From Object Cache
	 *
	 * @return boolean
	 */

	public function get_from_object_cache() {

		$this->data = wp_cache_get( $this->id, $this->cache_group );

		if ( false !== $this->data ) {

			return true;

		}

		return false;

	}

	/**
	 * Set in Object Cache
	 *
	 * @return boolean
	 */

	public function set_in_object_cache() {

		// Use random expiration so they don't all uncache at once.
		$exp = ( HOUR_IN_SECONDS * 6 ) + rand( MINUTE_IN_SECONDS, HOUR_IN_SECONDS );

		// Add to object cache
		wp_cache_set( $this->id, $this->data, $this->cache_group, $exp );

	}

	/**
	 * Get from API
	 *
	 * @return boolean
	 */

	public function get_from_api() {

		// Request from JW Player
		$url = "https://cdn.jwplayer.com/{$this->endpoint}/{$this->id}";

		$request = wpcom_vip_file_get_contents( $url, 3, HOUR_IN_SECONDS );

		if ( true !== is_string( $request ) ) {

			return false;

		}

		$request = json_decode( $request );

		if ( true !== is_object( $request ) || true !== property_exists( $request, 'playlist' ) ) {

			return false;

		}

		// Use version in playlist object for better sync with playlists
		$this->data = $request->playlist[0];

		return true;

	}

}
