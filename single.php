<?php
/**
 * Template del singolo articolo / prodotto del menu.
 *
 * @package Pizzumm
 */

get_header();

while ( have_posts() ) :
	the_post();

	$pizzumm_is_product = 'pizzumm_prodotto' === get_post_type();
	$pizzumm_prezzo     = $pizzumm_is_product ? get_post_meta( get_the_ID(), 'pizzumm_prezzo', true ) : '';
	$pizzumm_ing        = $pizzumm_is_product ? get_post_meta( get_the_ID(), 'pizzumm_ingredienti', true ) : '';
	$pizzumm_all        = $pizzumm_is_product ? get_post_meta( get_the_ID(), 'pizzumm_allergeni', true ) : '';
	?>

	<section class="page-hero">
		<div class="container text-center">
			<?php if ( $pizzumm_is_product ) : ?>
				<span class="kicker" data-anim><?php esc_html_e( 'dal nostro menu', 'pizzumm' ); ?></span>
			<?php endif; ?>

			<h1 data-split><?php the_title(); ?></h1>

			<?php if ( $pizzumm_ing ) : ?>
				<p class="lead" style="margin-inline:auto" data-anim><?php echo esc_html( $pizzumm_ing ); ?></p>
			<?php endif; ?>

			<?php if ( $pizzumm_prezzo ) : ?>
				<p class="t-sign" style="font-size:var(--fs-2xl);color:var(--giallo);margin:.4em 0 0" data-anim>
					<?php echo esc_html( $pizzumm_prezzo ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $pizzumm_is_product ) : ?>
				<div class="btn-row btn-row--center" data-anim data-anim-delay="0.1">
					<?php
					pizzumm_glovo_button( __( 'Ordina su', 'pizzumm' ), 'yellow' );
					pizzumm_button( __( 'Torna al menu', 'pizzumm' ), pizzumm_page_url( 'menu' ), 'ghost' );
					?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="section section--paper section--tight">
			<div class="container container--narrow">
				<?php the_post_thumbnail( 'pizzumm-wide', array( 'style' => 'border-radius:var(--r-lg)' ) ); ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="entry">
		<div class="container">
			<div class="entry__content">
				<?php the_content(); ?>

				<?php if ( $pizzumm_all ) : ?>
					<p class="allergen-note">
						<?php pizzumm_the_icon( 'info' ); ?>
						<span><strong><?php esc_html_e( 'Allergeni:', 'pizzumm' ); ?></strong> <?php echo esc_html( $pizzumm_all ); ?></span>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="container" style="padding-bottom:4rem">';
		comments_template();
		echo '</div>';
	}

endwhile;

get_footer();
