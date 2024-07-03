<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package minimalista
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function minimalista_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'minimalista_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function minimalista_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'minimalista_pingback_header' );

/**
 * Determine the format of the featured image.
 * 
 * @param int $post_id Post ID.
 * @return string Format of the image (square, landscape, portrait).
 */
function get_featured_image_format($post_id = null)
{
    // Se $post_id for fornecido, ele é convertido para inteiro usando intval(). Se não for fornecido, get_the_ID() é usado para obter o ID do post atual
    $post_id = $post_id ? intval($post_id) : get_the_ID();

    $image_id = get_post_thumbnail_id($post_id);
    if (!$image_id) {
        return ''; // No featured image.
    }

    $image_data = wp_get_attachment_metadata($image_id);
    if (!$image_data) {
        return ''; // No image data found.
    }

    $width = $image_data['width'];
    $height = $image_data['height'];

    if ($width > $height) {
        return 'landscape';
    } elseif ($width < $height) {
        return 'portrait';
    } else {
        return 'square';
    }
}
