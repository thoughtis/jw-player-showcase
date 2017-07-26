<?php

/**
 * Rewrites
 */

add_action( 'init', function() {

	add_rewrite_rule( 'videos', 'index.php?section=videos', 'top' );

	add_rewrite_rule( 'playlist/([^/]+)/?$', 'index.php?section=videos&playlist=$matches[1]', 'top' );

	add_rewrite_rule( 'playlist/([^/]+)/video/([^/]+)/?$', 'index.php?section=videos&playlist=$matches[1]&video=$matches[2]', 'top' );

});

/**
 * Query Vars
 */
add_filter( 'query_vars', function( $vars ) {

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

} );

/**
 * Template Includes
 *
 * @param string $template
 * @return string $template
 */

add_filter( 'template_include', function( $template ) {

	$section 	= get_query_var( 'section' );
	$playlist 	= get_query_var( 'playlist' );
	$video 		= get_query_var( 'video' );

	// Not our section, move on
	if ( 'videos' !== $section ) {
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

});

function do_404() {

	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	get_template_part( 404 );
	exit();

}

add_action( 'template_redirect', function() {

	$section 	= get_query_var( 'section' );
	$playlist 	= get_query_var( 'playlist' );
	$video 		= get_query_var( 'video' );

	// Not our section, move on
	if ( 'videos' !== $section ) {
		return;
	}

	// Video Archive
	if ( '' === $playlist && '' === $video ) {
		return;
	}

	// Playlist
	if ( '' !== $playlist && '' === $video ) {

		if ( ! JW_Showcase\get_playlist() ) {

			return do_404();

		}
	}

	// Video
	if ( '' !== $playlist && '' !== $video ) {

		if ( ! JW_Showcase\get_playlist() || ! JW_Showcase\get_video() ) {

			return do_404();

		}
	}

});

/**
 * Is Video ?
 * @return boolean
 */

function is_video() {

	return 'videos' === get_query_var( 'section' );

}

/**
 * Add Player Javascript using defer
 */

add_action( 'wp_footer', function() {

	if ( true !== is_video() ) {
		return;
	}

	$config = JW_Showcase\get_config();

	?>

	<script src="<?php echo esc_url( "https://content.jwplatform.com/libraries/{$config->player}.js" ); ?>" defer="true"></script>

	<?php

} );
