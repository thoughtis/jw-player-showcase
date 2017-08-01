<?php

namespace JW_Showcase;

/**
 * Enqueue Scripts
 * Enqueue JS and CSS Assets
 */

function enqueue_assets() {

	wp_enqueue_script( 'jw-player-showcase', JWSHOWCASE_PLUGIN_URL . 'assets/js/build.js', array(), '1.0.2', true );

	wp_enqueue_style( 'jw-player-showcase', JWSHOWCASE_PLUGIN_URL . 'assets/css/app.css', array(), '1.0.0', 'screen' );

}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );

/**
 * Add JW Player Javascript using defer
 */

function footer() {

	if ( true !== is_video() ) {
		return;
	}

	$config = get_config();

	?>

	<script src="<?php echo esc_url( "https://content.jwplatform.com/libraries/{$config->player}.js" ); ?>" defer="true"></script>

	<?php

}
add_action( 'wp_footer', __NAMESPACE__ . '\\footer' );
