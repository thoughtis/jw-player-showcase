<?php get_header(); ?>

<?php

$config 	= JW_Showcase\get_config();
$video_id 	= JW_Showcase\get_video_id();
$video 		= JW_Showcase\get_video( $video_id );

?>

<h1><?php echo esc_html( $config->siteName ); ?></h1>
<p><?php echo esc_html( $config->description ); ?></p>

<h2><?php echo esc_html( $video->title ); ?></h2>
<p><?php echo esc_html( $video->description ); ?></p>

<img src="<?php echo esc_url( $video->image ); ?>" alt="<?php echo esc_attr( $video->title ); ?>" />

<?php get_footer();
