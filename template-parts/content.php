<?php
/**
 * Template part for displaying posts in a list or a single post
 *
 * @package Minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */
?>

<?php
$post_format = get_post_format() ?: 'standard';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('article-component article-component-' . $post_format); ?>>
<!-- <article id="post-<?php the_ID(); ?>" <?php post_class('article-component') ?>> -->

    <div class="article-component-header">
        <?php if (is_singular()) : ?>
            <header class="page-header">
                <?php minimalista_display_custom_header_image(); ?>
                <?php minimalista_display_post_title('h1', 'page-title', ''); ?>
                <?php minimalista_display_post_metadata_primary(); ?>
            </header>
        <?php else : ?>
            <header class="entry-header">
                <?php minimalista_display_post_title('h2', 'entry-title', 'true'); ?>
                <?php minimalista_display_post_metadata_primary(); ?>
            </header>
        <?php endif; ?>
    </div><!-- .article-component-header -->

    <div class="article-component-content">
        <div class="article-thumbnail">
            <?php if (is_singular()) : ?>
                <?php minimalista_display_post_thumbnail('thumbnail', 'thumbnail alignleft', false); ?>
            <?php else : ?>
                <?php minimalista_display_post_thumbnail('thumbnail', 'thumbnail alignleft', true); ?>
            <?php endif; ?>
        </div>

        <?php
        // Load specific template part based on the post format
        set_query_var('template_part_name', 'format-' . $post_format);
        get_template_part('template-parts/format/format', $post_format);
        minimalista_link_pages();
        ?>
    </div><!-- .article-component-content -->

    <div class="article-component-footer">
        <footer class="entry-footer">
            <?php minimalista_display_post_metadata_secondary(); ?>
        </footer><!-- .article-footer -->
    </div><!-- .article-component-footer -->

</article><!-- #post-<?php the_ID(); ?> -->


<?php
if (is_single()) {

    /* Must be used in The Loop. */
    //minimalista_single_post_navigation();

    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) :
        comments_template();
    endif;

    minimalista_display_related_posts_by_tags(4, 'dps-widget-bs-related-posts', 'div', 'row row-cols-1 row-cols-md-2 g-4');
}
?>