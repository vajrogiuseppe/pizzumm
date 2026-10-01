<?php
/**
 * Aggiornamenti del tema dalle release GitHub.
 *
 * WordPress controlla periodicamente se ci sono aggiornamenti dei temi: qui gli
 * si dice di guardare anche l'ultima release di vajrogiuseppe/pizzumm. Se la
 * versione della release è più alta di quella in style.css, l'aggiornamento
 * compare in Bacheca → Aggiornamenti (e si installa da solo se gli
 * aggiornamenti automatici del tema sono attivi).
 *
 * Il pacchetto è l'allegato "pizzumm.zip" della release (cartella pizzumm/);
 * in mancanza si usa lo zip del codice sorgente generato da GitHub.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PIZZUMM_GITHUB_REPO', 'vajrogiuseppe/pizzumm' );

/**
 * Ultima release su GitHub (in cache per 6 ore).
 *
 * @param bool $force Ignora la cache.
 * @return array{version:string,package:string,url:string}|null
 */
function pizzumm_github_latest_release( $force = false ) {
	$cache_key = 'pizzumm_github_release';
	$cached    = $force ? false : get_site_transient( $cache_key );

	if ( is_array( $cached ) ) {
		return $cached['version'] ? $cached : null;
	}

	$response = wp_remote_get(
		'https://api.github.com/repos/' . PIZZUMM_GITHUB_REPO . '/releases/latest',
		array(
			'timeout' => 10,
			'headers' => array( 'Accept' => 'application/vnd.github+json' ),
		)
	);

	$release = array( 'version' => '', 'package' => '', 'url' => '' );

	if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( is_array( $body ) && ! empty( $body['tag_name'] ) ) {
			$release['version'] = ltrim( (string) $body['tag_name'], 'vV' );
			$release['url']     = (string) ( $body['html_url'] ?? '' );
			$release['package'] = (string) ( $body['zipball_url'] ?? '' );

			foreach ( (array) ( $body['assets'] ?? array() ) as $asset ) {
				if ( 'pizzumm.zip' === ( $asset['name'] ?? '' ) ) {
					$release['package'] = (string) $asset['browser_download_url'];
					break;
				}
			}
		}
	}

	// In caso di errore la cache dura un'ora, così non si interroga GitHub a ogni pagina.
	set_site_transient( $cache_key, $release, $release['version'] ? 6 * HOUR_IN_SECONDS : HOUR_IN_SECONDS );

	return $release['version'] ? $release : null;
}

/**
 * Inserisce l'aggiornamento del tema nel controllo di WordPress.
 *
 * @param object $transient Dati degli aggiornamenti dei temi.
 * @return object
 */
function pizzumm_github_check_update( $transient ) {
	if ( ! is_object( $transient ) ) {
		return $transient;
	}

	$stylesheet = get_template();
	$theme      = wp_get_theme( $stylesheet );
	$release    = pizzumm_github_latest_release();

	if ( ! $release || ! $release['package'] ) {
		return $transient;
	}

	$item = array(
		'theme'        => $stylesheet,
		'new_version'  => $release['version'],
		'url'          => $release['url'],
		'package'      => $release['package'],
		'requires'     => $theme->get( 'RequiresWP' ),
		'requires_php' => $theme->get( 'RequiresPHP' ),
	);

	if ( version_compare( $release['version'], $theme->get( 'Version' ), '>' ) ) {
		$transient->response[ $stylesheet ] = $item;
	} else {
		// Serve a WordPress per mostrare il pulsante "Attiva aggiornamenti automatici".
		$transient->no_update[ $stylesheet ] = $item;
	}

	return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'pizzumm_github_check_update' );

/**
 * Lo zip del codice sorgente di GitHub ha una cartella "utente-repo-hash":
 * la rinomina nella cartella del tema, altrimenti WordPress installerebbe
 * un tema nuovo invece di aggiornare questo.
 *
 * @param string      $source        Cartella estratta.
 * @param string      $remote_source Cartella temporanea.
 * @param WP_Upgrader $upgrader      Upgrader.
 * @param array       $hook_extra    Contesto dell'aggiornamento.
 * @return string|WP_Error
 */
function pizzumm_github_fix_source_dir( $source, $remote_source, $upgrader, $hook_extra = array() ) {
	global $wp_filesystem;

	$stylesheet = get_template();

	if ( ( $hook_extra['theme'] ?? '' ) !== $stylesheet ) {
		return $source;
	}

	$wanted = trailingslashit( $remote_source ) . $stylesheet . '/';

	if ( untrailingslashit( $source ) === untrailingslashit( $wanted ) ) {
		return $source;
	}

	if ( $wp_filesystem && $wp_filesystem->move( $source, $wanted, true ) ) {
		return $wanted;
	}

	return new WP_Error( 'pizzumm_update_dir', __( 'Impossibile preparare la cartella del tema aggiornato.', 'pizzumm' ) );
}
add_filter( 'upgrader_source_selection', 'pizzumm_github_fix_source_dir', 10, 4 );

/**
 * Dopo un aggiornamento la cache della release va rinnovata.
 */
function pizzumm_github_clear_cache() {
	delete_site_transient( 'pizzumm_github_release' );
}
add_action( 'upgrader_process_complete', 'pizzumm_github_clear_cache' );
add_action( 'load-update-core.php', 'pizzumm_github_clear_cache' );

/**
 * Aggiornamenti automatici sempre attivi per questo tema, anche quando
 * WordPress non mostra il link "Attiva aggiornamenti automatici".
 *
 * @param bool|null $update Decisione corrente.
 * @param object    $item   Tema da aggiornare.
 * @return bool|null
 */
function pizzumm_github_auto_update( $update, $item ) {
	if ( isset( $item->theme ) && get_template() === $item->theme ) {
		return true;
	}

	return $update;
}
add_filter( 'auto_update_theme', 'pizzumm_github_auto_update', 10, 2 );
