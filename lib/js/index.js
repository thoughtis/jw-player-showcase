;(function( window, document, $, undefined ){

  var id    = 'jw-player-showcase',
    player  = null,
    video   = null
  ;

  document.addEventListener( 'DOMContentLoaded', function( event ) {

    init();

  });

  function init(){

    player = document.getElementById( id );

    if ( ! player ) {
      return;
    }

    setup_video();

  }

  function setup_video(){

    video = JSON.parse( player.getAttribute( 'data-video' ) );

    jwplayer( id ).setup( video );

  }

})( window, document, jQuery )