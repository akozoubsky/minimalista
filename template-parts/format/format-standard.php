<?php
/**
 * Template part for displaying standard post format. 
 * 
 * @package Minimalista
 * @version 1.0.0
 * @author Alexandre Kozoubsky
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */
?>

<div class="format-standard-content">
    <?php
    if (is_singular()) {
        minimalista_display_post_content();
    } else {
        minimalista_display_post_excerpt();
    }
    ?>
</div>