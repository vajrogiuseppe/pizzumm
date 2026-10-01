<?php
/**
 * Template generico delle pagine.
 *
 * @package Pizzumm
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<section class="page-hero">
		<div class="container text-center">
			<h1 data-split><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="lead" style="margin-inline:auto" data-anim><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="section section--paper section--tight">
			<div class="container">
				<?php the_post_thumbnail( 'pizzumm-wide', array( 'style' => 'border-radius:var(--r-lg)' ) ); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php pizzumm_render_editor_content(); ?>

	<?php
endwhile;

get_footer();
