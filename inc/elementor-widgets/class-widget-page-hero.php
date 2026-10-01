<?php
/**
 * Widget "Page Hero" — apertura pagine interne (kicker + h1 + testo + pulsante opz.).
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Page_Hero extends Pizzumm_Widget_Base {

	public function get_name()  { return 'pizzumm_page_hero'; }
	public function get_title() { return __( 'Pizzumm · Apertura pagina', 'pizzumm' ); }
	public function get_icon()  { return 'eicon-post-title'; }

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Contenuto', 'pizzumm' ) ) );

		$this->add_control( 'kicker', array( 'label' => __( 'Sopratitolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Sopratitolo' ) );
		$this->add_control( 'title',  array( 'label' => __( 'Titolo H1', 'pizzumm' ),   'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Titolo della pagina' ) );
		$this->add_control( 'text',   array( 'label' => __( 'Testo', 'pizzumm' ),       'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Breve testo di apertura.' ) );

		$this->add_control( 'btn_text', array( 'label' => __( 'Pulsante · testo (opz.)', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT ) );
		$this->add_control( 'btn_url',  array( 'label' => __( 'Pulsante · URL', 'pizzumm' ),           'type' => \Elementor\Controls_Manager::URL ) );

		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => __( 'Stile', 'pizzumm' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array( 'name' => 'bg', 'types' => array( 'classic', 'gradient' ), 'selector' => '{{WRAPPER}} .page-hero' )
		);
		$this->add_control( 'kicker_color', array( 'label' => __( 'Colore sopratitolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .page-hero .kicker' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'title_color',  array( 'label' => __( 'Colore titolo', 'pizzumm' ),      'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .page-hero h1' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'text_color',   array( 'label' => __( 'Colore testo', 'pizzumm' ),       'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .page-hero .lead' => 'color: {{VALUE}};' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="page-hero">
			<div class="container text-center">
				<?php if ( ! empty( $s['kicker'] ) ) : ?><span class="kicker"><?php echo esc_html( $s['kicker'] ); ?></span><?php endif; ?>
				<?php if ( ! empty( $s['title'] ) ) : ?><h1><?php echo esc_html( $s['title'] ); ?></h1><?php endif; ?>
				<?php if ( ! empty( $s['text'] ) ) : ?><p class="lead" style="margin-inline:auto"><?php echo esc_html( $s['text'] ); ?></p><?php endif; ?>
				<?php if ( ! empty( $s['btn_text'] ) ) : ?>
					<div class="btn-row btn-row--center">
						<a class="btn" href="<?php echo esc_url( $s['btn_url']['url'] ?? '#' ); ?>"><?php echo esc_html( $s['btn_text'] ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
