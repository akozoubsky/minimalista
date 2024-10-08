<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses minimalista_header_style()
 */
function minimalista_custom_header_setup() {
	add_theme_support(
		'custom-header',
		apply_filters(
			'minimalista_custom_header_args',
			array(
				'default-image'      => '',
				'default-text-color' => '000000',
				'width'              => 1920, // Ajustado para 1920px para monitores de alta resolução
				'height'             => 300,  // Ajustado conforme necessário
				'flex-height'        => true,
				'wp-head-callback'   => 'minimalista_header_style',
			)
		)
	);
}
add_action( 'after_setup_theme', 'minimalista_custom_header_setup' );

if ( ! function_exists( 'minimalista_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see minimalista_custom_header_setup().
	 */
	function minimalista_header_style() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
			?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px); /* Visually hide text but keep it accessible to screen readers */
				clip-path: inset(50%); /* Modern method to visually hide text but keep it accessible to screen readers */
				white-space: nowrap; /* Prevent text from wrapping */
				overflow: hidden; /* Ensure no overflow is visible */
				}
			<?php
			// If the user has set a custom color for the text use that.
		else :
			?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
endif;
