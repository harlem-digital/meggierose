<!doctype html>  

	
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php global $page, $paged; bloginfo( 'name' ); wp_title( '-', true, 'left' ); ?></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.js"></script>
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php wp_head(); ?>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	
	<style type="text/css">
	.grid-post img{ margin-bottom: 20px;}
	</style>
	
	<!-- Site by Mirza Rahman - mirzar.com -->
	
</head>
	
<body>


	
	
<div id="grid-wrapper">
	
	<div id="header">
		<h1 id="logo"><a href="<?php echo home_url(); ?>"><img src="<?php bloginfo('template_url'); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>" /></a></h1>
		<a href="mailto:meggieroserepp@gmail.com" id="contact" class="notext">Contact</a>
		<div class="clear"></div>
	</div>
				
				