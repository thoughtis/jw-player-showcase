<?php

namespace JW_Showcase;

class Request_Cache {

	public $store;

	/**
	 * Construct
	 */

	public function __construct() {

		$store = array();

	}

	/**
	 * Get
	 * @param 	string $type
	 * @param 	string $key
	 * @return 	mixed
	 */

	public function get( $type, $key ) {

		if ( ! isset( $this->store[ $type ] ) ) {
			$this->store[ $type ] = array();
		}

		if ( isset( $this->store[ $type ][ $key ] ) ) {
			return $this->store[ $type ][ $key ];
		}

		return false;

	}

	/**
	 * Get
	 * @param 	string 	$type
	 * @param 	string 	$key
	 * @param 	mixed 	$value
	 */

	public function set( $type, $key, $value ) {

		if ( ! isset( $this->store[ $type ] ) ) {
			$this->store[ $type ] = array();
		}

		$this->store[ $type ][ $key ] = $value;

		$this->store[ $key ] = $value;

	}

}

$jw_request_cache = new Request_Cache();
