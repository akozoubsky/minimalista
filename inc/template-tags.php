<?php
/**
 * Custom template tags for this theme
 *
 * Esse arquivo geralmente é usado para definir funções personalizadas que são usadas dentro dos templates do seu tema para exibir conteúdo específico.
 * Essas funções geralmente retornam ou imprimem HTML diretamente e são frequentemente chamadas
 * dentro de arquivos de template como single.php, page.php, archive.php, etc.
 * 
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 */

 /**
 * Add even or odd class to posts. Usefull with CSS.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function minimalista_set_attr_post( $attr ) {
    global $evenodd_post;
    ++$evenodd_post;

    // Verifica se a chave 'class' existe no array $attr, se não, a inicializa como uma string vazia
    if (!isset($attr['class'])) {
        $attr['class'] = '';
    }

    // Adiciona 'odd' ou 'even' à classe
    $attr['class'] .= ( $evenodd_post % 2 ) ? 'odd' : 'even';
    
    return $attr;
}
add_filter( 'post_class', 'minimalista_set_attr_post' );

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

if ( ! function_exists( 'minimalista_posted_on' ) ) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function minimalista_posted_on() {
        // Get the date and time of publication and last modification
        $published_date = get_the_date();
        $modified_date = get_the_modified_date();

        $published_date_link = get_the_date( 'Y-m-d' );
        $modified_date_link = get_the_modified_date( 'Y-m-d' );

        $published_link = get_day_link( substr( $published_date_link, 0, 4 ), substr( $published_date_link, 5, 2 ), substr( $published_date_link, 8, 2 ) );
        $modified_link = get_day_link( substr( $modified_date_link, 0, 4 ), substr( $modified_date_link, 5, 2 ), substr( $modified_date_link, 8, 2 ) );

        // Get the authors of the publication and last modification
        $published_author_id = get_the_author_meta( 'ID' );
        $modified_author_id = get_post_meta( get_the_ID(), '_edit_last', true );

        // Get the author names
        $published_author_name = get_the_author_meta( 'display_name', $published_author_id );
        $modified_author_name = get_the_author_meta( 'display_name', $modified_author_id );

        // Time string for the publication date
        $time_string_published = sprintf(
            '<time pubdate class="entry-date published" datetime="%1$s">%2$s</time>',
            esc_attr( get_the_date( DATE_W3C ) ),
            esc_html( $published_date )
        );

        // Time string for the modified date
        $time_string_modified = '';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string_modified = sprintf(
                '<time class="updated" datetime="%1$s">%2$s</time>',
                esc_attr( get_the_modified_date( DATE_W3C ) ),
                esc_html( $modified_date )
            );
        }

        // Create the final string with the link to the post for the publication date
        $posted_on = sprintf(
            /* translators: %s: post date. */
            esc_html_x( 'Publicado em %s', 'post date', 'minimalista' ),
            '<a href="' . esc_url( $published_link ) . '" rel="bookmark">' . $time_string_published . '</a>'
        );

        // Create the final string with the link to the post for the modified date
        $updated_on = '';
        if ( $time_string_modified ) {
            $updated_on = sprintf(
                /* translators: %s: modified date. */
                esc_html_x( 'Atualizado em %s', 'modified date', 'minimalista' ),
                '<a href="' . esc_url( $modified_link ) . '" rel="bookmark">' . $time_string_modified . '</a>'
            );
        }

        // Display the author information
        $author_info = sprintf(
            /* translators: %s: post author. */
            esc_html_x( 'por %s', 'post author', 'minimalista' ),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( $published_author_id ) ) . '">' . esc_html( $published_author_name ) . '</a></span>'
        );

        // Display the modified author information if different from the original author
        $modified_author_info = '';
        if ( $published_author_id !== $modified_author_id && $modified_author_name ) {
            $modified_author_info = sprintf(
                /* translators: %s: modified author. */
                esc_html_x( 'por %s', 'modified author', 'minimalista' ),
                '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( $modified_author_id ) ) . '">' . esc_html( $modified_author_name ) . '</a></span>'
            );
        }

        // Print the final HTML with improved structure and elimination of unnecessary information
        echo '<div class="post-metadata">';
        echo '<div class="posted-on">' . $posted_on . ' ' . $author_info . '</div>';
        if ( $time_string_modified && $modified_author_info ) {
            echo '<div class="updated-on">' . $updated_on . ' ' . $modified_author_info . '</div>';
        }
        echo '</div>';
    }
endif;

/**
 * Display the time since a post was published in a human-readable format.
 *
 * This function calculates the time that has elapsed since a post was published and displays it
 * in a human-readable format, such as "2 hours ago" or "3 days ago" or "1 year ago".
 */
function minimalista_display_time_since_posted()
{
    // Get the UNIX timestamp for the current time and the post's published time
    $current_time = current_time('timestamp');
    $post_time = get_the_time('U');

    // Calculate the time difference in seconds
    $time_difference = $current_time - $post_time;

    // Define time periods in seconds
    $minute = 60;
    $hour = $minute * 60;
    $day = $hour * 24;
    $week = $day * 7;
    $year = $day * 365.25;  // Account for leap years

    // Determine the appropriate time unit and value
    if ($time_difference < $hour) {
        $time_value = round($time_difference / $minute);
        $time_unit = _n('minute', 'minutes', $time_value, 'light-cms-bootstrap');
    } elseif ($time_difference < $day) {
        $time_value = round($time_difference / $hour);
        $time_unit = _n('hour', 'hours', $time_value, 'light-cms-bootstrap');
    } elseif ($time_difference < $week) {
        $time_value = round($time_difference / $day);
        $time_unit = _n('day', 'days', $time_value, 'light-cms-bootstrap');
    } elseif ($time_difference < $year) {
        $time_value = round($time_difference / $week);
        $time_unit = _n('week', 'weeks', $time_value, 'light-cms-bootstrap');
    } else {
        $time_value = round($time_difference / $year);
        $time_unit = _n('year', 'years', $time_value, 'light-cms-bootstrap');
    }

    // Display the time since posted
    echo sprintf(_n('%s ' . $time_unit . ' ago', '%s ' . $time_unit . ' ago', $time_value, 'light-cms-bootstrap'), $time_value);
}

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
	}
endif;

if ( ! function_exists( 'minimalista_display_post_thumbnail_old' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function minimalista_display_post_thumbnail_old() {
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

/**
 * Displays the post title based on metabox selection, with optional link.
 * Incorporates semantic HTML, schema.org microdata, accessibility features, custom CSS classes,
 * alt attribute for images in the title, control over the rel attribute in links,
 * and general HTML wrappers around the title.
 *
 * @param string $tag Optional. The HTML tag to use for the title. Default 'h1'.
 * @param string $class Optional. Additional CSS classes for the title tag. Default empty.
 * @param bool $link Optional. Whether to display the title as a link. Default false.
 * @param string $html_before Optional. HTML to be added before the title. Default empty.
 * @param string $html_after Optional. HTML to be added after the title. Default empty.
 * @param bool $echo Optional. Whether to echo the title or return it. Default true (echo).
 * @param int|null $post_id Optional. Post ID. Default null for the current post in the loop.
 * @param string $rel Optional. The rel attribute for the link. Default empty.
 * @param string $alt_text Optional. Alternative text for an image in the title. Default empty.
 * @return string|void The post title.
 *
 * Example Usage:
 *
 * Inside the Loop:
 * 
 * if (have_posts()) {
 *     while (have_posts()) {
 *         the_post();
 *         minimalista_display_post_title('h2', 'my-custom-class', true, '<div class="title-wrapper">', '</div>');
 *     }
 * }
 *
 * 
 * Outside the Loop:
 * 
 * // Displaying a specific post title outside the loop
 * minimalista_display_post_title('h3', 'my-custom-class', false, '', '', false, 42); // 42 is an example post ID
 * 
 * 
 * Filter:
 * 
 * add_filter('minimalista_display_post_title_output', 'add_icon_to_title', 10, 2);
 * 
 * function add_icon_to_title($title_html, $post_id) {
 *    // Verifica se é um post específico ou adiciona lógica condicional
 *    $icon_html = '<span class="my-icon-class"></span>';
 *    return $icon_html . $title_html;
 * } 
 * 
 */
function minimalista_display_post_title($tag = 'h1', $class = '', $link = false, $html_before = '', $html_after = '', $echo = true, $post_id = null, $rel = '', $alt_text = '')
{

    // Validar tag HTML
    $allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div']; // Tags permitidas
    if (!in_array($tag, $allowed_tags)) {
        $tag = 'h1'; // Define um valor padrão se a tag não for permitida
    }

    // Escapamento de strings
    $class = esc_attr($class);
    $rel = esc_attr($rel);
    $alt_text = esc_attr($alt_text);
    $html_before = wp_kses_post($html_before); // Permite HTML seguro
    $html_after = wp_kses_post($html_after);   // Permite HTML seguro

    // Se $post_id for fornecido, ele é convertido para inteiro usando intval(). Se não for fornecido, get_the_ID() é usado para obter o ID do post atual
    $post_id = $post_id ? intval($post_id) : get_the_ID();

    // Obtém a configuração do metabox para exibir ou ocultar o título.
    $show_title = get_post_meta($post_id, '_title_display', true);

    // Verifica se o título deve ser exibido.
    if ($show_title !== 'no') {
        // Obtém o título do post.
        $title_text = get_the_title($post_id);
        $permalink = get_permalink($post_id);
        $class_attr = $class ? ' class="' . esc_attr($class) . '"' : '';
        $rel_attr = $rel ? ' rel="' . esc_attr($rel) . '"' : '';

        // Se a opção de link está ativa, envolve o título com um link para o post.
        if ($link) {
            $title = '<a href="' . esc_url($permalink) . '"' . $rel_attr . ' itemprop="url">' . esc_html($title_text) . '</a>';
        } else {
            $title = esc_html($title_text);
        }

        // Adiciona um alt text se fornecido e se o título contiver uma imagem
        if ($alt_text && strpos($title, '<img') !== false) {
            $title = str_replace('<img', '<img alt="' . esc_attr($alt_text) . '"', $title);
        }

        // Monta o título com o HTML antes e depois, conforme especificado.
        $title_html = $html_before . "<$tag $class_attr itemprop='headline'>" . $title . "</$tag>" . $html_after;

        // Aplicação do filtro para personalização adicional
        $title_html = apply_filters('minimalista_display_post_title_output', $title_html, $post_id);

        // Exibe ou retorna o título, baseado na opção $echo.
        if ($echo) {
            echo $title_html;
        } else {
            return $title_html;
        }
    }
}

/**
 * Determine the format of the featured image.
 * 
 * @param int $post_id Post ID.
 * @return string Format of the image (square, landscape, portrait).
 */
function minimalista_get_featured_image_format($post_id = null)
{
    // Se $post_id for fornecido, ele é convertido para inteiro usando intval(). Se não for fornecido, get_the_ID() é usado para obter o ID do post atual
    $post_id = $post_id ? intval($post_id) : get_the_ID();

    $image_id = get_post_thumbnail_id($post_id);
    if (!$image_id) {
        return ''; // No featured image.
    }

    $image_data = wp_get_attachment_metadata($image_id);
    if (!$image_data) {
        return ''; // No image data found.
    }

    $width = $image_data['width'];
    $height = $image_data['height'];

    if ($width > $height) {
        return 'landscape';
    } elseif ($width < $height) {
        return 'portrait';
    } else {
        return 'square';
    }
}

/**
 * Exibe a miniatura (thumbnail) do post, com opções de personalização.
 *
 * @param string $size Tamanho da imagem (thumbnail, medium, large, full ou customizado).
 * @param string $class Classe CSS adicional para a tag da imagem.
 * @param bool $link Optional. Whether to display the image as a link. Default false.
 * @param bool $echo Optional. Whether to echo the image or return the image. Default true (echo).
 * @param string $html_before Optional. HTML to be added before the image. Default empty.
 * @param string $html_after Optional. HTML to be added after the image. Default empty.
 * @param int|null $post_id Optional. Post ID. Default null for the current post in the loop.
 * @param string $rel Optional. The rel attribute for the link. Default empty.
 * @param string $alt_text Optional. Alternative text for an image in the title. Default empty.
 */
function minimalista_display_post_thumbnail($size = 'thumbnail', $class = '', $link = false, $echo = true, $html_before = '', $html_after = '', $post_id = null, $rel = '', $alt_text = '')
{
    // Escapamento e validação de strings
    $class = esc_attr($class);
    $rel = esc_attr($rel);
    $alt_text = esc_attr($alt_text);
    $html_before = wp_kses_post($html_before); // Permite HTML seguro
    $html_after = wp_kses_post($html_after);   // Permite HTML seguro

    // Sanitização de múltiplas classes CSS
    $classes_array = explode(' ', $class);
    $sanitized_classes = array_map('sanitize_html_class', $classes_array);

    // Se $post_id for fornecido, ele é convertido para inteiro usando intval(). Se não for fornecido, get_the_ID() é usado para obter o ID do post atual
    $post_id = $post_id ? intval($post_id) : get_the_ID();

    // Define o texto alternativo padrão para o título do post, se não fornecido
    if (empty($alt_text)) {
        $alt_text = get_the_title($post_id);
    }

    // Obtém o ID do attachment da imagem destacada
    $thumbnail_id = get_post_thumbnail_id($post_id);

    // Obtém as dimensões da imagem
    $image_info = wp_get_attachment_image_src($thumbnail_id, $size);
    if ($image_info) {
        $width = $image_info[1];
        $height = $image_info[2];

        // Determina o formato da imagem
        if ($width == $height) {
            $image_format = 'square';
        } elseif ($width > $height) {
            $image_format = 'landscape';
        } else {
            $image_format = 'portrait';
        }
    } else {
        $image_format = 'unknown';
    }

    // Constrói as classes fixas e combina com as classes fornecidas, evitando duplicatas
    $fixed_classes = "thumbnail img-fluid attachment-{$size} img-id-{$thumbnail_id} format-{$image_format}";
    $final_classes = implode(' ', array_unique(array_merge(explode(' ', $fixed_classes), $sanitized_classes)));

    // Verifica se o post tem uma imagem destacada
    if (has_post_thumbnail($post_id)) {
        // Recupera a imagem destacada
        $thumbnail_html = get_the_post_thumbnail($post_id, $size, array('class' => $final_classes, 'alt' => $alt_text));

        // Monta a imagem com link, se necessário
        if ($link) {
            $permalink = get_permalink($post_id);
            $rel_attribute = $rel ? ' rel="' . $rel . '"' : '';
            $thumbnail_html = '<a href="' . esc_url($permalink) . '"' . $rel_attribute . '>' . $thumbnail_html . '</a>';
        }

        // Prepara o HTML final
        $output = $html_before . $thumbnail_html . $html_after;

        // Exibe ou retorna o HTML
        if ($echo) {
            echo $output;
        } else {
            return $output;
        }
    }
    // Nenhuma ação se o post não tiver imagem destacada
}


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
function minimalista_display_post_metadata_primary($additional_classes = '')
{
    // Validating and sanitizing parameters
    $additional_classes = implode(' ', array_map('sanitize_html_class', explode(' ', $additional_classes)));

    // Get the post format
    $post_format = get_post_format() ?: 'standard';

    $div_classes = 'post-metadata post-metadata-primary post-meta d-flex flex-wrap align-items-center';

    // Concatena a classe adicional se ela for válida
    if ($additional_classes) {
        $div_classes .= ' ' . $additional_classes;
    }

    // Open meta container
    echo '<div class="entry-meta">';

    // Open Bootstrap 5 "post-metadata" container
    echo '<div class="' . $div_classes . '">';

    // Standard Metadata for posts
    // Date and Author
    echo '<time pubdate class="post-date text-break me-4" itemprop="datePublished">' . minimalista_generate_icon_html("fa-calendar", "me-2") . get_the_date() . '</time>';
    echo '<span class="post-author text-break me-4" itemprop="author">' . minimalista_generate_icon_html("fa-user", "me-2") . get_the_author() . '</span>';

    // Format-specific Metadata
    switch ($post_format) {
        case 'aside':
            echo '<span class="post-format post-format-aside text-break me-4">' . minimalista_generate_icon_html("fa-pencil-alt", "me-2") . __('Aside', 'minimalista') . '</span>';
            break;
        case 'gallery':
            echo '<span class="post-format post-format-gallery text-break me-4">' . minimalista_generate_icon_html("fa-images", "me-2") . __('Gallery', 'minimalista') . '</span>';
            break;
        case 'link':
            echo '<span class="post-format post-format-link text-break me-4">' . minimalista_generate_icon_html("fa-link", "me-2") . __('Link', 'minimalista') . '</span>';
            break;
        case 'image':
            echo '<span class="post-format post-format-image text-break me-4">' . minimalista_generate_icon_html("fa-camera", "me-2") . __('Image', 'minimalista') . '</span>';
            break;
        case 'quote':
            echo '<span class="post-format post-format-quote text-break me-4">' . minimalista_generate_icon_html("fa-quote-left", "me-2") . __('Quote', 'minimalista') . '</span>';
            break;
        case 'status':
            echo '<span class="post-format post-format-status text-break me-4">' . minimalista_generate_icon_html("fa-comment", "me-2") . __('Status', 'minimalista') . '</span>';
            break;
        case 'video':
            echo '<span class="post-format post-format-video text-break me-4">' . minimalista_generate_icon_html("fa-video", "me-2") . __('Video', 'minimalista') . '</span>';
            break;
        case 'audio':
            echo '<span class="post-format post-format-audio text-break me-4">' . minimalista_generate_icon_html("fa-music", "me-2") . __('Audio', 'minimalista') . '</span>';
            break;
        case 'chat':
            echo '<span class="post-format post-format-chat text-break me-4">' . minimalista_generate_icon_html("fa-comments", "me-2") . __('Chat', 'minimalista') . '</span>';
            break;
        default:
            // Standard post format doesn't need an icon
            break;
    }

    echo '</div>';  // Close the "post-metadata" container

    echo '</div><!-- .entry-meta -->'; // Close the "meta" container
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
function minimalista_display_post_metadata_secondary($additional_classes = '')
{
    // Validating and sanitizing parameters
    $additional_classes = implode(' ', array_map('sanitize_html_class', explode(' ', $additional_classes)));

    // Get the post format
    $post_format = get_post_format() ?: 'standard';

    $div_classes = 'post-metadata post-metadata-secondary post-meta d-flex flex-wrap align-items-center';

    // Concatena a classe adicional se ela for válida
    if ($additional_classes) {
        $div_classes .= ' ' . $additional_classes;
    }

    // Open footer container
    echo '<footer class="entry-footer">';

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
                echo '<span class="audio-duration me-4"><i class="fas fa-headphones"></i> ' . esc_html($audio_duration) . '</span>';
            }
            break;
        case 'video':
            // Metadata specific to video posts
            $video_duration = get_post_meta(get_the_ID(), 'video_duration', true);
            if (!empty($video_duration)) {
                // Assegura que o valor seja um formato de duração aceitável ou convertê-lo
                echo '<span class="video-duration me-4"><i class="fas fa-video"></i> ' . esc_html($video_duration) . '</span>';
            }
            break;
        case 'quote':
            // Metadata specific to quote posts
            // get_post_meta() é usado para obter o autor da citação
            $quote_author = get_post_meta(get_the_ID(), 'quote_author', true);
            if (!empty($quote_author)) {
                // Assegura que o valor seja seguro para exibição
                echo '<span class="quote-author me-4"><i class="fas fa-quote-left"></i> ' . esc_html($quote_author) . '</span>';
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
                echo '<span class="status-mood me-4"><i class="fas fa-smile"></i> ' . esc_html($emojis[$status_mood]) . ' ' . ucfirst($status_mood) . '</span>';
            }
            break;
        case 'aside':
            // Metadata specific to aside posts
            $word_count = str_word_count(strip_tags(get_the_content()));
            $post_time = get_the_time('g:i a');

            echo '<span class="aside-word-count me-4"><i class="fas fa-pencil-alt"></i> ' . sprintf(_n('%s palavra', '%s palavras', $word_count, 'minimalista'), $word_count) . '</span>';
            echo '<span class="aside-post-time me-4"><i class="fas fa-clock"></i> ' . esc_html($post_time) . '</span>';
            break;
        case 'gallery':
            // Metadata specific to gallery posts
            $gallery_images = get_post_meta(get_the_ID(), 'gallery_images', true);
            if (!empty($gallery_images) && is_array($gallery_images)) {
                $num_images = count($gallery_images);
                echo '<span class="gallery-count me-4"><i class="fas fa-images"></i> ' . sprintf(_n('%s imagem', '%s imagens', $num_images, 'minimalista'), $num_images) . '</span>';
            } else {
                echo '<span class="gallery-empty me-4">' . __('Sem imagens na galeria', 'minimalista') . '</span>';
            }
            break;
        case 'link':
            // Metadata specific to link posts
            // get_post_meta() é usado para obter o URL principal e o título do link.
            // parse_url() é usado para extrair o domínio do URL.
            // O título e o domínio do link são exibidos, com um ícone apropriado e um link clicável.
            $main_link = get_post_meta(get_the_ID(), 'main_link', true);
            if (!empty($main_link)) {
                $link_title = get_post_meta(get_the_ID(), 'link_title', true) ?: __('Link Externo', 'minimalista');
                $link_domain = parse_url($main_link, PHP_URL_HOST);
                echo '<span class="post-main-link me-4"><i class="fas fa-external-link-alt"></i> <a href="' . esc_url($main_link) . '" target="_blank" rel="noopener noreferrer">' . esc_html($link_title) . ' (' . esc_html($link_domain) . ')</a></span>';
            }
            break;
        case 'image':
            // Metadata specific to image posts
            // Assume que você tem metadados personalizados para dimensões e descrição
            $image_dimensions = get_post_meta(get_the_ID(), 'image_dimensions', true);
            $image_description = get_post_meta(get_the_ID(), 'image_description', true);

            if (!empty($image_dimensions)) {
                echo '<span class="image-dimensions me-4"><i class="fas fa-ruler-combined"></i> ' . esc_html($image_dimensions) . '</span>';
            }
            if (!empty($image_description)) {
                echo '<span class="image-description me-4"><i class="fas fa-info-circle"></i> ' . esc_html($image_description) . '</span>';
            }
            break;
        case 'chat':
            // Metadata specific to chat posts
            $chat_participants = get_post_meta(get_the_ID(), 'chat_participants', true);
            $chat_line_count = get_post_meta(get_the_ID(), 'chat_line_count', true);

            if (!empty($chat_participants)) {
                echo '<span class="chat-participants me-4"><i class="fas fa-users"></i> ' . sprintf(_n('%s participante', '%s participantes', $chat_participants, 'minimalista'), $chat_participants) . '</span>';
            }
            if (!empty($chat_line_count)) {
                echo '<span class="chat-line-count me-4"><i class="fas fa-comment-dots"></i> ' . sprintf(_n('%s linha', '%s linhas', $chat_line_count, 'minimalista'), $chat_line_count) . '</span>';
            }
            break;
    }

    // Metadata all formats
    // Categories and tags

    // Verifica se existem categorias
    if (has_category()) {
        echo '<span class="post-categories me-4" itemprop="articleSection">' . minimalista_generate_icon_html("fa-folder", "me-2") . get_the_category_list(', ') . '</span>';
    }

    // Verifica se existem tags
    if (has_tag()) {
        echo '<span class="post-tags me-4" itemprop="keywords">' . minimalista_generate_icon_html("fa-tags", "me-2") . get_the_tag_list('', ', ') . '</span>';
    }

    echo '</div><!-- .post-metadata -->';  // Close the "post-metadata" container

    echo '</footer><!-- .entry-footer -->'; // Close the "footer" container
}

/**
 * Displays an edit post link if the user has permission to edit the post.
 *
 * This function checks if the current user has permission to edit the post,
 * and if so, displays an edit link with the provided text. The link is wrapped
 * in a div with a class of "edit-link".
 *
 * @return void Outputs the edit post link HTML if the user can edit the post.
 */
function minimalista_display_edit_post_link() {
    if ( get_edit_post_link() ) {
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
            '<div id="edit-link" class="edit-link">',
            '</div>'
        );
    }
}


/**
 * Displays the post excerpt with additional customization options.
 * 
 * @param string $excerpt_classes Additional classes for the excerpt div.
 * @param string $html_before HTML content to be displayed before the excerpt.
 * @param string $html_after HTML content to be displayed after the excerpt.
 * @param int $length_limit Maximum length of the excerpt in words.
 * @param bool $show_more Indicates whether to show the custom 'more' text.
 * @param string $more_text Custom 'more' text. Default 'Read more'.
 * @return void
 */
function minimalista_display_post_excerpt($excerpt_classes = '', $html_before = '', $html_after = '', $length_limit = 55, $show_more = false, $more_text = 'Leia mais')
{
    // Validating and sanitizing parameters

    // Tratar o saneamento de varias classes
    $excerpt_classes_att = explode(' ', $excerpt_classes);
    $sanitized_excerpt_classes = array_map('sanitize_html_class', $excerpt_classes_att);
    $sanitized_excerpt_classes_string = implode(' ', $sanitized_excerpt_classes);

    $html_before = wp_kses_post($html_before);
    $html_after = wp_kses_post($html_after);
    $length_limit = intval($length_limit);
    $more_text = sanitize_text_field($more_text);

    // Custom excerpt length function
    add_filter('excerpt_length', function ($length) use ($length_limit) {
        return $length_limit;
    }, 999);

    // Custom 'more' text for the excerpt
    if ($show_more) {
        add_filter('excerpt_more', function () use ($more_text) {
            return ' <a class="read-more" href="' . get_permalink() . '">' . __($more_text, 'minimalista') . '</a>';
        });
    }

    // Buffering the output
    ob_start();
    the_excerpt();
    $excerpt_output = ob_get_clean();

    // Checking if the excerpt exists
    if (trim($excerpt_output) === '') {
        return; // Return nothing if no excerpt
    }

    // Displaying the excerpt with additional HTML and classes
    echo $html_before;
    //echo '<div class="entry-excerpt entry-description ' . $sanitized_excerpt_classes_string . '" itemprop="description" itemscope itemtype="http://schema.org/ArticleBody">';
    //echo '<div class="entry-excerpt entry-description ' . $sanitized_excerpt_classes_string . '" itemprop="articleBody">';
    echo '<div class="entry-excerpt entry-description ' . $sanitized_excerpt_classes_string . '" itemprop="description">';
    echo $excerpt_output;
    echo '</div>';
    echo $html_after;
}

/**
 * Displays the post content with additional customization options.
 * 
 * @param string $content_class Additional classes for the content div.
 * @param string $html_before HTML content to be displayed before the content.
 * @param string $html_after HTML content to be displayed after the content.
 * @param int $length_limit Maximum length of the content in words.
 * @param bool $show_more Indicates whether to show the custom 'more' text.
 * @param string $more_text Custom 'more' text. Default 'Read more'.
 * @return void
 */
function minimalista_display_post_content($content_class = '', $html_before = '', $html_after = '', $length_limit = 55, $show_more = false, $more_text = 'Leia mais')
{
    // Validating and sanitizing parameters
    $content_class = sanitize_html_class($content_class);
    $html_before = wp_kses_post($html_before);
    $html_after = wp_kses_post($html_after);
    $length_limit = intval($length_limit);
    $more_text = sanitize_text_field($more_text);

    // Custom content length function
    add_filter('content_length', function ($length) use ($length_limit) {
        return $length_limit;
    }, 999);

    // Custom 'more' text for the content
    if ($show_more) {
        add_filter('content_more', function () use ($more_text) {
            return ' <a class="read-more" href="' . get_permalink() . '">' . __($more_text, 'minimalista') . '</a>';
        });
    }

    // Buffering the output
    ob_start();
    the_content();
    $content_output = ob_get_clean();

    // Checking if the content exists
    if (trim($content_output) === '') {
        return; // Return nothing if no content
    }

    // Displaying the content with additional HTML and classes
    echo $html_before;
    //<div class="page-content" itemprop="mainEntityOfPage" itemscope itemtype="http://schema.org/WebPageElement">
    echo '<div class="entry-content ' . $content_class . '" itemprop="articleBody">';
    //echo '<div class="entry-content ' . $content_class . '" itemprop="mainEntityOfPage">';
    echo $content_output;
    echo '</div>';
    echo $html_after;
}

/**
 * Displays a list of popular articles.
 *
 * This function queries for the most popular articles based on a custom
 * meta field for post views count. It generates and outputs an unordered
 * list of the titles of the popular articles, each linking to the respective post.
 *
 * @since 1.0.0
 *
 * @return void Outputs HTML with the list of popular articles or a message if none are found.
 */
if ( ! function_exists( 'minimalista_display_popular_articles' ) ) :

    function minimalista_display_popular_articles($number_of_posts = 12, $section_title = '', $title_tag = 'h3', $additional_section_classes = '', $additional_item_classes = '', $page_for_posts_link_text = '', $page_for_posts_link_classes = '', $page_for_posts_link_html_before = '', $page_for_posts_link_html_after = '')
    {
        // Validate and sanitize parameters
        $number_of_posts = intval($number_of_posts);
        $section_title = sanitize_text_field($section_title);
        $title_tag = in_array($title_tag, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) ? $title_tag : 'h3';
        $additional_section_classes = implode(' ', array_map('sanitize_html_class', explode(' ', $additional_section_classes)));
        $additional_item_classes = implode(' ', array_map('sanitize_html_class', explode(' ', $additional_item_classes)));
        $page_for_posts_link_text = sanitize_text_field(__($page_for_posts_link_text, 'minimalista'));
        $page_for_posts_link_classes = implode(' ', array_map('sanitize_html_class', explode(' ', $page_for_posts_link_classes)));
        $page_for_posts_link_html_before = wp_kses_post($page_for_posts_link_html_before);
        $page_for_posts_link_html_after = wp_kses_post($page_for_posts_link_html_after);

        // Query for popular posts
        $args = array(
            'posts_per_page' => $number_of_posts,
            'orderby' => 'comment_count', // Or another criterion for popularity
            'order' => 'DESC'
        );
        $popular_posts = new WP_Query($args);

        if ($popular_posts->have_posts()) {
            // Start section
            echo '<section id="display-popular-articles" class="display-popular-articles ' . esc_attr($additional_section_classes) . '">';

            // Display section title if provided
            if (!empty($section_title)) {
                // echo "<{$title_tag}>" . esc_html($section_title) . "</{$title_tag}>";
                echo "<{$title_tag}" . " class='widget-title'" . ">" . esc_html($section_title) . "</{$title_tag}>";
            }

            // Display posts
            if ($popular_posts->have_posts()) {
                echo '<ul id="display-popular-articles-list" class="list-unstyled d-flex flex-column flex-wrap">';
                while ($popular_posts->have_posts()) {
                    $popular_posts->the_post();
                    echo '<li class="display-popular-articles-list-item  text-break ' . esc_attr($additional_item_classes) . '">';
                    echo '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a>';
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>Nenhum post popular encontrado.</p>';
            }

            // Link to the page for posts
            // Verifica se o texto do link está definido e não é vazio antes de exibir o link
            if (!empty($page_for_posts_link_text)) {
                echo $page_for_posts_link_html_before;
                echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '" class="all-topics-link ' . esc_attr($page_for_posts_link_classes) . '">' . esc_html($page_for_posts_link_text) . '</a>';
                echo $page_for_posts_link_html_after;
            }

            // End section
            echo '</section>';
        }
        // Reset post data
        wp_reset_postdata();
    }
endif;

/* ########################################################
 *                   Comments / Comentarios
 * ######################################################## */

/* 
 * Esta funcao eh chamada em comments.php
 * Customiza a exibição dos comentários. Exibe o autor do comentário, a data e hora, o conteúdo e o link para resposta.
 */
   function minimalista_bootstrap_comment_callback($comment, $args, $depth)
   {
       $GLOBALS['comment'] = $comment; // Define o comentário global para uso em tags de template.
       $tag = ('div' === $args['style']) ? 'div' : 'li';
       ?>
       <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class($args['has_children'] ? 'parent' : '', null, null, false); ?>>
           <div class="card">
               <div class="card-body bg-secondary-subtle">
                   <div class="row g-2">
                       <div class="col-md-1">
                           <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>
                       </div>
                       <div class="col-md-11">
                           <h5 class="card-title">
                               <?php printf(__('%s <span class="says">says:</span>'), get_comment_author_link()); ?>
                           </h5>
                           <h6 class="card-subtitle">
                               <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
                                   <?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()); ?>
                               </a>
                           </h6>
   
                           <?php if ($comment->comment_approved == '0') : ?>
                               <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.'); ?></em><br />
                           <?php endif; ?>
   
                           <div class="comment-metadata">
                               <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
                                   <?php edit_comment_link(__('Edit'), '<span class="edit-link">', '</span>'); ?>
                               </a>
                           </div>
                       </div><!--.col -->
                   </div><!-- .row -->
   
                   <div class="comment-content">
                       <?php comment_text(); ?>
                   </div>
   
                   <div class="reply">
                       <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                   </div>
               </div>
           </div>
       <?php
       // Não feche a tag $tag aqui, o WordPress fará isso por nós
   }

/*
 * Captura a saída do formulário de comentário, substitui novalidate por data-toggle="validator" e exibe o formulário modificado.
 * 
 */
function minimalista_validate_comment_form($args = array()) {
    // Adiciona classes Bootstrap aos campos do formulário de comentário
    $args = wp_parse_args($args, array(
        'fields' => array(
            'author' => '<div class="form-group"><label for="author">' . __('Name', 'minimalista') . ' <span class="required">*</span></label> ' .
                        '<input id="author" name="author" type="text" class="form-control" value="' . esc_attr(isset($commenter['comment_author']) ? $commenter['comment_author'] : '') . '" size="30" /></div>',
            'email'  => '<div class="form-group"><label for="email">' . __('Email', 'minimalista') . ' <span class="required">*</span></label> ' .
                        '<input id="email" name="email" type="text" class="form-control" value="' . esc_attr(isset($commenter['comment_author_email']) ? $commenter['comment_author_email'] : '') . '" size="30" /></div>',
            'url'    => '<div class="form-group"><label for="url">' . __('Website', 'minimalista') . '</label> ' .
                        '<input id="url" name="url" type="text" class="form-control" value="' . esc_attr(isset($commenter['comment_author_url']) ? $commenter['comment_author_url'] : '') . '" size="30" /></div>',
        ),
        'comment_field' => '<div class="form-group"><label for="comment">' . __('Comment', 'minimalista') . ' <span class="required">*</span></label> ' .
                           '<textarea id="comment" name="comment" class="form-control" rows="8" required></textarea></div>',
        'class_submit' => 'btn btn-primary', // Classe Bootstrap para o botão de envio
    ));

    // Start output buffering to capture the form HTML
    ob_start();

    // Call the comment_form() function with the provided arguments
    comment_form($args);

    // Capture the buffered HTML and replace 'novalidate' with 'data-toggle="validator"'
    $form_html = str_replace('novalidate', 'data-toggle="validator"', ob_get_clean());

    // Echo the modified form HTML
    echo $form_html;
}


/* 
 * Este filtro permite que você modifique os argumentos padrão passados para a função comment_form.
 * Adiciona as classes btn e btn-primary ao botão de envio do formulário de comentário, aplicando os estilos do Bootstrap ao botão.
 * Nao serah necessario porque alteramos o 
 */
function minimalista_comment_form_defaults($defaults) {
    // Adiciona classes Bootstrap ao botão de envio do formulário de comentário
    $defaults['class_submit'] = 'btn btn-primary';

    return $defaults;
}
//add_filter('comment_form_defaults', 'minimalista_comment_form_defaults');


/* ########################################################
 *                   Pagination / Paginacao
 * ######################################################## */

/**
 * Custom Post Page Navigation
 *
 * Esta função gera botões de navegação para posts mais antigos e mais recentes,
 * utilizando classes do Bootstrap 5 e ícones do FontAwesome 6 para estilização.
 * Os botões são exibidos apenas quando há posts na direção correspondente.
 * 
 * Because post queries are usually sorted in reverse chronological order,
 * next_posts_link() usually points to older entries (toward the end of the set)
 * and previous_posts_link() usually points to newer entries (toward the beginning of the set).
 * (@link https://developer.wordpress.org/reference/functions/previous_posts_link/)
 * 
 * @uses ob_start(), ob_get_clean() Para capturar a saída das funções de navegação do WordPress.
 * @uses previous_posts_link(), next_posts_link() Para gerar os links de navegação.
 * 
 * @return void
 */
function minimalista_the_post_navigation()
{
    // Buffering para capturar a saída das funções de navegação de posts
    ob_start();
    previous_posts_link('Mais Recentes');
    $previous_posts_link = ob_get_clean();

    ob_start();
    next_posts_link('Mais Antigos');
    $next_posts_link = ob_get_clean();

    // Adicionando classes Bootstrap aos links (btn btn-secondary para estilização de botão)
    $previous_posts_link = str_replace('<a href=', '<a class="btn btn-primary" href=', $previous_posts_link);
    $next_posts_link = str_replace('<a href=', '<a class="btn btn-primary" href=', $next_posts_link);

    // Inserindo ícones FontAwesome dentro dos botões
    if ($previous_posts_link) {
        $previous_posts_link = str_replace('Mais Recentes', '<i class="fas fa-arrow-left me-2"></i>Mais recentes', $previous_posts_link);
    }

    if ($next_posts_link) {
        $next_posts_link = str_replace('Mais Antigos', 'Mais antigos<i class="fas fa-arrow-right ms-2"></i>', $next_posts_link);
    }

    // Início da Navegação de Posts (d-flex e justify-content-between para alinhamento)
    echo '<div class="post-navigation d-flex justify-content-between">';

    // Exibe o botão "Posts mais recentes" apenas se houver posts mais recentes
    if ($previous_posts_link) {
        echo $previous_posts_link;
    }

    // Exibe o botão "Posts mais antigos" apenas se houver posts mais antigos
    if ($next_posts_link) {
        echo $next_posts_link;
    }

    echo '</div>';  // Fim da Navegação de Posts
}

/**
 * Custom Single Post Navigation
 *
 * Esta função gera botões de navegação para a postagem anterior e a próxima postagem,
 * dentro da mesma categoria, utilizando classes do Bootstrap 5 e ícones do FontAwesome 6.
 * Os botões são exibidos apenas quando há posts na direção correspondente.
 * 
 * Used on single post permalink pages, this template tag displays a link to the previous post
 * which exists in chronological order from the current post. This tag must be used in The Loop.
 * (@link https://developer.wordpress.org/reference/functions/previous_post_link/)
 * 
 * @uses ob_start(), ob_get_clean() Para capturar a saída das funções de navegação do WordPress.
 * @uses previous_post_link(), next_post_link() Para gerar os links de navegação.
 * 
 * @return void
 */
function minimalista_single_post_navigation()
{
    ob_start();
    // Gera um link para o post mais recente em relação ao post atual (o próximo post em ordem cronológica)
    previous_post_link('%link', 'Mais antigo');
    $previous_post_link = ob_get_clean();

    ob_start();
    // Gera um link para o post mais antigo em relação ao post atual (o post anterior em ordem cronológica).
    next_post_link('%link', 'Mais recente');
    $next_post_link = ob_get_clean();

    // Adicionando classes Bootstrap aos links (btn btn-secondary para estilização de botão)
    $previous_post_link = str_replace('<a href=', '<a class="btn btn-primary" href=', $previous_post_link);
    $next_post_link = str_replace('<a href=', '<a class="btn btn-primary" href=', $next_post_link);

    // Inserindo ícones FontAwesome dentro dos botões
    if ($previous_post_link) {
        //$previous_post_link = str_replace('Mais antigo', '<i class="fas fa-arrow-left me-2"></i>Mais antigo', $previous_post_link);
        $previous_post_link = str_replace('Mais antigo', 'Mais antigo<i class="fas fa-arrow-right ms-2"></i>', $previous_post_link);
    }
    if ($next_post_link) {
        //$next_post_link = str_replace('Mais recente', 'Mais recente<i class="fas fa-arrow-right ms-2"></i>', $next_post_link);
        $next_post_link = str_replace('Mais recente', '<i class="fas fa-arrow-left me-2"></i>Mais recente', $next_post_link);
    }

    if ($previous_post_link || $next_post_link) {
        echo '<div class="post-navigation d-flex justify-content-between">';
        if ($next_post_link) {
            echo $next_post_link;
        }
        if ($previous_post_link) {
            echo $previous_post_link;
        }
        echo '</div>';
    }
}

/**
 * Generate pagination links for a custom query.
 *
 * This function utilizes WordPress's paginate_links() function to create
 * a set of numerical pagination links based on the provided custom query.
 * The pagination links are styled using Bootstrap's pagination component.
 *
 * @param WP_Query $wp_custom_query The custom query for which to generate pagination links.
 *
 * @return void Outputs the pagination links directly to the page.
 * 
 * @link https://getbootstrap.com/docs/5.3/components/pagination/
 */
function minimalista_custom_query_pagination($wp_custom_query)
{
    // Determine the current page number.
    $paged = max(1, get_query_var('paged', 1));

    // Generate the base URL for pagination links.
    $base_url = str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999)));

    // Call the paginate_links() function to generate an array of pagination links.
    $pages = paginate_links(array(
        'base' => $base_url,
        'format' => '?paged=%#%',  // Define the format for the pagination query, '%#%' is replaced by the page number.
        'current' => $paged,  // Get the current page number, ensure it's at least 1.
        'total' => $wp_custom_query->max_num_pages,  // Get the total number of pages from the provided query.
        'type' => 'array',  // Specify that the function should return an array of page links.
        'show_all' => false,  // Do not show links to all pages, only show a subset of links.
        'end_size' => 2,  // Show links to the first and last 2 pages.
        'mid_size' => 1,  // Show links to the current page, and 1 page on either side.
        'prev_next' => true,  // Show links to the previous and next pages.
        'prev_text' => '<i class="fas fa-angle-left"></i>' . __(' Anterior'), // Set the text for the 'previous page' link.
        'next_text' =>  __(' Próximo ') . '<i class="fas fa-angle-right"></i>', // Set the text for the 'next page' link.
        'add_args' => false,  // Do not add any additional query args to the URLs.
        'add_fragment' => '',  // Do not append any fragments to the URLs.
    ));

    // Check if the paginate_links() function returned an array of page links.
    if (is_array($pages)) {
        echo '<nav class="post-navigation" aria-label="Custom Page Navigation">';
        echo '<ul class="pagination">';
        foreach ($pages as $page) {
            // Check if the page link is for the current page.
            if (strpos($page, 'current') !== false) {
                // If it's the current page, add .active class and aria-current attribute.
                echo '<li class="page-item active" aria-current="page">' . str_replace('page-numbers', 'page-link', $page) . '</li>';
            } else {
                // If it's not the current page, output the page link without .active class and aria-current attribute.
                echo '<li class="page-item">' . str_replace('page-numbers', 'page-link', $page) . '</li>';
            }
        }
        echo '</ul>';
        echo '</nav>';
    }
}

/**
 * Custom Comments Pagination
 *
 * Esta função gera links de paginação para comentários utilizando as classes do Bootstrap 5.
 *
 * @return void
 */
function minimalista_comments_pagination() {
    // Obter a página atual e o número total de páginas de comentários
    $current_page = get_query_var('cpage') ? intval(get_query_var('cpage')) : 1;
    $total_pages = get_comment_pages_count();

    // Somente exibir paginação se houver mais de uma página de comentários
    if ($total_pages > 1) {
        $pagination_args = array(
            'base'         => add_query_arg('cpage', '%#%'),
            'format'       => '',
            'current'      => $current_page,
            'total'        => $total_pages,
            'prev_text'    => '<i class="fas fa-angle-left"></i> ' . __('Anterior', 'minimalista'),
            'next_text'    => __('Próximo', 'minimalista') . ' <i class="fas fa-angle-right"></i>',
            'type'         => 'array',
            'add_args'     => false,
            'add_fragment' => '#comments',  // Adicionar um fragmento para pular para os comentários
        );

        // Gerar links de paginação
        $page_links = paginate_links($pagination_args);

        if (is_array($page_links)) {
            echo '<nav class="comment-navigation" aria-label="Comment Page Navigation">';
            echo '<ul class="pagination">';

            foreach ($page_links as $link) {
                if (strpos($link, 'current') !== false) {
                    echo '<li class="page-item active" aria-current="page">' . str_replace('page-numbers', 'page-link', $link) . '</li>';
                } else {
                    echo '<li class="page-item">' . str_replace('page-numbers', 'page-link', $link) . '</li>';
                }
            }

            echo '</ul>';
            echo '</nav>';
        }
    }
}

/**
 * Custom pagination for paginated posts.
 *
 * This function generates pagination links for paginated posts using Bootstrap 5 classes.
 *
 * @return void Outputs the pagination links directly to the page.
 */
function minimalista_link_pages() {
    $args = array(
        'before'           => '<nav aria-label="Page navigation example"><ul class="pagination">',
        'after'            => '</ul></nav>',
        'link_before'      => '<li class="page-item"><span class="page-link">',
        'link_after'       => '</span></li>',
        'next_or_number'   => 'number',
        'separator'        => '',
        'nextpagelink'     => __( 'Next page', 'minimalista' ),
        'previouspagelink' => __( 'Previous page', 'minimalista' ),
        'pagelink'         => '%',
        'echo'             => 1
    );

    wp_link_pages( $args );
}

/* ########################################################
 *                            Avatar
 * ######################################################## */

/**
 * Display a custom avatar for the post author.
 *
 * This function displays a custom avatar based on a given URL or falls back to the Gravatar avatar.
 *
 * @param string $custom_avatar_url The URL of the custom avatar. Default is an empty string.
 * @param int $size The size of the avatar in pixels. Default is 40.
 * @param string $additional_classes Additional CSS classes for the avatar. Default is an empty string.
 */
function minimalista_display_author_avatar($custom_avatar_url = '', $size = 40, $additional_classes = '')
{
    $class_str = 'border rounded-circle ' . $additional_classes;

    // If a custom avatar URL is provided, use it
    if (!empty($custom_avatar_url)) {
        echo '<img src="' . esc_url($custom_avatar_url) . '" class="' . esc_attr($class_str) . '" alt="Avatar" style="height: ' . esc_attr($size) . 'px" />';
    } else {
        // Otherwise, fallback to the Gravatar avatar
        $author_id = get_the_author_meta('ID');
        $avatar = get_avatar($author_id, $size, '', 'Avatar', ['class' => $class_str]);
        echo $avatar;
    }
}

/* ########################################################
 *                    Excerpts / Resumos
 * ######################################################## */

// Substitui o "[...]" no final dos excerpts por "..."
function minimalista_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'minimalista_excerpt_more' );

// Função adicional para ajustar o comprimento do excerpt, se necessário
function minimalista_excerpt_length( $length ) {
    return 20; // Define o número de palavras no excerpt
}
add_filter( 'excerpt_length', 'minimalista_excerpt_length', 999 );


/* ########################################################
 *                    Related Posts
 * ######################################################## */

 //echo do_shortcode('[display-posts post_type="post" taxonomy="tag" exclude_current="true" template="dps-widget-bs" wrapper="ul" wrapper_class="list-group" posts_per_page="' . $posts_per_page . '"]');


/**
 * Display Related Posts by Tags
 *
 * This function displays related posts based on the tags of the current post.
 * It excludes the current post from the related posts list and allows for a
 * customizable number of posts to be displayed. Additional parameters allow
 * for custom templates and wrappers to be used.
 *
 * @param int $posts_per_page Number of related posts to display. Default is 5.
 * @param string $template The template to use for displaying posts.
 * @param string $wrapper The HTML wrapper for the posts.
 * @param string $wrapper_class The CSS class for the wrapper.
 * @return void
 */
function minimalista_display_related_posts_by_tags($posts_per_page = 5, $template = '', $wrapper = 'ul', $wrapper_class = 'list-group') {
    global $post;
    $current_post_id = $post->ID;
    $tags = wp_get_post_tags($current_post_id);
    $tag_ids = array();

    if ($tags) {
        foreach ($tags as $tag) {
            $tag_ids[] = $tag->term_id;
        }
    }

    if (!empty($tag_ids)) {
        $args = array(
            'tag__in' => $tag_ids,
            'post__not_in' => array($current_post_id),
            'posts_per_page' => $posts_per_page
        );

        $related_posts = new WP_Query($args);

        if ($related_posts->have_posts()) {
            echo '<div class="related-posts my-3">';
            echo '<h5>Você também pode gostar de:</h5>';
            
            $shortcode = sprintf(
                '[display-posts post_type="post" taxonomy="tag" exclude_current="true" posts_per_page="%d" template="%s" wrapper="%s" wrapper_class="%s"]',
                $posts_per_page,
                esc_attr($template),
                esc_attr($wrapper),
                esc_attr($wrapper_class)
            );

            echo do_shortcode($shortcode);
            echo '</div>';
        }

        wp_reset_postdata();
    }
}


