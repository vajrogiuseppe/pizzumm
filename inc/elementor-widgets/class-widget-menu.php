<?php
/**
 * Widget "Menu prodotti Pizzumm".
 *
 * Menu completo con navigazione categorie + lista prodotti per ogni categoria.
 * Legge dai CPT "Menu Pizzumm".
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Menu extends Pizzumm_Widget_Base {

	public function get_name() {
		return 'pizzumm_menu_prodotti';
	}

	public function get_title() {
		return __( 'Pizzumm · Menu prodotti', 'pizzumm' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	protected function register_controls() {
		$this->start_controls_section( 'options', array(
			'label' => __( 'Opzioni', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'show_nav', array(
			'label'   => __( 'Mostra nav categorie in alto', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::SWITCHER,
			'default' => 'yes',
		) );

		$this->add_control( 'show_allergen_note', array(
			'label'   => __( 'Mostra nota allergeni in fondo', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::SWITCHER,
			'default' => 'yes',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'style', array(
			'label' => __( 'Stile', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		) );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array( 'name' => 'bg', 'types' => array( 'classic' ), 'selector' => '{{WRAPPER}} .pizzumm-menu' )
		);

		$this->add_control( 'cat_title_color', array(
			'label'     => __( 'Colore titolo categoria', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .menu-cat__title' => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'price_color', array(
			'label'     => __( 'Colore prezzo', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .dish__price' => 'color: {{VALUE}};' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$cats = pizzumm_menu_categories();

		echo '<div class="pizzumm-menu">';

		if ( ! $cats ) {
			echo '<div class="container"><p class="lead">' . esc_html__( 'Il menu è in aggiornamento. Aggiungi i prodotti da "Menu Pizzumm" nella bacheca.', 'pizzumm' ) . '</p></div>';
			echo '</div>';
			return;
		}

		if ( 'yes' === ( $s['show_nav'] ?? '' ) ) {
			echo '<nav class="menu-nav" aria-label="' . esc_attr__( 'Categorie del menu', 'pizzumm' ) . '"><div class="container menu-nav__inner">';
			foreach ( $cats as $cat ) {
				printf( '<a href="#cat-%1$s">%2$s</a>', esc_attr( $cat->slug ), esc_html( $cat->name ) );
			}
			echo '</div></nav>';
		}

		echo '<div class="container">';

		foreach ( $cats as $cat ) {
			$products = pizzumm_category_products( $cat );
			if ( ! $products ) {
				continue;
			}
			printf(
				'<section class="menu-cat" id="cat-%1$s">
					<div class="menu-cat__head">
						<span class="kicker">%2$s</span>
						<h2 class="menu-cat__title">%3$s</h2>%4$s
					</div><ul class="menu-list">',
				esc_attr( $cat->slug ),
				esc_html__( 'il nostro menu', 'pizzumm' ),
				esc_html( $cat->name ),
				$cat->description ? '<p class="menu-cat__desc">' . esc_html( $cat->description ) . '</p>' : ''
			);
			foreach ( $products as $pizzumm_product ) {
				include PIZZUMM_DIR . '/template-parts/pizza-card.php';
			}
			echo '</ul></section>';
		}

		if ( 'yes' === ( $s['show_allergen_note'] ?? '' ) ) {
			echo '<p class="allergen-note">';
			pizzumm_the_icon( 'info' );
			echo '<span>' . esc_html__( 'Alcuni prodotti possono contenere allergeni. Per informazioni dettagliate chiedi al banco.', 'pizzumm' ) . '</span></p>';
		}

		echo '</div></div>';
	}
}
