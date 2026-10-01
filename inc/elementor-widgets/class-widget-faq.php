<?php
/**
 * Widget "FAQ" — accordion domande/risposte.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Faq extends Pizzumm_Widget_Base {

	public function get_name()  { return 'pizzumm_faq'; }
	public function get_title() { return __( 'Pizzumm · FAQ', 'pizzumm' ); }
	public function get_icon()  { return 'eicon-help'; }

	protected function register_controls() {
		$this->register_header_controls();

		$this->start_controls_section( 'content', array( 'label' => __( 'Domande', 'pizzumm' ) ) );

		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'q', array( 'label' => __( 'Domanda', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT ) );
		$repeater->add_control( 'a', array( 'label' => __( 'Risposta', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA ) );

		$this->add_control( 'items', array(
			'label'   => __( 'FAQ', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::REPEATER,
			'fields'  => $repeater->get_controls(),
			'title_field' => '{{{ q }}}',
			'default' => array(
				array( 'q' => 'Posso ordinare dal tavolo?', 'a' => 'Sì. Nel locale puoi ordinare e pagare in digitale seguendo le indicazioni presenti sul tavolo.' ),
				array( 'q' => 'Fate consegna a domicilio?', 'a' => 'Sì. Puoi ordinare Pizzumm tramite Glovo.' ),
				array( 'q' => 'Posso ordinare da asporto?', 'a' => 'Sì. Verifica su Glovo le modalità disponibili oppure contattaci.' ),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		echo '<div class="container container--narrow">';
		$this->render_header();
		echo '<div class="faq">';
		foreach ( $s['items'] ?? array() as $it ) {
			printf( '<details><summary>%s</summary><div class="faq__a"><p>%s</p></div></details>',
				esc_html( $it['q'] ), esc_html( $it['a'] ) );
		}
		echo '</div></div>';
	}
}
