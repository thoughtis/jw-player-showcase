import $ from 'jquery';
import 'slick-carousel';

const 	screen_sm = 0,
		screen_md = 720,
		screen_lg = 1280,
		screen_xl = 1600
;

/**
 * Init Carousel
 * Apply slick carousel plugin to playlist elements
 */

function init_carousel() {

	const $playlists = $( '.jw-player-showcase-playlist' );

	if ( 0 === $playlists.length ) {

		return;

	}

	const config = {

		slidesToShow: 3,
		slidesToScroll: 3,
		arrows: true,
		responsive: [{
			breakpoint: screen_xl,
			settings: {
				slidesToShow: 3,
				slidesToScroll: 3,
				arrows: true,
			}
		},
		{
			breakpoint: screen_lg,
			settings: {
				slidesToShow: 2,
				slidesToScroll: 2,
				arrows: true,
			}
		},
		{
			breakpoint: screen_md,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				swipeToSlide: true,
				arrows: false
			}
		}
		]

	};

	$playlists.slick( config );

}

export default init_carousel;