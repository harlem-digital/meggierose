<?php

define( 'PROSE_THEME_VERSION', '1.4.5' );
define( 'PROSE_SETTING_DOMAIN', 'prose' );

define( 'PROSE_DIR', wptouch_get_bloginfo( 'theme_root_directory' ) );
define( 'PROSE_URL', wptouch_get_bloginfo( 'theme_root_url' ) );

// Prose actions
add_action( 'foundation_init', 'prose_theme_init' );
add_action( 'foundation_modules_loaded', 'prose_register_fonts' );
add_action( 'admin_enqueue_scripts', 'prose_enqueue_admin_scripts' );

// Prose filters
add_filter( 'wptouch_registered_setting_domains', 'prose_setting_domain' );
add_filter( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'prose_theme_settings' );
add_filter( 'wptouch_setting_defaults', 'prose_setting_defaults' );
add_filter( 'wptouch_setting_defaults_foundation', 'prose_foundation_setting_defaults' );
add_filter( 'foundation_settings_blog', 'prose_blog_settings' );
add_filter( 'wptouch_body_classes', 'prose_body_classes' );

function prose_setting_domain( $domain ) {
	$domain[] = PROSE_SETTING_DOMAIN;
	return $domain;
}

function prose_get_settings() {
	return wptouch_get_settings( PROSE_SETTING_DOMAIN );
}

function prose_setting_defaults( $settings ) {

	// Prose menu default
	$settings->primary_menu = 'wp';

	// Prose theme colors
	$settings->prose_background_color = '#fcfcf7';
	$settings->prose_branding_color = '#53c8ea';
	$settings->header_image = false;
	$settings->prose_site_intro = false;
	$settings->prose_link_color = '#cb4e4e';
	$settings->prose_show_featured_image_in_header = true;

	$settings->prose_show_comments = false;

	$settings->prose_show_category_collections = true;
	$settings->prose_animate_featured_image = true;

	$settings->prose_tab_bar_max_cats = 15;

	$settings->prose_use_dropcaps = true;

	return $settings;
}

function prose_foundation_setting_defaults( $settings ) {
	$settings->typography_sets = 'domine_karla';
	return $settings;
}

function prose_blog_settings( $blog_settings ) {
	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'prose_show_comments',
		__( 'Show comments when reading posts', 'wptouch-pro' ),
		__( 'Comment form will not appear for posts with comments turned off.' ),
		WPTOUCH_SETTING_BASIC,
		'1.0',
		'',
		PROSE_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'prose_show_category_collections',
		__( 'Show categories as "collections" in menu', 'wptouch-pro' ),
		'',
		WPTOUCH_SETTING_BASIC,
		'1.0',
		'',
		PROSE_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'prose_show_featured_image_in_header',
		__( 'Use featured image as the header background when viewing single posts', 'wptouch-pro' ),
		'',
		WPTOUCH_SETTING_BASIC,
		'1.0',
		'',
		PROSE_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'list',
		'prose_tab_bar_max_cats',
		__( 'Maximum number of items in categories/collections', 'wptouch-pro' ),
		'',
		WPTOUCH_SETTING_BASIC,
		'1.0',
		array(
			'5' => '5',
			'10' => '10',
			'25' => '25',
			'50' => '50'
		),
		PROSE_SETTING_DOMAIN
	);

	return $blog_settings;
}

function prose_theme_settings( $page_options ) {
	wptouch_add_page_section(
		FOUNDATION_PAGE_BRANDING,
		__( 'Header Image', 'wptouch-pro' ),
		'header-image',
		array(
			wptouch_add_setting(
				'image-upload',
				'header_image',
				__( 'Displayed behind header on pages and posts without featured images.', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0'
			)
		),

		$page_options,
		PROSE_SETTING_DOMAIN
	);

	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Site Intro', 'wptouch-pro' ),
		'site-intro',
		array(
			wptouch_add_setting(
				'text',
				'prose_site_intro',
				__( 'Displayed on post listing page below site name.', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0'
			)
		),

		$page_options,
		PROSE_SETTING_DOMAIN
	);

	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Drop Caps', 'wptouch-pro' ),
		'drop-caps',
		array(
			wptouch_add_setting(
				'checkbox',
				'prose_use_dropcaps',
				__( 'Use a drop cap at the start of each blog post.', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0'
			)
		),

		$page_options,
		PROSE_SETTING_DOMAIN
	);

	return $page_options;
}


function prose_theme_init() {

	// Foundation modules this theme should load
	foundation_add_theme_support(
		array(
			// Modules w/ settings
			'advertising',
			'webapp',
			'google-fonts',
			'load-more',
			'sharing',
			'related-posts',
			'custom-posts',
			'custom-latest-posts',
			'media',
			'menu',
			'pushit',
			'wptouch-icons',
			'fastclick',
			'tappable',
			'spinjs',
			'concat'
		)
	);

	// Register theme menu
	wptouch_register_theme_menu(
		array(
			'name' => 'primary_menu',	// this is the name of the setting
			'friendly_name' => __( 'Primary Menu', 'wptouch-pro' ),	// the friendly name, shows as a section heading
			'settings_domain' => PROSE_SETTING_DOMAIN,	// the setting domain (should be the same for the whole theme)
			'description' => __( 'Choose a menu', 'wptouch-pro' ),	 	// the description
			'tooltip' => __( 'Off-canvas menu for Prose', 'wptouch-pro' ), // Extra help info about this menu, perhaps?
			'can_be_disabled' => false
		)
	);

	// Register theme colors
	// (Name, element to add color to, element to add background-color to, settings domain)
	foundation_register_theme_color( 'prose_background_color', __( 'Theme background', 'wptouch-pro' ), '', '.page-wrapper, .wptouch-login-wrap, .progress-indicator', PROSE_SETTING_DOMAIN );
	foundation_register_theme_color( 'prose_branding_color', __( 'Branding', 'wptouch-pro' ), '#header-area h1, #header-area a.home-link, a#menu-toggle, .load-more-link, #site-intro, .light-branding.dark-body.single.use-dropcap #content .post-content > p:first-of-type:first-letter, .dark-branding.light-body.single.use-dropcap #content .post-content > p:first-of-type:first-letter', 'body, #menu, .fixed-header-fill, .header-image, .progress-indicator.share', PROSE_SETTING_DOMAIN );
	foundation_register_theme_color( 'prose_link_color', __( 'Links', 'wptouch-pro' ), 'a', '.dots li.active', PROSE_SETTING_DOMAIN );
}

// Register Google font pairings
// (Apply to (Headings or Body), Google font Pretty Name, kerning, weights)
function prose_register_fonts() {
	if ( foundation_is_theme_using_module( 'google-fonts' ) ) {
		foundation_register_google_font_pairing(
			'domine_karla',
			foundation_create_google_font( 'body', 'Domine', 'serif', array( '400', '700', '400italic', '700italic' ) ),
			foundation_create_google_font( 'meta', 'Karla', 'sans-serif', array( '400', '700' ) ),
			foundation_create_google_font( 'heading', 'Domine', 'serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'podkova_badscript',
			foundation_create_google_font( 'body', 'Podkova', 'serif', array( '400', '700' ) ),
			foundation_create_google_font( 'meta', 'Bad Script', 'sans-serif', array( '400' ) ),
			foundation_create_google_font( 'heading', 'Podkova', 'serif', array( '400', '700' ) )
		);
		foundation_register_google_font_pairing(
			'source_alegreya',
			foundation_create_google_font( 'body', 'Source Sans Pro', 'sans-serif', array( '400', '700', '400italic', '700italic' ) ),
			foundation_create_google_font( 'meta', 'Alegreya Sans SC', 'sans-serif', array( '400', '400italic' ) ),
			foundation_create_google_font( 'heading', 'Source Sans Pro', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'allerta_alike',
			foundation_create_google_font( 'heading', 'Allerta Stencil', 'sans-serif', array( '400', '400italic' ) ),
			foundation_create_google_font( 'body', 'Actor', 'sans-serif', array( '400', '700', '400italic', '700italic' ) ),
			foundation_create_google_font( 'meta', 'Actor', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'bevan_copse',
			foundation_create_google_font( 'heading', 'Bevan', 'sans-serif', array( '400' ) ),
			foundation_create_google_font( 'body', 'Copse', 'sans-serif', array( '400', '700', '400italic', '700italic' ) ),
			foundation_create_google_font( 'meta', 'Copse', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'ek_mukta_patrick',
			foundation_create_google_font( 'heading', 'Patrick Hand SC', 'sans-serif', array( '400' ) ),
			foundation_create_google_font( 'body', 'Ek Mukta', 'sans-serif', array( '300', '400', '700', '300italic', '400italic', '700italic' ) ),
			foundation_create_google_font( 'meta', 'Ek Mukta', 'sans-serif', array( '300', '400', '700', '300italic', '400italic', '700italic' ) )
		);
	}
}

function prose_enqueue_admin_scripts() {
	wp_enqueue_script(
		'prose-admin-js',
		PROSE_URL . '/admin/prose-admin.js',
		array( 'jquery', 'wptouch-pro-admin' ),
		PROSE_THEME_VERSION,
		false
	);
}

function prose_body_classes( $classes ) {
	$settings = prose_get_settings();


	if ( $settings->prose_use_dropcaps ) {
		$classes[]= 'use-dropcap';
	}

  	$branding_luma = wptouch_hex_to_luma( $settings->prose_branding_color );

	if ( $branding_luma <= 180 || $settings->prose_branding_color == '#53c8ea' ) {
		$classes[] = 'dark-branding';
	} else {
		$classes[] = 'light-branding';
	}

	$body_luma = wptouch_hex_to_luma( $settings->prose_background_color );

	if ( $body_luma <= 180 ) {
		$classes[] = 'dark-body';
	} else {
		$classes[] = 'light-body';
	}

	if ( isset ( $_COOKIE[ 'prose-font-size' ] ) ) {
		if ( $_COOKIE[ 'prose-font-size' ] != 'medium' ) {
			$classes[] = 'font-size-' . $_COOKIE[ 'prose-font-size' ];
		}
	}

  return $classes;
}


function prose_get_excerpt( $post ) {
	if ( is_numeric( $post ) ) {
		//Gets post ID
		$post = get_post( $post );
	} elseif ( !is_object( $post ) ) {
		return false;
	}

	$the_excerpt = $post->post_content; //Gets post_content to be used as a basis for the excerpt
	$the_excerpt = wp_trim_words( $the_excerpt, 20 );

	$the_excerpt = '<p>' . $the_excerpt . '</p>';

	return $the_excerpt;
}
