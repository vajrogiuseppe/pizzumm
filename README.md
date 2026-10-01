# Pizzumm — tema WordPress

Tema su misura per **Pizzumm**, pizza in teglia in Piazza Vittorio Veneto 11 a Pompei.
Quattro pagine, un solo obiettivo operativo: far scegliere, trovare o ordinare.

---

## Installazione

1. Comprimi la cartella `pizzumm` in uno zip.
2. WordPress → **Aspetto → Temi → Aggiungi nuovo → Carica tema** → attiva.
3. Installa e attiva i **plugin richiesti** (compaiono in bacheca con un link diretto):
   - **Elementor** (versione free) — costruttore visuale delle pagine.
   - **Advanced Custom Fields** (ACF) — pannello impostazioni del tema.
   - **Contact Form 7** — modulo contatti.

Appena Elementor è attivo, le 4 pagine del sito vengono **pre-configurate
automaticamente in Elementor**: aprendo una qualsiasi pagina con "Modifica con
Elementor" trovi ogni sezione del layout come widget separato, pronto per
essere spostato, duplicato o eliminato. Nessun file JSON da importare.

All'attivazione il tema crea da solo:

- le 4 pagine (**Home**, **Chi siamo**, **Menu**, **Contatti**), **già popolate** con la
  composizione di blocchi Gutenberg corrispondente: apri "Modifica pagina" e trovi ogni
  sezione come blocco modificabile, spostabile o eliminabile;
- la home impostata come pagina iniziale;
- il **menu di navigazione** principale;
- le **categorie del menu** e alcuni **prodotti demo** con foto segnaposto;
- un **modulo Contact Form 7** predefinito ("Contatti Pizzumm"), già collegato alla
  pagina Contatti.

---

## Pannello "Tema Pizzumm" (bacheca)

Voce **Tema Pizzumm** nel menu laterale. Tre tab:

### Hero

- **Tipo di hero** — `Attuale` (foto tonda con pizze fluttuanti) · `Solo foto` · `Video`.
- **Foto hero** — usata in modalità "Attuale" e "Solo foto".
- **Video hero (MP4)** — muto, in loop, max 5 MB consigliati.
- **Poster del video** — immagine di caricamento.

### Footer

- **Numero colonne** — `2`, `3` o `4`. Le aree "Footer · Colonna N" appaiono
  automaticamente in **Aspetto → Widget** e si compongono con **widget nativi di
  WordPress** (Menu di navigazione, Testo, HTML, Immagine, Ricerca, ecc).
- **Prefooter** — riga superiore opzionale con due aree widget (utile per CTA o
  banner sopra il footer principale).

Suggerimenti predefiniti per le colonne:

| Colonna | Cosa metterci |
|---|---|
| 1 | Logo, breve descrizione, indirizzo del locale |
| 2 | Widget "Menu di navigazione" (menu Pagine) |
| 3 | Widget "Menu di navigazione" o Testo (Servizi: ordina, contatti, ecc) |
| 4 | Widget "Testo" con lo shortcode del tuo plugin newsletter (MailPoet, Mailchimp for WP, Newsletter, ecc) |

### Copyright

- **Testo della barra copyright** — WYSIWYG con link HTML. Segnaposto disponibili:
  - `{year}` → anno corrente
  - `{sitename}` → nome del sito
  - Esempio: `© {year} {sitename} — made by <a href="https://studio.it">Studio X</a>`
- **Menu legale (a destra)** — selettore del menu WP con link privacy, cookie, ecc.
  Crealo prima in *Aspetto → Menu*.

---

## Modificare le pagine con Gutenberg

Ogni pagina è un **template Gutenberg** completo: **Modifica pagina** apre l'editor
a blocchi con tutte le sezioni già impaginate. Puoi:

- **modificare** testi, titoli, immagini direttamente in ogni blocco;
- **spostare** o **duplicare** una sezione;
- **eliminare** o **aggiungere** blocchi (categoria **Pizzumm** nell'inseritore).

### Metabox "Layout Pizzumm" (colonna laterale)

| Opzione | Effetto |
|---|---|
| **Automatico** | Se la pagina ha contenuto blocchi, mostra i blocchi. Se è vuota, mostra il fallback PHP del tema. |
| **Usa sempre il layout Pizzumm** | Ignora i blocchi, mostra il fallback PHP. |
| **Usa solo il contenuto dell'editor** | Mostra solo i blocchi. |

**Rigenera contenuto Gutenberg** — pulsante nel metabox che ripristina la
composizione predefinita della pagina (utile se hai eliminato tutto e vuoi ripartire).

### Pattern disponibili nella categoria "Pizzumm"

`00 · Apertura pagina` · `01 · Hero rosso con pizza` · `02 · Immagini e testo con
elenco` · `03 · Tre passaggi su fondo rosso` · `04 · Racconto con foto` ·
`05 · CTA finale` · `06 · FAQ` · `07 · Timeline "Chi siamo"` ·
`08 · Menu completo (dinamico)` · `09 · Mappa Google` · `10 · Info del locale` ·
`11 · Modulo contatti (Contact Form 7)`.

Alcune sezioni dinamiche (menu prodotti, mappa, info locale) si compongono di
uno shortcode dedicato dentro un blocco "Shortcode":

- `[pizzumm_menu_prodotti]` — griglia completa del menu, dai contenuti reali del CMS
- `[pizzumm_categorie]` — griglia tonda delle categorie
- `[pizzumm_mappa]` — mappa Google embed con banner consenso
- `[pizzumm_info_locale]` — elenco contatti + orari + social
- `[pizzumm_home_facts]` — le tre "prove sociali" dell'hero
- `[pizzumm_ticker]` — nastro scorrevole
- `[pizzumm_allergen_note]` — nota allergeni della pagina menu
- `[pizzumm_cta_band title="…" text="…" kicker="…"]` — banda CTA finale
- `[pizzumm_timeline_item numero="1" titolo="…" immagine="…"]testo[/pizzumm_timeline_item]`

---

## Modulo contatti — Contact Form 7

Il tema si appoggia a **Contact Form 7**. All'attivazione crea un modulo predefinito
chiamato **"Contatti Pizzumm"** (destinatario = e-mail del locale, con `Reply-To` a
chi scrive). Trovi il modulo in **Contatto → Moduli** nella bacheca: qui modifichi
campi, messaggi, notifiche.

Nel pattern *11 · Modulo contatti* lo shortcode è già inserito con l'ID reale del
modulo. Se ne crei uno nuovo, sostituisci lo shortcode con quello che ti dà CF7
(icona verde nella colonna "Shortcode" della lista moduli).

### Se le e-mail non arrivano

`wp_mail()` da hosting condiviso finisce spesso nello spam. Installa un plugin SMTP
(es. *WP Mail SMTP*) collegato alla casella del dominio, e imposta come mittente
un indirizzo di quel dominio.

---

## Aspetto → Personalizza → Pizzumm

Le impostazioni **non legate a hero/footer/copyright** restano nel Customizer:

| Sezione | Contenuto |
|---|---|
| **Colori del brand** | I 7 colori del logo con tinta piena e gradienti, più angolo di sfumatura. |
| **Dati del locale** | Indirizzo, telefono, WhatsApp, e-mail, orari, ragione sociale e P. IVA. |
| **Ordini (Glovo)** | URL Glovo (alimenta ogni pulsante "Ordina") + logo del brand + colori del pulsante. |
| **Social** | Instagram, TikTok, Facebook. |
| **Mappa** | Indirizzo, embed personalizzato, consenso GDPR. |
| **Legale e privacy** | Privacy URL, cookie URL, selettore preferenze cookie. |

Il **logo** si carica da *Personalizza → Identità del sito*.

### Orari — formato

Una riga per fascia, con `|` a separare giorni e orari:

```
Lunedì – Giovedì | 12:00 – 15:00 · 18:00 – 23:00
Venerdì – Sabato | 12:00 – 15:00 · 18:00 – 00:00
Domenica | 12:00 – 23:00
```

---

## Menu Pizzumm (bacheca)

Voce **Menu Pizzumm** nel menu laterale.

- **Prodotti**: titolo, immagine in evidenza, prezzo, ingredienti, allergeni, etichetta
  (rossa / verde "veg" / gialla "novità"). L'ordine si imposta con *Attributi pagina → Ordine*.
- **Categorie**: nome, descrizione, campo **Ordine** e **ID immagine di copertina**.
  Le categorie senza prodotti non vengono mostrate.

---

## SEO

Title tag e meta description del brief sono già impostati per le 4 pagine, con campi
per sovrascriverli su ogni pagina/prodotto (box **SEO — titolo e descrizione**).
Il tema genera anche Open Graph, dati strutturati **Restaurant** e **FAQPage**.

Se installi Yoast, Rank Math, SEOPress o AIOSEO, il modulo SEO del tema si disattiva
da solo.

---

## Privacy e prestazioni

- **Font self-hosted** (Bebas Neue + Roboto + Yellowtail, licenza SIL OFL).
- **Mappa a consenso**: iframe di Google caricato solo dopo un clic.
- Immagini `lazy` con dimensioni dichiarate, animazioni disattivate con
  `prefers-reduced-motion`.

---

## Struttura

```
pizzumm/
├── style.css                 intestazione del tema
├── theme.json                palette, font e spaziature per Gutenberg
├── functions.php             setup, asset, supporti
├── header.php  footer.php    header e footer (footer dinamico)
├── front-page.php            Home (fallback PHP)
├── page-chi-siamo.php        Chi siamo (fallback PHP)
├── page-menu.php             Menu (fallback PHP)
├── page-contatti.php         Contatti (fallback PHP, con CF7)
├── page.php  index.php  single.php  404.php  searchform.php
├── template-parts/
│   └── pizza-card.php        card di un prodotto
├── inc/
│   ├── icons.php             set di icone SVG
│   ├── template-tags.php     helper e componenti
│   ├── customizer.php        opzioni residuali del Customizer
│   ├── theme-plugins.php     dichiarazione plugin richiesti (ACF + CF7)
│   ├── theme-options.php     pannello ACF "Tema Pizzumm" + sidebar footer
│   ├── shortcodes.php        shortcode delle sezioni dinamiche
│   ├── cpt-menu.php          CPT prodotti + categorie
│   ├── seo.php               title, meta, Open Graph, dati strutturati
│   ├── page-builders.php     compatibilità Elementor/Gutenberg + metabox layout
│   ├── block-patterns.php    sezioni Gutenberg + composizione pagine
│   └── demo-content.php      contenuti di partenza + CF7 default
└── assets/
    ├── css/main.css  css/fonts.css
    ├── js/main.js  js/motion.js  js/vendor/ (GSAP)
    ├── fonts/                Bebas Neue + Roboto + Yellowtail (woff2)
    └── img/                  illustrazioni SVG + stock/
```

---

## Direzione grafica

Impianto da **insegna di pizzeria**: hero rosso pieno, titoli condensati enormi,
sopratitoli corsivi, sezioni crema e menu a lista con tondi e prezzi rossi.

| Ruolo | Font |
|---|---|
| Insegne (titoli, pulsanti, prezzi, nav) | **Bebas Neue** |
| Testo | **Roboto** |
| Sopratitoli | **Yellowtail** (corsivo) |

**Colori**: la palette del logo è a gradiente, non a tinta piatta. Ogni colore ha
una tinta piena (testi, bordi, icone) e due stop (`--grad-rosso`, `--grad-giallo`,
`--grad-nero`…) usati su header, hero, pulsanti e fasce. Tutto modificabile dal
Customizer.

### GSAP

Il tema include **GSAP 3.15** (core + ScrollTrigger + SplitText) in
`assets/js/vendor/`, servito dal tema — licenza standard "no charge" di GreenSock.

| Effetto | Attributo |
|---|---|
| Titoli che salgono da una maschera, riga per riga | `data-split` |
| Comparse singole e a gruppi | `data-anim`, `data-anim-group` |

Con `prefers-reduced-motion` le animazioni non partono.

### Pulsante Glovo

Predisposto per il **logo ufficiale Glovo**: caricalo da *Personalizza → Ordini*,
prendendolo dal brand kit ufficiale. Il tema non include una riproduzione del marchio.

---

## Da completare prima del lancio

- [ ] Attiva **Advanced Custom Fields** e **Contact Form 7**
- [ ] Configura tab Hero, Footer e Copyright in **Tema Pizzumm**
- [ ] Popola i widget del footer in **Aspetto → Widget**
- [ ] Crea il menu legale (privacy, cookie) e assegnalo nel tab Copyright
- [ ] Logo definitivo (*Identità del sito*)
- [ ] URL Glovo + logo ufficiale
- [ ] Telefono, WhatsApp, e-mail, orari
- [ ] Link Instagram, TikTok, Facebook
- [ ] Ragione sociale e P. IVA
- [ ] Pagine Privacy policy e Cookie policy + banner cookie
- [ ] Prezzi reali e allergeni verificati di ogni prodotto
- [ ] Foto reali al posto degli scatti segnaposto
- [ ] Plugin SMTP per il modulo contatti
- [ ] Modulo CF7 personalizzato (o modifica il modulo predefinito)
