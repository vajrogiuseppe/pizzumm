<?php
/**
 * Helper per costruire strutture Elementor come array PHP.
 *
 * Ogni funzione restituisce l'array di un elemento (section/column/widget)
 * compatibile col formato `_elementor_data`. Vengono usati in
 * `inc/elementor-widgets.php` per comporre le pagine.
 *
 * Ogni widget porta con sé le classi CSS del tema (kicker, lead, btn, ecc.)
 * così la resa visiva resta coerente col design originale.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ID univoco per un elemento Elementor.
 *
 * @return string
 */
function pz_el_uid() {
	return substr( md5( uniqid( '', true ) . mt_rand() ), 0, 7 );
}

/**
 * Section Elementor.
 *
 * @param array $settings Impostazioni.
 * @param array $columns  Colonne (array di array da pz_el_column).
 * @return array
 */
function pz_el_section( $settings, $columns ) {
	return array(
		'id'       => pz_el_uid(),
		'elType'   => 'section',
		'settings' => array_merge(
			array(
				'stretch_section' => 'section-stretched',
				'content_width'   => array( 'unit' => 'px', 'size' => 1240 ),
				'gap'             => 'default',
			),
			$settings
		),
		'elements' => $columns,
		'isInner'  => false,
	);
}

/**
 * Inner section (dentro una colonna).
 *
 * @param array $settings Impostazioni.
 * @param array $columns  Colonne interne.
 * @return array
 */
function pz_el_inner_section( $settings, $columns ) {
	return array(
		'id'       => pz_el_uid(),
		'elType'   => 'section',
		'settings' => array_merge( array( 'gap' => 'default' ), $settings ),
		'elements' => $columns,
		'isInner'  => true,
	);
}

/**
 * Column Elementor.
 *
 * @param int   $size     Larghezza (0-100).
 * @param array $widgets  Widget o inner section.
 * @param array $settings Impostazioni aggiuntive.
 * @return array
 */
function pz_el_column( $size, $widgets, $settings = array() ) {
	return array(
		'id'       => pz_el_uid(),
		'elType'   => 'column',
		'settings' => array_merge(
			array(
				'_column_size' => $size,
				'_inline_size' => null,
			),
			$settings
		),
		'elements' => $widgets,
		'isInner'  => false,
	);
}

/**
 * Widget generico.
 *
 * @param string $type     Tipo widget (heading, text-editor, image, button, buttons, html, shortcode, icon-list, spacer).
 * @param array  $settings Impostazioni.
 * @return array
 */
function pz_el_widget( $type, $settings ) {
	return array(
		'id'         => pz_el_uid(),
		'elType'     => 'widget',
		'widgetType' => $type,
		'settings'   => $settings,
	);
}

/**
 * Heading widget.
 *
 * @param string $text    Testo.
 * @param string $tag     h1..h6.
 * @param string $classes Classi CSS aggiuntive.
 * @param array  $extra   Impostazioni extra.
 * @return array
 */
function pz_el_heading( $text, $tag = 'h2', $classes = '', $extra = array() ) {
	return pz_el_widget( 'heading', array_merge(
		array(
			'title'      => $text,
			'header_size' => $tag,
			'_css_classes' => $classes,
		),
		$extra
	) );
}

/**
 * Text-editor widget (per paragrafi).
 *
 * @param string $html    HTML.
 * @param string $classes Classi CSS.
 * @return array
 */
function pz_el_text( $html, $classes = '' ) {
	return pz_el_widget( 'text-editor', array(
		'editor'       => '<p>' . $html . '</p>',
		'_css_classes' => $classes,
	) );
}

/**
 * Image widget.
 *
 * @param string $url     URL immagine.
 * @param string $alt     Alt.
 * @param string $classes Classi CSS.
 * @return array
 */
function pz_el_image( $url, $alt = '', $classes = '' ) {
	return pz_el_widget( 'image', array(
		'image' => array(
			'url' => $url,
			'id'  => 0,
			'alt' => $alt,
		),
		'image_size'   => 'full',
		'_css_classes' => $classes,
	) );
}

/**
 * Button widget (singolo).
 *
 * @param string $text    Testo.
 * @param string $url     URL.
 * @param string $classes Classi CSS aggiuntive (es. "btn btn--red").
 * @param array  $extra   Extra.
 * @return array
 */
function pz_el_button( $text, $url, $classes = 'btn', $extra = array() ) {
	return pz_el_widget( 'button', array_merge(
		array(
			'text' => $text,
			'link' => array(
				'url'         => $url,
				'is_external' => '',
				'nofollow'    => '',
			),
			'_css_classes' => $classes,
		),
		$extra
	) );
}

/**
 * Icon-list widget (per gli elenchi "perks").
 *
 * @param array  $items   Lista items (['text' => '...', 'icon' => '...']).
 * @param string $classes Classi CSS.
 * @return array
 */
function pz_el_icon_list( $items, $classes = 'perks' ) {
	$list = array();

	foreach ( $items as $it ) {
		$list[] = array(
			'text'      => $it,
			'selected_icon' => array(
				'value'   => 'fas fa-arrow-right',
				'library' => 'fa-solid',
			),
			'_id'       => substr( md5( $it . mt_rand() ), 0, 7 ),
		);
	}

	return pz_el_widget( 'icon-list', array(
		'icon_list'    => $list,
		'view'         => 'traditional',
		'_css_classes' => $classes,
	) );
}

/**
 * HTML raw widget.
 *
 * @param string $html    HTML.
 * @param string $classes Classi.
 * @return array
 */
function pz_el_html( $html, $classes = '' ) {
	return pz_el_widget( 'html', array(
		'html'         => $html,
		'_css_classes' => $classes,
	) );
}

/**
 * Shortcode widget.
 *
 * @param string $shortcode Shortcode completo (con parentesi quadre).
 * @return array
 */
function pz_el_shortcode( $shortcode ) {
	return pz_el_widget( 'shortcode', array(
		'shortcode' => $shortcode,
	) );
}

/**
 * Spacer.
 *
 * @param int $size Altezza in px.
 * @return array
 */
function pz_el_spacer( $size = 40 ) {
	return pz_el_widget( 'spacer', array(
		'space' => array( 'unit' => 'px', 'size' => $size ),
	) );
}
