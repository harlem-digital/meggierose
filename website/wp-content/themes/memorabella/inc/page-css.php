<?php
/**
 * Page CSS for Memorabella
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/**
 * Wyatt Meade
 *
 * Creates a list of CSS files to only display on the pages needed.
 */
function page_specific_css() {
    
    /*if ( is_page( 'home' ) ) {
        wp_register_style( 'home_css', get_template_directory_uri().'/css/pages/home.css' );
        wp_enqueue_style( 'home_css' );
    }*/

}
add_action( 'wp_enqueue_scripts', 'page_specific_css' );