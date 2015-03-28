<?php
/**
 * Page JS for Memorabella
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/**
 * Wyatt meade
 *
 * Adds JS only to pages that need it.
 */
function page_specific_js() {

	/* Global Scripts */
	wp_register_script( 'scripts_js', get_template_directory_uri().'/js/scripts.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'scripts_js' );

}

add_action( 'wp_enqueue_scripts', 'page_specific_js' );