const player_id 			= 'jw-player-showcase';
const single_video_class 	= 'jw-player-showcase-single-video';

let	player 			= null,
	video 			= null,
	single_video 	= null,
	video_data 		= null
;

/**
 * Load Player
 *
 */

function load_player() {

	document.addEventListener( 'DOMContentLoaded', function( event ) {

		init_player();

	});
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

	setup_video();

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

function setup_video() {

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

	jwplayer( player_id ).setup( video );

}

export default load_player;