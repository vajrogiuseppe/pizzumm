<?php
/**
 * Widget "Blocco centrato stretto" — sezione con kicker + h2 + testo centrati,
 * ideale per "Perché Pizzumm" e simili.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Narrow extends Pizzumm_Widget_Base {

	public function get_name()  { return 'pizzumm_narrow'; }
	public function get_title() { return __( 'Pizzumm · Blocco centrato', 'pizzumm' ); }
	public function get_icon()  { return 'eicon-align-center-h'; }

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Contenuto', 'pizzumm' ) ) );

		$this->add_control( 'kicker', array( 'label' => __( 'Sopratitolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Sopratitolo' ) );
		$this->add_control( 'title',  array( 'label' => __( 'Titolo', 'pizzumm' ),      'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Titolo del blocco' ) );
		$this->add_control( 'text',   array( 'label' => __( 'Testo', 'pizzumm' ),       'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Testo del blocco.' ) );

		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => __( 'Stile', 'pizzumm' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array( 'name' => 'bg', 'types' => array( 'classic', 'gradient' ), 'selector' => '{{WRAPPER}} .pizzumm-narrow' )
		);
		$this->add_control( 'kicker_color', array( 'label' => __( 'Colore sopratitolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .kicker' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'title_color',  array( 'label' => __( 'Colore titolo', 'pizzumm' ),      'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} h2' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'text_color',   array( 'label' => __( 'Colore testo', 'pizzumm' ),       'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .lead' => 'color: {{VALUE}};' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="pizzumm-narrow">
			<div class="container container--narrow text-center">
				<?php if ( ! empty( $s['kicker'] ) ) : ?><span class="kicker"><?php echo esc_html( $s['kicker'] ); ?></span><?php endif; ?>
				<?php if ( ! empty( $s['title'] ) ) : ?><h2 class="section__title"><?php echo esc_html( $s['title'] ); ?></h2><?php endif; ?>
				<?php if ( ! empty( $s['text'] ) ) : ?><p class="lead" style="margin-inline:auto"><?php echo esc_html( $s['text'] ); ?></p><?php endif; ?>
			</div>
		</section>
		<?php
	}
}
