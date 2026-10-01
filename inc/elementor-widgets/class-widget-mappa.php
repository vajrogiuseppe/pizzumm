<?php
/**
 * Widget "Mappa" — Google Maps embed con banner consenso opzionale.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Mappa extends Pizzumm_Widget_Base {

	public function get_name() {
		return 'pizzumm_mappa';
	}

	public function get_title() {
		return __( 'Pizzumm · Mappa', 'pizzumm' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array(
			'label' => __( 'Contenuto', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'query', array(
			'label'       => __( 'Indirizzo da cercare', 'pizzumm' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'default'     => '',
			'placeholder' => 'Piazza Vittorio Veneto 11, 80045 Pompei NA',
			'description' => __( 'Se vuoto usa l\'indirizzo del Customizer.', 'pizzumm' ),
		) );

		$this->add_control( 'consent', array(
			'label'   => __( 'Richiedi consenso prima di caricare', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::SWITCHER,
			'default' => '',
		) );

		$this->add_control( 'show_actions', array(
			'label'   => __( 'Mostra riga indirizzo + pulsante', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::SWITCHER,
			'default' => 'yes',
		) );

		$this->add_control( 'button_text', array(
			'label'     => __( 'Testo pulsante', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::TEXT,
			'default'   => __( 'Apri in Google Maps', 'pizzumm' ),
			'condition' => array( 'show_actions' => 'yes' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'style', array(
			'label' => __( 'Stile', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		) );

		$this->add_responsive_control( 'height', array(
			'label'      => __( 'Altezza mappa', 'pizzumm' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'range'      => array( 'px' => array( 'min' => 200, 'max' => 900 ) ),
			'default'    => array( 'unit' => 'px', 'size' => 450 ),
			'selectors'  => array( '{{WRAPPER}} .map-wrap' => 'min-height: {{SIZE}}{{UNIT}};' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$query = $s['query'] ?: pizzumm_opt( 'map_query' );
		$embed = get_theme_mod( 'pizzumm_map_embed', '' );
		$src   = $embed ? $embed : 'https://www.google.com/maps?q=' . rawurlencode( $query ) . '&output=embed';
		$link  = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $query );

		echo '<div class="pizzumm-map">';

		echo '<div class="map-wrap">';
		if ( 'yes' === $s['consent'] ) {
			echo '<div class="map-consent" data-map-consent data-map-src="' . esc_url( $src ) . '">';
			pizzumm_the_icon( 'map' );
			echo '<p>' . esc_html__( 'La mappa è fornita da Google. Caricandola accetti che Google possa raccogliere alcuni dati di navigazione.', 'pizzumm' ) . '</p>';
			echo '<button type="button" class="btn" data-map-load>' . esc_html__( 'Carica la mappa', 'pizzumm' ) . '</button>';
			echo '</div>';
		} else {
			echo '<iframe src="' . esc_url( $src ) . '" title="' . esc_attr__( 'Mappa Pizzumm', 'pizzumm' ) . '" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen style="width:100%;height:100%;border:0"></iframe>';
		}
		echo '</div>';

		if ( 'yes' === $s['show_actions'] ) {
			echo '<div class="map-actions">';
			echo '<p class="map-actions__address">';
			pizzumm_the_icon( 'pin' );
			echo esc_html( pizzumm_opt( 'address' ) );
			echo '</p>';
			printf( '<a class="btn btn--red" href="%s" target="_blank" rel="noopener">%s</a>', esc_url( $link ), esc_html( $s['button_text'] ) );
			echo '</div>';
		}

		echo '</div>';
	}
}
