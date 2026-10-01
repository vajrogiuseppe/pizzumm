<?php
/**
 * Sezioni del sito come shortcode.
 *
 * Ogni sezione delle pagine (Home, Chi siamo, Menu, Contatti) è esposta come
 * shortcode `[pizzumm_sec id="nome_sezione"]`. Il markup renderizzato è
 * IDENTICO a quello dei template PHP, quindi la grafica non cambia mai.
 *
 * Uso principale: Elementor. All'attivazione del tema, ogni pagina viene
 * pre-caricata in Elementor con una sequenza di widget "Shortcode", uno per
 * sezione. Il cliente può spostarli, duplicarli o rimuoverli dall'editor.
 *
 * Testi editabili via ACF (filter `pizzumm_text`), immagini via ACF
 * (`pizzumm_image`).
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elenco di tutte le sezioni disponibili con etichetta leggibile.
 * Usato dall'installer Elementor e per la validazione dello shortcode.
 *
 * @return array<string,string>
 */
function pizzumm_sections_index() {
	return array(
		// Home
		'home_hero'      => 'Home · Hero',
		'home_ticker'    => 'Home · Nastro',
		'home_duo'       => 'Home · Menu / Glovo',
		'home_categorie' => 'Home · Categorie',
		'home_steps'     => 'Home · Ordine smart',
		'home_storia'    => 'Home · Storia',
		'home_cta'       => 'Home · CTA finale',

		// Chi siamo
		'about_hero'     => 'Chi siamo · Apertura',
		'about_timeline' => 'Chi siamo · Timeline',
		'about_nome'     => 'Chi siamo · Perché Pizzumm',
		'about_cta'      => 'Chi siamo · CTA finale',

		// Menu
		'menu_hero'      => 'Menu · Apertura',
		'menu_grid'      => 'Menu · Categorie + prodotti',
		'menu_cta'       => 'Menu · CTA finale',

		// Contatti
		'contact_hero'   => 'Contatti · Apertura',
		'contact_grid'   => 'Contatti · Modulo + info',
		'contact_map'    => 'Contatti · Mappa',
		'contact_faq'    => 'Contatti · FAQ',
	);
}

/**
 * Shortcode master: [pizzumm_sec id="home_hero"]
 */
function pizzumm_sec_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'id' => '' ), $atts, 'pizzumm_sec' );
	$id   = sanitize_key( $atts['id'] );

	$fn = 'pizzumm_render_sec_' . $id;

	if ( ! function_exists( $fn ) ) {
		return '';
	}

	ob_start();
	$fn();

	return (string) ob_get_clean();
}
add_shortcode( 'pizzumm_sec', 'pizzumm_sec_shortcode' );

/* =========================================================================
 *  HOME
 * ======================================================================= */

function pizzumm_render_sec_home_hero() {
	$hero        = pizzumm_hero_config();
	$video       = 'video' === $hero['type'] ? $hero['video'] : '';
	$poster      = $hero['poster'];
	$image       = $hero['image'];
	$show_crumbs = in_array( $hero['type'], array( 'current', 'logo' ), true );
	?>
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

				<div class="hero__pizza hero__pizza--<?php echo esc_attr( $hero['type'] ); ?>" data-anim data-anim-delay="0.1">
					<?php if ( $video ) : ?>
						<video src="<?php echo esc_url( $video ); ?>" poster="<?php echo esc_url( $poster ); ?>"
							autoplay muted loop playsinline
							style="width:min(100%,560px);aspect-ratio:1;object-fit:cover;border-radius:50%"
							aria-label="<?php esc_attr_e( 'Pizza in teglia appena sfornata', 'pizzumm' ); ?>"></video>
					<?php else : ?>
						<img src="<?php echo esc_url( $image ); ?>"
							alt="<?php echo 'logo' === $hero['type'] ? esc_attr__( 'Pizzumm — una pizza per fetta', 'pizzumm' ) : esc_attr__( 'Pizza in teglia appena sfornata, tagliata a fette', 'pizzumm' ); ?>"
							width="1100" height="1100" fetchpriority="high" decoding="async" />
					<?php endif; ?>

					<?php if ( $show_crumbs ) : ?>
						<img class="hero__crumb hero__crumb--1" src="<?php echo esc_url( pizzumm_image( 'home_crumb_1', 'img/brand/pizzumm-1.jpg' ) ); ?>" alt="" width="300" height="300" loading="lazy" />
						<img class="hero__crumb hero__crumb--2" src="<?php echo esc_url( pizzumm_image( 'home_crumb_2', 'img/brand/pizzumm-2.jpg' ) ); ?>" alt="" width="300" height="300" loading="lazy" />
						<img class="hero__crumb hero__crumb--3" src="<?php echo esc_url( pizzumm_image( 'home_crumb_3', 'img/brand/pizzumm-3.jpg' ) ); ?>" alt="" width="300" height="300" loading="lazy" />
					<?php endif; ?>
				</div>
			</div>

			<ul class="hero__facts" data-anim-group>
				<li class="fact"><?php pizzumm_the_icon( 'slice' ); ?><span><strong><?php esc_html_e( 'Una pizza per fetta', 'pizzumm' ); ?></strong><span><?php esc_html_e( 'Scegli con gli occhi, paghi quello che prendi.', 'pizzumm' ); ?></span></span></li>
				<li class="fact"><?php pizzumm_the_icon( 'wheat' ); ?><span><strong><?php esc_html_e( 'Impasto contemporaneo', 'pizzumm' ); ?></strong><span><?php esc_html_e( 'Leggero fuori, croccante sotto.', 'pizzumm' ); ?></span></span></li>
				<li class="fact"><?php pizzumm_the_icon( 'scooter' ); ?><span><strong><?php esc_html_e( 'Pizzumm dove vuoi', 'pizzumm' ); ?></strong><span><?php esc_html_e( 'A casa, in ufficio o con gli amici, con Glovo.', 'pizzumm' ); ?></span></span></li>
			</ul>
		</div>
	</section>
	<?php
}

function pizzumm_render_sec_home_ticker() {
	?>
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
	<?php
}

function pizzumm_render_sec_home_duo() {
	?>
	<section class="section section--paper">
		<div class="container">
			<div class="split">
				<div class="split__media" data-anim>
					<div class="split__pair">
						<img src="<?php echo esc_url( pizzumm_image( 'home_duo_1', 'img/brand/pizzumm-5.jpg' ) ); ?>" alt="<?php esc_attr_e( 'Fette di pizza in teglia sul banco', 'pizzumm' ); ?>" width="1200" height="900" loading="lazy" decoding="async" />
						<img src="<?php echo esc_url( pizzumm_image( 'home_duo_2', 'img/brand/pizzumm-6.jpg' ) ); ?>" alt="<?php esc_attr_e( 'Fette di pizza pronte per la consegna', 'pizzumm' ); ?>" width="1200" height="900" loading="lazy" decoding="async" />
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
}

function pizzumm_render_sec_home_categorie() {
	// Nascosta per ora: si riattiva col filtro pizzumm_show_home_categories.
	$cats = apply_filters( 'pizzumm_show_home_categories', false ) ? pizzumm_menu_categories() : array();
	if ( ! $cats ) {
		return;
	}
	?>
	<section class="section section--cream">
		<div class="container">
			<div class="section__head section__head--center">
				<span class="kicker" data-anim><?php echo esc_html( pizzumm_text( 'home_cat_kicker', 'Una pizza per fetta' ) ); ?></span>
				<h2 class="section__title" data-split><?php echo esc_html( pizzumm_text( 'home_cat_title', 'Scegli da dove partire' ) ); ?></h2>
			</div>
			<ul class="rounds" data-stories data-anim-group>
				<?php foreach ( $cats as $cat ) : ?>
					<li>
						<a class="round" href="<?php echo esc_url( pizzumm_page_url( 'menu' ) . '#cat-' . $cat->slug ); ?>">
							<img class="round__img" src="<?php echo esc_url( pizzumm_category_image( $cat, true ) ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>" width="300" height="300" loading="lazy" decoding="async" />
							<span class="round__name"><?php echo esc_html( $cat->name ); ?></span>
							<span class="round__count"><?php printf( esc_html( _n( '%d proposta', '%d proposte', $cat->count, 'pizzumm' ) ), absint( $cat->count ) ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
}

function pizzumm_render_sec_home_steps() {
	?>
	<section class="section section--red">
		<div class="container">
			<div class="section__head section__head--center">
				<span class="kicker" data-anim><?php echo esc_html( pizzumm_text( 'home_step_kicker', 'Ordine smart' ) ); ?></span>
				<h2 class="section__title" data-split><?php echo esc_html( pizzumm_text( 'home_step_title', 'Tu scegli. Noi facciamo in fretta.' ) ); ?></h2>
				<p class="lead" style="margin-inline:auto" data-anim><?php echo esc_html( pizzumm_text( 'home_step_text', 'Se mangi da Pizzumm, puoi ordinare e pagare in digitale direttamente dal tavolo. Meno attese, più tempo per goderti ogni fetta.' ) ); ?></p>
			</div>
			<ul class="steps" data-anim-group>
				<?php
				$steps = array(
					array( __( 'Scansiona', 'pizzumm' ), __( 'Inquadra il codice che trovi sul tavolo.', 'pizzumm' ) ),
					array( __( 'Scegli', 'pizzumm' ), __( 'Sfoglia il menu e componi la tua selezione di fette.', 'pizzumm' ) ),
					array( __( 'Ordina e paga', 'pizzumm' ), __( 'Confermi, paghi dal telefono e resti seduto.', 'pizzumm' ) ),
				);
				foreach ( $steps as $i => $step ) : ?>
					<li class="step">
						<span class="step__num"><?php echo esc_html( $i + 1 ); ?></span>
						<h3><?php echo esc_html( $step[0] ); ?></h3>
						<p><?php echo esc_html( $step[1] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
			<p class="text-center" style="margin-top:clamp(2rem,4vw,3rem)" data-anim>
				<span class="kicker" style="font-size:clamp(1.6rem,1.3rem + 1.4vw,2.4rem)"><?php echo esc_html( pizzumm_text( 'home_step_closing', 'Facile, veloce, Pizzumm.' ) ); ?></span>
			</p>
		</div>
	</section>
	<?php
}

function pizzumm_render_sec_home_storia() {
	?>
	<section class="section section--paper">
		<div class="container">
			<div class="split">
				<div class="split__media" data-anim>
					<img src="<?php echo esc_url( pizzumm_image( 'home_story', 'img/brand/affresco.jpg' ) ); ?>" alt="<?php esc_attr_e( 'Fette di pizza in teglia pronte sul banco', 'pizzumm' ); ?>" width="1200" height="1500" loading="lazy" decoding="async" />
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
	<?php
}

function pizzumm_render_sec_home_cta() {
	?>
	<section class="section section--cream">
		<div class="container">
			<?php pizzumm_cta_band(
				pizzumm_text( 'home_cta_title', 'Hai già scelto? La parte facile inizia adesso.' ),
				pizzumm_text( 'home_cta_text', 'Ordina su Glovo per ricevere Pizzumm dove vuoi, oppure vieni a scegliere le tue fette direttamente al banco.' )
			); ?>
		</div>
	</section>
	<?php
}

/* =========================================================================
 *  CHI SIAMO
 * ======================================================================= */

function pizzumm_render_sec_about_hero() {
	?>
	<section class="page-hero">
		<div class="container text-center">
			<span class="kicker" data-anim><?php echo esc_html( pizzumm_text( 'about_kicker', 'Pizzumm nasce a Pompei' ) ); ?></span>
			<h1 data-split><?php echo esc_html( pizzumm_text( 'about_title', 'Un rito antico, un modo contemporaneo di stare insieme.' ) ); ?></h1>
			<p class="lead" style="margin-inline:auto" data-anim><?php echo esc_html( pizzumm_text( 'about_text', 'Pizzumm unisce un rito antico e un modo contemporaneo di stare insieme: pizza in teglia, servizio smart e tutta la libertà di scegliere una fetta alla volta.' ) ); ?></p>
		</div>
	</section>
	<?php
}

function pizzumm_render_sec_about_timeline() {
	$items = array(
		array( 'about_1_title', 'about_1_text', 'about_1', 'img/thermopolium.svg',        'Prima dello street food come lo conosciamo, c’erano i thermopolia.', 'Nelle strade dell’antica Pompei, i thermopolia erano punti di ristoro in cui si servivano cibi e bevande. Luoghi pratici, frequentati e pieni di vita: ci si fermava, si mangiava fuori casa e poi si ripartiva. Un’abitudine sorprendentemente vicina al nostro modo di vivere la città.', 'Illustrazione di un thermopolium pompeiano con il bancone e i dolia' ),
		array( 'about_2_title', 'about_2_text', 'about_2', 'img/brand/affresco.jpg',      'Una focaccia che ha fatto il giro del mondo.', 'Nel 2023, gli scavi della Regio IX hanno restituito una natura morta con una focaccia piatta usata come base per frutti e condimenti. Non era una pizza come la intendiamo oggi, ma il richiamo è inevitabile. Ed è proprio in quello spazio tra storia e immaginazione che prende forma Pizzumm.', 'Affresco della Regio IX con la focaccia piatta, Pompei' ),
		array( 'about_3_title', 'about_3_text', 'about_3', 'img/brand/banco-pizzumm.jpg', 'Il rito continua. Questa volta, in teglia.', 'Oggi portiamo quella stessa idea di convivialità nel presente con un impasto contemporaneo, leggero e croccante. Una pizza da scegliere con gli occhi, mangiare al volo, portare via o condividere. Senza cerimonie, ma con molta attenzione a ciò che mettiamo in ogni fetta.', 'Il banco di Pizzumm con le teglie appena sfornate' ),
	);
	?>
	<section class="section section--cream">
		<div class="container">
			<div class="timeline">
				<?php $n = 1; foreach ( $items as $it ) : ?>
					<article class="timeline__item" data-anim>
						<div>
							<span class="timeline__index"><?php echo (int) $n; ?></span>
							<h2 class="timeline__title" data-split><?php echo esc_html( pizzumm_text( $it[0], $it[4] ) ); ?></h2>
							<p class="lead"><?php echo esc_html( pizzumm_text( $it[1], $it[5] ) ); ?></p>
						</div>
						<div class="timeline__media">
							<img src="<?php echo esc_url( pizzumm_image( $it[2], $it[3] ) ); ?>" alt="<?php echo esc_attr( $it[6] ); ?>" width="1200" height="900" loading="lazy" decoding="async" />
						</div>
					</article>
				<?php $n++; endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

function pizzumm_render_sec_about_nome() {
	?>
	<section class="section section--dark">
		<div class="container container--narrow text-center">
			<div data-anim>
				<span class="kicker"><?php esc_html_e( 'Perché Pizzumm', 'pizzumm' ); ?></span>
				<h2 class="section__title"><?php echo esc_html( pizzumm_text( 'about_name_title', 'Un nome che sa di Pompei. E di primo morso.' ) ); ?></h2>
				<p class="lead" style="margin-inline:auto"><?php echo esc_html( pizzumm_text( 'about_name_text', 'Pizzumm gioca con le desinenze latine e con il suono più spontaneo davanti a qualcosa di buono: “umm”. Un nome che unisce territorio, gusto e ironia senza prendersi troppo sul serio.' ) ); ?></p>
			</div>
		</div>
	</section>
	<?php
}

function pizzumm_render_sec_about_cta() {
	?>
	<section class="section section--paper">
		<div class="container">
			<?php pizzumm_cta_band( pizzumm_text( 'about_cta_title', 'La storia l’hai letta. Ora assaggiala.' ), '', __( 'Il finale migliore', 'pizzumm' ) ); ?>
		</div>
	</section>
	<?php
}

/* =========================================================================
 *  MENU
 * ======================================================================= */

function pizzumm_render_sec_menu_hero() {
	?>
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
	<?php
}

function pizzumm_render_sec_menu_grid() {
	$cats = pizzumm_menu_categories();
	if ( ! $cats ) {
		echo '<section class="section section--paper"><div class="container"><p class="lead">' . esc_html__( 'Il menu è in aggiornamento. Aggiungi i prodotti da "Menu Pizzumm" nella bacheca di WordPress.', 'pizzumm' ) . '</p></div></section>';
		return;
	}
	?>
	<nav class="menu-nav" aria-label="<?php esc_attr_e( 'Categorie del menu', 'pizzumm' ); ?>">
		<div class="container menu-nav__inner">
			<?php foreach ( $cats as $cat ) : ?>
				<a href="#cat-<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></a>
			<?php endforeach; ?>
		</div>
	</nav>

	<div class="section--paper">
		<div class="container">
			<?php foreach ( $cats as $cat ) :
				$products = pizzumm_category_products( $cat );
				if ( ! $products ) continue; ?>
				<section class="menu-cat" id="cat-<?php echo esc_attr( $cat->slug ); ?>">
					<div class="menu-cat__head">
						<span class="kicker" data-anim><?php esc_html_e( 'il nostro menu', 'pizzumm' ); ?></span>
						<h2 class="menu-cat__title" data-split><?php echo esc_html( $cat->name ); ?></h2>
						<?php if ( $cat->description ) : ?>
							<p class="menu-cat__desc" data-anim><?php echo esc_html( $cat->description ); ?></p>
						<?php endif; ?>
					</div>
					<ul class="menu-list" data-anim-group>
						<?php foreach ( $products as $pizzumm_product ) {
							include PIZZUMM_DIR . '/template-parts/pizza-card.php';
						} ?>
					</ul>
				</section>
			<?php endforeach; ?>

			<p class="allergen-note" data-anim>
				<?php pizzumm_the_icon( 'info' ); ?>
				<span><?php esc_html_e( 'Alcuni prodotti possono contenere allergeni o essere lavorati in un laboratorio dove sono presenti glutine, latte, uova, frutta a guscio, soia e sesamo. Per informazioni dettagliate chiedi al banco: l’elenco completo degli ingredienti e degli allergeni è sempre disponibile.', 'pizzumm' ); ?></span>
			</p>
		</div>
	</div>
	<?php
}

function pizzumm_render_sec_menu_cta() {
	?>
	<section class="section section--cream">
		<div class="container">
			<?php pizzumm_cta_band(
				pizzumm_text( 'menu_cta_title', 'Hai già scelto? La parte facile inizia adesso.' ),
				pizzumm_text( 'menu_cta_text', 'Ordina su Glovo per ricevere Pizzumm dove vuoi, oppure vieni a scegliere le tue fette direttamente al banco.' ),
				__( 'Ci siamo', 'pizzumm' )
			); ?>
		</div>
	</section>
	<?php
}

/* =========================================================================
 *  CONTATTI
 * ======================================================================= */

function pizzumm_render_sec_contact_hero() {
	?>
	<section class="page-hero">
		<div class="container text-center">
			<span class="kicker" data-anim><?php echo esc_html( pizzumm_text( 'contact_kicker', 'Nel cuore di Pompei' ) ); ?></span>
			<h1 data-split><?php echo esc_html( pizzumm_text( 'contact_title', 'Ci vediamo da Pizzumm.' ) ); ?></h1>
			<p class="lead" style="margin-inline:auto" data-anim><?php echo esc_html( pizzumm_text( 'contact_text', 'Passa a scegliere la tua fetta, ordina per l’asporto o ricevila dove vuoi con Glovo.' ) ); ?></p>
		</div>
	</section>
	<?php
}

function pizzumm_render_sec_contact_grid() {
	$cf7_id = (int) apply_filters( 'pizzumm_default_cf7_id', 0 );
	?>
	<section class="section section--cream" id="scrivici">
		<div class="container">
			<div class="contact-grid">
				<div class="panel" data-anim>
					<span class="kicker"><?php esc_html_e( 'Parliamone', 'pizzumm' ); ?></span>
					<h2 class="section__title" style="font-size:var(--fs-2xl)"><?php echo esc_html( pizzumm_text( 'contact_form_title', 'Scrivici' ) ); ?></h2>
					<p class="lead" style="margin-bottom:2rem"><?php echo esc_html( pizzumm_text( 'contact_form_text', 'Hai una domanda? Lasciaci un messaggio e ti risponderemo appena possibile.' ) ); ?></p>
					<?php if ( pizzumm_has_cf7() && $cf7_id ) : ?>
						<?php echo do_shortcode( '[contact-form-7 id="' . (int) $cf7_id . '"]' ); ?>
					<?php else : ?>
						<p class="alert alert--err" role="alert"><?php pizzumm_the_icon( 'alert' ); ?><span><?php esc_html_e( 'Configura il modulo in Contatto → Moduli e inserisci lo shortcode.', 'pizzumm' ); ?></span></p>
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
	<?php
}

function pizzumm_render_sec_contact_map() {
	?>
	<section class="section section--paper section--tight">
		<div class="container">
			<?php pizzumm_section_head(
				pizzumm_text( 'contact_map_kicker', 'Come raggiungerci' ),
				pizzumm_text( 'contact_map_title', 'Pizzumm è qui.' ),
				pizzumm_text( 'contact_map_text', 'Piazza Vittorio Veneto, 11, Pompei. Apri la mappa e raggiungici.' ),
				'split'
			);
			echo do_shortcode( '[pizzumm_mappa]' ); ?>
		</div>
	</section>
	<?php
}

function pizzumm_render_sec_contact_faq() {
	$faq = array(
		array( pizzumm_text( 'faq_1_q', 'Posso ordinare dal tavolo?' ), pizzumm_text( 'faq_1_a', 'Sì. Nel locale puoi ordinare e pagare in digitale seguendo le indicazioni presenti sul tavolo.' ) ),
		array( pizzumm_text( 'faq_2_q', 'Fate consegna a domicilio?' ), pizzumm_text( 'faq_2_a', 'Sì. Puoi ordinare Pizzumm tramite Glovo.' ) ),
		array( pizzumm_text( 'faq_3_q', 'Posso ordinare da asporto?' ), pizzumm_text( 'faq_3_a', 'Sì. Verifica su Glovo le modalità disponibili oppure contattaci.' ) ),
	);
	?>
	<section class="section section--dark">
		<div class="container container--narrow">
			<?php pizzumm_section_head( __( 'Domande veloci', 'pizzumm' ), __( 'Le risposte in tre righe.', 'pizzumm' ), '', 'center' ); ?>
			<div class="faq" data-anim-group>
				<?php foreach ( $faq as $item ) : ?>
					<details><summary><?php echo esc_html( $item[0] ); ?></summary><div class="faq__a"><p><?php echo esc_html( $item[1] ); ?></p></div></details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}
