<?php
/**
 * Template part for displaying posts in the Status format.
 *
 * This template is designed to display short status updates, similar to those found on social media platforms like Twitter.
 *
 * @package LightCMSBootstrap
 * @version 1.0.0
 * @author Alexandre Kozoubsky
 *
 * @link https://mdbootstrap.com/docs/standard/extended/news-feed/
 */
?>

<div class="lcb-bg-light-gray border rounded-3 w-100">
    
    <section class="d-flex justify-content-center rounded-3 px-5 py-4">
	
		<!--Section: Newsfeed-->
		<section>

		  <div class="card" style="max-width: 42rem">
			<div class="card-body">
			  <!-- Data -->
			  <div class="d-flex mb-3">
				<a href="" class="">
				  <?php display_custom_author_avatar('',40,''); ?>
				</a>
				<div>
				  <a href="" class="text-dark mb-0">
					<span class="post-author fw-bold"><?php echo get_the_author() ?></span>
				  </a>
				  <a href="" class="text-muted d-block" style="margin-top: -6px">
					<small><?php display_time_since_posted(); ?></small>
				  </a>
				</div>
			  </div>
			  <!-- Description -->

				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</div>

			<!-- Media -->
			<div class="bg-image hover-overlay ripple rounded-0" data-mdb-ripple-color="light">
				<?php
			 	 // @link https://github.com/justintadlock/get-the-image
				/*
				get_the_image(array(
					'size' => 'large-16-9',
					'image_scan' => true,
					'image_class' => 'post-thumbnail w-100'
				));
				*/
				?>
				<a href="#!">
					<div class="mask" style="background-color: rgba(251, 251, 251, 0.2)"></div>
				</a>
			</div>
		 
		  </div>

		</section>

	</section>
	
</div>
<!--Section: Newsfeed-->

