<?php

add_filter( 'wptouch_setting_defaults_foundation', 'wptouch_related_posts_default_settings' );

function wptouch_related_posts_default_settings( $defaults ) {
	$defaults->enable_related_posts = false;
	$defaults->related_posts_skip_tags = false;

	return $defaults;
}

function wptouch_has_related_posts() {
	$settings = wptouch_get_settings( 'foundation' );
	if ( !$settings->related_posts_enabled ) {
		return false;
	}

	$related = wptouch_related_posts();
	return ( is_array( $related ) && count( $related ) );
}

function wptouch_get_related( $post_id, $taxonomies, $min_matches ) {
	global $wpdb;
	$use_taxonomies = array();
	$max_matches = 0;
	$ids = array();

	foreach( $taxonomies as $tax ) {
		$these_tax = wp_get_post_terms( $post_id, $tax );
		if ( $these_tax ) {
			$use_taxonomies = array_merge( $use_taxonomies, $these_tax );
		}
	}

	foreach( $use_taxonomies as $cat ) {
		$value = 1;

		$results = $wpdb->get_results( $wpdb->prepare( "SELECT object_id FROM " . $wpdb->prefix . "term_relationships WHERE term_taxonomy_id = %d", $cat->term_taxonomy_id ) );
		if ( $results ) {
			$new_ids = array();
			foreach( $results as $result ) {
				$new_ids[] = $result->object_id;
			}

			rsort( $new_ids );
			$new_ids = array_slice( $new_ids, 0, 25 );

			foreach( $new_ids as $id ) {

				$post_info = get_post( $id );
				if ( isset( $post_info->post_status ) && $post_info->post_status == 'publish' ) {
					if ( !isset( $ids[ $id ] ) ) {
						$ids[ $id ] = $value;
					} else {
						$ids[ $id ] += $value;
					}
				}

				if ( isset( $ids[ $id ] ) && $ids[ $id ] > $max_matches && $id != $post_id ) {
					$max_matches = $ids[ $id ];
				}

			}
		}
	}

	if ( $ids ) {
		foreach ( $ids as $id => $count ) {
			if ( $count < $min_matches ) {
				unset( $ids[ $id ] );
			}
		}

		$related_posts = array();
		unset( $ids[ $post_id ] );

		return array( $ids, $max_matches );
	}
}

function wptouch_related_posts() {
	global $post;
	$old_post = $post;
	$settings = wptouch_get_settings( 'foundation' );

	if ( !$settings->related_posts_enabled ) {
		return false;
	}

	global $wpdb;
	if ( is_single() ) {
		$min_matches = 1;
		$tag_matches = false;
		$cat_matches = false;

		$taxonomies = get_taxonomies( array( 'public' => true ) );
		unset( $taxonomies[ 'post_tag' ] ); // post tags are a special case.

		if ( !$settings->related_posts_skip_tags ) {

			$these_tags = wp_get_post_terms( $post->ID, 'post_tag' );

			if ( count( $these_tags ) > 0 && count( $these_tags >= 2 ) ) {
				// The post has two or more tags. Retrieve posts related by tag.
				$tag_matches = wptouch_get_related( $post->ID, array( 'post_tag' ), 2 );
			}

		}

		if ( !$tag_matches || ( is_array( $tag_matches[ 0 ] ) && count( $tag_matches[ 0 ] ) < $settings->related_posts_max ) )  {
			// Not enough tag-based related posts. Pull more using categories & other taxonomies.
			$cat_matches = wptouch_get_related( $post->ID, $taxonomies, 1);
		}
		if ( is_array( $tag_matches[ 0 ] ) && is_array( $cat_matches[ 0 ] ) ) {
			$ids = $tag_matches[ 0 ] + $cat_matches[ 0 ]; // Combine the two result sets.
		} elseif ( is_array( $tag_matches[ 0 ] ) ) {
			$ids = $tag_matches[ 0 ];
		} elseif ( is_array( $cat_matches[ 0 ] ) ) {
			$ids = $cat_matches[ 0 ];
		} else {
			$ids = array();
		}

		arsort( $ids ); // Now attempt to sort by relevance.

		$ids = array_slice( $ids, 0, $settings->related_posts_max, true ); // Trim the sets

		if ( $ids ) {
			foreach( $ids as $post_id => $count ) {
				$post = get_post( $post_id );

				$this_post = new stdClass;
				$this_post->id = $post_id;
				$this_post->link = get_permalink( $post_id );
				$this_post->title = get_the_title();

				if ( function_exists( 'foundation_disable_sharing_links' ) ) { foundation_disable_sharing_links(); }

				if( !empty( $post->post_excerpt ) ) {
					$this_post->excerpt = wp_trim_words( $post->post_excerpt, 20 );
				} else {
					$this_post->excerpt = wp_trim_words( apply_filters( 'the_content', $post->post_content ), 20 );
				}

				if ( function_exists( 'foundation_enable_sharing_links' ) ) { foundation_enable_sharing_links(); }

				if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post_id ) ) {
					$this_post->thumbnail = get_the_post_thumbnail( $post_id, 'wptouch-new-thumbnail' );
				} else {
					$this_post->thumbnail = '';
				}

				$related_posts[] = $this_post;
			}

			$post = $old_post;

			return $related_posts;
		}
	}
}
