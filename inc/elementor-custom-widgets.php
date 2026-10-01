<?php
/**
 * Widget Elementor custom del tema Pizzumm.
 *
 * Ogni widget appare nell'inseritore di Elementor sotto la categoria "Pizzumm"
 * ed espone controlli nativi per personalizzare testi, sfondi, colori,
 * tipografia, allineamento, spaziature — senza dover uscire dall'editor.
 *
 * Widget disponibili:
 *  - pizzumm_categorie      · griglia tonda delle categorie del menu
 *  - pizzumm_menu_prodotti  · menu completo (nav + categorie + prodotti)
 *  - pizzumm_mappa          · mappa Google embed con banner consenso
 *  - pizzumm_info_locale    · elenco contatti + orari + social
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra la categoria "Pizzumm" nell'inseritore di Elementor.
 *
 * @param \Elementor\Elements_Manager $elements_manager Manager.
 */
function pizzumm_elementor_widget_category( $elements_manager ) {
	$elements_manager->add_category(
		'pizzumm',
		array(
			'title' => __( 'Pizzumm', 'pizzumm' ),
			'icon'  => 'fa fa-plug',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'pizzumm_elementor_widget_category' );

/**
 * Registra i widget custom.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Manager.
 */
function pizzumm_register_elementor_widgets( $widgets_manager ) {
	$dir = PIZZUMM_DIR . '/inc/elementor-widgets/';

	require_once $dir . 'class-widget-base.php';
	require_once $dir . 'class-widget-hero.php';
	require_once $dir . 'class-widget-page-hero.php';
	require_once $dir . 'class-widget-narrow.php';
	require_once $dir . 'class-widget-ticker.php';
	require_once $dir . 'class-widget-facts.php';
	require_once $dir . 'class-widget-split.php';
	require_once $dir . 'class-widget-steps.php';
	require_once $dir . 'class-widget-cta.php';
	require_once $dir . 'class-widget-timeline.php';
	require_once $dir . 'class-widget-faq.php';
	require_once $dir . 'class-widget-categorie.php';
	require_once $dir . 'class-widget-menu.php';
	require_once $dir . 'class-widget-mappa.php';
	require_once $dir . 'class-widget-info.php';
	require_once $dir . 'class-widget-contact-grid.php';

	$widgets_manager->register( new \Pizzumm_Widget_Hero() );
	$widgets_manager->register( new \Pizzumm_Widget_Page_Hero() );
	$widgets_manager->register( new \Pizzumm_Widget_Narrow() );
	$widgets_manager->register( new \Pizzumm_Widget_Ticker() );
	$widgets_manager->register( new \Pizzumm_Widget_Facts() );
	$widgets_manager->register( new \Pizzumm_Widget_Split() );
	$widgets_manager->register( new \Pizzumm_Widget_Steps() );
	$widgets_manager->register( new \Pizzumm_Widget_Cta() );
	$widgets_manager->register( new \Pizzumm_Widget_Timeline() );
	$widgets_manager->register( new \Pizzumm_Widget_Faq() );
	$widgets_manager->register( new \Pizzumm_Widget_Categorie() );
	$widgets_manager->register( new \Pizzumm_Widget_Menu() );
	$widgets_manager->register( new \Pizzumm_Widget_Mappa() );
	$widgets_manager->register( new \Pizzumm_Widget_Info() );
	$widgets_manager->register( new \Pizzumm_Widget_Contact_Grid() );
}
add_action( 'elementor/widgets/register', 'pizzumm_register_elementor_widgets' );
