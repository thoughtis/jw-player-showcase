<?php get_header(); ?>

<?php

$config 	= JW_Showcase\get_config();
$paylist_id = JW_Showcase\get_playlist_id();
$playlist 	= JW_Showcase\get_playlist( $paylist_id );

?>

<h1><?php echo esc_html( $config->siteName ); ?></h1>
<p><?php echo esc_html( $config->description ); ?></p>

<h2><?php echo esc_html( $playlist->title ); ?></h2>
<p><?php echo esc_html( $playlist->description ); ?></p>

<?php if ( ! empty( $playlist->playlist ) ) : ?>

<?php $video = $playlist->playlist[0]; ?>

<div class="jw-player-showcase-wrapper">
	<div class="jw-player-showcase" id="jw-player-showcase" data-video="<?php echo esc_attr( wp_json_encode( $video ) ); ?>">
		<img src="<?php echo esc_url( $video->image ); ?>" alt="<?php echo esc_attr( $video->title ); ?>" />
	</div>
</div>

<?php endif; ?>

<?php if ( 1 < count( $playlist->playlist ) ) : ?>

<div class="jw-player-showcase-wrapper">

	<h3>All Videos</h3>

	<ul class="jw-player-showcase-playlist">

	<?php foreach ( $playlist->playlist as $video ) : ?>

		<li class="jw-player-showcase-playlist-item">
			<a href="<?php echo esc_url( home_url( '/video/' . $video->mediaid ) ); ?>">
				<img src="<?php echo esc_url( $video->image ); ?>" alt="<?php echo esc_attr( $video->title ); ?>" />
			</a>
		</li>

	<?php endforeach; ?>

	</ul>

</div>

<?php endif; ?>

<?php get_footer();
