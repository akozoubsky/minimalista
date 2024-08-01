<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function minimalista_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'minimalista_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function minimalista_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'minimalista_pingback_header' );

/**
 * Determine the format of the featured image.
 * 
 * @param int $post_id Post ID.
 * @return string Format of the image (square, landscape, portrait).
 */
function get_featured_image_format($post_id = null)
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

/* ########################################################
 *                    Seguranca
 * ######################################################## */

/**
 * Change the base for author URLs and flush the rewrite rules if necessary.
 *
 * This function changes the base for author URLs from 'author' to 'perfil' and flushes the
 * rewrite rules to apply the changes if they are not already configured correctly.
 *
 * @global WP_Rewrite $wp_rewrite WordPress rewrite rules global.
 */
function minimalista_change_author_base() {
    global $wp_rewrite;
    $author_base = 'perfil';

    if ( $wp_rewrite->author_base !== $author_base ) {
        $wp_rewrite->author_base = $author_base;
        $wp_rewrite->flush_rules();
    }
}
add_action( 'init', 'minimalista_change_author_base' );

/**
 * Add disallow rules to the robots.txt file to prevent indexing of specified paths or file types.
 *
 * This function creates or updates the robots.txt file to include disallow rules for specified
 * paths or file types. It merges the provided array of paths with a default set of disallowed paths.
 *
 * @param array $disallowed_paths Array of paths or file types to disallow in the robots.txt file.
 */
function minimalista_add_robots_rule( $disallowed_paths = array() ) {
    // Ensure $disallowed_paths is always an array
    if ( !is_array( $disallowed_paths ) ) {
        $disallowed_paths = array();
    }

    // Caminhos padrão a serem desindexados
    $default_disallowed_paths = array(
        '/wp-admin/',
        '/wp-admin/admin-ajax.php',
        '/wp-includes/',
        '/wp-content/plugins/',
        '/wp-content/themes/',        
        '/wp-content/uploads',
        '/wp-content/files',
        '/wp-content/cache',
        '/trackback',
        '/wp-login.php',
        '/wp-signup.php',
        '/readme.html',
        '/license.txt',
        '/wp-config-sample.php',
        '/cgi-bin/',
        '/wp-content/cache/',
        '/wp-content/debug.log',
        '/search/',
        '/feed/',
        '/comments/feed/',
        '/wp-json/',
        '/author/',
        '/autor/',
        '/perfil/',
        '/writer/',
        '/*.php$',
        '/*.inc$',
        '/*.gz$',
        '/*.log$',
        '/*.sql$',
        '/*.tar$',
        '/*.zip$',
        '/*readme.txt$',
        '/*README.txt$',
        '/*readme.md$',
        '/*README.md$',
        '/*favicon.*$'
    );

    // Merge arrays
    $disallowed_paths = array_merge( $default_disallowed_paths, $disallowed_paths );

    // Verifica se o arquivo robots.txt existe
    if ( ! file_exists( ABSPATH . 'robots.txt' ) ) {
        // Cria o arquivo robots.txt se não existir
        $robots_file = fopen( ABSPATH . 'robots.txt', 'w' );
        $robots_content = "User-agent: *\n";
        foreach ( $disallowed_paths as $path ) {
            $robots_content .= "Disallow: $path\n";
        }
        fwrite( $robots_file, $robots_content );
        fclose( $robots_file );
    } else {
        // Verifica se o conteúdo já está presente no arquivo robots.txt
        $robots_content = file_get_contents( ABSPATH . 'robots.txt' );
        foreach ( $disallowed_paths as $path ) {
            if ( strpos( $robots_content, "Disallow: $path" ) === false ) {
                $robots_content .= "Disallow: $path\n";
            }
        }
        file_put_contents( ABSPATH . 'robots.txt', $robots_content );
    }
}
//add_action( 'init', 'minimalista_add_robots_rule' );

/**
 * Chamar a função minimalista_add_robots_rule no tema ou em um plugin e passar um array adicional de caminhos que deseja desindexar.
 *
 */
/* 
add_action( 'init', function() {
    $extra_disallowed_paths = array(
        '/author/',
        '/perfil/',
    );
    minimalista_add_robots_rule( $extra_disallowed_paths );
});
*/

/** @TODO A FUNCAO NAO ESTAH COMPLETA. TEM BUG.
 * Adiciona regras ao arquivo .htaccess para configurar o servidor Apache.
 *
 * Esta função cria ou atualiza o arquivo .htaccess para incluir regras especificadas.
 * Ela mescla o array fornecido de regras com um conjunto padrão de regras.
 * 
 * Add disallow rules to the .htaccess file to configure the Apache server.
 *
 * This function creates or updates the .htaccess file to include specified rules.
 * It merges the provided array of rules with a default set of rules.
 *
 * @param array $rules Array de regras para adicionar ao arquivo .htaccess.
 */
function minimalista_add_htaccess_rules( $rules = array() ) {
    // Ensure $rules is always an array
    if ( ! is_array( $rules ) ) {
        $rules = array();
    }

    // Default rules to be added to the .htaccess
    $default_rules = array(
        "# Bloquear o acesso aos arquivos de configuração do WordPress",
        "<FilesMatch \"^wp-config.php$\">",
        "    Order deny,allow",
        "    Deny from all",
        "</FilesMatch>",
        "",
        "# Bloquear o acesso aos arquivos de log",
        "<FilesMatch \"\\.log$\">",
        "    Order deny,allow",
        "    Deny from all",
        "</FilesMatch>",
        "",
        "# Bloquear o acesso aos arquivos de banco de dados",
        "<FilesMatch \"\\.(sql|tar|zip|gz)$\">",
        "    Order deny,allow",
        "    Deny from all",
        "</FilesMatch>",
        "",
        "# Bloquear o acesso a arquivos readme e markdown",
        "<FilesMatch \"(?i)readme\\.(txt|md)$\">",
        "    Order deny,allow",
        "    Deny from all",
        "</FilesMatch>",
        "",
        "# Bloquear o acesso a arquivos HTML específicos",
        "<FilesMatch \"readme\\.html$\">",
        "    Order deny,allow",
        "    Deny from all",
        "</FilesMatch>",
        "",
        "# Bloquear o acesso a arquivos favicon",
        "<FilesMatch \"(?i)favicon\\.(ico|png|gif|jpg)$\">",
        "    Order deny,allow",
        "    Deny from all",
        "</FilesMatch>",
        "",
    );

    // Merge arrays
    $rules = array_merge( $default_rules, $rules );

    // Path to the .htaccess file
    $htaccess_path = ABSPATH . '.htaccess';

    // Check if the .htaccess file exists
    if ( file_exists( $htaccess_path ) ) {
        // Read the existing .htaccess content
        $htaccess_content = file_get_contents( $htaccess_path );

        // Check if each rule already exists before adding
        foreach ( $rules as $rule ) {
            $rule = trim( $rule ); // Remove whitespace from the rule
            if ( ! empty( $rule ) && strpos( $htaccess_content, $rule ) === false ) {
                // Add the rule only if it doesn't exist
                $htaccess_content .= "\n" . $rule;
            }
        }
    } else {
        // Create the .htaccess file with the new rules
        $htaccess_content = implode( "\n", $rules ) . "\n";
    }

    // Write the updated content to the .htaccess file
    file_put_contents( $htaccess_path, $htaccess_content );
}
//add_action( 'init', 'minimalista_add_htaccess_rules' );

/**
 * Chamar a função minimalista_add_htaccess_rules no tema ou em um plugin e passar um array adicional de regras.
 *
 */
/* 
add_action( 'init', function() {
    $extra_rules = array(
        '# Bloquear o acesso ao diretório wp-content/uploads',
        '<Directory "/wp-content/uploads">',
        '    Order deny,allow',
        '    Deny from all',
        '</Directory>',
    );
    minimalista_add_htaccess_rules( $extra_rules );
});
*/

/**
 * Impede que os usuários usem seu username como display name.
 *
 * @param int $user_id ID do usuário.
 */
function minimalista_prevent_username_as_display_name($user_id) {
    $user_info = get_userdata($user_id);
    $username = $user_info->user_login;
    $display_name = $user_info->display_name;

    if ($username === $display_name) {
        // Gera um novo display name seguro
        $new_display_name = $user_info->first_name . ' ' . $user_info->last_name;

        if (empty(trim($new_display_name))) {
            $new_display_name = 'User' . $user_id;
        }

        wp_update_user(array(
            'ID' => $user_id,
            'display_name' => $new_display_name
        ));
    }
}
add_action('user_register', 'minimalista_prevent_username_as_display_name');
add_action('profile_update', 'minimalista_prevent_username_as_display_name');

/**
 * Verifica e previne que o usuário use o username como display name no perfil.
 *
 * @param WP_Error $errors Objeto de erros do WordPress.
 * @param bool $update Se é uma atualização de perfil.
 * @param stdClass $user Dados do usuário.
 */
function minimalista_validate_display_name($errors, $update, $user) {
    if ($update && $user->user_login === $user->display_name) {
        $errors->add('display_name_error', __('You may not use your username to be displayed publicly (display name). Please choose another one.', 'minimalista'));
    }
}
add_action('user_profile_update_errors', 'minimalista_validate_display_name', 10, 3);