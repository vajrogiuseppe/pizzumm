<?php
/**
 * Footer del sito.
 *
 * Doppio comportamento:
 *  - se sono attive widget nelle sidebar "Footer · Colonna N", vengono usate
 *    quelle (permette al cliente di comporre il footer da Aspetto → Widget);
 *  - se una colonna è vuota, il tema mostra il contenuto predefinito coerente
 *    con il resto del sito (payoff, pagine, servizio, orari + social).
 *
 * @package Pizzumm
 */

$pizzumm_columns   = function_exists( 'pizzumm_footer_columns' ) ? pizzumm_footer_columns() : 4;
$pizzumm_prefooter = function_exists( 'pizzumm_prefooter_enabled' ) && pizzumm_prefooter_enabled();
$pizzumm_menu_id   = function_exists( 'pizzumm_copyright_menu_id' ) ? pizzumm_copyright_menu_id() : 0;

$pizzumm_phone   = pizzumm_opt( 'phone' );
$pizzumm_email   = pizzumm_opt( 'email' );
$pizzumm_socials = pizzumm_socials( true );
$pizzumm_hours   = pizzumm_hours_lines();
$pizzumm_company = pizzumm_opt( 'company' );
$pizzumm_privacy = get_theme_mod( 'pizzumm_privacy_url', '' );
$pizzumm_cookie  = get_theme_mod( 'pizzumm_cookie_url', '' );
$pizzumm_prefs   = get_theme_mod( 'pizzumm_cookie_prefs_selector', '' );

if ( ! $pizzumm_privacy && function_exists( 'get_privacy_policy_url' ) ) {
	$pizzumm_privacy = get_privacy_policy_url();
}
?>

</main><!-- #main -->

<footer class="site-footer">
	<div class="container">

		<?php if ( $pizzumm_prefooter && ( is_active_sidebar( 'prefooter-col-1' ) || is_active_sidebar( 'prefooter-col-2' ) ) ) : ?>
			<div class="footer__prefooter">
				<div class="prefooter__col"><?php dynamic_sidebar( 'prefooter-col-1' ); ?></div>
				<div class="prefooter__col"><?php dynamic_sidebar( 'prefooter-col-2' ); ?></div>
			</div>
		<?php endif; ?>

		<div class="footer__top footer__top--cols-<?php echo (int) $pizzumm_columns; ?>">

			<!-- Colonna 1: brand, recapiti, social (sempre dal tema) -->
			<div class="footer__col footer__brand">
				<img class="footer__logo" src="<?php echo esc_url( pizzumm_asset( 'img/brand/logo-pizzumm-bianco.png' ) ); ?>"
					alt="<?php esc_attr_e( 'Pizzumm — una pizza per fetta', 'pizzumm' ); ?>" width="833" height="900" loading="lazy" decoding="async" />

				<address><?php echo esc_html( pizzumm_opt( 'address_short' ) ); ?></address>

				<?php if ( $pizzumm_phone || $pizzumm_email ) : ?>
					<ul class="footer__contacts">
						<?php if ( $pizzumm_phone ) : ?>
							<li><a href="<?php echo esc_url( pizzumm_tel_href( $pizzumm_phone ) ); ?>"><?php pizzumm_the_icon( 'phone' ); ?><?php echo esc_html( $pizzumm_phone ); ?></a></li>
						<?php endif; ?>
						<?php if ( $pizzumm_email ) : ?>
							<li><a href="mailto:<?php echo esc_attr( $pizzumm_email ); ?>"><?php pizzumm_the_icon( 'mail' ); ?><?php echo esc_html( $pizzumm_email ); ?></a></li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>

				<ul class="socials" aria-label="<?php esc_attr_e( 'Seguici', 'pizzumm' ); ?>">
					<?php foreach ( $pizzumm_socials as $label => $social ) : ?>
						<li>
							<?php if ( $social['url'] ) : ?>
								<a href="<?php echo esc_url( $social['url'] ); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr( $label ); ?>">
							<?php else : // Link non ancora configurato nel Customizer: icona visibile ma non cliccabile. ?>
								<a aria-disabled="true" title="<?php echo esc_attr( $label ); ?>">
							<?php endif; ?>
								<?php pizzumm_the_icon( $social['icon'] ); ?>
								<span class="screen-reader-text"><?php echo esc_html( $label ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<?php if ( $pizzumm_columns >= 2 ) : ?>
				<!-- Colonna 2 -->
				<div class="footer__col">
					<?php if ( is_active_sidebar( 'footer-col-2' ) ) : ?>
						<?php dynamic_sidebar( 'footer-col-2' ); ?>
					<?php else : ?>
						<h3><?php esc_html_e( 'Pagine', 'pizzumm' ); ?></h3>
						<ul>
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'pizzumm' ); ?></a></li>
							<li><a href="<?php echo esc_url( pizzumm_page_url( 'chi-siamo' ) ); ?>"><?php esc_html_e( 'Chi siamo', 'pizzumm' ); ?></a></li>
							<li><a href="<?php echo esc_url( pizzumm_page_url( 'menu' ) ); ?>"><?php esc_html_e( 'Menu', 'pizzumm' ); ?></a></li>
							<li><a href="<?php echo esc_url( pizzumm_page_url( 'contatti' ) ); ?>"><?php esc_html_e( 'Contatti', 'pizzumm' ); ?></a></li>
						</ul>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $pizzumm_columns >= 3 ) : ?>
				<!-- Colonna 3 -->
				<div class="footer__col">
					<?php if ( is_active_sidebar( 'footer-col-3' ) ) : ?>
						<?php dynamic_sidebar( 'footer-col-3' ); ?>
					<?php else : ?>
						<h3><?php esc_html_e( 'Servizio', 'pizzumm' ); ?></h3>
						<ul>
							<li><a href="<?php echo esc_url( pizzumm_glovo_url() ); ?>"<?php echo pizzumm_glovo_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php esc_html_e( 'Ordina su Glovo', 'pizzumm' ); ?></a></li>
							<li><a href="<?php echo esc_url( pizzumm_page_url( 'menu' ) ); ?>"><?php esc_html_e( 'Consulta il menu', 'pizzumm' ); ?></a></li>
							<li><a href="<?php echo esc_url( pizzumm_map_link_url() ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Come raggiungerci', 'pizzumm' ); ?></a></li>
							<li><a href="<?php echo esc_url( pizzumm_page_url( 'contatti' ) ); ?>#scrivici"><?php esc_html_e( 'Scrivici', 'pizzumm' ); ?></a></li>
						</ul>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $pizzumm_columns >= 4 ) : ?>
				<!-- Colonna 4 -->
				<div class="footer__col">
					<?php if ( is_active_sidebar( 'footer-col-4' ) ) : ?>
						<?php dynamic_sidebar( 'footer-col-4' ); ?>
					<?php else : ?>
						<?php if ( $pizzumm_hours ) : ?>
							<h3><?php esc_html_e( 'Orari', 'pizzumm' ); ?></h3>
							<ul class="hours" style="margin-bottom:1.4rem">
								<?php foreach ( array_slice( $pizzumm_hours, 0, 4 ) as $pizzumm_line ) :
									$pizzumm_parts = array_map( 'trim', explode( '|', $pizzumm_line ) ); ?>
									<li>
										<span><?php echo esc_html( $pizzumm_parts[0] ); ?></span>
										<?php if ( isset( $pizzumm_parts[1] ) ) : ?>
											<span><?php echo esc_html( $pizzumm_parts[1] ); ?></span>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div><!-- .footer__top -->

		<div class="footer__bottom">
			<div class="footer__copyright">
				<?php echo pizzumm_copyright_html(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>

			<?php if ( $pizzumm_menu_id ) : ?>
				<?php wp_nav_menu( array( 'menu' => $pizzumm_menu_id, 'container' => false, 'menu_class' => 'footer__legal', 'depth' => 1, 'fallback_cb' => false ) ); ?>
			<?php elseif ( has_nav_menu( 'legal' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'legal', 'container' => false, 'menu_class' => 'footer__legal', 'depth' => 1, 'fallback_cb' => false ) ); ?>
			<?php elseif ( $pizzumm_privacy || $pizzumm_cookie || $pizzumm_prefs ) : ?>
				<ul class="footer__legal">
					<?php if ( $pizzumm_privacy ) : ?>
						<li><a href="<?php echo esc_url( $pizzumm_privacy ); ?>"><?php esc_html_e( 'Privacy policy', 'pizzumm' ); ?></a></li>
					<?php endif; ?>
					<?php if ( $pizzumm_cookie ) : ?>
						<li><a href="<?php echo esc_url( $pizzumm_cookie ); ?>"><?php esc_html_e( 'Cookie policy', 'pizzumm' ); ?></a></li>
					<?php endif; ?>
					<?php if ( $pizzumm_prefs ) : ?>
						<li><button type="button" data-cookie-prefs><?php esc_html_e( 'Preferenze cookie', 'pizzumm' ); ?></button></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
		</div>

	</div>
</footer>

<nav class="mobile-bar" aria-label="<?php esc_attr_e( 'Azioni rapide', 'pizzumm' ); ?>">
	<a href="<?php echo esc_url( pizzumm_page_url( 'menu' ) ); ?>">
		<?php pizzumm_the_icon( 'list' ); ?><?php esc_html_e( 'Menu', 'pizzumm' ); ?>
	</a>
	<a href="<?php echo esc_url( pizzumm_glovo_url() ); ?>"<?php echo pizzumm_glovo_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
		<?php pizzumm_the_icon( 'scooter' ); ?><?php esc_html_e( 'Ordina', 'pizzumm' ); ?>
	</a>
</nav>

<?php $pizzumm_wa = pizzumm_whatsapp_url(); ?>
<?php if ( $pizzumm_wa ) : ?>
	<a class="wa-float" href="<?php echo esc_url( $pizzumm_wa ); ?>" target="_blank" rel="noopener noreferrer"
		aria-label="<?php esc_attr_e( 'Scrivici su WhatsApp', 'pizzumm' ); ?>" title="<?php esc_attr_e( 'Scrivici su WhatsApp', 'pizzumm' ); ?>">
		<svg viewBox="0 0 32 32" aria-hidden="true" focusable="false"><path fill="currentColor" d="M16.04 3C8.86 3 3.03 8.82 3.03 16c0 2.3.6 4.54 1.74 6.52L3 29l6.64-1.74A12.95 12.95 0 0 0 16.04 29C23.2 29 29 23.18 29 16S23.2 3 16.04 3Zm0 23.8c-1.98 0-3.9-.53-5.6-1.53l-.4-.24-3.94 1.03 1.05-3.84-.26-.4A10.76 10.76 0 0 1 5.23 16c0-5.96 4.85-10.8 10.8-10.8 5.97 0 10.78 4.84 10.78 10.8 0 5.96-4.83 10.8-10.77 10.8Zm5.92-8.09c-.33-.16-1.93-.95-2.23-1.06-.3-.11-.52-.16-.74.16-.22.33-.85 1.06-1.04 1.28-.19.22-.38.25-.71.08-.33-.16-1.38-.51-2.63-1.62-.97-.87-1.63-1.94-1.82-2.27-.19-.33-.02-.5.14-.67.15-.15.33-.38.49-.57.16-.19.22-.33.33-.55.11-.22.05-.41-.03-.57-.08-.16-.74-1.78-1.01-2.44-.27-.64-.54-.55-.74-.56h-.63c-.22 0-.57.08-.87.41-.3.33-1.14 1.11-1.14 2.71s1.17 3.15 1.33 3.36c.16.22 2.3 3.5 5.56 4.91.78.34 1.39.54 1.86.69.78.25 1.49.21 2.05.13.63-.09 1.93-.79 2.2-1.55.27-.76.27-1.42.19-1.55-.08-.14-.3-.22-.63-.38Z"/></svg>
	</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
