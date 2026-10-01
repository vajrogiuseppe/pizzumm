<?php
/**
 * Composizione delle 4 pagine con widget Elementor custom Pizzumm.
 *
 * All'attivazione, il tema popola `_elementor_data` di ogni pagina con questa
 * struttura. Ogni sezione è un widget custom del tema, editabile dai controlli
 * nativi di Elementor (testi, colori, sfondi, tipografia).
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function pizzumm_elementor_page_data( $slug ) {
	switch ( $slug ) {
		case 'home':      return pizzumm_el_home();
		case 'chi-siamo': return pizzumm_el_about();
		case 'menu':      return pizzumm_el_menu();
		case 'contatti':  return pizzumm_el_contatti();
	}
	return array();
}

/**
 * Wrapper "sezione" Elementor con un solo widget dentro.
 *
 * @param string $widget_type WidgetType.
 * @param array  $settings    Impostazioni widget.
 * @return array
 */
function pz_sec_wrap( $widget_type, $settings = array() ) {
	return array(
		'id'       => pz_el_uid(),
		'elType'   => 'section',
		'settings' => array(
			'stretch_section' => 'section-stretched',
			'gap'             => 'no',
			'content_width'   => array( 'unit' => 'px', 'size' => 1240 ),
			'padding'         => array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => true ),
		),
		'elements' => array(
			array(
				'id'       => pz_el_uid(),
				'elType'   => 'column',
				'settings' => array(
					'_column_size' => 100,
					'_inline_size' => null,
					'padding'      => array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => true ),
				),
				'elements' => array( pz_el_widget( $widget_type, $settings ) ),
				'isInner'  => false,
			),
		),
		'isInner'  => false,
	);
}

function pizzumm_el_home() {
	$menu     = pizzumm_page_url( 'menu' );
	$contatti = pizzumm_page_url( 'contatti' );
	$storia   = pizzumm_page_url( 'chi-siamo' );
	$glovo    = pizzumm_glovo_url();

	return array(
		pz_sec_wrap( 'pizzumm_hero', array(
			'kicker' => 'Pizza in teglia a Pompei',
			'title'  => 'Lo street food a Pompei ha 2.000 anni. Noi gli abbiamo dato una teglia nuova.',
			'text'   => 'Pizza in teglia leggera, croccante e pronta quando lo sei tu. Scegli la tua fetta e goditela, senza troppi giri.',
			'btn1_text' => 'Scopri il menu', 'btn1_url' => array( 'url' => $menu ),
			'btn2_text' => 'Vieni a trovarci', 'btn2_url' => array( 'url' => $contatti ),
			'hero_image' => array( 'url' => pizzumm_asset( 'img/brand/logo-pizzumm-bianco.png' ) ),
			'hero_image_style' => 'logo',
			'show_crumbs' => 'yes',
			'crumb1' => array( 'url' => pizzumm_asset( 'img/brand/pizzumm-1.jpg' ) ),
			'crumb2' => array( 'url' => pizzumm_asset( 'img/brand/pizzumm-2.jpg' ) ),
			'crumb3' => array( 'url' => pizzumm_asset( 'img/brand/pizzumm-3.jpg' ) ),
		) ),
		pz_sec_wrap( 'pizzumm_facts' ),
		pz_sec_wrap( 'pizzumm_ticker' ),
		pz_sec_wrap( 'pizzumm_split', array(
			'kicker' => 'Scegli la tua fetta',
			'title'  => 'Il menu, e Pizzumm dove vuoi.',
			'text1'  => 'Classica, speciale o la voglia del momento: guarda tutte le proposte e trova la tua Pizzumm.',
			'text2'  => 'A casa, in ufficio o con gli amici. Scegli le tue fette e lascia che la teglia arrivi da te.',
			'perks'  => array(
				array( 'text' => 'Classiche e speciali' ),
				array( 'text' => 'Proposte di stagione' ),
				array( 'text' => 'Asporto e domicilio' ),
				array( 'text' => 'Ordine dal tavolo' ),
			),
			'btn1_text' => 'Scopri il menu', 'btn1_url' => array( 'url' => $menu ),
			'btn2_text' => 'Ordina su Glovo', 'btn2_url' => array( 'url' => $glovo ),
			'image1' => array( 'url' => pizzumm_asset( 'img/brand/pizzumm-5.jpg' ) ),
			'image2' => array( 'url' => pizzumm_asset( 'img/brand/pizzumm-6.jpg' ) ),
		) ),
		pz_sec_wrap( 'pizzumm_categorie', array(
			'show_kicker' => 'yes', 'kicker' => 'Una pizza per fetta',
			'show_title'  => 'yes', 'title'  => 'Scegli da dove partire',
			'title_tag' => 'h2', 'align' => 'center',
			// Nascosta per ora: si riattiva da Elementor → Avanzate → Responsive.
			'hide_desktop' => 'hidden-desktop', 'hide_tablet' => 'hidden-tablet', 'hide_mobile' => 'hidden-mobile',
		) ),
		pz_sec_wrap( 'pizzumm_steps', array(
			'show_kicker' => 'yes', 'kicker' => 'Ordine smart',
			'show_title'  => 'yes', 'title'  => 'Tu scegli. Noi facciamo in fretta.',
			'title_tag' => 'h2', 'align' => 'center',
			'text' => 'Se mangi da Pizzumm, puoi ordinare e pagare in digitale direttamente dal tavolo. Meno attese, più tempo per goderti ogni fetta.',
		) ),
		pz_sec_wrap( 'pizzumm_split', array(
			'kicker' => 'Da Pompei, con un gusto nuovo',
			'title'  => 'Una storia iniziata duemila anni fa. Più o meno.',
			'text1'  => 'Nell’antica Pompei, i thermopolia erano luoghi in cui fermarsi per mangiare e bere fuori casa. Nel 2023, in Regio IX, un affresco ha riportato alla luce l’immagine di una focaccia piatta che ricorda l’antenata della pizza.',
			'btn1_text' => 'Scopri la nostra storia', 'btn1_url' => array( 'url' => $storia ),
			'image1' => array( 'url' => pizzumm_asset( 'img/brand/affresco.jpg' ) ),
		) ),
		pz_sec_wrap( 'pizzumm_cta_band', array(
			'kicker' => 'Pizzumm ti aspetta',
			'title'  => 'Hai già scelto? La parte facile inizia adesso.',
			'text'   => 'Ordina su Glovo per ricevere Pizzumm dove vuoi, oppure vieni a scegliere le tue fette direttamente al banco.',
			'btn1_text' => 'Ordina su Glovo', 'btn1_url' => array( 'url' => $glovo ),
			'btn2_text' => 'Come raggiungerci', 'btn2_url' => array( 'url' => $contatti ),
		) ),
	);
}

/**
 * Le pagine About/Menu/Contatti usano sezioni shortcode che rendono
 * ESATTAMENTE il markup del template PHP originale (identico al design).
 * Ogni sezione è modificabile in Elementor come "widget Shortcode".
 * Per personalizzarle: cambiare il template o creare varianti nei file
 * inc/section-shortcodes.php.
 */

function pizzumm_el_about() {
	return array(
		pz_sec_wrap( 'shortcode', array( 'shortcode' => '[pizzumm_sec id="about_hero"]' ) ),
		pz_sec_wrap( 'shortcode', array( 'shortcode' => '[pizzumm_sec id="about_timeline"]' ) ),
		pz_sec_wrap( 'shortcode', array( 'shortcode' => '[pizzumm_sec id="about_nome"]' ) ),
		pz_sec_wrap( 'shortcode', array( 'shortcode' => '[pizzumm_sec id="about_cta"]' ) ),
	);
}

function pizzumm_el_menu() {
	return array(
		pz_sec_wrap( 'shortcode', array( 'shortcode' => '[pizzumm_sec id="menu_hero"]' ) ),
		pz_sec_wrap( 'shortcode', array( 'shortcode' => '[pizzumm_sec id="menu_grid"]' ) ),
		pz_sec_wrap( 'shortcode', array( 'shortcode' => '[pizzumm_sec id="menu_cta"]' ) ),
	);
}

function pizzumm_el_contatti() {
	return array(
		/* 1) Hero della pagina */
		pz_sec_wrap( 'pizzumm_page_hero', array(
			'kicker' => 'Nel cuore di Pompei',
			'title'  => 'Ci vediamo da Pizzumm.',
			'text'   => 'Passa a scegliere la tua fetta, ordina per l’asporto o ricevila dove vuoi con Glovo.',
		) ),

		/* 2) Modulo + Info (widget custom che rende il PHP originale a 2 colonne) */
		pz_sec_wrap( 'pizzumm_contact_grid', array(
			'form_kicker' => 'Parliamone',
			'form_title'  => 'Scrivici',
			'form_text'   => 'Hai una domanda? Lasciaci un messaggio e ti risponderemo appena possibile.',
			'info_kicker' => 'Dove e quando',
			'info_title'  => 'Il locale',
			'show_address' => 'yes', 'show_phone' => 'yes', 'show_whatsapp' => 'yes',
			'show_email' => 'yes', 'show_hours' => 'yes', 'show_glovo' => 'yes', 'show_socials' => 'yes',
		) ),

		/* 3) Mappa full width sempre visibile */
		pz_sec_wrap( 'pizzumm_mappa', array(
			'show_kicker'  => '',
			'show_title'   => '',
			'text'         => '',
			'consent'      => '',
			'show_actions' => '',
			'height'       => array( 'unit' => 'px', 'size' => 520 ),
		) ),

		/* 4) FAQ */
		pz_sec_wrap( 'pizzumm_faq', array(
			'show_kicker' => 'yes', 'kicker' => 'Domande veloci',
			'show_title'  => 'yes', 'title'  => 'Le risposte in tre righe.',
			'title_tag'   => 'h2',
			'align'       => 'center',
		) ),
	);
}
