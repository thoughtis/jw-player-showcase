<?php get_header(); ?>

<?php

$config 	= JW_Showcase\get_config();
$playlists 	= JW_Showcase\get_playlists();

?>

<h1><?php echo esc_html( $config->siteName ); ?></h1>
<h2><?php echo esc_html( $config->description ); ?></h2>

<?php if ( ! empty( $playlists ) ) : ?>

<h3>Playlists</h3>
<div class="jwp-showcase-playlists">
	<?php foreach ( $playlists as $playlist ) : ?>
	
		<div>
			<a href="<?php echo esc_url( home_url( '/playlist/' . $playlist->feedid ) ); ?>"><?php echo esc_html( $playlist->title ); ?></a>
		</div>
		<div class="jw-player-showcase-playlist-container">
			<ul class="jw-player-showcase-playlist jwp-showcase-carousel">

			<?php foreach ( $playlist->playlist as $video ) : ?>

			<li class="jw-player-showcase-playlist-item jwp-showcase-carousel-seat">
				<a href="<?php echo esc_url( home_url( '/playlist/' . $playlist->feedid . '/video/' . $video->mediaid ) ); ?>">
					<img src="<?php echo esc_url( $video->image ); ?>" alt="<?php echo esc_attr( $video->title ); ?>" />
				</a>
			</li>

			<?php endforeach; ?>

			</ul>
		</div>

	<?php endforeach; ?>
</div>

<?php endif; ?>

<?php get_footer();
