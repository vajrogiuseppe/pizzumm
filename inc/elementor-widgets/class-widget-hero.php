<?php
/**
 * Widget "Hero" della home — pizza tonda + kicker + h1 + testo + due pulsanti.
 *
 * @package Pizzumm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pizzumm_Widget_Hero extends Pizzumm_Widget_Base {

	public function get_name()  { return 'pizzumm_hero'; }
	public function get_title() { return __( 'Pizzumm · Hero home', 'pizzumm' ); }
	public function get_icon()  { return 'eicon-image-hotspot'; }

	protected function register_controls() {

		$this->start_controls_section( 'content', array(
			'label' => __( 'Contenuto', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		) );

		$this->add_control( 'kicker', array(
			'label'   => __( 'Sopratitolo', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => 'Pizza in teglia a Pompei',
		) );
		$this->add_control( 'title', array(
			'label'   => __( 'Titolo H1', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::TEXTAREA,
			'default' => 'Lo street food a Pompei ha 2.000 anni. Noi gli abbiamo dato una teglia nuova.',
		) );
		$this->add_control( 'text', array(
			'label'   => __( 'Testo introduttivo', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::TEXTAREA,
			'default' => 'Pizza in teglia leggera, croccante e pronta quando lo sei tu. Scegli la tua fetta e goditela, senza troppi giri.',
		) );

		$this->add_control( 'btn1_text', array( 'label' => __( 'Pulsante 1 · testo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Scopri il menu' ) );
		$this->add_control( 'btn1_url',  array( 'label' => __( 'Pulsante 1 · URL', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => array( 'url' => '/menu/' ) ) );
		$this->add_control( 'btn2_text', array( 'label' => __( 'Pulsante 2 · testo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Vieni a trovarci' ) );
		$this->add_control( 'btn2_url',  array( 'label' => __( 'Pulsante 2 · URL', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => array( 'url' => '/contatti/' ) ) );

		$this->add_control( 'hero_image', array(
			'label'   => __( 'Immagine pizza', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::MEDIA,
			'default' => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
		) );

		$this->add_control( 'hero_image_style', array(
			'label'   => __( 'Stile immagine', 'pizzumm' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'circle',
			'options' => array(
				'circle' => __( 'Foto tonda', 'pizzumm' ),
				'logo'   => __( 'Logo (PNG trasparente, senza ritaglio)', 'pizzumm' ),
			),
		) );

		$this->add_control( 'show_crumbs', array( 'label' => __( 'Pizze fluttuanti', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ) );
		$this->add_control( 'crumb1', array( 'label' => __( 'Pizza fluttuante 1', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'condition' => array( 'show_crumbs' => 'yes' ) ) );
		$this->add_control( 'crumb2', array( 'label' => __( 'Pizza fluttuante 2', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'condition' => array( 'show_crumbs' => 'yes' ) ) );
		$this->add_control( 'crumb3', array( 'label' => __( 'Pizza fluttuante 3', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'condition' => array( 'show_crumbs' => 'yes' ) ) );

		$this->end_controls_section();

		/* Style */
		$this->start_controls_section( 'style', array(
			'label' => __( 'Stile', 'pizzumm' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		) );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array( 'name' => 'bg', 'types' => array( 'classic', 'gradient' ), 'selector' => '{{WRAPPER}} .hero' )
		);

		$this->add_control( 'kicker_color', array( 'label' => __( 'Colore sopratitolo', 'pizzumm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .hero .kicker' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'title_color',  array( 'label' => __( 'Colore titolo', 'pizzumm' ),      'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .hero h1' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'text_color',   array( 'label' => __( 'Colore testo', 'pizzumm' ),       'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .hero .lead' => 'color: {{VALUE}};' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$img    = $s['hero_image']['url'] ?? '';
		$crumb1 = $s['crumb1']['url'] ?? '';
		$crumb2 = $s['crumb2']['url'] ?? '';
		$crumb3 = $s['crumb3']['url'] ?? '';
		$style  = ( $s['hero_image_style'] ?? 'circle' ) === 'logo' ? 'logo' : 'current';
		?>
		<section class="hero">
			<div class="container">
				<div class="hero__grid">
					<div>
						<?php if ( ! empty( $s['kicker'] ) ) : ?><span class="kicker"><?php echo esc_html( $s['kicker'] ); ?></span><?php endif; ?>
						<?php if ( ! empty( $s['title'] ) ) : ?><h1><?php echo esc_html( $s['title'] ); ?></h1><?php endif; ?>
						<?php if ( ! empty( $s['text'] ) ) : ?><p class="lead"><?php echo esc_html( $s['text'] ); ?></p><?php endif; ?>
						<div class="btn-row">
							<?php if ( ! empty( $s['btn1_text'] ) ) : ?>
								<a class="btn" href="<?php echo esc_url( $s['btn1_url']['url'] ?? '#' ); ?>"><?php echo esc_html( $s['btn1_text'] ); ?></a>
							<?php endif; ?>
							<?php if ( ! empty( $s['btn2_text'] ) ) : ?>
								<a class="btn btn--ghost" href="<?php echo esc_url( $s['btn2_url']['url'] ?? '#' ); ?>"><?php echo esc_html( $s['btn2_text'] ); ?></a>
							<?php endif; ?>
						</div>
					</div>
					<div class="hero__pizza hero__pizza--<?php echo esc_attr( $style ); ?>">
						<?php if ( $img ) : ?>
							<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo 'logo' === $style ? esc_attr__( 'Pizzumm — una pizza per fetta', 'pizzumm' ) : ''; ?>" width="1100" height="1100" fetchpriority="high" />
						<?php endif; ?>
						<?php if ( 'yes' === $s['show_crumbs'] ) : ?>
							<?php if ( $crumb1 ) : ?><img class="hero__crumb hero__crumb--1" src="<?php echo esc_url( $crumb1 ); ?>" alt="" width="300" height="300" /><?php endif; ?>
							<?php if ( $crumb2 ) : ?><img class="hero__crumb hero__crumb--2" src="<?php echo esc_url( $crumb2 ); ?>" alt="" width="300" height="300" /><?php endif; ?>
							<?php if ( $crumb3 ) : ?><img class="hero__crumb hero__crumb--3" src="<?php echo esc_url( $crumb3 ); ?>" alt="" width="300" height="300" /><?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
