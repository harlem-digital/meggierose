<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<title><?php wp_title( ' | ', true, 'right' ); ?></title>
		<?php wptouch_head(); ?>
	</head>
	
	<body <?php body_class( wptouch_get_body_classes() ); ?>>
		
		<?php do_action( 'wptouch_preview' ); ?>
		
		<?php do_action( 'wptouch_body_top' ); ?>
		
		<?php get_template_part( 'header-bottom' ); ?>

		<?php do_action( 'wptouch_body_top_second' ); ?>
