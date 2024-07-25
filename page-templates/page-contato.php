<?php
/**
 * Template Name: Página de Contato V.1
 *
 * The template for sending contact forms.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package minimalista
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); ?>

<div class="container">
	<div class="row">
		<div class="col-lg-8">
			<main id="primary" class="site-main">
			
				<?php
				while (have_posts()) :
					// Exibe o conteúdo da página.
					the_post();

					get_template_part('template-parts/content', 'page');

				endwhile; // End of the loop.
				?>

				<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="contact-form">
					
					<input type="hidden" name="action" value="custom_send_contact_form">

					<?php wp_nonce_field('custom_contact_form_nonce', 'custom_contact_form_nonce_field'); ?>

					<div class="form-group">
						<label for="name">Nome completo (obrigatório)</label>
						<input type="text" id="name" name="name" class="form-control" minlength="6" required>
					</div>

					<div class="form-group">
						<label for="email">E-mail (obrigatório)</label>
						<input type="email" id="email" name="email" class="form-control" required>
					</div>

					<div class="form-group">
						<label for="telefone_fixo">Telefone fixo</label>
						<input type="text" id="telefone_fixo" name="telefone_fixo" class="form-control" pattern="\([0-9]{2}\) [0-9]{4}-[0-9]{4}" placeholder="(12) 3456-7890">
					</div>

					<div class="form-group">
						<label for="celular">Celular</label>
						<input type="text" id="celular" name="celular" class="form-control" pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}" placeholder="(12) 98765-4321">
					</div>

					<div class="form-group">
						<label for="assunto">Assunto (obrigatório)</label>
						<input type="text" id="assunto" name="assunto" class="form-control" minlength="6" required>
					</div>

					<div class="form-group">
						<label for="message">Mensagem (obrigatório)</label>
						<textarea id="message" name="message" class="form-control" minlength="20" rows="6" required></textarea>
					</div>
					
					<button type="submit" class="btn btn-primary">Enviar</button>

				</form>

			</main><!-- #main -->
			
		</div>

		<!-- Right Sidebar Column -->
		<?php get_sidebar(); // Include the sidebar.php file ?>
	</div>
</div>

<?php
get_footer();
?>
