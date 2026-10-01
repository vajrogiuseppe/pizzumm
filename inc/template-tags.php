<?php
/**
 * Helper e componenti riutilizzabili.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Valori di default dei dati del locale.
 *
 * @return array<string,string>
 */
function pizzumm_defaults() {
	return array(
		'glovo_url'     => '',
		'phone'         => '+39 331 5027776',
		'whatsapp'      => '+39 331 5027776',
		'email'         => 'info@pizzumm.it',
		'address'       => 'Piazza Vittorio Veneto, 11, 80045 Pompei NA',
		'address_short' => 'Piazza Vittorio Veneto, 11 · 80045 Pompei NA',
		'hours'         => '',
		'instagram'     => '',
		'tiktok'        => '',
		'facebook'      => '',
		'tripadvisor'   => '',
		'company'       => 'Pizzum s.r.l.s.',
		'map_query'     => 'Piazza Vittorio Veneto 11, 80045 Pompei NA',
	);
}

/**
 * Palette di default (modificabile dal Customizer).
 *
 * Il logo usa gradienti: ogni colore ha un valore pieno — usato per testi, bordi
 * e icone — e due stop che compongono la sfumatura usata sulle superfici.
 *
 * @return array<string,array{label:string,default:string,gradient?:array{0:string,1:string}}>
 */
function pizzumm_palette() {
	return array(
		'nero'      => array(
			'label'    => __( 'Nero vulcanico', 'pizzumm' ),
			'default'  => '#111111',
			'gradient' => array( '#1E1D1C', '#0A0A0A' ),
		),
		'bianco'    => array(
			'label'   => __( 'Bianco farina', 'pizzumm' ),
			'default' => '#E2E2E1',
		),
		'giallo'    => array(
			'label'    => __( 'Giallo impasto', 'pizzumm' ),
			'default'  => '#E6C05B',
			'gradient' => array( '#F2DA8C', '#D3A238' ),
		),
		'rosso'     => array(
			'label'    => __( 'Rosso pomodoro', 'pizzumm' ),
			'default'  => '#D61119',
			'gradient' => array( '#E9282D', '#A20D13' ),
		),
		'rosso-cta' => array(
			'label'    => __( 'Rosso CTA accessibile', 'pizzumm' ),
			'default'  => '#C51316',
			'gradient' => array( '#DA1A1D', '#93060B' ),
		),
		'grigio'    => array(
			'label'    => __( 'Grigio pietra', 'pizzumm' ),
			'default'  => '#ADACAC',
			'gradient' => array( '#C8C7C6', '#8C8B8B' ),
		),
		'avorio'    => array(
			'label'   => __( 'Avorio di supporto', 'pizzumm' ),
			'default' => '#F5F1E8',
		),
	);
}

/**
 * Inclinazione delle sfumature, in gradi.
 *
 * @return int
 */
function pizzumm_gradient_angle() {
	return (int) get_theme_mod( 'pizzumm_gradient_angle', 135 );
}

/**
 * CSS inline che riscrive le variabili della palette con i valori scelti nel Customizer.
 *
 * @return string
 */
function pizzumm_inline_palette_css() {
	$vars  = array();
	$angle = pizzumm_gradient_angle();

	foreach ( pizzumm_palette() as $slug => $conf ) {
		$value = get_theme_mod( 'pizzumm_color_' . $slug, $conf['default'] );

		if ( $value && strtolower( $value ) !== strtolower( $conf['default'] ) ) {
			$vars[] = sprintf( '--%s:%s;', $slug, $value );
		}

		if ( empty( $conf['gradient'] ) ) {
			continue;
		}

		$from = get_theme_mod( 'pizzumm_grad_' . $slug . '_from', $conf['gradient'][0] );
		$to   = get_theme_mod( 'pizzumm_grad_' . $slug . '_to', $conf['gradient'][1] );

		if ( $from && strtolower( $from ) !== strtolower( $conf['gradient'][0] ) ) {
			$vars[] = sprintf( '--%s-1:%s;', $slug, $from );
		}
		if ( $to && strtolower( $to ) !== strtolower( $conf['gradient'][1] ) ) {
			$vars[] = sprintf( '--%s-2:%s;', $slug, $to );
		}
	}

	$glovo = array(
		'glovo-bg'       => array( 'pizzumm_glovo_bg', '#F0C838' ),
		'glovo-bg-hover' => array( 'pizzumm_glovo_bg_hover', '#DDB42B' ),
		'glovo-fg'       => array( 'pizzumm_glovo_fg', '#14322B' ),
		'glovo-accent'   => array( 'pizzumm_glovo_accent', '#389880' ),
	);

	foreach ( $glovo as $var => $conf ) {
		$value = get_theme_mod( $conf[0], $conf[1] );

		if ( $value && strtolower( $value ) !== strtolower( $conf[1] ) ) {
			$vars[] = sprintf( '--%s:%s;', $var, $value );
		}
	}

	if ( 135 !== $angle ) {
		foreach ( array( 'giallo', 'rosso', 'rosso-cta', 'grigio' ) as $slug ) {
			$vars[] = sprintf(
				'--grad-%1$s:linear-gradient(%2$ddeg, var(--%1$s-1) 0%%, var(--%1$s-2) 100%%);',
				$slug,
				$angle
			);
		}
	}

	if ( ! $vars ) {
		return '';
	}

	return ':root{' . implode( '', $vars ) . '}';
}

/**
 * Legge un'opzione del tema con fallback ai default.
 *
 * @param string $key Chiave.
 * @return string
 */
function pizzumm_opt( $key ) {
	$defaults = pizzumm_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	return (string) get_theme_mod( 'pizzumm_' . $key, $default );
}

/**
 * URL di un asset del tema.
 *
 * @param string $path Percorso relativo a /assets.
 * @return string
 */
function pizzumm_asset( $path ) {
	return PIZZUMM_URI . '/assets/' . ltrim( $path, '/' );
}

/**
 * URL della pagina Glovo. Se non configurata, rimanda ai contatti.
 *
 * @return string
 */
function pizzumm_glovo_url() {
	$url = pizzumm_opt( 'glovo_url' );

	return $url ? $url : pizzumm_page_url( 'contatti' );
}

/**
 * Attributi del link Glovo (nuova scheda solo quando è un link esterno).
 *
 * @return string
 */
function pizzumm_glovo_attrs() {
	return pizzumm_opt( 'glovo_url' ) ? ' target="_blank" rel="noopener noreferrer"' : '';
}

/**
 * URL di una pagina dal suo slug, con fallback.
 *
 * @param string $slug Slug della pagina.
 * @return string
 */
function pizzumm_page_url( $slug ) {
	static $cache = array();

	if ( isset( $cache[ $slug ] ) ) {
		return $cache[ $slug ];
	}

	/**
	 * Consente di rimappare gli slug delle pagine del tema.
	 *
	 * @param string $slug Slug richiesto (chi-siamo, menu, contatti).
	 */
	$slug = (string) apply_filters( 'pizzumm_page_slug', $slug );
	$page = get_page_by_path( $slug );

	if ( $page && 'publish' === get_post_status( $page ) ) {
		$cache[ $slug ] = get_permalink( $page );
	} else {
		$cache[ $slug ] = home_url( '/' . $slug . '/' );
	}

	return $cache[ $slug ];
}

/**
 * La pagina esiste ed è pubblicata?
 *
 * @param string $slug Slug.
 * @return bool
 */
function pizzumm_page_exists( $slug ) {
	$page = get_page_by_path( (string) apply_filters( 'pizzumm_page_slug', $slug ) );

	return (bool) $page && 'publish' === get_post_status( $page );
}

/**
 * Immagine: immagine in evidenza se presente, altrimenti file incluso nel tema.
 *
 * @param int    $post_id  ID del contenuto (0 per usare solo il fallback).
 * @param string $fallback Percorso relativo a /assets/img (es. "stock/menu-margherita.jpg").
 * @param string $size     Dimensione WordPress.
 * @param string $alt      Testo alternativo.
 * @param string $class    Classi CSS.
 * @param bool   $lazy     Caricamento lazy.
 */
function pizzumm_the_image( $post_id, $fallback, $size = 'pizzumm-card', $alt = '', $class = '', $lazy = true ) {
	$loading = $lazy ? 'lazy' : 'eager';

	if ( $post_id && has_post_thumbnail( $post_id ) ) {
		echo get_the_post_thumbnail(
			$post_id,
			$size,
			array(
				'class'   => $class,
				'loading' => $loading,
				'alt'     => esc_attr( $alt ? $alt : get_the_title( $post_id ) ),
			)
		);
		return;
	}

	$is_portrait = false !== strpos( $fallback, 'story' ) || false !== strpos( $fallback, 'thermopolium' );

	printf(
		'<img src="%1$s" alt="%2$s" class="%3$s" loading="%4$s" decoding="async" width="%5$d" height="%6$d" />',
		esc_url( pizzumm_asset( 'img/' . $fallback ) ),
		esc_attr( $alt ),
		esc_attr( $class ),
		esc_attr( $loading ),
		1200,
		$is_portrait ? 1500 : 900
	);
}

/**
 * Bottone.
 *
 * @param string $label   Testo.
 * @param string $url     URL.
 * @param string $variant Variante (red|yellow|dark|ghost).
 * @param string $extra   Attributi extra già validati.
 * @param string $icon    Nome icona opzionale.
 */
function pizzumm_button( $label, $url, $variant = 'red', $extra = '', $icon = '' ) {
	$classes = array( 'btn' );

	// Il giallo è la variante predefinita: nessun modificatore.
	if ( $variant && 'yellow' !== $variant ) {
		$classes[] = 'btn--' . $variant;
	}

	printf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s%5$s</a>',
		esc_attr( implode( ' ', $classes ) ),
		esc_url( $url ),
		$extra, // phpcs:ignore WordPress.Security.EscapeOutput -- attributi controllati dal tema.
		esc_html( $label ),
		$icon ? pizzumm_icon( $icon ) : '' // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

/**
 * Bottone "Ordina ora" verso Glovo, con il logo del servizio.
 *
 * Il logo va caricato dal Customizer (brand kit ufficiale di Glovo). Finché
 * non è presente, il pulsante mostra il nome del servizio come testo.
 *
 * @param string $label   Testo del bottone.
 * @param string $variant Variante.
 * @param string $extra   Classi aggiuntive (es. "btn--lg").
 */
function pizzumm_glovo_button( $label = '', $variant = 'red', $extra = '' ) {
	$label   = $label ? $label : __( 'Ordina ora', 'pizzumm' );
	$logo    = get_theme_mod( 'pizzumm_glovo_logo', '' );
	$classes = array( 'btn', 'btn--glovo' );

	if ( $variant && 'yellow' !== $variant ) {
		$classes[] = 'btn--' . $variant;
	}
	if ( $extra ) {
		$classes[] = $extra;
	}

	$mark = $logo
		? sprintf(
			'<img class="btn__logo" src="%s" alt="Glovo" loading="lazy" decoding="async" />',
			esc_url( $logo )
		)
		: '<span class="btn__dot" aria-hidden="true">' . pizzumm_icon( 'scooter' ) . '</span><span class="btn__wordmark">Glovo</span>';

	printf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s%5$s</a>',
		esc_attr( implode( ' ', $classes ) ),
		esc_url( pizzumm_glovo_url() ),
		pizzumm_glovo_attrs(), // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( $label ),
		$mark // phpcs:ignore WordPress.Security.EscapeOutput -- markup controllato dal tema.
	);
}

/**
 * Menu principale con fallback sulle quattro pagine del sito.
 */
function pizzumm_primary_nav() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		return;
	}

	$pages = array(
		''          => __( 'Home', 'pizzumm' ),
		'chi-siamo' => __( 'Chi siamo', 'pizzumm' ),
		'menu'      => __( 'Menu', 'pizzumm' ),
		'contatti'  => __( 'Contatti', 'pizzumm' ),
	);

	echo '<ul>';

	foreach ( $pages as $slug => $label ) {
		// La home c'è sempre; le altre voci compaiono solo se la pagina esiste.
		if ( $slug && ! pizzumm_page_exists( $slug ) ) {
			continue;
		}

		$url     = $slug ? pizzumm_page_url( $slug ) : home_url( '/' );
		$current = $slug ? is_page( $slug ) : is_front_page();

		printf(
			'<li class="%1$s"><a href="%2$s"%3$s>%4$s</a></li>',
			esc_attr( $current ? 'current_page_item' : '' ),
			esc_url( $url ),
			$current ? ' aria-current="page"' : '',
			esc_html( $label )
		);
	}

	echo '</ul>';
}

/**
 * Righe degli orari.
 *
 * @return string[]
 */
function pizzumm_hours_lines() {
	$raw = pizzumm_opt( 'hours' );

	if ( ! $raw ) {
		return array();
	}

	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $raw ) ) ) );
}

/**
 * Social configurati.
 *
 * @return array<string,array{url:string,icon:string}>
 */
function pizzumm_socials( $include_empty = false ) {
	$all = array(
		'Facebook'    => array( 'url' => pizzumm_opt( 'facebook' ), 'icon' => 'facebook' ),
		'Instagram'   => array( 'url' => pizzumm_opt( 'instagram' ), 'icon' => 'instagram' ),
		'TikTok'      => array( 'url' => pizzumm_opt( 'tiktok' ), 'icon' => 'tiktok' ),
		'Tripadvisor' => array( 'url' => pizzumm_opt( 'tripadvisor' ), 'icon' => 'tripadvisor' ),
	);

	if ( $include_empty ) {
		return $all;
	}

	return array_filter(
		$all,
		static function ( $item ) {
			return ! empty( $item['url'] );
		}
	);
}

/**
 * Il pulsante Glovo nella barra del menu è attivo?
 * Spento di default finché l'account Glovo non è operativo.
 *
 * @return bool
 */
function pizzumm_glovo_in_header() {
	return (bool) apply_filters( 'pizzumm_glovo_in_header', get_theme_mod( 'pizzumm_glovo_header', false ) );
}

/**
 * Numero di telefono in formato "tel:".
 *
 * @param string $number Numero.
 * @return string
 */
function pizzumm_tel_href( $number ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $number );
}

/**
 * Link WhatsApp (accetta numero o URL completo).
 *
 * @return string
 */
function pizzumm_whatsapp_url() {
	$value = pizzumm_opt( 'whatsapp' );

	if ( ! $value ) {
		return '';
	}

	if ( 0 === strpos( $value, 'http' ) ) {
		return $value;
	}

	return 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $value );
}

/**
 * URL della mappa Google (embed).
 *
 * @return string
 */
function pizzumm_map_embed_url() {
	$custom = get_theme_mod( 'pizzumm_map_embed', '' );

	if ( $custom ) {
		return $custom;
	}

	return 'https://www.google.com/maps?q=' . rawurlencode( pizzumm_opt( 'map_query' ) ) . '&output=embed';
}

/**
 * URL della mappa Google (link esterno).
 *
 * @return string
 */
function pizzumm_map_link_url() {
	return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( pizzumm_opt( 'map_query' ) );
}

/**
 * Sopratitolo + titolo + testo di sezione.
 *
 * @param string $kicker Sopratitolo.
 * @param string $title  Titolo.
 * @param string $text   Testo.
 * @param string $mod    Modificatore ("center"|"split").
 */
function pizzumm_section_head( $kicker, $title, $text = '', $mod = '' ) {
	$class = 'section__head' . ( $mod ? ' section__head--' . $mod : '' );

	echo '<div class="' . esc_attr( $class ) . '">';

	if ( $kicker ) {
		echo '<span class="kicker" data-anim>' . esc_html( $kicker ) . '</span>';
	}
	if ( $title ) {
		echo '<h2 class="section__title" data-split>' . esc_html( $title ) . '</h2>';
	}
	if ( $text ) {
		echo '<p class="lead" data-anim data-anim-delay="0.1">' . esc_html( $text ) . '</p>';
	}

	echo '</div>';
}

/**
 * Testo modificabile via filtro: consente di sovrascrivere il copy da un child theme
 * o da un plugin senza toccare i template.
 *
 * @param string $key     Identificativo del testo.
 * @param string $default Testo predefinito.
 * @return string
 */
function pizzumm_text( $key, $default ) {
	/**
	 * Filtra i testi predefiniti del tema.
	 *
	 * @param string $default Testo.
	 * @param string $key     Chiave.
	 */
	return (string) apply_filters( 'pizzumm_text', $default, $key );
}

/**
 * Banda CTA finale, usata in fondo a tutte le pagine.
 *
 * @param string $title Titolo.
 * @param string $text  Testo.
 * @param string $kicker Sopratitolo.
 */
function pizzumm_cta_band( $title, $text = '', $kicker = '' ) {
	$kicker = $kicker ? $kicker : __( 'Pizzumm ti aspetta', 'pizzumm' );
	?>
	<div class="cta-band">
		<div class="cta-band__bg" aria-hidden="true">
			<img src="<?php echo esc_url( pizzumm_asset( 'img/stock/menu-diavola.jpg' ) ); ?>"
				alt="" width="1200" height="900" loading="lazy" decoding="async" />
		</div>

		<div>
			<span class="kicker" data-anim><?php echo esc_html( $kicker ); ?></span>
			<h2 data-split><?php echo esc_html( $title ); ?></h2>
			<?php if ( $text ) : ?>
				<p class="lead" data-anim data-anim-delay="0.1"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
		</div>

		<div class="btn-row" data-anim data-anim-delay="0.2">
			<?php
			pizzumm_glovo_button( __( 'Ordina su', 'pizzumm' ), 'yellow', 'btn--lg' );
			pizzumm_button( __( 'Come raggiungerci', 'pizzumm' ), pizzumm_page_url( 'contatti' ), 'ghost', '', 'map' );
			?>
		</div>
	</div>
	<?php
}

/**
 * Prodotti da mostrare nello slider infinito della home.
 *
 * @param int $limit Numero massimo.
 * @return WP_Post[]
 */
function pizzumm_showcase_products( $limit = 8 ) {
	return get_posts( array(
		'post_type'      => 'pizzumm_prodotto',
		'posts_per_page' => $limit,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	) );
}
