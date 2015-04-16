<?php

add_action( 'foundation_enqueue_scripts', 'prose_enqueue_scripts' );

function prose_enqueue_scripts() {

	// Loading swipe.js directly from the foundation module, we only want the js, no settings
	wp_enqueue_script(
		'foundation_swipe',
		foundation_get_base_module_url() . '/featured/swipe.js',
		false,
		PROSE_THEME_VERSION,
		true
	);

	wp_enqueue_script(
		'prose-library',
		PROSE_URL . '/default/js/library.js',
		array( 'jquery' ),
		PROSE_THEME_VERSION,
		true
	);

	wp_register_script(
		'prose-js',
		PROSE_URL . '/default/prose.js',
		array( 'prose-library' ),
		PROSE_THEME_VERSION,
		true
	);

	$translation_array = array(
		'load_more_label' => __( 'Load more from this collection', 'wptouch-pro' ),
		'reading_time_minute' => __( '%d minute', 'wptouch-pro' ),
		'reading_time_minutes' => __( '%d minutes', 'wptouch-pro' ),
		'remaining_minute' => __( '%d minute remaining', 'wptouch-pro' ),
		'remaining_minutes' => __( '%d minutes remaining', 'wptouch-pro' ),
		'share_post' => __( 'Share post', 'wptouch-pro' )
	);
	wp_localize_script( 'prose-js', 'translated_strings', $translation_array );
	wp_enqueue_script( 'prose-js' );
}

function prose_has_custom_content() {
	$prose_settings = prose_get_settings();

	if ( $prose_settings->homepage_message ) {
		return true;
	} else {
		return false;
	}
}

function prose_the_author() {
	$author = get_the_author();
	if ( $author != 'admin' ) {
		echo '<span class="post-author"><span> |</span> ' . $author . '</span>';
	}
}

function prose_header_image( $post_id = false) {
	$header_image = false;
	$animate = true;
	$animate_class = false;
	$prose_settings = prose_get_settings();

	if ( $post_id && $prose_settings->prose_show_featured_image_in_header ) {
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' );
		$header_image = $thumbnail[0];
		$animate = $prose_settings->prose_animate_featured_image;
	}

	if ( $animate ) {
		$animate_class = ' animate';
	}

	if ( !$header_image ) {
		$header_image = WPTOUCH_BASE_CONTENT_URL . $prose_settings->header_image;
	}

	if ( $header_image && $header_image != WPTOUCH_BASE_CONTENT_URL ) {
		echo '<div class="header-image' . $animate_class . '" style="background-image: url(\'' . $header_image . '\');"></div>';
	}
}