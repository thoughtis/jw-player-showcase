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

<h3>Videos</h3>

<ul>

<?php foreach ( $playlist->playlist as $video ) : ?>

	<li>
		<a href="<?php echo esc_url( home_url( '/video/' . $video->mediaid ) ); ?>">
			<img src="<?php echo esc_url( $video->image ); ?>" alt="<?php echo esc_attr( $video->title ); ?>" />
		</a>
	</li>

<?php endforeach; ?>

</ul>

<?php endif; ?>

<?php get_footer();
