<?php
/**
 * Template Name: Pizzumm — Contatti
 * Template Post Type: page
 *
 * Questo template è usato solo come fallback quando la pagina non ha contenuto
 * Gutenberg. Il modulo contatti è gestito da Contact Form 7: inserisci lo
 * shortcode [contact-form-7 id="..."] nel contenuto della pagina (o modifica
 * l'ID sotto). Il resto della pagina si compone dai block pattern del tema.
 *
 * @package Pizzumm
 */

get_header();

if ( pizzumm_is_builder_page() ) {
	while ( have_posts() ) {
		the_post();
		pizzumm_render_editor_content();
	}
	get_footer();
	return;
}

$pizzumm_cf7_id = (int) apply_filters( 'pizzumm_default_cf7_id', 0 );
?>

<section class="page-hero">
	<div class="container text-center">
		<span class="kicker" data-anim><?php echo esc_html( pizzumm_text( 'contact_kicker', 'Nel cuore di Pompei' ) ); ?></span>
		<h1 data-split><?php echo esc_html( pizzumm_text( 'contact_title', 'Ci vediamo da Pizzumm.' ) ); ?></h1>
		<p class="lead" style="margin-inline:auto" data-anim><?php echo esc_html( pizzumm_text( 'contact_text', 'Passa a scegliere la tua fetta, ordina per l’asporto o ricevila dove vuoi con Glovo.' ) ); ?></p>
	</div>
</section>

<section class="section section--cream" id="scrivici">
	<div class="container">
		<div class="contact-grid">

			<div class="panel" data-anim>
				<span class="kicker"><?php esc_html_e( 'Parliamone', 'pizzumm' ); ?></span>
				<h2 class="section__title" style="font-size:var(--fs-2xl)"><?php echo esc_html( pizzumm_text( 'contact_form_title', 'Scrivici' ) ); ?></h2>
				<p class="lead" style="margin-bottom:2rem"><?php echo esc_html( pizzumm_text( 'contact_form_text', 'Hai una domanda? Lasciaci un messaggio e ti risponderemo appena possibile.' ) ); ?></p>

				<?php if ( pizzumm_has_cf7() && $pizzumm_cf7_id ) : ?>
					<?php echo do_shortcode( '[contact-form-7 id="' . (int) $pizzumm_cf7_id . '"]' ); ?>
				<?php elseif ( pizzumm_has_cf7() ) : ?>
					<p class="alert alert--err" role="alert">
						<?php pizzumm_the_icon( 'alert' ); ?>
						<span>
							<?php esc_html_e( 'Configura il modulo: crealo in Contatto → Moduli e inserisci lo shortcode nella pagina, oppure imposta l\'ID via filter pizzumm_default_cf7_id.', 'pizzumm' ); ?>
						</span>
					</p>
				<?php else : ?>
					<p class="alert alert--err" role="alert">
						<?php pizzumm_the_icon( 'alert' ); ?>
						<span><?php esc_html_e( 'Contact Form 7 non è attivo. Installalo e attivalo per abilitare il modulo contatti.', 'pizzumm' ); ?></span>
					</p>
				<?php endif; ?>
			</div>

			<div data-anim>
				<span class="kicker"><?php esc_html_e( 'Dove e quando', 'pizzumm' ); ?></span>
				<h2 class="section__title" style="font-size:var(--fs-2xl)"><?php esc_html_e( 'Il locale', 'pizzumm' ); ?></h2>
				<?php echo do_shortcode( '[pizzumm_info_locale]' ); ?>
			</div>

		</div>
	</div>
</section>

<section class="section section--paper section--tight">
	<div class="container">
		<?php
		pizzumm_section_head(
			pizzumm_text( 'contact_map_kicker', 'Come raggiungerci' ),
			pizzumm_text( 'contact_map_title', 'Pizzumm è qui.' ),
			pizzumm_text( 'contact_map_text', 'Piazza Vittorio Veneto, 11, Pompei. Apri la mappa e raggiungici.' ),
			'split'
		);

		echo do_shortcode( '[pizzumm_mappa]' );
		?>
	</div>
</section>

<?php
get_footer();
