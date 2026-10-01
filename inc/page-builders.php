<?php
/**
 * Compatibilità con i page builder (Elementor) e con l'editor a blocchi.
 *
 * Logica: ogni pagina del sito ha un layout "Pizzumm" già pronto nel template PHP.
 * Se la pagina viene costruita con Elementor, oppure contiene blocchi Gutenberg,
 * oppure l'opzione del metabox viene disattivata, il tema mostra il contenuto
 * dell'editor al posto del layout predefinito.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Supporto Elementor: header/footer dal tema, contenuto dal builder.
 */
function pizzumm_elementor_support() {
	add_theme_support( 'elementor' );
	add_theme_support( 'elementor-pro' );
	add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'pizzumm_elementor_support' );

/**
 * Registra le "theme locations" di Elementor Pro (header/footer/single/archive).
 *
 * @param object $manager Gestore delle location.
 */
function pizzumm_register_elementor_locations( $manager ) {
	if ( ! method_exists( $manager, 'register_all_core_location' ) ) {
		return;
	}

	$manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'pizzumm_register_elementor_locations' );

/**
 * La pagina è costruita con Elementor?
 *
 * @param int $post_id ID pagina (0 = corrente).
 * @return bool
 */
function pizzumm_is_elementor_page( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	if ( ! $post_id || ! did_action( 'elementor/loaded' ) ) {
		return false;
	}

	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}

	$plugin = \Elementor\Plugin::$instance;

	if ( ! isset( $plugin->documents ) ) {
		return false;
	}

	$document = $plugin->documents->get( $post_id );

	return $document && $document->is_built_with_elementor();
}

/**
 * La pagina contiene contenuto dell'editor (blocchi o testo)?
 *
 * @param int $post_id ID pagina (0 = corrente).
 * @return bool
 */
function pizzumm_has_editor_content( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$post    = $post_id ? get_post( $post_id ) : null;

	if ( ! $post ) {
		return false;
	}

	return '' !== trim( (string) $post->post_content );
}

/**
 * Il layout predefinito del tema va sostituito dal contenuto dell'editor?
 *
 * @param int $post_id ID pagina (0 = corrente).
 * @return bool
 */
function pizzumm_is_builder_page( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	if ( ! $post_id ) {
		return false;
	}

	if ( pizzumm_is_elementor_page( $post_id ) ) {
		return true;
	}

	// Solo se l'interruttore è esplicitamente "off" mostriamo il contenuto
	// dell'editor. In tutti gli altri casi (auto, on, contenuto residuo di un
	// vecchio esperimento) resta il layout PHP del tema, che è quello che
	// il cliente si aspetta.
	if ( 'off' === get_post_meta( $post_id, '_pizzumm_default_layout', true ) ) {
		return true;
	}

	return false;
}

/**
 * Metabox: scelta del layout della pagina.
 */
function pizzumm_layout_metabox() {
	add_meta_box(
		'pizzumm_layout',
		__( 'Layout Pizzumm', 'pizzumm' ),
		'pizzumm_render_layout_metabox',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'pizzumm_layout_metabox' );

/**
 * Render del metabox layout.
 *
 * @param WP_Post $post Pagina.
 */
function pizzumm_render_layout_metabox( $post ) {
	wp_nonce_field( 'pizzumm_save_layout', 'pizzumm_layout_nonce' );

	$value    = get_post_meta( $post->ID, '_pizzumm_default_layout', true );
	$value    = $value ? $value : 'auto';
	$options  = array(
		'auto' => __( 'Automatico (consigliato)', 'pizzumm' ),
		'on'   => __( 'Usa sempre il layout Pizzumm', 'pizzumm' ),
		'off'  => __( 'Usa solo il contenuto dell\'editor', 'pizzumm' ),
	);

	echo '<p>';
	foreach ( $options as $key => $label ) {
		printf(
			'<label style="display:block;margin-bottom:6px"><input type="radio" name="pizzumm_default_layout" value="%1$s" %2$s /> %3$s</label>',
			esc_attr( $key ),
			checked( $value, $key, false ),
			esc_html( $label )
		);
	}
	echo '</p>';

	echo '<p class="description">' . esc_html__( 'Di default il tema mostra il layout grafico curato. Passa a "Usa solo il contenuto dell\'editor" solo se vuoi costruire la pagina con blocchi Gutenberg o Elementor.', 'pizzumm' ) . '</p>';

	/**
	 * Punto d'aggancio per plugin/moduli del tema che vogliono aggiungere
	 * contenuti al metabox layout (es. il pulsante di reset Elementor).
	 *
	 * @param WP_Post $post Pagina corrente.
	 */
	do_action( 'pizzumm_after_layout_metabox', $post );
}

/**
 * Salvataggio del metabox layout.
 *
 * @param int $post_id ID pagina.
 */
function pizzumm_save_layout_meta( $post_id ) {
	if ( ! isset( $_POST['pizzumm_layout_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['pizzumm_layout_nonce'] ) ), 'pizzumm_save_layout' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$value = isset( $_POST['pizzumm_default_layout'] )
		? sanitize_key( wp_unslash( $_POST['pizzumm_default_layout'] ) )
		: 'auto';

	if ( ! in_array( $value, array( 'auto', 'on', 'off' ), true ) ) {
		$value = 'auto';
	}

	update_post_meta( $post_id, '_pizzumm_default_layout', $value );
}
add_action( 'save_post_page', 'pizzumm_save_layout_meta' );

/**
 * Stampa il contenuto dell'editor.
 *
 * Se la pagina è costruita con Elementor, non applichiamo il container stretto
 * del tema: le sezioni gestiscono da sole full-bleed e larghezza. Altrimenti
 * (pagina classica o Gutenberg) usiamo il wrapper editoriale a 68ch.
 */
function pizzumm_render_editor_content() {
	if ( pizzumm_is_elementor_page() ) {
		echo '<div class="entry entry--elementor">';
		the_content();
		echo '</div>';
		return;
	}

	echo '<div class="entry"><div class="container"><div class="entry__content">';
	the_content();
	wp_link_pages( array(
		'before' => '<nav class="page-links">',
		'after'  => '</nav>',
	) );
	echo '</div></div></div>';
}
