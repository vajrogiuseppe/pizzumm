<?php
/**
 * Template Name: Pizzumm — Chi siamo
 * Template Post Type: page
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
?>

<!-- 1. APERTURA --------------------------------------------------------- -->
<section class="page-hero">
	<div class="container text-center">
		<span class="kicker" data-anim><?php echo esc_html( pizzumm_text( 'about_kicker', 'Pizzumm nasce a Pompei' ) ); ?></span>
		<h1 data-split><?php echo esc_html( pizzumm_text( 'about_title', 'Un rito antico, un modo contemporaneo di stare insieme.' ) ); ?></h1>
		<p class="lead" style="margin-inline:auto" data-anim><?php echo esc_html( pizzumm_text( 'about_text', 'Pizzumm unisce un rito antico e un modo contemporaneo di stare insieme: pizza in teglia, servizio smart e tutta la libertà di scegliere una fetta alla volta.' ) ); ?></p>
	</div>
</section>

<!-- 2-5. IL RACCONTO ---------------------------------------------------- -->
<section class="section section--cream">
	<div class="container">
		<div class="timeline">

			<article class="timeline__item" data-anim>
				<div>
					<span class="timeline__index">1</span>
					<h2 class="timeline__title" data-split><?php echo esc_html( pizzumm_text( 'about_1_title', 'Prima dello street food come lo conosciamo, c’erano i thermopolia.' ) ); ?></h2>
					<p class="lead"><?php echo esc_html( pizzumm_text( 'about_1_text', 'Nelle strade dell’antica Pompei, i thermopolia erano punti di ristoro in cui si servivano cibi e bevande. Luoghi pratici, frequentati e pieni di vita: ci si fermava, si mangiava fuori casa e poi si ripartiva. Un’abitudine sorprendentemente vicina al nostro modo di vivere la città.' ) ); ?></p>
				</div>
				<div class="timeline__media">
					<img src="<?php echo esc_url( pizzumm_image( 'about_1', 'img/thermopolium.svg' ) ); ?>"
						alt="<?php esc_attr_e( 'Illustrazione di un thermopolium pompeiano con il bancone e i dolia', 'pizzumm' ); ?>"
						width="1000" height="1250" loading="lazy" decoding="async" />
				</div>
			</article>

			<article class="timeline__item" data-anim>
				<div>
					<span class="timeline__index">2</span>
					<h2 class="timeline__title" data-split><?php echo esc_html( pizzumm_text( 'about_2_title', 'Una focaccia che ha fatto il giro del mondo.' ) ); ?></h2>
					<p class="lead"><?php echo esc_html( pizzumm_text( 'about_2_text', 'Nel 2023, gli scavi della Regio IX hanno restituito una natura morta con una focaccia piatta usata come base per frutti e condimenti. Non era una pizza come la intendiamo oggi, ma il richiamo è inevitabile. Ed è proprio in quello spazio tra storia e immaginazione che prende forma Pizzumm.' ) ); ?></p>
				</div>
				<div class="timeline__media">
					<img src="<?php echo esc_url( pizzumm_image( 'about_2', 'img/brand/affresco.jpg' ) ); ?>"
						alt="<?php esc_attr_e( 'Illustrazione dell’affresco con la focaccia piatta della Regio IX', 'pizzumm' ); ?>"
						width="1200" height="900" loading="lazy" decoding="async" />
				</div>
			</article>

			<article class="timeline__item" data-anim>
				<div>
					<span class="timeline__index">3</span>
					<h2 class="timeline__title" data-split><?php echo esc_html( pizzumm_text( 'about_3_title', 'Il rito continua. Questa volta, in teglia.' ) ); ?></h2>
					<p class="lead"><?php echo esc_html( pizzumm_text( 'about_3_text', 'Oggi portiamo quella stessa idea di convivialità nel presente con un impasto contemporaneo, leggero e croccante. Una pizza da scegliere con gli occhi, mangiare al volo, portare via o condividere. Senza cerimonie, ma con molta attenzione a ciò che mettiamo in ogni fetta.' ) ); ?></p>
				</div>
				<div class="timeline__media">
					<img src="<?php echo esc_url( pizzumm_image( 'about_3', 'img/stock/menu-classiche.jpg' ) ); ?>"
						alt="<?php esc_attr_e( 'Fette di pizza in teglia sul tagliere', 'pizzumm' ); ?>"
						width="1200" height="900" loading="lazy" decoding="async" />
				</div>
			</article>

		</div>
	</div>
</section>

<!-- 5. PERCHÉ PIZZUMM --------------------------------------------------- -->
<section class="section section--dark">
	<div class="container container--narrow text-center">
		<div data-anim>
			<span class="kicker"><?php esc_html_e( 'Perché Pizzumm', 'pizzumm' ); ?></span>
			<h2 class="section__title"><?php echo esc_html( pizzumm_text( 'about_name_title', 'Un nome che sa di Pompei. E di primo morso.' ) ); ?></h2>
			<p class="lead" style="margin-inline:auto"><?php echo esc_html( pizzumm_text( 'about_name_text', 'Pizzumm gioca con le desinenze latine e con il suono più spontaneo davanti a qualcosa di buono: “umm”. Un nome che unisce territorio, gusto e ironia senza prendersi troppo sul serio.' ) ); ?></p>
		</div>
	</div>
</section>

<!-- CHIUSURA ------------------------------------------------------------ -->
<section class="section section--paper">
	<div class="container">
		<?php
		pizzumm_cta_band(
			pizzumm_text( 'about_cta_title', 'La storia l’hai letta. Ora assaggiala.' ),
			'',
			__( 'Il finale migliore', 'pizzumm' )
		);
		?>
	</div>
</section>

<?php
get_footer();
