# Pizzumm — deploy e modifiche future

Sito: https://www.pizzumm.it (WordPress su Aruba, pagine costruite con Elementor)
Tema: questa cartella (`Desktop/Siti - Portfolio/demos/pizzumm`)
Repository: https://github.com/vajrogiuseppe/pizzumm (pubblico, account personale `vajrogiuseppe`)

---

## Come si pubblica una modifica

1. Fai la modifica nei file del tema.
2. Alza la versione in `style.css` (`Version: 1.4.4` → `1.4.5`, ecc.).
3. Commit e zip della release:
   ```
   git add -A && git commit -m "Descrizione della modifica"
   git archive --format=zip --prefix=pizzumm/ -o ~/Desktop/pizzumm.zip HEAD
   ```
4. Push e release (il numero della release = la versione in `style.css`):
   ```
   git push
   gh release create 1.4.5 ~/Desktop/pizzumm.zip --repo vajrogiuseppe/pizzumm --target main --title "1.4.5" --notes "Descrizione"
   ```
5. WordPress si aggiorna da solo entro circa un'ora.
   Per farlo subito: **Bacheca → Aggiornamenti → "Controlla di nuovo" → seleziona Pizzumm → "Aggiorna temi"**.

L'allegato della release **deve chiamarsi `pizzumm.zip`** e contenere la cartella `pizzumm/`: è quello che il tema scarica.

### Come funziona

`inc/github-updater.php` controlla l'ultima release di `vajrogiuseppe/pizzumm`.
Se la versione è più alta di quella installata, WordPress propone l'aggiornamento.
Gli aggiornamenti automatici del tema sono forzati dal codice (su Aruba il link "Attiva aggiornamenti automatici" non compare).
Niente plugin, niente token: il repository è pubblico.

### Se l'aggiornamento non compare

- Verifica che la release esista e abbia l'allegato `pizzumm.zip`:
  `gh release view --repo vajrogiuseppe/pizzumm`
- Verifica la versione online:
  `curl -s https://www.pizzumm.it/wp-content/themes/pizzumm/style.css | grep Version`
- Piano B: **Aspetto → Temi → Aggiungi nuovo → Carica tema** → `pizzumm.zip` → "Sostituisci quello attuale".

---

## Attenzione

- **Non cambiare `PIZZUMM_VERSION`** in `functions.php`: se cambia, il tema rigenera da zero le pagine Elementor e si perdono le modifiche fatte nell'editor.
  Per svuotare la cache di CSS/JS si alza invece `PIZZUMM_ASSETS_VERSION`.
- Immagini e impostazioni delle pagine Elementor stanno nel database: cambiare i default dei widget non basta.
  Per aggiornare contenuti già salvati si aggiunge una revisione in `inc/content-updates.php` (gira una volta sola al primo caricamento del sito).
- Le immagini nuove del brand stanno in `assets/img/brand/`.
- FTP Aruba (`ftp.pizzumm.it`, utente `11005274@aruba.it`): il login viene rifiutato, probabilmente per il filtro accessi FTP del pannello Aruba. Per ora non si usa.

---

## Dove si cambiano le cose

| Cosa | Dove |
|---|---|
| Telefono, WhatsApp, email, indirizzo | Personalizza → Tema Pizzumm (o default in `inc/template-tags.php`) |
| Link social (Facebook, Instagram, TikTok, Tripadvisor) | Personalizza → Social |
| Pulsante Glovo nella barra del menu | Personalizza → Ordini (Glovo) → "Mostra il pulsante Glovo nella barra del menu" |
| Link Glovo dei pulsanti "Ordina su Glovo" | Personalizza → Ordini (Glovo) → URL della pagina Glovo |
| Copyright del footer | Personalizza → Footer → Testo del copyright |
| Sezione "Scegli da dove partire" (nascosta) | Elementor, pagina Home → widget Categorie → Avanzate → Responsive |
| Testi e immagini della home | Elementor, pagina Home |
| Foto di Chi siamo | `inc/section-shortcodes.php` → `pizzumm_render_sec_about_timeline()` |
| Pennellate sui fondi rossi | `assets/img/brand/brush-left.png`, `brush-right.png` (desktop), `brush-h.png` (mobile) + CSS in fondo a `assets/css/main.css` |
| Immagine di condivisione (WhatsApp, Facebook…) | `assets/img/brand/og-pizzumm.jpg` (1200×630), usata da `inc/seo.php` |
| Giallo del brand (`#FDD125`) | `--giallo` in `assets/css/main.css`, `pizzumm_palette()` in `inc/template-tags.php`, `theme.json` |
| Box del brand, formule Smile, Pizzummino Kids | `inc/section-shortcodes.php` → `pizzumm_render_sec_menu_boxes()`, `pizzumm_render_sec_menu_formule()` |
| Elenco prodotti del menu (nascosto) | Elementor, pagina Menu → widget Shortcode `menu_grid` → Avanzate → Responsive |
| Perché Pizzumm (Chi siamo e Home) | `inc/section-shortcodes.php` → `pizzumm_render_sec_about_nome()` |

---

## Da fare

- [ ] Foto del thermopolium per Chi siamo, punto 1 (ora c'è la foto delle teglie)
- [ ] Link dei social in Personalizza → Social (ora le icone non sono cliccabili)
- [ ] Creare la casella info@pizzumm.it su Aruba
- [ ] Link Glovo quando l'account è attivo, e riattivare il pulsante nella barra del menu
- [ ] Sviluppare e riattivare "Scegli da dove partire"
- [ ] Riattivare l'elenco prodotti del menu quando servirà
- [ ] Prezzo del Pizzummino Kids (ora "da verificare in locale")

---

## Storico versioni

| Versione | Modifiche |
|---|---|
| 1.4.0 | Modifiche di Terry: logo e favicon, pennellate del packaging, foto nuove in home, footer con dati societari e social, WhatsApp fisso, layout Contatti, categorie nascoste, pulsante Glovo tolto dal menu |
| 1.4.1 | Aggiornamenti del tema dalle release GitHub |
| 1.4.2 | Chi siamo: foto del banco al punto 3; aggiornamenti automatici forzati |
| 1.4.3 | Chi siamo: foto delle teglie al punto 1 |
| 1.4.4 | "Controlla di nuovo" rilegge sempre GitHub (cache di un'ora) |
| 1.4.5 | Pennellate orizzontali in alto su mobile (testo spostato sotto); logo come immagine di condivisione (Open Graph) |
| 1.4.6 | Modifiche di Terry (novembre): giallo #FDD125, nuovo logo "per(fetta)" e favicon, sottotitolo hero in evidenza, testi home (vantaggi, nastro, sezione menu, CTA), "Perché Pizzumm" senza titolo e ripetuto in home, Menu con box del brand, formule Smile e Pizzummino Kids, elenco prodotti nascosto (revisione contenuti 2) |
| 1.4.7 | Accenti delle maiuscole non più tagliati nei titoli animati (es. FELICITÀ, PIÙ) |
