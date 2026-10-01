<?php
/**
 * Pattern per l'editor a blocchi.
 *
 * Riproducono le sezioni del tema usando blocchi standard di WordPress con le
 * classi del tema: chi modifica la pagina cambia i testi da Gutenberg e la
 * grafica resta quella. Appena una pagina contiene blocchi, il tema mostra il
 * contenuto dell'editor al posto del layout predefinito (vedi inc/page-builders.php).
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra categoria e pattern.
 */
function pizzumm_register_patterns() {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	register_block_pattern_category( 'pizzumm', array( 'label' => __( 'Pizzumm', 'pizzumm' ) ) );

	foreach ( pizzumm_patterns() as $slug => $pattern ) {
		register_block_pattern( 'pizzumm/' . $slug, array(
			'title'       => $pattern['title'],
			'description' => isset( $pattern['description'] ) ? $pattern['description'] : '',
			'categories'  => array( 'pizzumm' ),
			'content'     => $pattern['content'],
		) );
	}
}
add_action( 'init', 'pizzumm_register_patterns', 20 );

/**
 * Sopratitolo corsivo del tema.
 *
 * @param string $text  Testo.
 * @param bool   $center Centrato.
 * @return string
 */
function pizzumm_pattern_kicker( $text, $center = false ) {
	$align = $center ? '"align":"center",' : '';
	$class = $center ? 'has-text-align-center kicker' : 'kicker';

	return '<!-- wp:paragraph {' . $align . '"className":"kicker"} -->' . "\n"
		. '<p class="' . $class . '">' . $text . '</p>' . "\n"
		. '<!-- /wp:paragraph -->' . "\n";
}

/**
 * Pulsante del tema.
 *
 * @param string $label   Testo.
 * @param string $url     URL.
 * @param string $variant Classe extra (es. btn--ghost).
 * @return string
 */
function pizzumm_pattern_button( $label, $url, $variant = '' ) {
	$class = trim( 'btn ' . $variant );

	return '<!-- wp:button {"className":"' . $class . '"} -->' . "\n"
		. '<div class="wp-block-button ' . $class . '"><a class="wp-block-button__link wp-element-button" href="'
		. esc_url( $url ) . '">' . $label . '</a></div>' . "\n"
		. '<!-- /wp:button -->' . "\n";
}

/**
 * Definizione dei pattern.
 *
 * @return array<string,array{title:string,description?:string,content:string}>
 */
function pizzumm_patterns() {
	$menu     = pizzumm_page_url( 'menu' );
	$contatti = pizzumm_page_url( 'contatti' );
	$storia   = pizzumm_page_url( 'chi-siamo' );
	$glovo    = pizzumm_glovo_url();
	$img      = pizzumm_asset( 'img/stock/' );

	$patterns = array();

	/* ------------------------------------------------------------------ */
	$patterns['hero'] = array(
		'title'       => __( '01 · Hero rosso con pizza', 'pizzumm' ),
		'description' => __( 'Apertura su fondo rosso: sopratitolo, titolo grande, testo, due pulsanti e immagine tonda.', 'pizzumm' ),
		'content'     => '<!-- wp:group {"className":"hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group hero">
<!-- wp:columns {"verticalAlignment":"center","className":"hero__grid"} -->
<div class="wp-block-columns are-vertically-aligned-center hero__grid">
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
' . pizzumm_pattern_kicker( 'Pizza in teglia a Pompei' ) . '<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Lo street food a Pompei ha 2.000 anni. Noi gli abbiamo dato una teglia nuova.</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"lead"} -->
<p class="lead">Pizza in teglia leggera, croccante e pronta quando lo sei tu. Scegli la tua fetta e goditela, senza troppi giri.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"className":"btn-row"} -->
<div class="wp-block-buttons btn-row">
' . pizzumm_pattern_button( 'Scopri il menu', $menu )
  . pizzumm_pattern_button( 'Vieni a trovarci', $contatti, 'btn--ghost' ) . '</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","className":"hero__pizza"} -->
<div class="wp-block-column is-vertically-aligned-center hero__pizza">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . esc_url( $img . 'round-hero.jpg' ) . '" alt="Pizza in teglia appena sfornata"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->',
	);

	/* ------------------------------------------------------------------ */
	$patterns['split'] = array(
		'title'       => __( '02 · Immagini e testo con elenco', 'pizzumm' ),
		'description' => __( 'Due foto affiancate e, a destra, sopratitolo, titolo, testo, elenco di vantaggi e pulsanti.', 'pizzumm' ),
		'content'     => '<!-- wp:group {"className":"section section--paper","layout":{"type":"constrained"}} -->
<div class="wp-block-group section section--paper">
<!-- wp:columns {"verticalAlignment":"center","className":"split"} -->
<div class="wp-block-columns are-vertically-aligned-center split">
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:columns {"className":"split__pair"} -->
<div class="wp-block-columns split__pair">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . esc_url( $img . 'menu-classiche.jpg' ) . '" alt="Fette di pizza in teglia sul banco"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . esc_url( $img . 'menu-diavola.jpg' ) . '" alt="Fette di pizza pronte per la consegna"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
' . pizzumm_pattern_kicker( 'Scegli la tua fetta' ) . '<!-- wp:heading -->
<h2 class="wp-block-heading">Il menu, e Pizzumm dove vuoi.</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"lead"} -->
<p class="lead">Classica, speciale o la voglia del momento: guarda tutte le proposte e trova la tua Pizzumm.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"className":"perks"} -->
<ul class="wp-block-list perks"><!-- wp:list-item --><li>Classiche e speciali</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Proposte di stagione</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Asporto e domicilio</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>Ordine dal tavolo</li><!-- /wp:list-item --></ul>
<!-- /wp:list -->
<!-- wp:buttons {"className":"btn-row"} -->
<div class="wp-block-buttons btn-row">
' . pizzumm_pattern_button( 'Scopri il menu', $menu )
  . pizzumm_pattern_button( 'Ordina su Glovo', $glovo, 'btn--ghost' ) . '</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->',
	);

	/* ------------------------------------------------------------------ */
	$patterns['steps'] = array(
		'title'       => __( '03 · Tre passaggi su fondo rosso', 'pizzumm' ),
		'description' => __( 'Sezione rossa con titolo centrato e tre schede numerate.', 'pizzumm' ),
		'content'     => '<!-- wp:group {"className":"section section--red","layout":{"type":"constrained"}} -->
<div class="wp-block-group section section--red">
<!-- wp:group {"className":"section__head section__head--center","layout":{"type":"constrained"}} -->
<div class="wp-block-group section__head section__head--center">
' . pizzumm_pattern_kicker( 'Ordine smart', true ) . '<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Tu scegli. Noi facciamo in fretta.</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","className":"lead"} -->
<p class="has-text-align-center lead">Se mangi da Pizzumm, puoi ordinare e pagare in digitale direttamente dal tavolo. Meno attese, più tempo per goderti ogni fetta.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:columns {"className":"steps"} -->
<div class="wp-block-columns steps">
<!-- wp:column {"className":"step"} -->
<div class="wp-block-column step">
<!-- wp:paragraph {"align":"center","className":"step__num"} --><p class="has-text-align-center step__num">1</p><!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center","level":3} --><h3 class="wp-block-heading has-text-align-center">Scansiona</h3><!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Inquadra il codice che trovi sul tavolo.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"className":"step"} -->
<div class="wp-block-column step">
<!-- wp:paragraph {"align":"center","className":"step__num"} --><p class="has-text-align-center step__num">2</p><!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center","level":3} --><h3 class="wp-block-heading has-text-align-center">Scegli</h3><!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Sfoglia il menu e componi la tua selezione di fette.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"className":"step"} -->
<div class="wp-block-column step">
<!-- wp:paragraph {"align":"center","className":"step__num"} --><p class="has-text-align-center step__num">3</p><!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center","level":3} --><h3 class="wp-block-heading has-text-align-center">Ordina e paga</h3><!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Confermi, paghi dal telefono e resti seduto.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->',
	);

	/* ------------------------------------------------------------------ */
	$patterns['storia'] = array(
		'title'       => __( '04 · Racconto con foto', 'pizzumm' ),
		'description' => __( 'Foto a sinistra e testo lungo a destra, per la storia del locale.', 'pizzumm' ),
		'content'     => '<!-- wp:group {"className":"section section--paper","layout":{"type":"constrained"}} -->
<div class="wp-block-group section section--paper">
<!-- wp:columns {"verticalAlignment":"center","className":"split"} -->
<div class="wp-block-columns are-vertically-aligned-center split">
<!-- wp:column {"verticalAlignment":"center","className":"split__media"} -->
<div class="wp-block-column is-vertically-aligned-center split__media">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="' . esc_url( $img . 'story-banco.jpg' ) . '" alt="Fette di pizza in teglia pronte sul banco"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
' . pizzumm_pattern_kicker( 'Da Pompei, con un gusto nuovo' ) . '<!-- wp:heading -->
<h2 class="wp-block-heading">Una storia iniziata duemila anni fa. Più o meno.</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"lead"} -->
<p class="lead">Nell’antica Pompei, i thermopolia erano luoghi in cui fermarsi per mangiare e bere fuori casa. Nel 2023, in Regio IX, un affresco ha riportato alla luce l’immagine di una focaccia piatta che ricorda, da lontano, l’antenata della pizza. Pizzumm nasce da qui: dal desiderio di portare quel rito nel presente con una pizza in teglia leggera, croccante e fatta per essere condivisa.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"className":"btn-row"} -->
<div class="wp-block-buttons btn-row">
' . pizzumm_pattern_button( 'Scopri la nostra storia', $storia ) . '</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->',
	);

	/* ------------------------------------------------------------------ */
	$patterns['cta'] = array(
		'title'       => __( '05 · CTA finale', 'pizzumm' ),
		'description' => __( 'Blocco rosso di chiusura con titolo, testo e due pulsanti.', 'pizzumm' ),
		'content'     => '<!-- wp:group {"className":"section section--cream","layout":{"type":"constrained"}} -->
<div class="wp-block-group section section--cream">
<!-- wp:group {"className":"cta-band","layout":{"type":"constrained"}} -->
<div class="wp-block-group cta-band">
' . pizzumm_pattern_kicker( 'Pizzumm ti aspetta', true ) . '<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Hai già scelto? La parte facile inizia adesso.</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","className":"lead"} -->
<p class="has-text-align-center lead">Ordina su Glovo per ricevere Pizzumm dove vuoi, oppure vieni a scegliere le tue fette direttamente al banco.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"className":"btn-row btn-row--center","layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons btn-row btn-row--center">
' . pizzumm_pattern_button( 'Ordina su Glovo', $glovo )
  . pizzumm_pattern_button( 'Come raggiungerci', $contatti, 'btn--ghost' ) . '</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
	);

	/* ------------------------------------------------------------------ */
	$patterns['page-hero'] = array(
		'title'       => __( '00 · Apertura pagina (kicker + titolo + testo)', 'pizzumm' ),
		'description' => __( 'Blocco di apertura centrato usato in cima alle pagine interne.', 'pizzumm' ),
		'content'     => '<!-- wp:group {"className":"page-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group page-hero">
' . pizzumm_pattern_kicker( 'Sopratitolo', true ) . '<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="wp-block-heading has-text-align-center">Titolo della pagina.</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","className":"lead"} -->
<p class="has-text-align-center lead">Breve testo introduttivo di apertura, editabile da Gutenberg.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
	);

	/* ------------------------------------------------------------------ */
	$patterns['timeline'] = array(
		'title'       => __( '07 · Timeline "Chi siamo"', 'pizzumm' ),
		'description' => __( 'Tre blocchi numerati con testo e immagine.', 'pizzumm' ),
		'content'     => '<!-- wp:group {"className":"section section--cream","layout":{"type":"constrained"}} -->
<div class="wp-block-group section section--cream">
<!-- wp:group {"className":"timeline","layout":{"type":"constrained"}} -->
<div class="wp-block-group timeline">
<!-- wp:shortcode -->
[pizzumm_timeline_item numero="1" titolo="Prima dello street food come lo conosciamo, c\'erano i thermopolia." immagine="' . esc_url( pizzumm_asset( 'img/thermopolium.svg' ) ) . '" alt="Illustrazione di un thermopolium pompeiano"]Nelle strade dell\'antica Pompei, i thermopolia erano punti di ristoro in cui si servivano cibi e bevande. Luoghi pratici, frequentati e pieni di vita: ci si fermava, si mangiava fuori casa e poi si ripartiva.[/pizzumm_timeline_item]
<!-- /wp:shortcode -->
<!-- wp:shortcode -->
[pizzumm_timeline_item numero="2" titolo="Una focaccia che ha fatto il giro del mondo." immagine="' . esc_url( pizzumm_asset( 'img/affresco-regio-ix.svg' ) ) . '" alt="Affresco della Regio IX"]Nel 2023, gli scavi della Regio IX hanno restituito una natura morta con una focaccia piatta usata come base per frutti e condimenti. Il richiamo alla pizza è inevitabile.[/pizzumm_timeline_item]
<!-- /wp:shortcode -->
<!-- wp:shortcode -->
[pizzumm_timeline_item numero="3" titolo="Il rito continua. Questa volta, in teglia." immagine="' . esc_url( $img . 'menu-classiche.jpg' ) . '" alt="Fette di pizza in teglia"]Oggi portiamo quella stessa idea di convivialità nel presente con un impasto contemporaneo, leggero e croccante. Una pizza da scegliere con gli occhi e condividere.[/pizzumm_timeline_item]
<!-- /wp:shortcode -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
	);

	/* ------------------------------------------------------------------ */
	$patterns['menu-completo'] = array(
		'title'       => __( '08 · Menu completo (dinamico)', 'pizzumm' ),
		'description' => __( 'Griglia con tutte le categorie e i prodotti del menu.', 'pizzumm' ),
		'content'     => '<!-- wp:shortcode -->
[pizzumm_menu_prodotti]
<!-- /wp:shortcode -->
<!-- wp:shortcode -->
[pizzumm_allergen_note]
<!-- /wp:shortcode -->',
	);

	/* ------------------------------------------------------------------ */
	$patterns['mappa'] = array(
		'title'       => __( '09 · Mappa Google', 'pizzumm' ),
		'description' => __( 'Mappa embed con banner consenso.', 'pizzumm' ),
		'content'     => '<!-- wp:group {"className":"section section--paper section--tight","layout":{"type":"constrained"}} -->
<div class="wp-block-group section section--paper section--tight">
' . pizzumm_pattern_kicker( 'Come raggiungerci' ) . '<!-- wp:heading -->
<h2 class="wp-block-heading">Pizzumm è qui.</h2>
<!-- /wp:heading -->
<!-- wp:shortcode -->
[pizzumm_mappa]
<!-- /wp:shortcode -->
</div>
<!-- /wp:group -->',
	);

	/* ------------------------------------------------------------------ */
	$patterns['info-locale'] = array(
		'title'       => __( '10 · Info del locale', 'pizzumm' ),
		'description' => __( 'Elenco contatti, orari e social, letti dalle impostazioni del tema.', 'pizzumm' ),
		'content'     => '<!-- wp:shortcode -->
[pizzumm_info_locale]
<!-- /wp:shortcode -->',
	);

	/* ------------------------------------------------------------------ */
	$patterns['contact-form'] = array(
		'title'       => __( '11 · Modulo contatti (Contact Form 7)', 'pizzumm' ),
		'description' => __( 'Sostituisci l\'ID con quello del tuo modulo (Contatto → Moduli).', 'pizzumm' ),
		'content'     => '<!-- wp:group {"className":"panel","layout":{"type":"constrained"}} -->
<div class="wp-block-group panel">
' . pizzumm_pattern_kicker( 'Parliamone' ) . '<!-- wp:heading -->
<h2 class="wp-block-heading">Scrivici</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"lead"} -->
<p class="lead">Hai una domanda? Lasciaci un messaggio e ti risponderemo appena possibile.</p>
<!-- /wp:paragraph -->
<!-- wp:shortcode -->
[contact-form-7 id="ID-DEL-TUO-MODULO" title="Contatti"]
<!-- /wp:shortcode -->
</div>
<!-- /wp:group -->',
	);

	/* ------------------------------------------------------------------ */
	$patterns['faq'] = array(
		'title'       => __( '06 · FAQ', 'pizzumm' ),
		'description' => __( 'Domande e risposte a fisarmonica su fondo scuro.', 'pizzumm' ),
		'content'     => '<!-- wp:group {"className":"section section--dark","layout":{"type":"constrained"}} -->
<div class="wp-block-group section section--dark">
<!-- wp:group {"className":"section__head section__head--center","layout":{"type":"constrained"}} -->
<div class="wp-block-group section__head section__head--center">
' . pizzumm_pattern_kicker( 'Domande veloci', true ) . '<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Le risposte in tre righe.</h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"faq","layout":{"type":"constrained"}} -->
<div class="wp-block-group faq">
<!-- wp:details --><details class="wp-block-details"><summary>Posso ordinare dal tavolo?</summary>
<!-- wp:paragraph --><p>Sì. Nel locale puoi ordinare e pagare in digitale seguendo le indicazioni presenti sul tavolo.</p><!-- /wp:paragraph -->
</details><!-- /wp:details -->
<!-- wp:details --><details class="wp-block-details"><summary>Fate consegna a domicilio?</summary>
<!-- wp:paragraph --><p>Sì. Puoi ordinare Pizzumm tramite Glovo.</p><!-- /wp:paragraph -->
</details><!-- /wp:details -->
<!-- wp:details --><details class="wp-block-details"><summary>Posso ordinare da asporto?</summary>
<!-- wp:paragraph --><p>Sì. Verifica su Glovo le modalità disponibili oppure contattaci.</p><!-- /wp:paragraph -->
</details><!-- /wp:details -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
	);

	return $patterns;
}

/**
 * Stili di blocco riutilizzabili, così le classi del tema si applicano
 * anche senza scrivere nulla a mano nell'editor.
 */
function pizzumm_register_block_styles() {
	if ( ! function_exists( 'register_block_style' ) ) {
		return;
	}

	register_block_style( 'core/paragraph', array(
		'name'  => 'pizzumm-kicker',
		'label' => __( 'Sopratitolo corsivo', 'pizzumm' ),
	) );
	register_block_style( 'core/paragraph', array(
		'name'  => 'pizzumm-lead',
		'label' => __( 'Testo introduttivo', 'pizzumm' ),
	) );
	register_block_style( 'core/group', array(
		'name'  => 'pizzumm-section-red',
		'label' => __( 'Sezione rossa', 'pizzumm' ),
	) );
	register_block_style( 'core/group', array(
		'name'  => 'pizzumm-section-dark',
		'label' => __( 'Sezione scura', 'pizzumm' ),
	) );
	register_block_style( 'core/group', array(
		'name'  => 'pizzumm-section-cream',
		'label' => __( 'Sezione crema', 'pizzumm' ),
	) );
	register_block_style( 'core/button', array(
		'name'  => 'pizzumm-btn',
		'label' => __( 'Pulsante Pizzumm', 'pizzumm' ),
	) );
	register_block_style( 'core/button', array(
		'name'  => 'pizzumm-btn-ghost',
		'label' => __( 'Pulsante contornato', 'pizzumm' ),
	) );
}
add_action( 'init', 'pizzumm_register_block_styles' );

/**
 * Contenuto Gutenberg predefinito per ciascuna pagina del sito.
 *
 * Restituisce l'HTML dei blocchi pronto per essere salvato in `post_content`.
 * Usato in due contesti:
 *  - all'attivazione del tema, per pre-popolare le nuove pagine;
 *  - dal pulsante "Rigenera contenuto Gutenberg" nel metabox layout, per
 *    ripristinare la composizione predefinita su una pagina esistente.
 *
 * @param string $slug Slug della pagina.
 * @return string
 */
function pizzumm_default_page_content( $slug ) {
	$patterns = pizzumm_patterns();
	$cf7_id   = (int) apply_filters( 'pizzumm_default_cf7_id', 0 );

	// Sostituisce il placeholder nel pattern del modulo con l'ID reale creato
	// dall'installer, se disponibile.
	if ( $cf7_id && isset( $patterns['contact-form']['content'] ) ) {
		$patterns['contact-form']['content'] = str_replace(
			'id="ID-DEL-TUO-MODULO"',
			'id="' . $cf7_id . '"',
			$patterns['contact-form']['content']
		);
	}

	$get = static function ( $key ) use ( $patterns ) {
		return isset( $patterns[ $key ]['content'] ) ? $patterns[ $key ]['content'] . "\n\n" : '';
	};

	switch ( $slug ) {
		case 'home':
			return $get( 'hero' )
				. '<!-- wp:shortcode -->
[pizzumm_home_facts]
<!-- /wp:shortcode -->

<!-- wp:shortcode -->
[pizzumm_ticker]
<!-- /wp:shortcode -->

'
				. $get( 'split' )
				. '<!-- wp:group {"className":"section section--cream","layout":{"type":"constrained"}} -->
<div class="wp-block-group section section--cream">
' . pizzumm_pattern_kicker( 'Una pizza per fetta', true ) . '<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Scegli da dove partire</h2>
<!-- /wp:heading -->
<!-- wp:shortcode -->
[pizzumm_categorie]
<!-- /wp:shortcode -->
</div>
<!-- /wp:group -->

'
				. $get( 'steps' )
				. $get( 'storia' )
				. $get( 'cta' );

		case 'chi-siamo':
			$hero = '<!-- wp:group {"className":"page-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group page-hero">
' . pizzumm_pattern_kicker( 'Pizzumm nasce a Pompei', true ) . '<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="wp-block-heading has-text-align-center">Un rito antico, un modo contemporaneo di stare insieme.</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","className":"lead"} -->
<p class="has-text-align-center lead">Pizzumm unisce un rito antico e un modo contemporaneo di stare insieme: pizza in teglia, servizio smart e tutta la libertà di scegliere una fetta alla volta.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

';

			return $hero . $get( 'timeline' ) . '<!-- wp:group {"className":"section section--dark","layout":{"type":"constrained"}} -->
<div class="wp-block-group section section--dark">
' . pizzumm_pattern_kicker( 'Perché Pizzumm', true ) . '<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Un nome che sa di Pompei. E di primo morso.</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","className":"lead"} -->
<p class="has-text-align-center lead">Pizzumm gioca con le desinenze latine e con il suono più spontaneo davanti a qualcosa di buono: "umm". Un nome che unisce territorio, gusto e ironia senza prendersi troppo sul serio.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

' . $get( 'cta' );

		case 'menu':
			$hero = '<!-- wp:group {"className":"page-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group page-hero">
' . pizzumm_pattern_kicker( 'Una pizza per fetta', true ) . '<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="wp-block-heading has-text-align-center">Scegli la fetta. O lascia che sia lei a scegliere te.</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","className":"lead"} -->
<p class="has-text-align-center lead">Classiche, speciali e proposte del momento. Guarda, scegli e ricomincia: da Pizzumm ogni fetta è un invito alla prossima.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

';

			return $hero . $get( 'menu-completo' ) . $get( 'cta' );

		case 'contatti':
			$hero = '<!-- wp:group {"className":"page-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group page-hero">
' . pizzumm_pattern_kicker( 'Nel cuore di Pompei', true ) . '<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="wp-block-heading has-text-align-center">Ci vediamo da Pizzumm.</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","className":"lead"} -->
<p class="has-text-align-center lead">Passa a scegliere la tua fetta, ordina per l\'asporto o ricevila dove vuoi con Glovo.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

';

			$grid = '<!-- wp:group {"className":"section section--cream","layout":{"type":"constrained"}} -->
<div class="wp-block-group section section--cream" id="scrivici">
<!-- wp:columns {"className":"contact-grid"} -->
<div class="wp-block-columns contact-grid">
<!-- wp:column -->
<div class="wp-block-column">
' . $patterns['contact-form']['content'] . '
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
' . pizzumm_pattern_kicker( 'Dove e quando' ) . '<!-- wp:heading -->
<h2 class="wp-block-heading">Il locale</h2>
<!-- /wp:heading -->
<!-- wp:shortcode -->
[pizzumm_info_locale]
<!-- /wp:shortcode -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->

';

			return $hero . $grid . $get( 'mappa' ) . $get( 'faq' );
	}

	return '';
}

