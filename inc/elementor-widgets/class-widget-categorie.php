<?php
/**
 * Widget "Categorie menu Pizzumm".
 *
 * Griglia tonda con tutte le categorie di prodotti definite in bacheca
 * (Menu Pizzumm → Categorie). Ogni categoria è un cerchio con foto, nome e
 * conteggio prodotti.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Categorie extends Pizzumm_Widget_Base {

	public function get_name() {
		return 'pizzumm_categorie';
	}

	public function get_title() {
		return __( 'Pizzumm · Categorie', 'pizzumm' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	protected function register_controls() {
		$this->register_header_controls( 'header', 'Intestazione' );

		/* Layout */
		$this->start_controls_section( 'layout', array(
			'label' => __( 'Layout', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'link_target', array(
			'label'   => __( 'Ogni categoria linka a', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'menu-anchor',
			'options' => array(
				'menu-anchor' => __( 'Pagina Menu · ancora categoria', 'pizzumm' ),
				'none'        => __( 'Nessun link', 'pizzumm' ),
			),
		) );

		$this->end_controls_section();

		/* Style */
		$this->start_controls_section( 'style_bg', array(
			'label' => __( 'Sfondo sezione', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		) );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array( 'name' => 'section_bg', 'types' => array( 'classic', 'gradient' ), 'selector' => '{{WRAPPER}} .pizzumm-cats' )
		);

		$this->add_responsive_control( 'padding', array(
			'label'      => __( 'Padding', 'pizzumm' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'  => array( '{{WRAPPER}} .pizzumm-cats' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			'default'    => array( 'top' => '80', 'right' => '20', 'bottom' => '80', 'left' => '20', 'unit' => 'px', 'isLinked' => false ),
		) );

		$this->add_control( 'label_color', array(
			'label'     => __( 'Colore nome categoria', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .round__name' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'count_color', array(
			'label'     => __( 'Colore conteggio', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .round__count' => 'color: {{VALUE}};' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$cats = pizzumm_menu_categories();

		echo '<div class="pizzumm-cats">';
		echo '<div class="container">';

		$this->render_header();

		if ( ! $cats ) {
			echo '<p>' . esc_html__( 'Nessuna categoria trovata. Aggiungile in Menu Pizzumm → Categorie.', 'pizzumm' ) . '</p>';
			echo '</div></div>';
			return;
		}

		$link_to = ( $s['link_target'] ?? 'menu-anchor' ) === 'menu-anchor';

		echo '<ul class="rounds" data-stories data-anim-group>';
		foreach ( $cats as $cat ) {
			$href = $link_to ? pizzumm_page_url( 'menu' ) . '#cat-' . $cat->slug : '#';
			$tag  = $link_to ? 'a' : 'span';
			printf(
				'<li><%1$s class="round" %2$s>
					<img class="round__img" src="%3$s" alt="%4$s" width="300" height="300" loading="lazy" />
					<span class="round__name">%5$s</span>
					<span class="round__count">%6$s</span>
				</%1$s></li>',
				esc_attr( $tag ),
				$link_to ? 'href="' . esc_url( $href ) . '"' : '',
				esc_url( pizzumm_category_image( $cat, true ) ),
				esc_attr( $cat->name ),
				esc_html( $cat->name ),
				esc_html( sprintf( _n( '%d proposta', '%d proposte', $cat->count, 'pizzumm' ), absint( $cat->count ) ) )
			);
		}
		echo '</ul>';

		echo '</div></div>';
	}
}
