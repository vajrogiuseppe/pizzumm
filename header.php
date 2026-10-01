<?php
/**
 * Header del sito.
 *
 * @package Pizzumm
 */

$pizzumm_phone = pizzumm_opt( 'phone' );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Vai al contenuto', 'pizzumm' ); ?></a>

<header class="site-header" id="site-header">
	<div class="container header__inner">

		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php // Pittogramma del brand (versione chiara, su barra rossa). ?>
			<img class="brand__logo" src="<?php echo esc_url( pizzumm_asset( 'img/brand/pittogramma-chiaro.png' ) ); ?>"
				alt="<?php esc_attr_e( 'Pizzumm — una pizza per fetta', 'pizzumm' ); ?>" width="219" height="240" />
		</a>

		<nav class="nav" id="site-nav" aria-label="<?php esc_attr_e( 'Menu principale', 'pizzumm' ); ?>">
			<?php pizzumm_primary_nav(); ?>
		</nav>

		<?php if ( $pizzumm_phone ) : ?>
			<a class="header__phone" href="<?php echo esc_url( pizzumm_tel_href( $pizzumm_phone ) ); ?>">
				<?php pizzumm_the_icon( 'phone' ); ?><span><?php echo esc_html( $pizzumm_phone ); ?></span>
			</a>
		<?php endif; ?>

		<?php // Spento finché l'account Glovo non è attivo: si riattiva da Personalizza → Ordini (Glovo). ?>
		<?php if ( pizzumm_glovo_in_header() ) : ?>
			<span class="header__cta header__cta--desktop">
				<?php pizzumm_glovo_button( __( 'Ordina su', 'pizzumm' ), 'yellow', 'btn--sm' ); ?>
			</span>
		<?php endif; ?>

		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
			<span></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Apri il menu', 'pizzumm' ); ?></span>
		</button>

	</div>
</header>

<main id="main">
