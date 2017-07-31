import $ from 'jquery';
import 'slick-carousel';

const 	screen_xs = 0,
		screen_sm = 480,
		screen_md = 768,
		screen_lg = 992,
		screen_xl = 1500
;

function init_carousel() {
	$('.jw-player-showcase-playlist').slick({
		slidesToShow: 4,
		slidesToScroll: 4,
		responsive: [{
			breakpoint: screen_lg,
			settings: {
				slidesToShow: 3,
				slidesToScroll: 3
			}
		},
		{
			breakpoint: screen_md,
			settings: {
				slidesToShow: 2,
				slidesToScroll: 2
			}
		},
		{
			breakpoint: screen_sm,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				swipeToSlide: true,
				arrows: false
			}
		}]
	});
}

export default init_carousel;