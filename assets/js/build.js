(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

;(function (window, document, $, undefined) {

  var id = 'jw-player-showcase',
      player = null,
      video = null;

  document.addEventListener('DOMContentLoaded', function (event) {

    init();
  });

  function init() {

    player = document.getElementById(id);

    if (!player) {
      return;
    }

    setup_video();
  }

  function setup_video() {

    video = JSON.parse(player.getAttribute('data-video'));

    jwplayer(id).setup(video);
  }
})(window, document, jQuery);

},{}]},{},[1])

//# sourceMappingURL=build.js.map
