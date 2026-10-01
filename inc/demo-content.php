<?php
/**
 * Contenuti di partenza: pagine, menu di navigazione, categorie e prodotti demo.
 * Vengono creati una sola volta all'attivazione del tema.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Struttura delle pagine del sito.
 *
 * @return array<string,array{title:string,template:string}>
 */
function pizzumm_site_pages() {
	return array(
		'home'      => array( 'title' => 'Home', 'template' => '' ),
		'chi-siamo' => array( 'title' => 'Chi siamo', 'template' => 'page-chi-siamo.php' ),
		'menu'      => array( 'title' => 'Menu', 'template' => 'page-menu.php' ),
		'contatti'  => array( 'title' => 'Contatti', 'template' => 'page-contatti.php' ),
	);
}

/**
 * Categorie del menu previste dal brief.
 *
 * @return array<int,array{slug:string,name:string,desc:string,ordine:int}>
 */
function pizzumm_demo_categories() {
	return array(
		array(
			'slug'   => 'classiche',
			'name'   => 'Le classiche',
			'desc'   => 'I gusti che non hanno bisogno di presentazioni.',
			'ordine' => 1,
		),
		array(
			'slug'   => 'pizzumm',
			'name'   => 'Le Pizzumm',
			'desc'   => 'Le combinazioni che raccontano meglio il nostro modo di fare pizza.',
			'ordine' => 2,
		),
		array(
			'slug'   => 'stagionali',
			'name'   => 'Le stagionali',
			'desc'   => 'Ingredienti del momento, nuove idee e fette che cambiano con il tempo.',
			'ordine' => 3,
		),
		array(
			'slug'   => 'fritti-sfizi',
			'name'   => 'Fritti & sfizi',
			'desc'   => 'Da inserire solo se questa categoria sarà realmente presente nel menu.',
			'ordine' => 4,
		),
		array(
			'slug'   => 'bevande',
			'name'   => 'Bevande',
			'desc'   => 'Le proposte da abbinare alla tua fetta.',
			'ordine' => 5,
		),
	);
}

/**
 * Prodotti demo. I prezzi sono segnaposto da sostituire.
 *
 * @return array<int,array<string,mixed>>
 */
function pizzumm_demo_products() {
	return array(
		array(
			'title'  => 'Margherita',
			'cat'    => 'classiche',
			'prezzo' => '3,50 €',
			'ing'    => 'Pomodoro, fiordilatte, basilico, olio EVO',
			'all'    => 'glutine, latte',
			'img'    => 'stock/menu-margherita.jpg',
			'order'  => 1,
		),
		array(
			'title'  => 'Marinara',
			'cat'    => 'classiche',
			'prezzo' => '3,00 €',
			'ing'    => 'Pomodoro, aglio, origano, olio EVO',
			'all'    => 'glutine',
			'badge'  => 'Veg',
			'btype'  => 'veg',
			'img'    => 'stock/menu-marinara.jpg',
			'order'  => 2,
		),
		array(
			'title'  => 'Diavola',
			'cat'    => 'classiche',
			'prezzo' => '4,00 €',
			'ing'    => 'Pomodoro, fiordilatte, salame piccante',
			'all'    => 'glutine, latte',
			'badge'  => 'Piccante',
			'img'    => 'stock/menu-diavola.jpg',
			'order'  => 3,
		),
		array(
			'title'  => 'Bianca al rosmarino',
			'cat'    => 'classiche',
			'prezzo' => '2,50 €',
			'ing'    => 'Olio EVO, rosmarino, sale',
			'all'    => 'glutine',
			'badge'  => 'Veg',
			'btype'  => 'veg',
			'img'    => 'stock/menu-bianca.jpg',
			'order'  => 4,
		),
		array(
			'title'  => 'Crudo e burrata',
			'cat'    => 'pizzumm',
			'prezzo' => '5,50 €',
			'ing'    => 'Base bianca, burrata, prosciutto crudo, basilico',
			'all'    => 'glutine, latte',
			'img'    => 'stock/menu-crudo.jpg',
			'order'  => 1,
		),
		array(
			'title'  => 'Quattro formaggi',
			'cat'    => 'pizzumm',
			'prezzo' => '4,80 €',
			'ing'    => 'Fiordilatte, gorgonzola, provola, grana',
			'all'    => 'glutine, latte',
			'img'    => 'stock/menu-formaggi.jpg',
			'order'  => 2,
		),
		array(
			'title'  => 'Orto pompeiano',
			'cat'    => 'pizzumm',
			'prezzo' => '4,50 €',
			'ing'    => 'Pomodoro, zucchine, melanzane, peperoni, basilico',
			'all'    => 'glutine',
			'badge'  => 'Veg',
			'btype'  => 'veg',
			'img'    => 'stock/menu-verdure.jpg',
			'order'  => 3,
		),
		array(
			'title'  => 'Fetta del momento',
			'cat'    => 'stagionali',
			'prezzo' => '5,00 €',
			'ing'    => 'Cambia ogni settimana con gli ingredienti di stagione. Chiedi al banco.',
			'all'    => 'glutine',
			'badge'  => 'Novità',
			'btype'  => 'new',
			'img'    => 'stock/menu-stagionale.jpg',
			'order'  => 1,
		),
		array(
			'title'  => 'Fritti misti',
			'cat'    => 'fritti-sfizi',
			'prezzo' => '4,00 €',
			'ing'    => 'Selezione di fritti del giorno',
			'all'    => 'glutine, latte, uova',
			'img'    => 'stock/menu-fritti.jpg',
			'order'  => 1,
		),
		array(
			'title'  => 'Birra in bottiglia',
			'cat'    => 'bevande',
			'prezzo' => '3,50 €',
			'ing'    => 'Chiara o ambrata, 33 cl',
			'all'    => 'glutine',
			'img'    => 'stock/menu-bevande.jpg',
			'order'  => 1,
		),
		array(
			'title'  => 'Acqua naturale o frizzante',
			'cat'    => 'bevande',
			'prezzo' => '1,00 €',
			'ing'    => '50 cl',
			'img'    => 'stock/menu-bevande.jpg',
			'order'  => 2,
		),
	);
}

/**
 * Crea i contenuti di partenza.
 */
function pizzumm_install_demo_content() {
	pizzumm_register_menu_cpt();

	// Crea prima il modulo CF7 predefinito, così il suo ID è già disponibile
	// quando popoliamo il contenuto Gutenberg della pagina Contatti.
	pizzumm_create_cf7_form();

	// Pagine e menu di navigazione: ricreati a ogni attivazione se mancano,
	// così i link dell'header puntano sempre a qualcosa di reale.
	$page_ids = pizzumm_create_pages();
	pizzumm_create_nav_menu( $page_ids );

	// I contenuti demo del menu si creano una sola volta.
	if ( ! get_option( 'pizzumm_demo_installed' ) ) {
		pizzumm_create_menu_content();
		update_option( 'pizzumm_demo_installed', PIZZUMM_VERSION );
	}

	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'pizzumm_install_demo_content' );

/**
 * Crea le quattro pagine del sito e imposta la home.
 *
 * @return array<string,int> Slug => ID.
 */
function pizzumm_create_pages() {
	$ids = array();

	foreach ( pizzumm_site_pages() as $slug => $conf ) {
		$existing = get_page_by_path( $slug );

		if ( $existing ) {
			$ids[ $slug ] = $existing->ID;
			continue;
		}

		$id = wp_insert_post( array(
			'post_title'   => $conf['title'],
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '',
		) );

		if ( is_wp_error( $id ) || ! $id ) {
			continue;
		}

		if ( $conf['template'] ) {
			update_post_meta( $id, '_wp_page_template', $conf['template'] );
		}

		update_post_meta( $id, '_pizzumm_default_layout', 'auto' );

		$ids[ $slug ] = $id;
	}

	if ( isset( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
	}

	return $ids;
}

/**
 * Crea categorie e prodotti del menu.
 */
function pizzumm_create_menu_content() {
	$term_ids = array();

	foreach ( pizzumm_demo_categories() as $cat ) {
		$term = term_exists( $cat['slug'], 'pizzumm_categoria' );

		if ( ! $term ) {
			$term = wp_insert_term( $cat['name'], 'pizzumm_categoria', array(
				'slug'        => $cat['slug'],
				'description' => $cat['desc'],
			) );
		}

		if ( is_wp_error( $term ) ) {
			continue;
		}

		$term_id                 = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
		$term_ids[ $cat['slug'] ] = $term_id;

		update_term_meta( $term_id, 'pizzumm_ordine', $cat['ordine'] );
	}

	foreach ( pizzumm_demo_products() as $product ) {
		$existing = get_posts( array(
			'post_type'              => 'pizzumm_prodotto',
			'title'                  => $product['title'],
			'posts_per_page'         => 1,
			'post_status'            => 'any',
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		) );

		if ( $existing ) {
			continue;
		}

		$id = wp_insert_post( array(
			'post_title'  => $product['title'],
			'post_status' => 'publish',
			'post_type'   => 'pizzumm_prodotto',
			'menu_order'  => isset( $product['order'] ) ? (int) $product['order'] : 0,
		) );

		if ( is_wp_error( $id ) || ! $id ) {
			continue;
		}

		if ( isset( $term_ids[ $product['cat'] ] ) ) {
			wp_set_object_terms( $id, array( $term_ids[ $product['cat'] ] ), 'pizzumm_categoria' );
		}

		update_post_meta( $id, 'pizzumm_prezzo', $product['prezzo'] );
		update_post_meta( $id, 'pizzumm_ingredienti', $product['ing'] );
		update_post_meta( $id, '_pizzumm_fallback_image', $product['img'] );

		if ( ! empty( $product['all'] ) ) {
			update_post_meta( $id, 'pizzumm_allergeni', $product['all'] );
		}
		if ( ! empty( $product['badge'] ) ) {
			update_post_meta( $id, 'pizzumm_badge', $product['badge'] );
		}
		if ( ! empty( $product['btype'] ) ) {
			update_post_meta( $id, 'pizzumm_badge_tipo', $product['btype'] );
		}
	}
}

/**
 * Crea il menu di navigazione principale.
 *
 * @param array<string,int> $page_ids Pagine create.
 */
function pizzumm_create_nav_menu( $page_ids ) {
	$menu_name = __( 'Menu principale', 'pizzumm' );
	$menu      = wp_get_nav_menu_object( $menu_name );
	$locations = get_theme_mod( 'nav_menu_locations', array() );

	// Il menu esiste già: basta assicurarsi che sia assegnato alla posizione.
	if ( $menu ) {
		if ( empty( $locations['primary'] ) ) {
			$locations['primary'] = $menu->term_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}

		return;
	}

	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );

		if ( is_wp_error( $menu_id ) ) {
			return;
		}

		$labels = array(
			'home'      => __( 'Home', 'pizzumm' ),
			'chi-siamo' => __( 'Chi siamo', 'pizzumm' ),
			'menu'      => __( 'Menu', 'pizzumm' ),
			'contatti'  => __( 'Contatti', 'pizzumm' ),
		);

		$position = 1;

		foreach ( $labels as $slug => $label ) {
			if ( ! isset( $page_ids[ $slug ] ) ) {
				continue;
			}

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => $label,
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page_ids[ $slug ],
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $position,
			) );

			$position++;
		}

		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}

/**
 * Crea un modulo Contact Form 7 di partenza (se il plugin è attivo) e lo
 * espone tramite un filter che il tema legge nel fallback della pagina
 * Contatti e nei pattern.
 */
function pizzumm_create_cf7_form() {
	if ( ! post_type_exists( 'wpcf7_contact_form' ) ) {
		return;
	}

	$existing_id = (int) get_option( 'pizzumm_default_cf7_id', 0 );

	if ( $existing_id && get_post( $existing_id ) ) {
		return;
	}

	$form_body = <<<'CF7'
<p><label>Nome e cognome
[text* your-name autocomplete:name] </label></p>

<p><label>E-mail
[email* your-email autocomplete:email] </label></p>

<p><label>Telefono (facoltativo)
[tel your-tel autocomplete:tel] </label></p>

<p><label>Messaggio
[textarea* your-message] </label></p>

<p>[acceptance consenso] Ho letto l'informativa privacy e acconsento al trattamento dei dati.[/acceptance]</p>

<p>[submit "Invia il messaggio"]</p>
CF7;

	$post_id = wp_insert_post( array(
		'post_type'   => 'wpcf7_contact_form',
		'post_status' => 'publish',
		'post_title'  => __( 'Contatti Pizzumm', 'pizzumm' ),
	) );

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return;
	}

	// Struttura minima riconosciuta da CF7 per un form nuovo.
	update_post_meta( $post_id, '_form', $form_body );
	update_post_meta( $post_id, '_mail', array(
		'active'             => true,
		'subject'            => '[' . get_bloginfo( 'name' ) . '] Nuovo messaggio da [your-name]',
		'sender'             => get_bloginfo( 'name' ) . ' <wordpress@' . preg_replace( '/^www\./i', '', (string) wp_parse_url( home_url(), PHP_URL_HOST ) ) . '>',
		'recipient'          => pizzumm_opt( 'email' ) ? pizzumm_opt( 'email' ) : get_option( 'admin_email' ),
		'body'               => "Nuovo messaggio dal sito.\n\nNome: [your-name]\nE-mail: [your-email]\nTelefono: [your-tel]\n\nMessaggio:\n[your-message]",
		'additional_headers' => 'Reply-To: [your-email]',
		'attachments'        => '',
		'use_html'           => false,
		'exclude_blank'      => false,
	) );

	update_option( 'pizzumm_default_cf7_id', $post_id );

	// Espone l'ID a template e pattern.
	add_filter( 'pizzumm_default_cf7_id', static function () use ( $post_id ) {
		return $post_id;
	} );
}

/**
 * Applica il filter pizzumm_default_cf7_id anche fuori dall'installer.
 */
function pizzumm_apply_default_cf7_id( $id ) {
	if ( $id ) {
		return $id;
	}

	return (int) get_option( 'pizzumm_default_cf7_id', 0 );
}
add_filter( 'pizzumm_default_cf7_id', 'pizzumm_apply_default_cf7_id' );

/**
 * Se CF7 viene attivato dopo il tema, il modulo predefinito potrebbe non essere
 * stato creato: ci riprova in admin, una volta sola.
 */
function pizzumm_late_create_cf7_form() {
	if ( ! is_admin() || wp_doing_ajax() ) {
		return;
	}
	if ( ! post_type_exists( 'wpcf7_contact_form' ) ) {
		return;
	}
	if ( get_option( 'pizzumm_default_cf7_id' ) ) {
		return;
	}

	pizzumm_create_cf7_form();
}
add_action( 'admin_init', 'pizzumm_late_create_cf7_form' );

/**
 * Avviso in bacheca al primo accesso, con le cose da configurare.
 */
function pizzumm_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! get_option( 'pizzumm_demo_installed' ) ) {
		return;
	}

	$missing = array();

	if ( ! pizzumm_opt( 'glovo_url' ) ) {
		$missing[] = __( 'link Glovo', 'pizzumm' );
	}
	if ( ! pizzumm_opt( 'phone' ) ) {
		$missing[] = __( 'telefono', 'pizzumm' );
	}
	if ( ! pizzumm_opt( 'email' ) ) {
		$missing[] = __( 'e-mail', 'pizzumm' );
	}
	if ( ! pizzumm_opt( 'hours' ) ) {
		$missing[] = __( 'orari', 'pizzumm' );
	}
	if ( ! pizzumm_socials() ) {
		$missing[] = __( 'social', 'pizzumm' );
	}

	if ( ! $missing ) {
		return;
	}

	printf(
		'<div class="notice notice-info is-dismissible"><p><strong>%1$s</strong> %2$s <em>%3$s</em>. <a href="%4$s">%5$s</a></p></div>',
		esc_html__( 'Tema Pizzumm:', 'pizzumm' ),
		esc_html__( 'restano da inserire', 'pizzumm' ),
		esc_html( implode( ', ', $missing ) ),
		esc_url( admin_url( 'customize.php' ) ),
		esc_html__( 'Apri il Personalizza →', 'pizzumm' )
	);
}
add_action( 'admin_notices', 'pizzumm_admin_notice' );
