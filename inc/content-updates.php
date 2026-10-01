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

define( 'PIZZUMM_CONTENT_REV', 1 );

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

	// Cache CSS di Elementor: si rigenera al prossimo caricamento.
	delete_post_meta( $post_id, '_elementor_css' );
	delete_post_meta( $post_id, '_elementor_page_assets' );
	delete_post_meta( $post_id, '_elementor_element_cache' );

	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}
