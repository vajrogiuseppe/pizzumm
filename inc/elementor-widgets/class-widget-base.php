<?php
/**
 * Classe base per i widget custom Pizzumm.
 *
 * Ogni widget del tema estende questa classe e ottiene di default un blocco
 * "Header" (kicker + heading + testo) editabile nativamente dall'editor di
 * Elementor. Ogni valore ha controllo di tipografia, colore e allineamento
 * separato, così l'utente cambia bg, font, colori direttamente dall'editor.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Pizzumm_Widget_Base extends \Elementor\Widget_Base {

	public function get_categories() {
		return array( 'pizzumm' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	/**
	 * Aggiunge un blocco di controlli "Intestazione sezione" (kicker + heading + testo).
	 */
	protected function register_header_controls( $section_id = 'section_header', $section_label = 'Intestazione' ) {
		$this->start_controls_section(
			$section_id,
			array(
				'label' => __( $section_label, 'pizzumm' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'show_kicker', array(
			'label'        => __( 'Mostra sopratitolo', 'pizzumm' ),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'label_on'     => __( 'Sì', 'pizzumm' ),
			'label_off'    => __( 'No', 'pizzumm' ),
		) );

		$this->add_control( 'kicker', array(
			'label'     => __( 'Sopratitolo', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::TEXT,
			'default'   => 'Sopratitolo',
			'condition' => array( 'show_kicker' => 'yes' ),
		) );

		$this->add_control( 'show_title', array(
			'label'     => __( 'Mostra titolo', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'label_on'  => __( 'Sì', 'pizzumm' ),
			'label_off' => __( 'No', 'pizzumm' ),
		) );

		$this->add_control( 'title', array(
			'label'     => __( 'Titolo', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::TEXT,
			'default'   => 'Titolo della sezione',
			'condition' => array( 'show_title' => 'yes' ),
		) );

		$this->add_control( 'title_tag', array(
			'label'   => __( 'Tag del titolo', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'h2',
			'options' => array( 'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4' ),
			'condition' => array( 'show_title' => 'yes' ),
		) );

		$this->add_control( 'text', array(
			'label'   => __( 'Testo introduttivo', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::TEXTAREA,
			'default' => '',
		) );

		$this->add_control( 'align', array(
			'label'   => __( 'Allineamento', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::CHOOSE,
			'default' => 'left',
			'options' => array(
				'left'   => array( 'title' => __( 'Sinistra', 'pizzumm' ), 'icon' => 'eicon-text-align-left' ),
				'center' => array( 'title' => __( 'Centro', 'pizzumm' ),   'icon' => 'eicon-text-align-center' ),
				'right'  => array( 'title' => __( 'Destra', 'pizzumm' ),   'icon' => 'eicon-text-align-right' ),
			),
			'selectors' => array(
				'{{WRAPPER}} .pizzumm-header' => 'text-align: {{VALUE}};',
			),
		) );

		$this->end_controls_section();

		/* ---- STYLE tab ---- */
		$this->start_controls_section(
			$section_id . '_style',
			array(
				'label' => __( 'Stile intestazione', 'pizzumm' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control( 'kicker_color', array(
			'label'     => __( 'Colore sopratitolo', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .pizzumm-header__kicker' => 'color: {{VALUE}};' ),
		) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array( 'name' => 'kicker_typography', 'selector' => '{{WRAPPER}} .pizzumm-header__kicker' )
		);

		$this->add_control( 'title_color', array(
			'label'     => __( 'Colore titolo', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .pizzumm-header__title' => 'color: {{VALUE}};' ),
		) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array( 'name' => 'title_typography', 'selector' => '{{WRAPPER}} .pizzumm-header__title' )
		);

		$this->add_control( 'text_color', array(
			'label'     => __( 'Colore testo', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .pizzumm-header__text' => 'color: {{VALUE}};' ),
		) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array( 'name' => 'text_typography', 'selector' => '{{WRAPPER}} .pizzumm-header__text' )
		);

		$this->end_controls_section();
	}

	/**
	 * Renderizza l'intestazione (kicker + titolo + testo) dai settings.
	 */
	protected function render_header() {
		$s = $this->get_settings_for_display();

		echo '<div class="pizzumm-header">';

		if ( 'yes' === ( $s['show_kicker'] ?? '' ) && ! empty( $s['kicker'] ) ) {
			echo '<span class="pizzumm-header__kicker kicker">' . esc_html( $s['kicker'] ) . '</span>';
		}
		if ( 'yes' === ( $s['show_title'] ?? '' ) && ! empty( $s['title'] ) ) {
			$tag = ! empty( $s['title_tag'] ) ? $s['title_tag'] : 'h2';
			printf( '<%1$s class="pizzumm-header__title section__title">%2$s</%1$s>', esc_attr( $tag ), esc_html( $s['title'] ) );
		}
		if ( ! empty( $s['text'] ) ) {
			echo '<p class="pizzumm-header__text lead">' . wp_kses_post( nl2br( $s['text'] ) ) . '</p>';
		}

		echo '</div>';
	}
}
