<?php
/**
 * Opzioni del tema nel Customizer (Aspetto → Personalizza → Pizzumm).
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra pannelli, sezioni e controlli.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function pizzumm_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->add_panel( 'pizzumm_panel', array(
		'title'       => __( 'Pizzumm — Impostazioni', 'pizzumm' ),
		'description' => __( 'Dati del locale, colori, link di ordinazione e social. Tutto ciò che compare in header, footer e pagina contatti si imposta da qui.', 'pizzumm' ),
		'priority'    => 20,
	) );

	/* ---------------------------------------------------------------
	 * Colori del brand
	 * ------------------------------------------------------------- */
	$wp_customize->add_section( 'pizzumm_colori', array(
		'title'       => __( 'Colori del brand', 'pizzumm' ),
		'panel'       => 'pizzumm_panel',
		'description' => __( 'Il logo usa sfumature, non tinte piatte: ogni colore ha una tinta piena (testi, bordi, icone) e due stop che compongono il gradiente usato su pulsanti, fasce e sfondi.', 'pizzumm' ),
	) );

	foreach ( pizzumm_palette() as $slug => $conf ) {
		$setting = 'pizzumm_color_' . $slug;

		$wp_customize->add_setting( $setting, array(
			'default'           => $conf['default'],
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
			'label'       => $conf['label'],
			'description' => empty( $conf['gradient'] )
				? ''
				: __( 'Tinta piena: usata per testi, bordi e icone.', 'pizzumm' ),
			'section'     => 'pizzumm_colori',
		) ) );

		if ( empty( $conf['gradient'] ) ) {
			continue;
		}

		$stops = array(
			'from' => array( __( '%s — inizio sfumatura', 'pizzumm' ), $conf['gradient'][0] ),
			'to'   => array( __( '%s — fine sfumatura', 'pizzumm' ), $conf['gradient'][1] ),
		);

		foreach ( $stops as $key => $stop ) {
			$stop_setting = 'pizzumm_grad_' . $slug . '_' . $key;

			$wp_customize->add_setting( $stop_setting, array(
				'default'           => $stop[1],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $stop_setting, array(
				'label'   => sprintf( $stop[0], $conf['label'] ),
				'section' => 'pizzumm_colori',
			) ) );
		}
	}

	$wp_customize->add_setting( 'pizzumm_gradient_angle', array(
		'default'           => 135,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'pizzumm_gradient_angle', array(
		'label'       => __( 'Inclinazione delle sfumature', 'pizzumm' ),
		'description' => __( 'In gradi: 0 = dal basso verso l\'alto, 90 = da sinistra a destra, 135 = diagonale (predefinito).', 'pizzumm' ),
		'section'     => 'pizzumm_colori',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 0, 'max' => 360, 'step' => 5 ),
	) );

	/* ---------------------------------------------------------------
	 * Dati del locale
	 * ------------------------------------------------------------- */
	$wp_customize->add_section( 'pizzumm_contatti', array(
		'title'       => __( 'Dati del locale', 'pizzumm' ),
		'panel'       => 'pizzumm_panel',
		'description' => __( 'Indirizzo, telefono, e-mail e orari usati in pagina Contatti, nel footer e nei dati strutturati per Google.', 'pizzumm' ),
	) );

	$defaults = pizzumm_defaults();

	$fields = array(
		'address'       => array( __( 'Indirizzo completo', 'pizzumm' ), 'text', 'sanitize_text_field' ),
		'address_short' => array( __( 'Indirizzo breve (footer)', 'pizzumm' ), 'text', 'sanitize_text_field' ),
		'phone'         => array( __( 'Telefono', 'pizzumm' ), 'text', 'sanitize_text_field' ),
		'whatsapp'      => array( __( 'WhatsApp (numero o link)', 'pizzumm' ), 'text', 'sanitize_text_field' ),
		'email'         => array( __( 'E-mail pubblica', 'pizzumm' ), 'email', 'sanitize_email' ),
		'hours'         => array( __( 'Orari — una riga per fascia, es. "Lun–Ven | 12:00–15:00"', 'pizzumm' ), 'textarea', 'sanitize_textarea_field' ),
		'company'       => array( __( 'Ragione sociale e P. IVA', 'pizzumm' ), 'text', 'sanitize_text_field' ),
	);

	foreach ( $fields as $key => $conf ) {
		list( $label, $type, $sanitize ) = $conf;

		$wp_customize->add_setting( 'pizzumm_' . $key, array(
			'default'           => isset( $defaults[ $key ] ) ? $defaults[ $key ] : '',
			'sanitize_callback' => $sanitize,
		) );

		$wp_customize->add_control( 'pizzumm_' . $key, array(
			'label'   => $label,
			'section' => 'pizzumm_contatti',
			'type'    => $type,
		) );
	}

	/* ---------------------------------------------------------------
	 * Ordini (Glovo)
	 * ------------------------------------------------------------- */
	$wp_customize->add_section( 'pizzumm_ordini', array(
		'title'       => __( 'Ordini (Glovo)', 'pizzumm' ),
		'panel'       => 'pizzumm_panel',
		'description' => __( 'Il link Glovo alimenta tutte le CTA “Ordina ora” del sito e si apre in una nuova scheda. Se lasciato vuoto, i pulsanti rimandano alla pagina Contatti. I colori qui sotto vanno allineati al brand kit ufficiale di Glovo.', 'pizzumm' ),
	) );

	// Il pulsante "Ordina su Glovo" nella barra del menu è spento finché
	// l'account Glovo non è attivo: basta spuntare qui per ripristinarlo.
	$wp_customize->add_setting( 'pizzumm_glovo_header', array(
		'default'           => false,
		'sanitize_callback' => 'pizzumm_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'pizzumm_glovo_header', array(
		'label'   => __( 'Mostra il pulsante Glovo nella barra del menu', 'pizzumm' ),
		'section' => 'pizzumm_ordini',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'pizzumm_glovo_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'pizzumm_glovo_url', array(
		'label'   => __( 'URL della pagina Glovo', 'pizzumm' ),
		'section' => 'pizzumm_ordini',
		'type'    => 'url',
	) );

	$wp_customize->add_setting( 'pizzumm_glovo_logo', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$glovo_colors = array(
		'glovo_bg'     => array( __( 'Pulsante Glovo — sfondo', 'pizzumm' ), '#F0C838' ),
		'glovo_bg_hover' => array( __( 'Pulsante Glovo — sfondo (hover)', 'pizzumm' ), '#DDB42B' ),
		'glovo_fg'     => array( __( 'Pulsante Glovo — testo', 'pizzumm' ), '#14322B' ),
		'glovo_accent' => array( __( 'Pulsante Glovo — accento', 'pizzumm' ), '#389880' ),
	);

	foreach ( $glovo_colors as $key => $conf ) {
		$wp_customize->add_setting( 'pizzumm_' . $key, array(
			'default'           => $conf[1],
			'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pizzumm_' . $key, array(
			'label'   => $conf[0],
			'section' => 'pizzumm_ordini',
		) ) );
	}

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'pizzumm_glovo_logo', array(
		'label'       => __( 'Logo Glovo per i pulsanti', 'pizzumm' ),
		'description' => __( 'Carica il logo ufficiale preso dal brand kit di Glovo (SVG o PNG su fondo trasparente, versione chiara: i pulsanti sono scuri o rossi). Finché non lo carichi, i pulsanti mostrano la scritta “Glovo”.', 'pizzumm' ),
		'section'     => 'pizzumm_ordini',
	) ) );

	/* ---------------------------------------------------------------
	 * Social
	 * ------------------------------------------------------------- */
	$wp_customize->add_section( 'pizzumm_social', array(
		'title' => __( 'Social', 'pizzumm' ),
		'panel' => 'pizzumm_panel',
	) );

	foreach ( array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'tripadvisor' => 'Tripadvisor' ) as $key => $label ) {
		$wp_customize->add_setting( 'pizzumm_' . $key, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'pizzumm_' . $key, array(
			'label'   => $label,
			'section' => 'pizzumm_social',
			'type'    => 'url',
		) );
	}

	/* ---------------------------------------------------------------
	 * Hero della home
	 * ------------------------------------------------------------- */
	$wp_customize->add_section( 'pizzumm_hero', array(
		'title'       => __( 'Hero della home', 'pizzumm' ),
		'panel'       => 'pizzumm_panel',
		'description' => __( 'Carica un video MP4 muto (consigliato max 5 MB) e un\'immagine. Senza video viene mostrata solo l\'immagine.', 'pizzumm' ),
	) );

	$wp_customize->add_setting( 'pizzumm_hero_video', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'pizzumm_hero_video', array(
		'label'     => __( 'Video hero (MP4)', 'pizzumm' ),
		'section'   => 'pizzumm_hero',
		'mime_type' => 'video',
	) ) );

	$wp_customize->add_setting( 'pizzumm_hero_poster', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'pizzumm_hero_poster', array(
		'label'       => __( 'Immagine hero / poster del video', 'pizzumm' ),
		'description' => __( 'Formato consigliato 4:3, almeno 1200×900 px.', 'pizzumm' ),
		'section'     => 'pizzumm_hero',
	) ) );

	/* ---------------------------------------------------------------
	 * Mappa
	 * ------------------------------------------------------------- */
	$wp_customize->add_section( 'pizzumm_mappa', array(
		'title' => __( 'Mappa', 'pizzumm' ),
		'panel' => 'pizzumm_panel',
	) );

	$wp_customize->add_setting( 'pizzumm_map_query', array(
		'default'           => $defaults['map_query'],
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'pizzumm_map_query', array(
		'label'   => __( 'Indirizzo da cercare su Google Maps', 'pizzumm' ),
		'section' => 'pizzumm_mappa',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'pizzumm_map_embed', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'pizzumm_map_embed', array(
		'label'       => __( 'URL embed personalizzato (facoltativo)', 'pizzumm' ),
		'description' => __( 'Incolla qui l\'URL contenuto nell\'iframe fornito da Google Maps → Condividi → Incorpora una mappa.', 'pizzumm' ),
		'section'     => 'pizzumm_mappa',
		'type'        => 'url',
	) );

	$wp_customize->add_setting( 'pizzumm_map_consent', array(
		'default'           => false,
		'sanitize_callback' => 'pizzumm_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'pizzumm_map_consent', array(
		'label'       => __( 'Carica la mappa solo dopo il clic dell\'utente', 'pizzumm' ),
		'description' => __( 'Spenta, la mappa si carica subito. Accendila se il tuo banner cookie richiede il consenso prima di contattare Google.', 'pizzumm' ),
		'section'     => 'pizzumm_mappa',
		'type'        => 'checkbox',
	) );

	/* ---------------------------------------------------------------
	 * Legale e modulo contatti
	 * ------------------------------------------------------------- */
	$wp_customize->add_section( 'pizzumm_legale', array(
		'title' => __( 'Legale e privacy', 'pizzumm' ),
		'panel' => 'pizzumm_panel',
	) );

	$wp_customize->add_setting( 'pizzumm_privacy_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'pizzumm_privacy_url', array(
		'label'       => __( 'URL Privacy Policy', 'pizzumm' ),
		'description' => __( 'Se vuoto viene usata la pagina impostata in Impostazioni → Privacy.', 'pizzumm' ),
		'section'     => 'pizzumm_legale',
		'type'        => 'url',
	) );

	$wp_customize->add_setting( 'pizzumm_cookie_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'pizzumm_cookie_url', array(
		'label'   => __( 'URL Cookie Policy', 'pizzumm' ),
		'section' => 'pizzumm_legale',
		'type'    => 'url',
	) );

	$wp_customize->add_setting( 'pizzumm_cookie_prefs_selector', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'pizzumm_cookie_prefs_selector', array(
		'label'       => __( 'Selettore CSS per “Preferenze cookie”', 'pizzumm' ),
		'description' => __( 'Es. “#cookie-preferences” o la classe del pulsante del banner. Se vuoto la voce non compare nel footer.', 'pizzumm' ),
		'section'     => 'pizzumm_legale',
		'type'        => 'text',
	) );

	/* ---------------------------------------------------------------
	 * Footer
	 * ------------------------------------------------------------- */
	$wp_customize->add_section( 'pizzumm_footer', array(
		'title'       => __( 'Footer', 'pizzumm' ),
		'panel'       => 'pizzumm_panel',
		'description' => __( 'Numero di colonne widget, prefooter e testo del copyright.', 'pizzumm' ),
	) );

	$wp_customize->add_setting( 'pizzumm_footer_columns', array(
		'default'           => 4,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'pizzumm_footer_columns', array(
		'label'       => __( 'Numero di colonne', 'pizzumm' ),
		'description' => __( 'Le aree "Footer · Colonna N" si popolano da Aspetto → Widget.', 'pizzumm' ),
		'section'     => 'pizzumm_footer',
		'type'        => 'select',
		'choices'     => array( 2 => '2', 3 => '3', 4 => '4' ),
	) );

	$wp_customize->add_setting( 'pizzumm_prefooter_enabled', array(
		'default'           => false,
		'sanitize_callback' => 'pizzumm_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'pizzumm_prefooter_enabled', array(
		'label'   => __( 'Attiva prefooter (2 colonne)', 'pizzumm' ),
		'section' => 'pizzumm_footer',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'pizzumm_copyright_text', array(
		'default'           => pizzumm_default_copyright(),
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'pizzumm_copyright_text', array(
		'label'       => __( 'Testo del copyright', 'pizzumm' ),
		'description' => __( 'Puoi usare i segnaposto {year} e {sitename}, e link HTML.', 'pizzumm' ),
		'section'     => 'pizzumm_footer',
		'type'        => 'textarea',
	) );

	$menus_choices = array( 0 => __( '— Nessuno —', 'pizzumm' ) );
	foreach ( wp_get_nav_menus() as $menu ) {
		$menus_choices[ (int) $menu->term_id ] = $menu->name;
	}

	$wp_customize->add_setting( 'pizzumm_copyright_menu_id', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'pizzumm_copyright_menu_id', array(
		'label'       => __( 'Menu legale (a destra del copyright)', 'pizzumm' ),
		'section'     => 'pizzumm_footer',
		'type'        => 'select',
		'choices'     => $menus_choices,
	) );

}

add_action( 'customize_register', 'pizzumm_customize_register' );

/**
 * Sanitizza una checkbox.
 *
 * @param mixed $value Valore.
 * @return bool
 */
function pizzumm_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Anteprima live del nome sito.
 */
function pizzumm_customize_preview_js() {
	wp_add_inline_script(
		'customize-preview',
		"wp.customize('blogname',function(v){v.bind(function(t){var e=document.querySelector('.brand__word');if(e){e.innerHTML=t;}});});"
	);
}
add_action( 'customize_preview_init', 'pizzumm_customize_preview_js' );
