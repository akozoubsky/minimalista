<?php
/**
 * Template part for displaying aside post format. 
 * 
 * Typically short and styled without a title. Similar to a Facebook note update.
 *
 * @package Minimalista
 * @version 1.0.0
 * @author Alexandre Kozoubsky
 */
?>

<div class="format-aside-content">
    <div class="format-icon">
        <i class="fa-solid fa-pen-to-square"></i>
    </div>
    <?php
    if (is_singular()) {
        minimalista_display_post_content();
    } else {
        minimalista_display_post_excerpt();
    }
    ?>
</div>