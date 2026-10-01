<?php
/**
 * Pagina 404.
 *
 * @package Pizzumm
 */

get_header();
?>

<section class="page-hero">
	<div class="container container--narrow text-center">
		<span class="kicker" data-anim><?php esc_html_e( 'ops', 'pizzumm' ); ?></span>
		<h1 data-split><?php esc_html_e( 'Questa fetta non c’è più.', 'pizzumm' ); ?></h1>
		<p class="lead" style="margin-inline:auto" data-anim><?php esc_html_e( 'La pagina che cercavi è stata spostata o non è mai esistita. Torna al menu: lì trovi sempre qualcosa di buono.', 'pizzumm' ); ?></p>

		<div class="btn-row btn-row--center" data-anim data-anim-delay="0.1">
			<?php
			pizzumm_button( __( 'Scopri il menu', 'pizzumm' ), pizzumm_page_url( 'menu' ), 'yellow' );
			pizzumm_button( __( 'Torna alla home', 'pizzumm' ), home_url( '/' ), 'ghost' );
			?>
		</div>
	</div>
</section>

<?php
get_footer();
