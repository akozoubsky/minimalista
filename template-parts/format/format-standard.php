<!-- content-standard.php -->

<!-- Início do Post no formato padrão -->
<div class="format-standard">
    <!-- Conteúdo do Post -->
    <?php
    if ( is_singular() ) {
        minimalista_display_post_content();
    } else {
        minimalista_display_post_excerpt();
    }
    ?>
</div>
<!-- Fim do Post no formato padrão -->