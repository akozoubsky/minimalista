<?php
// ==========================
// Imagens
// ==========================

/* @link https://bloggerpilot.com/en/disable-wordpress-image-sizes/ */
function remove_default_image_sizes($sizes)
{
    return array_diff($sizes, ['medium_large']); // Medium Large (768 x 0)
}

function remove_default_large_image_sizes()
{
    remove_image_size('1536x1536'); // 2 x Medium Large (1536 x 1536)
    remove_image_size('2048x2048'); // 2 x Large (2048 x 2048)
}

function modify_default_image_sizes()
{
    // Define o tamanho do thumbnail (miniatura)
    update_option('thumbnail_size_w', 250); // Width
    update_option('thumbnail_size_h', 0); // Height
    update_option('thumbnail_crop', 1); // Enables cropping to maintain aspect ratio

    // Define o tamanho médio (medium)
    update_option('medium_size_w', 768); // Width
    update_option('medium_size_h', 0); // 0 indicates that the height is flexible, preserving the image aspect ratio.

    // Define o tamanho grande (large)
    update_option('large_size_w', 1200); // Width
    update_option('large_size_h', 0); // 0 indicates that the height is flexible, preserving the image aspect ratio.
}

function add_custom_image_sizes()
{

    add_image_size('post-thumbnail', 576, 0, true); // Enables cropping to maintain aspect ratio

    /**
     * Adiciona um novo tamanho de thumbnai personalizado.
     * A altura será ajustada para manter a proporção original da imagem.
     * Evitar o corte e manter a proporção da imagem.
     */
    add_image_size('custom-thumbnail', 992, 0, false); // altura flexível

    /**
     * Add custom image sizes, taking into account the breakpoints of the Bootstrap grid system
     * 
     */

    // aspect ratio 16:9 - w x 0,5625 = h
    add_image_size('landscape-small', '576', '324', true);
    add_image_size('landscape-medium', '768', '432', true);
    add_image_size('landscape-large', '992', '558', true);
    add_image_size('landscape-x-large', '1200', '675', true);
    add_image_size('landscape-xx-large', '1400', '788', true);

    // aspect ratio 4:5 - w x 1,25 = h
    add_image_size('fashion-small', '576', '720', false);
    add_image_size('fashion-medium', '768', '960', false);
    add_image_size('fashion-large', '992', '1240', false);

    // aspect ratio 3:4 - w x 1,333333333333333 = h
    add_image_size('portrait-small', '576', '768', false);
    add_image_size('portrait-medium', '768', '1024', false);
    add_image_size('portrait-large', '992', '1323', false);

    // aspect ratio 1:1 - w x 1 = h
    add_image_size('square-small', '576', '576', true);
    add_image_size('square-medium', '768', '768', true);
    add_image_size('square-large', '992', '992', true);

    // aspect ratio 3:4 - x 1,333333333333333 = h
    add_image_size('profile-icon-rectangular-small', '90', '120', false);
    add_image_size('profile-icon-rectangular-medium', '150', '200', false);
    add_image_size('profile-icon-rectangular-large', '225', '300', false);

    // aspect ratio 1:1 - w x 1 = h
    add_image_size('profile-icon-square-small', '90', '90', false);
    add_image_size('profile-icon-square-medium', '150', '150', false);
    add_image_size('profile-icon-square-large', '225', '225', false);

    // Cabeçalho largo e curto
	// aspect ratio 10:1
    add_image_size('header-wide-short', 1920, 192, true);
    // Cabeçalho largo e alto
	// aspect ratio 20:10
    add_image_size('header-wide-tall', 1920, 384, true);
}

// ==========================
// Imagens
// ==========================
add_filter('intermediate_image_sizes', 'remove_default_image_sizes');
add_action('init', 'remove_default_large_image_sizes');
add_action('after_setup_theme', 'modify_default_image_sizes');
add_action('after_setup_theme', 'add_custom_image_sizes');

function minimalista_calculate_image_sizes_attr($sizes, $size)
{
    $width = $size[0];

    // Define breakpoints do Bootstrap 5 e tamanhos de imagem personalizados
    if ($width <= 576) {
        $sizes = '(max-width: 576px) 100vw, 576px';
    } elseif ($width > 576 && $width <= 768) {
        $sizes = '(max-width: 768px) 100vw, 768px';
    } elseif ($width > 768 && $width <= 992) {
        $sizes = '(max-width: 992px) 100vw, 992px';
    } elseif ($width > 992 && $width <= 1200) {
        $sizes = '(max-width: 1200px) 100vw, 1200px';
    } elseif ($width > 1200 && $width <= 1400) {
        // breakpoint para 'landscape-xx-large'
        $sizes = '(max-width: 1400px) 100vw, 1400px'; 
    } elseif ($width > 1400 && $width <= 1920) {
            $sizes = '(max-width: 1920px) 100vw, 1920px';
    } else {
        $sizes = '100vw';
    }

    return $sizes;
}
add_filter('wp_calculate_image_sizes', 'minimalista_calculate_image_sizes_attr', 10, 2);
