<?php
/**
 * minimalista Theme Customizer
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function minimalista_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'minimalista_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'minimalista_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'minimalista_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function minimalista_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function minimalista_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function minimalista_customize_preview_js() {
	wp_enqueue_script( 'minimalista-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'minimalista_customize_preview_js' );

/* alexk - incluido em 25/06/2024 */
function minimalista_customize_logo_size( $wp_customize ) {
    // Adiciona a seção de ajuste do logotipo
    $wp_customize->add_section( 'minimalista_logo_section', array(
        'title'       => __( 'Logo Settings', 'minimalista' ),
        'priority'    => 30,
        'description' => 'Adjust the logo size.',
    ) );

    // Adiciona a configuração para o tamanho do logotipo
    $wp_customize->add_setting( 'minimalista_logo_width', array(
        'default'   => 100,
        'transport' => 'refresh',
    ) );

    // Adiciona o controle para o tamanho do logotipo
    $wp_customize->add_control( 'minimalista_logo_width_control', array(
        'label'    => __( 'Logo Width (px)', 'minimalista' ),
        'section'  => 'minimalista_logo_section',
        'settings' => 'minimalista_logo_width',
        'type'     => 'number',
    ) );
}
add_action( 'customize_register', 'minimalista_customize_logo_size' );

// Função para adicionar a classe 'navbar-brand' e 'aria-label' ao link do logo personalizado
function minimalista_add_attributes_to_custom_logo($html) {
    // Obtém o título do site
    $site_title = get_bloginfo('name', 'display');

    // Verifica se o logo existe
    if (strpos($html, 'custom-logo-link') !== false) {
        // Adiciona a classe 'navbar-brand' e o atributo 'aria-label' ao link
        $html = str_replace('custom-logo-link', 'custom-logo-link navbar-brand me-0 me-lg-2', $html);
        $html = str_replace('<a ', '<a aria-label="' . esc_attr($site_title) . '" ', $html);
    }
    return $html;
}
add_filter('get_custom_logo', 'minimalista_add_attributes_to_custom_logo');


