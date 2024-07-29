<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
		
<header class="navbar navbar-expand-lg navbar-dark bg-primary">
	<nav class="container flex-wrap flex-lg-nowrap" aria-label="Main navigation">
		<div class="d-lg-none" style="width: 4.25rem;"></div>

			<!-- <a class="navbar-brand me-lg-2" href="<?php echo esc_url( home_url( '/' ) ); ?>"> -->

			<?php
			if ( has_custom_logo() ) {
				$logo_width = get_theme_mod( 'minimalista_logo_width', 100 );
				echo '<style>.custom-logo-link { max-width: ' . esc_attr( $logo_width ) . 'px; height: auto;}</style>';
				the_custom_logo();
			} else { ?>
				<span class="navbar-brand site-title me-0 me-lg-2"><?php echo esc_html( get_bloginfo( 'name' ) ) ?></span><?php ;
			}
			?>


			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<?php
					wp_nav_menu( array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						//'menu_class'     => 'navbar-nav me-auto mb-2 mb-lg-0',
						'menu_class'     => 'navbar-nav me-auto',
						'fallback_cb'    => '__return_false',
						'depth'          => 2,
						'walker'         => new Bootstrap_Nav_Walker(),
					) );
				?>
			</div>


		</div>
	</nav>

	<?php if ( get_header_image() ) : ?>
		<div class="">
			<div class="custom-header">
				<img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
			</div>
		</div>
	<?php endif; ?>
</header>

<div id="content" class="site-content">