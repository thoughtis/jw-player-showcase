(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

var _setupPlayer = require('./setup-player');

var _setupPlayer2 = _interopRequireDefault(_setupPlayer);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

(0, _setupPlayer2.default)();

},{"./setup-player":2}],2:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});
var player_id = 'jw-player-showcase',
    single_video_class = 'jw-player-showcase-single-video',
    player = null,
    video = null,
    single_video = [];

function load_player() {

	document.addEventListener('DOMContentLoaded', function (event) {

		init_player();
	});
}

function init_player() {

	player = document.getElementById(player_id);

	if (!player) {
		return;
	}

	setup_video();
}

function setup_video() {

	video = JSON.parse(player.getAttribute('data-video'));

	single_video = document.getElementsByClassName(single_video_class);

	if (0 !== single_video.length) {

		video["autostart"] = true;
	}

	jwplayer(player_id).setup(video);
}

exports.default = load_player;

},{}]},{},[1])

//# sourceMappingURL=build.js.map
