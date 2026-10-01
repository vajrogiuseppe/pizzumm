<?php
/**
 * Widget "CTA band" — kicker + titolo + testo + 2 pulsanti su sfondo immagine.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Cta extends Pizzumm_Widget_Base {

	public function get_name()  { return 'pizzumm_cta_band'; }
	public function get_title() { return __( 'Pizzumm · CTA banda', 'pizzumm' ); }
	public function get_icon()  { return 'eicon-call-to-action'; }

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Contenuto', 'pizzumm' ) ) );

		$this->add_control( 'kicker', array( 'label' => __( 'Sopratitolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Pizzumm ti aspetta' ) );
		$this->add_control( 'title',  array( 'label' => __( 'Titolo', 'pizzumm' ),      'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Hai già scelto? La parte facile inizia adesso.' ) );
		$this->add_control( 'text',   array( 'label' => __( 'Testo', 'pizzumm' ),       'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Ordina su Glovo per ricevere Pizzumm dove vuoi, oppure vieni a scegliere le tue fette direttamente al banco.' ) );

		$this->add_control( 'btn1_text', array( 'label' => __( 'Pulsante 1 · testo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Ordina su Glovo' ) );
		$this->add_control( 'btn1_url',  array( 'label' => __( 'Pulsante 1 · URL', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );
		$this->add_control( 'btn2_text', array( 'label' => __( 'Pulsante 2 · testo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Come raggiungerci' ) );
		$this->add_control( 'btn2_url',  array( 'label' => __( 'Pulsante 2 · URL', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => array( 'url' => '/contatti/' ) ) );

		$this->add_control( 'bg_image', array( 'label' => __( 'Immagine di sfondo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::MEDIA ) );

		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => __( 'Stile', 'pizzumm' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );

		$this->add_control( 'section_bg', array( 'label' => __( 'Sfondo esterno', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .pizzumm-cta-wrap' => 'background: {{VALUE}};' ) ) );
		$this->add_control( 'kicker_color', array( 'label' => __( 'Colore sopratitolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .cta-band .kicker' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'title_color',  array( 'label' => __( 'Colore titolo', 'pizzumm' ),      'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .cta-band h2' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'text_color',   array( 'label' => __( 'Colore testo', 'pizzumm' ),       'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .cta-band .lead' => 'color: {{VALUE}};' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$bg = $s['bg_image']['url'] ?? pizzumm_asset( 'img/stock/menu-diavola.jpg' );
		?>
		<div class="pizzumm-cta-wrap"><div class="container">
			<div class="cta-band">
				<div class="cta-band__bg" aria-hidden="true">
					<img src="<?php echo esc_url( $bg ); ?>" alt="" width="1200" height="900" />
				</div>
				<div>
					<?php if ( ! empty( $s['kicker'] ) ) : ?><span class="kicker"><?php echo esc_html( $s['kicker'] ); ?></span><?php endif; ?>
					<?php if ( ! empty( $s['title'] ) ) : ?><h2><?php echo esc_html( $s['title'] ); ?></h2><?php endif; ?>
					<?php if ( ! empty( $s['text'] ) ) : ?><p class="lead"><?php echo esc_html( $s['text'] ); ?></p><?php endif; ?>
				</div>
				<div class="btn-row">
					<?php if ( ! empty( $s['btn1_text'] ) ) : ?><a class="btn btn--lg" href="<?php echo esc_url( $s['btn1_url']['url'] ?? '#' ); ?>"><?php echo esc_html( $s['btn1_text'] ); ?></a><?php endif; ?>
					<?php if ( ! empty( $s['btn2_text'] ) ) : ?><a class="btn btn--ghost" href="<?php echo esc_url( $s['btn2_url']['url'] ?? '#' ); ?>"><?php echo esc_html( $s['btn2_text'] ); ?></a><?php endif; ?>
				</div>
			</div>
		</div></div>
		<?php
	}
}
