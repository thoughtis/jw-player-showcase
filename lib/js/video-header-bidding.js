/**
 * Video Header Bidding
 */

/**
 * Get Video Ad Unit
 * @param string id
 * @return object
 */
function get_video_ad_unit( id ) {

	return {
		code: id,
		sizes: [640, 480],
		mediaTypes: {
		video: {
			context: 'instream'
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
					video_slot: id + '_video',
					slot: id,
					hide_skin: false,
					autoplay: true,
					ad_mute: false,
					content_width: 720,
					content_height: 405
				}
			}
		},
		{
			bidder: 'indexExchange',
			params: {
				video: {
					siteID: 243748,
					playerType: 'HTML5',
					protocols: [2, 3],
					maxduration: 30
				}
			}
		}
		]
	};

}

/**
 * Get Bidder Settings
 * @return objects
 */
function get_bidder_settings() {

	return {
		standard: {
			adserverTargeting: [
				{
				key: 'hb_bidder',
					val: function (bidResponse) {
						return bidResponse.bidderCode;
					}
				}, {
					key: 'hb_adid',
					val: function (bidResponse) {
						return bidResponse.adId;
					}
				}, {
					key: 'hb_pb',
					val: function(bidResponse) {
						var cpm = bidResponse.cpm;
						if (cpm < 10.00) {
							return (Math.floor(cpm * 20) / 20).toFixed(2); // $0.05, up to $10.00
						} else if (cpm < 20.00) {
							return (Math.floor(cpm * 10) / 10).toFixed(2); // $0.10, up to $20.00
						} else if (cpm < 50.00) {
							return (Math.floor(cpm * 2) / 2).toFixed(2); // $0.50, up to $50.00
						} else if (cpm <= 99.00) {
							return (Math.floor(cpm * 1) / 1).toFixed(2); // $1.00, up to $99.00
						} else {
							return '100.00';
						}
					}
				}, {
					key: 'hb_size',
					val: function (bidResponse) {
						return bidResponse.size;
					}
				}
			]
		}
	};

}

module.exports = {
	get_video_ad_unit,
	get_bidder_settings
};