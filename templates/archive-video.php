<?php get_header(); ?>

<?php

$config 	= JW_Showcase\get_config();
$playlists 	= JW_Showcase\get_playlists();

?>

<h1><?php echo esc_html( $config->siteName ); ?></h1>
<h2><?php echo esc_html( $config->description ); ?></h2>

<?php if ( ! empty( $playlists ) ) : ?>

<h3>Playlists</h3>
<ul>
<?php foreach ( $playlists as $playlist ) : ?>

	<li><a href="<?php echo esc_url( home_url( '/playlist/' . $playlist->feedid ) ); ?>"><?php echo esc_html( $playlist->title ); ?></a></li>

<?php endforeach; ?>
</ul>

<?php endif; ?>

<?php get_footer();
