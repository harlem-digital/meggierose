<?php
	$settings = prose_get_settings();
	$foundation_settings = foundation_get_settings();
	$prose_show_category_collections = $settings->prose_show_category_collections;
	$prose_max_cats = $settings->prose_tab_bar_max_cats;

	if ( is_rtl() ) {
		$pushit = 'pushit-left';
	} else {
		$pushit = 'pushit-right';
	}
?>

<!-- PushIt Menu -->
<nav class="pushit <?php echo $pushit; ?>">
	<div id="menu" class="wptouch-menu">
		<ul class="tab-menu clearfix">
			<?php if ( $prose_show_category_collections ) { ?>
			<li><a href="#" class="no-ajax" data-section="collections"><?php _e( 'Collections', 'wptouch-pro' ); ?></a></li>
			<?php } ?>
			<li><a href="#" class="no-ajax" data-section="menu"><?php _e( 'Menu', 'wptouch-pro' ); ?></a></li>
			<li><a href="#" class="no-ajax" data-section="options"><?php _e( 'Options', 'wptouch-pro' ); ?></a></li>
		</ul>

			<?php if ( $prose_show_category_collections ) { ?>
			<div class="tab-section wptouch-menu collections">
				<?php wptouch_fdn_ordered_cat_list( $prose_max_cats, false ); ?>
			</div>
		<?php } ?>

		<div class="tab-section wptouch-menu menu show-hide-menu" id="site-menu">
			<?php wptouch_show_menu( 'primary_menu' ); ?>
		</div>

		<div class="tab-section wptouch-menu options">
			<?php
			if ( isset ( $_COOKIE[ 'prose-font-size' ] ) ) {
				$font_size = $_COOKIE[ 'prose-font-size' ];
			} else {
				$font_size = 'medium';
			}
			?>
			<h2><?php _e( 'Reading Text Size', 'wptouch-pro' ); ?></h2>
			<ul id="font-size">
				<li<?php if ( $font_size == 'small' ) { echo ' class="active"'; } ?>>
					<a href="#" data-font-size="small" class="no-ajax"><?php _e( 'Small', 'wptouch-pro'); ?></a>
				</li>
				<li<?php if ( $font_size == 'medium' ) { echo ' class="active"'; } ?>>
					<a href="#" data-font-size="medium" class="no-ajax"><?php _e( 'Medium', 'wptouch-pro'); ?></a>
				</li>
				<li<?php if ( $font_size == 'large' ) { echo ' class="active"'; } ?>>
					<a href="#" data-font-size="large" class="no-ajax"><?php _e( 'Large', 'wptouch-pro'); ?></a>
				</li>
			</ul>
			<h3><?php _e( 'Text Sample', 'wptouch-pro' ); ?></h3>
			<p>Vestibulum id ligula porta felis euismod semper. Cras justo odio, dapibus ac facilisis.</p>
		</div>
	</div>
</nav>

<!-- Time left to read indicator -->
<?php if ( is_single() || is_page() ) { ?>
	<div class="progress-indicator meta-font">
	    <div class="progress-count"></div>
	</div>
<?php } ?>

<!-- Back Button for Web-App Mode -->
<div class="wptouch-icon-arrow-left back-button tappable"><!-- css-button --></div>

<div class="page-wrapper">

	<div id="header-area">
		<div id="header-title-logo">
			<?php if ( !is_home() ) { ?>
			<a href="<?php wptouch_bloginfo( 'url' ); ?>" class="home_link tappable">
			<?php } ?>
				<?php if ( foundation_has_logo_image() ) { ?>
					<img id="header-logo" src="<?php foundation_the_logo_image(); ?>" alt="logo image" />
				<?php } else { ?>
					<h1 class="heading-font"><?php wptouch_bloginfo( 'site_title' ); ?></h1>
				<?php } ?>
			<?php if ( !is_home() ) { ?>
			</a>
			<?php } ?>
		</div>

		<a href="#" id="menu-toggle" class="menu-btn tappable no-ajax<?php if ( foundation_has_logo_image() ) { ?> beside-logo<?php } ?>" data-menu-target="menu">
			<i class="wptouch-icon-reorder"></i><!-- Menu Icon -->
		</a>

		<?php
			if ( is_home() && $settings->prose_site_intro ) {
				echo '<p id="site-intro">' . $settings->prose_site_intro . '</p>';
			}
		?>
		<?php if ( !is_single() ) { prose_header_image(); } ?>
	</div>

	<?php /* If this is a category archive */ if ( is_category() ) { ?>
		<h2 class="pagetitle"><span class="meta-font"><?php _e( 'Collection', 'wptouch-pro' ); ?></span> <?php single_cat_title(); ?></h2>
	<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle"><span class="meta-font"><?php _e('Posts Tagged', 'wptouch-pro');?></span> <?php single_tag_title('', false); ?></h2>
	<?php /* If this is a custom post type archive */ } elseif ( is_post_type_archive() ) { ?>
		<h2 class="pagetitle"><?php post_type_archive_title(); ?></h2>
	<?php } ?>

	<?php do_action( 'wptouch_advertising_top' ); ?>
