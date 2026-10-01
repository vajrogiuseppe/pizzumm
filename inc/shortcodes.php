<?php
/**
 * Shortcode del tema.
 *
 * Servono a rendere modificabili da Gutenberg anche le sezioni dinamiche del
 * sito (menu prodotti, mappa, informazioni del locale, ecc). Ogni shortcode
 * produce lo stesso markup dei template PHP, così spostare/duplicare/rimuovere
 * una sezione nell'editor a blocchi non fa perdere la resa grafica.
 *
 * Elenco:
 *  - [pizzumm_ticker]            · nastro scorrevole tra le sezioni
 *  - [pizzumm_home_facts]        · le tre "prove sociali" della hero
 *  - [pizzumm_categorie]         · griglia tonda delle categorie menu
 *  - [pizzumm_menu_prodotti]     · menu completo (nav + categorie + prodotti)
 *  - [pizzumm_mappa]             · mappa Google embed con banner consenso
 *  - [pizzumm_info_locale]       · elenco contatti + orari + social
 *  - [pizzumm_cta_band title="" text="" kicker=""] · banda CTA finale
 *  - [pizzumm_allergen_note]     · nota allergeni della pagina menu
 *  - [pizzumm_timeline_item numero="1" titolo="" testo="" immagine="url"]
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra tutti gli shortcode.
 */
function pizzumm_register_shortcodes() {
	add_shortcode( 'pizzumm_ticker', 'pizzumm_sc_ticker' );
	add_shortcode( 'pizzumm_home_facts', 'pizzumm_sc_home_facts' );
	add_shortcode( 'pizzumm_categorie', 'pizzumm_sc_categorie' );
	add_shortcode( 'pizzumm_menu_prodotti', 'pizzumm_sc_menu_prodotti' );
	add_shortcode( 'pizzumm_mappa', 'pizzumm_sc_mappa' );
	add_shortcode( 'pizzumm_info_locale', 'pizzumm_sc_info_locale' );
	add_shortcode( 'pizzumm_cta_band', 'pizzumm_sc_cta_band' );
	add_shortcode( 'pizzumm_allergen_note', 'pizzumm_sc_allergen_note' );
	add_shortcode( 'pizzumm_timeline_item', 'pizzumm_sc_timeline_item' );
}
add_action( 'init', 'pizzumm_register_shortcodes' );

/**
 * Cattura l'output di una funzione e lo restituisce come stringa.
 *
 * @param callable $fn   Callable da eseguire.
 * @param array    $args Argomenti.
 * @return string
 */
function pizzumm_capture( $fn, $args = array() ) {
	ob_start();
	call_user_func_array( $fn, $args );

	return (string) ob_get_clean();
}

/* -------------------------------------------------------------------------
 *  Ticker
 * ---------------------------------------------------------------------- */
function pizzumm_sc_ticker() {
	ob_start();
	?>
	<div class="ticker" aria-hidden="true">
		<div class="ticker__track">
			<?php for ( $i = 0; $i < 2; $i++ ) : ?>
				<div class="ticker__group">
					<span>Una pizza per fetta</span><?php pizzumm_the_icon( 'sparkle' ); ?>
					<span>Pizza in teglia</span><?php pizzumm_the_icon( 'sparkle' ); ?>
					<span>Nel cuore di Pompei</span><?php pizzumm_the_icon( 'sparkle' ); ?>
					<span>Facile, veloce, Pizzumm</span><?php pizzumm_the_icon( 'sparkle' ); ?>
					<span>Ordina e paga dal tavolo</span><?php pizzumm_the_icon( 'sparkle' ); ?>
				</div>
			<?php endfor; ?>
		</div>
	</div>
	<?php
	return (string) ob_get_clean();
}

/* -------------------------------------------------------------------------
 *  Home · facts
 * ---------------------------------------------------------------------- */
function pizzumm_sc_home_facts() {
	ob_start();
	?>
	<ul class="hero__facts" data-anim-group>
		<li class="fact">
			<?php pizzumm_the_icon( 'slice' ); ?>
			<span>
				<strong><?php esc_html_e( 'Una pizza per fetta', 'pizzumm' ); ?></strong>
				<span><?php esc_html_e( 'Scegli con gli occhi, paghi quello che prendi.', 'pizzumm' ); ?></span>
			</span>
		</li>
		<li class="fact">
			<?php pizzumm_the_icon( 'wheat' ); ?>
			<span>
				<strong><?php esc_html_e( 'Impasto contemporaneo', 'pizzumm' ); ?></strong>
				<span><?php esc_html_e( 'Leggero fuori, croccante sotto.', 'pizzumm' ); ?></span>
			</span>
		</li>
		<li class="fact">
			<?php pizzumm_the_icon( 'scooter' ); ?>
			<span>
				<strong><?php esc_html_e( 'Pizzumm dove vuoi', 'pizzumm' ); ?></strong>
				<span><?php esc_html_e( 'A casa, in ufficio o con gli amici, con Glovo.', 'pizzumm' ); ?></span>
			</span>
		</li>
	</ul>
	<?php
	return (string) ob_get_clean();
}

/* -------------------------------------------------------------------------
 *  Categorie tonde
 * ---------------------------------------------------------------------- */
function pizzumm_sc_categorie() {
	$cats = pizzumm_menu_categories();

	if ( ! $cats ) {
		return '';
	}

	ob_start();
	?>
	<ul class="rounds" data-stories data-anim-group>
		<?php foreach ( $cats as $cat ) : ?>
			<li>
				<a class="round" href="<?php echo esc_url( pizzumm_page_url( 'menu' ) . '#cat-' . $cat->slug ); ?>">
					<img class="round__img" src="<?php echo esc_url( pizzumm_category_image( $cat, true ) ); ?>"
						alt="<?php echo esc_attr( $cat->name ); ?>"
						width="300" height="300" loading="lazy" decoding="async" />
					<span class="round__name"><?php echo esc_html( $cat->name ); ?></span>
					<span class="round__count">
						<?php
						printf(
							/* translators: %d: numero di prodotti. */
							esc_html( _n( '%d proposta', '%d proposte', $cat->count, 'pizzumm' ) ),
							absint( $cat->count )
						);
						?>
					</span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
	return (string) ob_get_clean();
}

/* -------------------------------------------------------------------------
 *  Menu completo (nav categorie + elenco prodotti)
 * ---------------------------------------------------------------------- */
function pizzumm_sc_menu_prodotti() {
	$cats = pizzumm_menu_categories();

	if ( ! $cats ) {
		return '<p class="lead">' . esc_html__( 'Il menu è in aggiornamento. Aggiungi i prodotti da "Menu Pizzumm" nella bacheca di WordPress.', 'pizzumm' ) . '</p>';
	}

	ob_start();
	?>
	<nav class="menu-nav" aria-label="<?php esc_attr_e( 'Categorie del menu', 'pizzumm' ); ?>">
		<div class="container menu-nav__inner">
			<?php foreach ( $cats as $cat ) : ?>
				<a href="#cat-<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></a>
			<?php endforeach; ?>
		</div>
	</nav>

	<div class="section--paper">
		<div class="container">
			<?php
			foreach ( $cats as $cat ) :
				$products = pizzumm_category_products( $cat );

				if ( ! $products ) {
					continue;
				}
				?>
				<section class="menu-cat" id="cat-<?php echo esc_attr( $cat->slug ); ?>">
					<div class="menu-cat__head">
						<span class="kicker" data-anim><?php esc_html_e( 'il nostro menu', 'pizzumm' ); ?></span>
						<h2 class="menu-cat__title" data-split><?php echo esc_html( $cat->name ); ?></h2>
						<?php if ( $cat->description ) : ?>
							<p class="menu-cat__desc" data-anim><?php echo esc_html( $cat->description ); ?></p>
						<?php endif; ?>
					</div>

					<ul class="menu-list" data-anim-group>
						<?php
						foreach ( $products as $pizzumm_product ) {
							include PIZZUMM_DIR . '/template-parts/pizza-card.php';
						}
						?>
					</ul>
				</section>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	return (string) ob_get_clean();
}

/* -------------------------------------------------------------------------
 *  Mappa Google
 * ---------------------------------------------------------------------- */
function pizzumm_sc_mappa() {
	$consent = get_theme_mod( 'pizzumm_map_consent', true );

	ob_start();
	?>
	<div class="map-wrap" data-anim>
		<?php if ( $consent ) : ?>
			<div class="map-consent" data-map-consent data-map-src="<?php echo esc_url( pizzumm_map_embed_url() ); ?>">
				<?php pizzumm_the_icon( 'map' ); ?>
				<p><?php esc_html_e( 'La mappa è fornita da Google. Caricandola accetti che Google possa raccogliere alcuni dati di navigazione.', 'pizzumm' ); ?></p>
				<button type="button" class="btn" data-map-load>
					<?php esc_html_e( 'Carica la mappa', 'pizzumm' ); ?>
				</button>
			</div>
		<?php else : ?>
			<iframe
				src="<?php echo esc_url( pizzumm_map_embed_url() ); ?>"
				title="<?php esc_attr_e( 'Mappa: Pizzumm', 'pizzumm' ); ?>"
				loading="lazy" referrerpolicy="no-referrer-when-downgrade"
				allowfullscreen></iframe>
		<?php endif; ?>
	</div>

	<div class="map-actions" data-anim>
		<p class="map-actions__address">
			<?php pizzumm_the_icon( 'pin' ); ?>
			<?php echo esc_html( pizzumm_opt( 'address' ) ); ?>
		</p>
		<?php
		pizzumm_button(
			__( 'Apri in Google Maps', 'pizzumm' ),
			pizzumm_map_link_url(),
			'red',
			' target="_blank" rel="noopener noreferrer"',
			'arrow'
		);
		?>
	</div>
	<?php
	return (string) ob_get_clean();
}

/* -------------------------------------------------------------------------
 *  Blocco informazioni del locale
 * ---------------------------------------------------------------------- */
function pizzumm_sc_info_locale() {
	$phone    = pizzumm_opt( 'phone' );
	$whatsapp = pizzumm_whatsapp_url();
	$email    = pizzumm_opt( 'email' );
	$hours    = pizzumm_hours_lines();
	$socials  = pizzumm_socials();

	ob_start();
	?>
	<ul class="info-list">
		<li>
			<?php pizzumm_the_icon( 'pin' ); ?>
			<span>
				<span class="info-label"><?php esc_html_e( 'Indirizzo', 'pizzumm' ); ?></span>
				<a href="<?php echo esc_url( pizzumm_map_link_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( pizzumm_opt( 'address' ) ); ?></a>
			</span>
		</li>

		<?php if ( $phone ) : ?>
			<li>
				<?php pizzumm_the_icon( 'phone' ); ?>
				<span>
					<span class="info-label"><?php esc_html_e( 'Telefono', 'pizzumm' ); ?></span>
					<a href="<?php echo esc_url( pizzumm_tel_href( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
				</span>
			</li>
		<?php endif; ?>

		<?php if ( $whatsapp ) : ?>
			<li>
				<?php pizzumm_the_icon( 'whatsapp' ); ?>
				<span>
					<span class="info-label">WhatsApp</span>
					<a href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Scrivici su WhatsApp', 'pizzumm' ); ?></a>
				</span>
			</li>
		<?php endif; ?>

		<?php if ( $email ) : ?>
			<li>
				<?php pizzumm_the_icon( 'mail' ); ?>
				<span>
					<span class="info-label"><?php esc_html_e( 'E-mail', 'pizzumm' ); ?></span>
					<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				</span>
			</li>
		<?php endif; ?>

		<?php if ( $hours ) : ?>
			<li>
				<?php pizzumm_the_icon( 'clock' ); ?>
				<span style="flex:1">
					<span class="info-label"><?php esc_html_e( 'Orari', 'pizzumm' ); ?></span>
					<ul class="hours">
						<?php foreach ( $hours as $line ) :
							$parts = array_map( 'trim', explode( '|', $line ) ); ?>
							<li>
								<span><?php echo esc_html( $parts[0] ); ?></span>
								<?php if ( isset( $parts[1] ) ) : ?>
									<span><?php echo esc_html( $parts[1] ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</span>
			</li>
		<?php endif; ?>

		<li>
			<?php pizzumm_the_icon( 'scooter' ); ?>
			<span>
				<span class="info-label"><?php esc_html_e( 'Consegna', 'pizzumm' ); ?></span>
				<a href="<?php echo esc_url( pizzumm_glovo_url() ); ?>"<?php echo pizzumm_glovo_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php esc_html_e( 'Ordina su Glovo', 'pizzumm' ); ?></a>
			</span>
		</li>
	</ul>

	<?php if ( $socials ) : ?>
		<h3 style="margin-top:2rem;font-size:var(--fs-md)"><?php esc_html_e( 'Seguici', 'pizzumm' ); ?></h3>
		<ul class="socials">
			<?php foreach ( $socials as $label => $social ) : ?>
				<li>
					<a href="<?php echo esc_url( $social['url'] ); ?>" target="_blank" rel="noopener noreferrer">
						<?php pizzumm_the_icon( $social['icon'] ); ?><?php echo esc_html( $label ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	<?php
	return (string) ob_get_clean();
}

/* -------------------------------------------------------------------------
 *  Banda CTA finale
 * ---------------------------------------------------------------------- */
function pizzumm_sc_cta_band( $atts ) {
	$atts = shortcode_atts(
		array(
			'title'  => __( 'Hai già scelto? La parte facile inizia adesso.', 'pizzumm' ),
			'text'   => '',
			'kicker' => __( 'Pizzumm ti aspetta', 'pizzumm' ),
		),
		$atts,
		'pizzumm_cta_band'
	);

	return pizzumm_capture( 'pizzumm_cta_band', array( $atts['title'], $atts['text'], $atts['kicker'] ) );
}

/* -------------------------------------------------------------------------
 *  Nota allergeni
 * ---------------------------------------------------------------------- */
function pizzumm_sc_allergen_note() {
	ob_start();
	?>
	<p class="allergen-note" data-anim>
		<?php pizzumm_the_icon( 'info' ); ?>
		<span>
			<?php esc_html_e( 'Alcuni prodotti possono contenere allergeni o essere lavorati in un laboratorio dove sono presenti glutine, latte, uova, frutta a guscio, soia e sesamo. Per informazioni dettagliate chiedi al banco: l’elenco completo degli ingredienti e degli allergeni è sempre disponibile.', 'pizzumm' ); ?>
		</span>
	</p>
	<?php
	return (string) ob_get_clean();
}

/* -------------------------------------------------------------------------
 *  Elemento timeline (chi siamo)
 * ---------------------------------------------------------------------- */
function pizzumm_sc_timeline_item( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'numero'   => '1',
			'titolo'   => '',
			'testo'    => '',
			'immagine' => '',
			'alt'      => '',
		),
		$atts,
		'pizzumm_timeline_item'
	);

	$text = $atts['testo'] ? $atts['testo'] : wp_strip_all_tags( $content );

	ob_start();
	?>
	<article class="timeline__item" data-anim>
		<div>
			<span class="timeline__index"><?php echo esc_html( $atts['numero'] ); ?></span>
			<h2 class="timeline__title" data-split><?php echo esc_html( $atts['titolo'] ); ?></h2>
			<p class="lead"><?php echo esc_html( $text ); ?></p>
		</div>
		<?php if ( $atts['immagine'] ) : ?>
			<div class="timeline__media">
				<img src="<?php echo esc_url( $atts['immagine'] ); ?>"
					alt="<?php echo esc_attr( $atts['alt'] ); ?>"
					width="1200" height="900" loading="lazy" decoding="async" />
			</div>
		<?php endif; ?>
	</article>
	<?php
	return (string) ob_get_clean();
}
