var player_id 				= 'jw-player-showcase',
	single_video_class 		= 'jw-player-showcase-single-video',
	player 					= null,
	video 					= null,
	single_video 			= []
;

function load_player() {

	document.addEventListener( 'DOMContentLoaded', function( event ) {

		init_player();

	});
}

function init_player(){

	player = document.getElementById( player_id );

	if ( ! player ) {
		return;
	}

	setup_video();

}

function setup_video(){

	video = JSON.parse( player.getAttribute( 'data-video' ) );

	single_video  = document.getElementsByClassName( single_video_class );

	if ( 0 !== single_video.length ) {

		video["autostart"] = true;

	}

	jwplayer( player_id ).setup( video );

}

export default load_player;