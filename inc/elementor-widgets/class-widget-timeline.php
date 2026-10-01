<?php
/**
 * Widget "Timeline" — 3 riquadri numerati con titolo, testo e immagine (Chi siamo).
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Timeline extends Pizzumm_Widget_Base {

	public function get_name()  { return 'pizzumm_timeline'; }
	public function get_title() { return __( 'Pizzumm · Timeline', 'pizzumm' ); }
	public function get_icon()  { return 'eicon-time-line'; }

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Voci', 'pizzumm' ) ) );

		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'title', array( 'label' => __( 'Titolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA ) );
		$repeater->add_control( 'text',  array( 'label' => __( 'Testo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA ) );
		$repeater->add_control( 'image', array( 'label' => __( 'Immagine', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::MEDIA ) );

		$this->add_control( 'items', array(
			'label'   => __( 'Voci timeline', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::REPEATER,
			'fields'  => $repeater->get_controls(),
			'title_field' => '{{{ title }}}',
			'default' => array(
				array( 'title' => 'Prima dello street food come lo conosciamo, c’erano i thermopolia.', 'text' => 'Nelle strade dell’antica Pompei, i thermopolia erano punti di ristoro.', 'image' => array() ),
				array( 'title' => 'Una focaccia che ha fatto il giro del mondo.', 'text' => 'Nel 2023, gli scavi della Regio IX hanno restituito una natura morta con una focaccia piatta.', 'image' => array() ),
				array( 'title' => 'Il rito continua. Questa volta, in teglia.', 'text' => 'Oggi portiamo quella stessa idea di convivialità nel presente con un impasto contemporaneo.', 'image' => array() ),
			),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => __( 'Stile', 'pizzumm' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array( 'name' => 'bg', 'types' => array( 'classic' ), 'selector' => '{{WRAPPER}} .pizzumm-timeline-wrap' )
		);
		$this->add_control( 'index_color', array( 'label' => __( 'Colore numero', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .timeline__index' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'title_color', array( 'label' => __( 'Colore titolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .timeline__title' => 'color: {{VALUE}};' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		echo '<div class="pizzumm-timeline-wrap"><div class="container"><div class="timeline">';
		$n = 1;
		foreach ( $s['items'] ?? array() as $it ) {
			$img = $it['image']['url'] ?? '';
			printf(
				'<article class="timeline__item">
					<div>
						<span class="timeline__index">%d</span>
						<h2 class="timeline__title">%s</h2>
						<p class="lead">%s</p>
					</div>
					<div class="timeline__media">%s</div>
				</article>',
				$n,
				esc_html( $it['title'] ),
				esc_html( $it['text'] ),
				$img ? '<img src="' . esc_url( $img ) . '" alt="" width="1200" height="900" />' : ''
			);
			$n++;
		}
		echo '</div></div></div>';
	}
}
