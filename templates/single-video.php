<?php get_header(); ?>

<?php

$config 	= JW_Showcase\get_config();
$video_id 	= JW_Showcase\get_video_id();
$video 		= JW_Showcase\get_video( $video_id );

$paylist_id = JW_Showcase\get_playlist_id();
$playlist 	= JW_Showcase\get_playlist( $paylist_id );

?>

<h1><?php echo esc_html( $config->siteName ); ?></h1>
<p><?php echo esc_html( $config->description ); ?></p>

<h2><?php echo esc_html( $video->title ); ?></h2>
<p><?php echo esc_html( $video->description ); ?></p>

<div class="jw-player-showcase-wrapper">
	<div class="jw-player-showcase jw-player-showcase-single-video" id="jw-player-showcase" data-video="<?php echo esc_attr( wp_json_encode( $video ) ); ?>">
		<img src="<?php echo esc_url( $video->image ); ?>" alt="<?php echo esc_attr( $video->title ); ?>" />
	</div>
</div>

<?php if ( ! empty( $playlist->playlist ) ) : ?>

<div class="jw-player-showcase-wrapper">

	<h3><?php echo esc_html( $playlist->title ); ?></h3>

	<ul class="jw-player-showcase-playlist">

	<?php foreach ( $playlist->playlist as $video ) : ?>

		<li class="jw-player-showcase-playlist-item">
			<a href="<?php echo esc_url( home_url( '/playlist/' . $paylist_id . '/video/' . $video->mediaid ) ); ?>">
				<img src="<?php echo esc_url( $video->image ); ?>" alt="<?php echo esc_attr( $video->title ); ?>" />
			</a>
		</li>

	<?php endforeach; ?>

	</ul>

</div>

<?php endif; ?>

<?php get_footer();
