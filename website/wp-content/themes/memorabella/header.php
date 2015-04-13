<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script>(function(){document.documentElement.className='js'})();</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<header id="header" class="header">
		<div class="navigation">
			<nav class="social-nav">
				<?php
				$socialmenu = array(
					'theme_location'  => '',
					'menu'            => 'Social Menu',
					'container'       => '',
					'container_class' => '',
					'container_id'    => '',
					'menu_class'      => 'menu large_15 col centered',
					'menu_id'         => '',
					'echo'            => true,
					'fallback_cb'     => 'wp_page_menu',
					'before'          => '',
					'after'           => '',
					'link_before'     => '',
					'link_after'      => '',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'depth'           => 0,
					'walker'          => ''
				);

				wp_nav_menu( $socialmenu );
				?>
				<form id="form" action="/memorabella/" method="get">
					<label class="form-label">
						<input class="form-search" type="text" name="s" id="search" value="<?php the_search_query(); ?>" placeholder="What Can I Help You Find?" />
						<input class="form-icon form-search-icon" type="submit" value="" />
						<a href="#" class="form-icon form-cancel-icon"></a>
					</label>
				</form>
			</nav>
			<nav class="main-nav">
				<?php
				$mainmenu = array(
					'theme_location'  => '',
					'menu'            => 'Main Menu',
					'container'       => '',
					'container_class' => '',
					'container_id'    => '',
					'menu_class'      => 'menu large_15 col centered',
					'menu_id'         => '',
					'echo'            => true,
					'fallback_cb'     => 'wp_page_menu',
					'before'          => '',
					'after'           => '',
					'link_before'     => '',
					'link_after'      => '',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'depth'           => 0,
					'walker'          => ''
				);

				wp_nav_menu( $mainmenu );

				?>
			</nav>
			<a href="#" class="menu-button">Menu</a>
		</div>
		<h1><a href="/">Memorabella</a></h1>
	</header>

	<main class="main centered">