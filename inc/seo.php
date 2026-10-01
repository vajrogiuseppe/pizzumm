<?php
/**
 * SEO locale: title tag, meta description, Open Graph e dati strutturati.
 * Si disattiva automaticamente se è attivo un plugin SEO.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * È attivo un plugin SEO che gestisce già title e meta?
 *
 * @return bool
 */
function pizzumm_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' )        // Yoast.
		|| defined( 'RANK_MATH_VERSION' )    // Rank Math.
		|| defined( 'SEOPRESS_VERSION' )     // SEOPress.
		|| defined( 'AIOSEO_VERSION' );      // All in One SEO.
}

/**
 * Valori SEO predefiniti per le pagine del sito, presi dal brief.
 *
 * @return array<string,array{title:string,description:string}>
 */
function pizzumm_seo_defaults() {
	return array(
		'front' => array(
			'title'       => 'Pizzumm | Pizza in teglia a Pompei',
			'description' => 'Scopri Pizzumm, la pizza in teglia leggera e croccante nel cuore di Pompei. Consulta il menu, ordina su Glovo o vieni a trovarci.',
		),
		'chi-siamo' => array(
			'title'       => 'Chi siamo | La storia di Pizzumm a Pompei',
			'description' => 'Dai thermopolia alla pizza in teglia contemporanea: scopri la storia, il nome e l’idea che hanno dato vita a Pizzumm.',
		),
		'menu' => array(
			'title'       => 'Menu Pizzumm | Pizza in teglia a Pompei',
			'description' => 'Scopri il menu Pizzumm: pizze in teglia classiche, speciali e proposte stagionali. Scegli la tua fetta e ordina su Glovo.',
		),
		'contatti' => array(
			'title'       => 'Contatti e orari | Pizzumm Pompei',
			'description' => 'Trova Pizzumm in Piazza Vittorio Veneto 11 a Pompei. Consulta orari e contatti, apri la mappa o ordina tramite Glovo.',
		),
	);
}

/**
 * Chiave SEO della pagina corrente.
 *
 * @return string
 */
function pizzumm_seo_key() {
	if ( is_front_page() ) {
		return 'front';
	}

	if ( is_page() ) {
		$slug = get_post_field( 'post_name', get_the_ID() );

		if ( isset( pizzumm_seo_defaults()[ $slug ] ) ) {
			return $slug;
		}
	}

	return '';
}

/**
 * Title tag personalizzato.
 *
 * @param string $title Titolo.
 * @return string
 */
function pizzumm_document_title( $title ) {
	if ( pizzumm_seo_plugin_active() ) {
		return $title;
	}

	$custom = get_post_meta( get_the_ID(), '_pizzumm_seo_title', true );

	if ( $custom ) {
		return $custom;
	}

	$key = pizzumm_seo_key();

	if ( $key ) {
		return pizzumm_seo_defaults()[ $key ]['title'];
	}

	return $title;
}
add_filter( 'pre_get_document_title', 'pizzumm_document_title', 20 );

/**
 * Meta description della pagina corrente.
 *
 * @return string
 */
function pizzumm_meta_description() {
	$custom = get_post_meta( get_the_ID(), '_pizzumm_seo_description', true );

	if ( $custom ) {
		return $custom;
	}

	$key = pizzumm_seo_key();

	if ( $key ) {
		return pizzumm_seo_defaults()[ $key ]['description'];
	}

	if ( is_singular() ) {
		$excerpt = get_the_excerpt();

		if ( $excerpt ) {
			return wp_trim_words( wp_strip_all_tags( $excerpt ), 30, '…' );
		}
	}

	return (string) get_bloginfo( 'description' );
}

/**
 * Stampa meta description e Open Graph.
 */
function pizzumm_head_meta() {
	if ( pizzumm_seo_plugin_active() ) {
		return;
	}

	$description = pizzumm_meta_description();
	$title       = wp_get_document_title();
	$image       = '';

	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( get_the_ID(), 'pizzumm-wide' );
	} elseif ( get_theme_mod( 'pizzumm_hero_poster' ) ) {
		$image = get_theme_mod( 'pizzumm_hero_poster' );
	} else {
		$image = pizzumm_asset( 'img/stock/hero-pizzumm.jpg' );
	}

	printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:type" content="%s" />' . "\n", is_singular() ? 'article' : 'website' );
	printf( '<meta property="og:locale" content="%s" />' . "\n", esc_attr( get_locale() ) );
	printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( pizzumm_current_url() ) );
	printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $image ) );
	printf( '<meta name="twitter:card" content="summary_large_image" />' . "\n" );
}
add_action( 'wp_head', 'pizzumm_head_meta', 2 );

/**
 * URL canonico della pagina corrente.
 *
 * @return string
 */
function pizzumm_current_url() {
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_singular() ) {
		return get_permalink();
	}

	return home_url( add_query_arg( array() ) );
}

/**
 * Dati strutturati Restaurant + WebSite.
 */
function pizzumm_structured_data() {
	if ( ! is_front_page() && ! is_page( array( 'contatti', 'menu' ) ) ) {
		return;
	}

	$address = pizzumm_opt( 'address' );
	$phone   = pizzumm_opt( 'phone' );
	$email   = pizzumm_opt( 'email' );

	$data = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Restaurant',
		'name'        => get_bloginfo( 'name' ),
		'description' => pizzumm_seo_defaults()['front']['description'],
		'servesCuisine' => array( 'Pizza', 'Italiana' ),
		'url'         => home_url( '/' ),
		'image'       => pizzumm_asset( 'img/stock/hero-pizzumm.jpg' ),
		'hasMenu'     => pizzumm_page_url( 'menu' ),
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => 'Piazza Vittorio Veneto, 11',
			'postalCode'      => '80045',
			'addressLocality' => 'Pompei',
			'addressRegion'   => 'NA',
			'addressCountry'  => 'IT',
		),
	);

	if ( $address ) {
		$data['address']['name'] = $address;
	}
	if ( $phone ) {
		$data['telephone'] = $phone;
	}
	if ( $email ) {
		$data['email'] = $email;
	}

	$socials = pizzumm_socials();

	if ( $socials ) {
		$data['sameAs'] = array_values( wp_list_pluck( $socials, 'url' ) );
	}

	$hours = pizzumm_hours_lines();

	if ( $hours ) {
		$data['openingHours'] = $hours;
	}

	if ( pizzumm_opt( 'glovo_url' ) ) {
		$data['potentialAction'] = array(
			'@type'  => 'OrderAction',
			'target' => pizzumm_opt( 'glovo_url' ),
		);
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'pizzumm_structured_data', 5 );

/**
 * Metabox SEO su pagine, articoli e prodotti.
 */
function pizzumm_seo_metabox() {
	if ( pizzumm_seo_plugin_active() ) {
		return;
	}

	foreach ( array( 'page', 'post', 'pizzumm_prodotto' ) as $screen ) {
		add_meta_box(
			'pizzumm_seo',
			__( 'SEO — titolo e descrizione', 'pizzumm' ),
			'pizzumm_render_seo_metabox',
			$screen,
			'normal',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'pizzumm_seo_metabox' );

/**
 * Render del metabox SEO.
 *
 * @param WP_Post $post Contenuto.
 */
function pizzumm_render_seo_metabox( $post ) {
	wp_nonce_field( 'pizzumm_save_seo', 'pizzumm_seo_nonce' );

	$title = get_post_meta( $post->ID, '_pizzumm_seo_title', true );
	$desc  = get_post_meta( $post->ID, '_pizzumm_seo_description', true );
	$key   = isset( pizzumm_seo_defaults()[ $post->post_name ] ) ? $post->post_name : '';

	if ( $key ) {
		$defaults = pizzumm_seo_defaults()[ $key ];
		printf(
			'<p class="description">%s<br /><code>%s</code><br /><code>%s</code></p>',
			esc_html__( 'Valori predefiniti usati se lasci i campi vuoti:', 'pizzumm' ),
			esc_html( $defaults['title'] ),
			esc_html( $defaults['description'] )
		);
	}

	printf(
		'<p><label for="pizzumm_seo_title"><strong>%1$s</strong></label><br />
		<input type="text" id="pizzumm_seo_title" name="pizzumm_seo_title" value="%2$s" style="width:100%%" maxlength="70" /></p>
		<p><label for="pizzumm_seo_description"><strong>%3$s</strong></label><br />
		<textarea id="pizzumm_seo_description" name="pizzumm_seo_description" rows="3" style="width:100%%" maxlength="170">%4$s</textarea></p>',
		esc_html__( 'Title tag (max ~60 caratteri)', 'pizzumm' ),
		esc_attr( $title ),
		esc_html__( 'Meta description (max ~155 caratteri)', 'pizzumm' ),
		esc_textarea( $desc )
	);
}

/**
 * Salvataggio dei campi SEO.
 *
 * @param int $post_id ID contenuto.
 */
function pizzumm_save_seo_meta( $post_id ) {
	if ( ! isset( $_POST['pizzumm_seo_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['pizzumm_seo_nonce'] ) ), 'pizzumm_save_seo' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'_pizzumm_seo_title'       => isset( $_POST['pizzumm_seo_title'] ) ? sanitize_text_field( wp_unslash( $_POST['pizzumm_seo_title'] ) ) : '',
		'_pizzumm_seo_description' => isset( $_POST['pizzumm_seo_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['pizzumm_seo_description'] ) ) : '',
	);

	foreach ( $fields as $key => $value ) {
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post', 'pizzumm_save_seo_meta' );

/**
 * Dati strutturati FAQ nella pagina Contatti.
 */
function pizzumm_faq_schema() {
	if ( ! is_page( 'contatti' ) || pizzumm_is_builder_page() ) {
		return;
	}

	$faq = array(
		array( 'Posso ordinare dal tavolo?', 'Sì. Nel locale puoi ordinare e pagare in digitale seguendo le indicazioni presenti sul tavolo.' ),
		array( 'Fate consegna a domicilio?', 'Sì. Puoi ordinare Pizzumm tramite Glovo.' ),
		array( 'Posso ordinare da asporto?', 'Sì. Verifica su Glovo le modalità disponibili oppure contattaci.' ),
	);

	$items = array();

	foreach ( $faq as $entry ) {
		$items[] = array(
			'@type'          => 'Question',
			'name'           => $entry[0],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $entry[1],
			),
		);
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode(
			array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $items,
			),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		)
	);
}
add_action( 'wp_head', 'pizzumm_faq_schema', 6 );
