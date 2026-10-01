<?php
/**
 * Custom post type "Menu Pizzumm" (prodotti) + tassonomia delle categorie.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra CPT e tassonomia.
 */
function pizzumm_register_menu_cpt() {

	register_post_type( 'pizzumm_prodotto', array(
		'labels'       => array(
			'name'          => __( 'Menu Pizzumm', 'pizzumm' ),
			'singular_name' => __( 'Prodotto', 'pizzumm' ),
			'add_new'       => __( 'Aggiungi prodotto', 'pizzumm' ),
			'add_new_item'  => __( 'Aggiungi nuovo prodotto', 'pizzumm' ),
			'edit_item'     => __( 'Modifica prodotto', 'pizzumm' ),
			'new_item'      => __( 'Nuovo prodotto', 'pizzumm' ),
			'view_item'     => __( 'Vedi prodotto', 'pizzumm' ),
			'search_items'  => __( 'Cerca nel menu', 'pizzumm' ),
			'not_found'     => __( 'Nessun prodotto trovato', 'pizzumm' ),
			'menu_name'     => __( 'Menu Pizzumm', 'pizzumm' ),
			'all_items'     => __( 'Tutti i prodotti', 'pizzumm' ),
		),
		'description'   => __( 'Pizze, fritti e bevande mostrati nella pagina Menu.', 'pizzumm' ),
		'public'        => true,
		'has_archive'   => false,
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-food',
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt', 'custom-fields' ),
		'rewrite'       => array( 'slug' => 'prodotto' ),
	) );

	register_taxonomy( 'pizzumm_categoria', 'pizzumm_prodotto', array(
		'labels'            => array(
			'name'          => __( 'Categorie del menu', 'pizzumm' ),
			'singular_name' => __( 'Categoria', 'pizzumm' ),
			'add_new_item'  => __( 'Aggiungi categoria', 'pizzumm' ),
			'edit_item'     => __( 'Modifica categoria', 'pizzumm' ),
			'menu_name'     => __( 'Categorie', 'pizzumm' ),
		),
		'hierarchical'      => true,
		'public'            => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => 'categoria-menu' ),
	) );
}
add_action( 'init', 'pizzumm_register_menu_cpt' );

/**
 * Campi personalizzati del prodotto.
 *
 * @return array<string,array{label:string,type:string,help?:string}>
 */
function pizzumm_product_fields() {
	return array(
		'pizzumm_prezzo'      => array(
			'label' => __( 'Prezzo', 'pizzumm' ),
			'type'  => 'text',
			'help'  => __( 'Es. “4,50 €” oppure “al trancio”. Lascia vuoto per non mostrarlo.', 'pizzumm' ),
		),
		'pizzumm_ingredienti' => array(
			'label' => __( 'Ingredienti', 'pizzumm' ),
			'type'  => 'textarea',
			'help'  => __( 'Elenco breve, separato da virgole.', 'pizzumm' ),
		),
		'pizzumm_allergeni'   => array(
			'label' => __( 'Allergeni', 'pizzumm' ),
			'type'  => 'text',
			'help'  => __( 'Separati da virgola. Es. “glutine, latte”.', 'pizzumm' ),
		),
		'pizzumm_badge'       => array(
			'label' => __( 'Etichetta', 'pizzumm' ),
			'type'  => 'text',
			'help'  => __( 'Testo breve mostrato sull\'immagine. Es. “Novità”, “Piccante”, “Veg”.', 'pizzumm' ),
		),
		'pizzumm_badge_tipo'  => array(
			'label' => __( 'Colore etichetta', 'pizzumm' ),
			'type'  => 'select',
			'help'  => __( 'Rosso è il colore predefinito.', 'pizzumm' ),
			'options' => array(
				''     => __( 'Rosso', 'pizzumm' ),
				'veg'  => __( 'Verde (vegetariano)', 'pizzumm' ),
				'new'  => __( 'Giallo (novità)', 'pizzumm' ),
			),
		),
	);
}

/**
 * Metabox dei dettagli prodotto.
 */
function pizzumm_add_product_metabox() {
	add_meta_box(
		'pizzumm_product_details',
		__( 'Dettagli prodotto', 'pizzumm' ),
		'pizzumm_render_product_metabox',
		'pizzumm_prodotto',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'pizzumm_add_product_metabox' );

/**
 * Render del metabox prodotto.
 *
 * @param WP_Post $post Post corrente.
 */
function pizzumm_render_product_metabox( $post ) {
	wp_nonce_field( 'pizzumm_save_product', 'pizzumm_product_nonce' );

	echo '<style>.pizzumm-field{margin:0 0 16px}.pizzumm-field label{display:block;font-weight:600;margin-bottom:4px}.pizzumm-field input,.pizzumm-field textarea,.pizzumm-field select{width:100%;max-width:520px}.pizzumm-field .description{margin-top:4px}</style>';

	foreach ( pizzumm_product_fields() as $key => $conf ) {
		$value = get_post_meta( $post->ID, $key, true );

		echo '<div class="pizzumm-field">';
		printf( '<label for="%1$s">%2$s</label>', esc_attr( $key ), esc_html( $conf['label'] ) );

		if ( 'textarea' === $conf['type'] ) {
			printf(
				'<textarea id="%1$s" name="%1$s" rows="3">%2$s</textarea>',
				esc_attr( $key ),
				esc_textarea( $value )
			);
		} elseif ( 'select' === $conf['type'] ) {
			printf( '<select id="%1$s" name="%1$s">', esc_attr( $key ) );
			foreach ( $conf['options'] as $opt_key => $opt_label ) {
				printf(
					'<option value="%1$s" %2$s>%3$s</option>',
					esc_attr( $opt_key ),
					selected( $value, $opt_key, false ),
					esc_html( $opt_label )
				);
			}
			echo '</select>';
		} else {
			printf(
				'<input type="text" id="%1$s" name="%1$s" value="%2$s" />',
				esc_attr( $key ),
				esc_attr( $value )
			);
		}

		if ( ! empty( $conf['help'] ) ) {
			printf( '<p class="description">%s</p>', esc_html( $conf['help'] ) );
		}

		echo '</div>';
	}
}

/**
 * Salvataggio dei campi prodotto.
 *
 * @param int $post_id ID del post.
 */
function pizzumm_save_product_meta( $post_id ) {
	if ( ! isset( $_POST['pizzumm_product_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['pizzumm_product_nonce'] ) ), 'pizzumm_save_product' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( pizzumm_product_fields() as $key => $conf ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}

		$raw = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$value = ( 'textarea' === $conf['type'] )
			? sanitize_textarea_field( $raw )
			: sanitize_text_field( $raw );

		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post_pizzumm_prodotto', 'pizzumm_save_product_meta' );

/**
 * Colonna prezzo nell'elenco prodotti.
 *
 * @param array $columns Colonne.
 * @return array
 */
function pizzumm_product_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['pizzumm_prezzo'] = __( 'Prezzo', 'pizzumm' );
		}
	}

	return $new;
}
add_filter( 'manage_pizzumm_prodotto_posts_columns', 'pizzumm_product_columns' );

/**
 * Contenuto della colonna prezzo.
 *
 * @param string $column  Colonna.
 * @param int    $post_id ID.
 */
function pizzumm_product_column_content( $column, $post_id ) {
	if ( 'pizzumm_prezzo' === $column ) {
		echo esc_html( get_post_meta( $post_id, 'pizzumm_prezzo', true ) );
	}
}
add_action( 'manage_pizzumm_prodotto_posts_custom_column', 'pizzumm_product_column_content', 10, 2 );

/**
 * Categorie del menu ordinate, escluse quelle senza prodotti.
 *
 * @return WP_Term[]
 */
function pizzumm_menu_categories() {
	$terms = get_terms( array(
		'taxonomy'   => 'pizzumm_categoria',
		'hide_empty' => true,
		'meta_key'   => 'pizzumm_ordine', // phpcs:ignore WordPress.DB.SlowDBQuery
		'orderby'    => 'meta_value_num',
		'order'      => 'ASC',
	) );

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		// Fallback: qualche categoria potrebbe non avere il campo ordine.
		$terms = get_terms( array(
			'taxonomy'   => 'pizzumm_categoria',
			'hide_empty' => true,
			'orderby'    => 'name',
		) );
	}

	return is_wp_error( $terms ) ? array() : $terms;
}

/**
 * Prodotti di una categoria.
 *
 * @param WP_Term $term Categoria.
 * @return WP_Post[]
 */
function pizzumm_category_products( $term ) {
	return get_posts( array(
		'post_type'      => 'pizzumm_prodotto',
		'posts_per_page' => 100,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery
			array(
				'taxonomy' => 'pizzumm_categoria',
				'field'    => 'term_id',
				'terms'    => $term->term_id,
			),
		),
	) );
}

/**
 * Immagine di una categoria: immagine impostata, primo prodotto con foto, oppure fallback.
 *
 * @param WP_Term $term Categoria.
 * @return string URL.
 */
function pizzumm_category_image( $term, $round = false ) {
	$image_id = get_term_meta( $term->term_id, 'pizzumm_immagine_id', true );

	if ( $image_id ) {
		$url = wp_get_attachment_image_url( (int) $image_id, 'pizzumm-card' );
		if ( $url ) {
			return $url;
		}
	}

	$products = pizzumm_category_products( $term );

	// Prima scelta: la foto del primo prodotto che ne ha una.
	foreach ( $products as $product ) {
		if ( has_post_thumbnail( $product->ID ) ) {
			return (string) get_the_post_thumbnail_url( $product->ID, 'pizzumm-card' );
		}
	}

	// Seconda: l'immagine segnaposto del primo prodotto della categoria.
	foreach ( $products as $product ) {
		$fallback = get_post_meta( $product->ID, '_pizzumm_fallback_image', true );

		if ( ! $fallback ) {
			continue;
		}

		// Versione quadrata (per i tondi) se disponibile.
		if ( $round ) {
			$square = str_replace( 'stock/', 'stock/round-', $fallback );

			if ( file_exists( PIZZUMM_DIR . '/assets/img/' . $square ) ) {
				return pizzumm_asset( 'img/' . $square );
			}
		}

		if ( file_exists( PIZZUMM_DIR . '/assets/img/' . $fallback ) ) {
			return pizzumm_asset( 'img/' . $fallback );
		}
	}

	// Terza: un file con lo stesso slug della categoria.
	$bundled = 'img/stock/' . ( $round ? 'round-' : '' ) . 'menu-' . $term->slug . '.jpg';

	if ( file_exists( PIZZUMM_DIR . '/assets/' . $bundled ) ) {
		return pizzumm_asset( $bundled );
	}

	return pizzumm_asset( 'img/stock/menu-classiche.jpg' );
}

/**
 * Campi extra sulle categorie: ordine e immagine.
 *
 * @param WP_Term|string $term Termine (edit) o taxonomy slug (add).
 */
function pizzumm_category_fields( $term ) {
	$is_edit  = is_object( $term );
	$ordine   = $is_edit ? get_term_meta( $term->term_id, 'pizzumm_ordine', true ) : '';
	$image_id = $is_edit ? get_term_meta( $term->term_id, 'pizzumm_immagine_id', true ) : '';

	$fields = array(
		array(
			'id'    => 'pizzumm_ordine',
			'label' => __( 'Ordine', 'pizzumm' ),
			'type'  => 'number',
			'value' => $ordine,
			'help'  => __( 'Numero che definisce la posizione della categoria nella pagina Menu (1, 2, 3…).', 'pizzumm' ),
		),
		array(
			'id'    => 'pizzumm_immagine_id',
			'label' => __( 'ID immagine di copertina', 'pizzumm' ),
			'type'  => 'number',
			'value' => $image_id,
			'help'  => __( 'ID dell\'immagine nella Libreria media. Se vuoto viene usata la foto del primo prodotto della categoria.', 'pizzumm' ),
		),
	);

	foreach ( $fields as $field ) {
		if ( $is_edit ) {
			printf(
				'<tr class="form-field"><th scope="row"><label for="%1$s">%2$s</label></th><td>
				<input type="%3$s" name="%1$s" id="%1$s" value="%4$s" />
				<p class="description">%5$s</p></td></tr>',
				esc_attr( $field['id'] ),
				esc_html( $field['label'] ),
				esc_attr( $field['type'] ),
				esc_attr( $field['value'] ),
				esc_html( $field['help'] )
			);
		} else {
			printf(
				'<div class="form-field"><label for="%1$s">%2$s</label>
				<input type="%3$s" name="%1$s" id="%1$s" value="%4$s" />
				<p class="description">%5$s</p></div>',
				esc_attr( $field['id'] ),
				esc_html( $field['label'] ),
				esc_attr( $field['type'] ),
				esc_attr( $field['value'] ),
				esc_html( $field['help'] )
			);
		}
	}
}
add_action( 'pizzumm_categoria_edit_form_fields', 'pizzumm_category_fields' );
add_action( 'pizzumm_categoria_add_form_fields', 'pizzumm_category_fields' );

/**
 * Salva i campi extra della categoria.
 *
 * @param int $term_id ID termine.
 */
function pizzumm_save_category_fields( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	foreach ( array( 'pizzumm_ordine', 'pizzumm_immagine_id' ) as $key ) {
		if ( ! isset( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- nonce gestito dal core.
			continue;
		}

		$value = absint( wp_unslash( $_POST[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification

		if ( $value ) {
			update_term_meta( $term_id, $key, $value );
		} else {
			delete_term_meta( $term_id, $key );
		}
	}
}
add_action( 'created_pizzumm_categoria', 'pizzumm_save_category_fields' );
add_action( 'edited_pizzumm_categoria', 'pizzumm_save_category_fields' );
