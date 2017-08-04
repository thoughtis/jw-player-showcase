# JW Player Showcase as WordPress a VIP Plugin

**NOTE: This repo is for development purposes. This plugin has not been submitted for WordPress review. It is not recommended for use.**

## Requirements

- Uses WordPress VIP specific functions for external API requests and caching.
  - Will fail without these functions, but will fail early and silently.

## Setup

Before importing into your theme as a plugin, define a constant for your JSON config. You must enable JW Showcase through JW Player to obtain this URL. It is availble by inspecting your JW Showcase homepage. Example:

```php
define( 'JW_SHOWCASE_CONFIG_URL', 'https://thoughtcatalog.jwpapp.com/config.json' );
```

## Routing

The plugin creates three routes within the WordPress using `add_rewrite_rule()`

- `/videos`
- `/playlist/%playlist-id%`
- `/playlist/%playlist-id%/video/%video-id%`

### 404s

- If a video or playlist is not found during the `template_redirect` action, a 404 is returned and the request is redirected to the 404 template.
- If the config is not found or fails, the `/videos` route will still render, but with an empty array of playlists.

## API Interactions and Caching

### Config

- Both playlists and video depend on successful retrieval of the config file. It contains a list of playlists to display at the `/videos` route.
- Config is cached for 1 hour

### Playlists

- Playlists are cached for at least 6 hours and up to 7. The last hour is a random offset of minutes. This is done to avoid uncaching all playlists simultaneously.
- When a playlist is retrieved, all the videos within it are cached for 6-7 hours as well.

### Videos

- Videos requests are cached for 6-7 hours.
- Only the video->playlist[0] portion of the video API response is cached because it is identical to the video object in the playlist API response. As a result the `feed_instance_id` and `kind` fields are not present.

## Front-End

### Javascript

We use [Slick](https://kenwheeler.github.io/slick/) to display playlists as carousels.

### CSS

A bare minimum of CSS is provided. Any theme using this plugin should extend the styling to match their local templates.

### Templates

The plugin contains a default template for each route. They provide barebones support, and are intended as examples for how to structure your own templates.

Three filters are provided so that you can override the default template with your own from your theme.

- jwshowcase_archive_template
- jwshowcase_playlist_template
- jwshowcase_single_template

Implementing them through your template functions can be done like this
```php
/**
 * Local Template for Video Archive
 */

add_filter( 'jwshowcase_archive_template', function( $default_template ) {

	$local_template = locate_template( array( 'archive-video.php' ) );

	if ( '' !== $local_template ) {

		return $local_template;

	}

	return $default_template;

});
```