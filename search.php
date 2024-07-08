<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package minimalista
 */

 // Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();
?>

<div class="container">

    <div class="row">

        <div class="col-lg-8">

            <main id="primary" class="site-main">

                <?php
                // Get the search query and sanitize it
                $search_query = get_search_query();
                $search_query = sanitize_text_field($search_query);

                // Check if the search query has at least 3 characters
                if ( strlen( $search_query ) >= 3 ) :

                    if ( have_posts() ) : ?>

                        <header class="page-header">
                            <h1 class="page-title">
                                <?php
                                /* translators: %s: search query. */
                                printf( esc_html__( 'Search Results for: %s', 'minimalista' ), '<span>' . esc_html( $search_query ) . '</span>' );
                                ?>
                            </h1>
                        </header><!-- .page-header -->

                        <?php
                        /* Start the Loop */
                        while ( have_posts() ) :
                            the_post();

                            /**
                             * Run the loop for the search to output the results.
                             * If you want to overload this in a child theme then include a file
                             * called content-search.php and that will be used instead.
                             */
                            get_template_part( 'template-parts/content', 'search' );

                        endwhile;

                        minimalista_the_post_navigation();

                    else :

                        get_template_part( 'template-parts/content', 'none' );

                    endif;

                else : ?>

                    <header class="page-header">
                        <h1 class="page-title">
                            <?php esc_html_e( 'Search Results', 'minimalista' ); ?>
                        </h1>
                    </header><!-- .page-header -->

                    <div class="no-results">
                        <p><?php esc_html_e( 'Please enter at least 3 characters for your search.', 'minimalista' ); ?></p>
                    </div>

                <?php endif; ?>

            </main><!-- #main -->
    
        </div><!-- .col-lg-8 -->

        <!-- Right Sidebar Column -->
        <?php get_sidebar(); // Include the sidebar.php file ?>

    </div><!-- .row -->
    
</div>

<?php
get_footer();
?>

