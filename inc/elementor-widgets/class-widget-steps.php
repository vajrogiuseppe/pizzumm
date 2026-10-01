<?php
/**
 * Widget "Steps" — 3 riquadri numerati.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Steps extends Pizzumm_Widget_Base {

	public function get_name()  { return 'pizzumm_steps'; }
	public function get_title() { return __( 'Pizzumm · Passi numerati', 'pizzumm' ); }
	public function get_icon()  { return 'eicon-number-field'; }

	protected function register_controls() {
		$this->register_header_controls();

		$this->start_controls_section( 'content', array( 'label' => __( 'Passi', 'pizzumm' ) ) );

		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'title', array( 'label' => __( 'Titolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Titolo passo' ) );
		$repeater->add_control( 'text',  array( 'label' => __( 'Testo', 'pizzumm' ),  'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Descrizione passo' ) );

		$this->add_control( 'items', array(
			'label'   => __( 'Passi', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::REPEATER,
			'fields'  => $repeater->get_controls(),
			'default' => array(
				array( 'title' => 'Scansiona',       'text' => 'Inquadra il codice che trovi sul tavolo.' ),
				array( 'title' => 'Scegli',          'text' => 'Sfoglia il menu e componi la tua selezione di fette.' ),
				array( 'title' => 'Ordina e paga',   'text' => 'Confermi, paghi dal telefono e resti seduto.' ),
			),
			'title_field' => '{{{ title }}}',
		) );

		$this->add_control( 'closing', array( 'label' => __( 'Chiusura', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Facile, veloce, Pizzumm.' ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		echo '<div class="container">';
		$this->render_header();
		echo '<ul class="steps">';
		$n = 1;
		foreach ( $s['items'] ?? array() as $it ) {
			printf( '<li class="step"><span class="step__num">%d</span><h3>%s</h3><p>%s</p></li>',
				$n, esc_html( $it['title'] ), esc_html( $it['text'] ) );
			$n++;
		}
		echo '</ul>';
		if ( ! empty( $s['closing'] ) ) {
			printf( '<p class="text-center" style="margin-top:2rem"><span class="kicker" style="font-size:clamp(1.6rem,1.3rem + 1.4vw,2.4rem)">%s</span></p>',
				esc_html( $s['closing'] ) );
		}
		echo '</div>';
	}
}
