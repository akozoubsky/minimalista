<article <?php hybrid_attr( 'post' ); ?>>

	<?php if ( is_attachment() ) : // If viewing a single attachment. ?>

		<header class="entry-header alignwide">

			<h1 <?php hybrid_attr( 'entry-title' ); ?>><?php single_post_title(); ?></h1>

			<div class="entry-byline">
				<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
				<?php if ( comments_open() ) : ?>
					<?php comments_popup_link( number_format_i18n( 0 ), number_format_i18n( 1 ), '%', 'comments-link', '' ); ?>
				<?php endif; ?>
				<?php edit_post_link(); ?>
			</div><!-- .entry-byline -->

		</header><!-- .entry-header -->

		<?php $content = get_the_content(); ?>

		<?php if ( !empty( $content ) ) :  ?>

			<div class="entry-content">
				<?php hybrid_attachment(); // Function for handling non-image attachments. ?>
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div><!-- .entry-content -->

		<?php endif  ?>

		<!-- Prints HTML with meta information for taxonomies. -->
		<footer class="entry-footer default-max-width">
			<?php hybrid_post_terms( array( 'taxonomy' => 'category', 'text' => __( 'Categorizado como: %s', 'lightcms' ) ) ); ?>
			<?php hybrid_post_terms( array( 'taxonomy' => 'post_tag', 'text' => __( 'Marcado como: %s', 'lightcms' ), 'before' => '' ) ); ?>
		</footer><!-- .entry-footer -->

	<?php else : // If not viewing a single attachment. ?>

		<?php get_the_image( array( 'size' => 'large-16-9', 'order' => array( 'featured', 'attachment' ), 'image_class' => 'post-thumbnail' ) ); ?>

		<header class="entry-header alignwide">

			<?php the_title( '<h2 ' . hybrid_get_attr( 'entry-title default-max-width' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h2>' ); ?>

			<div class="entry-byline">
				<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
				<?php if ( comments_open() ) : ?>
					<?php comments_popup_link( number_format_i18n( 0 ), number_format_i18n( 1 ), '%', 'comments-link', '' ); ?>
				<?php endif; ?>
				<?php edit_post_link(); ?>
			</div><!-- .entry-byline -->

		</header><!-- .entry-header -->

		<?php $excerpt = get_the_excerpt(); ?>

		<?php if ( !empty( $excerpt ) ) :  ?>

			<div <?php hybrid_attr( 'entry-summary' ); ?>>
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->

		<?php endif  ?>

		<!-- Prints HTML with meta information for taxonomies. -->
		<footer class="entry-footer default-max-width">
			<?php //hybrid_post_terms( array( 'taxonomy' => 'category', 'text' => __( 'Categorizado como: %s', 'lightcms' ) ) ); ?>
			<?php //hybrid_post_terms( array( 'taxonomy' => 'post_tag', 'text' => __( 'Marcado como: %s', 'lightcms' ), 'before' => '' ) ); ?>
		</footer><!-- .entry-footer -->

	<?php endif; // End single attachment check. ?>

</article><!-- .entry -->