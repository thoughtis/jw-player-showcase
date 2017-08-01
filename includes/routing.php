<?php

namespace JW_Showcase;

/**
 * Rewrites
 */

function init() {

	add_rewrite_rule( 'videos', 'index.php?section=videos', 'top' );

	add_rewrite_rule( 'playlist/([^/]+)/?$', 'index.php?section=videos&playlist=$matches[1]', 'top' );

	add_rewrite_rule( 'playlist/([^/]+)/video/([^/]+)/?$', 'index.php?section=videos&playlist=$matches[1]&video=$matches[2]', 'top' );

}
add_filter( 'init', __NAMESPACE__ . '\\init' );

/**
 * Query Vars
 */

function query_vars( $vars ) {

	if ( ! in_array( 'section', $vars, true ) ) {
		$vars[] = 'section';
	}

	if ( ! in_array( 'playlist', $vars, true ) ) {
		$vars[] = 'playlist';
	}

	if ( ! in_array( 'video', $vars, true ) ) {
		$vars[] = 'video';
	}

	return $vars;

}
add_filter( 'query_vars', __NAMESPACE__ . '\\query_vars', 10, 1 );

/**
 * Template Includes
 *
 * @param string $template
 * @return string $template
 */

function template_include( $template ) {

	$section 	= get_query_var( 'section' );
	$playlist 	= get_query_var( 'playlist' );
	$video 		= get_query_var( 'video' );

	// Not our section, move on
	if ( true !== is_video() ) {
		return $template;
	}

	$archive_template 	= JWSHOWCASE_PLUGIN_DIR . '/templates/archive-video.php';
	$playlist_template 	= JWSHOWCASE_PLUGIN_DIR . '/templates/archive-video-playlist.php';
	$single_template 	= JWSHOWCASE_PLUGIN_DIR . '/templates/single-video.php';

	// Archive ( no video or playlist )
	if ( '' === $playlist && '' === $video ) {
		return apply_filters( 'jwshowcase_archive_template', $archive_template );
	}

	// Playlist
	if ( ! empty( $playlist ) && '' === $video ) {
		return apply_filters( 'jwshowcase_playlist_template', $playlist_template );
	}

	// Video
	return apply_filters( 'jwshowcase_single_template', $single_template );

}
add_filter( 'template_include', __NAMESPACE__ . '\\template_include', 10, 1 );

/**
 * Template Redirect
 * Ensure content is available. If not, return 404.
 */

function template_redirect() {

	$section 	= get_query_var( 'section' );
	$playlist 	= get_query_var( 'playlist' );
	$video 		= get_query_var( 'video' );

	// Not our section, move on
	if ( true !== is_video() ) {
		return;
	}

	// Video Archive
	if ( '' === $playlist && '' === $video ) {
		return;
	}

	// Playlist
	if ( '' !== $playlist && '' === $video ) {

		if ( ! get_playlist() ) {

			return do_404();

		}
	}

	// Video
	if ( '' !== $playlist && '' !== $video ) {

		if ( ! get_playlist() || ! get_video() ) {

			return do_404();

		}
	}

}
add_action( 'template_redirect', __NAMESPACE__ . '\\template_redirect' );
