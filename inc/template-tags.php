<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package minimalista
 */

 if ( ! function_exists( 'minimalista_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function minimalista_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'minimalista' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'minimalista_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function minimalista_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'minimalista' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'minimalista_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function minimalista_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'minimalista' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'minimalista' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'minimalista' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'minimalista' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'minimalista' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'minimalista' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'minimalista_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function minimalista_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;

/**
 * Generate HTML for FontAwesome icons
 *
 * @param string $icon_class Class for the FontAwesome icon.
 * @param string $additional_classes Additional classes to be added.
 * @return string HTML for the icon.
 */
function minimalista_generate_icon_html($icon_class, $additional_classes = '') {
    $icon_class = sanitize_html_class($icon_class);
    $additional_classes = sanitize_html_class($additional_classes);
    return '<i class="fa ' . esc_attr($icon_class) . ' ' . esc_attr($additional_classes) . '"></i>';
}

/**
 * Display post metadata based on post format
 *
 * This function displays metadata such as categories, tags, author, and date.
 * It also conditionally displays format-specific metadata.
 * Utilizes Bootstrap 5 and FontAwesome for styling.
 * @TODO: audio and video duration
 *
 * @param string $additional_class Classe adicional a ser adicionada à tag <div>.
 */
function display_post_metadata_primary($additional_classes = '')
{
    // Validating and sanitizing parameters
    $additional_classes = implode(' ', array_map('sanitize_html_class', explode(' ', $additional_classes)));

    // Get the post format
    $post_format = get_post_format() ?: 'standard';

    $div_classes = 'post-metadata post-metadata-primary post-meta d-flex flex-wrap align-items-center text-muted';

    // Concatena a classe adicional se ela for válida
    if ($additional_classes) {
        $div_classes .= ' ' . $additional_classes;
    }

    // Open Bootstrap 5 "post-metadata" container
    echo '<div class="' . $div_classes . '">';
    // Standard Metadata for posts
    // Date and Author
    echo '<time class="post-date me-4 text-break" itemprop="datePublished">' . minimalista_generate_icon_html("fa-calendar", "me-2") . get_the_date() . '</time>';
    echo '<span class="post-author me-4 text-break" itemprop="author">' . minimalista_generate_icon_html("fa-user", "me-2") . get_the_author() . '</span>';
    echo '</div>';  // Close the "post-metadata" container
}

/**
 * Display post metadata based on post format
 *
 * This function displays metadata such as categories, tags, author, and date.
 * It also conditionally displays format-specific metadata.
 *
 * Utilizes Bootstrap 5 and FontAwesome for styling.
 * @TODO: audio and video duration
 */
function display_post_metadata_secondary($additional_classes = '')
{
    // Validating and sanitizing parameters
    $additional_classes = implode(' ', array_map('sanitize_html_class', explode(' ', $additional_classes)));

    // Get the post format
    $post_format = get_post_format() ?: 'standard';

    $div_classes = 'post-metadata post-metadata-secondary post-meta d-flex flex-wrap align-items-center text-muted';

    // Concatena a classe adicional se ela for válida
    if ($additional_classes) {
        $div_classes .= ' ' . $additional_classes;
    }

    // Open Bootstrap 5 "post-metadata" container
    echo '<div class="' . $div_classes . '">';

    // Conditionals for format-specific metadata
    switch ($post_format) {
        case 'audio':
            // Metadata specific to audio posts
            $audio_duration = get_post_meta(get_the_ID(),
                'audio_duration',
                true
            );
            if (!empty($audio_duration)) {
                // Garantir que o valor seja um formato de duração aceitável ou convertê-lo
                echo '<span class="audio-duration me-3"><i class="fas fa-headphones"></i> ' . esc_html($audio_duration) . '</span>';
            }
            break;
        case 'video':
            // Metadata specific to video posts
            $video_duration = get_post_meta(get_the_ID(), 'video_duration', true);
            if (!empty($video_duration)) {
                // Assegura que o valor seja um formato de duração aceitável ou convertê-lo
                echo '<span class="video-duration me-3"><i class="fas fa-video"></i> ' . esc_html($video_duration) . '</span>';
            }
            break;
        case 'quote':
            // Metadata specific to quote posts
            // get_post_meta() é usado para obter o autor da citação
            $quote_author = get_post_meta(get_the_ID(), 'quote_author', true);
            if (!empty($quote_author)) {
                // Assegura que o valor seja seguro para exibição
                echo '<span class="quote-author me-3"><i class="fas fa-quote-left"></i> ' . esc_html($quote_author) . '</span>';
            }
            break;
        case 'status':
            // Metadata specific to status posts
            $status_mood = get_post_meta(get_the_ID(), 'status_mood', true);
            $emojis = [
                'happy' => '😊',
                'sad' => '😢',
                'excited' => '🤩',
                'thoughtful' => '🤔'
            ];

            if (!empty($status_mood) && isset($emojis[$status_mood])) {
                echo '<span class="status-mood me-3"><i class="fas fa-smile"></i> ' . esc_html($emojis[$status_mood]) . ' ' . ucfirst($status_mood) . '</span>';
            }
            break;
        case 'aside':
            // Metadata specific to aside posts
            $word_count = str_word_count(strip_tags(get_the_content()));
            $post_time = get_the_time('g:i a');

            echo '<span class="aside-word-count me-3"><i class="fas fa-pencil-alt"></i> ' . sprintf(_n('%s palavra', '%s palavras', $word_count, 'light-cms-bootstrap'), $word_count) . '</span>';
            echo '<span class="aside-post-time me-3"><i class="fas fa-clock"></i> ' . esc_html($post_time) . '</span>';
            break;
        case 'gallery':
            // Metadata specific to gallery posts
            $gallery_images = get_post_meta(get_the_ID(), 'gallery_images', true);
            if (!empty($gallery_images) && is_array($gallery_images)) {
                $num_images = count($gallery_images);
                echo '<span class="gallery-count me-3"><i class="fas fa-images"></i> ' . sprintf(_n('%s imagem', '%s imagens', $num_images, 'light-cms-bootstrap'), $num_images) . '</span>';
            } else {
                echo '<span class="gallery-empty me-3">' . __('Sem imagens na galeria', 'light-cms-bootstrap') . '</span>';
            }
            break;
        case 'link':
            // Metadata specific to link posts
            // get_post_meta() é usado para obter o URL principal e o título do link.
            // parse_url() é usado para extrair o domínio do URL.
            // O título e o domínio do link são exibidos, com um ícone apropriado e um link clicável.
            $main_link = get_post_meta(get_the_ID(), 'main_link', true);
            if (!empty($main_link)) {
                $link_title = get_post_meta(get_the_ID(), 'link_title', true) ?: __('Link Externo', 'light-cms-bootstrap');
                $link_domain = parse_url($main_link, PHP_URL_HOST);
                echo '<span class="post-main-link me-3"><i class="fas fa-external-link-alt"></i> <a href="' . esc_url($main_link) . '" target="_blank" rel="noopener noreferrer">' . esc_html($link_title) . ' (' . esc_html($link_domain) . ')</a></span>';
            }
            break;
        case 'image':
            // Metadata specific to image posts
            // Assume que você tem metadados personalizados para dimensões e descrição
            $image_dimensions = get_post_meta(get_the_ID(), 'image_dimensions', true);
            $image_description = get_post_meta(get_the_ID(), 'image_description', true);

            if (!empty($image_dimensions)) {
                echo '<span class="image-dimensions me-3"><i class="fas fa-ruler-combined"></i> ' . esc_html($image_dimensions) . '</span>';
            }
            if (!empty($image_description)) {
                echo '<span class="image-description me-3"><i class="fas fa-info-circle"></i> ' . esc_html($image_description) . '</span>';
            }
            break;
        case 'chat':
            // Metadata specific to chat posts
            $chat_participants = get_post_meta(get_the_ID(), 'chat_participants', true);
            $chat_line_count = get_post_meta(get_the_ID(), 'chat_line_count', true);

            if (!empty($chat_participants)) {
                echo '<span class="chat-participants me-3"><i class="fas fa-users"></i> ' . sprintf(_n('%s participante', '%s participantes', $chat_participants, 'light-cms-bootstrap'), $chat_participants) . '</span>';
            }
            if (!empty($chat_line_count)) {
                echo '<span class="chat-line-count me-3"><i class="fas fa-comment-dots"></i> ' . sprintf(_n('%s linha', '%s linhas', $chat_line_count, 'light-cms-bootstrap'), $chat_line_count) . '</span>';
            }
            break;
        default:
            // Metadata for standard and other custom formats
            // Categories and tags

            // Verifica se existem categorias
            if (has_category()) {
                echo '<span class="post-categories me-3" itemprop="articleSection">' . minimalista_generate_icon_html("fa-folder", "me-2") . get_the_category_list(', ') . '</span>';
            }

            // Verifica se existem tags
            if (has_tag()) {
                echo '<span class="post-tags me-3" itemprop="keywords">' . minimalista_generate_icon_html("fa-tags", "me-2") . get_the_tag_list('', ', ') . '</span>';
            }
            break;
    }

    echo '</div>';  // Close the "post-metadata" container
}