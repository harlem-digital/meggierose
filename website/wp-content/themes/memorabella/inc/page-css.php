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
    if ( is_page( 'about' ) ) {
        wp_register_style( 'about_css', get_template_directory_uri().'/css/pages/about.css' );
        wp_enqueue_style( 'about_css' );
    }
    if ( is_404() ) {
        wp_register_style( '404_css', get_template_directory_uri().'/css/pages/404.css' );
        wp_enqueue_style( '404_css' );
    }
    if ( is_search() ) {
        wp_register_style( 'search_css', get_template_directory_uri().'/css/pages/search.css' );
        wp_enqueue_style( 'search_css' );
    }
    if ( is_front_page() ) {
        wp_register_style( 'blog_css', get_template_directory_uri().'/css/pages/blog.css' );
        wp_enqueue_style( 'blog_css' );
    }

}
add_action( 'wp_enqueue_scripts', 'page_specific_css' );