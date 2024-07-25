<?php
/**
 * minimalista Shorcodes
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 */

/**
 * Shortcode to display the latest [N] posts.
 *
 * Usage: [latest_posts_bs_card number="5" image_size="fashion-small"]
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output of the latest posts.
 */

function latest_posts_bs_card_shortcode($atts)
{
    // Extract shortcode attributes, set default number of posts to 5 and default image size
    $atts = shortcode_atts(array(
        'number' => 6,
        'image_size' => 'thumbnail' // Sets a default size for the image if not specified
    ), $atts, 'latest_posts');

    // Set up query arguments to fetch the latest posts
    $args = array(
        'posts_per_page' => intval($atts['number']),  // Ensure the number is an integer
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1
    );

    // Query the latest posts
    $recent_posts = new WP_Query($args);

    // Start output buffering to capture HTML output
    ob_start();

    // Check if there are posts to display
    if ($recent_posts->have_posts()) : ?>

        <section class="latest_posts_bs_shortcode" itemscope itemtype="http://schema.org/Blog">

            <div class="card-group">

                <div class="row">

                    <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class('blog-posting col-12 col-sm-12 col-lg-12'); ?> itemscope itemtype="http://schema.org/BlogPosting" aria-label="Leia mais sobre <?php the_title(); ?>">

                            <div class="card">

                                <div class="row g-0">

                                    <?php if (has_post_thumbnail()) : // Verifica se há uma imagem destacada. 
                                    ?>
                                        <div class="col-4">
                                            <?php minimalista_display_post_thumbnail($atts['image_size'], "thumbnail img-fluid alignleft m-0", true); ?>
                                        </div><!-- ./col-4 -->
                                    <?php endif; ?>

                                    <div class="<?php echo has_post_thumbnail() ? 'col-8' : 'col-12'; ?>">
                                        <div class="card-body">
                                            <a class="" href="<?php the_permalink(); ?>" rel="related">
                                                <h4 class="card-title" itemprop="headline"><?php the_title(); ?></h4>
                                                <time class="card-text" itemprop="datePublished" datetime="<?php echo get_the_date('c'); ?>">
                                                    <small><span class="text-muted"><?php echo get_the_date(); ?></span></small>
                                                </time>
                                            </a>
                                        </div>
                                    </div><!-- ./col-8 -->

                                </div><!-- ./row -->

                            </div><!-- ./card -->

                        </article>

                        <article id="post-<?php the_ID(); ?>" <?php post_class("blog-posting"); ?> itemscope itemtype="http://schema.org/BlogPosting">

                            <header class="entry-header">
                                <?php minimalista_display_post_title('h2', 'entry-title', true); ?>
                                <?php minimalista_display_post_metadata_primary(); ?>
                            </header>

                            <?php if (has_post_thumbnail()) :
                            ?>
                                <div class="">
                                    <?php minimalista_display_post_thumbnail($atts['image_size'], "thumbnail img-fluid", true); ?>
                                </div>
                            <?php endif; ?>

                            <div class="d-flex flex-md-row flex-column">
                                <div class="me-md-4 <?php echo has_post_thumbnail() ? '' : 'ms-md-0'; ?>">
                                    <?php minimalista_display_post_excerpt(); ?>
                                </div>
                            </div><!--./d.flex-->

                        </article><!-- /.blog-post -->

                        <hr>

                    <?php endwhile; ?>

                </div><!-- ./row -->

            </div><!--./card-group-->

        </section>

<?php
        // Reset post data after custom query
        wp_reset_postdata();
    else :  // If no posts are found, display a message
        echo '<p>No recent posts found.</p>';
    endif;

    // Get the buffered content and end output buffering
    $output = ob_get_clean();

    return $output;
}
add_shortcode('latest_posts_bs_card', 'latest_posts_bs_card_shortcode');

/**
 * Shortcode to display a list of posts with variable design based on the featured image format.
 * This shortcode supports various attributes to customize the output, including the number of posts,
 * the image size, additional classes for the <article> tag, the post status, HTML to be used before
 * and after each article, and optional display of a link to the blog page with customizable text.
 * 
 * @param array $atts {
 *     Optional. Array of shortcode attributes.
 *
 *     @type int    $posts_per_page Number of posts to display. Default 6.
 *     @type string $image_size     Size of the featured image. Default 'full'.
 *     @type string $image_classes  Additional classes for the <img> tag. Default 'thumbnail img-fluid'.
 *     @type string $title_tag      The HTML tag to use for the title. Default 'h4'.
 *     @type string $article_class  Additional class for the <article> tag. Default 'blog-posting'.
 *     @type string $post_status    Status of the posts to display. Default 'publish'.
 *     @type string $html_before    HTML content before each <article>. Default empty.
 *     @type string $html_after     HTML content after each <article>. Default empty.
 *     @type string $section_class  Additional class for the <section> tag. Default 'custom-posts-listing'.
 *     @type bool   $show_thumbnail Whether to show the thumbnail. Default true.
 *     @type bool   $show_metadata  Whether to show post metadata. Default true.
 *     @type bool   $show_excerpt   Whether to show the excerpt. Default true.
 *     @type bool   $show_blog_link Whether to show a link to the blog page. Default false.
 *     @type string $blog_link_text Text for the blog page link. Default 'View All Posts'.
 *     @type string $blog_page_url  URL of the blog page. Default is the URL of the set blog page.
 * }
 *
 * @return string HTML content for the shortcode.
 * 
 * Example Usage
 * Page:
 * [minimalista_custom_posts_listing show_thumbnail="true" image_size="full" image_classes="" title_tag='h2' article_class="mb-5" html_after='']
 * Sidebar:
 * [minimalista_custom_posts_listing image_size="medium" image_classes="" show_excerpt="false" article_class="mb-3" html_after="<hr>"]
 * Simple lista:
 * [minimalista_custom_posts_listing show_thumbnail='false' image_size="medium" image_classes="" title_tag="p" show_excerpt="false" article_class="my-2"]
 * 
 */
function minimalista_custom_posts_listing_shortcode($atts)
{
    // Default attributes
    $atts = shortcode_atts([
        'posts_per_page'  => 6,
        'image_size'      => 'large',
        'image_classes'   => '', // Additional classes for <img>
        'title_tag'       => 'h4',
        'article_class'   => '', // Additional class for <article>
        'post_status'     => 'publish',
        'html_before'     => '',
        'html_after'      => '',
        'section_class'   => '', // Additional class for <section>
        'show_thumbnail'  => true, // Option to show/hide thumbnail
        'show_metadata'   => false, // Option to show/hide post metadata
        'show_excerpt'    => true, // Option to show/hide excerpt
        'show_blog_link'  => false, // Option to show/hide blog link
        'blog_link_text'  => __('View All Posts', 'minimalista'), // Translatable link text
        'blog_page_url'   => get_permalink(get_option('page_for_posts'))
    ], $atts, 'minimalista_custom_posts_listing');


    // Validar e sanear os atributos
    $atts['posts_per_page'] = intval($atts['posts_per_page']);
    $atts['image_size']     = sanitize_text_field($atts['image_size']);

    // Tratar o saneamento de varias classes para <img>
    $image_classes_att = explode(' ', $atts['image_classes']);
    $sanitized_image_classes = array_map('sanitize_html_class', $image_classes_att);
    $sanitized_image_class_string = implode(' ', $sanitized_image_classes);

    $atts['title_tag']      = sanitize_text_field($atts['title_tag']);
    $atts['article_class']  = sanitize_html_class($atts['article_class']);
    $atts['post_status']    = sanitize_text_field($atts['post_status']);
    $atts['html_before']    = wp_kses_post($atts['html_before']);
    $atts['html_after']     = wp_kses_post($atts['html_after']);
    $atts['section_class']  = sanitize_html_class($atts['section_class']);
    $atts['show_metadata']  = filter_var($atts['show_metadata'], FILTER_VALIDATE_BOOLEAN);
    $atts['show_thumbnail'] = filter_var($atts['show_thumbnail'], FILTER_VALIDATE_BOOLEAN);
    $atts['show_excerpt']   = filter_var($atts['show_excerpt'], FILTER_VALIDATE_BOOLEAN);
    $atts['show_blog_link'] = filter_var($atts['show_blog_link'], FILTER_VALIDATE_BOOLEAN);
    $atts['blog_link_text'] = sanitize_text_field($atts['blog_link_text']);
    $atts['blog_page_url']  = esc_url_raw($atts['blog_page_url']);

    // Validar tag HTML
    $allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div'
    ]; // Tags permitidas
    $title_tag = $atts['title_tag'];
    if (!in_array($title_tag, $allowed_tags)) {
        $title_tag = 'h4'; // Define um valor padrão se a tag não for permitida
    }
 
    // WP_Query arguments
    $args = [
        'post_type' => 'post',
        'posts_per_page' => intval($atts['posts_per_page']),
        'post_status' => sanitize_text_field($atts['post_status'])
    ];

    // Perform the query
    $query = new WP_Query($args);

    ob_start(); // Start output buffering.

    // Concatenar classes fixas com classes adicionais
    $image_classes = 'thumbnail img-fluid' . $sanitized_image_class_string;
    $section_classes = 'custom-posts-listing ' . esc_attr($atts['section_class']);

    echo '<section class="' . $section_classes . '" itemscope itemtype="http://schema.org/Blog">';

    while ($query->have_posts()) {
        $query->the_post();
        $image_format = get_featured_image_format(get_the_ID());

        echo $atts['html_before'];

        // Concatenar classe fixa 'blog-posting' com classe adicional
        $article_classes = 'blog-posting ' . esc_attr($atts['article_class']);

        echo '<article id="post-' . get_the_ID() . '" ';
        post_class($article_classes);
        echo ' itemscope itemtype="http://schema.org/BlogPosting">';

        // Apply different designs based on the image format.
        switch ($image_format) {
            case 'landscape':
                // Landscape format design.

                // Display title, summary, and image.
                echo '<header class="entry-header">';
                minimalista_display_post_title($title_tag, '', true);
                // Conditional display post metadata
                if ($atts['show_metadata']) {
                    minimalista_display_post_metadata_primary();
                }
                echo '</header><!-- ./header -->';
                // Conditional display of the thumbnail
                if ($atts['show_thumbnail']) {
                    minimalista_display_post_thumbnail($atts['image_size'], $image_classes, true);
                }
                // Conditional display the excerpt
                if ($atts['show_excerpt']) {
                    minimalista_display_post_excerpt();
                }

                break;

            case 'portrait':
                // Portrait format design.

                // Conditional display of the thumbnail
                if ($atts['show_thumbnail']) {

                    // Display image, title and summary.
                    echo '<div class="row">';

                    echo '<div class="col-4">';
                    minimalista_display_post_thumbnail($atts['image_size'], $image_classes, true);
                    echo '</div><!-- ./col-4 -->';
                    echo '<div class="col-8">';
                    echo '<header class="entry-header">';
                    minimalista_display_post_title($title_tag, '', true);
                    // Conditional display post metadata
                    if ($atts['show_metadata']) {
                        minimalista_display_post_metadata_primary();
                    }
                    echo '</header><!-- ./header -->';
                    // Conditional display the excerpt
                    if ($atts['show_excerpt']) {
                        minimalista_display_post_excerpt();
                    }
                    echo '</div><!-- .col-8 -->';
                    echo '</div><!-- ./row -->';

                }
                else {
                    echo '<header class="entry-header">';
                    minimalista_display_post_title($title_tag, '', true);
                    // Conditional display post metadata
                    if ($atts['show_metadata']) {
                        minimalista_display_post_metadata_primary();
                    }
                    echo '</header><!-- ./header -->';
                    // Conditional display the excerpt
                    if ($atts['show_excerpt']) {
                        minimalista_display_post_excerpt();
                    }
                }

                break;

            case 'square':
                // Square format design.

                // Conditional display of the thumbnail
                if ($atts['show_thumbnail']) {

                    // Display image, title and summary.
                    echo '<div class="row">';

                    echo '<div class="col-4">';
                    minimalista_display_post_thumbnail($atts['image_size'], $image_classes, true);
                    echo '</div><!-- ./col-4 -->';
                    echo '<div class="col-8">';
                    echo '<header class="entry-header">';
                    minimalista_display_post_title($title_tag, '', true);
                    // Conditional display post metadata
                    if ($atts['show_metadata']) {
                        minimalista_display_post_metadata_primary();
                    }
                    echo '</header><!-- ./header -->';
                    // Conditional display the excerpt
                    if ($atts['show_excerpt']) {
                        minimalista_display_post_excerpt();
                    }
                    echo '</div><!-- .col-8 -->';
                    echo '</div><!-- ./row -->';
                } else {
                    echo '<header class="entry-header">';
                    minimalista_display_post_title($title_tag, '', true);
                    // Conditional display post metadata
                    if ($atts['show_metadata']) {
                        minimalista_display_post_metadata_primary();
                    }
                    echo '</header><!-- ./header -->';
                    // Conditional display the excerpt
                    if ($atts['show_excerpt']) {
                        minimalista_display_post_excerpt();
                    }
                }

                break;

            default:
                // Fallback design if no featured image is found.
                echo '<header class="entry-header">';
                minimalista_display_post_title($title_tag, '', true);
                // Conditional display post metadata
                if ($atts['show_metadata']) {
                    minimalista_display_post_metadata_primary();
                }
                echo '</header><!-- ./header -->';
                // Conditional display the excerpt
                if ($atts['show_excerpt']) {
                    minimalista_display_post_excerpt();
                }
        }

        echo '</article>';
        echo $atts['html_after'];
    }

    wp_reset_postdata();

    // Conditional display of the blog link
    if ($atts['show_blog_link']) {
        echo '<a href="' . esc_url($atts['blog_page_url']) . '">' . esc_html($atts['blog_link_text']) . '</a>';
    }

    echo '</section>';

    return ob_get_clean(); // Return the buffered output.
}

// Register the shortcode.
add_shortcode('minimalista_custom_posts_listing', 'minimalista_custom_posts_listing_shortcode');
