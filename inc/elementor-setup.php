<?php
/**
 * Auto-configurazione Elementor per le 4 pagine del sito.
 *
 * Al primo caricamento (dopo l'attivazione del tema, quando Elementor è già
 * attivo), ogni pagina Pizzumm viene marcata come "costruita con Elementor" e
 * viene popolato `_elementor_data` con una sequenza di widget "Shortcode":
 * uno per ogni sezione del layout originale. Il cliente apre in Elementor e
 * trova tutto già pronto — niente file JSON da importare.
 *
 * Ogni widget richiama uno shortcode di `inc/section-shortcodes.php` che
 * produce esattamente il markup del template PHP (identico al design). Il
 * cliente può poi spostare, duplicare, eliminare o aggiungere sezioni con
 * il drag & drop di Elementor.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor è attivo?
 *
 * @return bool
 */
function pizzumm_elementor_active() {
	return did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' );
}

/**
 * Sequenza di sezioni per ciascuna pagina del sito.
 *
 * @return array<string,string[]>
 */
function pizzumm_page_sections_map() {
	return array(
		'home'      => array( 'home_hero', 'home_ticker', 'home_duo', 'home_categorie', 'home_steps', 'home_storia', 'home_cta' ),
		'chi-siamo' => array( 'about_hero', 'about_timeline', 'about_nome', 'about_cta' ),
		'menu'      => array( 'menu_hero', 'menu_grid', 'menu_cta' ),
		'contatti'  => array( 'contact_hero', 'contact_grid', 'contact_map', 'contact_faq' ),
	);
}

/**
 * ID univoco compatibile con Elementor (8 caratteri esadecimali).
 *
 * @return string
 */
function pizzumm_elementor_uid() {
	return substr( md5( uniqid( '', true ) ), 0, 7 );
}

/**
 * Costruisce la struttura Elementor per una pagina, come array PHP.
 *
 * Delegata a `pizzumm_elementor_page_data()` in inc/elementor-widgets.php,
 * che compone le pagine con widget veri (heading, image, button, ecc.)
 * modificabili nativamente dall'editor.
 *
 * @param string $slug Slug della pagina.
 * @return array
 */
function pizzumm_build_elementor_data( $slug ) {
	if ( ! function_exists( 'pizzumm_elementor_page_data' ) ) {
		return array();
	}
	return pizzumm_elementor_page_data( $slug );
}

/**
 * Applica la configurazione Elementor a una pagina.
 *
 * @param int    $post_id ID della pagina.
 * @param string $slug    Slug della pagina.
 */
function pizzumm_apply_elementor_to_page( $post_id, $slug ) {
	if ( ! $post_id || ! pizzumm_elementor_active() ) {
		return;
	}

	$data = pizzumm_build_elementor_data( $slug );

	if ( ! $data ) {
		return;
	}

	// Save. slashes because WP unslashes post meta on read.
	update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $post_id, '_elementor_template_type', 'wp-page' );

	if ( defined( 'ELEMENTOR_VERSION' ) ) {
		update_post_meta( $post_id, '_elementor_version', ELEMENTOR_VERSION );
	}

	// CSS cache di Elementor: forza rigenerazione al prossimo caricamento.
	delete_post_meta( $post_id, '_elementor_css' );
	delete_post_meta( $post_id, '_elementor_page_assets' );

	// Log una tantum per evitare di riscrivere ogni volta.
	update_post_meta( $post_id, '_pizzumm_elementor_setup', PIZZUMM_VERSION );
}

/**
 * Al primo caricamento admin dopo attivazione (o dopo che Elementor è stato
 * attivato), configura tutte le pagine Pizzumm.
 */
function pizzumm_maybe_setup_elementor_pages() {
	if ( ! is_admin() || wp_doing_ajax() ) {
		return;
	}
	if ( ! pizzumm_elementor_active() ) {
		return;
	}
	if ( get_option( 'pizzumm_elementor_setup_done' ) === PIZZUMM_VERSION ) {
		return;
	}

	foreach ( array_keys( pizzumm_page_sections_map() ) as $slug ) {
		$page_slug = ( 'home' === $slug ) ? 'home' : $slug;
		$page      = get_page_by_path( $page_slug );

		if ( ! $page ) {
			continue;
		}

		// Applichiamo/aggiorniamo la struttura predefinita del tema.
		// Se il cliente ha personalizzato la pagina in Elementor, la nostra
		// versione del tema (`_pizzumm_elementor_setup`) è già uguale a
		// PIZZUMM_VERSION e usciamo dal loop tramite il flag `pizzumm_elementor_setup_done`
		// controllato in maybe_setup.
		pizzumm_apply_elementor_to_page( $page->ID, $slug );
	}

	update_option( 'pizzumm_elementor_setup_done', PIZZUMM_VERSION );
}
add_action( 'admin_init', 'pizzumm_maybe_setup_elementor_pages', 20 );

/**
 * Su attivazione del tema, resetta il flag così l'installer riparte + clear
 * completa della cache CSS/asset di Elementor per evitare che vengano riusati
 * file .css statici generati con la vecchia struttura.
 */
function pizzumm_reset_elementor_setup_flag() {
	delete_option( 'pizzumm_elementor_setup_done' );

	if ( class_exists( '\Elementor\Plugin' ) ) {
		$plugin = \Elementor\Plugin::$instance;

		if ( method_exists( $plugin, 'files_manager' ) && $plugin->files_manager ) {
			$plugin->files_manager->clear_cache();
		}
	}

	// Pulisce la CSS cache anche a livello di post meta.
	global $wpdb;
	$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key IN ('_elementor_css','_elementor_page_assets','_elementor_inline_svg')" );
}
add_action( 'after_switch_theme', 'pizzumm_reset_elementor_setup_flag' );

/**
 * Pulsante nel metabox "Layout Pizzumm" per rigenerare la struttura Elementor
 * su una singola pagina. Utile se il cliente ha stravolto tutto e vuole
 * tornare al layout originale.
 */
function pizzumm_elementor_reset_button( $post ) {
	if ( ! pizzumm_elementor_active() ) {
		return;
	}

	$slug = get_post_field( 'post_name', $post );

	if ( ! isset( pizzumm_page_sections_map()[ $slug ] ) ) {
		return;
	}

	$url = wp_nonce_url(
		admin_url( 'admin-post.php?action=pizzumm_reset_elementor&post=' . $post->ID ),
		'pizzumm_reset_elementor_' . $post->ID
	);

	echo '<hr />';
	echo '<p><strong>' . esc_html__( 'Elementor', 'pizzumm' ) . '</strong></p>';
	echo '<p class="description">' . esc_html__( 'Ripristina la struttura Elementor predefinita di questa pagina. Attenzione: sostituisce il contenuto attuale.', 'pizzumm' ) . '</p>';
	echo '<p><a class="button" href="' . esc_url( $url ) . '" onclick="return confirm(\'' . esc_js( __( 'La struttura Elementor attuale verrà sostituita. Procedere?', 'pizzumm' ) ) . '\');">' . esc_html__( 'Rigenera struttura Elementor', 'pizzumm' ) . '</a></p>';
}
add_action( 'pizzumm_after_layout_metabox', 'pizzumm_elementor_reset_button' );

/**
 * Handler del pulsante di reset.
 */
function pizzumm_handle_elementor_reset() {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	$nonce   = isset( $_GET['_wpnonce'] ) ? sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ) : '';

	if ( ! $post_id || ! wp_verify_nonce( $nonce, 'pizzumm_reset_elementor_' . $post_id ) ) {
		wp_die( esc_html__( 'Sessione scaduta, riprova.', 'pizzumm' ) );
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_die( esc_html__( 'Permessi insufficienti.', 'pizzumm' ) );
	}

	$slug = get_post_field( 'post_name', $post_id );

	delete_post_meta( $post_id, '_pizzumm_elementor_setup' );
	pizzumm_apply_elementor_to_page( $post_id, $slug );

	wp_safe_redirect( get_edit_post_link( $post_id, 'url' ) );
	exit;
}
add_action( 'admin_post_pizzumm_reset_elementor', 'pizzumm_handle_elementor_reset' );
