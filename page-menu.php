<?php
/**
 * Template Name: Pizzumm — Menu
 * Template Post Type: page
 *
 * Pagina Menu: apertura, categorie con prodotti, allergeni e chiusura.
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

$pizzumm_cats = pizzumm_menu_categories();
?>

<!-- 1. APERTURA --------------------------------------------------------- -->
<section class="page-hero">
	<div class="container text-center">
		<span class="kicker" data-anim><?php echo esc_html( pizzumm_text( 'menu_kicker', 'Una pizza per fetta' ) ); ?></span>
		<h1 data-split><?php echo esc_html( pizzumm_text( 'menu_title', 'Scegli la fetta. O lascia che sia lei a scegliere te.' ) ); ?></h1>
		<p class="lead" style="margin-inline:auto" data-anim><?php echo esc_html( pizzumm_text( 'menu_text', 'Classiche, speciali e proposte del momento. Guarda, scegli e ricomincia: da Pizzumm ogni fetta è un invito alla prossima.' ) ); ?></p>
		<div class="btn-row btn-row--center" data-anim data-anim-delay="0.1">
			<?php pizzumm_glovo_button( __( 'Ordina su', 'pizzumm' ), 'yellow' ); ?>
		</div>
	</div>
</section>

<?php if ( $pizzumm_cats ) : ?>

	<nav class="menu-nav" aria-label="<?php esc_attr_e( 'Categorie del menu', 'pizzumm' ); ?>">
		<div class="container menu-nav__inner">
			<?php foreach ( $pizzumm_cats as $pizzumm_cat ) : ?>
				<a href="#cat-<?php echo esc_attr( $pizzumm_cat->slug ); ?>"><?php echo esc_html( $pizzumm_cat->name ); ?></a>
			<?php endforeach; ?>
		</div>
	</nav>

	<div class="section--paper">
		<div class="container">
			<?php
			foreach ( $pizzumm_cats as $pizzumm_cat ) :
				$pizzumm_products = pizzumm_category_products( $pizzumm_cat );

				if ( ! $pizzumm_products ) {
					continue;
				}
				?>
				<section class="menu-cat" id="cat-<?php echo esc_attr( $pizzumm_cat->slug ); ?>">
					<div class="menu-cat__head">
						<span class="kicker" data-anim><?php esc_html_e( 'il nostro menu', 'pizzumm' ); ?></span>
						<h2 class="menu-cat__title" data-split><?php echo esc_html( $pizzumm_cat->name ); ?></h2>
						<?php if ( $pizzumm_cat->description ) : ?>
							<p class="menu-cat__desc" data-anim><?php echo esc_html( $pizzumm_cat->description ); ?></p>
						<?php endif; ?>
					</div>

					<ul class="menu-list" data-anim-group>
						<?php
						foreach ( $pizzumm_products as $pizzumm_product ) {
							include PIZZUMM_DIR . '/template-parts/pizza-card.php';
						}
						?>
					</ul>
				</section>
			<?php endforeach; ?>

			<p class="allergen-note" data-anim>
				<?php pizzumm_the_icon( 'info' ); ?>
				<span>
					<?php esc_html_e( 'Alcuni prodotti possono contenere allergeni o essere lavorati in un laboratorio dove sono presenti glutine, latte, uova, frutta a guscio, soia e sesamo. Per informazioni dettagliate chiedi al banco: l’elenco completo degli ingredienti e degli allergeni è sempre disponibile.', 'pizzumm' ); ?>
				</span>
			</p>
		</div>
	</div>

<?php else : ?>

	<section class="section section--paper">
		<div class="container">
			<p class="lead"><?php esc_html_e( 'Il menu è in aggiornamento. Aggiungi i prodotti da “Menu Pizzumm” nella bacheca di WordPress.', 'pizzumm' ); ?></p>
		</div>
	</section>

<?php endif; ?>

<!-- 3. CHIUSURA MENU ---------------------------------------------------- -->
<section class="section section--cream">
	<div class="container">
		<?php
		pizzumm_cta_band(
			pizzumm_text( 'menu_cta_title', 'Hai già scelto? La parte facile inizia adesso.' ),
			pizzumm_text( 'menu_cta_text', 'Ordina su Glovo per ricevere Pizzumm dove vuoi, oppure vieni a scegliere le tue fette direttamente al banco.' ),
			__( 'Ci siamo', 'pizzumm' )
		);
		?>
	</div>
</section>

<?php
get_footer();
