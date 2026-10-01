<?php
/**
 * Helpers per opzioni globali del tema (hero, footer, immagini).
 *
 * Tutti i testi delle pagine e le loro immagini si editano ora direttamente
 * dai widget Elementor. Qui restano solo helper compatibili col vecchio codice
 * (usato dai fallback PHP quando la pagina non è ancora costruita con
 * Elementor) e la registrazione delle sidebar del footer.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Numero di colonne footer.
 * Legge dal Customizer (pizzumm_footer_columns), default 4.
 *
 * @return int
 */
function pizzumm_footer_columns() {
	$n = (int) get_theme_mod( 'pizzumm_footer_columns', 4 );
	return in_array( $n, array( 2, 3, 4 ), true ) ? $n : 4;
}

/**
 * Prefooter abilitato.
 *
 * @return bool
 */
function pizzumm_prefooter_enabled() {
	return (bool) get_theme_mod( 'pizzumm_prefooter_enabled', false );
}

/**
 * URL di un'immagine (fallback allo stock del tema).
 *
 * @param string $key            Chiave (non usata, retro-compat).
 * @param string $fallback_asset Path relativo a /assets.
 * @return string
 */
function pizzumm_image( $key, $fallback_asset = '' ) {
	return $fallback_asset ? pizzumm_asset( $fallback_asset ) : '';
}

/**
 * Configurazione hero (retro-compat con Customizer).
 *
 * @return array{type:string,image:string,video:string,poster:string}
 */
function pizzumm_hero_config() {
	$video  = get_theme_mod( 'pizzumm_hero_video', '' );
	$poster = get_theme_mod( 'pizzumm_hero_poster', '' );
	$image  = $poster ? $poster : pizzumm_asset( 'img/brand/logo-pizzumm-bianco.png' );
	$type   = $video ? 'video' : ( $poster ? 'current' : 'logo' );

	return array(
		'type'   => $type,
		'image'  => $image,
		'video'  => $video,
		'poster' => $image,
	);
}

/**
 * Testo copyright per il footer.
 *
 * @return string
 */
function pizzumm_copyright_html() {
	$raw = (string) get_theme_mod( 'pizzumm_copyright_text', pizzumm_default_copyright() );
	$html = strtr( $raw, array(
		'{year}'     => (string) gmdate( 'Y' ),
		'{sitename}' => get_bloginfo( 'name' ),
	) );
	return wp_kses_post( $html );
}

/**
 * Copyright predefinito con i dati societari.
 *
 * @return string
 */
function pizzumm_default_copyright() {
	return '© {year} Pizzum s.r.l.s. · Piazza Vittorio Veneto, 11 Pompei (NA) · REA NA-1151746 | CF 11058441210';
}

/**
 * ID menu privacy scelto.
 *
 * @return int
 */
function pizzumm_copyright_menu_id() {
	return (int) get_theme_mod( 'pizzumm_copyright_menu_id', 0 );
}

/**
 * Registra le sidebar del footer.
 */
function pizzumm_register_footer_sidebars() {
	$columns = pizzumm_footer_columns();

	for ( $i = 1; $i <= $columns; $i++ ) {
		register_sidebar( array(
			'name'          => sprintf( __( 'Footer · Colonna %d', 'pizzumm' ), $i ),
			'id'            => 'footer-col-' . $i,
			'description'   => __( 'Colonna widget del footer.', 'pizzumm' ),
			'before_widget' => '<div class="footer__widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="footer__title">',
			'after_title'   => '</h3>',
		) );
	}

	if ( pizzumm_prefooter_enabled() ) {
		for ( $i = 1; $i <= 2; $i++ ) {
			register_sidebar( array(
				'name'          => sprintf( __( 'Prefooter · Colonna %d', 'pizzumm' ), $i ),
				'id'            => 'prefooter-col-' . $i,
				'description'   => __( 'Riga superiore del footer.', 'pizzumm' ),
				'before_widget' => '<div class="prefooter__widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="prefooter__title">',
				'after_title'   => '</h3>',
			) );
		}
	}
}
add_action( 'widgets_init', 'pizzumm_register_footer_sidebars', 20 );
