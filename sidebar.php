<?php
/**
 * The sidebar containing the main widget area
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<div class="col-lg-4">
    <aside id="secondary" class="sidebar-right">
        <div class="sidebar widget-area ps-lg-3">
            <?php dynamic_sidebar('sidebar-1'); ?>
        </div>

    </aside><!-- #secondary -->
</div><!-- ./col -->