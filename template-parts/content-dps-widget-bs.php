<?php

/**
 * Template part with Display Posts Shortcode for displaying posts in a list.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @link https://displayposts.com/2019/01/04/use-template-parts-to-match-your-themes-styling/
 *
 * @package minimalista
 */

// Verifica se a variável global $dps_listing existe e contém o parâmetro image_size
global $dps_listing;
$image_size = false; // Inicializa como false

// Verifica se $dps_listing é um objeto e se contém a propriedade query_vars
if ( is_object( $dps_listing ) && isset( $dps_listing->query_vars['image_size'] ) ) {
    $image_size = $dps_listing->query_vars['image_size'];
}

?>

<li class="list-group-item">
    <?php
    // Recupera o título do post
    $post_title = get_the_title();

    // Recupera o link permanente do post
    $post_link = get_permalink();

    // Gera o HTML do link com o título do post
    echo '<a href="' . esc_url($post_link) . '">' . esc_html($post_title) . '</a>';

    // Verifica se o post tem uma imagem destacada e se o image_size foi definido
    if ($image_size && has_post_thumbnail()) {
        // Gera o HTML do link com a imagem destacada
        echo '<a href="' . esc_url($post_link) . '">';
        the_post_thumbnail($image_size);
        echo '</a>';
    }
    ?>
</li><!-- ./list-group-item -->
