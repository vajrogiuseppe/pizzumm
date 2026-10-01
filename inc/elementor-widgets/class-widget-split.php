<?php
/**
 * Widget "Split" — immagine/i a sinistra + testo con perks e pulsanti a destra.
 *
 * Riproduce le sezioni "duo" e "storia" della home + le mini-storie della
 * pagina Chi siamo (timeline).
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Split extends Pizzumm_Widget_Base {

	public function get_name()  { return 'pizzumm_split'; }
	public function get_title() { return __( 'Pizzumm · Split (foto + testo)', 'pizzumm' ); }
	public function get_icon()  { return 'eicon-image-box'; }

	protected function register_controls() {
		$this->start_controls_section( 'media', array( 'label' => __( 'Foto', 'pizzumm' ) ) );

		$this->add_control( 'image1', array( 'label' => __( 'Foto 1', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::MEDIA ) );
		$this->add_control( 'image2', array( 'label' => __( 'Foto 2 (opzionale)', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::MEDIA ) );
		$this->add_control( 'media_side', array(
			'label'   => __( 'Lato foto', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::CHOOSE,
			'default' => 'left',
			'options' => array(
				'left'  => array( 'title' => __( 'Sinistra', 'pizzumm' ), 'icon' => 'eicon-h-align-left' ),
				'right' => array( 'title' => __( 'Destra', 'pizzumm' ),   'icon' => 'eicon-h-align-right' ),
			),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'content', array( 'label' => __( 'Testo', 'pizzumm' ) ) );

		$this->add_control( 'kicker', array( 'label' => __( 'Sopratitolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Scegli la tua fetta' ) );
		$this->add_control( 'title',  array( 'label' => __( 'Titolo H2', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Titolo della sezione' ) );
		$this->add_control( 'text1',  array( 'label' => __( 'Paragrafo 1', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA ) );
		$this->add_control( 'text2',  array( 'label' => __( 'Paragrafo 2 (opz.)', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA ) );

		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'text', array( 'label' => __( 'Voce', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT ) );
		$this->add_control( 'perks', array(
			'label'   => __( 'Elenco vantaggi (opz.)', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::REPEATER,
			'fields'  => $repeater->get_controls(),
			'title_field' => '{{{ text }}}',
		) );

		$this->add_control( 'btn1_text', array( 'label' => __( 'Pulsante 1 · testo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT ) );
		$this->add_control( 'btn1_url',  array( 'label' => __( 'Pulsante 1 · URL', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::URL ) );
		$this->add_control( 'btn2_text', array( 'label' => __( 'Pulsante 2 · testo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT ) );
		$this->add_control( 'btn2_url',  array( 'label' => __( 'Pulsante 2 · URL', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::URL ) );

		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => __( 'Stile', 'pizzumm' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array( 'name' => 'bg', 'types' => array( 'classic', 'gradient' ), 'selector' => '{{WRAPPER}} .pizzumm-split-wrap' )
		);

		$this->add_control( 'kicker_color', array( 'label' => __( 'Colore sopratitolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .kicker' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'title_color',  array( 'label' => __( 'Colore titolo', 'pizzumm' ),      'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} h2' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'text_color',   array( 'label' => __( 'Colore testo', 'pizzumm' ),       'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .lead' => 'color: {{VALUE}};' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$img1 = $s['image1']['url'] ?? '';
		$img2 = $s['image2']['url'] ?? '';
		$reversed = ( $s['media_side'] ?? 'left' ) === 'right';

		echo '<div class="pizzumm-split-wrap"><div class="container"><div class="split' . ( $reversed ? ' split--reversed' : '' ) . '">';

		$media = '<div class="split__media">';
		if ( $img1 && $img2 ) {
			$media .= '<div class="split__pair">';
			$media .= '<img src="' . esc_url( $img1 ) . '" alt="" width="1200" height="900" />';
			$media .= '<img src="' . esc_url( $img2 ) . '" alt="" width="1200" height="900" />';
			$media .= '</div>';
		} elseif ( $img1 ) {
			$media .= '<img src="' . esc_url( $img1 ) . '" alt="" width="1200" height="1500" />';
		}
		$media .= '</div>';

		ob_start();
		echo '<div>';
		if ( ! empty( $s['kicker'] ) ) echo '<span class="kicker">' . esc_html( $s['kicker'] ) . '</span>';
		if ( ! empty( $s['title'] ) )  echo '<h2 class="section__title">' . esc_html( $s['title'] ) . '</h2>';
		if ( ! empty( $s['text1'] ) )  echo '<p class="lead">' . esc_html( $s['text1'] ) . '</p>';
		if ( ! empty( $s['text2'] ) )  echo '<p class="lead">' . esc_html( $s['text2'] ) . '</p>';

		if ( ! empty( $s['perks'] ) ) {
			echo '<ul class="perks">';
			foreach ( $s['perks'] as $p ) {
				echo '<li>';
				pizzumm_the_icon( 'arrow' );
				echo esc_html( $p['text'] );
				echo '</li>';
			}
			echo '</ul>';
		}

		if ( ! empty( $s['btn1_text'] ) || ! empty( $s['btn2_text'] ) ) {
			echo '<div class="btn-row">';
			if ( ! empty( $s['btn1_text'] ) ) {
				printf( '<a class="btn btn--red" href="%s">%s</a>',
					esc_url( $s['btn1_url']['url'] ?? '#' ), esc_html( $s['btn1_text'] ) );
			}
			if ( ! empty( $s['btn2_text'] ) ) {
				printf( '<a class="btn btn--ghost" href="%s">%s</a>',
					esc_url( $s['btn2_url']['url'] ?? '#' ), esc_html( $s['btn2_text'] ) );
			}
			echo '</div>';
		}
		echo '</div>';
		$text = ob_get_clean();

		echo $reversed ? $text . $media : $media . $text;

		echo '</div></div></div>';
	}
}
