/**
 * Setup Player
 */

// Imports

import $ from 'jquery';
import video_hb from './video-header-bidding';

// ID of div that contains JWP tnstance
const selector = 'jw-player-showcase';

// Selector to tell if this is a single video
const single_video_class = 'jw-player-showcase-single-video';

	// Object with details of ad unit, supplied to Prebid
let video_ad_unit = null,

	// URL of video ad, returned from Prebid
	video_ad_url = 'https://pubads.g.doubleclick.net/gampad/ads?sz=400x300|640x480&iu=/2322946/Video_TEST_HTL&impl=s&gdfp_req=1&env=vp&output=vast&unviewed_position_start=1&url=[referrer_url]&description_url=__page-url__&correlator=[timestamp]&cust_params=playerwidth=__player-width__%26playerheight%3D__player-height__',

	// Object instance of JWP API
	// @see https://developer.jwplayer.com/jw-player/docs/javascript-api-reference/
	video_instance 	= null,

	// Object containing config we supply to JWP API
    video_config 	= null,

	// Placeholder for DOM Element containing the video
    video_container = null,

	// Placeholder for timeout ID
	bidder_timeout = null,

	// How long to wait for the timeout
	bidder_timeout_length = 5000

;

/**
 * Load Player
 *
 */

function load_player() {

	document.addEventListener( 'DOMContentLoaded', function( event ) {

		init_player();

	});

	window.pbjs = window.pbjs || {};
    window.pbjs.que = window.pbjs.que || [];
    window.pbjs.bidderSettings = video_hb.get_bidder_settings();

    video_ad_unit = video_hb.get_video_ad_unit( selector );

}

/**
 * Create Video Instance
 */
function _create_video_instance(){

	video_instance = jwplayer( selector );

}

/**
 * Init Player
 *
 */

function init_player() {

	video_container = document.getElementById( selector );

	if ( ! video_container ) {
		return;
	}

	_create_video_instance();

	_request_bids();

}

/**
 * Is Single Video
 *
 */
function _is_single_video() {

	const single_video = document.getElementsByClassName( single_video_class );

	console.log( single_video );

	return 0 < single_video.length;

}

/**
 * Setup Video Data
 */
function _setup_video_data(){

	let video_data = video_container.getAttribute( 'data-video' );

	try {

		JSON.parse( video_data );

	} catch( e ) {

		console.error( 'JSON Parse Error:', e.message );

		return {};

	}

	return JSON.parse( video_data );

}

/**
 * Setup Video
 */
function _setup_video( source ) {

	if ( true === _is_video_setup() ) {
		return;
	}

	video_config = _setup_video_data();

	video_config.autostart = _is_single_video();

	video_config.advertising = {
      "client": "googima",
    };

	video_instance.setup( video_config );

	if ( 'bids' === source ) {
		video_instance.on( 'beforePlay', _before_play );
	}

}

/**
 * Is Video Setup?
 * Does the video's config have any keys?
 * @return boolean
 */
function _is_video_setup() {

	if ( null === video_instance.getConfig() ) {
		return false;
	}

	return 0 < Object.keys( video_instance.getConfig() ).length;

}

/**
 * Before Play
 */
function _before_play() {

	document.querySelector( '#' + selector + ' video' ).setAttribute( 'id', selector + '_video' );

  	video_instance.playAd( video_ad_url );

}

/**
 * Request Bids
 */
function _request_bids(){

	bidder_timeout = setTimeout(
		_setup_video.bind( null, 'timeout' ),
		bidder_timeout_length
	);

	window.pbjs.que.push(function(){

		pbjs.addAdUnits( video_ad_unit );

		pbjs.setConfig({ usePrebidCache: true });

		pbjs.requestBids({
			adUnits: [ video_ad_unit ],
			bidsBackHandler: _bids_back_handler
		});

	});

}

/**
 * Has Winning Bid?
 * @return boolean
 */
function _has_winning_bid() {

	return 0 < window.pbjs.getHighestCpmBids( selector ).length;

}

/**
 *
 */
function _bids_back_handler( bids ) {

	if ( _has_winning_bid() ) {

		video_ad_url = window.pbjs.adServers.dfp.buildVideoUrl({
			adUnit: video_ad_unit,
			params: {
				iu: '/2322946/Video_TEST_HTL',
				output: "vast"
			}
		});

	}

	clearTimeout( bidder_timeout );

    _setup_video( 'bids' );

}

export default load_player;