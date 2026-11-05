<?php
/**
 * Banner cookie e testi delle pagine Privacy e Cookie policy.
 *
 * Il sito usa solo cookie tecnici. L'unico contenuto di terze parti che può
 * installare cookie è la mappa di Google (pagina Contatti), caricata solo
 * dopo il consenso. La scelta resta nel cookie `pizzumm_consent` per 6 mesi.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function pizzumm_cookie_policy_url() {
	$url = get_theme_mod( 'pizzumm_cookie_url', '' );
	if ( $url ) {
		return $url;
	}
	$page = get_page_by_path( 'cookie-policy' );
	return ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : '';
}

function pizzumm_cookie_banner() {
	if ( is_admin() || ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->preview ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) ) {
		return;
	}
	$privacy = get_privacy_policy_url();
	$cookie  = pizzumm_cookie_policy_url();
	?>
	<div class="cookie-banner" id="pizzumm-cookie" role="dialog" aria-modal="false" aria-labelledby="cookie-banner-title" hidden>
		<button type="button" class="cookie-banner__close" data-consent="necessary" aria-label="<?php esc_attr_e( 'Chiudi e continua senza accettare', 'pizzumm' ); ?>">&times;</button>
		<p class="cookie-banner__title" id="cookie-banner-title"><?php esc_html_e( 'Un biscotto prima della fetta?', 'pizzumm' ); ?></p>
		<p class="cookie-banner__text">
			<?php esc_html_e( 'Usiamo solo cookie tecnici, necessari al funzionamento del sito. Con il tuo consenso carichiamo anche la mappa di Google nella pagina Contatti, che può installare cookie di terze parti. Puoi cambiare idea quando vuoi da "Preferenze cookie" in fondo alla pagina.', 'pizzumm' ); ?>
			<?php if ( $cookie ) : ?>
				<a href="<?php echo esc_url( $cookie ); ?>"><?php esc_html_e( 'Cookie policy', 'pizzumm' ); ?></a>
			<?php endif; ?>
			<?php if ( $privacy ) : ?>
				· <a href="<?php echo esc_url( $privacy ); ?>"><?php esc_html_e( 'Privacy policy', 'pizzumm' ); ?></a>
			<?php endif; ?>
		</p>
		<div class="cookie-banner__actions">
			<button type="button" class="btn btn--sm btn--ghost" data-consent="necessary"><?php esc_html_e( 'Solo necessari', 'pizzumm' ); ?></button>
			<button type="button" class="btn btn--sm btn--red" data-consent="all"><?php esc_html_e( 'Accetta tutti', 'pizzumm' ); ?></button>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'pizzumm_cookie_banner', 5 );

function pizzumm_legal_company() {
	return array(
		'name'    => 'Pizzum s.r.l.s.',
		'address' => 'Piazza Vittorio Veneto, 11 – 80045 Pompei (NA)',
		'cf'      => '11058441210',
		'rea'     => 'NA-1151746',
		'email'   => pizzumm_opt( 'email' ),
		'phone'   => pizzumm_opt( 'phone' ),
	);
}

function pizzumm_legal_privacy_content() {
	$c       = pizzumm_legal_company();
	$cookie  = home_url( '/cookie-policy/' );
	$contact = home_url( '/contatti/' );

	return '<div class="legal">
<p class="legal__updated">Ultimo aggiornamento: 5 novembre 2026</p>

<p>Questa informativa spiega come trattiamo i dati personali di chi visita il sito www.pizzumm.it e di chi ci contatta, ai sensi dell’art. 13 del Regolamento (UE) 2016/679 (GDPR) e del D.Lgs. 196/2003 (Codice privacy).</p>

<h2>Titolare del trattamento</h2>
<p><strong>' . esc_html( $c['name'] ) . '</strong><br>' . esc_html( $c['address'] ) . '<br>Codice fiscale e Partita IVA ' . esc_html( $c['cf'] ) . ' · REA ' . esc_html( $c['rea'] ) . '<br>Email: <a href="mailto:' . esc_attr( $c['email'] ) . '">' . esc_html( $c['email'] ) . '</a> · Telefono: ' . esc_html( $c['phone'] ) . '</p>

<h2>Quali dati trattiamo</h2>
<h3>Dati di navigazione</h3>
<p>I sistemi che fanno funzionare il sito raccolgono in automatico alcuni dati tecnici legati alla navigazione, come indirizzo IP, tipo di browser, pagine visitate, data e ora della richiesta. Servono solo a far funzionare il sito, a garantirne la sicurezza e a individuare eventuali abusi; non vengono usati per identificarti o profilarti.</p>
<h3>Dati che ci fornisci tu</h3>
<p>Se compili il <a href="' . esc_url( $contact ) . '">modulo di contatto</a>, ci scrivi un’email o un messaggio WhatsApp, oppure ci chiami, trattiamo i dati che ci comunichi: nome, email, numero di telefono e il contenuto del messaggio. Ti chiediamo di non inserire dati non necessari, in particolare dati relativi alla salute (ad esempio allergie o intolleranze) se non servono a rispondere alla tua richiesta.</p>
<h3>Cookie</h3>
<p>Il sito usa solo cookie tecnici. Contenuti di terze parti, come la mappa di Google, vengono caricati solo con il tuo consenso. Tutti i dettagli sono nella <a href="' . esc_url( $cookie ) . '">Cookie policy</a>.</p>

<h2>Perché li trattiamo e su quale base</h2>
<ul>
<li><strong>Rispondere alle tue richieste</strong> (informazioni, prenotazioni, eventi, collaborazioni): il trattamento è necessario per dare seguito a una tua richiesta (art. 6.1.b GDPR).</li>
<li><strong>Far funzionare il sito e proteggerlo</strong>: legittimo interesse del titolare alla sicurezza e al corretto funzionamento del servizio (art. 6.1.f GDPR).</li>
<li><strong>Adempiere a obblighi di legge</strong> o rispondere a richieste delle autorità (art. 6.1.c GDPR).</li>
<li><strong>Difendere un nostro diritto</strong> in caso di contestazioni (art. 6.1.f GDPR).</li>
</ul>
<p>Non usiamo i tuoi dati per inviarti newsletter o pubblicità, né per profilazione o decisioni automatizzate.</p>

<h2>Conferimento dei dati</h2>
<p>Fornire i dati nel modulo di contatto è facoltativo, ma senza nome, email e messaggio non possiamo risponderti.</p>

<h2>Per quanto tempo li conserviamo</h2>
<ul>
<li>Messaggi e richieste di contatto: per il tempo necessario a gestirle e comunque non oltre 12 mesi dall’ultimo scambio, salvo che servano più a lungo per obblighi di legge o per tutelare un nostro diritto.</li>
<li>Dati di navigazione: per il tempo tecnico previsto dal fornitore di hosting, salvo necessità di accertare reati informatici.</li>
<li>Scelta sui cookie: 6 mesi.</li>
</ul>

<h2>Chi può vedere i dati</h2>
<p>I dati sono trattati da persone autorizzate di ' . esc_html( $c['name'] ) . ' e possono essere trattati, per nostro conto e come responsabili del trattamento, dai fornitori dei servizi tecnici che usiamo: hosting del sito ed email (Aruba S.p.A., con server nell’Unione Europea) e consulenti informatici o amministrativi, solo quando necessario. I dati non vengono diffusi né venduti.</p>

<h2>Servizi di terze parti</h2>
<p>Dal sito puoi raggiungere servizi gestiti da altre aziende, che trattano i tuoi dati come titolari autonomi secondo le proprie informative:</p>
<ul>
<li><strong>Glovo</strong>, per gli ordini a domicilio;</li>
<li><strong>WhatsApp</strong> (Meta Platforms Ireland), se ci scrivi da lì;</li>
<li><strong>Google Maps</strong> (Google Ireland Ltd), se carichi la mappa o apri l’indirizzo su Google Maps;</li>
<li>i social network che trovi linkati nel sito (Facebook, Instagram, TikTok, Tripadvisor).</li>
</ul>
<p>Alcuni di questi servizi possono trasferire dati fuori dall’Unione Europea, ad esempio negli Stati Uniti, sulla base di garanzie previste dal GDPR come il Data Privacy Framework UE-USA o le clausole contrattuali standard.</p>

<h2>I tuoi diritti</h2>
<p>In qualsiasi momento puoi chiedere di accedere ai tuoi dati, di correggerli, di cancellarli, di limitarne il trattamento, di riceverli in un formato leggibile (portabilità) e puoi opporti al trattamento basato sul legittimo interesse (artt. 15–22 GDPR). Per farlo scrivi a <a href="mailto:' . esc_attr( $c['email'] ) . '">' . esc_html( $c['email'] ) . '</a>: ti risponderemo entro un mese.</p>
<p>Se ritieni che il trattamento non sia corretto puoi presentare reclamo al Garante per la protezione dei dati personali (<a href="https://www.garanteprivacy.it" target="_blank" rel="noopener">www.garanteprivacy.it</a>).</p>

<h2>Modifiche</h2>
<p>Potremmo aggiornare questa informativa. La versione valida è sempre quella pubblicata su questa pagina, con la data dell’ultimo aggiornamento in alto.</p>
</div>';
}

function pizzumm_legal_cookie_content() {
	$c       = pizzumm_legal_company();
	$privacy = home_url( '/privacy-policy/' );

	return '<div class="legal">
<p class="legal__updated">Ultimo aggiornamento: 5 novembre 2026</p>

<p>Questa pagina spiega quali cookie usa il sito www.pizzumm.it, gestito da ' . esc_html( $c['name'] ) . ', ' . esc_html( $c['address'] ) . ', in conformità al D.Lgs. 196/2003 e alle Linee guida del Garante privacy del 10 giugno 2021. Per tutto il resto vale la <a href="' . esc_url( $privacy ) . '">Privacy policy</a>.</p>

<h2>Cosa sono i cookie</h2>
<p>I cookie sono piccoli file di testo che un sito salva nel tuo browser per ricordare informazioni, ad esempio una scelta che hai già fatto. Alcuni sono indispensabili per far funzionare il sito (cookie tecnici); altri servono a misurare le visite o a mostrare pubblicità e richiedono il tuo consenso.</p>

<h2>Cookie tecnici (sempre attivi)</h2>
<p>Non richiedono consenso perché servono a far funzionare il sito.</p>
<div class="legal__table"><table>
<thead><tr><th>Nome</th><th>A cosa serve</th><th>Durata</th></tr></thead>
<tbody>
<tr><td>pizzumm_consent</td><td>Ricorda la tua scelta sui cookie, così il banner non ricompare a ogni pagina.</td><td>6 mesi</td></tr>
</tbody>
</table></div>
<p>Se accedi all’area riservata di WordPress (solo per lo staff), il sito usa anche i cookie tecnici di accesso di WordPress.</p>

<h2>Cookie di analisi e di profilazione</h2>
<p>Non ne usiamo: il sito non misura le visite e non mostra pubblicità.</p>

<h2>Contenuti di terze parti (solo con il tuo consenso)</h2>
<div class="legal__table"><table>
<thead><tr><th>Servizio</th><th>Dove</th><th>Cosa fa</th><th>Informativa</th></tr></thead>
<tbody>
<tr><td>Google Maps<br>(Google Ireland Ltd)</td><td>Pagina Contatti</td><td>Mostra la mappa per raggiungerci. Una volta caricata, Google può installare cookie e raccogliere dati di navigazione.</td><td><a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Privacy</a> · <a href="https://policies.google.com/technologies/cookies" target="_blank" rel="noopener">Cookie</a></td></tr>
</tbody>
</table></div>
<p>Finché non dai il consenso, al posto della mappa vedi un riquadro con il pulsante “Carica la mappa”: nessun dato viene inviato a Google.</p>
<p>I link a Glovo, WhatsApp e ai social sono semplici collegamenti: questi servizi installano i loro cookie solo se li apri, secondo le rispettive informative.</p>

<h2>Come gestire il consenso</h2>
<p>Alla prima visita un banner ti chiede cosa preferisci: “Accetta tutti” o “Solo necessari”. Chiudere il banner con la × equivale a “Solo necessari”. Puoi cambiare scelta in ogni momento dal link “Preferenze cookie” in fondo a ogni pagina. La scelta viene richiesta di nuovo dopo 6 mesi.</p>
<p>Puoi anche bloccare o cancellare i cookie dalle impostazioni del browser: <a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">Chrome</a>, <a href="https://support.mozilla.org/it/kb/protezione-antitracciamento-avanzata-firefox-desktop" target="_blank" rel="noopener">Firefox</a>, <a href="https://support.apple.com/it-it/guide/safari/sfri11471/mac" target="_blank" rel="noopener">Safari</a>, <a href="https://support.microsoft.com/it-it/microsoft-edge/eliminare-i-cookie-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" rel="noopener">Edge</a>.</p>

<h2>Contatti</h2>
<p>Per qualsiasi domanda scrivi a <a href="mailto:' . esc_attr( $c['email'] ) . '">' . esc_html( $c['email'] ) . '</a>.</p>
</div>';
}
