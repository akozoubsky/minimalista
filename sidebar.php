<?php

/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package minimalista
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/* Verifica se a área de widget sidebar-1 está ativa. Se não houver widgets ativos na área de widget sidebar-1, o código retorna e não renderiza a barra lateral. */
if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>
<!-- <aside id="secondary" class="widget-area col-lg-4 mt-5 mt-lg-0 ps-lg-5"> -->
<aside id="secondary" class="widget-area col-lg-4 mt-5 mt-lg-0 ps-lg-5">
    <div class="no-collapse-3">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </div>

</aside><!-- #secondary -->