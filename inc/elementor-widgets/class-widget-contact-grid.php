<?php
/**
 * Widget "Contatti · Modulo + Info" — riproduce la sezione .contact-grid
 * del template PHP originale (2 colonne: form CF7 + info del locale).
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Contact_Grid extends Pizzumm_Widget_Base {

	public function get_name()  { return 'pizzumm_contact_grid'; }
	public function get_title() { return __( 'Pizzumm · Modulo + Info', 'pizzumm' ); }
	public function get_icon()  { return 'eicon-form-horizontal'; }

	protected function register_controls() {

		/* ---- COLONNA SINISTRA: Modulo ---- */
		$this->start_controls_section( 'form_col', array(
			'label' => __( 'Colonna modulo (sx)', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'form_kicker', array( 'label' => __( 'Sopratitolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Parliamone' ) );
		$this->add_control( 'form_title',  array( 'label' => __( 'Titolo', 'pizzumm' ),      'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Scrivici' ) );
		$this->add_control( 'form_text',   array( 'label' => __( 'Testo', 'pizzumm' ),       'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Hai una domanda? Lasciaci un messaggio e ti risponderemo appena possibile.' ) );
		$this->add_control( 'cf7_id',      array(
			'label'       => __( 'ID modulo Contact Form 7', 'pizzumm' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'default'     => '',
			'description' => __( 'Lascia vuoto per usare il modulo predefinito creato dal tema.', 'pizzumm' ),
		) );

		$this->end_controls_section();

		/* ---- COLONNA DESTRA: Info ---- */
		$this->start_controls_section( 'info_col', array(
			'label' => __( 'Colonna info (dx)', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'info_kicker', array( 'label' => __( 'Sopratitolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Dove e quando' ) );
		$this->add_control( 'info_title',  array( 'label' => __( 'Titolo', 'pizzumm' ),      'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Il locale' ) );

		$this->add_control( 'show_address',  array( 'label' => __( 'Indirizzo', 'pizzumm' ),  'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_phone',    array( 'label' => __( 'Telefono', 'pizzumm' ),   'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_whatsapp', array( 'label' => 'WhatsApp',                    'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_email',    array( 'label' => __( 'E-mail', 'pizzumm' ),     'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_hours',    array( 'label' => __( 'Orari', 'pizzumm' ),      'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_glovo',    array( 'label' => __( 'Link Glovo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_socials',  array( 'label' => __( 'Social', 'pizzumm' ),     'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );

		$this->end_controls_section();

		/* ---- STYLE ---- */
		$this->start_controls_section( 'style', array(
			'label' => __( 'Stile sezione', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		) );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array( 'name' => 'section_bg', 'types' => array( 'classic', 'gradient' ), 'selector' => '{{WRAPPER}} .pizzumm-contact' )
		);

		$this->add_control( 'kicker_color', array( 'label' => __( 'Colore sopratitoli', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .pizzumm-contact .kicker' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'title_color',  array( 'label' => __( 'Colore titoli', 'pizzumm' ),      'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .pizzumm-contact h2, {{WRAPPER}} .pizzumm-contact h3' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'text_color',   array( 'label' => __( 'Colore testo', 'pizzumm' ),       'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .pizzumm-contact, {{WRAPPER}} .pizzumm-contact .lead, {{WRAPPER}} .pizzumm-contact p, {{WRAPPER}} .pizzumm-contact .info-list' => 'color: {{VALUE}};' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$cf7_id = $s['cf7_id'] ? absint( $s['cf7_id'] ) : (int) get_option( 'pizzumm_default_cf7_id', 0 );

		$phone    = pizzumm_opt( 'phone' );
		$whatsapp = pizzumm_whatsapp_url();
		$email    = pizzumm_opt( 'email' );
		$hours    = pizzumm_hours_lines();
		$socials  = pizzumm_socials();
		?>
		<section class="pizzumm-contact section section--cream" id="scrivici">
			<div class="container">
				<div class="contact-grid">

					<!-- Colonna sinistra: modulo -->
					<div class="panel" data-anim>
						<?php if ( ! empty( $s['form_kicker'] ) ) : ?>
							<span class="kicker"><?php echo esc_html( $s['form_kicker'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $s['form_title'] ) ) : ?>
							<h2 class="section__title" style="font-size:var(--fs-2xl)"><?php echo esc_html( $s['form_title'] ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $s['form_text'] ) ) : ?>
							<p class="lead" style="margin-bottom:2rem"><?php echo esc_html( $s['form_text'] ); ?></p>
						<?php endif; ?>

						<?php if ( pizzumm_has_cf7() && $cf7_id ) : ?>
							<?php echo do_shortcode( '[contact-form-7 id="' . (int) $cf7_id . '"]' ); ?>
						<?php else : ?>
							<p class="alert alert--err"><?php esc_html_e( 'Modulo Contact Form 7 non configurato. Crealo da Contatto → Moduli.', 'pizzumm' ); ?></p>
						<?php endif; ?>
					</div>

					<!-- Colonna destra: info del locale -->
					<div data-anim>
						<?php if ( ! empty( $s['info_kicker'] ) ) : ?>
							<span class="kicker"><?php echo esc_html( $s['info_kicker'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $s['info_title'] ) ) : ?>
							<h2 class="section__title" style="font-size:var(--fs-2xl)"><?php echo esc_html( $s['info_title'] ); ?></h2>
						<?php endif; ?>

						<ul class="info-list">
							<?php if ( 'yes' === $s['show_address'] ) : ?>
								<li>
									<?php pizzumm_the_icon( 'pin' ); ?>
									<span>
										<span class="info-label"><?php esc_html_e( 'Indirizzo', 'pizzumm' ); ?></span>
										<a href="<?php echo esc_url( pizzumm_map_link_url() ); ?>" target="_blank" rel="noopener"><?php echo esc_html( pizzumm_opt( 'address' ) ); ?></a>
									</span>
								</li>
							<?php endif; ?>

							<?php if ( 'yes' === $s['show_phone'] && $phone ) : ?>
								<li>
									<?php pizzumm_the_icon( 'phone' ); ?>
									<span>
										<span class="info-label"><?php esc_html_e( 'Telefono', 'pizzumm' ); ?></span>
										<a href="<?php echo esc_url( pizzumm_tel_href( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
									</span>
								</li>
							<?php endif; ?>

							<?php if ( 'yes' === $s['show_whatsapp'] && $whatsapp ) : ?>
								<li>
									<?php pizzumm_the_icon( 'whatsapp' ); ?>
									<span>
										<span class="info-label">WhatsApp</span>
										<a href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Scrivici su WhatsApp', 'pizzumm' ); ?></a>
									</span>
								</li>
							<?php endif; ?>

							<?php if ( 'yes' === $s['show_email'] && $email ) : ?>
								<li>
									<?php pizzumm_the_icon( 'mail' ); ?>
									<span>
										<span class="info-label"><?php esc_html_e( 'E-mail', 'pizzumm' ); ?></span>
										<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
									</span>
								</li>
							<?php endif; ?>

							<?php if ( 'yes' === $s['show_hours'] && $hours ) : ?>
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

							<?php if ( 'yes' === $s['show_glovo'] ) : ?>
								<li>
									<?php pizzumm_the_icon( 'scooter' ); ?>
									<span>
										<span class="info-label"><?php esc_html_e( 'Consegna', 'pizzumm' ); ?></span>
										<a href="<?php echo esc_url( pizzumm_glovo_url() ); ?>"<?php echo pizzumm_glovo_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput ?>><?php esc_html_e( 'Ordina su Glovo', 'pizzumm' ); ?></a>
									</span>
								</li>
							<?php endif; ?>
						</ul>

						<?php if ( 'yes' === $s['show_socials'] && $socials ) : ?>
							<h3 style="margin-top:2rem;font-size:var(--fs-md)"><?php esc_html_e( 'Seguici', 'pizzumm' ); ?></h3>
							<ul class="socials">
								<?php foreach ( $socials as $label => $social ) : ?>
									<li>
										<a href="<?php echo esc_url( $social['url'] ); ?>" target="_blank" rel="noopener">
											<?php pizzumm_the_icon( $social['icon'] ); ?><?php echo esc_html( $label ); ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>

				</div>
			</div>
		</section>
		<?php
	}
}
