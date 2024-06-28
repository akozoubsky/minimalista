<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package minimalista
 */

/* Verifica se a área de widget sidebar-1 está ativa. Se não houver widgets ativos na área de widget sidebar-1, o código retorna e não renderiza a barra lateral. */
if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside id="secondary" class="widget-area col-lg-4 mt-5 mt-lg-3 ps-lg-5">
    <div class="">
        <div class="">
            <?php dynamic_sidebar( 'sidebar-1' ); ?>
        </div>
    </div>
</aside><!-- #secondary -->

