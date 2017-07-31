<?php get_header(); ?>

<?php

$config 	= JW_Showcase\get_config();
$playlists 	= JW_Showcase\get_playlists();

?>

<h1><?php echo esc_html( $config->siteName ); ?></h1>
<h2><?php echo esc_html( $config->description ); ?></h2>

<?php if ( ! empty( $playlists ) ) : ?>

<h3>Playlists</h3>
<div>
	<?php if ( ! empty( $playlists[0] ) ) : ?>

		<?php $video = $playlists[0]->playlist[0]; ?>

		<div class="jw-player-showcase-wrapper">
			<div class="jw-player-showcase" id="jw-player-showcase" data-video="<?php echo esc_attr( wp_json_encode( $video ) ); ?>">
				<img src="<?php echo esc_url( $video->image ); ?>" alt="<?php echo esc_attr( $video->title ); ?>" />
			</div>
		</div>

	<?php endif; ?>

	<?php foreach ( $playlists as $playlist ) : ?>

		<div>
			<a href="<?php echo esc_url( home_url( '/playlist/' . $playlist->feedid ) ); ?>"><?php echo esc_html( $playlist->title ); ?></a>
		</div>
		<div class="jw-player-showcase-playlist-container">
			<div class="jw-player-showcase-playlist">

			<?php foreach ( $playlist->playlist as $video ) : ?>

			<div class="jw-player-showcase-playlist-item">
				<a href="<?php echo esc_url( home_url( '/playlist/' . $playlist->feedid . '/video/' . $video->mediaid ) ); ?>">
					<img src="<?php echo esc_url( $video->image ); ?>" alt="<?php echo esc_attr( $video->title ); ?>" />
				</a>
			</div>

			<?php endforeach; ?>

			</div>
		</div>

	<?php endforeach; ?>
</div>

<?php endif; ?>

<?php get_footer();
