<?php
/**
 * Fallback: elenco degli articoli.
 *
 * @package Pizzumm
 */

get_header();
?>

<section class="page-hero">
	<div class="container text-center">
		<h1 data-split>
			<?php
			if ( is_home() && ! is_front_page() ) {
				single_post_title();
			} elseif ( is_archive() ) {
				the_archive_title();
			} elseif ( is_search() ) {
				/* translators: %s: termine cercato. */
				printf( esc_html__( 'Risultati per “%s”', 'pizzumm' ), esc_html( get_search_query() ) );
			} else {
				esc_html_e( 'Novità', 'pizzumm' );
			}
			?>
		</h1>
	</div>
</section>

<section class="section section--paper">
	<div class="container">
		<?php if ( have_posts() ) : ?>

			<ul class="rounds" style="grid-template-columns:repeat(auto-fit,minmax(260px,1fr));align-items:start" data-anim-group>
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<li>
						<a class="round" href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'round__img', 'loading' => 'lazy' ) ); ?>
							<?php endif; ?>
							<span class="round__name"><?php the_title(); ?></span>
							<span class="round__count"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16, '…' ) ); ?></span>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>

			<div style="margin-top:3rem">
				<?php
				the_posts_pagination( array(
					'prev_text' => __( 'Precedenti', 'pizzumm' ),
					'next_text' => __( 'Successivi', 'pizzumm' ),
				) );
				?>
			</div>

		<?php else : ?>
			<p class="lead"><?php esc_html_e( 'Nessun contenuto trovato.', 'pizzumm' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
