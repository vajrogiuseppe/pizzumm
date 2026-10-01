<?php
/**
 * Widget "Info locale" — elenco contatti, orari, social letti dal Customizer.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Info extends Pizzumm_Widget_Base {

	public function get_name() {
		return 'pizzumm_info_locale';
	}

	public function get_title() {
		return __( 'Pizzumm · Info locale', 'pizzumm' );
	}

	public function get_icon() {
		return 'eicon-info-circle';
	}

	protected function register_controls() {
		$this->start_controls_section( 'options', array(
			'label' => __( 'Cosa mostrare', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_address', array( 'label' => __( 'Indirizzo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_phone',   array( 'label' => __( 'Telefono', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_whatsapp', array( 'label' => 'WhatsApp',                'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_email',   array( 'label' => __( 'E-mail', 'pizzumm' ),   'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_hours',   array( 'label' => __( 'Orari', 'pizzumm' ),    'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_glovo',   array( 'label' => __( 'Link Glovo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'show_socials', array( 'label' => __( 'Social', 'pizzumm' ),   'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );

		$this->end_controls_section();

		$this->start_controls_section( 'style', array(
			'label' => __( 'Stile', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'icon_color', array(
			'label'     => __( 'Colore icone', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .info-list svg' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'label_color', array(
			'label'     => __( 'Colore etichette', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .info-label' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'link_color', array(
			'label'     => __( 'Colore link', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .info-list a' => 'color: {{VALUE}};' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$phone    = pizzumm_opt( 'phone' );
		$whatsapp = pizzumm_whatsapp_url();
		$email    = pizzumm_opt( 'email' );
		$hours    = pizzumm_hours_lines();
		$socials  = pizzumm_socials();

		echo '<div class="pizzumm-info"><ul class="info-list">';

		if ( 'yes' === $s['show_address'] ) {
			echo '<li>';
			pizzumm_the_icon( 'pin' );
			echo '<span><span class="info-label">' . esc_html__( 'Indirizzo', 'pizzumm' ) . '</span>';
			printf( '<a href="%s" target="_blank" rel="noopener">%s</a>', esc_url( pizzumm_map_link_url() ), esc_html( pizzumm_opt( 'address' ) ) );
			echo '</span></li>';
		}
		if ( 'yes' === $s['show_phone'] && $phone ) {
			echo '<li>';
			pizzumm_the_icon( 'phone' );
			printf( '<span><span class="info-label">%s</span><a href="%s">%s</a></span></li>',
				esc_html__( 'Telefono', 'pizzumm' ), esc_url( pizzumm_tel_href( $phone ) ), esc_html( $phone ) );
		}
		if ( 'yes' === $s['show_whatsapp'] && $whatsapp ) {
			echo '<li>';
			pizzumm_the_icon( 'whatsapp' );
			printf( '<span><span class="info-label">WhatsApp</span><a href="%s" target="_blank" rel="noopener">%s</a></span></li>',
				esc_url( $whatsapp ), esc_html__( 'Scrivici su WhatsApp', 'pizzumm' ) );
		}
		if ( 'yes' === $s['show_email'] && $email ) {
			echo '<li>';
			pizzumm_the_icon( 'mail' );
			printf( '<span><span class="info-label">%s</span><a href="mailto:%s">%s</a></span></li>',
				esc_html__( 'E-mail', 'pizzumm' ), esc_attr( $email ), esc_html( $email ) );
		}
		if ( 'yes' === $s['show_hours'] && $hours ) {
			echo '<li>';
			pizzumm_the_icon( 'clock' );
			echo '<span style="flex:1"><span class="info-label">' . esc_html__( 'Orari', 'pizzumm' ) . '</span><ul class="hours">';
			foreach ( $hours as $line ) {
				$parts = array_map( 'trim', explode( '|', $line ) );
				echo '<li><span>' . esc_html( $parts[0] ) . '</span>';
				if ( isset( $parts[1] ) ) {
					echo '<span>' . esc_html( $parts[1] ) . '</span>';
				}
				echo '</li>';
			}
			echo '</ul></span></li>';
		}
		if ( 'yes' === $s['show_glovo'] ) {
			echo '<li>';
			pizzumm_the_icon( 'scooter' );
			printf( '<span><span class="info-label">%s</span><a href="%s"%s>%s</a></span></li>',
				esc_html__( 'Consegna', 'pizzumm' ), esc_url( pizzumm_glovo_url() ), pizzumm_glovo_attrs(), esc_html__( 'Ordina su Glovo', 'pizzumm' ) );
		}
		echo '</ul>';

		if ( 'yes' === $s['show_socials'] && $socials ) {
			echo '<h3 style="margin-top:2rem;font-size:var(--fs-md)">' . esc_html__( 'Seguici', 'pizzumm' ) . '</h3>';
			echo '<ul class="socials">';
			foreach ( $socials as $label => $social ) {
				printf( '<li><a href="%s" target="_blank" rel="noopener">', esc_url( $social['url'] ) );
				pizzumm_the_icon( $social['icon'] );
				echo esc_html( $label ) . '</a></li>';
			}
			echo '</ul>';
		}

		echo '</div>';
	}
}
