const player_id 			= 'jw-player-showcase';
const single_video_class 	= 'jw-player-showcase-single-video';

let	player 			= null,
	video 			= null,
	single_video 	= null,
	video_data 		= null
;

let video_instance = null;

var video_ad_unit = {
    code: player_id,
    sizes: [640, 480],
    mediaTypes: {
      video: {
        context: "instream"
      }
    },
    bids: [
      {
        bidder: 'tremor',
        params: {
          supplyCode: 'hhrx1-tjkxo',
          adCode: 'hhrx1-3fzhs'
        }
      },
      {
        bidder: 'appnexusAst',
        params: {
          placementId: '12553195',
          video: {
            skipppable: true,
            playback_method: ['auto_play_sound_off']
          }
        }
      },
      {
        bidder: 'spotx',
        params: {
          video: {
            channel_id: 209718,
            video_slot: player_id + '_video',
            slot: player_id,
            hide_skin: false,
            autoplay: true,
            ad_mute: false
          }
        }
      }
    ]
  };

var ad_url = null;

var _bidderSettings = {
    standard: {
        adserverTargeting: [
            {
                key: "hb_bidder",
                val: function (bidResponse) {
                    return bidResponse.bidderCode;
                }
            }, {
                key: "hb_adid",
                val: function (bidResponse) {
                    return bidResponse.adId;
                }
            }, {
                key: "hb_pb",
                val: function(bidResponse) {
                    var cpm = bidResponse.cpm;
                    if (cpm < 10.00) {
                        return (Math.floor(cpm * 200) / 200).toFixed(2); // $0.05, up to $10.00
                    } else if (cpm < 20.00) {
                        return (Math.floor(cpm * 10) / 10).toFixed(2); // $0.10, up to $20.00
                    } else if (cpm < 50.00) {
                        return (Math.floor(cpm * 50) / 50).toFixed(2); // $0.50, up to $50.00
                    } else if (cpm <= 99.00) {
                        return (Math.floor(cpm * 1) / 1).toFixed(2); // $1.00, up to $99.00
                    } else {
                        return '100.00';
                    }
                }
            }, {
                key: "hb_size",
                val: function (bidResponse) {
                    return bidResponse.size;

                }
            }
        ]
    }
};

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
    window.pbjs.bidderSettings = _bidderSettings;

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

function setup_video( use_before_play, b, c ) {

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

	if ( true === use_before_play ) {
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

	bidderTimeout = setTimeout( setup_video.bind( null, false ), timeout_length );

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

    //_maybe_invoke_video_player();
    setup_video( true );

}

export default load_player;