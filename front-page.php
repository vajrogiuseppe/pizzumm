<?php
/**
 * Home page — copy del brief, impaginazione "insegna da pizzeria".
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

$pizzumm_hero   = pizzumm_hero_config();
$pizzumm_video  = 'video' === $pizzumm_hero['type'] ? $pizzumm_hero['video'] : '';
$pizzumm_poster = $pizzumm_hero['poster'];
$pizzumm_image  = $pizzumm_hero['image'];
$pizzumm_show_crumbs = in_array( $pizzumm_hero['type'], array( 'current', 'logo' ), true );
?>

<!-- 1. HERO -------------------------------------------------------------- -->
<section class="hero">
	<div class="container">
		<div class="hero__grid">

			<div>
				<span class="kicker" data-anim><?php echo esc_html( pizzumm_text( 'home_hero_kicker', 'Pizza in teglia a Pompei' ) ); ?></span>
				<h1 data-split><?php echo esc_html( pizzumm_text( 'home_hero_title', 'Lo street food a Pompei ha 2.000 anni. Noi gli abbiamo dato una teglia nuova.' ) ); ?></h1>
				<p class="lead" data-anim data-anim-delay="0.15"><?php echo esc_html( pizzumm_text( 'home_hero_text', 'Pizza in teglia leggera, croccante e pronta quando lo sei tu. Scegli la tua fetta e goditela, senza troppi giri.' ) ); ?></p>

				<div class="btn-row" data-anim data-anim-delay="0.25">
					<?php
					pizzumm_button( __( 'Scopri il menu', 'pizzumm' ), pizzumm_page_url( 'menu' ), 'yellow' );
					pizzumm_button( __( 'Vieni a trovarci', 'pizzumm' ), pizzumm_page_url( 'contatti' ), 'ghost' );
					?>
				</div>
			</div>

			<div class="hero__pizza hero__pizza--<?php echo esc_attr( $pizzumm_hero['type'] ); ?>" data-anim data-anim-delay="0.1">
				<?php if ( $pizzumm_video ) : ?>
					<video src="<?php echo esc_url( $pizzumm_video ); ?>"
						poster="<?php echo esc_url( $pizzumm_poster ); ?>"
						autoplay muted loop playsinline
						style="width:min(100%,560px);aspect-ratio:1;object-fit:cover;border-radius:50%"
						aria-label="<?php esc_attr_e( 'Pizza in teglia appena sfornata', 'pizzumm' ); ?>"></video>
				<?php else : ?>
					<img src="<?php echo esc_url( $pizzumm_image ); ?>"
						alt="<?php echo 'logo' === $pizzumm_hero['type'] ? esc_attr__( 'Pizzumm — una pizza per fetta', 'pizzumm' ) : esc_attr__( 'Pizza in teglia appena sfornata, tagliata a fette', 'pizzumm' ); ?>"
						width="1100" height="1100" fetchpriority="high" decoding="async" />
				<?php endif; ?>

				<?php if ( $pizzumm_show_crumbs ) : ?>
					<img class="hero__crumb hero__crumb--1" src="<?php echo esc_url( pizzumm_image( 'home_crumb_1', 'img/brand/pizzumm-1.jpg' ) ); ?>" alt="" width="300" height="300" loading="lazy" />
					<img class="hero__crumb hero__crumb--2" src="<?php echo esc_url( pizzumm_image( 'home_crumb_2', 'img/brand/pizzumm-2.jpg' ) ); ?>" alt="" width="300" height="300" loading="lazy" />
					<img class="hero__crumb hero__crumb--3" src="<?php echo esc_url( pizzumm_image( 'home_crumb_3', 'img/brand/pizzumm-3.jpg' ) ); ?>" alt="" width="300" height="300" loading="lazy" />
				<?php endif; ?>
			</div>

		</div>

		<ul class="hero__facts" data-anim-group>
			<li class="fact">
				<?php pizzumm_the_icon( 'slice' ); ?>
				<span>
					<strong><?php esc_html_e( 'Una pizza per fetta', 'pizzumm' ); ?></strong>
					<span><?php esc_html_e( 'Scegli con gli occhi, paghi quello che prendi.', 'pizzumm' ); ?></span>
				</span>
			</li>
			<li class="fact">
				<?php pizzumm_the_icon( 'wheat' ); ?>
				<span>
					<strong><?php esc_html_e( 'Impasto contemporaneo', 'pizzumm' ); ?></strong>
					<span><?php esc_html_e( 'Leggero fuori, croccante sotto.', 'pizzumm' ); ?></span>
				</span>
			</li>
			<li class="fact">
				<?php pizzumm_the_icon( 'scooter' ); ?>
				<span>
					<strong><?php esc_html_e( 'Pizzumm dove vuoi', 'pizzumm' ); ?></strong>
					<span><?php esc_html_e( 'A casa, in ufficio o con gli amici, con Glovo.', 'pizzumm' ); ?></span>
				</span>
			</li>
		</ul>
	</div>
</section>

<!-- Nastro --------------------------------------------------------------- -->
<div class="ticker" aria-hidden="true">
	<div class="ticker__track">
		<?php for ( $i = 0; $i < 2; $i++ ) : ?>
			<div class="ticker__group">
				<span>Una pizza per fetta</span><?php pizzumm_the_icon( 'sparkle' ); ?>
				<span>Pizza in teglia</span><?php pizzumm_the_icon( 'sparkle' ); ?>
				<span>Nel cuore di Pompei</span><?php pizzumm_the_icon( 'sparkle' ); ?>
				<span>Facile, veloce, Pizzumm</span><?php pizzumm_the_icon( 'sparkle' ); ?>
				<span>Ordina e paga dal tavolo</span><?php pizzumm_the_icon( 'sparkle' ); ?>
			</div>
		<?php endfor; ?>
	</div>
</div>

<!-- 2. DOPPIA CARD: MENU / GLOVO ----------------------------------------- -->
<section class="section section--paper">
	<div class="container">
		<div class="split">

			<div class="split__media" data-anim>
				<div class="split__pair">
					<img src="<?php echo esc_url( pizzumm_image( 'home_duo_1', 'img/brand/pizzumm-5.jpg' ) ); ?>"
						alt="<?php esc_attr_e( 'Fette di pizza in teglia sul banco', 'pizzumm' ); ?>"
						width="1200" height="900" loading="lazy" decoding="async" />
					<img src="<?php echo esc_url( pizzumm_image( 'home_duo_2', 'img/brand/pizzumm-6.jpg' ) ); ?>"
						alt="<?php esc_attr_e( 'Fette di pizza pronte per la consegna', 'pizzumm' ); ?>"
						width="1200" height="900" loading="lazy" decoding="async" />
				</div>
			</div>

			<div>
				<span class="kicker" data-anim><?php echo esc_html( pizzumm_text( 'home_card1_kicker', 'Scegli la tua fetta' ) ); ?></span>
				<h2 class="section__title" data-split><?php echo esc_html( pizzumm_text( 'home_duo_title', 'Il menu, e Pizzumm dove vuoi.' ) ); ?></h2>
				<p class="lead" data-anim><?php echo esc_html( pizzumm_text( 'home_card1_text', 'Classica, speciale o la voglia del momento: guarda tutte le proposte e trova la tua Pizzumm.' ) ); ?></p>
				<p class="lead" data-anim data-anim-delay="0.05"><?php echo esc_html( pizzumm_text( 'home_card2_text', 'A casa, in ufficio o con gli amici. Scegli le tue fette e lascia che la teglia arrivi da te.' ) ); ?></p>

				<ul class="perks" data-anim data-anim-delay="0.1">
					<li><?php pizzumm_the_icon( 'arrow' ); ?><?php esc_html_e( 'Classiche e speciali', 'pizzumm' ); ?></li>
					<li><?php pizzumm_the_icon( 'arrow' ); ?><?php esc_html_e( 'Proposte di stagione', 'pizzumm' ); ?></li>
					<li><?php pizzumm_the_icon( 'arrow' ); ?><?php esc_html_e( 'Asporto e domicilio', 'pizzumm' ); ?></li>
					<li><?php pizzumm_the_icon( 'arrow' ); ?><?php esc_html_e( 'Ordine dal tavolo', 'pizzumm' ); ?></li>
				</ul>

				<div class="btn-row" data-anim data-anim-delay="0.15">
					<?php
					pizzumm_button( __( 'Scopri il menu', 'pizzumm' ), pizzumm_page_url( 'menu' ), 'red' );
					pizzumm_glovo_button( __( 'Ordina su', 'pizzumm' ), 'ghost' );
					?>
				</div>
			</div>

		</div>
	</div>
</section>

<?php
// Categorie del menu, dai contenuti reali del CMS.
// Sezione categorie nascosta per ora: si riattiva col filtro pizzumm_show_home_categories.
$pizzumm_cats = apply_filters( 'pizzumm_show_home_categories', false ) ? pizzumm_menu_categories() : array();

if ( $pizzumm_cats ) :
	?>
	<section class="section section--cream">
		<div class="container">
			<div class="section__head section__head--center">
				<span class="kicker" data-anim><?php echo esc_html( pizzumm_text( 'home_cat_kicker', 'Una pizza per fetta' ) ); ?></span>
				<h2 class="section__title" data-split><?php echo esc_html( pizzumm_text( 'home_cat_title', 'Scegli da dove partire' ) ); ?></h2>
			</div>

			<ul class="rounds" data-stories data-anim-group>
				<?php foreach ( $pizzumm_cats as $pizzumm_cat ) : ?>
					<li>
						<a class="round" href="<?php echo esc_url( pizzumm_page_url( 'menu' ) . '#cat-' . $pizzumm_cat->slug ); ?>">
							<img class="round__img" src="<?php echo esc_url( pizzumm_category_image( $pizzumm_cat, true ) ); ?>"
								alt="<?php echo esc_attr( $pizzumm_cat->name ); ?>"
								width="300" height="300" loading="lazy" decoding="async" />
							<span class="round__name"><?php echo esc_html( $pizzumm_cat->name ); ?></span>
							<span class="round__count">
								<?php
								printf(
									/* translators: %d: numero di prodotti. */
									esc_html( _n( '%d proposta', '%d proposte', $pizzumm_cat->count, 'pizzumm' ) ),
									absint( $pizzumm_cat->count )
								);
								?>
							</span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
<?php endif; ?>

<!-- 3. ORDINE E PAGAMENTO DIGITALE --------------------------------------- -->
<section class="section section--red">
	<div class="container">
		<div class="section__head section__head--center">
			<span class="kicker" data-anim><?php echo esc_html( pizzumm_text( 'home_step_kicker', 'Ordine smart' ) ); ?></span>
			<h2 class="section__title" data-split><?php echo esc_html( pizzumm_text( 'home_step_title', 'Tu scegli. Noi facciamo in fretta.' ) ); ?></h2>
			<p class="lead" style="margin-inline:auto" data-anim><?php echo esc_html( pizzumm_text( 'home_step_text', 'Se mangi da Pizzumm, puoi ordinare e pagare in digitale direttamente dal tavolo. Meno attese, più tempo per goderti ogni fetta.' ) ); ?></p>
		</div>

		<ul class="steps" data-anim-group>
			<?php
			$pizzumm_steps = array(
				array( __( 'Scansiona', 'pizzumm' ), __( 'Inquadra il codice che trovi sul tavolo.', 'pizzumm' ) ),
				array( __( 'Scegli', 'pizzumm' ), __( 'Sfoglia il menu e componi la tua selezione di fette.', 'pizzumm' ) ),
				array( __( 'Ordina e paga', 'pizzumm' ), __( 'Confermi, paghi dal telefono e resti seduto.', 'pizzumm' ) ),
			);

			foreach ( $pizzumm_steps as $pizzumm_i => $pizzumm_step ) :
				?>
				<li class="step">
					<span class="step__num"><?php echo esc_html( $pizzumm_i + 1 ); ?></span>
					<h3><?php echo esc_html( $pizzumm_step[0] ); ?></h3>
					<p><?php echo esc_html( $pizzumm_step[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>

		<p class="text-center" style="margin-top:clamp(2rem,4vw,3rem)" data-anim>
			<span class="kicker" style="font-size:clamp(1.6rem,1.3rem + 1.4vw,2.4rem)">
				<?php echo esc_html( pizzumm_text( 'home_step_closing', 'Facile, veloce, Pizzumm.' ) ); ?>
			</span>
		</p>
	</div>
</section>

<!-- 4. ANTEPRIMA DELLA STORIA -------------------------------------------- -->
<section class="section section--paper">
	<div class="container">
		<div class="split">
			<div class="split__media" data-anim>
				<img src="<?php echo esc_url( pizzumm_image( 'home_story', 'img/brand/affresco.jpg' ) ); ?>"
					alt="<?php esc_attr_e( 'Affresco della Regio IX con la focaccia piatta, Pompei', 'pizzumm' ); ?>"
					width="656" height="492" loading="lazy" decoding="async" />
			</div>

			<div>
				<span class="kicker" data-anim><?php echo esc_html( pizzumm_text( 'home_story_kicker', 'Da Pompei, con un gusto nuovo' ) ); ?></span>
				<h2 class="section__title" data-split><?php echo esc_html( pizzumm_text( 'home_story_title', 'Una storia iniziata duemila anni fa. Più o meno.' ) ); ?></h2>
				<p class="lead" data-anim><?php echo esc_html( pizzumm_text( 'home_story_text', 'Nell’antica Pompei, i thermopolia erano luoghi in cui fermarsi per mangiare e bere fuori casa. Nel 2023, in Regio IX, un affresco ha riportato alla luce l’immagine di una focaccia piatta che ricorda, da lontano, l’antenata della pizza. Pizzumm nasce da qui: dal desiderio di portare quel rito nel presente con una pizza in teglia leggera, croccante e fatta per essere condivisa.' ) ); ?></p>

				<div class="btn-row" data-anim data-anim-delay="0.1">
					<?php pizzumm_button( __( 'Scopri la nostra storia', 'pizzumm' ), pizzumm_page_url( 'chi-siamo' ), 'red' ); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- CTA finale ------------------------------------------------------------ -->
<section class="section section--cream">
	<div class="container">
		<?php
		pizzumm_cta_band(
			pizzumm_text( 'home_cta_title', 'Hai già scelto? La parte facile inizia adesso.' ),
			pizzumm_text( 'home_cta_text', 'Ordina su Glovo per ricevere Pizzumm dove vuoi, oppure vieni a scegliere le tue fette direttamente al banco.' )
		);
		?>
	</div>
</section>

<?php
get_footer();
