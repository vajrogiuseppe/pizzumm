<?php
/**
 * Widget "Facts" — tre riquadri icona + strong + testo.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Facts extends Pizzumm_Widget_Base {

	public function get_name()  { return 'pizzumm_facts'; }
	public function get_title() { return __( 'Pizzumm · Vantaggi (3 riquadri)', 'pizzumm' ); }
	public function get_icon()  { return 'eicon-icon-box'; }

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Contenuto', 'pizzumm' ) ) );

		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'icon', array(
			'label'   => __( 'Icona', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'slice',
			'options' => array( 'slice' => 'Slice', 'wheat' => 'Grano', 'scooter' => 'Scooter', 'clock' => 'Orologio', 'phone' => 'Telefono', 'sparkle' => 'Sparkle' ),
		) );
		$repeater->add_control( 'title', array( 'label' => __( 'Titolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Titolo fatto' ) );
		$repeater->add_control( 'text',  array( 'label' => __( 'Testo', 'pizzumm' ),  'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Breve testo di supporto.' ) );

		$this->add_control( 'items', array(
			'label'   => __( 'Vantaggi', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::REPEATER,
			'fields'  => $repeater->get_controls(),
			'default' => array(
				array( 'icon' => 'slice',   'title' => 'Una pizza per fetta', 'text' => 'Scegli con gli occhi, paghi quello che prendi.' ),
				array( 'icon' => 'wheat',   'title' => 'Impasto contemporaneo', 'text' => 'Leggero fuori, croccante sotto.' ),
				array( 'icon' => 'scooter', 'title' => 'Pizzumm dove vuoi', 'text' => 'A casa, in ufficio o con gli amici, con Glovo.' ),
			),
			'title_field' => '{{{ title }}}',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => __( 'Stile', 'pizzumm' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'icon_color',  array( 'label' => __( 'Colore icona', 'pizzumm' ),  'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .fact svg' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'title_color', array( 'label' => __( 'Colore titolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .fact strong' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'text_color',  array( 'label' => __( 'Colore testo', 'pizzumm' ),  'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .fact > span > span' => 'color: {{VALUE}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		echo '<div class="container"><ul class="hero__facts">';
		foreach ( $s['items'] ?? array() as $it ) {
			echo '<li class="fact">';
			pizzumm_the_icon( $it['icon'] );
			printf( '<span><strong>%s</strong><span>%s</span></span></li>',
				esc_html( $it['title'] ), esc_html( $it['text'] ) );
		}
		echo '</ul></div>';
	}
}
