<?php
/**
 * Set di icone SVG (stile lineare, stroke 1.8) — nessuna emoji, nessuna libreria esterna.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Restituisce il markup di un'icona.
 *
 * @param string $name  Nome icona.
 * @param string $class Classi CSS aggiuntive.
 * @return string
 */
function pizzumm_icon( $name, $class = '' ) {
	$paths = array(
		'pizza'      => '<path d="M12 3 3 20l9-2 9 2Z"/><circle cx="12" cy="9" r="1.1"/><circle cx="9.6" cy="14.4" r="1.1"/><circle cx="14.4" cy="14.6" r="1.1"/>',
		'slice'      => '<path d="M4 5.5 20 4l-1.5 16Z"/><path d="M5.6 9.2 18.7 8"/>',
		'bag'        => '<path d="M6 7h12l-1 13H7Z"/><path d="M9 7a3 3 0 0 1 6 0"/>',
		'scooter'    => '<circle cx="5.5" cy="17.5" r="2.5"/><circle cx="18.5" cy="17.5" r="2.5"/><path d="M8 17.5h8M14 6h3l2 11.5M14 6l-2 11.5"/>',
		'qr'         => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM20 14h1M14 20h3M20 17v4"/>',
		'list'       => '<path d="M8 6h13M8 12h13M8 18h13M3.5 6h.01M3.5 12h.01M3.5 18h.01"/>',
		'card'       => '<rect x="2.5" y="5" width="19" height="14" rx="2.5"/><path d="M2.5 10h19"/>',
		'clock'      => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.2 2"/>',
		'pin'        => '<path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"/><circle cx="12" cy="10" r="2.6"/>',
		'phone'      => '<path d="M6.5 3.5h3l1.6 4-2 1.5a12 12 0 0 0 5.9 5.9l1.5-2 4 1.6v3a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 4.5 5.7a2 2 0 0 1 2-2.2Z"/>',
		'mail'       => '<rect x="2.5" y="4.5" width="19" height="15" rx="2.5"/><path d="m3.5 6.5 8.5 6 8.5-6"/>',
		'whatsapp'   => '<path d="M3.5 20.5 5 16.4A8 8 0 1 1 8.1 19.4Z"/><path d="M9.2 9.1c.2 1.6 2.3 4 4 4.4l1-1.2 1.7.9c-.2 1-1.2 1.5-2.2 1.3-2.6-.5-5-2.9-5.5-5.5-.2-1 .3-2 1.3-2.2l.9 1.7Z"/>',
		'instagram'  => '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="3.8"/><circle cx="17" cy="7" r="1"/>',
		'facebook'   => '<path d="M14.5 8.5V7a1.5 1.5 0 0 1 1.5-1.5h1.5V3H15a4 4 0 0 0-4 4v1.5H9V12h2v9h3.5v-9H17l.5-3.5Z"/>',
		'tiktok'     => '<path d="M14.5 3v11.2a3.6 3.6 0 1 1-3-3.55"/><path d="M14.5 3c.4 2.4 2 4 4.5 4.2"/>',
		'tripadvisor' => '<circle cx="7" cy="13.5" r="3.6"/><circle cx="17" cy="13.5" r="3.6"/><circle cx="7" cy="13.5" r=".9"/><circle cx="17" cy="13.5" r=".9"/><path d="M2.5 9.9h2.1M19.4 9.9h2.1M4.6 9.9C6.6 7.6 9.2 6.5 12 6.5s5.4 1.1 7.4 3.4M10.4 16.6 12 18.7l1.6-2.1"/>',
		'arrow'      => '<path d="M5 12h13M13 6.5 18.5 12 13 17.5"/>',
		'arrow-left' => '<path d="M19 12H6M11 6.5 5.5 12 11 17.5"/>',
		'arrow-up'   => '<path d="M12 19V6M6.5 11 12 5.5 17.5 11"/>',
		'check'      => '<circle cx="12" cy="12" r="9"/><path d="m8 12.4 2.7 2.6L16 9.5"/>',
		'alert'      => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5v5.2M12 16.2h.01"/>',
		'info'       => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5.5M12 7.8h.01"/>',
		'leaf'       => '<path d="M4 20c0-8 5-13 16-13 0 9-5 13-11 13a5 5 0 0 1-5-5Z"/><path d="M8.5 15.5 17 7"/>',
		'flame'      => '<path d="M12 3s5 4.2 5 9a5 5 0 0 1-10 0c0-1.8.8-3.2 1.6-4.2.4 1.2 1 1.9 1.7 2.1C10 8.2 12 5.6 12 3Z"/>',
		'star'       => '<path d="m12 3.8 2.5 5.2 5.7.8-4.1 4 1 5.6-5.1-2.7-5.1 2.7 1-5.6-4.1-4 5.7-.8Z"/>',
		'wheat'      => '<path d="M12 21V9"/><path d="M12 9c0-2 1.2-3.6 3-4 0 2-1.2 3.6-3 4Zm0 0c0-2-1.2-3.6-3-4 0 2 1.2 3.6 3 4Zm0 4.5c0-2 1.2-3.6 3-4 0 2-1.2 3.6-3 4Zm0 0c0-2-1.2-3.6-3-4 0 2 1.2 3.6 3 4Z"/>',
		'map'        => '<path d="m3 6.5 6-2.5 6 2.5 6-2.5v13.5l-6 2.5-6-2.5-6 2.5Z"/><path d="M9 4v13.5M15 6.5V20"/>',
		'sparkle'    => '<path d="M12 3.5 13.6 9l5.4 1.6-5.4 1.6L12 17.6l-1.6-5.4L5 10.6 10.4 9Z"/><path d="M18.5 16.5 19 18l1.5.5-1.5.5-.5 1.5-.5-1.5L16.5 19l1.5-.5Z"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="icon %1$s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%2$s</svg>',
		esc_attr( $class ),
		$paths[ $name ] // phpcs:ignore WordPress.Security.EscapeOutput -- markup SVG statico del tema.
	);
}

/**
 * Stampa un'icona.
 *
 * @param string $name  Nome icona.
 * @param string $class Classi CSS.
 */
function pizzumm_the_icon( $name, $class = '' ) {
	echo pizzumm_icon( $name, $class ); // phpcs:ignore WordPress.Security.EscapeOutput
}
