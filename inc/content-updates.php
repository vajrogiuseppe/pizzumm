<?php
/**
 * Aggiornamenti una tantum dei contenuti già salvati nel database.
 *
 * Le pagine costruite con Elementor tengono immagini e impostazioni in
 * `_elementor_data`: cambiare i default dei widget non basta. Qui si applicano,
 * una sola volta per revisione, le modifiche richieste dal cliente senza
 * rigenerare le pagine (le personalizzazioni fatte in Elementor restano).
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PIZZUMM_CONTENT_REV', 3 );

/**
 * Esegue le revisioni mancanti.
 */
function pizzumm_run_content_updates() {
	$done = (int) get_option( 'pizzumm_content_rev', 0 );

	if ( $done >= PIZZUMM_CONTENT_REV ) {
		return;
	}

	if ( $done < 1 ) {
		pizzumm_content_update_1();
	}

	if ( $done < 2 ) {
		pizzumm_content_update_2();
	}

	if ( $done < 3 ) {
		pizzumm_content_update_3();
	}

	update_option( 'pizzumm_content_rev', PIZZUMM_CONTENT_REV );
}
add_action( 'init', 'pizzumm_run_content_updates', 99 );

/**
 * Revisione 1 — modifiche di settembre 2026.
 *
 * - Home: logo al posto della pizza tonda, nuove foto (hero, duo, storia),
 *   sezione categorie nascosta.
 * - Opzioni: recapiti e copyright societario al posto dei valori vuoti.
 */
function pizzumm_content_update_1() {
	$home_id = (int) get_option( 'page_on_front' );

	if ( $home_id ) {
		pizzumm_update_elementor_widgets( $home_id, 'pizzumm_content_update_1_home' );
	}

	// Valori salvati vuoti: tolti, così valgono i default del tema.
	foreach ( array( 'phone', 'whatsapp', 'email', 'company' ) as $key ) {
		if ( '' === get_theme_mod( 'pizzumm_' . $key, null ) ) {
			remove_theme_mod( 'pizzumm_' . $key );
		}
	}

	$copyright = get_theme_mod( 'pizzumm_copyright_text', null );
	if ( null !== $copyright && in_array( trim( wp_strip_all_tags( $copyright ) ), array( '', '© {year} {sitename}' ), true ) ) {
		remove_theme_mod( 'pizzumm_copyright_text' );
	}

	// Il nome del sito era rimasto quello di installazione.
	if ( 'WordPress' === get_option( 'blogname' ) ) {
		update_option( 'blogname', 'Pizzumm' );
	}
}

/**
 * Modifiche ai widget della home.
 *
 * @param array $el Elemento Elementor (per riferimento).
 */
function pizzumm_content_update_1_home( &$el ) {
	$type = $el['widgetType'] ?? '';
	$s    = &$el['settings'];

	if ( 'pizzumm_hero' === $type ) {
		$s['hero_image']       = array( 'url' => pizzumm_asset( 'img/brand/logo-pizzumm-bianco.png' ), 'id' => '' );
		$s['hero_image_style'] = 'logo';
		$s['show_crumbs']      = 'yes';
		$s['crumb1']           = array( 'url' => pizzumm_asset( 'img/brand/pizzumm-1.jpg' ), 'id' => '' );
		$s['crumb2']           = array( 'url' => pizzumm_asset( 'img/brand/pizzumm-2.jpg' ), 'id' => '' );
		$s['crumb3']           = array( 'url' => pizzumm_asset( 'img/brand/pizzumm-3.jpg' ), 'id' => '' );
		return;
	}

	if ( 'pizzumm_split' === $type ) {
		$img1 = $s['image1']['url'] ?? '';

		if ( ! empty( $s['image2']['url'] ) ) {
			// Sezione "Il menu, e Pizzumm dove vuoi": coppia di foto.
			$s['image1'] = array( 'url' => pizzumm_asset( 'img/brand/pizzumm-5.jpg' ), 'id' => '' );
			$s['image2'] = array( 'url' => pizzumm_asset( 'img/brand/pizzumm-6.jpg' ), 'id' => '' );
		} elseif ( false !== strpos( $img1, 'story-banco' ) ) {
			// Sezione "Una storia iniziata duemila anni fa".
			$s['image1'] = array( 'url' => pizzumm_asset( 'img/brand/affresco.jpg' ), 'id' => '' );
		}
		return;
	}

	if ( 'pizzumm_categorie' === $type ) {
		// Nascosta per ora: si riattiva da Elementor → Avanzate → Responsive.
		$s['hide_desktop'] = 'hidden-desktop';
		$s['hide_tablet']  = 'hidden-tablet';
		$s['hide_mobile']  = 'hidden-mobile';
	}
}

/**
 * Voci dell'elenco nella sezione "Il menu, le formule, Pizzumm dove vuoi".
 *
 * @return array
 */
function pizzumm_home_duo_perks() {
	$voci = array( 'Classiche e speciali', 'Proposte di stagione', 'Formule Smile', 'Pizzummino Kids', 'Asporto e domicilio', 'Ordine dal tavolo' );

	return array_map(
		static function ( $text ) {
			return array( '_id' => substr( md5( $text ), 0, 7 ), 'text' => $text );
		},
		$voci
	);
}

/**
 * Revisione 2 — modifiche di Terry, novembre 2026.
 *
 * - Home: testi di vantaggi, nastro, sezione menu e CTA; "Perché Pizzumm"
 *   ripetuto prima della storia.
 * - Menu: box del brand, formule Smile e Pizzummino Kids; l'elenco prodotti
 *   resta nella pagina ma nascosto (si riattiva da Elementor → Avanzate →
 *   Responsive), la nota allergeni resta visibile.
 */
function pizzumm_content_update_2() {
	$home_id = (int) get_option( 'page_on_front' );

	if ( $home_id ) {
		pizzumm_update_elementor_widgets( $home_id, 'pizzumm_content_update_2_home' );
		pizzumm_update_elementor_sections( $home_id, 'pizzumm_content_update_2_home_sections' );
	}

	$menu = get_page_by_path( 'menu' );

	if ( $menu ) {
		pizzumm_update_elementor_widgets( $menu->ID, 'pizzumm_content_update_2_menu' );
		pizzumm_update_elementor_sections( $menu->ID, 'pizzumm_content_update_2_menu_sections' );
	}
}

/**
 * @param array $el Elemento Elementor (per riferimento).
 */
function pizzumm_content_update_2_home( &$el ) {
	$type = $el['widgetType'] ?? '';
	$s    = &$el['settings'];

	if ( 'pizzumm_facts' === $type ) {
		if ( empty( $s['items'] ) ) {
			return;
		}
		$map = array(
			'una pizza per fetta' => 'Una pizza per(fetta)',
			'pizzumm dove vuoi'   => 'Pizzumm dove vuoi tu',
		);
		foreach ( $s['items'] as &$item ) {
			$key = strtolower( trim( $item['title'] ?? '' ) );
			if ( isset( $map[ $key ] ) ) {
				$item['title'] = $map[ $key ];
			}
		}
		unset( $item );
		return;
	}

	if ( 'pizzumm_ticker' === $type ) {
		if ( empty( $s['items'] ) ) {
			return;
		}
		$items = array();
		foreach ( $s['items'] as $item ) {
			if ( 'una pizza per fetta' === strtolower( trim( $item['text'] ?? '' ) ) ) {
				$item['text'] = 'Una pizza per(fetta)';
				$items[]      = $item;
				$items[]      = array( '_id' => substr( md5( 'croccante' ), 0, 7 ), 'text' => 'Croccante e super gustosa' );
				continue;
			}
			$items[] = $item;
		}
		$s['items'] = $items;
		return;
	}

	if ( 'pizzumm_split' === $type && ! empty( $s['image2']['url'] ) ) {
		$s['kicker']    = 'Scegli la tua fetta';
		$s['title']     = 'Il menu, le formule, Pizzumm dove vuoi.';
		$s['text1']     = 'Classica, speciale o la voglia del momento: componi la tua teglia fetta dopo fetta, oppure scegli una formula già pronta.';
		$s['text2']     = 'A casa, in ufficio o con gli amici. Le formule Smile mettono insieme pizza, sfizi e bevanda in un’unica scelta, dalla pausa pranzo all’aperitivo. E con il Pizzummino Kids anche i più piccoli hanno la loro.';
		$s['perks']     = pizzumm_home_duo_perks();
		$s['btn1_text'] = 'Scopri il menu e le formule';
		$s['btn2_text'] = 'Ordina su Glovo';
		return;
	}

	if ( 'pizzumm_cta_band' === $type ) {
		$s['title'] = 'Hai già scelto? Il bello inizia adesso.';
	}
}

/**
 * "Perché Pizzumm" prima della sezione storia.
 *
 * @param array $sections Sezioni di primo livello.
 * @return array
 */
function pizzumm_content_update_2_home_sections( $sections ) {
	$shortcode = '[pizzumm_sec id="about_nome"]';

	if ( pizzumm_sections_find( $sections, $shortcode ) >= 0 ) {
		return $sections;
	}

	foreach ( $sections as $i => $section ) {
		$widget = pizzumm_section_first_widget( $section );
		if ( $widget && 'pizzumm_split' === ( $widget['widgetType'] ?? '' ) && empty( $widget['settings']['image2']['url'] ) ) {
			array_splice( $sections, $i, 0, array( pz_sec_wrap( 'shortcode', array( 'shortcode' => $shortcode ) ) ) );
			break;
		}
	}

	return $sections;
}

/**
 * @param array $el Elemento Elementor (per riferimento).
 */
function pizzumm_content_update_2_menu( &$el ) {
	if ( 'shortcode' !== ( $el['widgetType'] ?? '' ) || false === strpos( $el['settings']['shortcode'] ?? '', 'menu_grid' ) ) {
		return;
	}
	$el['settings']['hide_desktop'] = 'hidden-desktop';
	$el['settings']['hide_tablet']  = 'hidden-tablet';
	$el['settings']['hide_mobile']  = 'hidden-mobile';
}

/**
 * Box, formule e nota allergeni attorno all'elenco prodotti.
 *
 * @param array $sections Sezioni di primo livello.
 * @return array
 */
function pizzumm_content_update_2_menu_sections( $sections ) {
	$hero = pizzumm_sections_find( $sections, 'menu_hero' );
	if ( $hero < 0 || pizzumm_sections_find( $sections, 'menu_formule' ) >= 0 ) {
		return $sections;
	}

	array_splice( $sections, $hero + 1, 0, array(
		pizzumm_section_clone_shortcode( $sections[ $hero ], '[pizzumm_sec id="menu_boxes"]' ),
		pizzumm_section_clone_shortcode( $sections[ $hero ], '[pizzumm_sec id="menu_formule"]' ),
	) );

	$grid = pizzumm_sections_find( $sections, 'menu_grid' );
	if ( $grid >= 0 ) {
		array_splice( $sections, $grid + 1, 0, array(
			pizzumm_section_clone_shortcode( $sections[ $grid ], '[pizzumm_sec id="menu_allergeni"]' ),
		) );
	}

	return $sections;
}

/**
 * Revisione 3 — Privacy e Cookie policy.
 *
 * - Pubblica la pagina Privacy (riusa la bozza di WordPress) e crea la Cookie policy.
 * - Imposta la pagina privacy in Impostazioni → Privacy.
 * - Nel modulo contatti, "informativa privacy" diventa un link.
 */
function pizzumm_content_update_3() {
	$privacy_id = (int) get_option( 'wp_page_for_privacy_policy' );
	$privacy    = $privacy_id ? get_post( $privacy_id ) : null;

	if ( ! $privacy || 'page' !== $privacy->post_type ) {
		$privacy = get_page_by_path( 'privacy-policy' );
	}

	$privacy_args = array(
		'post_title'   => 'Privacy policy',
		'post_name'    => 'privacy-policy',
		'post_content' => pizzumm_legal_block( pizzumm_legal_privacy_content() ),
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);

	if ( $privacy ) {
		$privacy_args['ID'] = $privacy->ID;
		$privacy_id         = wp_update_post( wp_slash( $privacy_args ) );
	} else {
		$privacy_id = wp_insert_post( wp_slash( $privacy_args ) );
	}

	if ( $privacy_id && ! is_wp_error( $privacy_id ) ) {
		update_option( 'wp_page_for_privacy_policy', (int) $privacy_id );
	}

	$cookie      = get_page_by_path( 'cookie-policy' );
	$cookie_args = array(
		'post_title'   => 'Cookie policy',
		'post_name'    => 'cookie-policy',
		'post_content' => pizzumm_legal_block( pizzumm_legal_cookie_content() ),
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);

	if ( $cookie ) {
		$cookie_args['ID'] = $cookie->ID;
		wp_update_post( wp_slash( $cookie_args ) );
	} else {
		wp_insert_post( wp_slash( $cookie_args ) );
	}

	$privacy_url = get_privacy_policy_url();
	if ( $privacy_url ) {
		$forms = get_posts( array( 'post_type' => 'wpcf7_contact_form', 'numberposts' => -1, 'post_status' => 'any' ) );
		foreach ( $forms as $form ) {
			$markup = (string) get_post_meta( $form->ID, '_form', true );
			$old    = 'Ho letto l\'informativa privacy';
			if ( false === strpos( $markup, $old ) || false !== strpos( $markup, 'privacy-policy' ) ) {
				continue;
			}
			$markup = str_replace( $old, 'Ho letto l\'<a href="' . esc_url( $privacy_url ) . '" target="_blank">informativa privacy</a>', $markup );
			update_post_meta( $form->ID, '_form', wp_slash( $markup ) );
		}
	}
}

/**
 * Contenuto in un blocco HTML: niente paragrafi automatici dentro tabelle e liste.
 *
 * @param string $html Markup.
 * @return string
 */
function pizzumm_legal_block( $html ) {
	$html = preg_replace( '/>\s+</', '><', trim( $html ) );
	return "<!-- wp:html -->\n" . $html . "\n<!-- /wp:html -->";
}

/**
 * Primo widget di una sezione.
 *
 * @param array $el Elemento.
 * @return array|null
 */
function pizzumm_section_first_widget( $el ) {
	if ( 'widget' === ( $el['elType'] ?? '' ) ) {
		return $el;
	}
	foreach ( $el['elements'] ?? array() as $child ) {
		$found = pizzumm_section_first_widget( $child );
		if ( $found ) {
			return $found;
		}
	}
	return null;
}

/**
 * Indice della sezione il cui primo widget shortcode contiene $needle.
 *
 * @param array  $sections Sezioni.
 * @param string $needle   Testo da cercare nello shortcode.
 * @return int -1 se non trovata.
 */
function pizzumm_sections_find( $sections, $needle ) {
	foreach ( $sections as $i => $section ) {
		$widget = pizzumm_section_first_widget( $section );
		if ( $widget && false !== strpos( $widget['settings']['shortcode'] ?? '', $needle ) ) {
			return $i;
		}
	}
	return -1;
}

/**
 * Copia di una sezione con id nuovi, senza regole di visibilità e con un altro shortcode.
 *
 * @param array  $section   Sezione di partenza.
 * @param string $shortcode Shortcode completo.
 * @return array
 */
function pizzumm_section_clone_shortcode( $section, $shortcode ) {
	$hide          = array( 'hide_desktop', 'hide_tablet', 'hide_mobile' );
	$section['id'] = pz_el_uid();
	foreach ( $hide as $k ) {
		unset( $section['settings'][ $k ] );
	}
	foreach ( $section['elements'] as &$col ) {
		$col['id'] = pz_el_uid();
		foreach ( $col['elements'] as &$w ) {
			$w['id'] = pz_el_uid();
			foreach ( $hide as $k ) {
				unset( $w['settings'][ $k ] );
			}
			$w['settings']['shortcode'] = $shortcode;
		}
		unset( $w );
	}
	unset( $col );
	return $section;
}

/**
 * Applica una callback all'elenco delle sezioni di primo livello e salva.
 *
 * @param int      $post_id  Pagina.
 * @param callable $callback Riceve e restituisce l'array delle sezioni.
 */
function pizzumm_update_elementor_sections( $post_id, $callback ) {
	$raw  = get_post_meta( $post_id, '_elementor_data', true );
	$data = is_array( $raw ) ? $raw : json_decode( (string) $raw, true );

	if ( ! is_array( $data ) || ! $data ) {
		return;
	}

	$data = call_user_func( $callback, $data );

	update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	pizzumm_clear_elementor_cache( $post_id );
}

/**
 * Applica una callback a ogni widget di una pagina Elementor e salva.
 *
 * @param int      $post_id  Pagina.
 * @param callable $callback Riceve l'elemento per riferimento.
 */
function pizzumm_update_elementor_widgets( $post_id, $callback ) {
	$raw  = get_post_meta( $post_id, '_elementor_data', true );
	$data = is_array( $raw ) ? $raw : json_decode( (string) $raw, true );

	if ( ! is_array( $data ) || ! $data ) {
		return;
	}

	$walk = static function ( &$elements ) use ( &$walk, $callback ) {
		foreach ( $elements as &$el ) {
			if ( isset( $el['elType'] ) && 'widget' === $el['elType'] ) {
				if ( ! isset( $el['settings'] ) || ! is_array( $el['settings'] ) ) {
					$el['settings'] = array();
				}
				call_user_func_array( $callback, array( &$el ) );
			}
			if ( ! empty( $el['elements'] ) ) {
				$walk( $el['elements'] );
			}
		}
		unset( $el );
	};
	$walk( $data );

	update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	pizzumm_clear_elementor_cache( $post_id );
}

/**
 * Cache CSS di Elementor: si rigenera al prossimo caricamento.
 *
 * @param int $post_id Pagina.
 */
function pizzumm_clear_elementor_cache( $post_id ) {
	delete_post_meta( $post_id, '_elementor_css' );
	delete_post_meta( $post_id, '_elementor_page_assets' );
	delete_post_meta( $post_id, '_elementor_element_cache' );

	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}
