<?php
/**
 * Plugin richiesti dal tema.
 *
 * Il tema si appoggia a:
 *  - Elementor (versione free): costruttore visuale delle pagine.
 *  - Contact Form 7: modulo contatti.
 *
 * Quando uno dei due manca o non è attivo mostriamo un avviso in bacheca con
 * il link diretto alla schermata di installazione dei plugin ufficiali.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elenco dei plugin richiesti dal tema.
 *
 * @return array<string,array{name:string,slug:string,file:string}>
 */
function pizzumm_required_plugins() {
	return array(
		'elementor' => array(
			'name' => __( 'Elementor', 'pizzumm' ),
			'slug' => 'elementor',
			'file' => 'elementor/elementor.php',
		),
		'cf7' => array(
			'name' => __( 'Contact Form 7', 'pizzumm' ),
			'slug' => 'contact-form-7',
			'file' => 'contact-form-7/wp-contact-form-7.php',
		),
	);
}

/**
 * Plugin richiesti che risultano non attivi.
 *
 * @return array<int,array{name:string,slug:string,file:string}>
 */
function pizzumm_missing_required_plugins() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$missing = array();

	foreach ( pizzumm_required_plugins() as $plugin ) {
		if ( ! is_plugin_active( $plugin['file'] ) ) {
			$missing[] = $plugin;
		}
	}

	return $missing;
}

/**
 * Avviso in bacheca con il link di installazione.
 */
function pizzumm_required_plugins_notice() {
	if ( ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	$missing = pizzumm_missing_required_plugins();

	if ( ! $missing ) {
		return;
	}

	$links = array();

	foreach ( $missing as $plugin ) {
		$url = wp_nonce_url(
			self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin['slug'] ),
			'install-plugin_' . $plugin['slug']
		);

		$links[] = sprintf(
			'<a href="%s">%s</a>',
			esc_url( $url ),
			esc_html( $plugin['name'] )
		);
	}

	printf(
		'<div class="notice notice-warning"><p><strong>%s</strong> %s %s</p></div>',
		esc_html__( 'Tema Pizzumm:', 'pizzumm' ),
		esc_html__( 'per completare l\'installazione attiva:', 'pizzumm' ),
		wp_kses_post( implode( ', ', $links ) )
	);
}
add_action( 'admin_notices', 'pizzumm_required_plugins_notice' );

/**
 * Contact Form 7 è disponibile?
 *
 * @return bool
 */
function pizzumm_has_cf7() {
	return defined( 'WPCF7_VERSION' );
}
