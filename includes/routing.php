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

/**
 * Pre Get Posts
 * The query should not think that any video page is the homepage
 */

function pre_get_posts( $query ) {

	if ( is_video() ) {
		$query->is_home = false;
	}

}
add_action( 'pre_get_posts', __NAMESPACE__ . '\\pre_get_posts' );

/**
 * Document Title Parts
 * @param array
 * @return array
 */

function document_title_parts( $title ) {

	if ( ! is_video() ) {
		return $title;
	}

	$playlist 	= get_playlist();
	$video 		= get_video();

	if ( ! $playlist && ! $video ) {
		return array_merge( array( 'Videos' ), $title );
	}

	if ( $playlist && ! $video ) {
		return array_merge( array( $playlist->title, 'Video Playlist' ), $title );
	}

	if ( $video ) {
		return array_merge( array( $video->title, 'Video' ), $title );
	}

	return $title;

}

add_filter( 'document_title_parts', __NAMESPACE__ . '\\document_title_parts' );

/**
 * Open Graph Base Tags
 * @param array
 * @return array
 */

function jetpack_open_graph_base_tags( $tags ) {

	if ( ! is_video() ) {
		return $tags;
	}

	$playlist 	= get_playlist();
	$video 		= get_video();

	$tags['og:title'] = wp_get_document_title();
	$tags['twitter:title'] = $tags['og:title'];

	if ( ! $playlist && ! $video ) {

		// Video Archive
		$tags['og:url'] = esc_url( home_url( '/videos/' ) );
		$tags['twitter:url'] = $tags['og:url'];

	} elseif ( $playlist && ! $video ) {

		// Playlist
		$tags['og:url'] = esc_url( home_url( "/playlist/{$playlist->feedid}/" ) );
		$tags['twitter:url'] = $tags['og:url'];

	} elseif ( $playlist && $video ) {

		// Single Video
		$tags['og:url'] = esc_url( home_url( "/playlist/{$playlist->feedid}/video/{$video->mediaid}" ) );
		$tags['twitter:url'] = $tags['og:url'];

	}

	return $tags;

}
add_filter( 'jetpack_open_graph_base_tags', __NAMESPACE__ . '\\jetpack_open_graph_base_tags', 10, 2 );

/**
 * Open Graph Tags
 * @param array
 * @return array
 */

function jetpack_open_graph_tags( $tags ) {

	if ( ! is_video() ) {
		return $tags;
	}

	$playlist 	= get_playlist();
	$video 		= get_video();

	if ( ! $playlist && ! $video ) {

		// Video Archive

	} elseif ( $playlist && ! $video ) {

		// Playlist

		$tags['og:type'] = 'article';
		$tags['og:image'] = esc_url( $playlist->playlist[0]->image );
		$tags['og:image:type'] = 'image/jpeg';
		$tags['og:description'] = $playlist->description;

		$tags['twitter:card'] = 'summary_large_image';
		$tags['twitter:image'] = $tags['og:image'];
		$tags['twitter:description'] = $tags['og:description'];

	} elseif ( $playlist && $video ) {

		// Single Video

		$tags['og:type'] = 'article';
		$tags['og:image'] = esc_url( $video->image );
		$tags['og:image:type'] = 'image/jpeg';
		$tags['og:description'] = $video->description;

		$tags['twitter:card'] = 'summary_large_image';
		$tags['twitter:image'] = $tags['og:image'];
		$tags['twitter:description'] = $tags['og:description'];

	}

	return $tags;

}
add_filter( 'jetpack_open_graph_tags', __NAMESPACE__ . '\\jetpack_open_graph_tags', 10, 1 );
