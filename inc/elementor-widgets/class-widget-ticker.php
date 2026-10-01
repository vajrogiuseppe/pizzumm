<?php
/**
 * Widget "Ticker" — nastro scorrevole con frasi ripetute.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Ticker extends Pizzumm_Widget_Base {

	public function get_name()  { return 'pizzumm_ticker'; }
	public function get_title() { return __( 'Pizzumm · Nastro scorrevole', 'pizzumm' ); }
	public function get_icon()  { return 'eicon-navigation-horizontal'; }

	protected function register_controls() {

		$this->start_controls_section( 'content', array( 'label' => __( 'Contenuto', 'pizzumm' ) ) );

		$repeater = new \Elementor\Repeater();
		$repeater->add_control( 'text', array(
			'label' => __( 'Frase', 'pizzumm' ),
			'type'  => \Elementor\Controls_Manager::TEXT,
			'default' => 'Una pizza per fetta',
		) );

		$this->add_control( 'items', array(
			'label'   => __( 'Frasi', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::REPEATER,
			'fields'  => $repeater->get_controls(),
			'default' => array(
				array( 'text' => 'Una pizza per fetta' ),
				array( 'text' => 'Pizza in teglia' ),
				array( 'text' => 'Nel cuore di Pompei' ),
				array( 'text' => 'Facile, veloce, Pizzumm' ),
				array( 'text' => 'Ordina e paga dal tavolo' ),
			),
			'title_field' => '{{{ text }}}',
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => __( 'Stile', 'pizzumm' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ) );

		$this->add_control( 'bg_color', array(
			'label'     => __( 'Sfondo', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .ticker' => 'background: {{VALUE}};' ),
		) );
		$this->add_control( 'text_color', array(
			'label'     => __( 'Colore testo', 'pizzumm' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} .ticker span' => 'color: {{VALUE}};' ),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$items = $s['items'] ?? array();
		?>
		<div class="ticker" aria-hidden="true">
			<div class="ticker__track">
				<?php for ( $i = 0; $i < 2; $i++ ) : ?>
					<div class="ticker__group">
						<?php foreach ( $items as $it ) : ?>
							<span><?php echo esc_html( $it['text'] ); ?></span>
							<?php pizzumm_the_icon( 'sparkle' ); ?>
						<?php endforeach; ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php
	}
}
