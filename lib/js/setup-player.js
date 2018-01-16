import video_header_bidding from './video-header-bidding';

const player_id 			= 'jw-player-showcase';
const single_video_class 	= 'jw-player-showcase-single-video';

let	player 			= null,
	video 			= null,
	single_video 	= null,
	video_data 		= null
;

let video_instance = null;

var video_ad_unit = null;

var ad_url = null;

var _bidderSettings = null;

var bidderTimeout 	= null,
	video_is_setup 	= false,
	timeout_length 	= 5000
;

/**
 * Load Player
 *
 */

function load_player() {

	document.addEventListener( 'DOMContentLoaded', function( event ) {

		init_player();

	});

	// new
	window.pbjs = window.pbjs || {};
    window.pbjs.que = window.pbjs.que || [];
    window.pbjs.bidderSettings = video_header_bidding.get_bidder_settings();

    video_ad_unit = video_header_bidding.get_video_ad_unit( player_id );

}

/**
 * Create Video Instance
 */
function _create_video_instance(){

	video_instance = jwplayer( player_id );

}

/**
 * Init Player
 *
 */

function init_player() {

	player = document.getElementById( player_id );

	if ( ! player ) {
		return;
	}

	_create_video_instance();
	_request_bids();

}

/**
 * Is Single Video
 *
 */

function is_single_video() {

	single_video = document.getElementsByClassName( single_video_class );

	return 0 < single_video.length;

}

/**
 * Setup Video
 *
 */

function setup_video( source ) {

	if( video_is_setup ) {
		return;
	}

	video_is_setup = true;

	video_data = player.getAttribute( 'data-video' );

	if ( 'string' !== typeof video_data ) {

		console.error( 'Video: Unusable data' );

		return;

	}

	try {

		video = JSON.parse( video_data );

	} catch( e ) {

		console.error( 'JSON Parse Error:', e.message );

		return;

	}

	video['autostart'] = is_single_video();
	video['advertising']= {
      "client": "googima",
    };

	video_instance.setup( video );

	if ( 'bids' === source ) {
		video_instance.on( 'beforePlay', _before_play );
	}

}

/**
 * Before Play
 */
function _before_play() {

	document.querySelector( '#' + player_id + ' video' ).setAttribute( 'id', player_id + '_video' );

  	video_instance.playAd( ad_url );

}

/**
 * Request Bids
 */
function _request_bids(){

	bidderTimeout = setTimeout( setup_video.bind( null, 'timeout' ), timeout_length );

	window.pbjs.que.push(function(){

		pbjs.addAdUnits( video_ad_unit );

		pbjs.setConfig({ usePrebidCache: true });

		pbjs.requestBids({ bidsBackHandler: _bids_back_handler });

	});

}

/**
 *
 */
function _bids_back_handler( bids ) {

	clearTimeout( bidderTimeout );

	ad_url = window.pbjs.adServers.dfp.buildVideoUrl({
		adUnit: video_ad_unit,
		params: {
			iu: '/2322946/Video_TEST_HTL',
			output: "vast"
		}
	});

    setup_video( 'bids' );

}

export default load_player;