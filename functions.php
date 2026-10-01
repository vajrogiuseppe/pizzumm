<?php
/**
 * Pizzumm — functions & definitions
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PIZZUMM_VERSION', '1.3.0' );
// Versione di CSS/JS per il cache-busting. È separata da PIZZUMM_VERSION perché
// cambiare quest'ultima rigenera le pagine Elementor da zero.
define( 'PIZZUMM_ASSETS_VERSION', '1.4.0' );
define( 'PIZZUMM_DIR', get_template_directory() );
define( 'PIZZUMM_URI', get_template_directory_uri() );

require_once PIZZUMM_DIR . '/inc/icons.php';
require_once PIZZUMM_DIR . '/inc/template-tags.php';
require_once PIZZUMM_DIR . '/inc/customizer.php';
require_once PIZZUMM_DIR . '/inc/cpt-menu.php';
require_once PIZZUMM_DIR . '/inc/theme-plugins.php';
require_once PIZZUMM_DIR . '/inc/theme-options.php';
require_once PIZZUMM_DIR . '/inc/shortcodes.php';
require_once PIZZUMM_DIR . '/inc/section-shortcodes.php';
require_once PIZZUMM_DIR . '/inc/elementor-builder.php';
require_once PIZZUMM_DIR . '/inc/elementor-widgets.php';
require_once PIZZUMM_DIR . '/inc/elementor-setup.php';
require_once PIZZUMM_DIR . '/inc/elementor-custom-widgets.php';
require_once PIZZUMM_DIR . '/inc/seo.php';
require_once PIZZUMM_DIR . '/inc/page-builders.php';
require_once PIZZUMM_DIR . '/inc/block-patterns.php';
require_once PIZZUMM_DIR . '/inc/demo-content.php';
require_once PIZZUMM_DIR . '/inc/content-updates.php';
require_once PIZZUMM_DIR . '/inc/github-updater.php';

/**
 * Setup del tema.
 */
function pizzumm_setup() {
	load_theme_textdomain( 'pizzumm', PIZZUMM_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
	) );

	add_image_size( 'pizzumm-card', 900, 675, true );
	add_image_size( 'pizzumm-wide', 1600, 900, true );
	add_image_size( 'pizzumm-portrait', 900, 1125, true );

	register_nav_menus( array(
		'primary' => __( 'Menu principale', 'pizzumm' ),
		'legal'   => __( 'Menu legale (footer)', 'pizzumm' ),
	) );

	add_editor_style( array( 'assets/css/fonts.css', 'assets/css/main.css' ) );
}
add_action( 'after_setup_theme', 'pizzumm_setup' );

/**
 * Asset front-end.
 */
function pizzumm_assets() {
	wp_enqueue_style( 'pizzumm-fonts', PIZZUMM_URI . '/assets/css/fonts.css', array(), PIZZUMM_ASSETS_VERSION );
	wp_enqueue_style( 'pizzumm-style', get_stylesheet_uri(), array( 'pizzumm-fonts' ), PIZZUMM_ASSETS_VERSION );
	wp_enqueue_style( 'pizzumm-main', PIZZUMM_URI . '/assets/css/main.css', array( 'pizzumm-style' ), PIZZUMM_ASSETS_VERSION );

	wp_add_inline_style( 'pizzumm-main', pizzumm_inline_palette_css() );

	// GSAP: core + ScrollTrigger + SplitText, serviti dal tema (licenza standard "no charge").
	$gsap = PIZZUMM_URI . '/assets/js/vendor/';
	wp_enqueue_script( 'gsap', $gsap . 'gsap.min.js', array(), '3.15.0', true );
	wp_enqueue_script( 'gsap-scrolltrigger', $gsap . 'ScrollTrigger.min.js', array( 'gsap' ), '3.15.0', true );
	wp_enqueue_script( 'gsap-splittext', $gsap . 'SplitText.min.js', array( 'gsap' ), '3.15.0', true );

	wp_enqueue_script( 'pizzumm-main', PIZZUMM_URI . '/assets/js/main.js', array(), PIZZUMM_ASSETS_VERSION, true );
	wp_enqueue_script(
		'pizzumm-motion',
		PIZZUMM_URI . '/assets/js/motion.js',
		array( 'gsap', 'gsap-scrolltrigger', 'gsap-splittext' ),
		PIZZUMM_ASSETS_VERSION,
		true
	);
	wp_localize_script( 'pizzumm-main', 'pizzummData', array(
		'cookiePrefs' => get_theme_mod( 'pizzumm_cookie_prefs_selector', '' ),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'pizzumm_assets' );

/**
 * Forza il caricamento del CSS del tema anche nell'editor visuale di Elementor
 * e nell'anteprima. Così l'editor mostra il vero design, non il default di
 * Elementor.
 */
function pizzumm_editor_assets() {
	wp_enqueue_style( 'pizzumm-fonts', PIZZUMM_URI . '/assets/css/fonts.css', array(), PIZZUMM_ASSETS_VERSION );
	wp_enqueue_style( 'pizzumm-style', get_stylesheet_uri(), array( 'pizzumm-fonts' ), PIZZUMM_ASSETS_VERSION );
	wp_enqueue_style( 'pizzumm-main', PIZZUMM_URI . '/assets/css/main.css', array( 'pizzumm-style' ), PIZZUMM_ASSETS_VERSION );
	wp_add_inline_style( 'pizzumm-main', pizzumm_inline_palette_css() );
}
add_action( 'elementor/editor/after_enqueue_styles', 'pizzumm_editor_assets' );
add_action( 'elementor/preview/enqueue_styles', 'pizzumm_editor_assets' );

/**
 * Precarica i font critici (self-hosted, nessuna richiesta esterna).
 */
function pizzumm_preload_fonts() {
	$fonts = array( 'bebas-neue-400-latin.woff2', 'roboto-300-700-latin.woff2' );

	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin />' . "\n",
			esc_url( PIZZUMM_URI . '/assets/fonts/' . $font )
		);
	}
}
add_action( 'wp_head', 'pizzumm_preload_fonts', 1 );

/**
 * Favicon dal pittogramma del brand, se in WordPress non è impostata
 * un'icona del sito (Aspetto → Personalizza → Identità del sito).
 */
function pizzumm_favicon() {
	if ( has_site_icon() ) {
		return;
	}

	$base = PIZZUMM_URI . '/assets/img/brand/';
	printf( '<link rel="icon" href="%s" sizes="any" />' . "\n", esc_url( $base . 'favicon.ico' ) );
	printf( '<link rel="icon" type="image/png" sizes="32x32" href="%s" />' . "\n", esc_url( $base . 'favicon-32.png' ) );
	printf( '<link rel="icon" type="image/png" sizes="192x192" href="%s" />' . "\n", esc_url( $base . 'favicon-192.png' ) );
	printf( '<link rel="apple-touch-icon" href="%s" />' . "\n", esc_url( $base . 'favicon-180.png' ) );
}
add_action( 'wp_head', 'pizzumm_favicon', 2 );
add_action( 'admin_head', 'pizzumm_favicon', 2 );

/**
 * Classi body utili.
 *
 * @param array $classes Classi.
 * @return array
 */
function pizzumm_body_class( $classes ) {
	if ( pizzumm_is_builder_page() ) {
		$classes[] = 'pizzumm-builder';
	}

	// L'header è trasparente solo sopra un hero a tutto schermo.
	if ( ! pizzumm_is_builder_page() && ( is_front_page() || is_page( array( 'chi-siamo', 'menu', 'contatti' ) ) ) ) {
		$classes[] = 'has-hero';
	}

	return $classes;
}
add_filter( 'body_class', 'pizzumm_body_class' );

/**
 * Larghezza contenuto.
 */
function pizzumm_content_width() {
	$GLOBALS['content_width'] = 1240;
}
add_action( 'after_setup_theme', 'pizzumm_content_width', 0 );

/**
 * Consente il caricamento di SVG ai soli amministratori (utile per il logo).
 *
 * @param array $mimes Mime types.
 * @return array
 */
function pizzumm_allow_svg( $mimes ) {
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 'pizzumm_allow_svg' );
